<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';

$id = $_GET['id'] ?? null;
if ($id && $id != $_SESSION['user_id']) {
    $database = new Database();
    $db = $database->getConnection();
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
}
header("Location: user_list.php");
exit;