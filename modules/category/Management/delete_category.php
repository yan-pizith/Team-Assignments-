<?php
// modules/category/delete_category.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = "លុបប្រភេទថ្នាំបានជោគជ័យ!";
}

header("Location: category_list.php");
exit();
?>