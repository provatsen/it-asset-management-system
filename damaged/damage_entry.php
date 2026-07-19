<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
require_once '../config/db.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check for success message
$success_message = isset($_GET['success']) ? 'Damage reported successfully!' : '';

// Fetch available products
$stmt = $pdo->query("SELECT id, name, serial_number, category, brand FROM products WHERE status != 'damaged' ORDER BY name");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Store options
$stores = ['Creation Store', 'Apparel Store', 'Denims Store', 'Laundry Store', 'Head Office Store'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Damage Entry</title>
  <link rel="icon" type="image/x-icon" href="../image/favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e2eafc, #f0f4ff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      
    }
    .container {
      max-width: 900px;
      margin: 2rem auto;
      padding: 2rem;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    .searchable-select {
      position: relative;
    }
    .search-results {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1000;
      max-height: 300px;
      overflow-y: auto;
      border: 1px solid #ced4da;
      background: white;
      border-radius: 0 0 8px 8px;
      display: none;
    }
    .search-results div {
      padding: 10px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
    }
    .search-results div:hover {
      background-color: #f8f9fa;
    }
    .product-info {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
    }
  </style>
</head>
<body>
  <?php include_once '../includes/header.php'; ?>

  <main class="py-4">
    <div class="container">
      <h4 class="mb-4"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Damage Asset Entry</h4>
      
      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <form method="POST" action="process_damage.php" id="damageForm">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        
        <!-- Product Search -->
        <div class="mb-4">
          <label class="form-label fw-bold">Select Asset</label>
          <div class="searchable-select">
            <input type="text" id="productSearch" class="form-control form-control-lg" 
                   placeholder="Search by name, serial, or category..." autocomplete="off">
            <div id="productResults" class="search-results">
              <?php foreach ($products as $product): ?>
                <div data-id="<?= htmlspecialchars($product['id']) ?>" 
                     data-name="<?= htmlspecialchars($product['name']) ?>"
                     data-serial="<?= htmlspecialchars($product['serial_number'] ?? '') ?>"
                     data-category="<?= htmlspecialchars($product['category'] ?? '') ?>"
                     data-brand="<?= htmlspecialchars($product['brand'] ?? '') ?>">
                  <strong><?= htmlspecialchars($product['name']) ?></strong>
                  <?php if (!empty($product['serial_number'])): ?>
                    <br><small class="text-muted">Serial: <?= htmlspecialchars($product['serial_number']) ?></small>
                  <?php endif; ?>
                  <?php if (!empty($product['category'])): ?>
                    <br><small class="text-muted">Category: <?= htmlspecialchars($product['category']) ?></small>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <input type="hidden" name="product_id" id="selectedProductId" required>
          
          <!-- Product Info Display -->
          <div id="productInfo" class="product-info mt-3">
            <div class="row">
              <div class="col-md-6">
                <p><strong>Name:</strong> <span id="displayName"></span></p>
                <p><strong>Serial:</strong> <span id="displaySerial"></span></p>
                <p><strong>Category:</strong> <span id="displayCategory"></span></p>
              </div>
              <div class="col-md-6">
                <p><strong>Brand:</strong> <span id="displayBrand"></span></p>
                <p><strong>Condition:</strong> <span id="displayCondition"></span></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Concern Store -->
        <div class="mb-4">
          <label for="concern_store" class="form-label fw-bold">Concern Store</label>
          <select class="form-select form-select-lg" name="concern_store" id="concern_store" required>
            <option value="">Select Concern Store</option>
            <?php foreach ($stores as $store): ?>
              <option value="<?= htmlspecialchars($store) ?>"><?= htmlspecialchars($store) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Damage Reason -->
        <div class="mb-4">
          <label for="damage_reason" class="form-label fw-bold">Reason for Damage</label>
          <textarea class="form-control" name="damage_reason" id="damage_reason" rows="4" 
                    placeholder="Describe the damage in detail..." required maxlength="500"></textarea>
          <small class="text-muted">Maximum 500 characters</small>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-danger btn-lg">
            <i class="fas fa-exclamation-triangle me-2"></i> Report Damage
          </button>
        </div>
      </form>
    </div>
  </main>

  <?php include_once '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const productSearch = document.getElementById('productSearch');
      const productResults = document.getElementById('productResults');
      const selectedProductId = document.getElementById('selectedProductId');
      const productInfo = document.getElementById('productInfo');
      
      // Show success message if exists
      <?php if (!empty($success_message)): ?>
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: '<?= $success_message ?>',
          timer: 3000,
          showConfirmButton: false
        });
      <?php endif; ?>
      
      // Product search functionality
      productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = productResults.querySelectorAll('div');
        let hasMatches = false;
        
        items.forEach(item => {
          const itemText = item.textContent.toLowerCase();
          if (itemText.includes(searchTerm)) {
            item.style.display = 'block';
            hasMatches = true;
          } else {
            item.style.display = 'none';
          }
        });
        
        productResults.style.display = hasMatches ? 'block' : 'none';
      });
      
      // Handle product selection
      productResults.addEventListener('click', function(e) {
        const item = e.target.closest('div');
        if (item) {
          selectedProductId.value = item.dataset.id;
          productSearch.value = item.dataset.name;
          
          // Display selected product info
          document.getElementById('displayName').textContent = item.dataset.name;
          document.getElementById('displaySerial').textContent = item.dataset.serial || 'N/A';
          document.getElementById('displayCategory').textContent = item.dataset.category || 'N/A';
          document.getElementById('displayBrand').textContent = item.dataset.brand || 'N/A';
          productInfo.style.display = 'block';
          
          productResults.style.display = 'none';
        }
      });
      
      // Hide results when clicking outside
      document.addEventListener('click', function(e) {
        if (!e.target.closest('.searchable-select')) {
          productResults.style.display = 'none';
        }
      });
      
      // Form submission with AJAX to stay on page
      document.getElementById('damageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedProductId.value) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select a valid product'
          });
          productSearch.focus();
          return;
        }
        
        const formData = new FormData(this);
        
        fetch('process_damage.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: data.message,
              timer: 3000,
              showConfirmButton: false
            }).then(() => {
              // Reset form after success
              document.getElementById('damageForm').reset();
              productInfo.style.display = 'none';
              selectedProductId.value = '';
              productSearch.value = '';
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message
            });
          }
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while submitting the form'
          });
        });
      });
    });
  </script>
</body>
</html>