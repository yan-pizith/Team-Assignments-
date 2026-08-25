<?php
// includes/navbar.php
$user_fullname = $_SESSION['full_name'] ?? 'អ្នកប្រើប្រាស់';
$user_role     = $_SESSION['role'] ?? 'Staff';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-3 py-2">
    <div class="container-fluid p-0">
        <!-- Toggle Button for Sidebar -->
        <button type="button" id="sidebarCollapse" class="btn btn-light me-3">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Global Quick Search -->
        <form class="d-none d-md-flex ms-auto me-3 position-relative" style="width: 280px;">
            <input class="form-control form-control-sm rounded-pill pe-4" type="search" id="globalSearchInput" placeholder="ស្វែងរកក្នុងប្រព័ន្ធ..." aria-label="Search">
            <i class="fa-solid fa-magnifying-glass position-absolute end-0 top-50 translate-middle-y me-3 text-muted"></i>
            <div id="globalSearchResult" class="dropdown-menu w-100 shadow mt-1 position-absolute" style="display:none; top: 100%; left: 0; z-index: 1000;"></div>
        </form>

        <!-- Right Menu -->
        <ul class="navbar-nav ms-auto ms-md-0 align-items-center">
            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-regular fa-bell fa-lg"></i>
                    <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="width: 280px;">
                    <li><h6 class="dropdown-header">ការជូនដំណឹង (Notifications)</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="px-3 py-1 small text-muted" id="lowStockAlert"><i class="fa-solid fa-box text-warning me-2"></i>ថ្នាំជិតអស់ស្តុក: <b id="lowStockCount">0</b></li>
                    <li class="px-3 py-1 small text-muted" id="expiredAlert"><i class="fa-solid fa-calendar-xmark text-danger me-2"></i>ថ្នាំជិតផុតកំណត់: <b id="expiredCount">0</b></li>
                </ul>
            </li>

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-user small"></i>
                    </div>
                    <span class="d-none d-md-inline font-weight-bold text-dark"><?php echo htmlspecialchars($user_fullname); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li><span class="dropdown-item-text text-muted small">តួនាទី: <b><?php echo htmlspecialchars($user_role); ?></b></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php" onclick="return confirm('តើអ្នកពិតជាចង់ចាកចេញមែនទេ?');"><i class="fa-solid fa-right-from-bracket text-danger me-2"></i>ចាកចេញ</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
// Auto Fetch Notifications Count from API
document.addEventListener('DOMContentLoaded', function() {
    fetch('../api/notification_api.php')
        .then(res => res.json())
        .then(data => {
            if(data.success && data.total_alerts > 0) {
                const badge = document.getElementById('notifBadge');
                badge.innerText = data.total_alerts;
                badge.style.display = 'inline-block';

                document.getElementById('lowStockCount').innerText = data.low_stock_count;
                document.getElementById('expiredCount').innerText = data.expired_count;
            }
        }).catch(err => console.log('Notif Error:', err));
});
</script>