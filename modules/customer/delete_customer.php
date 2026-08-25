<?php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM customers WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = "លុបអតិថិជនបានជោគជ័យ!";
}
header("Location: customer_list.php");
exit();
?>