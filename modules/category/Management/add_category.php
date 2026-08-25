<?php
// modules/category/add_category.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

if (isset($_POST['btn_save'])) {
    $category_name = trim($_POST['category_name']);
    $description   = trim($_POST['description']);

    if (!empty($category_name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (category_name, description) VALUES (:name, :desc)");
        $stmt->execute([
            'name' => $category_name,
            'desc' => $description
        ]);

        $_SESSION['success'] = "បន្ថែមប្រភេទថ្នាំបានជោគជ័យ!";
        header("Location: category_list.php");
        exit();
    }
}

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="card shadow-sm col-md-6 mx-auto">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">បន្ថែមប្រភេទថ្នាំថ្មី</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">ឈ្មោះប្រភេទថ្នាំ <span class="text-danger">*</span></label>
                            <input type="text" name="category_name" class="form-control" required placeholder="ឧ. Tablets, Syrup, Capsules">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ការពិពណ៌នា</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="ព័ត៌មានបន្ថែម..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="category_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                            <button type="submit" name="btn_save" class="btn btn-success">រក្សាទុក</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>