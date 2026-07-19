<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator | IT Asset Management</title>
    <link rel="icon" href="/image/favicon.ico" type="image/x-icon" />
     <link rel="stylesheet" href="includes/style.css">
    
    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --dark: #212529;
            --light: #f8f9fa;
        }
        
        body {
            font-family:'Roboto', sans-serif; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .main-container {
            max-width: 1200px; /* Increased from 900px */
            margin: 0 auto;
            padding: 30px 20px; /* Increased padding */
            min-height: calc(100vh - 120px); /* Ensure full height */
        }
        
        
        .generator-card {
            background: white;
            border-radius: 20px; /* Increased from 16px */
            box-shadow: 0 12px 35px rgba(0,0,0,0.1); /* Enhanced shadow */
            padding: 15px; /* Increased padding */
            margin-bottom: 20px; /* Added margin bottom */
        }
        
        .password-display {
            background: var(--light);
            border: 3px solid #e9ecef; /* Thicker border */
            border-radius: 15px; /* Increased from 12px */
            padding: 25px; /* Increased padding */
            font-family: 'Courier New', monospace;
            font-size: 30px; /* Increased from 22px */
            font-weight: 800;
            text-align: center;
            letter-spacing: 2px;
            word-break: break-all;
            margin-bottom: 5px; /* Increased from 25px */
            min-height: 10px; /* Increased from 80px */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.3s;
        }
        
        .password-display:hover {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15); /* Enhanced hover effect */
        }
        
        .copy-btn {
            position: absolute;
            top: 25px; /* Adjusted position */
            right: 20px; /* Adjusted position */
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px; /* Increased from 8px */
            padding: 10px 16px; /* Increased padding */
            font-size: 20px; /* Increased from 14px */
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .copy-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px); /* Enhanced hover effect */
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .copy-btn.copied {
            background: var(--success);
        }
        
        .settings-section {
            margin-bottom: 10px; /* Increased from 30px */
        }
        
        .settings-title {
            font-size: 20px; /* Increased from 18px */
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 10px; /* Increased from 20px */
            padding-bottom: 5px; /* Increased from 10px */
            border-bottom: 3px solid #f1f3f5; /* Thicker border */
        }
        
        .length-slider {
            width: 100%;
            margin: 5px 0; /* Increased margin */
            height: 10px; /* Thicker slider */
        }
        
        .length-value {
            font-size: 20px; /* Increased from 18px */
            font-weight: 500;
            color: var(--primary);
            text-align: center;
            margin-bottom: 10px; /* Increased from 10px */
        }
        
        .option-group {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); /* Increased min width */
            gap: 20px; /* Increased gap */
            margin-bottom: 20px; /* Increased from 25px */
        }
        
        .option-btn {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px; /* Increased from 10px */
            padding: 20px; /* Increased padding */
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            min-height: 130px; /* Added minimum height */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .option-btn:hover {
            border-color: var(--primary);
            transform: translateY(-5px); /* Enhanced hover effect */
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
        }
        
        .option-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .option-btn i {
            font-size: 32px; /* Increased from 24px */
            margin-bottom: 15px; /* Increased from 10px */
            display: block;
        }
        
        .option-btn div {
            font-size: 18px; /* Increased font size */
            margin-bottom: 8px;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 25px; /* Increased gap */
            margin-bottom: 35px; /* Increased from 25px */
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px; /* Increased gap */
            cursor: pointer;
            padding: 10px 15px; /* Added padding */
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .checkbox-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .checkbox-item input[type="checkbox"] {
            width: 20px; /* Increased from 18px */
            height: 20px; /* Increased from 18px */
            cursor: pointer;
        }
        
        .checkbox-item label {
            cursor: pointer;
            font-weight: 500;
            color: var(--dark);
            font-size: 16px; /* Increased font size */
        }
        
        .action-buttons {
            display: flex;
            gap: 20px; /* Increased gap */
            margin-top: 40px; /* Increased from 30px */
        }
        
        .generate-btn {
            flex: 1;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 15px; /* Increased from 12px */
            padding: 20px; /* Increased from 16px */
            font-size: 20px; /* Increased from 18px */
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px; /* Increased gap */
        }
        
        .generate-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-4px); /* Enhanced hover effect */
            box-shadow: 0 15px 25px rgba(67, 97, 238, 0.25);
        }
        
        .reset-btn {
            flex: 1;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 15px; /* Increased from 12px */
            padding: 20px; /* Increased from 16px */
            font-size: 20px; /* Increased from 18px */
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px; /* Increased gap */
        }
        
        .reset-btn:hover {
            background: #5a6268;
            transform: translateY(-4px); /* Enhanced hover effect */
            box-shadow: 0 15px 25px rgba(108, 117, 125, 0.25);
        }
        
        .strength-meter {
            height: 12px; /* Increased from 8px */
            background: #e9ecef;
            border-radius: 6px; /* Increased from 4px */
            margin: 20px 0; /* Increased margin */
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 6px;
            transition: width 0.5s ease;
        }
        
        .strength-text {
            text-align: right;
            font-weight: 600;
            font-size: 16px; /* Increased from 14px */
        }
        
        .toast {
            position: fixed;
            bottom: 30px; /* Increased from 20px */
            right: 30px; /* Increased from 20px */
            background: var(--success);
            color: white;
            padding: 20px 30px; /* Increased padding */
            border-radius: 12px; /* Increased from 10px */
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px; /* Increased gap */
            transform: translateY(120px); /* Increased translation */
            opacity: 0;
            transition: all 0.3s;
            z-index: 1000;
            font-size: 16px;
        }
        
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .main-container {
                padding: 25px 15px;
                max-width: 100%;
            }
            
            .option-group {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .password-display {
                font-size: 20px;
                padding: 20px;
                min-height: 90px;
            }
            
            .header-card,
            .generator-card {
                padding: 25px;
            }
        }
        
        /* Content wrapper for better spacing */
        .content-wrapper {
            flex: 1;
        }
        
        /* Back button styling */
        .btn-outline-primary {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 10px;
            border-width: 2px;
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include '../includes/header.php'; ?>
    
    <div class="content-wrapper">
        <div class="main-container">
            
            <!-- Generator Card -->
            <div class="generator-card">
                <!-- Generated Password Display -->
                <div class="password-display" id="passwordDisplay">
                    <span id="generatedPassword">Click Generate to create a password</span>
                    <button class="copy-btn" id="copyBtn">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                
                <!-- Password Strength -->
                <div class="strength-meter">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <div class="strength-text">
                    <span id="strengthText">Password Strength: Low</span>
                </div>
                
                <!-- Password Length -->
                <div class="settings-section">
                    <h3 class="settings-title">Password Length</h3>
                    <div class="length-value">
                        <span id="lengthValue">12</span> characters
                    </div>
                    <input type="range" class="length-slider" id="lengthSlider" min="8" max="32" value="12">
                </div>
                
                <!-- Password Type Options -->
                <div class="settings-section">
                    <h3 class="settings-title">Password Type</h3>
                    <div class="option-group" id="passwordTypeGroup">
                        <div class="option-btn active" data-type="strong">
                            <i class="fas fa-shield-alt"></i>
                            <div>Strong Password</div>
                            <small>Mixed characters</small>
                        </div>
                        <div class="option-btn" data-type="memorable">
                            <i class="fas fa-brain"></i>
                            <div>Memorable</div>
                            <small>Easy to remember</small>
                        </div>
                        <div class="option-btn" data-type="numeric">
                            <i class="fas fa-hashtag"></i>
                            <div>Numeric Only</div>
                            <small>Numbers only</small>
                        </div>
                        <div class="option-btn" data-type="pin">
                            <i class="fas fa-unlock-alt"></i>
                            <div>PIN Code</div>
                            <small>4-6 digits</small>
                        </div>
                    </div>
                </div>
                
                <!-- Character Options -->
                <div class="settings-section">
                    <h3 class="settings-title">Character Sets</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox" id="uppercase" checked>
                            <label for="uppercase">Uppercase Letters (A-Z)</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="lowercase" checked>
                            <label for="lowercase">Lowercase Letters (a-z)</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="numbers" checked>
                            <label for="numbers">Numbers (0-9)</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="symbols" checked>
                            <label for="symbols">Symbols (!@#$%^&*)</label>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="generate-btn" id="generateBtn">
                        <i class="fas fa-bolt"></i> Generate Password
                    </button>
                    <button class="reset-btn" id="resetBtn">
                        <i class="fas fa-redo"></i> Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include Footer -->
    <?php include '../includes/footer.php'; ?>
    
    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span>Password copied to clipboard!</span>
    </div>
    
    <script>
        // DOM Elements
        const passwordDisplay = document.getElementById('generatedPassword');
        const copyBtn = document.getElementById('copyBtn');
        const lengthSlider = document.getElementById('lengthSlider');
        const lengthValue = document.getElementById('lengthValue');
        const generateBtn = document.getElementById('generateBtn');
        const resetBtn = document.getElementById('resetBtn');
        const toast = document.getElementById('toast');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');
        
        // Character sets
        const charSets = {
            uppercase: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            lowercase: 'abcdefghijklmnopqrstuvwxyz',
            numbers: '0123456789',
            symbols: '!@#$%^&*()_+-=[]{}|;:,.<>?'
        };
        
        // Initialize
        updateLengthValue();
        
        // Event Listeners
        lengthSlider.addEventListener('input', updateLengthValue);
        generateBtn.addEventListener('click', generatePassword);
        resetBtn.addEventListener('click', resetSettings);
        copyBtn.addEventListener('click', copyPassword);
        
        // Password type selection
        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Adjust settings based on type
                const type = this.dataset.type;
                adjustSettingsForType(type);
                generatePassword();
            });
        });
        
        // Update length display
        function updateLengthValue() {
            lengthValue.textContent = lengthSlider.value;
        }
        
        // Adjust settings based on password type
        function adjustSettingsForType(type) {
            const uppercase = document.getElementById('uppercase');
            const lowercase = document.getElementById('lowercase');
            const numbers = document.getElementById('numbers');
            const symbols = document.getElementById('symbols');
            
            switch(type) {
                case 'strong':
                    uppercase.checked = true;
                    lowercase.checked = true;
                    numbers.checked = true;
                    symbols.checked = true;
                    lengthSlider.value = 16;
                    break;
                case 'memorable':
                    uppercase.checked = true;
                    lowercase.checked = true;
                    numbers.checked = true;
                    symbols.checked = false;
                    lengthSlider.value = 12;
                    break;
                case 'numeric':
                    uppercase.checked = false;
                    lowercase.checked = false;
                    numbers.checked = true;
                    symbols.checked = false;
                    lengthSlider.value = 8;
                    break;
                case 'pin':
                    uppercase.checked = false;
                    lowercase.checked = false;
                    numbers.checked = true;
                    symbols.checked = false;
                    lengthSlider.value = 6;
                    break;
            }
            updateLengthValue();
        }
        
        // Generate password
        function generatePassword() {
            const length = parseInt(lengthSlider.value);
            const type = document.querySelector('.option-btn.active').dataset.type;
            
            let charset = '';
            let password = '';
            
            // Build charset based on selected options
            if (document.getElementById('uppercase').checked) {
                charset += charSets.uppercase;
            }
            if (document.getElementById('lowercase').checked) {
                charset += charSets.lowercase;
            }
            if (document.getElementById('numbers').checked) {
                charset += charSets.numbers;
            }
            if (document.getElementById('symbols').checked) {
                charset += charSets.symbols;
            }
            
            // If no character sets selected, use all
            if (charset === '') {
                charset = charSets.lowercase + charSets.uppercase + charSets.numbers;
                document.getElementById('uppercase').checked = true;
                document.getElementById('lowercase').checked = true;
                document.getElementById('numbers').checked = true;
            }
            
            // Generate based on type
            switch(type) {
                case 'memorable':
                    password = generateMemorablePassword(length);
                    break;
                case 'pin':
                    password = generatePinCode(length);
                    break;
                default:
                    password = generateRandomPassword(charset, length);
            }
            
            passwordDisplay.textContent = password;
            updatePasswordStrength(password);
        }
        
        // Generate random password
        function generateRandomPassword(charset, length) {
            let password = '';
            const charsetLength = charset.length;
            
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charsetLength);
                password += charset[randomIndex];
            }
            
            return password;
        }
        
        // Generate memorable password
        function generateMemorablePassword(length) {
            const words = ['apple', 'brave', 'cloud', 'dream', 'eagle', 'flame', 'globe', 'heart', 'iron', 'jump'];
            const separators = ['-', '_', '.', '@', '!', '&'];
            
            let password = '';
            const wordCount = Math.ceil(length / 6);
            
            for (let i = 0; i < wordCount; i++) {
                const randomWord = words[Math.floor(Math.random() * words.length)];
                password += randomWord.charAt(0).toUpperCase() + randomWord.slice(1);
                
                if (i < wordCount - 1) {
                    const randomSeparator = separators[Math.floor(Math.random() * separators.length)];
                    password += randomSeparator;
                }
            }
            
            // Add numbers if needed
            if (password.length < length) {
                password += Math.floor(Math.random() * 100);
            }
            
            return password.substring(0, length);
        }
        
        // Generate PIN code
        function generatePinCode(length) {
            let pin = '';
            for (let i = 0; i < length; i++) {
                pin += Math.floor(Math.random() * 10);
            }
            return pin;
        }
        
        // Update password strength indicator
        function updatePasswordStrength(password) {
            let strength = 0;
            const length = password.length;
            
            // Length score
            if (length >= 8) strength += 1;
            if (length >= 12) strength += 1;
            if (length >= 16) strength += 1;
            
            // Character variety score
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Calculate percentage and update UI
            const percentage = Math.min(100, (strength / 7) * 100);
            strengthFill.style.width = percentage + '%';
            
            // Set color and text
            let color, text;
            if (percentage >= 80) {
                color = '#28a745';
                text = 'Very Strong';
            } else if (percentage >= 60) {
                color = '#17a2b8';
                text = 'Strong';
            } else if (percentage >= 40) {
                color = '#ffc107';
                text = 'Good';
            } else if (percentage >= 20) {
                color = '#fd7e14';
                text = 'Weak';
            } else {
                color = '#dc3545';
                text = 'Very Weak';
            }
            
            strengthFill.style.backgroundColor = color;
            strengthText.textContent = `Password Strength: ${text}`;
            strengthText.style.color = color;
        }
        
        // Copy password to clipboard
        function copyPassword() {
            const password = passwordDisplay.textContent;
            
            if (password === 'Click Generate to create a password') {
                showToast('Generate a password first!', 'warning');
                return;
            }
            
            navigator.clipboard.writeText(password).then(() => {
                // Visual feedback
                copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                copyBtn.classList.add('copied');
                
                showToast('Password copied to clipboard!', 'success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
                    copyBtn.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showToast('Failed to copy password', 'error');
            });
        }
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toastIcon = toast.querySelector('i');
            const toastText = toast.querySelector('span');
            
            toastText.textContent = message;
            
            // Set icon and color based on type
            switch(type) {
                case 'success':
                    toast.style.background = '#28a745';
                    toastIcon.className = 'fas fa-check-circle';
                    break;
                case 'warning':
                    toast.style.background = '#ffc107';
                    toastIcon.className = 'fas fa-exclamation-triangle';
                    break;
                case 'error':
                    toast.style.background = '#dc3545';
                    toastIcon.className = 'fas fa-times-circle';
                    break;
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
        
        // Reset all settings
        function resetSettings() {
            lengthSlider.value = 12;
            updateLengthValue();
            
            // Reset checkboxes
            document.getElementById('uppercase').checked = true;
            document.getElementById('lowercase').checked = true;
            document.getElementById('numbers').checked = true;
            document.getElementById('symbols').checked = true;
            
            // Reset to strong password type
            document.querySelectorAll('.option-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('[data-type="strong"]').classList.add('active');
            
            // Generate new password
            generatePassword();
            
            showToast('Settings reset to default', 'success');
        }
        
        // Generate initial password on page load
        generatePassword();
    </script>
</body>
</html>