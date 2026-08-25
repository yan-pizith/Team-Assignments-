<?php
// modules/category/category_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// 1. បន្ថែម Category
if (isset($_POST['btn_add'])) {
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (:name, :desc)");
        $stmt->execute(['name' => $name, 'desc' => $desc]);
        $_SESSION['success'] = "បន្ថែមប្រភេទថ្នាំបានជោគជ័យ!";
        header("Location: category_list.php"); exit();
    }
}

// 2. កែប្រែ Category
if (isset($_POST['btn_edit'])) {
    $id   = $_POST['cat_id'];
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['description']);
    if (!empty($name)) {
        $stmt = $pdo->prepare("UPDATE categories SET category_name = :name, description = :desc WHERE id = :id");
        $stmt->execute(['name' => $name, 'desc' => $desc, 'id' => $id]);
        $_SESSION['success'] = "កែប្រែប្រភេទថ្នាំបានជោគជ័យ!";
        header("Location: category_list.php"); exit();
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងប្រភេទថ្នាំ (Categories)</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa-solid fa-plus"></i> បន្ថែមប្រភេទថ្នាំ</button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="10%">#</th>
                                <th>ឈ្មោះប្រភេទថ្នាំ</th>
                                <th>ការពិពណ៌នា</th>
                                <th width="15%" class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($categories) > 0): ?>
                                <?php foreach ($categories as $index => $c): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($c['category_name']); ?></td>
                                        <td><?= htmlspecialchars($c['description']); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <?php if ($_SESSION['role'] === 'Admin'): ?>
                                                <a href="delete_category.php?id=<?= $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកប្រាកដថាចង់លុបប្រភេទថ្នាំនេះទេ?');"><i class="fa-solid fa-trash"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Category -->
                                    <div class="modal fade" id="editModal<?= $c['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="" method="POST">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title">កែប្រែប្រភេទថ្នាំ</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cat_id" value="<?= $c['id']; ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">ឈ្មោះប្រភេទថ្នាំ *</label>
                                                            <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($c['category_name']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">ការពិពណ៌នា</label>
                                                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($c['description']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                                                        <button type="submit" name="btn_edit" class="btn btn-warning">កែប្រែ</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted">មិនទាន់មានទិន្នន័យឡើយ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">បន្ថែមប្រភេទថ្នាំថ្មី</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ឈ្មោះប្រភេទថ្នាំ *</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ការពិពណ៌នា</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" name="btn_add" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>