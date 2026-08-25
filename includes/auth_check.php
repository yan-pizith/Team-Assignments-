<?php
// includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ពិនិត្យថាបាន Login ហើយឬនៅ?
if (!isset($_SESSION['user_id'])) {
    header("Location: /pharmacy_system/index.php");
    exit();
}

// Function សម្រាប់ពិនិត្យ Role (Admin, Pharmacist, Staff)
function checkRole($allowed_roles = []) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        echo "<div style='color:red; text-align:center; padding:20px;'>
                <h2>Access Denied!</h2>
                <p>អ្នកគ្មានសិទ្ធិចូលប្រើប្រាស់ទំព័រនេះទេ។</p>
              </div>";
        exit();
    }
}
?>