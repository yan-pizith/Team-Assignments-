<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT m.*, c.name as category_name FROM medicines m LEFT JOIN categories c ON m.category_id = c.id ORDER BY m.stock_quantity ASC");

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>របាយការណ៍ស្តុកឱសថ (Inventory Report)</h4>
        <button onclick="window.print()" class="btn btn-secondary"><i class="bi bi-printer"></i> បោះពុម្ព</button>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr><th>#</th><th>ឈ្មោះឱសថ</th><th>ប្រភេទ</th><th>ស្តុកនៅសល់</th><th>ស្ថានភាព</th></tr>
                </thead>
                <tbody>
                    <?php $i=1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['category_name'] ?? 'N/A'); ?></td>
                        <td class="fw-bold"><?= $row['stock_quantity']; ?></td>
                        <td>
                            <?php if ($row['stock_quantity'] <= 10): ?>
                                <span class="badge bg-danger">ជិតអស់</span>
                            <?php else: ?>
                                <span class="badge bg-success">ធម្មតា</span>
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