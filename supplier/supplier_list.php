<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$stmt = $pdo->query("
    SELECT s.id, s.name, COUNT(p.id) AS usage_count
    FROM suppliers s
    LEFT JOIN products p ON p.supplier = s.name
    GROUP BY s.id, s.name
    ORDER BY s.name ASC
");
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IT-Asset Management || Supplier List</title>
<link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Google Fonts Import -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    /* Base Body Styling */
    body {
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        font-weight: 400;
        font-size: 16px;
        color: #333;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        margin: 0;
        min-height: 100vh;
    }
    
    /* Container Centering */
    .container.my-4 {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Card Styling */
    .card-box {
        background: #fff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
        margin-bottom: 20px;
    }
    
    .print-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .print-header img {
        height: 60px;
    }

    .header-center {
        text-align: center;
        flex: 1;
    }

    .header-center h1 {
        font-size: 18pt;
        font-weight: 700;
        margin: 0;
    }

    .header-center h2 {
        font-size: 12pt;
        margin: 2px 0;
        font-weight: 500;
    }

    .header-center h3 {
        font-size: 13pt;
        margin: 4px 0 0;
        font-weight: 600;
        text-decoration: underline;
    }

    .header-right {
        font-size: 9pt;
        text-align: right;
        white-space: nowrap;
    }
    
    /* Table Styling */
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    /* Header Styling */
    h4 {
        color: #2c3e50;
        font-weight: 600;
    }
    
    /* Button Styling */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4090 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border: none;
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #5d0ab5 0%, #1c68e6 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .btn {
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    /* Badge Styling */
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.9em;
        border-radius: 20px;
    }
    
    /* Action Buttons */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.3rem;
    }
    
    
    
   /* ================= PRINT STYLES ================= */
   @media print {

    @page {
        size: A4;
        margin: 0;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
    }

    header,
    footer,
    .navbar,
    .btn,
    .no-print {
        display: none !important;
    }

    body {
        background: #fff !important;
        color: #000 !important;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 11pt;
        line-height: 1.4;
    }

    /* Push content slightly from top manually */
    .container {
        margin: 0 !important;
        padding: 10mm 15mm 15mm 15mm !important;
        max-width: 100% !important;
    }

    .card-box {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* ===== PRINT HEADER ===== */
    .print-header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 6px;
        margin-bottom: 10px;
    }

    .print-header img {
        height: 60px;
    }

    .header-center {
        flex: 1;
        text-align: center;
    }

    .header-center h1 {
        font-size: 18pt;
        margin: 0;
        font-weight: 700;
    }

    .header-center h2 {
        font-size: 12pt;
        margin: 2px 0;
    }

    .header-center h3 {
        font-size: 13pt;
        margin: 3px 0 0;
        font-weight: 600;
        text-decoration: underline;
    }

    .header-right {
        font-size: 9pt;
        text-align: right;
        white-space: nowrap;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 10.5pt;
    }

    th {
        background: #f0f0f0 !important;
        print-color-adjust: exact;
    }

    tr {
        page-break-inside: avoid;
    }

    .badge {
        background: none !important;
        border: 1px solid #000;
        color: #000;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 10pt;
    }
}



</style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<div class="container my-4">
        <div class="card-box">
        <!-- PRINT HEADER -->
        <div class="print-header">
            <div class="header-left">
                <img src="../image/sg_logo.png" alt="Sterling Group Logo">
            </div>
        
            <div class="header-center">
                <h1>STERLING GROUP</h1>
                <h2>IT-Asset Management System</h2>
            </div>
        
            <div class="header-right">
                <div>Print Date:</div>
                <strong><?= date('d F Y, h:i A') ?></strong>
            </div>
        </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">
                <i class="fas fa-truck me-2"></i>Supplier List
            </h4>

            <div class="no-print">
                <a href="add_supplier.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Supplier
                </a>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">SN</th>
                        <th>Supplier Name</th>
                        <th class="text-center" style="width:140px">Used In Assets</th>
                        <th class="no-print text-center" style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($suppliers): ?>
                        <?php foreach ($suppliers as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $s['usage_count'] > 0 ? 'bg-primary' : 'bg-secondary' ?>">
                                    <?= (int)$s['usage_count'] ?>
                                </span>
                            </td>
                            <td class="no-print text-center">
                                <a href="edit_supplier.php?id=<?= $s['id'] ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($s['usage_count'] > 0): ?>
                                    <button class="btn btn-danger btn-sm" disabled
                                            title="Cannot delete. Supplier is linked with assets.">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="delete_supplier.php?id=<?= $s['id'] ?>"
                                       onclick="return confirm('Delete this supplier?')"
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No suppliers found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>