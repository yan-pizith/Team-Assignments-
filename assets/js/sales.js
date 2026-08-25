// assets/js/sales.js
let cart = [];

document.getElementById('search_medicine').addEventListener('input', function () {
    let q = this.value.trim();
    let resultDiv = document.getElementById('search_result');
    if (q.length < 1) { resultDiv.innerHTML = ''; return; }

    fetch(`../../api/medicine_api.php?q=${q}`)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.length > 0) {
                let html = '';
                res.data.forEach(item => {
                    html += `<a href="#" class="list-group-item list-group-item-action" onclick="addToCart(${item.id}, '${item.medicine_name}', ${item.selling_price}, ${item.quantity})">
                                <b>${item.medicine_name}</b> - $${item.selling_price} (ស្តុក: ${item.quantity})
                             </a>`;
                });
                resultDiv.innerHTML = html;
            } else {
                resultDiv.innerHTML = '<div class="list-group-item text-muted">រកមិនឃើញទំនិញទេ</div>';
            }
        });
});

function addToCart(id, name, price, stock) {
    document.getElementById('search_result').innerHTML = '';
    document.getElementById('search_medicine').value = '';

    let exist = cart.find(i => i.id === id);
    if (exist) {
        if (exist.qty < stock) {
            exist.qty++;
        } else {
            alert('ចំនួនទិញលើសពីចំនួនស្តុកដែលមាន!');
        }
    } else {
        cart.push({ id, name, price, qty: 1, stock });
    }
    renderCart();
}

function renderCart() {
    let tbody = document.querySelector('#cart_table tbody');
    tbody.innerHTML = '';
    let subtotal = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.qty;
        subtotal += itemTotal;
        tbody.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${index}, this.value)">
                </td>
                <td>$${itemTotal.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${index})"><i class="fa-solid fa-times"></i></button>
                </td>
            </tr>
        `;
    });

    let discount = parseFloat(document.getElementById('txt_discount').value) || 0;
    let grandTotal = Math.max(0, subtotal - discount);
    let paid = parseFloat(document.getElementById('txt_paid').value) || 0;
    let due = Math.max(0, paid - grandTotal);

    document.getElementById('txt_subtotal').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('txt_grand_total').innerText = `$${grandTotal.toFixed(2)}`;
    document.getElementById('txt_grand_total_khr').innerText = `${(grandTotal * EXCHANGE_RATE).toLocaleString()} ៛`;
    document.getElementById('txt_due').innerText = `$${due.toFixed(2)}`;
}

function updateQty(index, qty) {
    let val = parseInt(qty);
    if (val > cart[index].stock) {
        alert('ចំនួនទិញលើសពីចំនួនស្តុក!');
        cart[index].qty = cart[index].stock;
    } else {
        cart[index].qty = val > 0 ? val : 1;
    }
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

document.getElementById('txt_discount').addEventListener('input', renderCart);
document.getElementById('txt_paid').addEventListener('input', renderCart);

document.getElementById('btn_checkout').addEventListener('click', function () {
    if (cart.length === 0) { alert('សូមជ្រើសរើសទំនិញយ៉ាងហោចណាស់មួយ!'); return; }

    let payload = {
        customer_id: document.getElementById('customer_id').value,
        items: cart,
        total_amount: parseFloat(document.getElementById('txt_subtotal').innerText.replace('$', '')),
        discount: parseFloat(document.getElementById('txt_discount').value) || 0,
        grand_total: parseFloat(document.getElementById('txt_grand_total').innerText.replace('$', '')),
        paid_amount: parseFloat(document.getElementById('txt_paid').value) || 0,
        due_amount: parseFloat(document.getElementById('txt_due').innerText.replace('$', ''))
    };

    fetch('../../modules/sales/cart_process.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                window.open(`../../modules/sales/print_receipt.php?id=${res.sale_id}`, '_blank');
                cart = [];
                renderCart();
            } else {
                alert(res.message || 'មានបញ្ហាក្នុងការរក្សាទុក!');
            }
        });
});