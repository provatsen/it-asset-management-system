<?php
$host = 'localhost';
$db   = 'sdlcutting_inventory_db';
$user = 'sdlcutting_inventory_IT';
$pass = '3OzkT2VpXzMt';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("DB connection failed: " . $e->getMessage());
}
?>
