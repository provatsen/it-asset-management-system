<?php
require_once '../config/db.php';
session_start();

// Only allow Provat and Kamrul
$allowedUsers = ['Provat', 'Kamrul'];
if (!isset($_SESSION['username']) || !in_array($_SESSION['username'], $allowedUsers)) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";

    // Check if username exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) $errors[] = "Username already exists";

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        $success = "User created successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User | IT Asset Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --danger: #ef4444;
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--success) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-header .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--success) 100%);
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
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .form-text {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .password-strength {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-bar.active {
            background: var(--success);
        }

        .strength-bar.medium {
            background: var(--warning);
        }

        .strength-bar.weak {
            background: var(--danger);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
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
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.3);
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

        .permissions-note {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-top: 2rem;
            margin-bottom: 1.5rem;
        }

        .permissions-note h6 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .permissions-note p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 0;
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
            <h1><i class="fas fa-user-plus me-2"></i>Create New User</h1>
            <div class="badge">
                <i class="fas fa-shield-alt me-1"></i> Restricted Access
            </div>
        </div>

        <div class="glass-card fade-in">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user-cog"></i> User Information
                </h3>

                <?php if ($errors): ?>
                    <div class="alert-container">
                        <div class="custom-alert alert-danger">
                            <i class="fas fa-exclamation-circle alert-icon"></i>
                            <div>
                                <strong>Please fix the following errors:</strong>
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

                
                <form method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-1"></i> Username *
                        </label>
                        <input type="text" 
                               class="form-input" 
                               id="username" 
                               name="username" 
                               placeholder="Enter username" 
                               required>
                        <div class="form-text">Username must be unique and will be used for login</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i> Password *
                        </label>
                        <input type="password" 
                               class="form-input" 
                               id="password" 
                               name="password" 
                               placeholder="Enter password (min 8 characters)" 
                               required
                               oninput="checkPasswordStrength(this.value)">
                        <div class="form-text">Password must be at least 8 characters long</div>
                        
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar"></div>
                            <div class="strength-bar"></div>
                            <div class="strength-bar"></div>
                            <div class="strength-bar"></div>
                        </div>
                        <div class="form-text" id="passwordStrengthText">Password strength: None</div>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i> Create User Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function checkPasswordStrength(password) {
            const bars = document.querySelectorAll('.strength-bar');
            const text = document.getElementById('passwordStrengthText');
            
            // Reset bars
            bars.forEach(bar => {
                bar.classList.remove('active', 'medium', 'weak');
            });
            
            if (password.length === 0) {
                text.textContent = 'Password strength: None';
                return;
            }
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Complexity checks
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Update bars
            for (let i = 0; i < bars.length; i++) {
                if (i < Math.min(strength, 4)) {
                    if (strength <= 2) {
                        bars[i].classList.add('weak');
                    } else if (strength === 3) {
                        bars[i].classList.add('medium');
                    } else {
                        bars[i].classList.add('active');
                    }
                }
            }
            
            // Update text
            if (strength <= 2) {
                text.textContent = 'Password strength: Weak';
                text.style.color = '#ef4444';
            } else if (strength === 3) {
                text.textContent = 'Password strength: Medium';
                text.style.color = '#f59e0b';
            } else {
                text.textContent = 'Password strength: Strong';
                text.style.color = '#10b981';
            }
        }

        // Theme toggle
        document.addEventListener('DOMContentLoaded', function() {
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