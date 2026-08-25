<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT * FROM medicines ORDER BY stock_quantity ASC");
?>

<div class="content-wrapper p-4">
    <h4><i class="bi bi-box-seam me-2"></i>មើលស្តុកឱសថ (Stock View)</h4>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr><th>#</th><th>ឈ្មោះឱសថ</th><th>ចំនួនក្នុងស្តុក</th><th>ស្ថានភាព</th></tr>
                </thead>
                <tbody>
                    <?php $i=1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td class="fw-bold"><?= $row['stock_quantity']; ?></td>
                        <td>
                            <?php if ($row['stock_quantity'] <= 10): ?>
                                <span class="badge bg-danger">ស្តុកជិតអស់</span>
                            <?php else: ?>
                                <span class="badge bg-success">គ្រប់គ្រាន់</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>