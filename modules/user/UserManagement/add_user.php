<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $full_name = trim($_POST['full_name']);
    $role = $_POST['role'];

    $stmt = $db->prepare("INSERT INTO users (username, password, full_name, role) VALUES (:username, :password, :full_name, :role)");
    $stmt->execute([':username' => $username, ':password' => $password, ':full_name' => $full_name, ':role' => $role]);
    header("Location: user_list.php");
    exit;
}

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card col-md-6 mx-auto shadow-sm">
        <div class="card-header bg-primary text-white"><h5>បន្ថែមអ្នកប្រើប្រាស់</h5></div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                <div class="mb-3"><label>ពាក្យសម្ងាត់ (Password)</label><input type="password" name="password" class="form-control" required></div>
                <div class="mb-3"><label>ឈ្មោះពេញ</label><input type="text" name="full_name" class="form-control" required></div>
                <div class="mb-3">
                    <label>តួនាទី</label>
                    <select name="role" class="form-select">
                        <option value="Staff">Staff</option>
                        <option value="Pharmacist">Pharmacist</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">រក្សាទុក</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>