<?php
session_start();
$allowedUsers = ['Provat', 'Kamrul'];
if (!in_array($_SESSION['username'], $allowedUsers)) { die("Access Denied"); }
require_once '../config/db.php';

if (isset($_POST['action'])) {
    $stmt = $pdo->prepare("UPDATE purchase_requisitions SET status = ?, handled_by = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_SESSION['user_id'], $_POST['req_id']]);
}

$requisitions = $pdo->query("SELECT pr.*, u.username FROM purchase_requisitions pr JOIN users u ON pr.requester_id = u.id ORDER BY id DESC")->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <table class="table align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>REQ #</th>
                    <th>Item</th>
                    <th>Requester</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requisitions as $r): ?>
                <tr>
                    <td class="fw-bold"><?= $r['requisition_no'] ?></td>
                    <td><?= $r['item_name'] ?> (<?= $r['quantity'] ?>)</td>
                    <td><?= $r['username'] ?></td>
                    <td><span class="badge bg-secondary"><?= $r['status'] ?></span></td>
                    <td class="text-end">
                        <?php if($r['status'] == 'Pending'): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="req_id" value="<?= $r['id'] ?>">
                            <button name="status" value="Approved" name="action" class="btn btn-sm btn-success">Approve</button>
                            <button name="status" value="Rejected" name="action" class="btn btn-sm btn-danger">Reject</button>
                            <input type="hidden" name="action" value="1">
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>