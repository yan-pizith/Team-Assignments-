<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT s.*, c.name as customer_name, u.username as seller_name FROM sales s LEFT JOIN customers c ON s.customer_id = c.id LEFT JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-receipt me-2"></i>ប្រវត្តិការលក់ (Sales List)</h4>
        <a href="pos.php" class="btn btn-success"><i class="bi bi-cart-plus me-1"></i>ទៅកាន់ POS</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>លេខវិក្កយបត្រ</th>
                            <th>កាលបរិច្ឆេទ</th>
                            <th>អតិថិជន</th>
                            <th>អ្នកលក់</th>
                            <th>សរុប ($)</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td class="fw-bold">#INV-<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?= date('d-M-Y H:i', strtotime($row['created_at'])); ?></td>
                            <td><?= htmlspecialchars($row['customer_name'] ?? 'General Customer'); ?></td>
                            <td><?= htmlspecialchars($row['seller_name']); ?></td>
                            <td class="fw-bold text-primary">$<?= number_format($row['grand_total'], 2); ?></td>
                            <td class="text-center">
                                <a href="print_receipt.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer"></i> បោះពុម្ព</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>