<?php
// modules/category/category_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// ទាញយកបញ្ជី Category ទាំងអស់
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងប្រភេទថ្នាំ (Category Management)</h3>
                <a href="add_category.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> បន្ថែមប្រភេទថ្នាំ</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">ID</th>
                                <th>ឈ្មោះប្រភេទថ្នាំ</th>
                                <th>ការពិពណ៌នា (Description)</th>
                                <th width="15%" class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?= $cat['id']; ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($cat['category_name']); ?></td>
                                        <td><?= htmlspecialchars($cat['description'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <a href="edit_category.php?id=<?= $cat['id']; ?>" class="btn btn-sm btn-warning me-1"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="delete_category.php?id=<?= $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបប្រភេទថ្នាំនេះមែនទេ?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">មិនទាន់មានទិន្នន័យនៅឡើយទេ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>