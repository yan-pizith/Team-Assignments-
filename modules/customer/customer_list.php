<?php
// modules/customer/customer_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// Process Add Customer
if (isset($_POST['btn_save_customer'])) {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email, address) VALUES (:name, :phone, :email, :addr)");
        $stmt->execute([
            'name'  => $name,
            'phone' => $phone,
            'email' => $email,
            'addr'  => $address
        ]);
        $_SESSION['success'] = "បន្ថែមអតិថិជនបានជោគជ័យ!";
        header("Location: customer_list.php");
        exit();
    }
}

$customers = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();
include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងអតិថិជន (Customer Management)</h3>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <i class="fa-solid fa-user-plus"></i> បន្ថែមអតិថិជន
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
                                <th>ឈ្មោះអតិថិជន</th>
                                <th>លេខទូរស័ព្ទ</th>
                                <th>អ៊ីមែល</th>
                                <th>អាសយដ្ឋាន</th>
                                <th class="text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($customers) > 0): ?>
                                <?php foreach ($customers as $cust): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($cust['name']); ?></td>
                                        <td><?= htmlspecialchars($cust['phone'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($cust['email'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($cust['address'] ?? '-'); ?></td>
                                        <td class="text-center">
                                            <a href="delete_customer.php?id=<?= $cust['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបអតិថិជននេះមែនទេ?');"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">មិនទាន់មានទិន្នន័យនៅឡើយទេ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Customer -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">បន្ថែមអតិថិជនថ្មី</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">ឈ្មោះអតិថិជន *</label>
                        <input type="text" name="name" class="form-control" required>
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
                    <button type="submit" name="btn_save_customer" class="btn btn-success">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>