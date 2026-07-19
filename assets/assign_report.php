<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';

// Get all employees for the dropdown
$employees = $pdo->query("SELECT id, name, job_id, designation, department, location FROM employees ORDER BY name")->fetchAll();
$employee_id = $_GET['employee_id'] ?? '';

$where = $employee_id ? "WHERE aa.employee_id = ?" : "";
$params = $employee_id ? [$employee_id] : [];

$stmt = $pdo->prepare("
    SELECT 
        e.name AS employee_name,
        e.job_id,
        e.designation,
        e.department,
        e.location,
        p.name AS product_name,
        p.category AS product_category,
        p.serial_number,
        aa.assigned_date,
        aa.return_date,
        aa.remarks
    FROM asset_assignments aa
    JOIN products p ON aa.product_id = p.id
    JOIN employees e ON aa.employee_id = e.id
    $where
    ORDER BY aa.assigned_date DESC
");
$stmt->execute($params);
$assignments = $stmt->fetchAll();

// Handle data for the specific employee view
$selected_emp_details = null;
if ($employee_id) {
    foreach ($employees as $emp) {
        if ($emp['id'] == $employee_id) {
            $selected_emp_details = $emp;
            break;
        }
    }
}
$total_assigned = count($assignments);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Asset Assign Report | Sterling Group</title>
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --bg-light: #f8f9fc;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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
            padding: 40px 20px;
            max-width: 1200px;
            margin: auto;
        }

        /* Report Header Styling */
        .report-header {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border-bottom: 4px solid var(--primary-color);
        }

        .org-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        /* Custom Search Styling */
        .filter-section {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .search-wrapper { position: relative; }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 5;
        }

        .search-input {
            padding-left: 45px !important;
            height: 48px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .select-dropdown {
            position: absolute;
            background: white;
            border: 1px solid #e2e8f0;
            width: 100%;
            max-height: 350px;
            overflow-y: auto;
            z-index: 1050;
            border-radius: 8px;
            display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }

        .select-dropdown.show { display: block; }
        
        .select-option {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s;
        }

        .select-option:hover { background: #f8fafc; color: var(--primary-color); }
        .select-option.selected { background: #eef2ff; border-left: 4px solid var(--primary-color); }

        /* Employee Profile Summary */
        .emp-profile-card {
            background: linear-gradient(to right, #ffffff, #f8faff);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .info-block .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 5px;
        }

        .info-block .value { font-size: 1rem; font-weight: 600; color: #1e293b; }

        /* Table Styling */
        .report-table-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .table thead { background-color: #f8fafc; }
        .table thead th {
            padding: 15px;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: #475569;
            border-bottom: 2px solid #f1f5f9;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .status-assigned { background: #fff1f2; color: #e11d48; }
        .status-returned { background: #f0fdf4; color: #166534; }

        /* Print UI */
        @media print {

    /* A4 page setup */
    @page {
        size: A4 portrait;
        margin: 12mm 10mm;
    }

    body {
        background: #fff !important;
        color: #000 !important;
        font-size: 10px;
    }

    .no-print {
        display: none !important;
    }

    .main-content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .report-header {
        border: none !important;
        box-shadow: none !important;
        padding: 0 0 10px 0 !important;
        margin-bottom: 10px !important;
    }

    .emp-profile-card {
        grid-template-columns: repeat(3, 1fr) !important;
        border: 1px solid #000 !important;
        padding: 8px !important;
        margin-bottom: 10px !important;
    }

    .report-table-card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }

    /* TABLE FIX */
    table {
        width: 100% !important;
        table-layout: fixed !important;   /* KEY FIX */
        border-collapse: collapse !important;
    }

    .table th,
    .table td {
        border: 1px solid #000 !important;
        padding: 5px !important;
        vertical-align: top !important;

        /* TEXT WRAP FIX */
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;

        /* REMOVE ELLIPSIS */
        text-overflow: unset !important;
        overflow: visible !important;
    }

    /* Prevent row splitting */
    tr {
        page-break-inside: avoid !important;
    }
}

    </style>
</head>
<body>

<div class="no-print">
    <?php require_once '../includes/header.php'; ?>
</div>

<div class="main-content">
    <div class="report-header d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <img src="/image/sg_logo.png" alt="Sterling Group" class="org-name me-3" style="max-height: 55px; width: auto; object-fit: contain;">
            <div>
                <div class="org-name" style="line-height: 1;">STERLING GROUP</div>
                <div class="text-muted small mt-1">
                    <i class="fas fa-file-alt me-1"></i> IT-Asset Assignment Report 
                    <span class="mx-3 text-light">|</span>
                    <i class="fas fa-calendar-alt me-1"></i> <?= date('d M, Y h:i A') ?>
                </div>
            </div>
        </div>
        <div class="text-end no-print">
             <button onclick="window.print()" class="btn btn-outline-dark btn-sm px-3">
                <i class="fas fa-print me-1"></i> Print Report
            </button>
        </div>
    </div>

    <div class="filter-section no-print">
        <form method="get" class="row g-3">
            <div class="col-md-8 search-wrapper">
                <label class="form-label fw-bold">Select Employee</label>
                <div class="position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="form-control search-input" 
                        placeholder="Search by Name, Job ID, or Department..." 
                        autocomplete="off"
                        value="<?= $selected_emp_details ? htmlspecialchars($selected_emp_details['name']) . ' (' . htmlspecialchars($selected_emp_details['job_id']) . ')' : '' ?>">
                    <input type="hidden" name="employee_id" id="selectedEmployee" value="<?= htmlspecialchars($employee_id) ?>">
                    
                    <div class="select-dropdown" id="employeeDropdown">
                        <div class="select-option" data-value="">All Employees</div>
                        <?php foreach ($employees as $emp): ?>
                            <div class="select-option" data-value="<?= $emp['id'] ?>">
                                <strong><?= htmlspecialchars($emp['name']) ?></strong> (<?= htmlspecialchars($emp['job_id']) ?>)
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <?= htmlspecialchars($emp['designation']) ?> • <?= htmlspecialchars($emp['department']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-primary h-48 w-100 fw-bold" type="submit">Filter Records</button>
                <a href="?" class="btn btn-light h-48 px-3 border" title="Reset"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>

    <?php if ($selected_emp_details): ?>
        <div class="emp-profile-card">
            <div class="info-block">
                <div class="label">Employee Name</div>
                <div class="value"><?= htmlspecialchars($selected_emp_details['name']) ?></div>
            </div>
            <div class="info-block">
                <div class="label">Job ID / Unit</div>
                <div class="value"><?= htmlspecialchars($selected_emp_details['job_id']) ?></div>
            </div>
            <div class="info-block">
                <div class="label">Department</div>
                <div class="value"><?= htmlspecialchars($selected_emp_details['department']) ?></div>
            </div>
            <div class="info-block">
                <div class="label">Seat Location</div>
                <div class="value"><?= htmlspecialchars($selected_emp_details['location']) ?></div>
            </div>
            <div class="info-block">
                <div class="label">Assigned Assets</div>
                <div class="value text-primary"><?= $total_assigned ?> Items</div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($assignments): ?>
        <div class="report-table-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Asset Details</th>
                            <th>Category</th>
                            <th>Serial Number</th>
                            <th>Timeline</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $row): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($row['product_name']) ?></div>
                                    <?php if(!$employee_id): ?>
                                        <div class="text-muted small"><?= htmlspecialchars($row['employee_name']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="text-secondary"><?= htmlspecialchars($row['product_category']) ?></span></td>
                                <td class="font-monospace small"><?= htmlspecialchars($row['serial_number']) ?></td>
                                <td>
                                    <div class="small">Issued: <?= date('d M, Y', strtotime($row['assigned_date'])) ?></div>
                                    <?php if ($row['return_date']): ?>
                                        <div class="small text-danger">Ret: <?= date('d M, Y', strtotime($row['return_date'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['return_date']): ?>
                                        <span class="badge-status status-returned">Returned</span>
                                    <?php else: ?>
                                        <span class="badge-status status-assigned">Possession</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted italic"><?= htmlspecialchars($row['remarks'] ?: '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white rounded-3 border">
            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
            <h5 class="text-muted">No assignment records found</h5>
        </div>
    <?php endif; ?>

    <div class="mt-5 pt-3 border-top text-center text-muted small">
        Sterling Group | IT Asset Management System | Digital Record - Signature Not Required
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('searchInput');
        const dropdown = document.getElementById('employeeDropdown');
        const hiddenInput = document.getElementById('selectedEmployee');
        const options = dropdown.querySelectorAll('.select-option');

        input.addEventListener('focus', () => dropdown.classList.add('show'));

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Search filtering
        input.addEventListener('input', function() {
            const term = input.value.toLowerCase();
            options.forEach(opt => {
                const text = opt.textContent.toLowerCase();
                opt.style.display = text.includes(term) ? 'block' : 'none';
            });
            dropdown.classList.add('show');
        });

        // Selection handling
        options.forEach(opt => {
            opt.addEventListener('click', function() {
                const val = opt.dataset.value;
                hiddenInput.value = val;
                input.value = opt.innerText.split('\n')[0].trim();
                dropdown.classList.remove('show');
                
                options.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
            });
        });
    });
</script>

<div class="no-print">
    <?php require_once '../includes/footer.php'; ?>
</div>
</body>
</html>