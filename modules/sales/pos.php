<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$database = new Database();
$db = $database->getConnection();

$medicines = $db->query("SELECT * FROM medicines WHERE stock_quantity > 0")->fetchAll(PDO::FETCH_ASSOC);
$customers = $db->query("SELECT * FROM customers")->fetchAll(PDO::FETCH_ASSOC);

require_once '../../includes/header.php';
?>

<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="bi bi-cart-check me-2"></i>ប្រព័ន្ធលក់ POS</h4>
        <a href="../../dashboard.php" class="btn btn-secondary btn-sm">ត្រឡប់ទៅ Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm p-3">
                <input type="text" id="search" class="form-control mb-3" placeholder="ស្វែងរកឱសថ...">
                <div class="row g-2" style="max-height: 500px; overflow-y: auto;">
                    <?php foreach ($medicines as $m): ?>
                    <div class="col-md-4">
                        <div class="card h-100 p-2 text-center shadow-sm" style="cursor: pointer;" onclick="addToCart(<?= $m['id']; ?>, '<?= htmlspecialchars($m['name']); ?>', <?= $m['price']; ?>, <?= $m['stock_quantity']; ?>)">
                            <h6><?= htmlspecialchars($m['name']); ?></h6>
                            <p class="text-success fw-bold mb-1">$<?= number_format($m['price'], 2); ?></p>
                            <small class="text-muted">ស្តុក៖ <?= $m['stock_quantity']; ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm p-3">
                <h5>កន្ត្រកទំនិញ</h5>
                <select id="customer_id" class="form-select mb-3">
                    <option value="">-- អតិថិជនទូទៅ --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id']; ?>"><?= htmlspecialchars($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <table class="table table-sm">
                    <thead>
                        <tr><th>ឱសថ</th><th>ចំនួន</th><th>តម្លៃ</th><th></th></tr>
                    </thead>
                    <tbody id="cart-list"></tbody>
                </table>
                <hr>
                <h4 class="d-flex justify-content-between">
                    <span>សរុប៖</span>
                    <span id="grand-total" class="text-primary">$0.00</span>
                </h4>
                <button onclick="checkout()" class="btn btn-success w-100 mt-3 btn-lg">គិតលុយ (Checkout)</button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
function addToCart(id, name, price, stock) {
    let item = cart.find(i => i.id === id);
    if (item) {
        if (item.qty < stock) item.qty++;
        else alert('ស្តុកមិនគ្រប់គ្រាន់!');
    } else {
        cart.push({ id, name, price, qty: 1, stock });
    }
    renderCart();
}
function renderCart() {
    let html = '';
    let total = 0;
    cart.forEach((item, index) => {
        let sum = item.price * item.qty;
        total += sum;
        html += `<tr><td>${item.name}</td><td><input type="number" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${index}, this.value)" style="width: 50px;"></td><td>$${sum.toFixed(2)}</td><td><button onclick="removeItem(${index})" class="btn btn-danger btn-sm">X</button></td></tr>`;
    });
    document.getElementById('cart-list').innerHTML = html;
    document.getElementById('grand-total').innerText = '$' + total.toFixed(2);
}
function updateQty(index, qty) { cart[index].qty = parseInt(qty); renderCart(); }
function removeItem(index) { cart.splice(index, 1); renderCart(); }
function checkout() {
    if (cart.length === 0) return alert('កន្ត្រកទទេ!');
    let customer_id = document.getElementById('customer_id').value;
    let grand_total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

    fetch('cart_process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ customer_id, grand_total, cart })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.open('print_receipt.php?id=' + data.sale_id, '_blank');
            location.reload();
        } else {
            alert('មានបញ្ហា៖ ' + data.message);
        }
    });
}
</script>

<?php require_once '../../includes/footer.php'; ?>