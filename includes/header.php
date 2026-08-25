<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ភ្ជាប់ទៅកាន់ database
require_once __DIR__ . "/../config/database.php";

// កំណត់ Base URL ដើម្បីការពារបញ្ហា Link ខុសផ្លូវ (Path Error)
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/pharmacy_system/";

// ទាញយកព័ត៌មាន Settings របស់ហាង
$store_name = 'ប្រព័ន្ធគ្រប់គ្រងឱសថស្ថាន';
try {
    $stmt_setting = $pdo->query("SELECT * FROM settings WHERE id = 1 LIMIT 1");
    $app_setting = $stmt_setting->fetch();
    if ($app_setting && !empty($app_setting['store_name'])) {
        $store_name = $app_setting['store_name'];
    }
} catch (PDOException $e) {
    // ករណីមិនទាន់មាន Table settings វានឹងប្រើឈ្មោះ Default
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($store_name); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom CSS (ភ្ជាប់ទៅកាន់ Folder assets/css/style.css) -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>
<body>
<div id="wrapper">