<?php
// includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ពិនិត្យមើលថាតើអ្នកប្រើប្រាស់បាន Login រួចហើយឬនៅ
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Function សម្រាប់ពិនិត្យមើល Role
function checkRole($allowed_roles = []) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: ../dashboard.php?error=access_denied");
        exit();
    }
}
?>