<?php
// modules/supplier/supplier_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// Process Add Supplier
if (isset($_POST['btn_save_supplier'])) {
    $company_name   = trim($_POST['company_name']);
    $contact_person = trim($_POST['contact_person']);
    $phone          = trim($_POST['phone']);
    $email          = trim($_POST['email']);
    $address        = trim($_POST['address']);

    if (!empty($company_name)) {
        $stmt = $pdo->prepare("INSERT INTO suppliers (company_name, contact_person, phone, email, address) VALUES (:comp, :cont, :phone, :email, :addr)");
        $stmt->execute([
            'comp'  => $company_name,
            'cont'  => $contact_person,
            'phone' => $phone,
            'email' => $email,
            'addr'  => $address
        ]);
        $_SESSION['success'] = "បន្ថែមអ្នកផ្គត់ផ្គង់បានជោគជ័យ!";
        header("Location: supplier_list.php");
        exit();
    }
}

$suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY id DESC")->fetchAll();
include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងអ្នកផ្គត់ផ្គង់ (Supplier Management)</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fa-solid fa-plus"></i> បន្ថែមអ្នកផ្គត់ផ្គង់
                </button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ឈ្មោះក្រុមហ៊ុន</th>
                                <th>អ្នកទំនាក់ទំនង</th>
                                <th>លេខទូរស័ព្ទ</th>
                                <th>អ៊ីមែល</th>
                                <th>អាសយដ្ឋាន</th>
                                <th class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($suppliers) > 0): ?>
                                <?php foreach ($suppliers as $sup): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($sup['company_name']); ?></td>
                                        <td><?= htmlspecialchars($sup['contact_person'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($sup['phone'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($sup['email'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($sup['address'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <a href="delete_supplier.php?id=<?= $sup['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបអ្នកផ្គត់ផ្គង់នេះមែនទេ?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">មិនទាន់មានទិន្នន័យនៅឡើយទេ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Supplier -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">ឈ្មោះក្រុមហ៊ុន *</label>
                        <input type="text" name="company_name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">អ្នកទំនាក់ទំនង</label>
                        <input type="text" name="contact_person" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">អ៊ីមែល</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">អាសយដ្ឋាន</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" name="btn_save_supplier" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>