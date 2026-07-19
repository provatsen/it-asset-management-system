<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Initialize pagination variables
$per_page = 15;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $per_page;
$total_items = 0;
$total_pages = 1;
$expired_products = [];
$error = '';

try {
    // Count total expired products
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE purchase_date IS NOT NULL AND warranty IS NOT NULL AND DATE_ADD(purchase_date, INTERVAL warranty MONTH) < NOW()");
    $count_stmt->execute();
    $total_items = $count_stmt->fetchColumn();
    $total_pages = ceil($total_items / $per_page);

    // Fetch paginated expired products
    if ($total_items > 0) {
        $stmt = $pdo->prepare("SELECT *, 
                              DATE_FORMAT(DATE_ADD(purchase_date, INTERVAL warranty MONTH), '%Y-%m-%d') AS expired_on,
                              DATEDIFF(NOW(), DATE_ADD(purchase_date, INTERVAL warranty MONTH)) AS days_expired
                              FROM products 
                              WHERE purchase_date IS NOT NULL AND warranty IS NOT NULL 
                              AND DATE_ADD(purchase_date, INTERVAL warranty MONTH) < NOW()
                              ORDER BY expired_on DESC
                              LIMIT :offset, :per_page");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        $expired_products = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT-Asset Database|Expire Warranty</title>
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
        :root {
            --primary-color: #1a365d;
            --secondary-color: #3182ce;
            --danger-color: #e53e3e;
            --success-color: #38a169;
            --warning-color: #dd6b20;
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-500: #718096;
            --gray-700: #4a5568;
            }

            html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            }
            
            body > .main-container {
            flex: 1;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            }

      

        .main-container {
            flex: 1;
            padding: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .page-title {
            color: var(--primary-color);
            margin: 1rem 0 1.75rem;
            font-weight: 700;
            font-size: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-error {
            background-color: rgba(229, 62, 62, 0.1);
            color: var(--danger-color);
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
            background-color: white;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.1rem;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            background-color: var(--gray-100);
            font-weight: 600;
            white-space: nowrap;
            padding: 1rem 1.25rem;
            color: var(--gray-700);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table td {
            padding: 0.875rem 1.25rem;
            vertical-align: middle;
            border-top: 1px solid var(--gray-200);
            color: var(--gray-700);
        }

        .table tr:hover td {
            background-color: rgba(237, 242, 247, 0.5);
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .badge-expired {
            background-color: var(--danger-color);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3.5rem;
            color: var(--gray-200);
            margin-bottom: 1.25rem;
            opacity: 0.7;
        }

        .empty-state h4 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--gray-700);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 2rem 0 1rem;
        }

        .page-item.active .page-link {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .page-link {
            color: var(--primary-color);
            padding: 0.5rem 0.875rem;
            border-radius: 0.375rem !important;
            margin: 0 0.15rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-print {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-print:hover {
            background-color: #153e75;
            transform: translateY(-1px);
        }

        .btn-home {
            background-color: var(--success-color);
            color: white;
        }

        .btn-home:hover {
            background-color: #2f855a;
            transform: translateY(-1px);
        }

        .count-badge {
        background-color: var(--danger-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.875rem;
        margin-left: 0.75rem;
        }

    @media (max-width: 768px) {
        .header {
            font-size: 1.25rem;
            padding: 1rem;
        }
        
        .main-container {
            padding: 1.25rem;
        }
        
        .page-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .table th, .table td {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
    
    @media print {
        * {
            box-sizing: border-box;
        }
    
        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
            background: white;
            font-size: 10pt;
            overflow: visible;
        }
    
        .no-print {
            display: none !important;
        }
    
        .main-container {
            padding: 0;
            margin: 0;
        }
    
        .card {
            box-shadow: none;
            border: none;
        }
    
        .page-title,
        .card-header {
            color: black !important;
            background-color: transparent !important;
            -webkit-print-color-adjust: exact;
        }
    
        .table-responsive {
            overflow: visible !important;
        }
    
        .table {
            width: 100% !important;
            table-layout: fixed;
            word-wrap: break-word;
        }
    
        .table th,
        .table td {
            white-space: normal;
            text-align: middle
            font-size: 10pt;
            color: black !important;
            background-color: white !important;
            border: 1px solid #ccc;
            padding: 6px 8px;
            -webkit-print-color-adjust: exact;
        }
        .table th:nth-child(1),
        .table td:nth-child(1) {
            width: 40px;
            text-align: center;
        }
    
        .status-badge {
            font-weight: bold;
            color: #e53e3e !important;
            background-color: #fde8e8 !important;
            padding: 3px 6px;
            border-radius: 4px;
        }
    
        .pagination-container,
        .action-buttons {
            display: none !important;
        }
    }
</style>
</head>
<div class="no-print">
<?php include '../includes/header.php'; ?>
</div>
<body>
<div class="main-container">
        <h2 class="page-title">
            <i class="fas fa-exclamation-triangle me-2"></i>Expired Warranties
        </h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list me-2"></i>Expired Products List
                <span class="badge bg-danger ms-2"><?= $total_items ?> items</span>
            </div>
            
            <?php if (count($expired_products) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Serial No.</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Warranty</th>
                                <th>Expired On</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expired_products as $product): ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= htmlspecialchars($product['serial_number']) ?></td>
                                    <td><?= htmlspecialchars($product['supplier']) ?></td>
                                    <td><?= date('M j, Y', strtotime($product['purchase_date'])) ?></td>
                                    <td><?= $product['warranty'] ?> months</td>
                                    <td><?= date('M j, Y', strtotime($product['expired_on'])) ?></td>
                                    <td>
                                        <span class="status-badge badge-expired">
                                            <i class="fas fa-clock me-1"></i>
                                            Expired <?= $product['days_expired'] ?> days ago
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=1" aria-label="First">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">&lsaquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $total_pages); $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">&rsaquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $total_pages ?>" aria-label="Last">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
                
                <div class="action-buttons no-print">
                    <button onclick="window.print()" class="btn btn-print">
                        <i class="fas fa-print me-2"></i> Print Report
                    </button>
                    <a href="../dashboard.php" class="btn btn-home">
                        <i class="fas fa-home me-2"></i> Back to Dashboard
                    </a>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h4>No Expired Warranties Found</h4>
                    <p class="text-muted">All products currently have valid warranties</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="no-print">
    <?php include '../includes/footer.php'; ?>
    </div>
  
</body>
</html>