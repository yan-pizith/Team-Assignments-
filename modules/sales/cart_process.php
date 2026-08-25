<?php
// modules/sales/cart_process.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

if (isset($_POST['btn_checkout']) && !empty($_POST['items'])) {
    $customer_id = $_POST['customer_id'] ?: null;
    $user_id     = $_SESSION['user_id'];
    $discount    = floatval($_POST['discount']);
    $items       = $_POST['items'];

    try {
        $pdo->beginTransaction();

        // 1. គណនាសរុប
        $subtotal = 0;
        foreach ($items as $item) {
            $stmt = $pdo->prepare("SELECT selling_price FROM medicines WHERE id = :id");
            $stmt->execute(['id' => $item['id']]);
            $price = $stmt->fetchColumn();
            $subtotal += $price * $item['qty'];
        }
        $grand_total = max(0, $subtotal - $discount);
        $invoice_no = "INV-" . date("Ymd") . "-" . rand(1000, 9999);

        // 2. រក្សាទុក Sales Master Table
        $stmt = $pdo->prepare("INSERT INTO sales (invoice_no, customer_id, user_id, subtotal, discount, grand_total) 
                               VALUES (:inv, :cust, :usr, :sub, :disc, :grand)");
        $stmt->execute([
            'inv'   => $invoice_no,
            'cust'  => $customer_id,
            'usr'   => $user_id,
            'sub'   => $subtotal,
            'disc'  => $discount,
            'grand' => $grand_total
        ]);
        $sale_id = $pdo->lastInsertId();

        // 3. រក្សាទុក Detail & ដកស្តុកអូតូ
        foreach ($items as $item) {
            $stmt = $pdo->prepare("SELECT selling_price FROM medicines WHERE id = :id");
            $stmt->execute(['id' => $item['id']]);
            $price = $stmt->fetchColumn();
            $item_subtotal = $price * $item['qty'];

            // Save sale item
            $stmt_item = $pdo->prepare("INSERT INTO sale_items (sale_id, medicine_id, quantity, unit_price, subtotal) 
                                       VALUES (:sid, :mid, :qty, :price, :sub)");
            $stmt_item->execute([
                'sid'   => $sale_id,
                'mid'   => $item['id'],
                'qty'   => $item['qty'],
                'price' => $price,
                'sub'   => $item_subtotal
            ]);

            // **ដកស្តុកអូតូ**
            $stmt_stock = $pdo->prepare("UPDATE medicines SET quantity = quantity - :qty WHERE id = :mid");
            $stmt_stock->execute(['qty' => $item['qty'], 'mid' => $item['id']]);
        }

        $pdo->commit();
        header("Location: print_receipt.php?id=" . $sale_id);
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error Processing Transaction: " . $e->getMessage());
    }
} else {
    header("Location: pos.php");
    exit();
}
?>