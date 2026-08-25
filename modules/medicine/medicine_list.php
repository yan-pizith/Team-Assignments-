<?php
// modules/medicine/medicine_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// ទាញយកទិន្នន័យថ្នាំ ដោយ Join ជាមួយ Categories ដើមី្បយកឈ្មោះប្រភេទថ្នាំ
$query = "SELECT m.*, c.category_name 
          FROM medicines m 
          LEFT JOIN categories c ON m.category_id = c.id 
          ORDER BY m.id DESC";
$medicines = $pdo->query($query)->fetchAll();

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងបញ្ជីថ្នាំ (Medicine Management)</h3>
                <a href="add_medicine.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> បន្ថែមថ្នាំថ្មី</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>រូបភាព</th>
                                    <th>កូដថ្នាំ</th>
                                    <th>ឈ្មោះថ្នាំ</th>
                                    <th>ប្រភេទ</th>
                                    <th>តម្លៃដើម</th>
                                    <th>តម្លៃលក់</th>
                                    <th>ចំនួនស្តុក</th>
                                    <th>ថ្ងៃផុតកំណត់</th>
                                    <th class="text-center">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($medicines) > 0): ?>
                                    <?php foreach ($medicines as $med): ?>
                                        <tr>
                                            <td class="text-center">
                                                <img src="../../assets/uploads/medicine_images/<?= $med['image']; ?>" width="45" height="45" class="rounded border" style="object-fit:cover;">
                                            </td>
                                            <td><code><?= htmlspecialchars($med['medicine_code']); ?></code></td>
                                            <td class="fw-bold"><?= htmlspecialchars($med['medicine_name']); ?></td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($med['category_name'] ?? 'N/A'); ?></span></td>
                                            <td>$<?= number_format($med['unit_price'], 2); ?></td>
                                            <td class="text-success fw-bold">$<?= number_format($med['selling_price'], 2); ?></td>
                                            <td>
                                                <?php if ($med['quantity'] <= $med['low_stock_threshold']): ?>
                                                    <span class="badge bg-danger"><?= $med['quantity']; ?> (ជិតអស់)</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><?= $med['quantity']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $med['expiry_date']; ?></td>
                                            <td class="text-center">
                                                <a href="edit_medicine.php?id=<?= $med['id']; ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                                <a href="delete_medicine.php?id=<?= $med['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកពិតជាចង់លុបថ្នាំនេះមែនទេ?');"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">មិនទាន់មានទិន្នន័យថ្នាំនៅឡើយទេ</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>