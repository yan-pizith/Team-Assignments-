<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

$stmt = $db->prepare("SELECT DATE(created_at) as sale_date, COUNT(id) as total_orders, SUM(grand_total) as total_revenue FROM sales WHERE DATE(created_at) BETWEEN :start AND :end GROUP BY DATE(created_at) ORDER BY sale_date DESC");
$stmt->execute([':start' => $start_date, ':end' => $end_date]);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <h4 class="mb-3">របាយការណ៍ការលក់ (Sales Report)</h4>
    <form class="row g-3 mb-4 card p-3 shadow-sm bg-white" method="GET">
        <div class="col-md-4">
            <label class="form-label">ចាប់ពីថ្ងៃ</label>
            <input type="date" name="start_date" class="form-control" value="<?= $start_date; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">ដល់ថ្ងៃ</label>
            <input type="date" name="end_date" class="form-control" value="<?= $end_date; ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2"><i class="bi bi-filter"></i> ទាញយក</button>
            <button type="button" onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i></button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr><th>កាលបរិច្ឆេទ</th><th>ចំនួនវិក្កយបត្រ</th><th>ចំណូលសរុប ($)</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['sale_date']; ?></td>
                        <td><?= $row['total_orders']; ?></td>
                        <td class="fw-bold text-success">$<?= number_format($row['total_revenue'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>