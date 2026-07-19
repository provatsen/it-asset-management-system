<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

require_once 'config/db.php';

// Recently added products - Updated to 10 items
$stmt = $pdo->query("SELECT id, name, supplier, purchase_date, price, factory_name FROM products ORDER BY id DESC LIMIT 10");
$products = $stmt->fetchAll();

// Expiring warranty (within 30 days)
$stmt = $pdo->query("SELECT name, warranty, purchase_date FROM products WHERE DATE_ADD(purchase_date, INTERVAL warranty MONTH) <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)");
$expiring = $stmt->fetchAll();

// Totals
$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalValue = $pdo->query("SELECT SUM(price) FROM products")->fetchColumn();

// Top concern (factory name)
$stmt = $pdo->query("SELECT factory_name, COUNT(*) as total FROM products GROUP BY factory_name ORDER BY total DESC LIMIT 1");
$topFactory = $stmt->fetch();
$topConcern = $topFactory ? $topFactory['factory_name'] : 'N/A';

// All concern-wise totals with SUM of prices
$concernCounts = $pdo->query("
    SELECT factory_name, COUNT(*) AS total, SUM(price) AS total_value
    FROM products 
    GROUP BY factory_name 
    ORDER BY total DESC
")->fetchAll();

// Top 5 asset users
$stmt = $pdo->query("
    SELECT e.name, COUNT(a.id) AS total 
    FROM asset_assignments a
    JOIN employees e ON a.employee_id = e.id
    GROUP BY e.id 
    ORDER BY total DESC LIMIT 5
");
$topUsers = $stmt->fetchAll();

// Get total value per unit for the distribution column
$unitStats = $pdo->query("
    SELECT 
        factory_name,
        COUNT(*) AS item_count,
        SUM(price) AS total_value
    FROM products 
    GROUP BY factory_name 
    ORDER BY item_count DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | IT-Asset Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/favicon.ico" type="image/x-icon">
    
    <!-- Roboto Font Import -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* Apply Roboto font globally */
        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        
        /* Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        
        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Stat Cards */
        .stat-card-top {
            background: white;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .stat-card-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 24px;
        }
        
        .icon-blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; }
        .icon-green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .icon-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; }
        .icon-red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        
        .border-blue { border-left: 5px solid #3b82f6; }
        .border-green { border-left: 5px solid #10b981; }
        .border-purple { border-left: 5px solid #8b5cf6; }
        .border-red { border-left: 5px solid #ef4444; }
        
        /* Section Cards */
        .section-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .card-header {
            border-bottom: 1px solid #e2e8f0;
        }
        
        /* Product Items */
        .product-item {
            background: white;
            border-radius: 12px;
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }
        
        .product-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }
        
        .badge-unit {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: #0369a1;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #bae6fd;
        }
        
        /* Distribution Column - Updated */
        .scrollable-content {
            max-height: 400px;
            overflow-y: auto;
            padding: 15px;
        }
        
        .user-row {
            padding: 15px;
            margin-bottom: 10px;
            background: white;
            border-radius: 12px;
            border-left: 5px solid;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .user-row:hover {
            background: #f8fafc;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .unit-details {
            flex-grow: 1;
            margin-left: 15px;
        }
        
        .unit-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .unit-stats {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
        }
        
        .unit-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stat-count {
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
        }
        
        .stat-value {
            background: #ecfdf5;
            color: #047857;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 500;
        }
        
        /* Top Users */
        .user-badge {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            border: 1px solid #fcd34d;
        }
        
        /* Animation delays for better UX */
        .animate__animated {
            animation-duration: 0.5s;
        }
        
        /* Custom scrollbar */
        .scrollable-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .scrollable-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .section-card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body>

<?php include_once 'includes/header.php'; ?>

<div class="dashboard-header">
    <div class="container-main">
        <div class="row align-items-center animate__animated animate__fadeIn">
            <div class="col-md-6">
                <h2 class="fw-bold mb-1"><i class="fas fa-chart-line me-2"></i>Asset Dashboard</h2>
                <p class="text-white" style="opacity: 0.9;">Real-time IT Asset Metrics & Inventory Analysis</p>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-white-50">Last updated: <?= date('F j, Y g:i A') ?></span>
            </div>
        </div>
    </div>
</div>

<div class="container-main">
    <!-- Top Stats Bar with Full Amounts & Animations -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3 animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
            <div class="stat-card-top border-blue">
                <div class="stat-icon icon-blue"><i class="fas fa-boxes"></i></div>
                <div>
                    <p class="text-muted small mb-0">Total Item (Checkin)</p>
                    <h4 class="fw-bold mb-0"><?= number_format($totalProducts) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
            <div class="stat-card-top border-green">
                <div class="stat-icon icon-green"><i class="fas fa-wallet"></i></div>
                <div>
                    <p class="text-muted small mb-0">Inventory Value BDT</p>
                    <h4 class="fw-bold mb-0 full-amount"><?= number_format($totalValue, 0) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
            <div class="stat-card-top border-purple">
                <div class="stat-icon icon-purple"><i class="fas fa-university"></i></div>
                <div>
                    <p class="text-muted small mb-0">Best User Unit</p>
                    <h4 class="fw-bold mb-0 text-truncate" style="max-width:120px; font-size: 1.5rem;"><?= htmlspecialchars($topConcern) ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 animate__animated animate__zoomIn" style="animation-delay: 0.4s;">
            <div class="stat-card-top border-red">
                <div class="stat-icon icon-red"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <p class="text-muted small mb-0">Warranty Risk</p>
                    <h4 class="fw-bold mb-0 text-danger"><?= count($expiring) ?> <span class="small fw-normal">Items</span></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recently Added Products (Updated to 10) -->
        <div class="col-lg-8 animate__animated animate__fadeInUp">
            <div class="card section-card">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <span class="h5 mb-0 fw-bold"><i class="fas fa-clock-rotate-left me-2 text-primary"></i>Recent Acquisitions</span>
                    <a href="products/view_all_products.php" class="btn btn-sm btn-outline-primary rounded-pill">View Inventory</a>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <?php foreach ($products as $index => $product): ?>
                            <div class="col-12 animate__animated animate__fadeInUp" style="animation-delay: <?= ($index * 0.1) ?>s;">
                                <div class="product-item p-3 d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-blue-subtle text-primary p-3 rounded-circle me-3" style="background:#eef2ff">
                                            <i class="fas fa-laptop"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($product['name']) ?></h6>
                                            <div class="small text-muted mt-1">
                                                <span class="me-3"><i class="fas fa-truck me-1"></i><?= htmlspecialchars($product['supplier']) ?></span>
                                                <span><i class="far fa-calendar-alt me-1"></i><?= date('d M, Y', strtotime($product['purchase_date'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success mb-1">BDT <?= number_format($product['price'], 0) ?></div>
                                        <span class="badge-unit"><?= htmlspecialchars($product['factory_name']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distributions Column - UPDATED WITH VALUE -->
        <div class="col-lg-4">
            <div class="row g-4">
                <!-- Items by Concern - Updated with Total Value -->
                <div class="col-12 animate__animated animate__fadeInRight">
                    <div class="card section-card">
                        <div class="card-header border-0 bg-white py-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold h6 mb-0"><i class="fas fa-chart-pie me-2 text-info"></i>Asset Checkin By Unit</span>
                            
                        </div>
                        <div class="card-body p-0">
                            <div class="scrollable-content">
                                <?php foreach ($unitStats as $index => $unit): ?>
                                    <div class="user-row animate__animated animate__fadeInRight" 
                                         style="border-left-color: #3b82f6; animation-delay: <?= ($index * 0.05) ?>s;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-2 rounded-circle">
                                                <i class="fas fa-building text-info"></i>
                                            </div>
                                            <div class="unit-details">
                                                <div class="unit-name"><?= htmlspecialchars($unit['factory_name'] ?: 'Unassigned') ?></div>
                                                <div class="unit-stats">
                                                    <div class="unit-stat">
                                                        <i class="fas fa-box text-muted"></i>
                                                        <span class="stat-count"><?= $unit['item_count'] ?> items</span>
                                                    </div>
                                                    <div class="unit-stat">
                                                        <i class="fas fa-coins text-muted"></i>
                                                        <span class="stat-value">BDT <?= number_format($unit['total_value'], 0) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="badge bg-primary rounded-pill px-3 py-2">
                                                <i class="fas fa-percentage me-1"></i>
                                                <?= $totalValue > 0 ? number_format(($unit['total_value'] / $totalValue) * 100, 1) : 0 ?>%
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="p-3 bg-light border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Showing <?= count($unitStats) ?> units
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Asset Users -->
                <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                    <div class="card section-card">
                        <div class="card-header border-0 bg-white py-3">
                            <span class="fw-bold h6 mb-0"><i class="fas fa-user-shield me-2 text-warning"></i>Key Custodians</span>
                        </div>
                        <div class="card-body p-3 pt-0">
                            <?php foreach ($topUsers as $index => $user): ?>
                                <div class="d-flex align-items-center justify-content-between p-3 mb-2 bg-light rounded-4 border-start border-4 border-warning animate__animated animate__fadeInRight" 
                                     style="animation-delay: <?= ($index * 0.1) ?>s;">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <div class="bg-white p-2 rounded-circle me-3 shadow-sm">
                                                <i class="fas fa-user-tie text-warning"></i>
                                            </div>
                                                </div>
                                        <div>
                                            <div class="small fw-bold"><?= htmlspecialchars($user['name']) ?></div>
                                            <small class="text-muted">Custodian</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="user-badge d-block mb-1"><?= $user['total'] ?> items</span>
                                        <small class="text-muted">Managed</small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <?php include_once 'includes/footer.php'; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        // Animate elements on scroll
        const animatedElements = document.querySelectorAll('.animate__animated');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.visibility = 'visible';
                    entry.target.classList.add('animate__fadeIn');
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            observer.observe(element);
        });
        
        // Format numbers with commas
        document.querySelectorAll('.full-amount').forEach(el => {
            const num = parseInt(el.textContent.replace(/,/g, ''));
            el.textContent = num.toLocaleString('en-US');
        });
    });
</script>
</body>
</html>