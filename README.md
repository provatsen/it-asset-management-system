# IT Asset Management System

A modern web-based **IT Asset Management System** developed using **PHP, MySQL, Bootstrap 5, JavaScript, HTML, and CSS**. This application helps organizations efficiently manage IT assets, employee assignments, warranties, suppliers, and asset lifecycle.

---

## 📌 Features

### Dashboard
- Total Assets Overview
- Assigned Assets
- Available Assets
- Damaged Assets
- Warranty Expiry Alerts
- Recently Added Assets
- Factory-wise Asset Statistics
- Top Asset Users

### Asset Management
- Add New Asset
- Edit Asset Details
- Delete Asset
- Search by Serial Number
- Advanced Filtering
- Print Asset Details

### Employee Management
- Add Employee
- Edit Employee
- Employee Search
- Asset Assignment History

### Asset Assignment
- Assign Assets to Employees
- Return Assets
- Assignment Reports
- Printable Reports

### Warranty Management
- Warranty Tracker
- Expired Warranty Report
- Warranty Certificate Printing

### Supplier Management
- Add Supplier
- Edit Supplier
- Supplier List

### Category Management
- Add Categories
- Edit Categories
- Manage Asset Types


## 🛠️ Technology Stack

- PHP (PDO)
- MySQL
- Bootstrap 5
- JavaScript
- HTML5
- CSS3
- Font Awesome

---

## 📂 Project Structure

```
IT-Asset-Management/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
│   └── db.php
│
├── includes/
│
├── uploads/
│
├── dashboard.php
├── login.php
├── logout.php
├── add_product.php
├── edit_product.php
├── all_products.php
├── asset_assignment.php
├── damaged_assets.php
├── suppliers.php
├── employees.php
└── ...
```

---

## 💻 Installation

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/it-asset-management-system.git
```

### 2. Move the project to your web server

For XAMPP:

```
xampp/htdocs/
```

### 3. Create a MySQL database

Example:

```
inventory_db
```

### 4. Import the SQL file

Import the provided database file using **phpMyAdmin**.

### 5. Configure the database

Update the database connection in:

```
config/db.php
```

Example:

```php
$host = "localhost";
$dbname = "inventory_db";
$username = "root";
$password = "";
```

### 6. Start Apache and MySQL

Open XAMPP Control Panel and start:

- Apache
- MySQL

### 7. Run the application

```
http://localhost/IT-Asset-Management/
```

---

## 📸 Screenshots

You can add screenshots here.

Example:

```
screenshots/dashboard.png
screenshots/login.png
screenshots/assets.png
```

---

## 🚀 Future Improvements

- QR Code Asset Tracking
- Barcode Printing
- Email Notifications
- Role-Based Access Control
- Audit Logs
- Excel Import & Export
- REST API
- Mobile Responsive Enhancements
- Dashboard Charts
- Multi-Branch Support

---

## 🤝 Contributing

Contributions are welcome.

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push the branch
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 👨‍💻 Developer

**Provat Sen**

Assistant Manager – IT

Sterling Laundry Ltd.

GitHub: https://github.com/provatsen

---

## ⭐ Support

If you found this project helpful, please consider giving it a ⭐ on GitHub.
