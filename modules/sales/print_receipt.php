<?php
// modules/sales/print_receipt.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: pos.php"); exit(); }

// ទាញយកព័ត៌មាន Sales Master
$stmt = $pdo->prepare("SELECT s.*, u.full_name as cashier, c.name as customer_name 
                       FROM sales s 
                       LEFT JOIN users u ON s.user_id = u.id 
                       LEFT JOIN customers c ON s.customer_id = c.id 
                       WHERE s.id = :id");
$stmt->execute(['id' => $id]);
$sale = $stmt->fetch();

if (!$sale) { header("Location: pos.php"); exit(); }

// ទាញយកព័ត៌មាន Sale Items
$stmt_items = $pdo->prepare("SELECT si.*, m.medicine_name 
                             FROM sale_items si 
                             JOIN medicines m ON si.medicine_id = m.id 
                             WHERE si.sale_id = :id");
$stmt_items->execute(['id' => $id]);
$items = $stmt_items->fetchAll();
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>Receipt - <?= $sale['invoice_no']; ?></title>
    <style>
        body { font-family: monospace; width: 80mm; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { font-size: 12px; padding: 4px 0; }
        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print();">

<div class="no-print" style="margin-bottom: 15px;">
    <a href="pos.php" style="padding: 5px 10px; background: #6c757d; color: #fff; text-decoration: none; border-radius: 3px;">← ត្រឡប់ទៅ POS</a>
    <button onclick="window.print();" style="padding: 5px 10px; background: #198754; color: #fff; border: none; border-radius: 3px; cursor: pointer;">Print</button>
</div>

<div class="text-center">
    <h3 style="margin:0;">PHARMACY STORE</h3>
    <p style="margin:2px 0; font-size:12px;">អាសយដ្ឋាន: រាជធានីភ្នំពេញ</p>
    <p style="margin:2px 0; font-size:12px;">ទូរស័ព្ទ: 012 345 678</p>
</div>

<div class="line"></div>

<div style="font-size: 12px;">
    <div><strong>Invoice:</strong> <?= $sale['invoice_no']; ?></div>
    <div><strong>កាលបរិច្ឆេទ:</strong> <?= $sale['created_at']; ?></div>
    <div><strong>អ្នកគិតលុយ:</strong> <?= htmlspecialchars($sale['cashier']); ?></div>
    <div><strong>អតិថិជន:</strong> <?= htmlspecialchars($sale['customer_name'] ?? 'General'); ?></div>
</div>

<div class="line"></div>

<table>
    <thead>
        <tr>
            <th align="left">ទំនិញ</th>
            <th align="center">ចំនួន</th>
            <th align="right">តម្លៃ</th>
            <th align="right">សរុប</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['medicine_name']); ?></td>
                <td align="center"><?= $item['quantity']; ?></td>
                <td align="right">$<?= number_format($item['unit_price'], 2); ?></td>
                <td align="right">$<?= number_format($item['subtotal'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="line"></div>

<table style="font-size: 12px;">
    <tr>
        <td>សរុបដើម (Subtotal):</td>
        <td align="right">$<?= number_format($sale['subtotal'], 2); ?></td>
    </tr>
    <tr>
        <td>បញ្ចុះតម្លៃ (Discount):</td>
        <td align="right">-$<?= number_format($sale['discount'], 2); ?></td>
    </tr>
    <tr>
        <th><strong>សរុបត្រូវបង់:</strong></th>
        <th align="right"><strong>$<?= number_format($sale['grand_total'], 2); ?></strong></th>
    </tr>
</table>

<div class="line"></div>
<p class="text-center" style="font-size:11px;">សូមអរគុណ! សូមអញ្ជើញមកសារជាថ្មី!</p>

</body>
</html>