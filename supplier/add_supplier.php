<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$errors = [];
$supplier_name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name = trim($_POST['supplier_name'] ?? '');

    // Validation
    if ($supplier_name === '') {
        $errors[] = "Supplier name is required.";
    }

    // Check duplicate
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM suppliers WHERE name = ?");
        $stmt->execute([$supplier_name]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "This supplier already exists.";
        }
    }

    // Insert
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO suppliers (name) VALUES (?)");
            $stmt->execute([$supplier_name]);

            $_SESSION['supplier_success'] = true;
            header('Location: add_supplier.php');
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IT-Asset Management | Add Supplier</title>
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">

    <style>
        body {
            background: #f0f2f5;
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .card-box {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .title-gradient {
            font-weight: 800;
            background: linear-gradient(135deg,#667eea,#764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
        }
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #4a5568;
        }
        .required::after {
            content: " *";
            color: #e53e3e;
        }
        .btn-primary {
            background: linear-gradient(135deg,#667eea,#764ba2);
            border: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

<?php include_once '../includes/header.php'; ?>

<div class="container">
    <div class="card-box animate__animated animate__fadeIn">
        <h3 class="title-gradient mb-4">
            <i class="fas fa-truck me-2"></i>Add Supplier
        </h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label required">
                    Supplier Name
                </label>
                <input type="text"
                       name="supplier_name"
                       class="form-control"
                       placeholder="e.g. Computer Source Ltd"
                       value="<?= htmlspecialchars($supplier_name) ?>"
                       required>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Supplier
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['supplier_success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Saved!',
    text: 'Supplier added successfully.',
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['supplier_success']); endif; ?>

</body>
</html>
