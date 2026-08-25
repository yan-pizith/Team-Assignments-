<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-shield-lock me-2"></i>គ្រប់គ្រងអ្នកប្រើប្រាស់</h4>
        <a href="add_user.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>បន្ថែមអ្នកប្រើប្រាស់</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>#</th><th>Username</th><th>ឈ្មោះពេញ</th><th>តួនាទី</th><th class="text-center">សកម្មភាព</th></tr>
                </thead>
                <tbody>
                    <?php $i=1; while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                        <td><span class="badge bg-info text-dark"><?= $row['role']; ?></span></td>
                        <td class="text-center">
                            <a href="edit_user.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <a href="delete_user.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>