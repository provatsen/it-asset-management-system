<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config/db.php';

$product = null;
$searchPerformed = false;
$searchQuery = '';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['search'])) {
    $searchPerformed = true;
    $searchQuery = trim($_GET['query'] ?? '');

    if (!empty($searchQuery)) {
        $stmt = $pdo->prepare(
            "SELECT * FROM products 
             WHERE serial_number = ? 
             LIMIT 1"
        );
        $stmt->execute([$searchQuery]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/x-icon" href="/image/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Serial Number Search | IT-Asset Management</title>

<!-- Google Fonts - Roboto -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
:root {
    --primary: #4361ee;
    --secondary: #3f37c9;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
}

/* Apply Roboto font */
body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    color: #334155;
}

/* Header spacing */
.page-wrapper {
    padding-top: 80px;
}

/* Search Container */
.search-container {
    max-width: 800px;
    margin: 0 auto;
}

/* Search Card */
.search-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.search-card h1 {
    color: var(--primary);
    font-weight: 700;
    margin-bottom: 30px;
    text-align: center;
}

/* Search Form */
.search-form {
    position: relative;
}

.search-input-group {
    position: relative;
}

.search-input-group .form-control {
    border-radius: 12px;
    padding: 20px 60px 20px 25px;
    border: 2px solid #e2e8f0;
    font-size: 1.2rem;
    font-weight: 500;
    transition: all 0.3s ease;
    height: 65px;
}

.search-input-group .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.search-input-group .search-icon {
    position: absolute;
    right: 25px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1.5rem;
}

.btn-search {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border: none;
    border-radius: 12px;
    padding: 15px 40px;
    font-size: 1.1rem;
    font-weight: 600;
    height: 65px;
    width: 100%;
    transition: all 0.3s ease;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
}

/* Product Card */
.product-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f5f9;
}

.product-header h2 {
    color: var(--primary);
    font-weight: 700;
    margin: 0;
}

.serial-badge {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-family: 'Roboto Mono', monospace;
    font-size: 1.1rem;
}

/* Product Info Table */
.product-info-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.product-info-table tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.3s ease;
}

.product-info-table tr:hover {
    background: #f8fafc;
}

.product-info-table th {
    text-align: left;
    padding: 18px 15px;
    font-weight: 600;
    color: #475569;
    background: #f8fafc;
    width: 30%;
    border-right: 1px solid #e2e8f0;
}

.product-info-table td {
    padding: 18px 15px;
    color: #334155;
    font-weight: 500;
}

