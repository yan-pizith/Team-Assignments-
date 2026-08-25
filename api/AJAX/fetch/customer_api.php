<?php
// api/customer_api.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$action = $_GET['action'] ?? ($_SERVER['REQUEST_METHOD'] === 'POST' ? 'add' : 'search');

try {
    if ($action === 'search') {
        $query = trim($_GET['q'] ?? '');
        $stmt = $pdo->prepare("SELECT id, name, phone, address FROM customers 
                               WHERE name LIKE :q OR phone LIKE :q 
                               ORDER BY name ASC LIMIT 10");
        $stmt->execute(['q' => "%{$query}%"]);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $customers]);
    } 
    elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $name    = trim($input['name'] ?? '');
        $phone   = trim($input['phone'] ?? '');
        $address = trim($input['address'] ?? '');

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'សូមបញ្ចូលឈ្មោះអតិថិជន!']);
            exit();
        }

        $stmt = $pdo->prepare("INSERT INTO customers (name, phone, address) VALUES (:name, :phone, :address)");
        $stmt->execute([
            'name'    => $name,
            'phone'   => $phone,
            'address' => $address
        ]);

        $new_id = $pdo->lastInsertId();
        echo json_encode([
            'success' => true, 
            'message' => 'បន្ថែមអតិថិជនជោគជ័យ!', 
            'data'    => ['id' => $new_id, 'name' => $name, 'phone' => $phone]
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>