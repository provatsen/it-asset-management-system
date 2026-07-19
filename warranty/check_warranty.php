<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config/db.php';

$product = null;
$serial = '';
$message = '';

$certificate_id = "SGIT-" . date("Y") . "-" . rand(10000,99999);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['serial'])) {
    $serial = trim($_POST['serial']);
    $stmt = $pdo->prepare("SELECT * FROM products WHERE serial_number = ?");
    $stmt->execute([$serial]);
    $product = $stmt->fetch();

    if (!$product) {
        $message = "No product found with serial number: " . htmlspecialchars($serial);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="icon" href="/image/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Asset Warranty Tracker</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
body {
    background: #f4f6fa;
    font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Main content wrapper */
main {
    flex: 1;
}

/* SEARCH */
.search-box {
    max-width: 850px;
    margin: auto;
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0,0,0,.08)
}

/* CERTIFICATE */
.result-container {
    width: 100%;
    max-width: 210mm;
    min-height: 297mm; /* A4 height */
    margin: 20px auto;
    background: #fff;
    padding: 10mm;
    border: 2px solid #000;
    page-break-inside: avoid;
    position: relative;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
}

.report-header {
    text-align: center;
    border-bottom: 2px solid #000;
    padding-bottom: 10px
}

.report-logo {
    width: 80px;
    display: block;
    margin: 0 auto 6px
}

.report-meta {
    font-size: 13px;
    margin-top: 6px
}

.product-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    flex-grow: 1;
    table-layout: fixed;
}

.product-table th,
.product-table td {
    border: 1px solid #000;
    padding: 8px 10px;
    font-size: 13px
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.product-table th {
    background: #f1f1f1;
    width: 35%
}

.warranty-box {
    text-align: center;
    font-weight: 700;
    margin-top: 18px;
    padding: 12px;
    border: 2px solid #000
}

.signature-box {
    border-top: 2px solid #000;
    margin-top: 60px;
    padding-top: 8px;
    font-weight: 600;
}

.qr-box img {
    width: 120px
}

.report-footer {
    text-align: center;
    margin-top: 20px; /* Push to bottom */
    padding-top: 10px;
    font-size: 12px;
    border-top: 1px solid #000;
}

/* PRINT STYLES - FIXED */
@media print {

                    @page {
                        size: A4 portrait;
                        margin: 12mm 10mm;
                    }
                
                    body {
                        background: #fff !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        justify-content: center;
                        align-items: center;
                        font-family: "Segoe UI", Arial, sans-serif;
                        color: #000;
                    }
                
                    /* Hide UI elements */
                    /* 4. Hide all unnecessary elements */
                    .no-print, header, footer, .search-box, .print-button {
                        display: none !important;
                        height: 0 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }
                
                    main,
                    .container {
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 100% !important;
                        display: flex;
                        justify-content: center;
                    }
                
                    /* Certificate centered */
                    .result-container { 
                        width: 100% !important; 
                        min-height: 100% !important; 
                        height: 100% !important; 
                        margin: 0 !important; 
                        padding: 0mm !important; 
                        border: none !important; 
                        box-shadow: none !important; 
                        page-break-inside: avoid !important; 
                        page-break-after: avoid !important; 
                        position: relative; 
                        display: block !important; 
                        
                    }
                
                    /* Header */
                    .report-header {
                        text-align: center;
                        padding-bottom: 6px !important;
                        margin-bottom: 8px !important;
                        border-bottom: 2px solid #000;
                    }
                
                    .report-logo {
                        width: 80px !important;
                        margin-bottom: 5px;
                    }
                
                    .report-meta {
                        font-size: 12px !important;
                    }
                
                  
                
                    /* Status */
                    .warranty-box {
                        margin-top: 12px !important;
                        padding: 8px !important;
                        border: 2px solid #000;
                        font-size: 15px;
                        font-weight: 700;
                        text-align: center;
                    }
                
                    /* Signature */
                    .signature-box {
                        border-top: 2px solid #000;
                        margin-top: 80px !important;
                        padding-top: 6px;
                        font-size: 12px;
                    }
                
                    .qr-box img {
                        width: 100px !important;
                        
                    }
                
                   
                    .report-footer {
                        position: absolute;
                        bottom: 15mm;
                        left: 20mm;
                        right: 20mm;
                        text-align: center;
                        font-size: 10px !important;
                        border-top: 1px solid #000 !important;
                        padding-top: 5px;
                        margin-top: 0 !important;
                    }
                
                    /* Stop second page */
                    /* 7. Force page-break prevention */
                    html, body, .result-container {
                        page-break-after: avoid !important;
                        page-break-before: avoid !important;
                    }
            }

</style>
</head>

<body>

<div class="no-print">
<?php include '../includes/header.php'; ?>
</div>

<main>
<div class="container my-4">

<!-- SEARCH -->
<div class="search-box no-print">
<h4 class="text-center fw-bold">
<i class="fas fa-shield-alt"></i> Warranty Tracker
</h4>
<p class="text-center text-muted">
Check product warranty by serial number
</p>

<form method="post">
<div class="d-flex gap-2">
<input class="form-control"
name="serial"
placeholder="Enter serial number"
value="<?=htmlspecialchars($serial)?>" required>

<button class="btn btn-dark">
<i class="fas fa-search"></i> Check
</button>
</div>
</form>
</div>

<?php if($message): ?>
<div class="alert alert-danger text-center mt-3 no-print">
<?=$message?>
</div>
<?php endif; ?>

<?php if($product):
$purchase = new DateTime($product['purchase_date']);
$warranty = (int)$product['warranty'];
$end = clone $purchase;
$end->modify("+$warranty months");

$today = new DateTime();
$days = $today->diff($end)->days;
$status = ($end >= $today) ? "VALID" : "EXPIRED";
?>

<!-- CERTIFICATE -->
<div class="result-container mt-4" id="printArea">

<div class="report-header">

<img src="../image/sg_logo.png" class="report-logo" alt="Logo">

<h5 class="fw-bold">STERLING GROUP</h5>
<h6>IT Asset Warranty Certificate</h6>

<div class="report-meta">
Certificate ID: <strong><?=$certificate_id?></strong><br>
Generated: <?=date('d M Y, h:i A')?> | Issued By: <strong><?= $_SESSION['username'] ?></strong>
</div>

</div>

<table class="product-table">
<tr><th>Product Name</th><td><?=htmlspecialchars($product['name'])?></td></tr>
<tr><th>Description</th><td><?=htmlspecialchars($product['product_description'])?></td></tr>
<tr><th>Brand</th><td><?=htmlspecialchars($product['brand'])?></td></tr>
<tr><th>Model</th><td><?=htmlspecialchars($product['model'])?></td></tr>
<tr><th>Serial Number</th><td><?=htmlspecialchars($product['serial_number'])?></td></tr>
<tr><th>Supplier</th><td><?=htmlspecialchars($product['supplier'])?></td></tr>
<tr><th>Purchase Date</th><td><?=$purchase->format('d M Y')?></td></tr>
<tr><th>Warranty Period</th><td><?=$warranty?> Months</td></tr>
<tr><th>Warranty End</th><td><?=$end->format('d M Y')?></td></tr>
</table>

<div class="warranty-box">
STATUS: <?=$status?> |
<?=($status=="VALID")?"$days Days Remaining":"Expired $days Days Ago"?>
</div>

<div class="row mt-4">

<div class="col-6 text-center">
<div class="signature-box">
Authorized Signature
</div>
</div>

<div class="col-6 text-center qr-box">
<img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=https://www.itsg.sdldts.net/warranty/verify.php?serial=<?=$product['serial_number']?>">

<div class="small mt-1">Scan to Verify</div>
</div>

</div>

<div class="report-footer">
This certificate is generated by IT Asset Management System.
</div>

<div class="text-center mt-3 no-print print-button">
<button onclick="window.print()" class="btn btn-dark">
<i class="fas fa-print"></i> Print Certificate
</button>
</div>

</div>
<?php endif; ?>

</div>
</main>

<div class="no-print">
<?php include '../includes/footer.php'; ?>
</div>

<script>
// Print optimization script
document.addEventListener('DOMContentLoaded', function() {
    // Store original body classes
    const originalBodyClass = document.body.className;
    
    // Add event listener for print
    window.addEventListener('beforeprint', function() {
        // Hide everything except the certificate
        document.body.className = 'print-mode';
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = 'none';
        });
    });
    
    window.addEventListener('afterprint', function() {
        // Restore original state
        document.body.className = originalBodyClass;
        document.querySelectorAll('.no-print').forEach(el => {
            el.style.display = '';
        });
    });
});
</script>

</body>
</html>