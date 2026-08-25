<?php
// api/medicine_api.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/auth_check.php";
require_once "../config/database.php";

$action = $_GET['action'] ?? 'search';
$query  = trim($_GET['q'] ?? '');

try {
    if ($action === 'search') {
        if (!empty($query)) {
            $stmt = $pdo->prepare("SELECT id, barcode, medicine_name, selling_price, quantity, expiry_date 
                                   FROM medicines 
                                   WHERE (medicine_name LIKE :q OR barcode = :exact_q) 
                                     AND status = 'Active' 
                                   LIMIT 15");
            $stmt->execute([
                'q'       => "%{$query}%",
                'exact_q' => $query
            ]);
            $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $medicines]);
        } else {
            echo json_encode(['success' => true, 'data' => []]);
        }
    } 
    elseif ($action === 'get_by_id') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = :id AND status = 'Active'");
        $stmt->execute(['id' => $id]);
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medicine) {
            echo json_encode(['success' => true, 'data' => $medicine]);
        } else {
            echo json_encode(['success' => false, 'message' => 'រកមិនឃើញទិន្នន័យថ្នាំនេះទេ!']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>