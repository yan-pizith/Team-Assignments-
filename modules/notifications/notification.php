<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();

$low_stock = $db->query("SELECT * FROM medicines WHERE stock_quantity <= 10")->fetchAll(PDO::FETCH_ASSOC);
$expired = $db->query("SELECT * FROM medicines WHERE expiry_date <= CURDATE()")->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <h4 class="mb-4"><i class="bi bi-bell me-2"></i>ការជូនដំណឹងប្រព័ន្ធ</h4>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">ឱសថផុតកំណត់ (<?= count($expired); ?>)</div>
        <div class="card-body">
            <ul>
                <?php foreach ($expired as $e): ?>
                    <li><strong><?= htmlspecialchars($e['name']); ?></strong> ផុតកំណត់នៅថ្ងៃទី៖ <?= $e['expiry_date']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">ឱសថជិតអស់ពីស្តុក (<?= count($low_stock); ?>)</div>
        <div class="card-body">
            <ul>
                <?php foreach ($low_stock as $l): ?>
                    <li><strong><?= htmlspecialchars($l['name']); ?></strong> នៅសល់៖ <?= $l['stock_quantity']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>