<?php
// No PHP redirect here – JS handles it for smooth UX
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Asset Database | Loading</title>
    <link rel="icon" href="image/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            font-family: 'Roboto', sans-serif; 
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #212529;
        }

        .loader-card {
            width: 420px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            text-align: center;
            padding: 2.5rem 2rem;
        }

        .logo-icon {
            font-size: 3rem;
            color: #0d6efd;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .progress {
            height: 6px;
            border-radius: 10px;
            overflow: hidden;
            background-color: #e9ecef;
        }

        .progress-bar {
            animation: loading 3s linear forwards;
        }

        @keyframes loading {
            from { width: 0%; }
            to { width: 100%; }
        }

        .loading-text {
            font-size: 0.95rem;
            color: #6c757d;
            letter-spacing: 0.3px;
        }

        .system-title {
            font-weight: 600;
            letter-spacing: 0.4px;
        }
    </style>
</head>
<body>

<div class="loader-card">
    <!-- Logo -->
    <div class="mb-3">
        <i class="fas fa-network-wired logo-icon"></i>
    </div>

    <!-- Title -->
    <h4 class="system-title mb-1">IT Asset Management</h4>
    <p class="loading-text mb-4">
        Initializing secure system modules…
    </p>

    <!-- Spinner -->
    <div class="d-flex justify-content-center mb-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Progress -->
    <div class="progress">
        <div class="progress-bar bg-primary"></div>
    </div>
</div>

<script>
    setTimeout(() => {
        window.location.href = "auth/login.php";
    }, 3000); // 3 seconds
</script>

</body>
</html>
