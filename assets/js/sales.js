let cart = [];

const searchInput = document.getElementById('search_input');
const searchResults = document.getElementById('search_results');

// AJAX Search
searchInput.addEventListener('input', function () {
    let q = this.value.trim();
    if (q.length > 0) {
        fetch(`../../api/search_api.php?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                data.forEach(item => {
                    let a = document.createElement('a');
                    a.className = 'list-group-item list-group-item-action d-flex justify-content-between';
                    a.innerHTML = `<span><b>${item.medicine_name}</b> (${item.medicine_code})</span> 
                                   <span class="badge bg-primary">$${item.selling_price} (ស្តុក: ${item.quantity})</span>`;
                    a.onclick = () => addToCart(item);
                    searchResults.appendChild(a);
                });
            });
    } else {
        searchResults.innerHTML = '';
    }
});

function addToCart(item) {
    searchResults.innerHTML = '';
    searchInput.value = '';

    let existing = cart.find(x => x.id === item.id);
    if (existing) {
        if (existing.qty < item.quantity) {
            existing.qty++;
        } else {
            alert('ចំនួនស្តុកមិនគ្រប់គ្រាន់ឡើយ!');
        }
    } else {
        cart.push({ id: item.id, name: item.medicine_name, price: parseFloat(item.selling_price), qty: 1, maxQty: item.quantity });
    }
    renderCart();
}

function renderCart() {
    let tbody = document.getElementById('cart_body');
    tbody.innerHTML = '';
    let subtotal = 0;

    cart.forEach((item, index) => {
        let total = item.price * item.qty;
        subtotal += total;

        tbody.innerHTML += `
            <tr>
                <td>${item.name} <input type="hidden" name="items[${index}][id]" value="${item.id}"></td>
                <td>$${item.price.toFixed(2)}</td>
                <td><input type="number" name="items[${index}][qty]" value="${item.qty}" min="1" max="${item.maxQty}" onchange="updateQty(${index}, this.value)" class="form-control form-control-sm"></td>
                <td>$${total.toFixed(2)}</td>
                <td><button type="button" onclick="removeItem(${index})" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-xmark"></i></button></td>
            </tr>
        `;
    });

    let discount = parseFloat(document.getElementById('discount_input').value) || 0;
    let grandTotal = Math.max(0, subtotal - discount);

    document.getElementById('subtotal_val').innerText = `$${subtotal.toFixed(2)}`;
    document.getElementById('grand_total_val').innerText = `$${grandTotal.toFixed(2)}`;
}

function updateQty(index, qty) {
    cart[index].qty = parseInt(qty) || 1;
    renderCart();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

document.getElementById('discount_input').addEventListener('input', renderCart);