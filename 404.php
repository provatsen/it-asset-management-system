<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wrong Page Link!</title>
    <!-- Google Fonts for Bengali -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;600;700&family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f43f5e;
            --secondary: #fb7185;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.03);
            --text: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
            background-color: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(244, 63, 94, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            overflow: hidden;
            text-align: center;
        }

        .container {
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            z-index: 10;
        }

        /* Sad Emoji Animation Container */
        .emoji-container {
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: center;
            animation: float 6s ease-in-out infinite;
        }

        .sad-emoji {
            width: 150px;
            height: 150px;
            fill: none;
            stroke: var(--primary);
            stroke-width: 1.5;
            filter: drop-shadow(0 0 15px rgba(244, 63, 94, 0.4));
        }

        /* Tear Animation */
        .tear {
            fill: #38bdf8;
            opacity: 0;
            animation: drip 3s infinite;
        }

        @keyframes drip {
            0% { transform: translateY(0); opacity: 0; }
            30% { opacity: 1; }
            100% { transform: translateY(20px); opacity: 0; }
        }

        /* Eyes blinking */
        .eye {
            animation: blink 4s infinite;
            transform-origin: center;
        }

        @keyframes blink {
            0%, 90%, 100% { transform: scaleY(1); }
            95% { transform: scaleY(0.1); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(-2deg); }
        }

        .message-box {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2.5rem;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #fff;
            font-weight: 700;
        }

        p {
            font-size: 1.1rem;
            color: #94a3b8;
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .error-label {
            display: block;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            letter-spacing: 2px;
            font-size: 1.2rem;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px -5px rgba(244, 63, 94, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 25px -5px rgba(244, 63, 94, 0.5);
        }

        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--primary);
            filter: blur(100px);
            opacity: 0.15;
            z-index: 1;
            border-radius: 50%;
        }

        .blob-1 { top: 10%; left: 10%; animation: move 20s infinite alternate; }
        .blob-2 { bottom: 10%; right: 10%; animation: move 25s infinite alternate-reverse; }

        @keyframes move {
            from { transform: translate(0, 0); }
            to { transform: translate(100px, 100px); }
        }

        @media (max-width: 480px) {
            h1 { font-size: 1.5rem; }
            p { font-size: 1rem; }
            .sad-emoji { width: 100px; height: 100px; }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container">
        <div class="emoji-container">
            <svg class="sad-emoji" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <!-- Head -->
                <circle cx="12" cy="12" r="10" />
                <!-- Eyes -->
                <circle class="eye" cx="8" cy="10" r="1" fill="currentColor" />
                <circle class="eye" cx="16" cy="10" r="1" fill="currentColor" />
                <!-- Mouth (Sad Curve) -->
                <path d="M8 17c1-2 3-3 4-3s3 1 4 3" stroke-linecap="round" />
                <!-- Animated Tear -->
                <circle class="tear" cx="8" cy="12" r="0.6" />
            </svg>
        </div>
        
        <div class="message-box">
            <span class="error-label">Error 404!</span>
            <h1>দুঃখিত! পাতাটি পাওয়া যায়নি</h1>
            <p>
                আপনি যে পাতাটি খুঁজছেন সেটি সম্ভবত সরিয়ে ফেলা হয়েছে অথবা বর্তমানে বিদ্যমান নেই। অনুগ্রহ করে ইউআরএল (URL) টি পুনরায় পরীক্ষা করুন।
            </p>
            <a href="index.php" class="btn-home">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                হোম পেজে ফিরে যান
            </a>
        </div>
    </div>
</body>
</html>