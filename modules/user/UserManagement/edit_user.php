<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET full_name = :full_name, role = :role, password = :password WHERE id = :id");
        $stmt->bindParam(':password', $password);
    } else {
        $stmt = $db->prepare("UPDATE users SET full_name = :full_name, role = :role WHERE id = :id");
    }

    $stmt->execute([':full_name' => $full_name, ':role' => $role, ':id' => $id]);
    header("Location: user_list.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card col-md-6 mx-auto shadow-sm">
        <div class="card-header bg-warning"><h5>កែប្រែអ្នកប្រើប្រាស់</h5></div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3"><label>Username</label><input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" disabled></div>
                <div class="mb-3"><label>ពាក្យសម្ងាត់ថ្មី (ទុកទទេបើមិនចង់ដូរ)</label><input type="password" name="password" class="form-control"></div>
                <div class="mb-3"><label>ឈ្មោះពេញ</label><input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']); ?>" required></div>
                <div class="mb-3">
                    <label>តួនាទី</label>
                    <select name="role" class="form-select">
                        <option value="Staff" <?= $user['role'] == 'Staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="Pharmacist" <?= $user['role'] == 'Pharmacist' ? 'selected' : ''; ?>>Pharmacist</option>
                        <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">កែប្រែ</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>