<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Initialize
$message = '';
$employee = null;
$showModal = false;
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

$departments = ['Accounts','IT', 'HR', 'Admin', 'Production', 'Marketing', 'Marchandising', 'Store', 'Commercial', 'Compliance', 'Management'];
$locations = ['SAL', 'SCL', 'SLL', 'SDL', 'HO', 'US', 'SA'];

// Fetch employee if editing
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $employee = $stmt->fetch();
    if (!$employee) {
        $message = "❌ Employee not found";
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $job_id = trim($_POST['job_id']);
        $department = $_POST['department'];
        $designation = trim($_POST['designation']);
        $contact = trim($_POST['contact_number']);
        $location = $_POST['location'];
        $status = $_POST['status'];

        if (empty($name) || empty($job_id)) {
            throw new Exception("Name and Job ID are required");
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        $stmt = $pdo->prepare("
            UPDATE employees SET
                name = ?,
                email = ?,
                job_id = ?,
                department = ?,
                designation = ?,
                contact_number = ?,
                location = ?,
                status = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $stmt->execute([
            $name, $email, $job_id, $department,
            $designation, $contact, $location, $status, $id
        ]);

        $_SESSION['success_message'] = "Employee updated successfully!";
        $showModal = true;

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;

    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($employee) ? 'Edit' : 'Add' ?> Employee | IT Asset Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --border-color: #ddd;
            --text-color: #333;
        }
        html, body {
              height: 100%;
              margin: 0;
              padding: 0;
        }
            
        body {
              display: flex;
              flex-direction: column;
              min-height: 100vh;
        }
            
        main {
              flex: 1;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 10px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            flex: 1 1 calc(50% - 20px);
        }
        .form-group label {
            font-weight: 500;
        }
        .form-group.required label::after {
            content: ' *';
            color: var(--danger-color);
        }
        .form-control, .form-select {
            padding: 12px;
            font-size: 1rem;
        }
        .status-options {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 30px;
        }
        .modal-header {
            background-color: var(--success-color);
            color: white;
        }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 class="page-title">
        <span><i class="fas fa-user-edit me-2"></i><?= isset($employee) ? 'Edit Employee' : 'Add New Employee' ?></span>
        <a href="employees.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </h2>

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success_message) ?>
        </div>
    <?php elseif ($message): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($employee) || !isset($_GET['id'])): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $employee['id'] ?? '' ?>">

        <div class="form-row">
            <div class="form-group required">
                <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
                <input type="text" name="name" id="name" class="form-control"
                       value="<?= htmlspecialchars($employee['name'] ?? '') ?>" required>
            </div>
            <div class="form-group required">
                <label for="job_id"><i class="fas fa-id-badge me-2"></i>Job ID</label>
                <input type="text" name="job_id" id="job_id" class="form-control"
                       value="<?= htmlspecialchars($employee['job_id'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="<?= htmlspecialchars($employee['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="department"><i class="fas fa-building me-2"></i>Department</label>
                <select name="department" id="department" class="form-select">
                    <option value="">Select</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept) ?>"
                            <?= (isset($employee['department']) && $employee['department'] === $dept) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="designation"><i class="fas fa-briefcase me-2"></i>Designation</label>
                <input type="text" name="designation" id="designation" class="form-control"
                       value="<?= htmlspecialchars($employee['designation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="contact_number"><i class="fas fa-phone me-2"></i>Contact Number</label>
                <input type="tel" name="contact_number" id="contact_number" class="form-control"
                       value="<?= htmlspecialchars($employee['contact_number'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group required">
                <label for="location"><i class="fas fa-map-marker-alt me-2"></i>Location</label>
                <select name="location" id="location" class="form-select" required>
                    <option value="">Select</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= htmlspecialchars($loc) ?>"
                            <?= (isset($employee['location']) && $employee['location'] === $loc) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($loc) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group required">
                <label><i class="fas fa-circle-check me-2"></i>Status</label>
                <div class="status-options">
                    <label>
                        <input type="radio" name="status" value="active"
                            <?= (!isset($employee['status']) || $employee['status'] === 'active') ? 'checked' : '' ?>>
                        Active
                    </label>
                    <label>
                        <input type="radio" name="status" value="inactive"
                            <?= (isset($employee['status']) && $employee['status'] === 'inactive') ? 'checked' : '' ?>>
                        Inactive
                    </label>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save me-2"></i><?= isset($employee) ? 'Update Employee' : 'Add Employee' ?>
            </button>
            <a href="employees.php" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5">Employee <?= isset($employee) ? 'updated' : 'added' ?> successfully!</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>

<?php if ($showModal): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('successModal')).show();
  });
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body>
</html>
