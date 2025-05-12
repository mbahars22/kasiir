
        </div> <!-- End of .wrapper -->
        
        <footer class="footer mt-auto py-3 bg-light">
            <div class="container text-center">
                <span class="text-muted">© <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?></span>
            </div>
        </footer>
        
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
        <script>
            // Sidebar toggle
            document.addEventListener('DOMContentLoaded', function() {
                const menuToggle = document.getElementById('menu-toggle');
                const wrapper = document.querySelector('.wrapper');
                
                if (menuToggle) {
                    menuToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        wrapper.classList.toggle('toggled');
                    });
                }
                
                // Initialize dropdown menus
                const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
                dropdownToggles.forEach(function(toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        const target = document.querySelector(href);
                        if (target) {
                            target.classList.toggle('show');
                        }
                    });
                });
                
                // Initialize Select2
                if ($.fn.select2) {
                    $('.select2').select2({
                        theme: 'bootstrap-5'
                    });
                }
                
                // Initialize DataTables
                if ($.fn.DataTable) {
                    $('.datatable').DataTable({
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                        }
                    });
                }
            });
        </script>
        
        <!-- Page specific scripts -->
        <?php if (isset($pageScripts)): ?>
            <?= $pageScripts ?>
        <?php endif; ?>
    </body>
</html>
