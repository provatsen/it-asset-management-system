<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

/* ================= DATA ================= */
$product = null;
$message = '';
$searchResults = [];
$closeEditForm = false;

if (isset($_SESSION['close_edit_form'])) {
    $closeEditForm = true;
    unset($_SESSION['close_edit_form']);
}

function isPeripheral($category) {
    return strtolower($category) === 'peripheral';
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$suppliers  = $pdo->query("SELECT name FROM suppliers ORDER BY name ASC")->fetchAll(PDO::FETCH_COLUMN);

/* ================= SEARCH ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $term = trim($_POST['search_term']);
    $type = $_POST['search_type'];

    if ($term === '') {
        $message = "Please enter a search term.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE $type LIKE ? ORDER BY name ASC");
        $stmt->execute(["%$term%"]);
        $searchResults = $stmt->fetchAll();
        if (!$searchResults) {
            $message = "No product found.";
        }
    }
}

/* ================= SELECT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_product'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $stmt->execute([(int)$_POST['product_id']]);
    $product = $stmt->fetch();
}

/* ================= UPDATE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    try {
        $id = (int)$_POST['id'];

        $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id=?");
        $catStmt->execute([$_POST['category_id']]);
        $categoryName = $catStmt->fetchColumn();

        $required = ['name','category_id','purchase_date','price','supplier','factory_name','product_condition'];
        foreach ($required as $r) {
            if (empty($_POST[$r])) {
                throw new Exception(ucfirst(str_replace('_',' ',$r))." is required");
            }
        }

        if (empty($_POST['serial_number']) && !isPeripheral($categoryName)) {
            throw new Exception("Serial number is required for non-peripheral items.");
        }

/* ===== WARRANTY LOGIC (UPDATED) ===== */
        $warranty_input = trim($_POST['warranty']);

        if ($warranty_input === '' || $warranty_input == 0) {
            $warranty = '00 Month';
        } else {
            $warranty = str_pad((int)$warranty_input, 2, '0', STR_PAD_LEFT) . ' Month';
        }
        
        
        $stmt = $pdo->prepare("
            UPDATE products SET
                name=:name,
                product_description=:description,
                serial_number=:serial,
                asset_tag=:asset_tag,
                supplier=:supplier,
                purchase_date=:purchase_date,
                warranty=:warranty,
                price=:price,
                product_condition=:condition,
                requisition_no=:requisition,
                factory_name=:factory,
                brand=:brand,
                model=:model,
                category_id=:category_id,
                category=:category,
                remarks=:remarks
            WHERE id=:id
        ");

        $stmt->execute([
            ':name' => trim($_POST['name']),
            ':description' => trim($_POST['product_description']),
            ':serial' => trim($_POST['serial_number']),
            ':asset_tag' => trim($_POST['asset_tag']),
            ':supplier' => trim($_POST['supplier']),
            ':purchase_date' => $_POST['purchase_date'],
            ':warranty' =>$warranty,
            ':price' => (float)$_POST['price'],
            ':condition' => $_POST['product_condition'],
            ':requisition' => trim($_POST['requisition_no']),
            ':factory' => $_POST['factory_name'],
            ':brand' => trim($_POST['brand']),
            ':model' => trim($_POST['model']),
            ':category_id' => (int)$_POST['category_id'],
            ':category' => $categoryName,
            ':remarks' => trim($_POST['remarks']),
            ':id' => $id
        ]);

        $_SESSION['show_success_alert'] = true;
        $_SESSION['close_edit_form'] = true;
        header("Location: edit_product.php");
        exit;

    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>IT Asset Management|Edit Product</title>
<link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
</style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container my-4">
<div class="card shadow-lg p-4">

<h4 class="mb-3"><i class="fas fa-edit me-2"></i>Edit Product</h4>

<?php if ($message): ?>
<div class="alert alert-warning"><?= $message ?></div>
<?php endif; ?>

<!-- SEARCH -->
<form method="post" class="row g-3 mb-4">
    <div class="col-md-6">
        <input type="text" name="search_term" class="form-control" placeholder="Search product..." required>
    </div>
    <div class="col-md-3">
        <select name="search_type" class="form-select">
            <option value="name">Product Name</option>
            <option value="serial_number">Serial Number</option>
            <option value="supplier">Supplier</option>
        </select>
    </div>
    <div class="col-md-3">
        <button name="search" class="btn btn-primary w-100">
            <i class="fas fa-search me-1"></i>Search
        </button>
    </div>
</form>

<!-- RESULTS -->
<?php if ($searchResults): ?>
<form method="post">
<input type="hidden" name="select_product" value="1">
<div class="list-group mb-4">
<?php foreach ($searchResults as $r): ?>
<button class="list-group-item list-group-item-action" name="product_id" value="<?= $r['id'] ?>">
<strong><?= htmlspecialchars($r['name']) ?></strong>
<small class="text-muted ms-2"><?= htmlspecialchars($r['serial_number']) ?></small>
</button>
<?php endforeach; ?>
</div>
</form>
<?php endif; ?>

<!-- EDIT FORM -->
<?php if ($product && !$closeEditForm): ?>
<form method="post">
<input type="hidden" name="id" value="<?= $product['id'] ?>">

<div class="row g-3">

<!-- BASIC -->
<div class="col-md-4">
<label class="form-label">Product Name *</label>
<input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
</div>

<div class="col-md-4">
<label class="form-label">Category *</label>
<select name="category_id" class="form-select" required>
<?php foreach ($categories as $c): ?>
<option value="<?= $c['id'] ?>" <?= $product['category_id']==$c['id']?'selected':'' ?>>
<?= htmlspecialchars($c['name']) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-4">
<label class="form-label">Serial Number</label>
<input type="text" name="serial_number" class="form-control" value="<?= htmlspecialchars($product['serial_number']) ?>">
</div>

<!-- BRAND / MODEL / TAG -->
<div class="col-md-4">
<label class="form-label">Brand</label>
<input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Model</label>
<input type="text" name="model" class="form-control" value="<?= htmlspecialchars($product['model']) ?>">
</div>

<div class="col-md-4">
<label class="form-label">Asset Tag</label>
<input type="text" name="asset_tag" class="form-control" value="<?= htmlspecialchars($product['asset_tag']) ?>">
</div>

<!-- PURCHASE -->
<div class="col-md-3">
<label class="form-label">Purchase Date *</label>
<input type="date" name="purchase_date" class="form-control" value="<?= $product['purchase_date'] ?>" required>
</div>

<div class="col-md-3">
<label class="form-label">Price *</label>
<input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
</div>

<div class="col-md-3">
<label class="form-label">Warranty (Months)</label>
<input type="number" name="warranty" class="form-control" value="<?= (int)$product['warranty'] ?>">
</div>

<div class="col-md-3">
<label class="form-label">Supplier *</label>
<select name="supplier" class="form-select" required>
<?php foreach ($suppliers as $s): ?>
<option value="<?= $s ?>" <?= $product['supplier']===$s?'selected':'' ?>>
<?= $s ?>
</option>
<?php endforeach; ?>
</select>
</div>

<!-- OTHERS -->
<div class="col-md-4">
<label class="form-label">Concern / Factory *</label>
<select name="factory_name" class="form-select" required>
<?php foreach (['SCL','SAL','SDL','SLL','HO','US','SA'] as $f): ?>
<option value="<?= $f ?>" <?= $product['factory_name']===$f?'selected':'' ?>><?= $f ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-4">
<label class="form-label">Condition *</label>
<select name="product_condition" class="form-select" required>
<option <?= $product['product_condition']=='New'?'selected':'' ?>>New</option>
<option <?= $product['product_condition']=='Used'?'selected':'' ?>>Used</option>
<option <?= $product['product_condition']=='Refurbished'?'selected':'' ?>>Refurbished</option>
</select>
</div>

<div class="col-md-4">
<label class="form-label">Requisition No</label>
<input type="text" name="requisition_no" class="form-control" value="<?= htmlspecialchars($product['requisition_no']) ?>">
</div>

<!-- DESCRIPTION -->
<div class="col-md-12">
<label class="form-label">Description</label>
<textarea name="product_description" class="form-control" rows="2"><?= htmlspecialchars($product['product_description']) ?></textarea>
</div>

<div class="col-md-12">
<label class="form-label">Remarks</label>
<textarea name="remarks" class="form-control" rows="2"><?= htmlspecialchars($product['remarks']) ?></textarea>
</div>

</div>

<div class="text-end mt-4">
<button name="update" class="btn btn-success px-5">
<i class="fas fa-save me-1"></i>Update Product
</button>
</div>

</form>
<?php endif; ?>

</div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['show_success_alert'])): ?>
<script>
Swal.fire({icon:'success',title:'Product Updated',timer:2500,showConfirmButton:false});
</script>
<?php unset($_SESSION['show_success_alert']); endif; ?>

</body>
</html>
