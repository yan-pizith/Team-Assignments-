<?php
// api/search_api.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 2) {
    echo json_encode(['success' => true, 'data' => []]);
    exit();
}

try {
    // ស្វែងរកថ្នាំ
    $stmt_med = $pdo->prepare("SELECT id, medicine_name AS title, 'medicine' AS type 
                               FROM medicines 
                               WHERE medicine_name LIKE :q LIMIT 5");
    $stmt_med->execute(['q' => "%{$query}%"]);
    $medicines = $stmt_med->fetchAll(PDO::FETCH_ASSOC);

    // ស្វែងរកអតិថិជន
    $stmt_cust = $pdo->prepare("SELECT id, name AS title, 'customer' AS type 
                                FROM customers 
                                WHERE name LIKE :q OR phone LIKE :q LIMIT 5");
    $stmt_cust->execute(['q' => "%{$query}%"]);
    $customers = $stmt_cust->fetchAll(PDO::FETCH_ASSOC);

    // ស្វែងរកវិក្កយបត្រ
    $stmt_sale = $pdo->prepare("SELECT id, invoice_no AS title, 'sale' AS type 
                                FROM sales 
                                WHERE invoice_no LIKE :q LIMIT 5");
    $stmt_sale->execute(['q' => "%{$query}%"]);
    $sales = $stmt_sale->fetchAll(PDO::FETCH_ASSOC);

    $results = array_merge($medicines, $customers, $sales);

    echo json_encode(['success' => true, 'data' => $results]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>