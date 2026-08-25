<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT p.*, s.company_name FROM purchases p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.purchase_date DESC");

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <h4>របាយការណ៍ការទិញចូល (Purchase Report)</h4>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>កាលបរិច្ឆេទ</th><th>អ្នកផ្គត់ផ្គង់</th><th>ចំណាយសរុប ($)</th></tr></thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['purchase_date']; ?></td>
                        <td><?= htmlspecialchars($row['company_name'] ?? 'N/A'); ?></td>
                        <td class="fw-bold text-danger">$<?= number_format($row['total_amount'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>