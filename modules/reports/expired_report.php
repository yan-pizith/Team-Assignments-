<?php
// modules/reports/expired_report.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// ទាញយកថ្នាំជិតអស់ស្តុក (Quantity <= Threshold)
$low_stock = $pdo->query("SELECT * FROM medicines WHERE quantity <= low_stock_threshold ORDER BY quantity ASC")->fetchAll();

// ទាញយកថ្នាំជិតផុតកំណត់ (ក្នុងរយះពេល 60 ថ្ងៃ) ឬ ផុតកំណត់ហើយ
$expiring = $pdo->query("SELECT * FROM medicines WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 60 DAY) ORDER BY expiry_date ASC")->fetchAll();

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <h3 class="mb-4">របាយការណ៍ការដាស់តឿន (Stock & Expiry Alerts)</h3>

            <!-- 1. ស្តុកជិតអស់ -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-triangle-exclamation"></i> បញ្ជីថ្នាំជិតអស់ពីស្តុក (Low Stock Alert)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>កូដថ្នាំ</th>
                                <th>ឈ្មោះថ្នាំ</th>
                                <th>ចំនួននៅសល់</th>
                                <th>កម្រិតប្រកាសអាសន្ន</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($low_stock) > 0): ?>
                                <?php foreach ($low_stock as $m): ?>
                                    <tr>
                                        <td><code><?= $m['medicine_code']; ?></code></td>
                                        <td class="fw-bold"><?= htmlspecialchars($m['medicine_name']); ?></td>
                                        <td><span class="badge bg-danger fs-6"><?= $m['quantity']; ?></span></td>
                                        <td><?= $m['low_stock_threshold']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted">គ្មានថ្នាំជិតអស់ពីស្តុកទេ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. ថ្នាំជិតផុតកំណត់ -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fa-solid fa-clock"></i> បញ្ជីថ្នាំជិតផុតកំណត់ / ផុតកំណត់ (Expired Alert)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>កូដថ្នាំ</th>
                                <th>ឈ្មោះថ្នាំ</th>
                                <th>ចំនួនស្តុក</th>
                                <th>ថ្ងៃផុតកំណត់</th>
                                <th>ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($expiring) > 0): ?>
                                <?php foreach ($expiring as $e): 
                                    $is_expired = strtotime($e['expiry_date']) < strtotime(date('Y-m-d'));
                                ?>
                                    <tr>
                                        <td><code><?= $e['medicine_code']; ?></code></td>
                                        <td class="fw-bold"><?= htmlspecialchars($e['medicine_name']); ?></td>
                                        <td><?= $e['quantity']; ?></td>
                                        <td><?= $e['expiry_date']; ?></td>
                                        <td>
                                            <?php if ($is_expired): ?>
                                                <span class="badge bg-danger">ផុតកំណត់ហើយ</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">ជិតផុតកំណត់</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted">គ្មានថ្នាំជិតផុតកំណត់ទេ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>