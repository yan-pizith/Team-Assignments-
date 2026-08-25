<?php
// api/search_api.php
require_once "../config/database.php";

$keyword = $_GET['q'] ?? '';

if (!empty($keyword)) {
    $stmt = $pdo->prepare("SELECT id, medicine_code, medicine_name, selling_price, quantity 
                           FROM medicines 
                           WHERE (medicine_name LIKE :kw OR medicine_code LIKE :kw) 
                           AND quantity > 0 
                           LIMIT 10");
    $stmt->execute(['kw' => "%{$keyword}%"]);
    $medicines = $stmt->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode($medicines);
    exit();
}

echo json_encode([]);
?>