<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid request");

$stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id=?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) die("Supplier not found");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name === '') $errors[] = "Supplier name required.";

    if (empty($errors)) {
        $chk = $pdo->prepare("SELECT COUNT(*) FROM suppliers WHERE name=? AND id!=?");
        $chk->execute([$name,$id]);
        if ($chk->fetchColumn() > 0) {
            $errors[] = "Supplier already exists.";
        }
    }

    if (empty($errors)) {
        $up = $pdo->prepare("UPDATE suppliers SET name=? WHERE id=?");
        $up->execute([$name,$id]);
        header("Location: supplier_list.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Supplier</title>
<link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container my-4">
<div class="card p-4 shadow-sm">

<h4><i class="fas fa-edit me-2"></i>Edit Supplier</h4>

<?php if($errors): ?>
<div class="alert alert-danger">
<?php foreach($errors as $e) echo "<div>$e</div>"; ?>
</div>
<?php endif; ?>

<form method="POST">
<label class="form-label">Supplier Name</label>
<input type="text" name="name" class="form-control mb-3"
       value="<?= htmlspecialchars($supplier['name']) ?>" required>

<button class="btn btn-primary">
<i class="fas fa-save"></i> Update
</button>
<a href="supplier_list.php" class="btn btn-secondary">Cancel</a>
</form>

</div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
