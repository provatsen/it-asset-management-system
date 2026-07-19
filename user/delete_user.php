<?php
require_once '../config/db.php';
session_start();

// Only allow Provat and Kamrul to access
$allowedUsers = ['Provat', 'Kamrul'];
if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], $allowedUsers)) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = '';

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $userId = (int)$_POST['user_id'];
    
    // Prevent self-deletion
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user && $user['username'] === $_SESSION['username']) {
        $errors[] = "You cannot delete your own account!";
    } elseif ($userId) {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $success = "User deleted successfully!";
        } catch (PDOException $e) {
            $errors[] = "Error deleting user: " . $e->getMessage();
        }
    } else {
        $errors[] = "Invalid user selection";
    }
}

// Fetch all users (except current admin)
$users = [];
$stmt = $pdo->query("SELECT id, username FROM users WHERE username != '{$_SESSION['username']}' ORDER BY username");
if ($stmt) {
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User | IT Asset Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --success: #10b981;
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
            padding: 2.5rem;
            box-shadow: var(--shadow), var(--glow);
            margin-bottom: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-width: 600px;
            margin: 0 auto;
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
            background: linear-gradient(135deg, var(--danger) 0%, var(--primary) 100%);
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

        .form-section {
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title i {
            color: var(--primary);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-select {
            width: 100%;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-select option {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .form-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .warning-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 1.5rem;
            margin-bottom: 2rem;
        }

        .warning-box h6 {
            color: var(--danger);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .warning-box p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.3);
        }

        .submit-btn:active {
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

        .alert-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .alert-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .alert-info {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .alert-icon {
            font-size: 1.5rem;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 1rem;
        }

        .alert ul li {
            margin-bottom: 0.25rem;
        }

        .alert ul li:last-child {
            margin-bottom: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
            color: var(--primary);
        }

        .empty-state h4 {
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
                padding: 1.75rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
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
    <?php include_once '../includes/header.php'; ?>

    <div class="main-container">
        <div class="page-header">
            <h1><i class="fas fa-user-times me-2"></i>Delete User</h1>
            <div class="badge">
                <i class="fas fa-shield-alt me-1"></i> Restricted Access
            </div>
        </div>

        <div class="glass-card fade-in">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-slash"></i> Select User for Deletion
                </h3>

                <?php if ($errors): ?>
                    <div class="alert-container">
                        <div class="custom-alert alert-danger">
                            <i class="fas fa-exclamation-circle alert-icon"></i>
                            <div>
                                <strong>Action Failed:</strong>
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert-container">
                        <div class="custom-alert alert-success">
                            <i class="fas fa-check-circle alert-icon"></i>
                            <div><?php echo htmlspecialchars($success); ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($users)): ?>
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h4>No Users Available</h4>
                        <p>There are no other users in the system to delete.</p>
                        <a href="index.php" class="submit-btn" style="max-width: 200px; margin: 0 auto;">
                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                        </a>
                    </div>
                <?php else: ?>
                    <div class="warning-box">
                        <h6><i class="fas fa-exclamation-triangle"></i> Critical Warning</h6>
                        <p>Deleting a user is a permanent action that cannot be undone. All user data and access will be permanently removed from the system.</p>
                    </div>

                    <form method="POST" id="deleteForm">
                        <div class="form-group">
                            <label for="user_id" class="form-label">
                                <i class="fas fa-user me-1"></i> Select User to Delete *
                            </label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">-- Select a User --</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= htmlspecialchars($user['username']) ?> (ID: <?= $user['id'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text text-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                This action is irreversible!
                            </div>
                        </div>
                        
                        <button type="button" 
                                class="submit-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#confirmDeleteModal"
                                id="deleteButton"
                                disabled>
                            <i class="fas fa-trash-alt"></i> Delete User Account
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                    <h3 class="modal-title" style="background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Confirm User Deletion</h3>
                    <p class="modal-message">You are about to permanently delete the following user account:</p>
                    <div class="modal-product-name" id="modalUserName" style="text-align: center; font-size: 1.25rem; font-weight: 600; color: var(--text-primary); margin-bottom: 2rem; padding: 1rem; background: rgba(239, 68, 68, 0.1); border-radius: 0.75rem; border-left: 4px solid var(--danger);"></div>
                    <p class="text-danger fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action cannot be undone!
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <div class="w-100">
                        <div class="d-flex gap-3 justify-content-center">
                            <button type="button" class="modal-btn modal-btn-cancel" data-bs-dismiss="modal" style="padding: 0.875rem 2rem; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer; min-width: 120px; background: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color);">
                                <i class="fas fa-times me-2"></i> Cancel
                            </button>
                            <button type="submit" form="deleteForm" name="delete_user" class="modal-btn modal-btn-delete" style="padding: 0.875rem 2rem; background: linear-gradient(135deg, var(--danger) 0%, var(--danger-dark) 100%); color: white; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer; min-width: 120px;">
                                <i class="fas fa-trash-alt me-2"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSelect = document.getElementById('user_id');
            const deleteButton = document.getElementById('deleteButton');
            const modalUserName = document.getElementById('modalUserName');
            const users = <?= json_encode($users) ?>;
            
            // Enable/disable delete button based on selection
            userSelect.addEventListener('change', function() {
                deleteButton.disabled = !this.value;
            });
            
            // Set up modal data when delete button is clicked
            deleteButton.addEventListener('click', function() {
                const selectedUserId = userSelect.value;
                const selectedUser = users.find(user => user.id == selectedUserId);
                
                if (selectedUser) {
                    modalUserName.textContent = `${selectedUser.username} (ID: ${selectedUser.id})`;
                }
            });

            // Theme toggle
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

    <?php include_once '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>