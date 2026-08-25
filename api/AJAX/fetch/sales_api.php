<?php
// api/sales_api.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$action  = $_GET['action'] ?? 'get_detail';
$sale_id = intval($_GET['id'] ?? 0);

try {
    if ($action === 'get_detail' && $sale_id > 0) {
        // ទាញព័ត៌មានវិក្កយបត្រ
        $stmt = $pdo->prepare("SELECT s.*, u.full_name AS seller_name, c.name AS customer_name 
                               FROM sales s 
                               LEFT JOIN users u ON s.user_id = u.id 
                               LEFT JOIN customers c ON s.customer_id = c.id 
                               WHERE s.id = :id");
        $stmt->execute(['id' => $sale_id]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) {
            echo json_encode(['success' => false, 'message' => 'រកមិនឃើញវិក្កយបត្រនេះទេ!']);
            exit();
        }

        // ទាញមុខទំនិញក្នុងវិក្កយបត្រ
        $stmt_items = $pdo->prepare("SELECT si.*, m.medicine_name 
                                     FROM sale_items si 
                                     JOIN medicines m ON si.medicine_id = m.id 
                                     WHERE si.sale_id = :id");
        $stmt_items->execute(['id' => $sale_id]);
        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data'    => [
                'sale'  => $sale,
                'items' => $items
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid Request']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>