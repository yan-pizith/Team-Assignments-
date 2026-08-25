<?php
// modules/users/login.php
session_start();
require_once "../../config/database.php";

if (isset($_POST['btn_login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ទាញយកព័ត៌មាន User ពី Database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND status = 'Active'");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // ផ្ទៀងផ្ទាត់ Hash Password
    if ($user && password_verify($password, $user['password'])) {
        // រក្សាទុក Session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Username ឬ លេខសម្ងាត់មិនត្រឹមត្រូវឡើយ!";
        header("Location: ../../index.php");
        exit();
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>