<?php
// modules/medicine/add_medicine.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// ទាញយក Category & Supplier សម្រាប់ជ្រើសរើសក្នុង Select Dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$suppliers  = $pdo->query("SELECT * FROM suppliers")->fetchAll();

if (isset($_POST['btn_save'])) {
    $code          = trim($_POST['medicine_code']);
    $name          = trim($_POST['medicine_name']);
    $category_id   = $_POST['category_id'] ?: null;
    $supplier_id   = $_POST['supplier_id'] ?: null;
    $unit_price    = $_POST['unit_price'];
    $selling_price = $_POST['selling_price'];
    $quantity      = $_POST['quantity'];
    $expiry_date   = $_POST['expiry_date'];
    $description   = trim($_POST['description']);
    
    // ការងារ Upload រូបភាព
    $image_name = "default.png";
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . "_" . uniqid() . "." . $ext;
        $target = "../../assets/uploads/medicine_images/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO medicines (medicine_code, medicine_name, category_id, supplier_id, unit_price, selling_price, quantity, expiry_date, image, description) 
            VALUES (:code, :name, :cat, :sup, :u_price, :s_price, :qty, :exp, :img, :desc)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'code'    => $code,
        'name'    => $name,
        'cat'     => $category_id,
        'sup'     => $supplier_id,
        'u_price' => $unit_price,
        's_price' => $selling_price,
        'qty'     => $quantity,
        'exp'     => $expiry_date,
        'img'     => $image_name,
        'desc'    => $description
    ]);

    $_SESSION['success'] = "បន្ថែមថ្នាំថ្មីបានជោគជ័យ!";
    header("Location: medicine_list.php");
    exit();
}

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="card shadow-sm col-md-10 mx-auto">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">បន្ថែមថ្នាំថ្មី</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">កូដថ្នាំ (Code/Barcode) *</label>
                                <input type="text" name="medicine_code" class="form-control" required placeholder="ឧ. MED-1001">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">ឈ្មោះថ្នាំ *</label>
                                <input type="text" name="medicine_name" class="form-control" required placeholder="ឧ. Paracetamol 500mg">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ប្រភេទថ្នាំ (Category)</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">អ្នកផ្គត់ផ្គង់ (Supplier)</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="">-- ជ្រើសរើសអ្នកផ្គត់ផ្គង់ --</option>
                                    <?php foreach ($suppliers as $sup): ?>
                                        <option value="<?= $sup['id']; ?>"><?= htmlspecialchars($sup['company_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">តម្លៃទិញចូល (Unit Price) *</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">តម្លៃលក់ចេញ (Selling Price) *</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ចំនួនស្តុក (Quantity) *</label>
                                <input type="number" name="quantity" class="form-control" required value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ថ្ងៃផុតកំណត់ (Expiry Date) *</label>
                                <input type="date" name="expiry_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">រូបភាពថ្នាំ</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">ការពិពណ៌នា</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="medicine_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                            <button type="submit" name="btn_save" class="btn btn-success">រក្សាទុក</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>