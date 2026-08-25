<?php
// modules/dashboard/dashboard.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// ទាញយកទិន្នន័យសរុបសម្រាប់បង្ហាញលើ Dashboard Card
$total_meds = $pdo->query("SELECT COUNT(*) FROM medicines")->fetchColumn();
$total_cats = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$low_stock  = $pdo->query("SELECT COUNT(*) FROM medicines WHERE quantity <= low_stock_threshold")->fetchColumn();
$today_sales = $pdo->query("SELECT COALESCE(SUM(grand_total), 0) FROM sales WHERE DATE(created_at) = CURDATE()")->fetchColumn();

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <h3 class="mb-4">Dashboard</h3>
            
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card bg-primary text-white p-3 shadow-sm">
                        <h5>ថ្នាំសរុប</h5>
                        <h2><?= $total_meds; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white p-3 shadow-sm">
                        <h5>ប្រភេទថ្នាំ</h5>
                        <h2><?= $total_cats; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark p-3 shadow-sm">
                        <h5>ថ្នាំជិតអស់ពីស្តុក</h5>
                        <h2><?= $low_stock; ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white p-3 shadow-sm">
                        <h5>ការលក់ថ្ងៃនេះ ($)</h5>
                        <h2>$<?= number_format($today_sales, 2); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>