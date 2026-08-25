<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT sh.*, m.name FROM stock_history sh JOIN medicines m ON sh.medicine_id = m.id ORDER BY sh.created_at DESC");
?>

<div class="content-wrapper p-4">
    <h4><i class="bi bi-clock-history me-2"></i>ប្រវត្តិចលនាស្តុក</h4>
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr><th>កាលបរិច្ឆេទ</th><th>ឱសថ</th><th>ប្រភេទ</th><th>ចំនួន</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['created_at']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><span class="badge bg-<?= $row['type'] == 'IN' ? 'success' : 'danger'; ?>"><?= $row['type']; ?></span></td>
                        <td><?= $row['quantity']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>