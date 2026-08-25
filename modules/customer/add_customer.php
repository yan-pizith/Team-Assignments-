<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if (!empty($name) && !empty($phone)) {
        $stmt = $db->prepare("INSERT INTO customers (name, phone, address) VALUES (:name, :phone, :address)");
        $stmt->execute([':name' => $name, ':phone' => $phone, ':address' => $address]);
        header("Location: customer_list.php?msg=added");
        exit;
    }
}
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">បន្ថែមអតិថិជនថ្មី</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះអតិថិជន <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">លេខទូរស័ព្ទ <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">អាសយដ្ឋាន</label>
                    <textarea name="address" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">រក្សាទុក</button>
                <a href="customer_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>