<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Get supplier name
$stmt = $pdo->prepare("SELECT name FROM suppliers WHERE id=?");
$stmt->execute([$id]);
$supplierName = $stmt->fetchColumn();

if (!$supplierName) {
    die("Supplier not found.");
}

// Check usage
$chk = $pdo->prepare("SELECT COUNT(*) FROM products WHERE supplier=?");
$chk->execute([$supplierName]);

if ($chk->fetchColumn() > 0) {
    die("Delete blocked. Supplier is linked with assets.");
}

// Safe delete
$del = $pdo->prepare("DELETE FROM suppliers WHERE id=?");
$del->execute([$id]);
