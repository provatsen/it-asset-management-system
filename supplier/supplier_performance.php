<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config/db.php';

// Get date range from URL or use defaults
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-01-01'); // Start of current year
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// SIMPLE WORKING QUERY with date filtering
$stmt = $pdo->prepare("
    SELECT 
        s.name as supplier_name,
        COUNT(p.id) as total_assets,
        COALESCE(SUM(p.price), 0) as total_spent,
        COALESCE(AVG(p.price), 0) as avg_price,
        MIN(p.purchase_date) as first_purchase,
        MAX(p.purchase_date) as last_purchase,
        COUNT(DISTINCT p.category) as categories,
        COUNT(DISTINCT p.factory_name) as departments
    FROM suppliers s
    LEFT JOIN products p ON s.name = p.supplier
    AND (p.purchase_date BETWEEN ? AND ? OR p.purchase_date IS NULL)
    GROUP BY s.name
    ORDER BY total_spent DESC
");
$stmt->execute([$start_date, $end_date]);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get warranty data
$warrantyStmt = $pdo->prepare("
    SELECT 
        p.supplier,
        COALESCE(AVG(p.warranty), 0) as avg_warranty,
        COUNT(p.id) as items_with_warranty
    FROM products p
    WHERE p.supplier IS NOT NULL 
    AND (p.purchase_date BETWEEN ? AND ? OR p.purchase_date IS NULL)
    GROUP BY p.supplier
");
$warrantyStmt->execute([$start_date, $end_date]);
$warrantyData = $warrantyStmt->fetchAll(PDO::FETCH_ASSOC);

// Convert to associative array for easy lookup
$warrantyLookup = [];
foreach ($warrantyData as $item) {
    $warrantyLookup[$item['supplier']] = $item;
}

// Calculate totals
$total_spent = array_sum(array_column($suppliers, 'total_spent'));
$total_assets = array_sum(array_column($suppliers, 'total_assets'));
$supplier_count = count($suppliers);

// Calculate additional metrics
foreach ($suppliers as &$supplier) {
    $supplier['market_share'] = $total_spent > 0 ? ($supplier['total_spent'] / $total_spent) * 100 : 0;
    $supplier['warranty_info'] = $warrantyLookup[$supplier['supplier_name']] ?? ['avg_warranty' => 0, 'items_with_warranty' => 0];
    
    // Determine performance rating
    $share = $supplier['market_share'];
    if ($share > 20) {
        $supplier['performance'] = 'Excellent';
        $supplier['performance_class'] = 'success';
    } elseif ($share > 10) {
        $supplier['performance'] = 'Good';
        $supplier['performance_class'] = 'primary';
    } elseif ($share > 5) {
        $supplier['performance'] = 'Average';
        $supplier['performance_class'] = 'warning';
    } else {
        $supplier['performance'] = 'Poor';
        $supplier['performance_class'] = 'danger';
    }
}
unset($supplier); // Break reference
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Performance Analytics | IT-Asset Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/favicon.ico" type="image/x-icon">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --purple: #8b5cf6;
        }
        
        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        
        .dashboard-header {
            background: none;
            color: black;
            padding: 25px 0;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card.primary { border-color: var(--primary); }
        .stat-card.success { border-color: var(--success); }
        .stat-card.warning { border-color: var(--warning); }
        .stat-card.danger { border-color: var(--danger); }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 15px;
        }
        
        .icon-primary { background: var(--primary); color: white; }
        .icon-success { background: var(--success); color: white; }
        .icon-warning { background: var(--warning); color: white; }
        .icon-danger { background: var(--danger); color: white; }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }
        
        .supplier-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }
        
        .table th {
            background-color: #f1f5f9;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .supplier-rank {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        
        .progress-thin {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin: 5px 0;
        }
        
        .progress-thin .progress-bar {
            border-radius: 4px;
        }
        
        .badge-performance {
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-primary { background: #dbeafe; color: #1e40af; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        
        .rating-stars {
            color: #fbbf24;
        }
        
        .top-supplier-highlight {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid #bae6fd;
            margin-bottom: 25px;
        }
        
        .export-btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .visual-bar {
            height: 24px;
            background: #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        
        .bar-fill {
            height: 100%;
            border-radius: 12px;
            position: absolute;
            left: 0;
            top: 0;
            transition: width 1s ease;
        }
        
        .bar-label {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: #1e293b;
            text-shadow: 0 1px 1px rgba(255,255,255,0.8);
        }
        
       /* ============================================
   PROFESSIONAL PRINT STYLES
   Includes: Confidential Stamp, Metadata, Legends, Responsive Layout
   ============================================ */

@media print {
    /* PAGE SETUP */
    @page {
        margin: 2cm 2.5cm;
        size: A4 portrait;
        marks: crop cross;
        
        /* Footer with page numbers */
        @bottom-center {
            content: "Page " counter(page) " of " counter(pages);
            font-family: "Roboto", Arial, sans-serif;
            font-size: 9pt;
            color: #666666;
            margin-bottom: 1cm;
        }
        
        /* Header with report title */
        @top-center {
            content: "Supplier Performance Analytics Report";
            font-family: "Roboto", Arial, sans-serif;
            font-size: 10pt;
            color: #333333;
            font-weight: bold;
            margin-top: 0.5cm;
        }
    }
    
    @page :first {
        margin-top: 3.5cm;
        @top-center {
            content: "CONFIDENTIAL - IT-ASSET MANAGEMENT SYSTEM";
            font-size: 11pt;
            color: #990000;
        }
    }
    
    /* GENERAL RESETS */
    * {
        text-shadow: none !important;
        box-shadow: none !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    html, body {
        width: 100% !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }
    
    body {
        font-family: "Roboto", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        font-size: 11pt !important;
        line-height: 1.4 !important;
        color: #000000 !important;
        background: #ffffff !important;
        position: relative;
    }
    
    /* HIDE UNNECESSARY ELEMENTS */
    .no-print,
    .btn,
    button,
    .filter-card,
    .export-btn,
    .top-supplier-highlight,
    .rating-stars,
    .progress-thin,
    .visual-bar,
    .badge-performance,
    .stat-icon,
    .supplier-rank,
    canvas,
    [class*="fa-"]:not(.fa-calendar-alt, .fa-chart-line, .fa-table),
    .dashboard-header .badge,
    .text-primary,
    .text-success,
    .text-warning,
    .text-danger {
        display: none !important;
    }
    
    /* PRINT-SPECIFIC HEADER */
    .print-header {
        display: block !important;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: #ffffff !important;
        border-bottom: 3px double #000000 !important;
        padding: 15px 0 10px 0 !important;
        text-align: center;
        z-index: 1000;
        margin-bottom: 2cm;
    }
    
    .print-header h1 {
        color: #000000 !important;
        font-size: 22pt !important;
        font-weight: bold !important;
        margin: 0 0 5px 0 !important;
        padding: 0 !important;
    }
    
    .print-header .report-meta {
        font-size: 9pt !important;
        color: #666666 !important;
        line-height: 1.3 !important;
    }
    
    .print-header .report-meta span {
        display: inline-block;
        margin: 0 10px;
        padding: 0 10px;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
    }
    
    .print-header .report-meta span:first-child {
        border-left: none;
    }
    
    .print-header .report-meta span:last-child {
        border-right: none;
    }
    
    /* CONFIDENTIAL STAMP */
    .confidential-stamp {
        display: block !important;
        position: fixed;
        top: 1.5cm;
        right: 2cm;
        transform: rotate(45deg);
        background: rgba(255, 0, 0, 0.1) !important;
        border: 3px solid #990000 !important;
        color: #990000 !important;
        padding: 15px 30px !important;
        font-weight: bold !important;
        font-size: 16pt !important;
        letter-spacing: 3px;
        text-transform: uppercase;
        z-index: 999;
        opacity: 0.8;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* MAIN CONTENT AREA */
    .container {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        margin-top: 4cm !important; /* Space for fixed header */
    }
    
    /* EXECUTIVE SUMMARY SECTION */
    .executive-summary {
        display: block !important;
        margin: 0 0 25px 0 !important;
        padding: 15px !important;
        border: 2px solid #000000 !important;
        background: #f9f9f9 !important;
        page-break-inside: avoid;
    }
    
    .executive-summary h3 {
        font-size: 14pt !important;
        font-weight: bold !important;
        margin: 0 0 10px 0 !important;
        color: #000000 !important;
        border-bottom: 1px solid #000000 !important;
        padding-bottom: 5px !important;
    }
    
    .executive-summary p {
        font-size: 10pt !important;
        margin: 5px 0 !important;
        line-height: 1.3 !important;
    }
    
    /* STATISTICS CARDS - PRINT VERSION */
    .print-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: 10px !important;
        margin: 20px 0 30px 0 !important;
        page-break-inside: avoid;
    }
    
    .print-stat-card {
        border: 1px solid #000000 !important;
        padding: 15px 10px !important;
        text-align: center !important;
        background: #ffffff !important;
        page-break-inside: avoid;
    }
    
    .print-stat-card h4 {
        font-size: 16pt !important;
        font-weight: bold !important;
        margin: 0 0 5px 0 !important;
        color: #000000 !important;
    }
    
    .print-stat-card p {
        font-size: 9pt !important;
        color: #666666 !important;
        margin: 0 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* TABLE STYLING */
    .supplier-table {
        border: 2px solid #000000 !important;
        margin: 20px 0 30px 0 !important;
        page-break-inside: avoid;
    }
    
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        border: none !important;
        font-size: 9pt !important;
        page-break-inside: avoid;
    }
    
    table thead {
        display: table-header-group !important;
        background: #333333 !important;
        color: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    table th {
        background: #333333 !important;
        color: #ffffff !important;
        font-weight: bold !important;
        border: 1px solid #000000 !important;
        padding: 12px 8px !important;
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 9pt !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    table td {
        border: 1px solid #cccccc !important;
        padding: 10px 8px !important;
        vertical-align: top !important;
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    /* PERFORMANCE INDICATORS IN TABLE */
    .performance-indicator {
        display: inline-block !important;
        width: 12px !important;
        height: 12px !important;
        border-radius: 50% !important;
        margin-right: 5px !important;
        vertical-align: middle !important;
    }
    
    .performance-excellent { background: #006400 !important; } /* Dark Green */
    .performance-good { background: #1e40af !important; } /* Dark Blue */
    .performance-average { background: #92400e !important; } /* Dark Brown */
    .performance-poor { background: #991b1b !important; } /* Dark Red */
    
    /* WARRANTY RATING STARS */
    .warranty-stars {
        color: #000000 !important;
        font-size: 8pt !important;
        letter-spacing: 1px;
    }
    
    .warranty-stars .filled {
        color: #000000 !important;
    }
    
    .warranty-stars .empty {
        color: #cccccc !important;
    }
    
    /* PERFORMANCE LEGEND */
    .performance-legend {
        display: block !important;
        margin: 15px 0 25px 0 !important;
        padding: 15px !important;
        border: 1px solid #000000 !important;
        background: #f8f8f8 !important;
        page-break-inside: avoid;
    }
    
    .performance-legend h4 {
        font-size: 10pt !important;
        font-weight: bold !important;
        margin: 0 0 10px 0 !important;
        color: #000000 !important;
    }
    
    .legend-items {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        font-size: 8pt !important;
    }
    
    .legend-item {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    
    /* REPORT METADATA FOOTER */
    .report-metadata {
        display: block !important;
        margin-top: 30px !important;
        padding: 15px !important;
        border-top: 2px solid #000000 !important;
        font-size: 8pt !important;
        color: #666666 !important;
        page-break-inside: avoid;
    }
    
    .metadata-grid {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 15px !important;
        margin-bottom: 10px !important;
    }
    
    .metadata-item strong {
        color: #000000 !important;
        display: block !important;
        margin-bottom: 2px !important;
    }
    
    /* CONFIDENTIAL FOOTER */
    .confidential-footer {
        display: block !important;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff !important;
        border-top: 3px double #990000 !important;
        padding: 10px 0 !important;
        text-align: center;
        font-size: 9pt !important;
        color: #990000 !important;
        font-weight: bold !important;
        letter-spacing: 1px;
        z-index: 1000;
        margin-bottom: 2cm;
    }
    
    /* PAGE BREAK CONTROL */
    h1, h2, h3, h4, h5, h6 {
        page-break-after: avoid;
        page-break-inside: avoid;
    }
    
    table, img, .executive-summary, .performance-legend {
        page-break-inside: avoid;
    }
    
    p, ul, ol {
        orphans: 3;
        widows: 3;
    }
    
    /* AVOID BREAKING INSIDE ROWS */
    tr {
        page-break-inside: avoid;
    }
    
    /* FORCE NEW PAGE FOR SECTIONS */
    .new-page {
        page-break-before: always;
    }
    
    /* UTILITY CLASSES */
    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }
    .fw-bold { font-weight: bold !important; }
    .mt-20 { margin-top: 20px !important; }
    .mb-20 { margin-bottom: 20px !important; }
    
    /* SHOW PRINT-ONLY ELEMENTS */
    .print-only {
        display: block !important;
    }
    
    /* RESPONSIVE PRINT LAYOUT */
    @media print and (max-width: 21cm) {
        .print-stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .metadata-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .legend-items {
            grid-template-columns: 1fr !important;
        }
    }
    
    /* LANDSCAPE MODE FOR WIDE TABLES */
    @media print and (orientation: landscape) {
        @page {
            size: A4 landscape;
            margin: 1.5cm 2cm;
        }
        
        table {
            font-size: 8pt !important;
        }
        
        table th,
        table td {
            padding: 8px 6px !important;
        }
    }
}
    </style>
</head>
<body>
<div class="no-print">
    <?php include_once '../includes/header.php'; ?>
</div>


<div class="dashboard-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-2"><i class="fas fa-chart-line me-2"></i>Supplier Performance Analytics</h1>
                <p class="mb-0 opacity-90">Comprehensive analysis of vendor performance and spending patterns</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="badge bg-white text-primary fs-6 px-3 py-2">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= date('M Y', strtotime($start_date)) ?> - <?= date('M Y', strtotime($end_date)) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card primary">
                <div class="stat-icon icon-primary">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="fw-bold"><?= $supplier_count ?></h3>
                <p class="text-muted mb-0">Active Suppliers</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card success">
                <div class="stat-icon icon-success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3 class="fw-bold">BDT <?= number_format($total_spent) ?></h3>
                <p class="text-muted mb-0">Total Spent</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card warning">
                <div class="stat-icon icon-warning">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="fw-bold"><?= number_format($total_assets) ?></h3>
                <p class="text-muted mb-0">Total Assets</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card danger">
                <div class="stat-icon icon-danger">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="fw-bold text-truncate" title="<?= htmlspecialchars($suppliers[0]['supplier_name'] ?? 'N/A') ?>">
                    <?= htmlspecialchars($suppliers[0]['supplier_name'] ?? 'N/A') ?>
                </h3>
                <p class="text-muted mb-0">Top Supplier</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="<?= $start_date ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium">End Date</label>
                <input type="date" class="form-control" name="end_date" value="<?= $end_date ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-outline-success export-btn me-2" onclick="exportToCSV()">
                    <i class="fas fa-file-excel me-2"></i> Export CSV
                </button>
                <button type="button" class="btn btn-outline-danger export-btn me-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> Print
                </button>
                <a href="add_supplier.php" class="btn btn-outline-primary export-btn">
                    <i class="fas fa-plus me-2"></i> Add Supplier
                </a>
            </div>
        </form>
    </div>

    <!-- Top Supplier Highlight -->
    <?php if (!empty($suppliers)): ?>
    <div class="top-supplier-highlight">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="supplier-rank me-3">#1</div>
                    <div>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($suppliers[0]['supplier_name']) ?></h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-coins me-1"></i>BDT <?= number_format($suppliers[0]['total_spent']) ?> 
                            <span class="mx-2">•</span>
                            <i class="fas fa-box me-1"></i><?= number_format($suppliers[0]['total_assets']) ?> assets
                            <span class="mx-2">•</span>
                            <i class="fas fa-chart-pie me-1"></i><?= number_format($suppliers[0]['market_share'], 1) ?>% market share
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="rating-stars fs-5 mb-2">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="badge-performance badge-<?= $suppliers[0]['performance_class'] ?>">
                    <?= $suppliers[0]['performance'] ?> Performer
                </span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Visual Comparison Bars -->
    <div class="filter-card mb-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Spending Comparison</h5>
        <?php 
        $top5Suppliers = array_slice($suppliers, 0, 5);
        $maxSpent = max(array_column($top5Suppliers, 'total_spent'));
        ?>
        <?php foreach ($top5Suppliers as $index => $supplier): ?>
        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span class="fw-medium"><?= $index + 1 ?>. <?= htmlspecialchars($supplier['supplier_name']) ?></span>
                <span class="fw-bold text-primary">BDT <?= number_format($supplier['total_spent']) ?></span>
            </div>
            <div class="visual-bar">
                <div class="bar-fill bg-primary" 
                     style="width: <?= $maxSpent > 0 ? ($supplier['total_spent'] / $maxSpent) * 100 : 0 ?>%;
                            background: linear-gradient(90deg, var(--primary), #60a5fa);">
                </div>
                <div class="bar-label"><?= number_format($supplier['market_share'], 1) ?>%</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Main Supplier Table -->
    <div class="supplier-table">
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="fas fa-table me-2"></i>Supplier Performance Details</h5>
                <span class="badge bg-light text-dark">Showing <?= count($suppliers) ?> suppliers</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover" id="supplierTable">
                    <thead>
                        <tr>
                            <th width="60">Rank</th>
                            <th>Supplier</th>
                            <th class="text-center">Assets</th>
                            <th class="text-center">Financials</th>
                            <th class="text-center">Warranty</th>
                            <th class="text-center">Performance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $index => $supplier): ?>
                        <tr>
                            <td class="text-center">
                                <div class="supplier-rank">#<?= $index + 1 ?></div>
                            </td>
                            <td>
                                <div class="fw-bold mb-1"><?= htmlspecialchars($supplier['supplier_name']) ?></div>
                                <div class="small text-muted">
                                    <i class="fas fa-history me-1"></i>
                                    <?= $supplier['first_purchase'] ? date('M Y', strtotime($supplier['first_purchase'])) : 'No purchases' ?>
                                    -
                                    <?= $supplier['last_purchase'] ? date('M Y', strtotime($supplier['last_purchase'])) : '' ?>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-tags me-1"></i><?= $supplier['categories'] ?> categories
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-building me-1"></i><?= $supplier['departments'] ?> depts
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fw-bold fs-5"><?= number_format($supplier['total_assets']) ?></div>
                                <div class="progress-thin">
                                    <div class="progress-bar bg-primary" 
                                         style="width: <?= min(($supplier['total_assets'] / ($total_assets ?: 1)) * 100, 100) ?>%">
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fw-bold text-success">BDT <?= number_format($supplier['total_spent']) ?></div>
                                <div class="small text-muted mb-1">
                                    Avg: BDT <?= number_format($supplier['avg_price'], 2) ?>
                                </div>
                                <div class="small text-muted">
                                    Share: <?= number_format($supplier['market_share'], 1) ?>%
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($supplier['warranty_info']['avg_warranty'] > 0): ?>
                                    <div class="fw-bold <?= $supplier['warranty_info']['avg_warranty'] >= 24 ? 'text-success' : 'text-warning' ?>">
                                        <?= number_format($supplier['warranty_info']['avg_warranty'], 1) ?> mo
                                    </div>
                                    <div class="small text-muted">
                                        <?= $supplier['warranty_info']['items_with_warranty'] ?> items
                                    </div>
                                    <div class="rating-stars small">
                                        <?php
                                        $warrantyRating = min(5, ceil($supplier['warranty_info']['avg_warranty'] / 6));
                                        for ($i = 1; $i <= 5; $i++):
                                        ?>
                                            <i class="fas fa-star<?= $i <= $warrantyRating ? '' : '-o' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">No data</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge-performance badge-<?= $supplier['performance_class'] ?> d-block mb-2">
                                    <?= $supplier['performance'] ?>
                                </span>
                                <div class="progress-thin">
                                    <div class="progress-bar bg-<?= $supplier['performance_class'] ?>"
                                         style="width: <?= min($supplier['market_share'] * 3, 100) ?>%">
                                    </div>
                                </div>
                                <div class="small text-muted mt-1">
                                    Rank #<?= $index + 1 ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Summary Footer -->
        <div class="p-3 bg-light border-top">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="small text-muted">Suppliers Analyzed</div>
                    <div class="fw-bold"><?= count($suppliers) ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="small text-muted">Total Assets</div>
                    <div class="fw-bold"><?= number_format($total_assets) ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="small text-muted">Total Spent</div>
                    <div class="fw-bold text-success">BDT <?= number_format($total_spent) ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div class="small text-muted">Avg per Supplier</div>
                    <div class="fw-bold">BDT <?= number_format($supplier_count > 0 ? $total_spent / $supplier_count : 0) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="no-print">
    <?php include_once '../includes/footer.php'; ?>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animate progress bars on page load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
        
        // Animate visual bars
        const barFills = document.querySelectorAll('.bar-fill');
        barFills.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 300);
        });
    });
    
    // Export to CSV function
    function exportToCSV() {
        const table = document.getElementById('supplierTable');
        let csv = [];
        
        // Headers
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push(th.innerText);
        });
        csv.push(headers.join(','));
        
        // Data rows
        table.querySelectorAll('tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach(cell => {
                // Get text without progress bars and icons
                let text = cell.innerText;
                // Remove extra whitespace and newlines
                text = text.replace(/\n/g, ' | ');
                text = text.replace(/\s+/g, ' ').trim();
                // Remove percentage indicators from progress bars
                text = text.replace(/\d+\.?\d*%/g, '').trim();
                // Wrap in quotes if contains comma
                if (text.includes(',')) {
                    text = `"${text}"`;
                }
                rowData.push(text);
            });
            csv.push(rowData.join(','));
        });
        
        // Create and download CSV file
        const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `supplier-performance-<?= date('Y-m-d') ?>.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show confirmation
        alert('CSV file downloaded successfully!');
    }
</script>
</body>
</html>