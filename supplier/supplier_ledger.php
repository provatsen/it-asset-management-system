<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

/* ================= SUPPLIER LIST ================= */
$suppliers = $pdo->query("
    SELECT supplier,
           COUNT(*) AS total_items,
           SUM(price) AS total_value
    FROM products
    WHERE supplier IS NOT NULL 
      AND supplier != ''
      AND supplier != 'N/A'
    GROUP BY supplier
    ORDER BY supplier
")->fetchAll(PDO::FETCH_ASSOC);

/* ================= SELECTED SUPPLIER ================= */
$selectedSupplier = $_GET['supplier'] ?? '';
$products = [];
$totalItems = 0;
$totalValue = 0;

if ($selectedSupplier) {
    $stmt = $pdo->prepare("
        SELECT *,
               DATE_FORMAT(purchase_date, '%d/%m/%Y') AS formatted_purchase_date
        FROM products
        WHERE supplier = ?
        ORDER BY purchase_date DESC, name ASC
    ");
    $stmt->execute([$selectedSupplier]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $p) {
        $totalItems++;
        $totalValue += (float)$p['price'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Supplier Ledger Report</title>
<link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root {
    --primary-color: #4f46e5;
    --secondary-color: #059669;
    --light-bg: #f4f6f9;
    --border-color: #e2e8f0;
}

body {
    background: var(--light-bg);
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    line-height: 1.5;
    padding-top: 56px;
}

/* Header adjustments for mobile */
@media (max-width: 767.98px) {
    body {
        padding-top: 70px;
    }
}

.container {
    max-width: 1400px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    margin-bottom: 2rem;
    overflow: hidden;
}

.card-body {
    padding: 1.5rem;
}

/* Filter section */
.filter-section {
    background: #fff;
    border-radius: 10px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
}

.form-select {
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.2s;
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Report header */
.report-header {
    text-align: center;
    padding: 1.5rem 0;
    margin-bottom: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 10px;
    border: 2px solid var(--border-color);
}

.report-header img {
    height: 70px;
    margin-bottom: 1rem;
}

.report-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0.5rem 0;
}

.report-header .subtitle {
    font-size: 1.1rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.report-header .meta-info {
    font-size: 0.9rem;
    color: #475569;
    background: #fff;
    padding: 0.75rem;
    border-radius: 6px;
    display: inline-block;
    border: 1px solid var(--border-color);
}

/* Summary cards */
.summary-card {
    border-radius: 10px;
    padding: 1.5rem;
    color: #fff;
    text-align: center;
    margin-bottom: 1rem;
    transition: transform 0.2s;
    height: 80%;
    
}

.summary-card:hover {
    transform: translateY(-2px);
}

.summary-card strong {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.summary-card .label {
    font-size: 0.95rem;
    opacity: 0.9;
}

/* Responsive table */
.table-responsive-wrapper {
    overflow: hidden;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 1rem;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
    background: #fff;
}

.report-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid var(--border-color);
}

.report-table th {
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #334155;
    text-align: center;
    vertical-align: middle;
    font-size: 0.95rem;
    white-space: nowrap;
}

.report-table td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid var(--border-color);
    color: #475569;
    vertical-align: middle;
}

.report-table tbody tr:hover {
    background-color: #f8fafc;
}

.report-table .text-end {
    text-align: right;
}

.report-table .text-center {
    text-align: center;
}

/* Total row styling for screen display */
.report-table .total-row {
    background: #f8fafc;
    font-weight: 600;
    border-top: 2px solid var(--border-color);
}

/* Mobile optimizations */
@media (max-width: 767.98px) {
    .card-body {
        padding: 1rem;
    }
    
    .report-header img {
        height: 50px;
    }
    
    .report-header h2 {
        font-size: 1.25rem;
    }
    
    .report-header .subtitle {
        font-size: 0.9rem;
    }
    
    .report-header .meta-info {
        font-size: 0.8rem;
        display: block;
        text-align: left;
    }
    
    .summary-card {
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .summary-card strong {
        font-size: 1.5rem;
    }
    
    /* Mobile table with horizontal scroll */
    .table-responsive-wrapper {
        margin: 0 -1rem;
        border-radius: 0;
        box-shadow: none;
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 1rem;
    }
    
    .report-table {
        min-width: 800px;
    }
    
    .report-table th,
    .report-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
    
    .btn-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .btn-group .btn {
        flex: 1;
        min-width: 120px;
    }
}

/* Tablet optimizations */
@media (min-width: 768px) and (max-width: 991.98px) {
    .report-table {
        font-size: 0.9rem;
    }
    
    .report-table th,
    .report-table td {
        padding: 0.875rem 0.625rem;
    }
}

/* Action buttons */
.btn-group {
    gap: 0.5rem;
}

.btn-print {
    background: var(--primary-color);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-print:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-export {
    background: var(--secondary-color);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-export:hover {
    background: #047857;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* ================= PRINT STYLES ================= */
@media print {
    @page {
        size: A4 portrait;
        margin: 10mm 10mm 20mm 10mm; /* Top Right Bottom Left */
    }
    /* Hide everything except print content */
    body * {
        visibility: hidden;
        background: none !important;
        color: black !important;
    }
    
    /* Show only print sections */
    .print-section,
    .print-section * {
        visibility: visible;
    }
    
    .print-section {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white !important;
        padding: 0;
        margin: 0;
    }
    
    /* Remove all background colors for print */
    * {
        background-color: transparent !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    /* Print container */
    .print-container {
        width: 100%;
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Arial, sans-serif !important;
        font-size: 11pt;
        line-height: 1.4;
    }
    
    /* Print header - First page only */
    .print-header-first {
        text-align: center;
        margin-bottom: 12mm;
        border-bottom: 2px solid #000;
        padding-bottom: 5mm;
    }
    
    .print-header-first img {
        height: 80px;
        margin-bottom: 0mm;
    }
    
    .print-header-first h1 {
        font-size: 22pt;
        font-weight: bold;
        margin: 2mm 0;
        color: #000 !important;
    }
    
    .print-header-first .company-info {
        font-size: 11pt;
        margin-bottom: 5mm;
    }
    
    .print-header-first .report-info {
        font-size: 10pt;
        background: none;
        
        
    }
    
    
    /* Print table */
    .print-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
        margin-bottom: 5mm;
        page-break-inside: auto;
    }
    
    .print-table thead {
        display: table-header-group;
    }
    
    .print-table th {
        background: #e8e8e8 !important;
        border: 1px solid #000 !important;
        padding: 3mm 2mm !important;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .print-table td {
        border: 1px solid #000 !important;
        padding: 2.5mm 1.5mm !important;
        vertical-align: top;
        word-wrap: break-word;
    }
    
    .print-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    /* Total row styling for print */
    .print-table .total-row td {
        background: none !important;
        font-weight: bold;
        padding: 3mm 2mm !important;
        border-top: 2px solid #000 !important;
    }
    
    /* Control page breaks */
    .no-break {
        page-break-inside: avoid;
    }
    
    .avoid-page-break {
        page-break-before: avoid;
        page-break-inside: avoid;
        page-break-after: avoid;
    }
    
    /* Force black text for all elements */
    body, h1, h2, h3, h4, h5, h6, p, span, div, td, th {
        color: #000 !important;
    }
    
    /* Remove all shadows and effects */
    * {
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    /* Hide page elements in print */
    .no-print, .btn, .filter-section, .summary-card, header, footer, .empty-state {
        display: none !important;
    }
    .summary-card, .print-summary no-break, .print-summary-item{
        display: none;
    }
    
    /* Ensure table fits within page */
    .print-table {
        max-width: 100%;
        table-layout: fixed;
    }
    
    /* Column widths for better print layout */
    .col-sn { width: 4%; }
    .col-product { width: 20%; }
    .col-category { width: 10%; }
    .col-serial { width: 15%; }
    .col-unit { width: 7%; }
    .col-date { width: 10%; }
    .col-warranty { width: 8%; }
    .col-price { width: 12%; text-align: right; }
    
    /* Let browser handle page breaks naturally */
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}

/* Loading state */
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.loading-overlay.active {
    display: flex;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>

<body>

<?php include "../includes/header.php"; ?>
<!-- Loading overlay -->
<div class="loading-overlay">
    <div class="spinner"></div>
    <p class="mt-3">Generating report...</p>
</div>

<!-- Print Section (Hidden on screen) --->
<div class="print-section" style="display: none;">
    <?php if ($selectedSupplier): ?>
    <div class="print-container">
        <!-- First Page Header -->
        <div class="print-header-first no-break">
            <img src="../image/sg_logo.png" alt="Logo">
            <h1>STERLING GROUP</h1>
            <div class="company-info">IT Asset Management System</div>
            <div class="report-info">
                <strong>Supplier: <?= htmlspecialchars($selectedSupplier) ?></strong><br>
                Report Date: <?= date('d M Y') ?> | 
                Generated By: <?= htmlspecialchars($_SESSION['username'] ?? 'System') ?>
            </div>
        </div>
        
        <!-- Summary Section -->
        <div class="print-summary no-break">
            <div class="print-summary-item">
                <strong><?= $totalItems ?></strong>
                Total Items
            </div>
            <div class="print-summary-item">
                <strong><?= number_format($totalValue, 2) ?></strong>
                Total Value
            </div>
        </div>
        
        <!-- Table -->
        <table class="print-table">
            <thead>
                <tr>
                    <th class="col-sn">SN</th>
                    <th class="col-product">Product Name</th>
                    <th class="col-category">Category</th>
                    <th class="col-serial">Serial No</th>
                    <th class="col-unit">Unit</th>
                    <th class="col-date">Date</th>
                    <th class="col-warranty">Warranty(Month)</th>
                    <th class="col-price">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $productCount = count($products);
                foreach ($products as $i => $p): ?>
                    <tr>
                        <td class="text-center col-sn"><?= $i + 1 ?></td>
                        <td class="col-product"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="col-category"><?= htmlspecialchars($p['category'] ?? 'N/A') ?></td>
                        <td class="col-serial"><?= htmlspecialchars($p['serial_number'] ?? 'N/A') ?></td>
                        <td class="text-center col-unit"><?= htmlspecialchars($p['factory_name'] ?? 'N/A') ?></td>
                        <td class="col-date"><?= $p['formatted_purchase_date'] ?? 'N/A' ?></td>
                        <td class="text-center col-warranty"><?= htmlspecialchars($p['warranty'] ?? 'N/A') ?></td>
                        <td class="text-end col-price"><?= number_format($p['price'], 2) ?></td>
                    </tr>
                    
                    <?php if ($i === $productCount - 1): // Last row ?>
                        <!-- Total row - appears only at the end -->
                        <tr class="total-row avoid-page-break">
                            <td colspan="7" class="text-end"><strong>TOTAL</strong></td>
                            <td class="text-end"><strong><?= number_format($totalValue, 2) ?></strong></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<div class="container my-4">
<div class="card">
<div class="card-body">

<!-- FILTER SECTION -->
<div class="filter-section no-print">
    <div class="row g-3">
        <div class="col-md-8">
            <form method="get" id="reportForm">
                <label class="form-label fw-semibold">Select Supplier</label>
                <select name="supplier" class="form-select" required>
                    <option value="">-- Select Supplier --</option>
                    <?php foreach ($suppliers as $s): ?>
                    <option value="<?= htmlspecialchars($s['supplier']) ?>" 
                            <?= $selectedSupplier == $s['supplier'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['supplier']) ?> (<?= $s['total_items'] ?> items)
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="col-md-4">
            <div class="d-flex gap-2 justify-content-md-end align-items-end h-100">
                <?php if ($selectedSupplier): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-print" onclick="printReport()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <button type="button" class="btn btn-export" onclick="exportToExcel()">
                        <i class="fas fa-file-excel me-1"></i> Export
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($selectedSupplier): ?>

<!-- SCREEN DISPLAY -->
<div id="reportContent" class="no-print">

<!-- HEADER -->
<div class="report-header">
    <img src="../image/sg_logo.png" alt="Sterling Group Logo">
    <h2>STERLING GROUP</h2>
    <div class="subtitle">IT Asset Management System</div>
    <div class="meta-info">
        Supplier: <strong><?= htmlspecialchars($selectedSupplier) ?></strong> | 
        Report Date: <?= date('d M Y') ?> | 
        Generated By: <?= htmlspecialchars($_SESSION['username'] ?? 'System') ?>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="summary-card" style="background: var(--primary-color)">
            <strong><?= $totalItems ?></strong>
            <span class="label">Total Items</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="summary-card" style="background: var(--secondary-color)">
            <strong><?= number_format($totalValue, 2) ?></strong>
            <span class="label">Total Value</span>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="table-responsive-wrapper">
    <div class="table-responsive">
        <table class="report-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Serial No</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Warranty (Month)</th>
                    <th class="text-end">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $i => $p): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['category'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($p['serial_number'] ?? 'N/A') ?></td>
                    <td class="text-center"><?= htmlspecialchars($p['factory_name'] ?? 'N/A') ?></td>
                    <td><?= $p['formatted_purchase_date'] ?? 'N/A' ?></td>
                    <td class="text-center"><?= htmlspecialchars($p['warranty'] ?? 'N/A') ?></td>
                    <td class="text-end"><?= number_format($p['price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Total row at the end of tbody -->
                <tr class="total-row">
                    <td colspan="7" class="text-end fw-bold">TOTAL</td>
                    <td class="text-end fw-bold"><?= number_format($totalValue, 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</div> <!-- End reportContent -->

<?php else: ?>

<!-- EMPTY STATE -->
<div class="empty-state">
    <i class="fas fa-file-alt text-muted"></i>
    <h5>Select a supplier to generate report</h5>
    <p class="text-muted">Choose a supplier from the dropdown above to view their products</p>
</div>

<?php endif; ?>

</div>
</div>
</div>

<?php include "../includes/footer.php"; ?>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
// Auto-submit form when supplier changes
document.querySelector('select[name="supplier"]').addEventListener('change', function() {
    document.querySelector('.loading-overlay').classList.add('active');
    document.getElementById('reportForm').submit();
});

// Print function with proper formatting
function printReport() {
    document.querySelector('.loading-overlay').classList.add('active');
    
    // Show print section
    document.querySelector('.print-section').style.display = 'block';
    
    // Update page numbers
    updatePageNumbers();
    
    // Short delay to ensure DOM is ready
    setTimeout(() => {
        // Use browser's print function
        window.print();
        
        // Hide print section after printing
        setTimeout(() => {
            document.querySelector('.print-section').style.display = 'none';
            document.querySelector('.loading-overlay').classList.remove('active');
        }, 100);
    }, 100);
}

// Export to Excel
function exportToExcel() {
    document.querySelector('.loading-overlay').classList.add('active');
    
    // Simple Excel export using table data
    let table = document.querySelector('.report-table').cloneNode(true);
    
    // Remove hover effects for export
    let rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        row.removeAttribute('style');
        row.classList.remove('hover');
    });
    
    let html = table.outerHTML;
    
    // Create a Blob with the HTML table
    let blob = new Blob([html], {type: 'application/vnd.ms-excel'});
    
    // Create download link
    let link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Supplier-Report-<?= preg_replace('/[^A-Za-z0-9_-]/', '-', $selectedSupplier) ?>-<?= date('Y-m-d') ?>.xls';
    link.click();
    
    // Clean up
    URL.revokeObjectURL(link.href);
    
    setTimeout(() => {
        document.querySelector('.loading-overlay').classList.remove('active');
    }, 500);
}

// Update page numbers for print - simple version
function updatePageNumbers() {
    document.querySelectorAll('.page-number').forEach(el => {
        el.textContent = '1'; // Simple page number
    });
}

// Handle print events
window.addEventListener('beforeprint', function() {
    // Add any pre-print logic here
});

window.addEventListener('afterprint', function() {
    // Hide print section after printing
    document.querySelector('.print-section').style.display = 'none';
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Handle window resize
window.addEventListener('resize', function() {
    // Adjust table responsiveness on resize
    let tableWrapper = document.querySelector('.table-responsive-wrapper');
    if (window.innerWidth < 768 && tableWrapper) {
        tableWrapper.classList.add('mobile-view');
    } else {
        tableWrapper.classList.remove('mobile-view');
    }
});

// Trigger initial resize check
window.dispatchEvent(new Event('resize'));
</script>

</body>
</html>