<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $db->beginTransaction();
    try {
        $stmt = $db->prepare("INSERT INTO sales (customer_id, user_id, grand_total) VALUES (:customer_id, :user_id, :grand_total)");
        $stmt->execute([
            ':customer_id' => $data['customer_id'] ?: null,
            ':user_id' => $_SESSION['user_id'] ?? 1,
            ':grand_total' => $data['grand_total']
        ]);
        $sale_id = $db->lastInsertId();

        $stmt_item = $db->prepare("INSERT INTO sale_items (sale_id, medicine_id, quantity, unit_price) VALUES (:sale_id, :medicine_id, :quantity, :price)");
        $stmt_stock = $db->prepare("UPDATE medicines SET stock_quantity = stock_quantity - :qty WHERE id = :id");

        foreach ($data['cart'] as $item) {
            $stmt_item->execute([':sale_id' => $sale_id, ':medicine_id' => $item['id'], ':quantity' => $item['qty'], ':price' => $item['price']]);
            $stmt_stock->execute([':qty' => $item['qty'], ':id' => $item['id']]);
        }

        $db->commit();
        echo json_encode(['success' => true, 'sale_id' => $sale_id]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}