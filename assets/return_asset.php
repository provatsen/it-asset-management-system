<?php
require_once '../includes/header.php';
require_once '../config/db.php';

// Fetch all employees
$employees = $pdo->query("SELECT id, name, job_id FROM employees")->fetchAll();
$assigned_assets = [];
$success_message = '';

// Handle employee selection
if (isset($_POST['employee_id'])) {
    $stmt = $pdo->prepare("SELECT aa.id AS assign_id, p.serial_number, p.name 
                          FROM asset_assignments aa
                          JOIN products p ON aa.product_id = p.id
                          WHERE aa.employee_id = ? AND aa.return_date IS NULL");
    $stmt->execute([$_POST['employee_id']]);
    $assigned_assets = $stmt->fetchAll();
}

// Handle asset return
if (isset($_POST['return_asset'])) {
    $assign_id = $_POST['assign_id'];
    $return_note = trim($_POST['return_note']);

    try {
        $pdo->beginTransaction();

        // Get product ID from assignment
        $stmt = $pdo->prepare("SELECT product_id FROM asset_assignments WHERE id = ?");
        $stmt->execute([$assign_id]);
        $product_id = $stmt->fetchColumn();

        if (!$product_id) {
            throw new Exception("Invalid asset assignment");
        }

        // Update assignment with return details
        $stmt = $pdo->prepare("UPDATE asset_assignments 
                             SET return_date = CURDATE(), remarks = ? 
                             WHERE id = ?");
        $stmt->execute([$return_note, $assign_id]);

        // Update product status
        $stmt = $pdo->prepare("UPDATE products 
                             SET status = 'available' 
                             WHERE id = ?");
        $stmt->execute([$product_id]);

        $pdo->commit();
        $success_message = "✅ Asset returned successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $success_message = "❌ Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./image/favicon.ico" type="image/x-icon" />
    <title>Asset Return System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!--Google Font--->
     <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
            color: #333;
        }
        
        .main-content {
            flex: 1;
            padding-top: 50px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            overflow: hidden;
            background-color: none;
            margin-bottom: 2rem;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
        }
        
        .card-header i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #d91a6d;
            border-color: #d91a6d;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border: none;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .list-group-item {
            border-radius: 8px !important;
            margin-bottom: 0.75rem;
            border: 1px solid #eee;
            transition: var(--transition);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .list-group-item:hover {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .form-check-input {
            margin-right: 1rem;
        }
        
        .asset-serial {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        .asset-name {
            color: #6c757d;
        }
        
        textarea.form-control {
            min-height: 120px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card {
                margin: 0 0.5rem 1.5rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 0.65rem 1.25rem;
                width: 100%;
            }
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
                        <div class="card-header">
                            <i class="fas fa-undo-alt"></i>
                            <h4 class="mb-0">Return Assigned Asset</h4>
                        </div>
                        
                        <div class="card-body">
                            <?php if (!empty($success_message)): ?>
                                <div class="alert alert-<?= strpos($success_message, '✅') !== false ? 'success' : 'danger' ?>">
                                    <i class="fas <?= strpos($success_message, '✅') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                                    <?= $success_message ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Employee Selection Form -->
                            <form method="POST" class="mb-4">
                                <div class="mb-3">
                                    <label for="employeeSelect" class="form-label">
                                        <i class="fas fa-user"></i> Select Employee
                                    </label>
                                    <select id="employeeSelect" name="employee_id" class="form-select" onchange="this.form.submit()" required>
                                        <option value="">-- Select Employee --</option>
                                        <?php foreach ($employees as $emp): ?>
                                            <option value="<?= htmlspecialchars($emp['id']) ?>" 
                                                <?= (isset($_POST['employee_id']) && $_POST['employee_id'] == $emp['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($emp['name']) ?> (<?= htmlspecialchars($emp['job_id']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                            
                            <!-- Asset Return Form -->
                            <?php if (!empty($assigned_assets)): ?>
                                <form method="POST">
                                    <input type="hidden" name="employee_id" value="<?= htmlspecialchars($_POST['employee_id']) ?>">
                                    
                                    <div class="mb-4">
                                        <label class="form-label">
                                            <i class="fas fa-laptop"></i> Select Asset to Return
                                        </label>
                                        
                                        <div class="list-group mb-3">
                                            <?php foreach ($assigned_assets as $asset): ?>
                                                <label class="list-group-item">
                                                    <input class="form-check-input" type="radio" name="assign_id" 
                                                           value="<?= htmlspecialchars($asset['assign_id']) ?>" required>
                                                    <div>
                                                        <span class="asset-serial"><?= htmlspecialchars($asset['serial_number']) ?></span>
                                                        <span class="asset-name"><?= htmlspecialchars($asset['name']) ?></span>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="returnNote" class="form-label">
                                            <i class="fas fa-comment-dots"></i> Return Notes
                                        </label>
                                        <textarea id="returnNote" name="return_note" class="form-control" rows="3" 
                                                  placeholder="Please describe the asset condition or any remarks..." required></textarea>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" name="return_asset" class="btn btn-danger">
                                            <i class="fas fa-undo"></i> Return Selected Asset
                                        </button>
                                    </div>
                                </form>
                            <?php elseif (isset($_POST['employee_id'])): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> This employee has no currently assigned assets.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once '../includes/footer.php'; ?>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>