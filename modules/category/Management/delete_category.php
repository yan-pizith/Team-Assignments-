<?php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";
checkRole(['Admin']);
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}
header("Location: category_list.php");
exit();