<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$id = $_GET['id'] ?? null;

if (!$id) { header("Location: purchase_list.php"); exit; }

$query = "SELECT pi.*, m.name as medicine_name FROM purchase_items pi JOIN medicines m ON pi.medicine_id = m.id WHERE pi.purchase_id = :id";
$stmt = $db->prepare($query);
$stmt->execute([':id' => $id]);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">ព័ត៌មានលម្អិតការនាំចូល (#<?= $id; ?>)</h5>
            <a href="purchase_list.php" class="btn btn-light btn-sm">ត្រឡប់ក្រោយ</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ឈ្មោះឱសថ</th>
                        <th>ចំនួន</th>
                        <th>តម្លៃដើម ($)</th>
                        <th>សរុប ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= htmlspecialchars($row['medicine_name']); ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td>$<?= number_format($row['cost_price'], 2); ?></td>
                        <td>$<?= number_format($row['quantity'] * $row['cost_price'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>