<?php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = "លុបអ្នកផ្គត់ផ្គង់បានជោគជ័យ!";
}
header("Location: supplier_list.php");
exit();
?>