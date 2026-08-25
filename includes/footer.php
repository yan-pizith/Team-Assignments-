<?php
// includes/footer.php
?>
        </div> <!-- End main-content -->

        <footer class="bg-white border-top text-center py-3 text-muted small mt-auto">
            <div class="container-fluid">
                <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($store_name ?? 'Pharmacy Management System'); ?>. រក្សាសិទ្ធិគ្រប់យ៉ាង។</span>
            </div>
        </footer>
    </div> <!-- End content-wrapper -->
</div> <!-- End wrapper -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
<!-- jQuery ( optional for easy DOM / AJAX ) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    // Sidebar Toggle Functionality
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
            if ($('#sidebar').css('margin-left') === '0px') {
                $('#sidebar').css('margin-left', '-250px');
            } else {
                $('#sidebar').css('margin-left', '0px');
            }
        });
    });
</script>

</body>
</html>