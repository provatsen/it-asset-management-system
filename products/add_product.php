<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Load Category From DB
$categoryOptions = [];
try {
    $catStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categoryOptions = $catStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}

// Load Suppliers From DB
$supplierOptions = [];
try {
    $supStmt = $pdo->query("SELECT id, name FROM suppliers ORDER BY name ASC");
    $supplierOptions = $supStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching suppliers: " . $e->getMessage());
}


$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $product_description = trim($_POST['product_description'] ?? '');
    $serial_number = trim($_POST['serial_number'] ?? '');
    $asset_tag = trim($_POST['asset_tag'] ?? '');
    $supplier = trim($_POST['supplier'] ?? '');
    $purchase_date = $_POST['purchase_date'] ?? '';
    $warranty_input = trim($_POST['warranty'] ?? '');

    if ($warranty_input === '' || $warranty_input == 0) {
        $warranty = '00 Month';
    } else {
        $warranty = str_pad((int)$warranty_input, 2, '0', STR_PAD_LEFT) . ' Month';
    }

    $price = $_POST['price'] ?? '';
    $product_condition = trim($_POST['product_condition'] ?? 'New');
    $requisition_no = trim($_POST['requisition_no'] ?? '');
    $factory_name = trim($_POST['factory_name'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');

    // Validation
    if (empty($name)) $errors[] = "Product name is required.";
    if (empty($category)) $errors[] = "Product category is required.";
    
    // Modified: Only require serial number if category is NOT "Peripheral"
    if (empty($serial_number) && strtolower($category) !== 'peripheral') {
        $errors[] = "Serial number is required for non-peripheral items.";
    }
    
    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = "Valid price is required.";
    }
    if (empty($factory_name)) $errors[] = "Concern name is required.";

    // Modified: Only check for duplicate serial number if it's provided
    if (empty($errors) && !empty($serial_number)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE serial_number = ?");
        $stmt->execute([$serial_number]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Serial number already exists.";
        }
    }

    if (empty($errors)) {
        try {
            // For peripheral items with empty serial number, set it to NULL in database
            $serial_number_db_value = (strtolower($category) === 'peripheral' && empty($serial_number)) ? NULL : $serial_number;
            
            $stmt = $pdo->prepare("INSERT INTO products 
                (name, category, brand, model, product_description, serial_number, asset_tag, supplier, purchase_date, warranty, price, product_condition, requisition_no, factory_name, remarks)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name, $category, $brand, $model, $product_description, 
                $serial_number_db_value, $asset_tag, $supplier, $purchase_date, 
                $warranty, $price, $product_condition, $requisition_no, 
                $factory_name, $remarks
            ]);

            $_SESSION['show_success_alert'] = true;
            header('Location: add_product.php');
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>IT Asset Management | Add Product</title>
  <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  
  <style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --light-bg: #f0f2f5;
        --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        --transition-smooth: all 0.3s ease;
    }

    body {
      background-color: var(--light-bg);
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    .form-container {
      max-width: 1100px;
      margin: 30px auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: var(--card-shadow);
    }

    .form-title {
      font-weight: 800;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 30px;
      text-align: center;
      font-size: 2rem;
    }

    .form-label {
      font-weight: 600;
      font-size: 0.8rem;
      color: #4a5568;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .form-control, .form-select {
      border: 1.5px solid #e2e8f0;
      padding: 10px 15px;
      border-radius: 8px;
      transition: var(--transition-smooth);
    }

    .required-field::after {
      content: ' *';
      color: #e53e3e;
    }

    .section-divider {
      border-bottom: 2px solid #f7fafc;
      margin: 30px 0;
    }
    
    .btn-primary { 
        background: var(--primary-gradient); 
        border: none; 
        font-weight: 700; 
        padding: 12px 30px; 
    }
    .btn-warning { 
        background: var(--warning-gradient); 
        border: none; 
        color: #fff; 
        font-weight: 700; 
        padding: 12px 30px; 
    }
    
    /* Custom style for warranty input */
    .warranty-input-group .input-group-text {
        border-radius: 8px 0 0 8px;
        background-color: #f8f9fa;
        border: 1.5px solid #e2e8f0;
        border-right: none;
    }
    
    .warranty-input-group .form-control {
        border-radius: 0 8px 8px 0;
        border-left: none;
    }
  </style>
</head>
<body>

<?php include_once '../includes/header.php'; ?>

<div class="container">
  <div class="form-container animate__animated animate__fadeIn">
    <h3 class="form-title"><i class="fas fa-plus-circle me-2"></i>Product Registration</h3>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" id="productForm">
      
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label required-field"><i class="fas fa-box me-1"></i> Product Name</label>
          <input type="text" name="name" class="form-control" placeholder="HP Laptop..." value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label required-field"><i class="fas fa-list me-1"></i> Category</label>
          <select id="category" name="category" class="form-select" required>
            <option value="">-- Choose --</option>
            <?php foreach ($categoryOptions as $cat): ?>
              <option value="<?= htmlspecialchars($cat['name']) ?>" <?= (isset($_POST['category']) && $_POST['category'] === $cat['name']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label required-field" id="serialLabel"><i class="fas fa-barcode me-1"></i> Serial Number</label>
          <input type="text" id="serial_number" name="serial_number" class="form-control" value="<?= htmlspecialchars($_POST['serial_number'] ?? '') ?>" placeholder="Enter serial number" required>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label">Brand</label>
          <input type="text" name="brand" class="form-control" placeholder="e.g., HP, Dell, Lenovo" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Model</label>
          <input type="text" name="model" class="form-control" placeholder="e.g., EliteBook 840 G7" value="<?= htmlspecialchars($_POST['model'] ?? '') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Asset Tag</label>
          <input type="text" name="asset_tag" class="form-control" placeholder="e.g., ASSET-001" value="<?= htmlspecialchars($_POST['asset_tag'] ?? '') ?>">
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label">Detailed Description</label>
        <textarea name="product_description" class="form-control" rows="2" placeholder="RAM, Processor, Storage..."><?= htmlspecialchars($_POST['product_description'] ?? '') ?></textarea>
      </div>

      <div class="section-divider"></div>

      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <label class="form-label">Purchase Date</label>
          <input type="date" id="purchase_date" name="purchase_date" class="form-control" value="<?= htmlspecialchars($_POST['purchase_date'] ?? date('Y-m-d')) ?>">
        </div>
        
        <div class="col-md-3">
          <label class="form-label required-field">
            <i class="fas fa-truck me-1"></i> Supplier
          </label>
          <select name="supplier" class="form-select" required>
            <option value="">-- Choose Supplier --</option>
            <?php foreach ($supplierOptions as $sup): ?>
              <option value="<?= htmlspecialchars($sup['name']) ?>"
                <?= (isset($_POST['supplier']) && $_POST['supplier'] === $sup['name']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($sup['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label required-field">Price (BDT)</label>
          <div class="input-group">
            <span class="input-group-text bg-light">৳</span>
            <input type="number" step="0.01" min="0" name="price" class="form-control" placeholder="0.00" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
          </div>
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Warranty (Months)</label>
          <div class="input-group warranty-input-group">
            <input type="number" name="warranty" class="form-control" placeholder="12" min="0" max="120" value="value="<?= htmlspecialchars($_POST['warranty'] ?? '') ?>">
            <span class="input-group-text bg-light">Months</span>
          </div>
          <small class="text-muted mt-1 d-block">Enter warranty period in months (0 for no warranty)</small>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <label class="form-label">Condition</label>
          <select name="product_condition" class="form-select">
            <option value="New" <?= (isset($_POST['product_condition']) && $_POST['product_condition'] === 'New') ? 'selected' : '' ?>>New</option>
            <option value="Used" <?= (isset($_POST['product_condition']) && $_POST['product_condition'] === 'Used') ? 'selected' : '' ?>>Used</option>
            <option value="Refurbished" <?= (isset($_POST['product_condition']) && $_POST['product_condition'] === 'Refurbished') ? 'selected' : '' ?>>Refurbished</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label required-field">Concern (Factory)</label>
          <select name="factory_name" class="form-select" required>
            <option value="">-- Select --</option>
            <?php 
            $concerns = ['SCL', 'SAL', 'SDL', 'SLL', 'HO', 'USL', 'SACL']; 
            foreach($concerns as $c): ?>
              <option value="<?= $c ?>" <?= (isset($_POST['factory_name']) && $_POST['factory_name'] === $c) ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Requisition No</label>
          <input type="text" name="requisition_no" class="form-control" placeholder="e.g., REQ-2024-001" value="<?= htmlspecialchars($_POST['requisition_no'] ?? '') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Remarks</label>
          <input type="text" name="remarks" class="form-control" placeholder="Any additional notes" value="<?= htmlspecialchars($_POST['remarks'] ?? '') ?>">
        </div>
      </div>

      <div class="d-flex justify-content-end gap-3 pt-3">
        <button type="button" id="resetBtn" class="btn btn-warning"><i class="fas fa-undo me-2"></i>Reset</button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save me-2"></i>Save Asset
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const serialInput = document.getElementById('serial_number');
    const serialLabel = document.getElementById('serialLabel');

    function updateSerialRequirement() {
        if (categorySelect.value.toLowerCase() === 'peripheral') {
            serialInput.required = false;
            serialLabel.classList.remove('required-field');
            serialInput.placeholder = "Optional for peripheral";
        } else {
            serialInput.required = true;
            serialLabel.classList.add('required-field');
            serialInput.placeholder = "Required";
        }
    }

    // Initialize on page load
    updateSerialRequirement();
    categorySelect.addEventListener('change', updateSerialRequirement);

    // Set default purchase date to today if empty
    const dateInput = document.getElementById('purchase_date');
    if(!dateInput.value) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }

    // Show success alert if needed
    <?php if (isset($_SESSION['show_success_alert'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Product has been added successfully.',
            confirmButtonColor: '#764ba2',
            timer: 3000,
            timerProgressBar: true
        });
        <?php unset($_SESSION['show_success_alert']); ?>
    <?php endif; ?>

    // Enhanced reset button functionality
    const resetButton = document.getElementById('resetBtn');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Reset Form?',
                text: 'Are you sure you want to reset all form fields? All entered data will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa709a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reset!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Get the form
                    const form = document.getElementById('productForm');
                    
                    // Reset form
                    form.reset();
                    
                    // Reset date to today
                    const dateInput = document.getElementById('purchase_date');
                    dateInput.value = new Date().toISOString().split('T')[0];
                    
                    // Reset select elements to default
                    const conditionSelect = document.querySelector('select[name="product_condition"]');
                    if (conditionSelect) conditionSelect.value = 'New';
                    
                    // Update serial requirement based on default category
                    updateSerialRequirement();
                    
                    // Remove validation classes
                    const formControls = document.querySelectorAll('.form-control, .form-select');
                    formControls.forEach(control => {
                        control.classList.remove('is-invalid', 'is-valid');
                    });
                    
                    // Clear error messages
                    const errorAlert = document.querySelector('.alert-danger');
                    if (errorAlert) errorAlert.remove();
                    
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    toast.style.zIndex = '1050';
                    toast.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        Form reset successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(toast);
                    
                    // Auto-remove notification after 3 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    
                    // Focus on first input
                    const firstInput = form.querySelector('input:not([type="hidden"])');
                    if (firstInput) firstInput.focus();
                }
            });
        });
    }
    
    // Form validation on submit
    const form = document.getElementById('productForm');
    form.addEventListener('submit', function(e) {
        // Client-side validation for price
        const priceInput = document.querySelector('input[name="price"]');
        if (priceInput && (isNaN(parseFloat(priceInput.value)) || parseFloat(priceInput.value) <= 0)) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Invalid Price',
                text: 'Please enter a valid price greater than 0.',
                confirmButtonColor: '#764ba2'
            });
            priceInput.focus();
            return false;
        }
        
        // Client-side validation for warranty (ensure it's a number if provided)
        const warrantyInput = document.querySelector('input[name="warranty"]');
        if (warrantyInput && warrantyInput.value.trim() !== '') {
            const warrantyValue = parseInt(warrantyInput.value);
            if (isNaN(warrantyValue) || warrantyValue < 0 || warrantyValue > 120) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Warranty',
                    text: 'Warranty must be a number between 0 and 120 months.',
                    confirmButtonColor: '#764ba2'
                });
                warrantyInput.focus();
                return false;
            }
        }
    });
});
</script>

<?php include_once '../includes/footer.php'; ?>
</body>
</html>