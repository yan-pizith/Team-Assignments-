<?php
// modules/category/edit_category.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: category_list.php"); exit(); }

// ទាញយកទិន្នន័យចាស់
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
$stmt->execute(['id' => $id]);
$category = $stmt->fetch();

if (!$category) { header("Location: category_list.php"); exit(); }

// Update Logic
if (isset($_POST['btn_update'])) {
    $category_name = trim($_POST['category_name']);
    $description   = trim($_POST['description']);

    $stmt = $pdo->prepare("UPDATE categories SET category_name = :name, description = :desc WHERE id = :id");
    $stmt->execute([
        'name' => $category_name,
        'desc' => $description,
        'id'   => $id
    ]);

    $_SESSION['success'] = "កែប្រែប្រភេទថ្នាំបានជោគជ័យ!";
    header("Location: category_list.php");
    exit();
}

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="card shadow-sm col-md-6 mx-auto">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">កែប្រែប្រភេទថ្នាំ</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">ឈ្មោះប្រភេទថ្នាំ <span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" value="<?= htmlspecialchars($category['category_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ការពិពណ៌នា</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($category['description']); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="category_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                            <button type="submit" name="btn_update" class="btn btn-warning">កែប្រែ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>