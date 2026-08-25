<?php
// modules/medicine/edit_medicine.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: medicine_list.php"); exit(); }

$stmt = $pdo->prepare("SELECT * FROM medicines WHERE id = :id");
$stmt->execute(['id' => $id]);
$med = $stmt->fetch();
if (!$med) { header("Location: medicine_list.php"); exit(); }

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$suppliers  = $pdo->query("SELECT * FROM suppliers")->fetchAll();

if (isset($_POST['btn_update'])) {
    $code          = trim($_POST['medicine_code']);
    $name          = trim($_POST['medicine_name']);
    $category_id   = $_POST['category_id'] ?: null;
    $supplier_id   = $_POST['supplier_id'] ?: null;
    $unit_price    = $_POST['unit_price'];
    $selling_price = $_POST['selling_price'];
    $quantity      = $_POST['quantity'];
    $expiry_date   = $_POST['expiry_date'];
    $description   = trim($_POST['description']);
    $image_name    = $med['image'];

    // បើមាន Upload រូបភាពថ្មី
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image = time() . "_" . uniqid() . "." . $ext;
        $target = "../../assets/uploads/medicine_images/" . $new_image;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // លុបរូបចាស់បើមិនមែនជា default.png
            if ($image_name !== 'default.png') {
                @unlink("../../assets/uploads/medicine_images/" . $image_name);
            }
            $image_name = $new_image;
        }
    }

    $sql = "UPDATE medicines SET 
            medicine_code = :code, medicine_name = :name, category_id = :cat, 
            supplier_id = :sup, unit_price = :u_price, selling_price = :s_price, 
            quantity = :qty, expiry_date = :exp, image = :img, description = :desc 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'code'    => $code, 'name' => $name, 'cat' => $category_id, 'sup' => $supplier_id,
        'u_price' => $unit_price, 's_price' => $selling_price, 'qty' => $quantity,
        'exp'     => $expiry_date, 'img' => $image_name, 'desc' => $description, 'id' => $id
    ]);

    $_SESSION['success'] = "កែប្រែទិន្នន័យថ្នាំបានជោគជ័យ!";
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
                <div class="card-header bg-warning text-dark"><h5 class="mb-0">កែប្រែព័ត៌មានថ្នាំ</h5></div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">កូដថ្នាំ *</label>
                                <input type="text" name="medicine_code" class="form-control" value="<?= htmlspecialchars($med['medicine_code']); ?>" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">ឈ្មោះថ្នាំ *</label>
                                <input type="text" name="medicine_name" class="form-control" value="<?= htmlspecialchars($med['medicine_name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ប្រភេទថ្នាំ</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $med['category_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($cat['category_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">អ្នកផ្គត់ផ្គង់</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="">-- ជ្រើសរើសអ្នកផ្គត់ផ្គង់ --</option>
                                    <?php foreach ($suppliers as $sup): ?>
                                        <option value="<?= $sup['id']; ?>" <?= $sup['id'] == $med['supplier_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($sup['company_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">តម្លៃទិញចូល ($) *</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" value="<?= $med['unit_price']; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">តម្លៃលក់ចេញ ($) *</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" value="<?= $med['selling_price']; ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ចំនួនស្តុក *</label>
                                <input type="number" name="quantity" class="form-control" value="<?= $med['quantity']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ថ្ងៃផុតកំណត់ *</label>
                                <input type="date" name="expiry_date" class="form-control" value="<?= $med['expiry_date']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">រូបភាពបច្ចុប្បន្ន</label>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="../../assets/uploads/medicine_images/<?= $med['image']; ?>" width="40" height="40" class="rounded border">
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">ការពិពណ៌នា</label>
                                <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($med['description']); ?></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="medicine_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                            <button type="submit" name="btn_update" class="btn btn-warning">កែប្រែ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>