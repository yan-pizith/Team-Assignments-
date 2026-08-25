<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$id = $_GET['id'] ?? null;
if (!$id) { exit("Invalid ID"); }

$database = new Database();
$db = $database->getConnection();

$sale_stmt = $db->prepare("SELECT s.*, c.name as customer_name, u.username FROM sales s LEFT JOIN customers c ON s.customer_id = c.id JOIN users u ON s.user_id = u.id WHERE s.id = :id");
$sale_stmt->execute([':id' => $id]);
$sale = $sale_stmt->fetch(PDO::FETCH_ASSOC);

$items_stmt = $db->prepare("SELECT si.*, m.name FROM sale_items si JOIN medicines m ON si.medicine_id = m.id WHERE si.sale_id = :id");
$items_stmt->execute([':id' => $id]);
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?= $id; ?></title>
    <style>
        body { font-family: sans-serif; width: 80mm; padding: 5mm; margin: auto; }
        .text-center { text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { text-align: left; font-size: 12px; padding: 4px 0; }
        .border-top { border-top: 1px dashed #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3>PHARMACY STORE</h3>
        <p>វិក្កយបត្រ / Receipt</p>
        <p>កាលបរិច្ឆេទ: <?= $sale['created_at']; ?></p>
    </div>
    <div class="border-top"></div>
    <p style="font-size: 12px;">
        លេខ៖ #INV-<?= str_pad($sale['id'], 5, '0', STR_PAD_LEFT); ?><br>
        អតិថិជន៖ <?= $sale['customer_name'] ?? 'General'; ?><br>
        អ្នកលក់៖ <?= $sale['username']; ?>
    </p>
    <table class="table">
        <thead>
            <tr class="border-top"><th>ទំនិញ</th><th>ចំនួន</th><th>តម្លៃ</th></tr>
        </thead>
        <tbody>
            <?php while ($item = $items_stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td><?= $item['quantity']; ?></td>
                <td>$<?= number_format($item['unit_price'] * $item['quantity'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="border-top" style="margin-top: 10px;"></div>
    <h4 style="text-align: right;">សរុប៖ $<?= number_format($sale['grand_total'], 2); ?></h4>
    <p class="text-center" style="font-size: 11px;">សូមអរគុណ! សូមអញ្ជើញមកម្តងទៀត!</p>
</body>
</html>