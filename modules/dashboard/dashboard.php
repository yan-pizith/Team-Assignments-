<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();

$total_meds = $db->query("SELECT COUNT(*) FROM medicines")->fetchColumn();
$total_sales = $db->query("SELECT SUM(grand_total) FROM sales")->fetchColumn() ?? 0;
$total_cust = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$low_stock = $db->query("SELECT COUNT(*) FROM medicines WHERE stock_quantity <= 10")->fetchColumn();

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <h3 class="mb-4">ផ្ទាំងព័ត៌មានទូទៅ (Dashboard)</h3>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card bg-primary text-white p-3 shadow-sm">
                <h5>ឱសថសរុប</h5>
                <h3><?= $total_meds; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white p-3 shadow-sm">
                <h5>ចំណូលសរុប</h5>
                <h3>$<?= number_format($total_sales, 2); ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white p-3 shadow-sm">
                <h5>អតិថិជនសរុប</h5>
                <h3><?= $total_cust; ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white p-3 shadow-sm">
                <h5>ស្តុកជិតអស់</h5>
                <h3><?= $low_stock; ?></h3>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>