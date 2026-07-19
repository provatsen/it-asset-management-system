<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

$message = '';
$departments = ['Accounts','IT', 'HR', 'Admin', 'Production', 'Marketing', 'Marchandising', 'Store', 'Commercial', 'Compliance', 'Management'];
$locations = ['SAL', 'SCL', 'SLL', 'SDL', 'HO', 'US', 'SA'];
$statuses = ['active'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $job_id = trim($_POST['job_id']);
        $department = $_POST['department'];
        $designation = trim($_POST['designation']);
        $contact = trim($_POST['contact_number']);
        $location = $_POST['location'];
        $status = 'active';

        // Validation
        if (empty($name)) {
            throw new Exception("Employee name is required");
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO employees 
            (name, email, job_id, department, designation, contact_number, location, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $name,
            $email,
            $job_id,
            $department,
            $designation,
            $contact,
            $location,
            $status
        ]);

        $message = "Employee created successfully!";
        $_SESSION['success_message'] = $message;
        header('Location: add_employee.php');
        exit;
        
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Check for success message in session
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee | IT Asset Management</title>
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --success-color: #27ae60;
      --danger-color: #e74c3c;
      --light-color: #ecf0f1;
      --dark-color: #2c3e50;
      --border-color: #ddd;
      --text-color: #333;
      --text-light: #7f8c8d;
    }

    body {
      background: #f5f7fa;
      font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-color);
      line-height: 1.6;
      padding-top: 20px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .container {
      max-width: 900px;
      margin: 10px auto 40px;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .page-title {
      text-align: center;
      color: var(--primary-color);
      margin-bottom: 10px;
      font-weight: 500;
      font-size: 2rem;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--border-color);
    }

    .alert {
      border-radius: 8px;
      padding: 15px 20px;
      margin-bottom: 25px;
      border: none;
    }

    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
    }

    .form-section {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      margin-top: 20px;
    }

    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      flex: 1 1 calc(50% - 20px);
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--primary-color);
    }

    .form-group.required label:after {
      content: " *";
      color: var(--danger-color);
    }

    .form-control, .form-select {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      font-size: 1rem;
      transition: all 0.3s;
      background-color: #fff;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
      outline: none;
    }

    .btn {
      padding: 12px 25px;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      border: none;
      font-size: 1rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn i {
      font-size: 1rem;
    }

    .btn-primary {
      background: var(--secondary-color);
      color: white;
    }

    .btn-primary:hover {
      background: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
      background: var(--success-color);
      color: white;
    }

    .btn-success:hover {
      background: #219653;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding: 15px;
        margin: 15px;
      }
      
      .form-group {
        flex: 1 1 100%;
      }
      
      .page-title {
        font-size: 1.5rem;
      }
    }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 class="page-title"><i class="fas fa-user-plus me-2"></i>Add New Employee</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success_message) ?>
        </div>
    <?php elseif ($message): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="form-section">
        <form method="POST">
            <div class="form-row">
                <div class="form-group required">
                    <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="John Doe" required>
                </div>

                <div class="form-group required">
                    <label for="job_id"><i class="fas fa-id-card me-2"></i>Job ID</label>
                    <input type="text" id="job_id" name="job_id" class="form-control" placeholder="EMP-001" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="john@example.com">
                </div>

                <div class="form-group">
                    <label for="department"><i class="fas fa-building me-2"></i>Department</label>
                    <select id="department" name="department" class="form-select">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="designation"><i class="fas fa-briefcase me-2"></i>Designation</label>
                    <input type="text" id="designation" name="designation" class="form-control" placeholder="Software Engineer">
                </div>

                <div class="form-group">
                    <label for="contact_number"><i class="fas fa-phone me-2"></i>Contact Number</label>
                    <input type="tel" id="contact_number" name="contact_number" class="form-control" placeholder="+1234567890">
                </div>
            </div>

            <div class="form-group required">
                <label for="location"><i class="fas fa-map-marker-alt me-2"></i>Location</label>
                <select id="location" name="location" class="form-select" required>
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= htmlspecialchars($loc) ?>"><?= htmlspecialchars($loc) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="status" value="active">

            <div class="action-buttons">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i>Save Employee
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo me-2"></i>Reset Form
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i>Employee Added</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-3">
          <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
          <h4>Employee Added Successfully!</h4>
          <p class="text-muted">The new employee has been added to the database.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($success_message)): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
  });
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
</body>
</html>