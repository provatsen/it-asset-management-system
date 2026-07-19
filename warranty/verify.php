<?php
require_once '../config/db.php';

$serial = htmlspecialchars($_GET['serial'] ?? ''); 
$stmt = $pdo->prepare("SELECT * FROM products WHERE serial_number=?");
$stmt->execute([$serial]);
$product = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/image/favicon.ico">
    <title>Certificate Verification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --success: #059669;
            --danger: #dc2626;
            --glass: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: radial-gradient(circle at top right, #e0e7ff 0%, #f8fafc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            width: 90%;
            max-width: 420px;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            text-align: center;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .icon-header {
            font-size: 3.5rem;
            margin-bottom: 20px;
        }

        .success-icon {
            color: var(--success);
            filter: drop-shadow(0 4px 10px rgba(5, 150, 105, 0.2));
            animation: pulse 2s infinite;
        }

        .error-icon {
            color: var(--danger);
        }
        
        h1 {
            margin: 0 0 8px 0;
            color: #1e293b;
            font-size: 1.5rem;
        }

        h2 {
            margin: 0 0 8px 0;
            color: #1e293b;
            font-size: 1.5rem;
        }

        .status-text {
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 30px;
            display: block;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: left;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-item:last-child { margin-bottom: 0; }

        .info-item i {
            width: 35px;
            height: 35px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-size: 0.9rem;
        }

        .info-content label {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
        }

        .info-content span {
            font-weight: 600;
            color: #334155;
            font-size: 0.95rem;
        }

        .btn-print {
            margin-top: 25px;
            background: #1e293b;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print:hover {
            background: #000;
            transform: translateY(-2px);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<div class="card">
    <?php if($product): ?>
        <div class="icon-header">
            <i class="fa-solid fa-circle-check success-icon"></i>
        </div>
        <h1>STERLING GROUP</h1>
        <h2>Authenticity Verified</h2>
        <span class="status-text" style="color: var(--success);">Verified Record Found</span>
        
        <div class="info-box">
            <div class="info-item">
                <i class="fa-solid fa-box"></i>
                <div class="info-content">
                    <label>Product Name</label>
                    <span><?= htmlspecialchars($product['name']) ?></span>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fa-solid fa-hashtag"></i>
                <div class="info-content">
                    <label>Serial Number</label>
                    <span><?= htmlspecialchars($product['serial_number']) ?></span>
                </div>
            </div>
            
            <div class="info-item">
                <i class="fa-solid fa-calendar-check"></i>
                <div class="info-content">
                    <label>Expiry / Warranty</label>
                    <span><?= htmlspecialchars($product['purchase_date']) ?></span>
                </div>
            </div>
        </div>

        <button class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Record
        </button>

    <?php else: ?>
        <div class="icon-header">
            <i class="fa-solid fa-circle-exclamation error-icon"></i>
        </div>
        <h2>Unable to Verify</h2>
        <span class="status-text" style="color: var(--danger);">No Record Found</span>
        
        <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6;">
            We could not find any product matching the serial number: <br>
            <strong style="color: #1e293b;"><?= $serial ?></strong>
        </p>

        <a href="javascript:history.back()" class="btn-print" style="text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Go Back
        </a>
    <?php endif; ?>
</div>

</body>
</html>