<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Filter functionality
$name_filter = $_GET['name_filter'] ?? '';
$job_id_filter = $_GET['job_id_filter'] ?? '';
$status_filter = $_GET['status_filter'] ?? '';
$department_filter = $_GET['department_filter'] ?? '';

$where = [];
$params = [];

if (!empty($name_filter)) {
    $where[] = "name LIKE ?";
    $params[] = "%$name_filter%";
}

if (!empty($job_id_filter)) {
    $where[] = "job_id LIKE ?";
    $params[] = "%$job_id_filter%";
}

if (!empty($status_filter) && $status_filter !== 'all') {
    $where[] = "status = ?";
    $params[] = $status_filter;
}

if (!empty($department_filter) && $department_filter !== 'all') {
    $where[] = "department = ?";
    $params[] = $department_filter;
}

$where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Fetch all employees with optional filters
$sql = "SELECT id, name, job_id, department, designation, status FROM employees $where_clause ORDER BY name";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$employees = $stmt->fetchAll();

// Get unique departments for filter dropdown
$dept_stmt = $pdo->query("SELECT DISTINCT department FROM employees ORDER BY department");
$departments = $dept_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees | IT Asset Management</title>
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
      max-width: 1200px;
      margin: 0 auto 40px;
      padding: 25px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .page-title {
      color: var(--primary-color);
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 2rem;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .filter-container {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
    }

    .filter-group {
      flex: 1;
      min-width: 200px;
    }

    .filter-label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: var(--primary-color);
    }

    .filter-input, .filter-select {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      font-size: 1rem;
      transition: all 0.3s;
    }

    .filter-input:focus, .filter-select:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
      outline: none;
    }

    .btn {
      padding: 10px 20px;
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

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .table th {
      background-color: var(--primary-color);
      color: white;
      padding: 12px 15px;
      text-align: left;
    }

    .table td {
      padding: 12px 15px;
      border-bottom: 1px solid var(--border-color);
    }

    .table tr:hover {
      background-color: rgba(52, 152, 219, 0.1);
    }

    .status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status-active {
      background-color: #d4edda;
      color: #155724;
    }

    .status-inactive {
      background-color: #f8d7da;
      color: #721c24;
    }

    .action-btn {
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.85rem;
      margin-right: 5px;
      transition: all 0.2s;
    }

    .edit-btn {
      background-color: var(--secondary-color);
      color: white;
    }

    .edit-btn:hover {
      background-color: #2980b9;
    }

    .view-btn {
      background-color: var(--success-color);
      color: white;
    }

    .view-btn:hover {
      background-color: #219653;
    }

    .no-results {
      text-align: center;
      padding: 20px;
      color: var(--text-light);
    }

    .filter-actions {
      display: flex;
      align-items: flex-end;
      gap: 10px;
    }

    .btn-reset {
      background-color: var(--danger-color);
      color: white;
    }

    .btn-reset:hover {
      background-color: #c0392b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding: 15px;
        margin: 15px;
      }
      
      .filter-container {
        flex-direction: column;
      }
      
      .table {
        display: block;
        overflow-x: auto;
      }

      .filter-group {
        min-width: 100%;
      }
    }
    </style>
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 class="page-title">
        <span><i class="fas fa-users me-2"></i>All Employees</span>
        <a href="add_employee.php" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Employee
        </a>
    </h2>

    <form method="GET" action="employees.php">
        <div class="filter-container">
            <div class="filter-group">
                <label class="filter-label" for="name_filter">Name</label>
                <input 
                    type="text" 
                    id="name_filter" 
                    name="name_filter" 
                    class="filter-input" 
                    placeholder="Filter by name..."
                    value="<?= htmlspecialchars($name_filter) ?>"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="job_id_filter">Job ID</label>
                <input 
                    type="text" 
                    id="job_id_filter" 
                    name="job_id_filter" 
                    class="filter-input" 
                    placeholder="Filter by job ID..."
                    value="<?= htmlspecialchars($job_id_filter) ?>"
                >
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="status_filter">Status</label>
                <select id="status_filter" name="status_filter" class="filter-select">
                    <option value="all">All Statuses</option>
                    <option value="active" <?= $status_filter === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status_filter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label" for="department_filter">Department</label>
                <select id="department_filter" name="department_filter" class="filter-select">
                    <option value="all">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept) ?>" <?= $department_filter === $dept ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <a href="employees.php" class="btn btn-reset">
                    <i class="fas fa-undo me-2"></i>Reset
                </a>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Job ID</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="7" class="no-results">No employees found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= htmlspecialchars($employee['name']) ?></td>
                    <td><?= htmlspecialchars($employee['job_id']) ?></td>
                    <td><?= htmlspecialchars($employee['department']) ?></td>
                    <td><?= htmlspecialchars($employee['designation']) ?></td>
                    <td>
                        <span class="status status-<?= $employee['status'] ?>">
                            <?= ucfirst($employee['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="update_employee.php?id=<?= $employee['id'] ?>" class="action-btn edit-btn">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="view_employee.php?id=<?= $employee['id'] ?>" class="action-btn view-btn">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>