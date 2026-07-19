<?php
session_start();
// Keep error reporting off for a clean print view, but log errors internally
error_reporting(E_ALL & ~E_DEPRECATED); 
ini_set('display_errors', 0);

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$id = $_GET['id'] ?? null;
if (!$id) die("ID missing.");

try {
    // Optimized Query: Fetches User, Employee Department, and Requisition data
    $stmt = $pdo->prepare("
        SELECT 
            pr.*, 
            u.username, 
            e.department AS emp_dept
        FROM purchase_requisitions pr 
        JOIN users u ON pr.requester_id = u.id 
        LEFT JOIN employees e ON u.username = e.name 
        WHERE pr.id = ?
    ");
    $stmt->execute([$id]);
    $req = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$req) die("Record not found.");

} catch (PDOException $e) {
    die("Connection failed.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>REQ_<?= htmlspecialchars($req['requisition_no'] ?? '00000') ?></title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #333; margin: 0; padding: 20px; background: #f4f4f4; }
        .print-container { background: white; width: 210mm; min-height: 297mm; margin: auto; padding: 20mm; box-sizing: border-box; }
        
        .header-table { width: 100%; border-bottom: 2px solid #333; margin-bottom: 20px; }
        .logo { height: 60px; }
        .company-name { font-size: 26px; font-weight: 800; margin: 0; text-transform: uppercase; }
        
        .info-grid { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
        
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .details-table th, .details-table td { border: 1px solid #333; padding: 12px; text-align: left; }
        .details-table th { background: #f9f9f9; width: 30%; font-size: 12px; text-transform: uppercase; }
        
        .sig-section { margin-top: 80px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; text-align: center; }
        .sig-box { border-top: 1px solid #333; padding-top: 5px; font-size: 11px; font-weight: bold; }

        @media print {
            body { background: none; padding: 0; }
            .print-container { box-shadow: none; margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="print-container">
    <table class="header-table">
        <tr>
            <td><img src="/image/sg_logo.png" class="logo"></td>
            <td style="text-align: right;">
                <h1 class="company-name">Sterling Group</h1>
                <div style="font-weight: 600;">Purchase Requisition Form</div>
            </td>
        </tr>
    </table>

    <div class="info-grid">
        <div>
            <strong>Requisition No:</strong> #<?= htmlspecialchars($req['requisition_no'] ?? '') ?><br>
            <strong>Status:</strong> <?= htmlspecialchars($req['status'] ?? 'PENDING') ?>
        </div>
        <div style="text-align: right;">
            <strong>Create Date:</strong> <?= date('d-M-Y', strtotime($req['created_at'] ?? 'now')) ?><br>
            <strong>Print Date:</strong> <?= date('d-M-Y h:i A') ?>
        </div>
    </div>

    <table class="details-table">
        <tr>
            <th>Unit Name</th>
            <td><?= htmlspecialchars($req['unit_name'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <th>Department</th>
            <td><?= htmlspecialchars($req['emp_dept'] ?? 'Not Specified') ?></td>
        </tr>
        <tr>
            <th>Item Name</th>
            <td style="font-weight: bold;"><?= htmlspecialchars($req['item_name'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td><?= htmlspecialchars($req['quantity'] ?? '0') ?> Units</td>
        </tr>
        <tr>
            <th>Unit Rate</th>
            <td>৳ <?= number_format((float)($req['rate'] ?? 0), 2) ?></td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td style="font-weight: 800; font-size: 16px;">৳ <?= number_format((float)($req['estimated_price'] ?? 0), 2) ?></td>
        </tr>
        <tr>
            <th>Reason for Purchase</th>
            <td style="height: 60px; vertical-align: top;"><?= nl2br(htmlspecialchars($req['reason_for_purchase'] ?? '')) ?></td>
        </tr>
    </table>

    <div class="sig-section">
        <div class="sig-box">CREATED BY<br><small>(<?= htmlspecialchars($req['username'] ?? '') ?>)</small></div>
        <div class="sig-box">DEPARTMENT HEAD</div>
        <div class="sig-box">GM-ADMIN</div>
        <div class="sig-box">APPROVED BY<br><small>(Provat / Kamrul)</small></div>
    </div>

    <div style="margin-top: 60px; text-align: center; font-size: 10px; color: #777; border-top: 1px dashed #ccc; padding-top: 10px;">
        This document is generated by the Sterling Group IT Asset Management System.
    </div>
</div>

<div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #333; color: #fff; border: none; border-radius: 5px;">Print Document</button>
</div>

</body>
</html>