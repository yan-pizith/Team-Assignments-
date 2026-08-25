<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';

$database = new Database();
$db = $database->getConnection();

$stmt = $db->prepare("SELECT * FROM suppliers ORDER BY created_at DESC");
$stmt->execute();
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-truck me-2"></i>អ្នកផ្គត់ផ្គង់</h4>
        <a href="add_supplier.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>បន្ថែមអ្នកផ្គត់ផ្គង់</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ក្រុមហ៊ុន/អ្នកផ្គត់ផ្គង់</th>
                            <th>អ្នកទំនាក់ទំនង</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>អាសយដ្ឋាន</th>
                            <th class="text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['company_name']); ?></td>
                            <td><?= htmlspecialchars($row['contact_name'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
                            <td class="text-center">
                                <a href="edit_supplier.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <a href="delete_supplier.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');"><i class="bi bi-trash"></i></a>
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