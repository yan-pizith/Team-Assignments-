<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $database = new Database();
    $db = $database->getConnection();
    $stmt = $db->prepare("DELETE FROM suppliers WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
header("Location: supplier_list.php?msg=deleted");
exit;