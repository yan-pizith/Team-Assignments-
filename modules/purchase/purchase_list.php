<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT p.*, s.company_name FROM purchases p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.purchase_date DESC";
$stmt = $db->prepare($query);
$stmt->execute();
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-box-arrow-in-down me-2"></i>ប្រវត្តិការទិញចូលស្តុក</h4>
        <a href="purchase_order.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>នាំចូលស្តុកថ្មី</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>កាលបរិច្ឆេទ</th>
                            <th>អ្នកផ្គត់ផ្គង់</th>
                            <th>សរុប ($)</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d-M-Y H:i', strtotime($row['purchase_date'])); ?></td>
                            <td><?= htmlspecialchars($row['company_name'] ?? 'N/A'); ?></td>
                            <td class="fw-bold text-success">$<?= number_format($row['total_amount'], 2); ?></td>
                            <td class="text-center">
                                <a href="purchase_receipt.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i> លម្អិត</a>
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