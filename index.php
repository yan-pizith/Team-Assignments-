<?php
// index.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: modules/dashboard/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pharmacy System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-primary d-flex align-items-center justify-content-center vh-100">

<div class="card shadow-lg p-4 style-card" style="width: 400px; border-radius: 12px;">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">Pharmacy System</h3>
        <p class="text-muted">សូមបញ្ចូលគណនីដើមី្បចូលប្រើប្រាស់</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger py-2"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="modules/users/login.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required placeholder="បញ្ចូល Username">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="បញ្ចូល លេខសម្ងាត់">
        </div>
        <button type="submit" name="btn_login" class="btn btn-primary w-100 mt-2">ចូលប្រព័ន្ធ (Login)</button>
    </form>
</div>

</body>
</html>