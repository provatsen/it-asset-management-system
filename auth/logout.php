<?php
/**
 * logout.php
 * * This script handles session destruction and provides a modern UI
 * with a 10-second countdown before redirecting to the login page.
 */

// Initialize the session
session_start();

// Unset all session variables
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out | Secure Exit</title>
    <link rel="icon" type="image/x-icon" href="/image/favicon.ico">
    <!-- Google Fonts -->
    <!-- Google Fonts - Roboto (Regular 400) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --glass: rgba(255, 255, 255, 0.05);
            --text: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif; 
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            overflow: hidden;
        }

        /* Background Animation Elements */
        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
        }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        /* Container & Card */
        .logout-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 2rem;
            border-radius: 24px;
            text-align: center;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: rgba(99, 102, 241, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary);
        }

        h1 {
            font-weight: 600;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        p {
            color: #94a3b8;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Countdown Loader */
        .timer-container {
            position: relative;
            width: 60px;
            height: 60px;
            margin: 0 auto 1.5rem;
        }

        .timer-svg {
            transform: rotate(-90deg);
        }

        .timer-circle-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.1);
            stroke-width: 4;
        }

        .timer-circle-progress {
            fill: none;
            stroke: var(--primary);
            stroke-width: 4;
            stroke-linecap: round;
            stroke-dasharray: 175.9; /* 2 * PI * r (28) */
            stroke-dashoffset: 0;
            animation: countdown 10s linear forwards;
        }

        @keyframes countdown {
            from { stroke-dashoffset: 0; }
            to { stroke-dashoffset: 175.9; }
        }

        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-manual {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-manual:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .logout-card {
                padding: 2rem 1.5rem;
            }
            h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <ul class="circles">
        <li style="left: 25%; width: 80px; height: 80px; animation-delay: 0s;"></li>
        <li style="left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s;"></li>
        <li style="left: 70%; width: 20px; height: 20px; animation-delay: 4s;"></li>
        <li style="left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s;"></li>
        <li style="left: 65%; width: 20px; height: 20px; animation-delay: 0s;"></li>
    </ul>

    <div class="logout-card" id="card">
        <div class="icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
        </div>
        <h1>Successfully Logged Out</h1>
        <p>Your session has been securely ended. Thank you.</p>
        
        <div class="timer-container">
            <svg class="timer-svg" width="60" height="60">
                <circle class="timer-circle-bg" cx="30" cy="30" r="28"></circle>
                <circle class="timer-circle-progress" cx="30" cy="30" r="28"></circle>
            </svg>
            <span class="timer-text" id="countdown">10</span>
        </div>

        <p style="font-size: 0.875rem;">Redirecting to login in <span id="secs">10</span> seconds...</p>
        
        <a href="login.php" class="btn-manual">Login Now</a>
    </div>

    <script>
        let timeLeft = 10;
        const countdownEl = document.getElementById('countdown');
        const secsEl = document.getElementById('secs');
        const card = document.getElementById('card');

        const timer = setInterval(() => {
            timeLeft--;
            if (timeLeft >= 0) {
                countdownEl.innerText = timeLeft;
                secsEl.innerText = timeLeft;
            }

            if (timeLeft === 0) {
                clearInterval(timer);
                // Exit animation
                card.style.transition = "all 0.5s ease";
                card.style.opacity = "0";
                card.style.transform = "scale(0.9)";
                
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 500);
            }
        }, 1000);
    </script>
</body>
</html>