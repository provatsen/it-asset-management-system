<?php
session_start();
require_once '../config/db.php';

// Fetch all requisitions
$list = $pdo->query("SELECT pr.*, u.username FROM purchase_requisitions pr JOIN users u ON pr.requester_id = u.id ORDER BY pr.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Requisitions | Sterling Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <?php include '../includes/header.php'; ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">All Requisitions</h4>
            <a href="create_requisition.php" class="btn btn-primary btn-sm">+ New Request</a>
        </div>

        <div class="table-responsive bg-white shadow-sm rounded">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Req #</th>
                        <th>Create Date</th>
                        <th>Item Description</th>
                        <th>Requester</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $item): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= $item['requisition_no'] ?></td>
                        <td><?= date('d-M-Y', strtotime($item['created_at'])) ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($item['item_name']) ?></div>
                            <small class="text-muted"><?= $item['quantity'] ?> Qty @ <?= number_format($item['rate'], 2) ?></small>
                        </td>
                        <td><?= htmlspecialchars($item['username']) ?></td>
                        <td>
                            <span class="badge rounded-pill <?= $item['status'] == 'Approved' ? 'bg-success' : ($item['status'] == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                <?= $item['status'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="print_requisition.php?id=<?= $item['id'] ?>" target="_blank" class="btn btn-dark btn-sm px-3">
                                <i class="fas fa-print me-1"></i> View & Print
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>