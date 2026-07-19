# IT Asset Management System

A modern web-based **IT Asset Management System** developed using **PHP, MySQL, Bootstrap 5, JavaScript, HTML, and CSS**. This application helps organizations efficiently manage IT assets, employee assignments, warranties, suppliers, and asset lifecycle.

---
## Login Info:
User: administrator
Key: QwertY@26
After logging in, delete all products and others users and create your own users. Remember, the current version only supports the administrator user to access the admin button.

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
```
<img width="1871" height="913" alt="image" src="https://github.com/user-attachments/assets/1a4a624a-b27f-40e4-969c-9f061aa64f70" />
<img width="1728" height="834" alt="image" src="https://github.com/user-attachments/assets/8e3585c3-c81d-45f0-92a7-001ba059e020" />
<img width="1900" height="915" alt="image" src="https://github.com/user-attachments/assets/9f6aea60-e66b-4ec0-b29b-3bb34f73ab1d" />
<img width="1901" height="919" alt="image" src="https://github.com/user-attachments/assets/a4ce2df2-9f1f-4f0b-88a7-1e1e3334a5b1" />
<img width="1920" height="1583" alt="screencapture-itsg-sdldts-net-warranty-check-warranty-php-2026-07-19-09_47_20" src="https://github.com/user-attachments/assets/71606c5a-6c52-4953-aebc-2946d186ecea" />
<img width="1920" height="1008" alt="screencapture-itsg-sdldts-net-generate-password-generator-php-2026-07-19-09_48_17" src="https://github.com/user-attachments/assets/53991c42-0e50-4281-8703-51711879cf08" />
<img width="1920" height="1266" alt="screencapture-itsg-sdldts-net-supplier-supplier-list-php-2026-07-19-09_48_52" src="https://github.com/user-attachments/assets/525b8844-85e0-4256-91c5-1031e5c5422d" />
<img width="1920" height="2959" alt="screencapture-itsg-sdldts-net-supplier-supplier-performance-php-2026-07-19-09_49_12" src="https://github.com/user-attachments/assets/fdc23f83-4d52-4690-96ef-8fb188aba0c8" />
<img width="1920" height="2392" alt="image" src="https://github.com/user-attachments/assets/9156a802-4386-4653-bd26-ffe289f65100" />
<img width="1920" height="1305" alt="screencapture-itsg-sdldts-net-assets-assign-report-php-2026-07-19-09_50_24" src="https://github.com/user-attachments/assets/43de1b70-7bb6-42e5-bea7-eab853cdc7f2" />
<img width="1920" height="1034" alt="image" src="https://github.com/user-attachments/assets/f7d307cd-f5e9-4b70-8ad5-9bf536108a7b" />
<img width="1920" height="1683" alt="image" src="https://github.com/user-attachments/assets/0e26a30d-116a-4519-bfe5-e0b41dd4afb1" />
<img width="1920" height="1176" alt="image" src="https://github.com/user-attachments/assets/b528c2a2-18d8-4321-aeb9-856b40f8b1de" />
<img width="1905" height="913" alt="image" src="https://github.com/user-attachments/assets/687a88bc-6278-474e-9660-74663c820249" />
<img width="1920" height="2396" alt="image" src="https://github.com/user-attachments/assets/7994eb8d-4f3f-4987-bd70-ffcf9f6afd23" />

```

---

## 🚀 Future Improvements

- QR Code Asset Tracking
- Barcode Printing
- Email Notifications
- Role-Based Access Control
- Audit Logs
- Excel Import & Export
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
GitHub: https://github.com/provatsen

---

## ⭐ Support

If you found this project helpful, please consider giving it a ⭐ on GitHub.
