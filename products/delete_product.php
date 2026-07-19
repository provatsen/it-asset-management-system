<?php
require_once '../config/db.php';
session_start();

// Check authentication
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Allowed user to access this page
$allowedUsers = ['Provat', 'Kamrul'];
if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], $allowedUsers)) {
    header('Location: index.php');
    exit;
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$products = [];
$search = '';
$deleted = false;
$error = '';
$success = '';

// Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = trim($_POST['search']);
    
    if (!empty($search)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM products 
                WHERE name LIKE ? OR serial_number LIKE ? OR supplier LIKE ? OR requisition_no LIKE ? 
                ORDER BY id DESC");
            $searchWildcard = "%" . $search . "%";
            $stmt->execute([$searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard]);
            $products = $stmt->fetchAll();
            
            if (empty($products)) {
                $error = "No products found matching your search criteria.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter a search term.";
    }
}

// Handle delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
        
        if ($id === false || $id <= 0) {
            $error = "Invalid product ID.";
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "Product deleted successfully.";
                    $deleted = true;
                    
                    // Refresh search results after deletion
                    if (!empty($search)) {
                        $stmt = $pdo->prepare("SELECT * FROM products 
                            WHERE name LIKE ? OR serial_number LIKE ? OR supplier LIKE ? OR requisition_no LIKE ? 
                            ORDER BY id DESC");
                        $stmt->execute([$searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard]);
                        $products = $stmt->fetchAll();
                    }
                } else {
                    $error = "Product not found or already deleted.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Product | IT Asset Manager</title>
  <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #6366f1;
      --primary-dark: #4f46e5;
      --danger: #ef4444;
      --danger-dark: #dc2626;
      --success: #10b981;
      --warning: #f59e0b;
      --bg-primary: #0f172a;
      --bg-secondary: #1e293b;
      --bg-card: rgba(30, 41, 59, 0.7);
      --text-primary: #f8fafc;
      --text-secondary: #94a3b8;
      --border-color: #334155;
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --glow: 0 0 20px rgba(99, 102, 241, 0.1);
    }

    [data-theme="light"] {
      --primary: #4f46e5;
      --primary-dark: #4338ca;
      --danger: #ef4444;
      --danger-dark: #dc2626;
      --bg-primary: #f8fafc;
      --bg-secondary: #f1f5f9;
      --bg-card: rgba(255, 255, 255, 0.9);
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
      color: var(--text-primary);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .main-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem;
    }

    .glass-card {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 1.5rem;
      padding: 2rem;
      box-shadow: var(--shadow), var(--glow);
      margin-bottom: 2rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2), var(--glow);
    }

    .page-header {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
    }

    .page-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--primary) 0%, var(--danger) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.5rem;
    }

    .page-header .badge {
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
      border-radius: 50px;
      background: linear-gradient(135deg, var(--danger) 0%, var(--primary) 100%);
      color: white;
      font-weight: 600;
    }

    .search-section {
      margin-bottom: 2rem;
    }

    .search-box {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .search-input {
      flex: 1;
      padding: 1rem 1.5rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-color);
      border-radius: 0.75rem;
      color: var(--text-primary);
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .search-input::placeholder {
      color: var(--text-secondary);
    }

    .search-btn {
      padding: 1rem 2rem;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border: none;
      border-radius: 0.75rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .search-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.3);
    }

    .search-btn:active {
      transform: translateY(0);
    }

    .alert-container {
      margin-bottom: 2rem;
    }

    .custom-alert {
      padding: 1.25rem 1.5rem;
      border-radius: 0.75rem;
      border: none;
      display: flex;
      align-items: center;
      gap: 1rem;
      animation: slideIn 0.3s ease;
    }

    .alert-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }

    .alert-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }

    .alert-info {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
    }

    .alert-warning {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
    }

    .alert-icon {
      font-size: 1.5rem;
    }

    .results-section {
      margin-top: 2rem;
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .section-title i {
      color: var(--primary);
    }

    .table-container {
      overflow: hidden;
      border-radius: 0.75rem;
      border: 1px solid var(--border-color);
    }

    .table {
      margin: 0;
      color: var(--text-primary);
      background: transparent;
    }

    .table thead th {
      background: rgba(99, 102, 241, 0.1);
      border-bottom: 2px solid var(--border-color);
      font-weight: 600;
      padding: 1.25rem 1.5rem;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
    }

    .table tbody tr {
      background: rgba(255, 255, 255, 0.02);
      transition: all 0.3s ease;
      border-bottom: 1px solid var(--border-color);
    }

    .table tbody tr:hover {
      background: rgba(99, 102, 241, 0.05);
    }

    .table tbody tr:last-child {
      border-bottom: none;
    }

    .table tbody td {
      padding: 1.25rem 1.5rem;
      vertical-align: middle;
      border-top: none;
    }

    .product-name {
      font-weight: 500;
      color: var(--text-primary);
    }

    .product-serial {
      color: var(--text-secondary);
      font-family: 'Monaco', 'Consolas', monospace;
      font-size: 0.9rem;
    }

    .product-supplier {
      color: var(--text-secondary);
    }

    .action-cell {
      text-align: right;
      white-space: nowrap;
    }

    .delete-btn {
      padding: 0.625rem 1.25rem;
      background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
      color: white;
      border: none;
      border-radius: 0.5rem;
      font-weight: 600;
      font-size: 0.875rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .delete-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.3);
    }

    .delete-btn:active {
      transform: translateY(0);
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--text-secondary);
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1.5rem;
      opacity: 0.5;
    }

    .empty-state h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: var(--text-primary);
    }

    .empty-state p {
      font-size: 1rem;
      max-width: 400px;
      margin: 0 auto 2rem;
    }

    /* Modal Styling */
    .custom-modal .modal-content {
      background: var(--bg-card);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 1.5rem;
      color: var(--text-primary);
    }

    .custom-modal .modal-header {
      border-bottom: 1px solid var(--border-color);
      padding: 1.5rem 2rem;
    }

    .custom-modal .modal-body {
      padding: 2rem;
    }

    .custom-modal .modal-footer {
      border-top: 1px solid var(--border-color);
      padding: 1.5rem 2rem;
    }

    .modal-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2.5rem;
      color: white;
    }

    .modal-title {
      font-size: 1.75rem;
      font-weight: 700;
      text-align: center;
      background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .modal-message {
      text-align: center;
      font-size: 1.1rem;
      color: var(--text-secondary);
      margin-bottom: 0.5rem;
    }

    .modal-product-name {
      text-align: center;
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 2rem;
      padding: 1rem;
      background: rgba(239, 68, 68, 0.1);
      border-radius: 0.75rem;
      border-left: 4px solid var(--danger);
    }

    .modal-btn {
      padding: 0.875rem 2rem;
      border: none;
      border-radius: 0.75rem;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 120px;
    }

    .modal-btn-cancel {
      background: var(--bg-secondary);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
    }

    .modal-btn-cancel:hover {
      background: var(--border-color);
      transform: translateY(-2px);
    }

    .modal-btn-delete {
      background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
      color: white;
    }

    .modal-btn-delete:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.3);
    }

    /* Animations */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    .fade-in {
      animation: fadeIn 0.5s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-container {
        padding: 1rem;
      }
      
      .glass-card {
        padding: 1.5rem;
      }
      
      .page-header h1 {
        font-size: 2rem;
      }
      
      .search-box {
        flex-direction: column;
      }
      
      .search-btn {
        width: 100%;
        justify-content: center;
      }
      
      .table-container {
        overflow-x: auto;
      }
      
      .table {
        min-width: 700px;
      }
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: var(--bg-secondary);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-dark);
    }
  </style>
