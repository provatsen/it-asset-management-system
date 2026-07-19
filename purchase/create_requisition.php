<?php
session_start();
require_once '../config/db.php'; // Ensure this path is correct

// 1. Generate Automatic 5-digit Requisition Number
$stmt = $pdo->query("SELECT MAX(id) as last_id FROM purchase_requisitions");
$last_id = $stmt->fetch()['last_id'] ?? 0;
$req_no = str_pad($last_id + 1, 5, '0', STR_PAD_LEFT);

// 2. Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // Insert Master Data
        $stmtMain = $pdo->prepare("INSERT INTO purchase_requisitions (requisition_no, unit_name, reason_for_purchase, requester_id) VALUES (?, ?, ?, ?)");
        $stmtMain->execute([$req_no, $_POST['unit'], $_POST['reason'], $_SESSION['user_id']]);
        
        $requisition_id = $pdo->lastInsertId();

        // Insert Multiple Items
        $stmtItem = $pdo->prepare("INSERT INTO purchase_requisition_items (requisition_id, item_name, quantity, rate, total_price) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($_POST['item_name'] as $key => $name) {
            $qty = $_POST['qty'][$key];
            $rate = $_POST['rate'][$key];
            $total = $qty * $rate;
            $stmtItem->execute([$requisition_id, $name, $qty, $rate, $total]);
        }

        $pdo->commit();
        header("Location: view_all_requisitions.php?msg=success");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error saving requisition: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Requisition-IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7fe; font-family: 'Inter', sans-serif; }
        .form-card { background: #fff; border-radius: 15px; border-top: 5px solid #0d6efd; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .table thead { background: #1e293b; color: white; }
        .btn-add { background: #10b981; color: white; border: none; }
        .btn-add:hover { background: #059669; color: white; }
        .grand-total-box { background: #f8fafc; border: 2px solid #e2e8f0; font-size: 1.2rem; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-uppercase m-0">IT Purchase Requisition</h4>
                    <span class="badge bg-primary px-3 py-2">REQ #<?= $req_no ?></span>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" id="reqForm">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Date</label>
                            <input type="text" class="form-control bg-light" value="<?= date('d-M-Y') ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Department / Unit</label>
                            <input type="text" name="unit" class="form-control" placeholder="Enter Unit Name" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">Reason for Purchase</label>
                            <input type="text" name="reason" class="form-control" placeholder="e.g. New Joiner Laptop Setup" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width: 45%;">Item Description</th>
                                    <th style="width: 12%;">Qty</th>
                                    <th style="width: 15%;">Unit Rate</th>
                                    <th style="width: 20%;">Total</th>
                                    <th style="width: 8%;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemBody">
                                <tr>
                                    <td><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></td>
                                    <td><input type="number" name="qty[]" class="form-control qty" min="1" required oninput="calculateRow(this)"></td>
                                    <td><input type="number" name="rate[]" class="form-control rate" step="0.01" required oninput="calculateRow(this)"></td>
                                    <td><input type="text" class="form-control row-total bg-light" readonly value="0.00"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger border-0" onclick="removeRow(this)">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3 align-items-center">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-add fw-bold" onclick="addRow()">
                                <i class="bi bi-plus-circle-fill me-2"></i> ADD ANOTHER ITEM
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-end">
                                <span class="me-3 fw-bold text-muted">ESTIMATED GRAND TOTAL:</span>
                                <input type="text" id="grandTotal" class="form-control grand-total-box text-end fw-bold text-primary" style="width: 200px;" readonly value="0.00">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-end">
                        <button type="reset" class="btn btn-light px-4 me-2">CANCEL</button>
                        <button type="submit" class="btn btn-dark px-5 fw-bold py-2">SUBMIT REQUISITION</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Add a new row to the table
    function addRow() {
        const tbody = document.getElementById('itemBody');
        const newRow = `
            <tr>
                <td><input type="text" name="item_name[]" class="form-control" placeholder="Item Name" required></td>
                <td><input type="number" name="qty[]" class="form-control qty" min="1" required oninput="calculateRow(this)"></td>
                <td><input type="number" name="rate[]" class="form-control rate" step="0.01" required oninput="calculateRow(this)"></td>
                <td><input type="text" class="form-control row-total bg-light" readonly value="0.00"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger border-0" onclick="removeRow(this)">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </td>
            </tr>`;
        tbody.insertAdjacentHTML('beforeend', newRow);
    }

    // Remove a row
    function removeRow(btn) {
        const rows = document.querySelectorAll('#itemBody tr');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            updateGrandTotal();
        } else {
            alert("At least one item is required.");
        }
    }

    // Calculate total for a single row
    function calculateRow(input) {
        const row = input.closest('tr');
        const qty = row.querySelector('.qty').value || 0;
        const rate = row.querySelector('.rate').value || 0;
        const total = (qty * rate).toFixed(2);
        row.querySelector('.row-total').value = total;
        updateGrandTotal();
    }

    // Calculate total for the entire form
    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.row-total').forEach(el => {
            grandTotal += parseFloat(el.value) || 0;
        });
        document.getElementById('grandTotal').value = grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2});
    }
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>