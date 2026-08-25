<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT * FROM medicines WHERE stock_quantity <= 10");

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <h4 class="text-danger">របាយការណ៍ឱសថជិតអស់ពីស្តុក (Low Stock Alert)</h4>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>#</th><th>ឈ្មោះឱសថ</th><th>ស្តុកនៅសល់</th></tr></thead>
                <tbody>
                    <?php $i=1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td class="text-danger fw-bold"><?= $row['stock_quantity']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>