<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'Staff';
?>
<style>
    #sidebar {
        min-width: 250px;
        max-width: 250px;
        background: #1e293b;
        color: #fff;
        transition: all 0.3s;
        min-height: 100vh;
    }
    #sidebar .sidebar-header {
        padding: 20px;
        background: #0f172a;
        text-align: center;
        font-weight: bold;
    }
    #sidebar ul.components {
        padding: 10px 0;
    }
    #sidebar ul li a {
        padding: 12px 20px;
        font-size: 0.95rem;
        display: block;
        color: #cbd5e1;
        text-decoration: none;
        transition: 0.2s;
    }
    #sidebar ul li a:hover, #sidebar ul li.active > a {
        color: #fff;
        background: #334155;
        border-left: 4px solid #3b82f6;
    }
    #sidebar ul li a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
</style>

<nav id="sidebar">
    <div class="sidebar-header">
        <h5 class="m-0"><i class="fa-solid fa-clinic-medical text-primary me-2"></i>ឱសថស្ថាន</h5>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="../dashboard.php"><i class="fa-solid fa-gauge"></i> ផ្ទាំងគ្រប់គ្រង (Dashboard)</a>
        </li>

        <li class="<?php echo ($current_page == 'pos.php') ? 'active' : ''; ?>">
            <a href="../pos.php" class="text-warning font-weight-bold"><i class="fa-solid fa-cart-shopping"></i> កន្លែងលក់ (POS)</a>
        </li>

        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/medicine') !== false) ? 'active' : ''; ?>">
            <a href="../modules/medicine/index.php"><i class="fa-solid fa-pills"></i> គ្រប់គ្រងឱសថ/ថ្នាំ</a>
        </li>

        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/category') !== false) ? 'active' : ''; ?>">
            <a href="../modules/category/index.php"><i class="fa-solid fa-tags"></i> ប្រភេទឱសថ</a>
        </li>

        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/sales') !== false) ? 'active' : ''; ?>">
            <a href="../modules/sales/index.php"><i class="fa-solid fa-file-invoice-dollar"></i> ប្រវត្តិលក់ (Sales)</a>
        </li>

        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/customer') !== false) ? 'active' : ''; ?>">
            <a href="../modules/customer/index.php"><i class="fa-solid fa-users"></i> អតិថិជន</a>
        </li>

        <?php if ($role === 'Admin' || $role === 'Pharmacist'): ?>
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/supplier') !== false) ? 'active' : ''; ?>">
            <a href="../modules/supplier/index.php"><i class="fa-solid fa-truck-field"></i> អ្នកផ្គត់ផ្គង់</a>
        </li>
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/purchase') !== false) ? 'active' : ''; ?>">
            <a href="../modules/purchase/index.php"><i class="fa-solid fa-boxes-packing"></i> ការទិញចូល (Stock)</a>
        </li>
        <?php endif; ?>

        <?php if ($role === 'Admin'): ?>
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/reports') !== false) ? 'active' : ''; ?>">
            <a href="../modules/reports/index.php"><i class="fa-solid fa-chart-line"></i> របាយការណ៍</a>
        </li>
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/users') !== false) ? 'active' : ''; ?>">
            <a href="../modules/users/index.php"><i class="fa-solid fa-user-gear"></i> អ្នកប្រើប្រាស់</a>
        </li>
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], 'modules/settings') !== false) ? 'active' : ''; ?>">
            <a href="../modules/settings/index.php"><i class="fa-solid fa-sliders"></i> ការកំណត់ (Settings)</a>
        </li>
        <?php endif; ?>

        <li>
            <a href="../logout.php" onclick="return confirm('តើអ្នកពិតជាចង់ចាកចេញមែនទេ?');" class="text-danger">
                <i class="fa-solid fa-right-from-bracket"></i> ចាកចេញ
            </a>
        </li>
    </ul>
</nav>