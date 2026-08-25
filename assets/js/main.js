// assets/js/main.js
document.addEventListener("DOMContentLoaded", function () {
    // បិទ/បើក Sidebar
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });
    }

    // បាត់ Alert អូតូក្រោយ ៣ វិនាទី
    setTimeout(function () {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
            alert.style.display = 'none';
        });
    }, 3000);
});