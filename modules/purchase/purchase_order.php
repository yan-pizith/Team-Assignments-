<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $medicine_id = $_POST['medicine_id'];
    $quantity = (int)$_POST['quantity'];
    $cost_price = (float)$_POST['cost_price'];
    $total_amount = $quantity * $cost_price;

    $db->beginTransaction();
    try {
        $stmt = $db->prepare("INSERT INTO purchases (supplier_id, total_amount) VALUES (:supplier_id, :total)");
        $stmt->execute([':supplier_id' => $supplier_id, ':total' => $total_amount]);
        $purchase_id = $db->lastInsertId();

        $stmt_detail = $db->prepare("INSERT INTO purchase_items (purchase_id, medicine_id, quantity, cost_price) VALUES (:purchase_id, :medicine_id, :quantity, :cost_price)");
        $stmt_detail->execute([':purchase_id' => $purchase_id, ':medicine_id' => $medicine_id, ':quantity' => $quantity, ':cost_price' => $cost_price]);

        $stmt_stock = $db->prepare("UPDATE medicines SET stock_quantity = stock_quantity + :qty WHERE id = :id");
        $stmt_stock->execute([':qty' => $quantity, ':id' => $medicine_id]);

        $db->commit();
        header("Location: purchase_list.php?msg=success");
        exit;
    } catch (Exception $e) {
        $db->rollBack();
        $error = "មានបញ្ហា៖ " . $e->getMessage();
    }
}

$suppliers = $db->query("SELECT * FROM suppliers")->fetchAll(PDO::FETCH_ASSOC);
$medicines = $db->query("SELECT * FROM medicines")->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>

<div class="content-wrapper p-4">
    <div class="card shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">ទិញថ្នាំចូលស្តុក (Purchase Order)</h5>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">អ្នកផ្គត់ផ្គង់</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['company_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">ជ្រើសរើសឱសថ</label>
                    <select name="medicine_id" class="form-select" required>
                        <option value="">-- ជ្រើសរើស --</option>
                        <?php foreach ($medicines as $m): ?>
                            <option value="<?= $m['id']; ?>"><?= htmlspecialchars($m['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ចំនួនទិញ</label>
                        <input type="number" name="quantity" min="1" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">តម្លៃដើម/ឯកតា ($)</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">នាំចូល</button>
                <a href="purchase_list.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>