/* Status Badges */
.badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.badge-new { background: #dcfce7; color: #166534; }
.badge-used { background: #fef3c7; color: #92400e; }
.badge-refurbished { background: #dbeafe; color: #1e40af; }
.badge-available { background: #dcfce7; color: #166534; }
.badge-assigned { background: #fef3c7; color: #92400e; }
.badge-maintenance { background: #fee2e2; color: #991b1b; }

/* Warranty Status */
.warranty-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.warranty-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
}

.warranty-valid { background: #10b981; }
.warranty-expiring { background: #f59e0b; }
.warranty-expired { background: #ef4444; }

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #f1f5f9;
}

.action-buttons .btn {
    flex: 1;
    padding: 15px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.btn-print {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
}

.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
}

.btn-clear {
    background: #ffffff;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-clear:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 60px 40px;
}

.no-results-icon {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.no-results h4 {
    color: #64748b;
    margin-bottom: 15px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 40px;
}

.empty-state-icon {
    font-size: 5rem;
    color: #e2e8f0;
    margin-bottom: 20px;
    opacity: 0.5;
}

/* Print Styles */
@media print {
    @page { 
        margin: 10mm;
        size: A4 portrait; 
    }
    
    body {
        background: white !important;
        font-size: 12pt;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .no-print, 
    .search-card,
    .action-buttons,
    .btn {
        display: none !important;
    }
    
    .page-wrapper {
        padding-top: 0 !important;
    }
    
    .product-card {
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
    
    .product-header {
        border-bottom: 3px solid #000 !important;
        margin-bottom: 20px !important;
    }
    
    .product-info-table {
        border: 2px solid #000 !important;
    }
    
    .product-info-table th,
    .product-info-table td {
        border: 1px solid #000 !important;
        padding: 12px 10px !important;
        font-size: 11pt !important;
    }
    
    .product-info-table th {
        background-color: #f0f0f0 !important;
        color: #000 !important;
        font-weight: bold !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
        background: none !important;
        color: black !important;
        padding: 4px 10px !important;
        font-weight: normal !important;
    }
    
    .serial-badge {
        border: 2px solid #000 !important;
        background: none !important;
        color: black !important;
        padding: 8px 15px !important;
    }
    
    /* Print Header */
    body::before {
        content: "Product Information Report - Sterling Group";
        display: block;
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #000;
    }
    
    /* Print Footer */
    body::after {
        content: "Printed on: " attr(data-print-date) " | Page " counter(page);
        display: block;
        text-align: center;
        font-size: 9pt;
        color: #666;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #ccc;
        position: fixed;
        bottom: 0;
        width: 100%;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .search-container {
        padding: 15px;
    }
    
    .search-card,
    .product-card {
        padding: 25px;
    }
    
    .product-info-table th,
    .product-info-table td {
        padding: 12px 10px;
        font-size: 0.95rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
        width: 100%;
    }
}
</style>
</head>

<body>

<!-- HEADER -->
<?php include '../includes/header.php'; ?>

<!-- MAIN CONTENT -->
<div class="page-wrapper">
    <div class="container">
        <div class="search-container">
            
            <!-- Search Card -->
            <div class="search-card">
                <h1><i class="fas fa-search me-3"></i>Serial Number Search</h1>
                
                <form method="get" id="searchForm" class="search-form">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <div class="search-input-group">
                                <input type="text" 
                                       name="query" 
                                       class="form-control" 
                                       placeholder="Enter exact serial number..."
                                       value="<?= htmlspecialchars($searchQuery) ?>"
                                       autocomplete="off"
                                       autofocus
                                       required>
                                <i class="fas fa-barcode search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="search" class="btn btn-search">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 text-center text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Enter the exact serial number to retrieve complete product information
                    </div>
                </form>
            </div>
            
            <?php if ($searchPerformed): ?>
                
                <?php if ($product): ?>
                
                <!-- Product Information Card -->
                <div class="product-card" id="productCard">
                    <div class="product-header">
                        <h2><i class="fas fa-box me-2"></i>Product Details</h2>
                        <div class="serial-badge">
                            <?= htmlspecialchars($product['serial_number'] ?? 'N/A') ?>
                        </div>
                    </div>
                    
                    <!-- Product Information Table -->
                    <table class="product-info-table">
                        <tbody>
                            <tr>
                                <th><i class="fas fa-tag me-2"></i>Product Name</th>
                                <td><strong><?= htmlspecialchars($product['name'] ?? 'N/A') ?></strong></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-id-badge me-2"></i>Product ID</th>
                                <td><span class="badge bg-light text-dark">#<?= $product['id'] ?? 'N/A' ?></span></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-folder me-2"></i>Category</th>
                                <td><?= htmlspecialchars($product['category'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-industry me-2"></i>Brand & Model</th>
                                <td>
                                    <strong><?= htmlspecialchars($product['brand'] ?? 'N/A') ?></strong>
                                    <?php if ($product['model']): ?>
                                        <span class="text-muted"> | <?= htmlspecialchars($product['model']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-truck me-2"></i>Supplier</th>
                                <td><?= htmlspecialchars($product['supplier'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-calendar-alt me-2"></i>Purchase Date</th>
                                <td>
                                    <?= !empty($product['purchase_date']) ? date('F j, Y', strtotime($product['purchase_date'])) : 'N/A' ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-shield-alt me-2"></i>Warranty</th>
                                <td>
                                    <div class="warranty-status">
                                        <span class="warranty-dot warranty-<?= 
                                            ($product['warranty'] ?? 0) >= 24 ? 'valid' : 
                                            (($product['warranty'] ?? 0) >= 12 ? 'expiring' : 'expired') 
                                        ?>"></span>
                                        <span><?= htmlspecialchars($product['warranty'] ?? 0) ?> months</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-money-bill-wave me-2"></i>Price</th>
                                <td class="fw-bold text-success">
                                    ৳<?= number_format($product['price'] ?? 0, 2) ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-clipboard-check me-2"></i>Condition</th>
                                <td>
                                    <?php 
                                    $condition = strtolower($product['product_condition'] ?? 'new');
                                    $conditionClass = 'badge-' . $condition;
                                    ?>
                                    <span class="badge <?= $conditionClass ?>">
                                        <?= ucfirst($condition) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-building me-2"></i>Factory / Department</th>
                                <td><?= htmlspecialchars($product['factory_name'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                <td>
                                    <?php 
                                    $status = strtolower($product['status'] ?? 'available');
                                    $statusClass = 'badge-' . $status;
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php if (!empty($product['notes'])): ?>
                            <tr>
                                <th><i class="fas fa-sticky-note me-2"></i>Additional Notes</th>
                                <td><?= htmlspecialchars($product['notes']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th><i class="fas fa-calendar me-2"></i>Last Updated</th>
                                <td class="text-muted">
                                    <?php if (!empty($product['updated_at'])): ?>
                                        <?= date('F j, Y, g:i a', strtotime($product['updated_at'])) ?>
                                    <?php else: ?>
                                        <?= date('F j, Y, g:i a') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons no-print">
                        <button onclick="window.print()" class="btn btn-print">
                            <i class="fas fa-print"></i> Print This Record
                        </button>
                        <a href="?" class="btn btn-clear">
                            <i class="fas fa-redo"></i> New Search
                        </a>
                    </div>
                </div>
                
                <?php else: ?>
                
                <!-- No Results Found -->
                <div class="product-card">
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h4>No Product Found</h4>
                        <p class="text-muted mb-4">
                            No product found with serial number: <strong>"<?= htmlspecialchars($searchQuery) ?>"</strong>
                        </p>
                        <div class="action-buttons">
                            <a href="?" class="btn btn-print">
                                <i class="fas fa-redo me-2"></i> Try Another Search
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php endif; ?>
                
            <?php else: ?>
            
            <!-- Empty State (Initial Page Load) -->
            <div class="product-card">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-barcode"></i>
                    </div>
                    <h3 class="mb-3">Ready to Search</h3>
                    <p class="text-muted mb-4">
                        Enter a serial number above to retrieve complete product information.
                    </p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-qrcode fa-2x text-primary mb-3"></i>
                                    <h5>Scan or Enter</h5>
                                    <p class="small text-muted">Enter exact serial number from asset label</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-print fa-2x text-success mb-3"></i>
                                    <h5>Print Report</h5>
                                    <p class="small text-muted">Get printable product information sheet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?php include '../includes/footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-focus search input
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        searchInput.focus();
        // Select all text for easy replacement
        searchInput.select();
    }
});

// Enhanced print functionality
function printProductReport() {
    // Set print date attribute
    document.body.setAttribute('data-print-date', new Date().toLocaleString());
    
    // Add print-specific styling
    const printStyle = document.createElement('style');
    printStyle.innerHTML = `
        @media print {
            body * { visibility: hidden; }
            #productCard, #productCard * { visibility: visible; }
            #productCard { position: absolute; left: 0; top: 0; width: 100%; }
        }
    `;
    document.head.appendChild(printStyle);
    
    // Trigger print
    window.print();
    
    // Remove the style after printing
    setTimeout(() => {
        document.head.removeChild(printStyle);
    }, 100);
}

// Replace default print with enhanced version
window.print = printProductReport;

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + F to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        document.querySelector('input[name="query"]').focus();
    }
    
    // Enter to submit form when input is focused
    if (e.key === 'Enter' && document.activeElement.name === 'query') {
        document.getElementById('searchForm').submit();
    }
    
    // Escape to clear search
    if (e.key === 'Escape') {
        window.location.href = window.location.pathname;
    }
});

// Add barcode scanner simulation (optional)
let barcodeBuffer = '';
let lastKeyTime = Date.now();

document.addEventListener('keypress', function(e) {
    const currentTime = Date.now();
    
    // If more than 100ms passed, reset buffer (not a barcode scanner)
    if (currentTime - lastKeyTime > 100) {
        barcodeBuffer = '';
    }
    
    lastKeyTime = currentTime;
    
    // If Enter is pressed and buffer looks like a barcode (more than 5 chars quickly)
    if (e.key === 'Enter' && barcodeBuffer.length > 5) {
        document.querySelector('input[name="query"]').value = barcodeBuffer;
        document.getElementById('searchForm').submit();
    } else {
        barcodeBuffer += e.key;
    }
});
</script>
</body>
</html>