</head>
<body>
<?php include_once "../includes/header.php" ?>

<div class="main-container">
  <div class="page-header">
    <h1><i class="fas fa-trash-alt me-2"></i>Delete Product</h1>
    <div class="badge">
      <i class="fas fa-shield-alt me-1"></i> Restricted Access
    </div>
  </div>

  <div class="glass-card fade-in">
    <div class="search-section">
      <h3 class="section-title">
        <i class="fas fa-search"></i> Search Products
      </h3>
      <p class="text-muted mb-4">Enter product name, serial number, supplier, or requisition number to find items for deletion.</p>
      
      <form method="post" class="search-box">
        <input type="text" 
               name="search" 
               class="search-input" 
               placeholder="🔍 Search by name, serial, supplier, or requisition number..." 
               value="<?= htmlspecialchars($search) ?>" 
               required>
        <button type="submit" class="search-btn">
          <i class="fas fa-search"></i> Search Products
        </button>
      </form>
    </div>

    <div class="alert-container">
      <?php if (!empty($success)): ?>
        <div class="custom-alert alert-success">
          <i class="fas fa-check-circle alert-icon"></i>
          <div><?= htmlspecialchars($success) ?></div>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($error)): ?>
        <div class="custom-alert alert-danger">
          <i class="fas fa-exclamation-circle alert-icon"></i>
          <div><?= htmlspecialchars($error) ?></div>
        </div>
      <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
      <div class="results-section fade-in">
        <h3 class="section-title">
          <i class="fas fa-list"></i> Search Results
          <span class="badge bg-primary ms-2" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);">
            <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?>
          </span>
        </h3>
        
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Serial Number</th>
                <th>Supplier</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr>
                <td class="fw-bold">#<?= htmlspecialchars($product['id']) ?></td>
                <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                <td class="product-serial"><?= htmlspecialchars($product['serial_number']) ?></td>
                <td class="product-supplier"><?= htmlspecialchars($product['supplier']) ?></td>
                <td class="action-cell">
                  <button type="button" 
                          class="delete-btn" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal" 
                          data-product-id="<?= htmlspecialchars($product['id']) ?>"
                          data-product-name="<?= htmlspecialchars($product['name']) ?>"
                          data-product-serial="<?= htmlspecialchars($product['serial_number']) ?>">
                    <i class="fas fa-trash-alt"></i> Delete
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search']) && empty($error)): ?>
      <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>No Products Found</h3>
        <p>Try searching with different keywords or check the spelling.</p>
        <button type="button" class="search-btn" onclick="document.querySelector('.search-input').focus()">
          <i class="fas fa-search"></i> Try Again
        </button>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Modern Delete Confirmation Modal -->
