<?php
// modules/purchases/purchase_list.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

// Add Purchase Logic
if (isset($_POST['btn_save_purchase'])) {
    $supplier_id  = $_POST['supplier_id'];
    $medicine_id  = $_POST['medicine_id'];
    $quantity     = intval($_POST['quantity']);
    $purchase_cost= floatval($_POST['purchase_cost']);

    try {
        $pdo->beginTransaction();

        // 1. បន្ថែម record ក្នុង Table purchases
        $stmt = $pdo->prepare("INSERT INTO purchases (supplier_id, medicine_id, quantity, purchase_cost) VALUES (:sup, :med, :qty, :cost)");
        $stmt->execute(['sup' => $supplier_id, 'med' => $medicine_id, 'qty' => $quantity, 'cost' => $purchase_cost]);

        // 2. បូកចំនួនចូលស្តុកថ្នាំ (Stock In)
        $stmt_stock = $pdo->prepare("UPDATE medicines SET quantity = quantity + :qty WHERE id = :med");
        $stmt_stock->execute(['qty' => $quantity, 'med' => $medicine_id]);

        $pdo->commit();
        $_SESSION['success'] = "នាំចូលស្តុកជោគជ័យ!";
        header("Location: purchase_list.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}

$purchases = $pdo->query("SELECT p.*, s.company_name, m.medicine_name 
                          FROM purchases p 
                          LEFT JOIN suppliers s ON p.supplier_id = s.id 
                          LEFT JOIN medicines m ON p.medicine_id = m.id 
                          ORDER BY p.id DESC")->fetchAll();

$suppliers = $pdo->query("SELECT * FROM suppliers")->fetchAll();
$medicines = $pdo->query("SELECT * FROM medicines")->fetchAll();

include "../../includes/header.php";
?>

<div class="d-flex">
    <?php include "../../includes/sidebar.php"; ?>
    <div class="w-100">
        <?php include "../../includes/navbar.php"; ?>
        
        <div class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>គ្រប់គ្រងការទិញចូល (Purchase / Stock In)</h3>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPurchaseModal"><i class="fa-solid fa-cart-plus"></i> ទិញថ្នាំចូលស្តុក</button>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success py-2"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>អ្នកផ្គត់ផ្គង់</th>
                                <th>ឈ្មោះថ្នាំ</th>
                                <th>ចំនួនទិញចូល</th>
                                <th>តម្លៃដើមទិញចូល ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($purchases) > 0): ?>
                                <?php foreach ($purchases as $p): ?>
                                    <tr>
                                        <td><?= $p['created_at']; ?></td>
                                        <td><?= htmlspecialchars($p['company_name']); ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($p['medicine_name']); ?></td>
                                        <td><span class="badge bg-success">+<?= $p['quantity']; ?></span></td>
                                        <td>$<?= number_format($p['purchase_cost'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted">មិនទាន់មានទិន្នន័យទិញចូលទេ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Purchase -->
<div class="modal fade" id="addPurchaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">ទិញថ្នាំចូលស្តុក (Purchase Stock)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">អ្នកផ្គត់ផ្គង់ *</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- ជ្រើសរើស --</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['company_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ថ្នាំ *</label>
                        <select name="medicine_id" class="form-select" required>
                            <option value="">-- ជ្រើសរើស --</option>
                            <?php foreach ($medicines as $m): ?>
                                <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['medicine_name']); ?> (ស្តុកបច្ចុប្បន្ន: <?= $m['quantity']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ចំនួនទិញបន្ថែម *</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">តម្លៃទិញសរុប ($) *</label>
                        <input type="number" step="0.01" name="purchase_cost" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" name="btn_save_purchase" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>