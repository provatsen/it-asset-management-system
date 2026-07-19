<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Fetch active employees & available products
$employees = $pdo->query("SELECT id, name, job_id, department, designation FROM employees WHERE status='active' ORDER BY name")->fetchAll();
$products  = $pdo->query("SELECT id, name, serial_number, category FROM products WHERE status='available' ORDER BY name")->fetchAll();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empId  = $_POST['employee_id'] ?? null;
    $prodId = $_POST['product_id'] ?? null;
    $date   = $_POST['assigned_date'] ?? date('Y-m-d');
    $notes  = trim($_POST['remarks'] ?? '');

    if ($empId && $prodId) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id FROM products WHERE id = ? AND status = 'available' FOR UPDATE");
            $stmt->execute([$prodId]);
            if (!$stmt->fetch()) throw new Exception("This product is no longer available for assignment");

            $stmt = $pdo->prepare("INSERT INTO asset_assignments (product_id, employee_id, assigned_date, remarks) VALUES (?, ?, ?, ?)");
            $stmt->execute([$prodId, $empId, $date, $notes]);

            $stmt = $pdo->prepare("UPDATE products SET status = 'assigned' WHERE id = ?");
            $stmt->execute([$prodId]);

            $pdo->commit();
            $message = "✅ Asset assigned successfully!";
            $products = $pdo->query("SELECT id, name, serial_number, category FROM products WHERE status = 'available' ORDER BY name")->fetchAll();
        } catch (Exception $e) {
            $pdo->rollBack();
            $message = "❌ Error: " . $e->getMessage();
        }
    } else {
        $message = "❌ Please select both employee and product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Asset | Sterling Group</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
            color: #333;
        }

        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: #4361ee;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control, .form-select {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .btn-primary:hover {
            background-color: #3f37c9;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<div class="main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header p-4">
                        <h4 class="mb-0"><i class="fas fa-share-square me-2"></i>Assign Asset to Employee</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?= strpos($message, '✅') !== false ? 'success' : 'danger' ?>">
                                <?= $message ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <!-- Employee Search -->
                            <div class="mb-3 position-relative">
                                <label for="empSearch" class="form-label"><i class="fas fa-user me-1"></i> Search Employee</label>
                                <input type="text" id="empSearch" class="form-control" placeholder="Type employee name or job ID..." autocomplete="off" required>
                                <div id="empDropdown" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; display:none;"></div>
                                <input type="hidden" name="employee_id" id="empId" required>
                            </div>
                        
                            <!-- Product Search -->
                            <div class="mb-3 position-relative">
                                <label for="productSearch" class="form-label"><i class="fas fa-box me-1"></i> Search Product</label>
                                <input type="text" id="productSearch" class="form-control" placeholder="Type product name or serial number..." autocomplete="off" required>
                                <div id="productDropdown" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; display:none;"></div>
                                <input type="hidden" name="product_id" id="productId" required>
                            </div>
                        
                            <!-- Date -->
                            <div class="mb-3">
                                <label for="assigned_date" class="form-label"><i class="fas fa-calendar-day me-1"></i> Assignment Date</label>
                                <input type="date" name="assigned_date" id="assigned_date" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        
                            <!-- Remarks -->
                            <div class="mb-4">
                                <label for="remarks" class="form-label"><i class="fas fa-comment-dots me-1"></i> Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Optional notes..."><?= htmlspecialchars($_POST['remarks'] ?? '') ?></textarea>
                            </div>
                        
                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i> Assign Asset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
    const employees = <?= json_encode($employees) ?>;
    const empSearch = document.getElementById('empSearch');
    const empDropdown = document.getElementById('empDropdown');
    const empId = document.getElementById('empId');

    empSearch.addEventListener('input', () => {
        const val = empSearch.value.toLowerCase().trim();
        empDropdown.innerHTML = '';
        if (!val) return empDropdown.style.display = 'none';

        const matches = employees.filter(emp =>
            emp.name.toLowerCase().includes(val) || emp.job_id.toLowerCase().includes(val)
        );

        matches.forEach(emp => {
            const item = document.createElement('div');
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = `<strong>${emp.name}</strong> (${emp.job_id}) - ${emp.department}`;
            item.onclick = () => {
                empSearch.value = `${emp.name} (${emp.job_id})`;
                empId.value = emp.id;
                empDropdown.style.display = 'none';
            };
            empDropdown.appendChild(item);
        });

        empDropdown.style.display = matches.length ? 'block' : 'none';
    });

    const products = <?= json_encode($products) ?>;
    const productSearch = document.getElementById('productSearch');
    const productDropdown = document.getElementById('productDropdown');
    const productId = document.getElementById('productId');

    productSearch.addEventListener('input', () => {
        const val = productSearch.value.toLowerCase().trim();
        productDropdown.innerHTML = '';
        if (!val) return productDropdown.style.display = 'none';

        const matches = products.filter(p =>
            p.name.toLowerCase().includes(val) ||
            p.serial_number.toLowerCase().includes(val) ||
            (p.category && p.category.toLowerCase().includes(val))
        );

        matches.forEach(p => {
            const item = document.createElement('div');
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = `<strong>${p.name}</strong> - SN: ${p.serial_number} ${p.category ? '(' + p.category + ')' : ''}`;
            item.onclick = () => {
                productSearch.value = `${p.name} (${p.serial_number})`;
                productId.value = p.id;
                productDropdown.style.display = 'none';
            };
            productDropdown.appendChild(item);
        });

        productDropdown.style.display = matches.length ? 'block' : 'none';
    });

    document.addEventListener('click', (e) => {
        if (!empDropdown.contains(e.target) && e.target !== empSearch) empDropdown.style.display = 'none';
        if (!productDropdown.contains(e.target) && e.target !== productSearch) productDropdown.style.display = 'none';
    });
</script>
</body>
</html>