<div class="modal fade custom-modal" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="modal-icon">
          <i class="fas fa-exclamation"></i>
        </div>
        <h3 class="modal-title">Confirm Deletion</h3>
        <p class="modal-message">You are about to permanently delete the following product:</p>
        <div class="modal-product-name" id="modalProductName"></div>
        <p class="text-danger fw-bold">
          <i class="fas fa-exclamation-triangle me-2"></i>
          This action cannot be undone!
        </p>
      </div>
      <div class="modal-footer border-0">
        <form method="post" id="deleteForm" class="w-100">
          <input type="hidden" name="delete_id" id="delete_id">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
          <div class="d-flex gap-3 justify-content-center">
            <button type="button" class="modal-btn modal-btn-cancel" data-bs-dismiss="modal">
              <i class="fas fa-times me-2"></i> Cancel
            </button>
            <button type="submit" class="modal-btn modal-btn-delete">
              <i class="fas fa-trash-alt me-2"></i> Delete Permanently
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const confirmDeleteModal = document.getElementById('confirmDeleteModal');
  
  confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const productId = button.getAttribute('data-product-id');
    const productName = button.getAttribute('data-product-name');
    const productSerial = button.getAttribute('data-product-serial');
    
    document.getElementById('delete_id').value = productId;
    document.getElementById('modalProductName').innerHTML = `
      ${productName}<br>
      <small class="text-muted">Serial: ${productSerial}</small>
    `;
  });

  // Theme toggle (if you want to add it)
  const themeToggle = document.createElement('button');
  themeToggle.className = 'btn btn-outline-primary position-fixed bottom-3 end-3 rounded-pill';
  themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
  themeToggle.style.zIndex = '1000';
  document.body.appendChild(themeToggle);

  themeToggle.addEventListener('click', function() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', newTheme);
    themeToggle.innerHTML = newTheme === 'dark' ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
  });
});
</script>

<?php require_once "../includes/footer.php"; ?>
</body>
</html>