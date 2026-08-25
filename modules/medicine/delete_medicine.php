<?php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";
checkRole(['Admin']);
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM medicines WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}
header("Location: medicine_list.php");
exit();