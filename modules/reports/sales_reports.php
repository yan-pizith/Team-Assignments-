<?php
// modules/reports/sales_report.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date   = $_GET['end_date'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT s.*, u.full_name as cashier, c.name as customer_name 
                       FROM sales s 
                       LEFT JOIN users u ON s.user_id = u.id 
                       LEFT JOIN customers c ON s.customer_id = c.id 
                       WHERE DATE(s.created_at) BETWEEN :start AND :end 
                       ORDER BY s.id DESC");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$sales = $stmt->fetchAll();

$total_revenue = array_sum(array_column($sales, 'grand_total'));

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <h3 class="mb-3">របាយការណ៍លក់ (Sales Report)</h3>

            <!-- Form Filter តាមកាលបរិច្ឆេទ -->
            <form action="" method="GET" class="card card-body shadow-sm mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">ចាប់ពីថ្ងៃទី</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $start_date; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ដល់ថ្ងៃទី</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $end_date; ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> ទាញយករបាយការណ៍</button>
                    </div>
                </div>
            </form>

            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <h5 class="mb-0">ចំណូលសរុបក្នុងអំឡុងពេលនេះ៖</h5>
                <h3 class="mb-0 fw-bold text-success">$<?= number_format($total_revenue, 2); ?></h3>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>លេខវិក្កយបត្រ</th>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>អ្នកគិតលុយ</th>
                                <th>អតិថិជន</th>
                                <th>បញ្ចុះតម្លៃ</th>
                                <th>សរុបបង់</th>
                                <th class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($sales) > 0): ?>
                                <?php foreach ($sales as $s): ?>
                                    <tr>
                                        <td><code><?= $s['invoice_no']; ?></code></td>
                                        <td><?= $s['created_at']; ?></td>
                                        <td><?= htmlspecialchars($s['cashier']); ?></td>
                                        <td><?= htmlspecialchars($s['customer_name'] ?? 'General'); ?></td>
                                        <td>$<?= number_format($s['discount'], 2); ?></td>
                                        <td class="fw-bold text-success">$<?= number_format($s['grand_total'], 2); ?></td>
                                        <td class="text-center">
                                            <a href="../sales/print_receipt.php?id=<?= $s['id']; ?>" target="_blank" class="btn btn-sm btn-secondary"><i class="fa-solid fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted">មិនទាន់មានទិន្នន័យលក់ទេ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>