<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch employee data
$employee = null;
if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $employee = $stmt->fetch();
        
        if (!$employee) {
            throw new Exception("Employee not found");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request - Employee ID not provided");
}

// Organization details
$organizationName = "STERLING GROUP";
$organizationLogo = "/image/sg_logo.png"; // Update this path
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($employee['name']) ?> - Employee Profile</title>
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* General Body and Container Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      color: #333;
      line-height: 1.6;
    }

    .container {
      max-width: 900px;
      margin: 20px auto 40px;
      padding: 25px;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Live View Header inside the container */
    .header-section .org-info {
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: center;
    }
    .header-section .org-logo { height: 50px; }
    .header-section .org-name { font-size: 30px; font-weight: bold; color: #2c3e50; }
    
    /* Page Title */
    .page-title {
      color: #2c3e50;
      margin-top: 25px;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #ddd;
      font-size: 1.8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    /* --- Live View Details: Single Column Layout --- */
    /* Correction: Final style to match the single-column image */
    .detail-group {
        margin-bottom: 20px;
    }
    .detail-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-value {
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid #3498db;
    }
    
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 500; display: inline-block; }
    .status-active { background-color: #d4edda; color: #155724; }
    .status-inactive { background-color: #f8d7da; color: #721c24; }

    /* Action Buttons */
    .action-buttons { display: flex; gap: 12px; margin-bottom: 30px; justify-content: center; }

    /* Utility Classes for Print/Screen */
    .print-only { display: none !important; }
    .screen-only { display: block !important; }

    /* --- Print Styles (No Changes Needed) --- */
    @media print {
      @page { size: A4; margin: 15mm; }
      body { background: #fff; }
      .screen-only, .action-buttons, footer, header { display: none !important; }
      .print-only { display: block !important; }
      .employee-print-table { display: table !important; }
      .container { box-shadow: none; border-radius: 0; max-width: 100%; padding: 0; margin: 0; border: none; }
      .header-section { border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
      .header-section .org-info { justify-content: center; }
      .header-section .org-logo { height: 50px; filter: grayscale(100%); }
      .header-section .org-name { font-size: 22px; }
      .print-date { text-align: left; margin-top: 20px; font-size: 12px; }
      .page-title { border-bottom: none; font-size: 20px; margin-top: 0; margin-bottom: 20px; font-weight: bold; }
      .page-title i { display: none; }
      .employee-print-table { width: 100%; margin-top: 20px; border-collapse: collapse; border: 1px solid #000; }
      .employee-print-table th, .employee-print-table td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 11pt; }
      .employee-print-table th { font-weight: bold; width: 25%; background-color: #f2f2f2; }
      .print-footer { display: block !important; margin-top: 40px; text-align: center; font-style: italic; font-size: 10pt; }
    }

    @media (max-width: 768px) {
      .container { padding: 15px; margin: 15px; }
      .action-buttons { flex-direction: column; }
    }
    </style>
</head>
<body>

<div class="screen-only">
    <?php include '../includes/header.php'; ?>
</div>

<div class="container">
    
    <div class="header-section screen-only">
        <div class="org-info">
            <img src="<?= $organizationLogo ?>" alt="Organization Logo" class="org-logo">
            <span class="org-name"><?= htmlspecialchars($organizationName) ?></span>
        </div>
    </div>
    
    <div class="print-only">
        <div class="header-section">
             <div class="org-info">
                <img src="<?= $organizationLogo ?>" alt="Organization Logo" class="org-logo">
                <span class="org-name"><?= htmlspecialchars($organizationName) ?></span>
            </div>
        </div>
        <div class="print-date">
            Print Date and Time: <?= date('d/m/Y, H:i:s') ?>
        </div>
    </div>


    <h1 class="page-title">
        <i class="fas fa-user"></i>
        <span>Employee Profile</span>
    </h1>

    <div class="action-buttons screen-only">
        <a href="update_employee.php?id=<?= $employee['id'] ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print"></i> Print Profile
        </button>
        <a href="employees.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Live View Details: Single Column Layout -->
    <div class="screen-only">
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-user"></i>Full Name</div>
            <div class="detail-value"><?= htmlspecialchars($employee['name']) ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-id-card"></i>Job ID</div>
            <div class="detail-value"><?= htmlspecialchars($employee['job_id']) ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-envelope"></i>Email</div>
            <div class="detail-value"><?= !empty($employee['email']) ? htmlspecialchars($employee['email']) : 'N/A' ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-phone"></i>Contact Number</div>
            <div class="detail-value"><?= !empty($employee['contact_number']) ? htmlspecialchars($employee['contact_number']) : 'N/A' ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-building"></i>Department</div>
            <div class="detail-value"><?= !empty($employee['department']) ? htmlspecialchars($employee['department']) : 'N/A' ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-briefcase"></i>Designation</div>
            <div class="detail-value"><?= !empty($employee['designation']) ? htmlspecialchars($employee['designation']) : 'N/A' ?></div>
        </div>
         <div class="detail-group">
            <div class="detail-label"><i class="fas fa-map-marker-alt"></i>Location</div>
            <div class="detail-value"><?= !empty($employee['location']) ? htmlspecialchars($employee['location']) : 'N/A' ?></div>
        </div>
        <div class="detail-group">
            <div class="detail-label"><i class="fas fa-circle-check"></i>Status</div>
            <div class="detail-value">
                <span class="status-badge status-<?= $employee['status'] ?>"><?= ucfirst($employee['status']) ?></span>
            </div>
        </div>
    </div>

    <!-- Print View Table: Hidden on screen -->
    <table class="employee-print-table print-only">
        <tbody>
            <tr><th>Full Name</th><td><?= htmlspecialchars($employee['name']) ?></td></tr>
            <tr><th>Job ID</th><td><?= htmlspecialchars($employee['job_id']) ?></td></tr>
            <tr><th>Email</th><td><?= !empty($employee['email']) ? htmlspecialchars($employee['email']) : 'N/A' ?></td></tr>
            <tr><th>Contact Number</th><td><?= !empty($employee['contact_number']) ? htmlspecialchars($employee['contact_number']) : 'N/A' ?></td></tr>
            <tr><th>Department</th><td><?= !empty($employee['department']) ? htmlspecialchars($employee['department']) : 'N/A' ?></td></tr>
            <tr><th>Designation</th><td><?= !empty($employee['designation']) ? htmlspecialchars($employee['designation']) : 'N/A' ?></td></tr>
            <tr><th>Location</th><td><?= !empty($employee['location']) ? htmlspecialchars($employee['location']) : 'N/A' ?></td></tr>
            <tr><th>Status</th><td><?= ucfirst($employee['status']) ?></td></tr>
        </tbody>
    </table>

    <div class="print-footer print-only">
        This document is system-generated and does not require authorization.
    </div>
</div>

<div class="screen-only">
    <?php include '../includes/footer.php'; ?>
</div>

</body>
</html>