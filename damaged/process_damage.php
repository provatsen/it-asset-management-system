<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

// Verify CSRF token
if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Validate inputs
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $damage_reason = trim($_POST['damage_reason'] ?? '');
        $concern_store = trim($_POST['concern_store'] ?? '');

        if (!$product_id || empty($damage_reason) || empty($concern_store)) {
            throw new Exception('All fields are required.');
        }

        // Fetch complete product info
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND status != 'damaged'");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception('Product not found or already marked as damaged.');
        }

        // Insert into damaged_assets table
        $insert = $pdo->prepare("INSERT INTO damaged_assets (
            product_id, product_name, serial_number, damage_reason_text, concern_store, damaged_at,
            category, supplier, purchase_date, warranty, price, product_condition,
            requisition_no, factory_name, product_description, brand, model,
            asset_tag, remarks, category_id, original_created_at, original_updated_at
        ) VALUES (
            :id, :name, :serial, :reason, :store, NOW(),
            :category, :supplier, :purchase_date, :warranty, :price, :condition,
            :req_no, :factory, :description, :brand, :model,
            :asset_tag, :remarks, :cat_id, :created, :updated
        )");

        $insert->execute([
            ':id' => $product['id'],
            ':name' => $product['name'],
            ':serial' => $product['serial_number'],
            ':reason' => $damage_reason,
            ':store' => $concern_store,
            
            ':category' => $product['category'],
            ':supplier' => $product['supplier'],
            ':purchase_date' => $product['purchase_date'],
            ':warranty' => $product['warranty'],
            ':price' => $product['price'],
            ':condition' => $product['product_condition'],
            
            ':req_no' => $product['requisition_no'],
            ':factory' => $product['factory_name'],
            ':description' => $product['product_description'],
            ':brand' => $product['brand'],
            ':model' => $product['model'],
            
            ':asset_tag' => $product['asset_tag'],
            ':remarks' => $product['remarks'],
            ':cat_id' => $product['category_id'],
            ':created' => $product['created_at'],
            ':updated' => $product['updated_at']
        ]);

        // Update product status to 'damaged'
        $update = $pdo->prepare("UPDATE products SET status = 'damaged' WHERE id = ?");
        $update->execute([$product_id]);

        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Damage reported successfully!'
        ]);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Damage entry error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}
?>