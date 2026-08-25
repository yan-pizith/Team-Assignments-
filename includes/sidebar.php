<?php
// includes/sidebar.php
?>
<div class="bg-white border-end vh-100 p-3" style="width: 250px;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="../dashboard/dashboard.php" class="nav-link text-dark"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        </li>
        <li class="mb-2">
            <a href="../sales/pos.php" class="nav-link text-dark"><i class="fa-solid fa-cart-shopping me-2"></i> POS (លក់ថ្នាំ)</a>
        </li>
        <li class="mb-2">
            <a href="../medicine/medicine_list.php" class="nav-link text-dark"><i class="fa-solid fa-capsules me-2"></i> គ្រប់គ្រងថ្នាំ</a>
        </li>
        <li class="mb-2">
            <a href="../category/category_list.php" class="nav-link text-dark"><i class="fa-solid fa-list me-2"></i> ប្រភេទថ្នាំ</a>
        </li>
        <li class="mb-2">
            <a href="../supplier/supplier_list.php" class="nav-link text-dark"><i class="fa-solid fa-truck me-2"></i> អ្នកផ្គត់ផ្គង់</a>
        </li>
        <li class="mb-2">
            <a href="../customer/customer_list.php" class="nav-link text-dark"><i class="fa-solid fa-users me-2"></i> អតិថិជន</a>
        </li>
        <li class="mb-2">
            <a href="../inventory/stock_view.php" class="nav-link text-dark"><i class="fa-solid fa-warehouse me-2"></i> ស្តុកទំនិញ</a>
        </li>
        <li class="mb-2">
            <a href="../reports/sales_report.php" class="nav-link text-dark"><i class="fa-solid fa-chart-line me-2"></i> របាយការណ៍</a>
        </li>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
        <li class="mb-2">
            <a href="../users/user_list.php" class="nav-link text-dark"><i class="fa-solid fa-user-gear me-2"></i> គ្រប់គ្រង User</a>
        </li>
        <?php endif; ?>
    </ul>
</div>