<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Pagination setup
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Get filter values
$filterFactory = $_GET['factory'] ?? '';
$filterSupplier = $_GET['supplier'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';

// WHERE conditions
$where = [];
$params = [];

if ($filterFactory !== '') {
    $where[] = "factory_name = :factory";
    $params[':factory'] = $filterFactory;
}
if ($filterSupplier !== '') {
    $where[] = "supplier = :supplier";
    $params[':supplier'] = $filterSupplier;
}
if ($filterCategory !== '') {
    $where[] = "category = :category";
    $params[':category'] = $filterCategory;
}
if ($startDate !== '' && $endDate !== '') {
    $where[] = "purchase_date BETWEEN :start_date AND :end_date";
    $params[':start_date'] = $startDate;
    $params[':end_date'] = $endDate;
}

// Base query for counting
$countQuery = "SELECT COUNT(*) FROM products";
$filterQuery = $where ? " WHERE " . implode(' AND ', $where) : "";

// Get total count
$totalStmt = $pdo->prepare($countQuery . $filterQuery);
$totalStmt->execute($params);
$total = $totalStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Main data query
$sql = "SELECT * FROM products" . $filterQuery . " ORDER BY id DESC LIMIT {$start}, {$perPage}";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Fetch filters
$factories = $pdo->query("SELECT DISTINCT factory_name FROM products ORDER BY factory_name")->fetchAll();
$suppliers = $pdo->query("SELECT DISTINCT supplier FROM products ORDER BY supplier")->fetchAll();
$categories = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT-Asset Inventory | Sterling Group</title>
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e40af;
            --bg-body: #f1f5f9;
        }
        
        body {
            background-color: var(--bg-body);
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #334155;
        }
        
        .main-content { padding: 2rem 0; }
        .filter-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .filter-card .card-header { background: var(--primary); color: white; font-weight: 600; }
        .table-card { border: none; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); background: #fff; }
        
        .product-table thead th {
            background-color: #f8fafc;
            color: #000;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 10px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            vertical-align: middle;
        }
        
        .product-table tbody td {
            padding: 10px 12px;
            font-size: 0.85rem;
            border-bottom: 1px solid #000;
        }

        /* PRINT STYLES */
        @media print {
            /* Hide Web Elements */
            header, footer, .navbar, .d-print-none, .breadcrumb, .filter-card, .btn, .card-footer { 
                display: none !important; 
            }

            /* Page Setup: Portrait with 0.5 inch margin */
            @page {
                size: portrait;
                margin: 0.5in;
            }

            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                color: black !important;
            }

            .main-content { padding: 0 !important; margin: 0 !important; }
            .container-fluid { padding: 0 !important; }
            .table-card { box-shadow: none !important; border: none !important; }

            /* Black and White Table for Printing */
            .product-table {
                width: 100% !important;
                border: 1px solid #000 !important;
                color: black !important;
            }

            .product-table thead th {
                background-color:#8c8b8b !important;
                text-align: center;
                color: #fff !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 9pt !important;
            }

            .product-table tbody td {
                color: black !important;
                border: 1px solid #000 !important;
                background-color: white !important;
                font-size: 9pt !important;
                padding: 4px 6px !important;
            }

            /* Ensure serial numbers and names are visible */
            .product-table code { color: black !important; font-family: inherit; font-weight: bold; }
            
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid black;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Use header for browser view only -->
    <div class="d-print-none">
        <?php include '../includes/header.php'; ?>
    </div>

    <div class="main-content">
        <div class="container-fluid px-4">
            
            <!-- Custom Print Header -->
            <div class="print-header d-none">
                <h2 style="margin: 0; font-size: 22pt;">STERLING GROUP</h2>
                <h4 style="margin: 5px 0; font-size: 14pt;">IT Product Inventory Report.</h4>
                <div style="display: flex; justify-content: space-between; font-size: 10pt; margin-top: 10px;">
                    <span><strong>Generated By:</strong> <?= htmlspecialchars($_SESSION['username'] ?? 'System') ?></span>
                    <span><strong>Date:</strong> <?= date('d-M-Y h:i A') ?></span>
                </div>
            </div>

            <div class="row">
                <!-- Filter Section (Hidden in Print) -->
                <div class="col-lg-3 d-print-none mb-4">
                    <div class="card filter-card">
                        <div class="card-header"><i class="fas fa-filter me-2"></i>Filter Assets</div>
                        <div class="card-body">
                            <form method="get" class="small">
                                <div class="mb-2">
                                    <label class="form-label mb-1">Factory</label>
                                    <select name="factory" class="form-select form-select-sm">
                                        <option value="">All Factories</option>
                                        <?php foreach ($factories as $f): ?>
                                            <option value="<?= htmlspecialchars($f['factory_name']) ?>" <?= $filterFactory === $f['factory_name'] ? 'selected' : '' ?>><?= htmlspecialchars($f['factory_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1">Category</label>
                                    <select name="category" class="form-select form-select-sm">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?= htmlspecialchars($c['category']) ?>" <?= $filterCategory === $c['category'] ? 'selected' : '' ?>><?= htmlspecialchars($c['category']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1">Date Range</label>
                                    <input type="date" name="start_date" class="form-control form-control-sm mb-1" value="<?= htmlspecialchars($startDate) ?>">
                                    <input type="date" name="end_date" class="form-control form-control-sm" value="<?= htmlspecialchars($endDate) ?>">
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                                    <a href="view_all_products.php" class="btn btn-light btn-sm border">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="card table-card">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center d-print-none">
                            <h5 class="mb-0 fw-bold text-primary">Filtered Products List</h5>
                            <button onclick="window.print()" class="btn btn-dark shadow-sm">
                                <i class="fas fa-print me-2"></i>Print Report
                            </button>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table product-table mb-1">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Name</th>
                                            <th>Serial No</th>
                                            <th>Supplier</th>
                                            <th>Date</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Factory</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($products): ?>
                                            <?php foreach ($products as $i => $product): ?>
                                            <tr>
                                                <td><?= $start + $i + 1 ?></td>
                                                <td class="fw-bold"><?= htmlspecialchars($product['name']) ?></td>
                                                <td><code><?= htmlspecialchars($product['serial_number'] ?? 'N/A') ?></code></td>
                                                <td><?= htmlspecialchars($product['supplier']) ?></td>
                                                <td><?= date('d-M-y', strtotime($product['purchase_date'])) ?></td>
                                                <td><?= number_format($product['price']) ?></td>
                                                <td><?= htmlspecialchars($product['category']) ?></td>
                                                <td><?= htmlspecialchars($product['factory_name']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr><td colspan="8" class="text-center py-4">Sorry No data found!.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                            <div class="card-footer bg-white d-print-none">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm justify-content-center mb-0">
                                        <?php 
                                        $params = $_GET;
                                        for($i = 1; $i <= $totalPages; $i++): 
                                            $params['page'] = $i;
                                        ?>
                                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                            <a class="page-link" href="?<?= http_build_query($params) ?>"><?= $i ?></a>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use footer for browser view only -->
    <div class="d-print-none">
        <?php include '../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>