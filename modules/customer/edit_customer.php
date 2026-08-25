<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$id = $_GET['id'] ?? null;

if (!$id) { header("Location: customer_list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $stmt = $db->prepare("UPDATE customers SET name = :name, phone = :phone, address = :address WHERE id = :id");
    $stmt->execute([':name' => $name, ':phone' => $phone, ':address' => $address, ':id' => $id]);
    header("Location: customer_list.php?msg=updated");
    exit;
}

$stmt = $db->prepare("SELECT * FROM customers WHERE id = :id");
$stmt->execute([':id' => $id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">កែប្រែព័ត៌មានអតិថិជន</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះអតិថិជន</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">អាសយដ្ឋាន</label>
                    <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($customer['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">កែប្រែ</button>
                <a href="customer_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>