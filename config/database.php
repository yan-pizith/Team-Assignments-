<?php
// config/database.php

$host     = 'localhost';
$db_name  = 'pharmacy_db';
$username = 'root';
$password = ''; // ប្រសិនបើប្រើ XAMPP លើ Windows ជាទូទៅ Password ទំនេរ
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("ការភ្ជាប់ទៅ Database បរាជ័យ៖ " . $e->getMessage());
}
?>