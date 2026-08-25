<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$id = $_GET['id'] ?? null;

if (!$id) { header("Location: supplier_list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company_name = trim($_POST['company_name']);
    $contact_name = trim($_POST['contact_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $stmt = $db->prepare("UPDATE suppliers SET company_name = :company_name, contact_name = :contact_name, phone = :phone, address = :address WHERE id = :id");
    $stmt->execute([':company_name' => $company_name, ':contact_name' => $contact_name, ':phone' => $phone, ':address' => $address, ':id' => $id]);
    header("Location: supplier_list.php?msg=updated");
    exit;
}

$stmt = $db->prepare("SELECT * FROM suppliers WHERE id = :id");
$stmt->execute([':id' => $id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">កែប្រែអ្នកផ្គត់ផ្គង់</h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះក្រុមហ៊ុន/អ្នកផ្គត់ផ្គង់</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($supplier['company_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ឈ្មោះអ្នកទំនាក់ទំនង</label>
                    <input type="text" name="contact_name" class="form-control" value="<?= htmlspecialchars($supplier['contact_name']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">លេខទូរស័ព្ទ</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($supplier['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">អាសយដ្ឋាន</label>
                    <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($supplier['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">កែប្រែ</button>
                <a href="supplier_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>