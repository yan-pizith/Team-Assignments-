<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
require_once '../../includes/navbar.php';
require_once '../../includes/sidebar.php';
?>
<div class="content-wrapper p-4">
    <h4>ព័ត៌មានឱសថស្ថាន</h4>
    <form class="card p-3 col-md-6 shadow-sm">
        <div class="mb-3"><label>ឈ្មោះហាង</label><input type="text" class="form-control" value="Pharmacy Store"></div>
        <div class="mb-3"><label>លេខទូរស័ព្ទ</label><input type="text" class="form-control" value="012 345 678"></div>
        <button class="btn btn-primary">រក្សាទុក</button>
    </form>
</div>
<?php require_once '../../includes/footer.php'; ?>