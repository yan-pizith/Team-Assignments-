<?php
// api/notification_api.php
header('Content-Type: application/json; charset=utf-8');
require_once "../includes/auth_check.php";
require_once "../config/database.php";

try {
    // ថ្នាំជិតអស់ស្តុក (<= 10)
    $stmt_stock = $pdo->query("SELECT COUNT(*) FROM medicines WHERE quantity <= 10 AND status = 'Active'");
    $low_stock_count = $stmt_stock->fetchColumn();

    // ថ្នាំជិតផុតកំណត់ (ក្នុងរយ:ពេល ៣០ ថ្ងៃ)
    $stmt_exp = $pdo->query("SELECT COUNT(*) FROM medicines 
                             WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                               AND status = 'Active'");
    $expired_count = $stmt_exp->fetchColumn();

    $total_alerts = $low_stock_count + $expired_count;

    echo json_encode([
        'success'         => true,
        'total_alerts'    => $total_alerts,
        'low_stock_count' => $low_stock_count,
        'expired_count'   => $expired_count
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>