<?php
// modules/users/user_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// មានតែ Admin ប៉ុណ្ណោះដែលអាចមើលទំព័រនេះបាន
checkRole(['Admin']);

// Process Add User
if (isset($_POST['btn_save_user'])) {
    $username  = trim($_POST['username']);
    $full_name = trim($_POST['full_name']);
    $role      = $_POST['role'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, role) VALUES (:user, :pass, :name, :role)");
    $stmt->execute(['user' => $username, 'pass' => $password, 'name' => $full_name, 'role' => $role]);
    
    $_SESSION['success'] = "បន្ថែម Account អ្នកប្រើប្រាស់បានជោគជ័យ!";
    header("Location: user_list.php");
    exit();
}

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងអ្នកប្រើប្រាស់ (User Management)</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fa-solid fa-user-plus"></i> បន្ថែម User ថ្មី</button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>ឈ្មោះពេញ</th>
                                <th>តួនាទី (Role)</th>
                                <th>ស្ថានភាព</th>
                                <th>ថ្ងៃបង្កើត</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($u['username']); ?></td>
                                    <td><?= htmlspecialchars($u['full_name']); ?></td>
                                    <td><span class="badge bg-info text-dark"><?= $u['role']; ?></span></td>
                                    <td><span class="badge bg-<?= $u['status'] === 'Active' ? 'success' : 'danger'; ?>"><?= $u['status']; ?></span></td>
                                    <td><?= $u['created_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">បន្ថែម User ថ្មី</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">ឈ្មោះពេញ *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">លេខសម្ងាត់ (Password) *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">តួនាទី (Role) *</label>
                        <select name="role" class="form-select" required>
                            <option value="Staff">Staff</option>
                            <option value="Pharmacist">Pharmacist</option>
                            <option value="Admin">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" name="btn_save_user" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>