<?php
// includes/navbar.php
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3 shadow-sm">
    <a class="navbar-brand fw-bold" href="#">Pharmacy System</a>
    <div class="ms-auto d-flex align-items-center text-white">
        <span class="me-3"><i class="fa-solid fa-user-circle"></i> <?= $_SESSION['full_name'] ?? 'User'; ?> (<b><?= $_SESSION['role'] ?? ''; ?></b>)</span>
        <a href="../../logout.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket"></i> ចាកចេញ</a>
    </div>
</nav>