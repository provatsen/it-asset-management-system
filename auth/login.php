<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: ../dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Please enter username and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../image/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT-Asset Management System</title>
    
    <!-- Google Fonts - Roboto (Regular 400) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --dark: #212529;
            --light: #f8f9fa;
            --danger: #ef233c;
            --success: #4cc9f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 1000px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .login-illustration {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            display: none; /* Hidden on mobile */
        }

        .login-illustration img {
            max-width: 100%;
            height: auto;
        }

        .login-container {
            flex: 1;
            background-color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .login-header p {
            color: #6c757d;
            font-size: 14px;
        }

        .brand-logo {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            display: inline-block;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
            font-size: 14px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 38px;
            color: #adb5bd;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .error-message {
            color: var(--danger);
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: rgba(239, 35, 60, 0.1);
            border-radius: 6px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
            line-height: 1.6;
        }

        .developer-info {
            display: inline-block;
            position: relative;
            cursor: pointer;
            color: var(--primary);
            font-weight: 600;
        }

        .developer-info:hover::after {
            content: "+8801751467162";
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
        }

        .version-info {
            margin-top: 15px;
            font-size: 11px;
            color: #adb5bd;
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            .login-illustration {
                display: flex;
            }
            .login-container {
                padding: 60px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Logo Animation */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 150px;
            height: 150px;
            animation: spin 10s linear infinite;
            filter: drop-shadow(0 0 5px rgba(0,0,0,0.1));
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-illustration">
                <div class="logo-container">
                    <img src="../image/sg_logo.png" alt="Sterling Group Logo" class="logo">
                    </div>
            <div style="text-align: center;">
                <h2 style="font-size: 28px; margin-bottom: 15px;">STERLING GROUP</h2>
                <p style="margin-bottom: 30px;">IT Asset Database</p>
                <i class="fas fa-lock" style="font-size: 80px; opacity: 0.8; margin-bottom: 30px;"></i>
                <p>Secure access to your organization's assets</p>
            </div>
        </div>
        
        <div class="login-container">
            <div class="login-header">
                <div class="brand-logo">IT Department</div>
                <h1>Authorized Access Only</h1>
                <p>Please enter your credentials to continue</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" required autofocus placeholder="Enter your username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="footer">
                <p>This application developed by <span class="developer-info">Provat Sen</span></p>
                <p>For registration, contact with system <strong>Administrator</strong></p>
                <div class="version-info">
                    Version: PS-3.2 | Initial: PS-0.1
                </div>
            </div>
        </div>
    </div>
</body>
</html>