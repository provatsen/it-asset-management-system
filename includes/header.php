<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$allowedUsers = ['Provat', 'Kamrul'];
$showAdminLinks = (isset($_SESSION['username']) && in_array($_SESSION['username'], $allowedUsers));
date_default_timezone_set('Asia/Dhaka');

$current_date = date("F j, Y");

$hour = date('H');
$greeting_message = ($hour >= 5 && $hour < 12) ? "Good Morning" :
                    (($hour >= 12 && $hour < 17) ? "Good Afternoon" : "Good Evening");

$current_page = basename($_SERVER['PHP_SELF']);
$is_product_page = strpos($_SERVER['REQUEST_URI'], '/products/') !== false;
$is_employee_page = strpos($_SERVER['REQUEST_URI'], '/employee/') !== false;
$is_asset_page = strpos($_SERVER['REQUEST_URI'], '/assets/') !== false;
$is_user_page = strpos($_SERVER['REQUEST_URI'], '/user/') !== false;
$is_supplier_page = strpos($_SERVER['REQUEST_URI'], '/supplier/') !== false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title><?= isset($page_title) ? $page_title : 'IT-Asset Database'; ?></title>
  <!-- Google Fonts - Roboto (Regular 400) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
  <!-- Google Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <!-- Optional: Add Material Icons Outlined if you want outlined version -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" /> -->
  <style>
    :root {
      --primary-blue: #3b82f6;
      --dark-blue: #1e40af;
      --nav-bg: rgba(31, 41, 55, 0.98);
      --nav-hover: rgba(55, 65, 81, 0.9);
      --danger-red: #ef4444;
      --danger-hover: #dc2626;
      --text-light: #f9fafb;
      --text-muted: #d1d5db;
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
      --admin-purple: #6366f1;
      --admin-hover: #4f46e5;
    }

    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f3f4f6;
    }

    /* Material Icons Base Styles */
    .material-icons {
      font-size: 18px;
      vertical-align: middle;
      margin-right: 10px;
      transition: transform 0.2s ease;
    }
    
    .dropdown-menu .material-icons {
      font-size: 16px;
    }

    /* Header Container */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
      color: var(--text-light);
      display: flex;
      flex-direction: column;
      padding: 0.8rem 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      z-index: 1001;
      transition: all 0.3s ease;
    }

    @media (min-width: 992px) {
      .header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 2rem;
        height: 70px;
      }
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 0.8rem;
      text-decoration: none;
    }

    .logo-img {
      height: 60px;
      width: auto;
      transition: transform 0.3s ease;
    }

    .logo-text h1 {
      font-size: 1.5rem;
      margin: 0;
      color: white;
      font-weight: 700;
      line-height: 1;
    }

    .logo-text p {
      margin: 0;
      font-size: 0.95rem;
      color: #a7f3d0;
    }

    /* User Info Section */
    .user-info-container {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem 1rem;
      margin-top: 0.8rem;
      font-size: 0.8rem;
    }

    @media (min-width: 992px) {
      .user-info-container {
        margin-top: 0;
        font-size: 0.85rem;
      }
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 0.4rem;
      background: var(--glass-bg);
      padding: 0.3rem 0.6rem;
      border-radius: 6px;
      border: 1px solid var(--glass-border);
      white-space: nowrap;
    }

    .info-label { color: var(--text-muted); }
    .info-value { font-weight: 600; }

    
      /* Nav Bar */
    .nav-bar {
        position: fixed;
        top: 115px; /* Adjust based on mobile header height */
        left: 0;
        width: 100%;
        background-color: var(--nav-bg);
        z-index: 1000;
        border-bottom: 2px solid var(--primary-blue);
        transition: top 0.3s ease, transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: top;
    }
    
    @media (min-width: 992px) {
        .nav-bar {
            top: 70px;
        }
    }
    
    .mobile-menu-toggle {
        position: absolute;
        right: 1rem;
        top: 0.8rem;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .mobile-menu-toggle:hover {
        background: var(--nav-hover);
        transform: scale(1.05);
    }
    
    .mobile-menu-toggle:active {
        transform: scale(0.95);
    }
    
    @media (min-width: 992px) {
        .mobile-menu-toggle { 
            display: none; 
        }
    }
    
    .nav-items-container {
        display: none;
        flex-direction: column;
        max-height: 70vh;
        overflow-y: auto;
        animation: slideDown 0.3s ease-out;
    }
    
    .nav-items-container.show {
        display: flex;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (min-width: 992px) {
        .nav-items-container {
            display: flex;
            flex-direction: row;
            max-height: none;
            overflow-y: visible;
            width: 100%;
            animation: none;
        }
    }
    
    .nav-item-link {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem;
        color: var(--text-light);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    @media (min-width: 992px) {
        .nav-item-link {
            padding: 1rem 1.5rem;
            border-bottom: none;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
    }
    
    .nav-item-link:hover {
        background: var(--nav-hover);
        color: var(--primary-blue);
        padding-left: 1.5rem;
    }
    
    .nav-item-link.active {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.15), transparent);
        color: var(--primary-blue);
        border-left: 3px solid var(--primary-blue);
        font-weight: 600;
    }
    
    @media (min-width: 992px) {
        .nav-item-link:hover {
            padding-left: 1.7rem;
        }
        
        .nav-item-link.active {
            border-left: none;
            border-bottom: 3px solid var(--primary-blue);
        }
    }
    
    .nav-item-link:hover .material-icons {
        transform: translateX(2px);
    }
    
    /* Dropdowns */
    .dropdown { 
        position: relative; 
    }
    
    .dropdown-menu {
        display: none;
        background: #2d3748;
        list-style: none;
        padding: 0.5rem 0;
        margin: 0;
        animation: fadeIn 0.2s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (min-width: 992px) {
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 220px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-radius: 0 0 6px 6px;
            border-top: 2px solid var(--primary-blue);
        }
        
        .dropdown:hover .dropdown-menu { 
            display: block;
            animation: slideIn 0.25s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
    
    .dropdown.open .dropdown-menu { 
        display: block; 
    }
    
    .dropdown-menu a {
        padding: 0.8rem 1.5rem;
        display: flex;
        align-items: center;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .dropdown-menu a:hover { 
        background: rgba(255, 255, 255, 0.1); 
        color: white;
        padding-left: 1.8rem;
        border-left-color: var(--primary-blue);
    }
    
    .dropdown-menu a:hover .material-icons {
        transform: scale(1.1);
    }
    
    /* Right Side items */
    .nav-right {
        display: flex;
        flex-direction: column;
    }
    
    @media (min-width: 992px) {
        .nav-right {
            flex-direction: row;
            margin-left: auto;
        }
    }
    
    .btn-logout, .btn-admin {
        padding: 0.8rem 1.5rem;
        margin: 0.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.25s ease;
        text-align: center;
    }
    
    .btn-logout { 
        background: var(--danger-red) !important; 
        color: white !important; 
    }
    
    .btn-logout:hover { 
        background: var(--danger-hover) !important; 
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    
    .btn-admin { 
        background: var(--admin-purple) !important; 
        color: white !important;
    }
    
    .btn-admin:hover {
        background: var(--admin-hover) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
    }
    
    /* Add a smooth transition for the active state indicator */
    .nav-item-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -1px;
        width: 100%;
        height: 2px;
        background: var(--primary-blue);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .nav-item-link.active::after {
        transform: scaleX(1);
    }
    
    /* Spacer */
    .spacer { 
        height: 160px; 
        transition: height 0.3s ease;
    }
    
    @media (min-width: 992px) { 
        .spacer { 
            height: 120px; 
        } 
    }
  </style>
</head>
<body>

  <header class="header">
    <a href="/dashboard.php" class="logo-container">
      <img src="/image/sg_logo.png" alt="Logo" class="logo-img">
      <div class="logo-text">
        <h1>STERLING GROUP</h1>
        <p>IT-Asset Database</p>
      </div>
    </a>

    <button class="mobile-menu-toggle" id="menuToggle">
      <span class="material-icons">menu</span>
    </button>

    <div class="user-info-container">
      <div class="info-item">
        <span class="info-label"><span class="material-icons">person</span></span>
        <span class="info-value"><?= htmlspecialchars($_SESSION["username"] ?? 'Guest') ?></span>
      </div>
      <div class="info-item">
        <span class="info-label"><span class="material-icons">wb_sunny</span></span>
        <span class="info-value"><?= $greeting_message ?></span>
      </div>
      <div class="info-item">
        <span class="info-label"><span class="material-icons">schedule</span></span>
        <span class="info-value" id="liveClock">00:00:00</span>
      </div>
      <div class="info-item">
        <span class="info-label"><span class="material-icons">event</span></span>
        <span class="info-value"><?= $current_date ?></span>
      </div>
    </div>
  </header>

  <nav class="nav-bar">
    <div class="nav-items-container" id="navItems">
      <a href="/dashboard.php" class="nav-item-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
        <span class="material-icons">dashboard</span> Dashboard
      </a>

      <!-- Products -->
        <div class="dropdown">
        <a href="#" class="nav-item-link dropdown-toggle <?=$is_product_page?'active':''?>">
        <span class="material-icons">inventory</span> Inventory
        </a>
        <div class="dropdown-menu">
        <a href="/products/add_product.php"><span class="material-icons">add_circle</span> Add Product</a>
        <a href="/products/edit_product.php"><span class="material-icons">edit</span> Edit Product</a>
        <a href="/products/search_product.php"><span class="material-icons">search</span> Search Product</a>
        <a href="/products/view_all_products.php"><span class="material-icons">list</span> View All Product</a> 
        <a href="/warranty/expired_warranty.php"><span class="material-icons">security</span> Expire Warranty</a>
        </div>
        </div>
        
        <!-- Employees -->
        <div class="dropdown">
        <a href="#" class="nav-item-link dropdown-toggle <?=$is_employee_page?'active':''?>">
        <span class="material-icons">groups</span> Employees
        </a>
        <div class="dropdown-menu">
        <a href="/employee/add_employee.php"><span class="material-icons">person_add</span> Add Employee</a>
        <a href="/employee/employees.php"><span class="material-icons">contacts</span> Directory</a>
        </div>
        </div>
        
        <!-- Assets -->
        <div class="dropdown">
        <a href="#" class="nav-item-link dropdown-toggle <?=$is_asset_page?'active':''?>">
        <span class="material-icons">computer</span> Assets
        </a>
        <div class="dropdown-menu">
        <a href="/assets/assign_asset.php"><span class="material-icons">arrow_forward</span> Assign Item</a>
        <a href="/assets/return_asset.php"><span class="material-icons">arrow_back</span> Return Asset</a>
        <a href="/assets/assign_report.php"><span class="material-icons">description</span> Reports</a>
        </div>
        </div>
        
        <!-- Suppliers -->
        <div class="dropdown">
        <a href="#" class="nav-item-link dropdown-toggle <?=$is_supplier_page?'active':''?>">
        <span class="material-icons">local_shipping</span> Suppliers
        </a>
        <div class="dropdown-menu">
        <a href="/supplier/add_supplier.php"><span class="material-icons">add_business</span> Add Supplier</a>
        <a href="/supplier/supplier_list.php"><span class="material-icons">list_alt</span> Supplier List</a>
        <a href="/supplier/supplier_ledger.php"><span class="material-icons">account_balance_wallet</span> Supplier Ledger</a>
        <a href="/supplier/supplier_performance.php"><span class="material-icons">analytics</span> Supplier Performance</a>
        </div>
        </div>
        
        <!-- Warranty -->
        <a href="/warranty/check_warranty.php" class="nav-item-link <?=$current_page==='check_warranty.php'?'active':''?>">
        <span class="material-icons">security</span> Warranty Traker
        </a>
        
        <!-- generate/password -->
        <a href="/generate/password-generator.php" class="nav-item-link <?=$current_page==='password-generator.php'?'active':''?>">
        <span class="material-icons">key</span>Generate Password
        </a>

      <div class="nav-right">
            <?php if ($showAdminLinks): ?>
              <div class="dropdown">
                <a href="#" class="nav-item-link btn-admin dropdown-toggle">
                  <span class="material-icons">admin_panel_settings</span> Admin
                    </a>
                <div class="dropdown-menu">
                  <a href="/user/create_user.php"><span class="material-icons">person_add_alt</span> Create User</a>
                  <a href="/user/delete_user.php"><span class="material-icons">person_remove</span> Delete User</a>
                  <a href="/products/delete_product.php"><span class="material-icons">delete</span> Delete Product</a>
                </div>
              </div>
            <?php endif; ?>
            
        <a href="/auth/logout.php" class="nav-item-link btn-logout">
          <span class="material-icons">logout</span> Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="spacer"></div>

  <script>
    // Live Clock
    function updateClock() {
      const now = new Date();
      document.getElementById('liveClock').textContent = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
      });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Toggle Mobile Main Menu
    const menuToggle = document.getElementById('menuToggle');
    const navItems = document.getElementById('navItems');
    menuToggle.addEventListener('click', () => {
      navItems.classList.toggle('show');
      const icon = menuToggle.querySelector('.material-icons');
      icon.textContent = icon.textContent === 'menu' ? 'close' : 'menu';
    });

    // Mobile Dropdown Logic
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
      toggle.addEventListener('click', (e) => {
        if (window.innerWidth < 992) {
          e.preventDefault();
          const parent = toggle.closest('.dropdown');
          parent.classList.toggle('open');
        }
      });
    });
  </script>
</body>
</html>