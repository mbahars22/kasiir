
<!-- Sidebar -->
<div class="sidebar-wrapper">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo text-center mt-3 mb-3">
                <img src="assets/images/kemenag-logo.png" alt="Logo" height="60">
            </div>
        </div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="?page=dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            
            <!-- Master Data -->
            <li class="nav-item">
                <a class="nav-link sidebar-dropdown-toggle <?= in_array($page, ['students', 'payment_types', 'batch']) ? 'active' : '' ?>" href="#masterDataSubmenu">
                    <i class="fas fa-database me-2"></i> Data Master
                </a>
                <ul class="collapse <?= in_array($page, ['students', 'payment_types', 'batch']) ? 'show' : '' ?>" id="masterDataSubmenu">
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'students' ? 'active' : '' ?>" href="?page=students">
                            <i class="fas fa-user-graduate me-2"></i> Siswa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'payment_types' ? 'active' : '' ?>" href="?page=payment_types">
                            <i class="fas fa-money-check me-2"></i> Jenis Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'batch' ? 'active' : '' ?>" href="?page=batch">
                            <i class="fas fa-users me-2"></i> Angkatan & Kelas
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Financial -->
            <li class="nav-item">
                <a class="nav-link sidebar-dropdown-toggle <?= in_array($page, ['payments', 'student_fees', 'committee_funds']) ? 'active' : '' ?>" href="#financialSubmenu">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Keuangan
                </a>
                <ul class="collapse <?= in_array($page, ['payments', 'student_fees', 'committee_funds']) ? 'show' : '' ?>" id="financialSubmenu">
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'payments' ? 'active' : '' ?>" href="?page=payments">
                            <i class="fas fa-hand-holding-usd me-2"></i> Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'student_fees' ? 'active' : '' ?>" href="?page=student_fees">
                            <i class="fas fa-percentage me-2"></i> Biaya & Diskon
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'committee_funds' ? 'active' : '' ?>" href="?page=committee_funds">
                            <i class="fas fa-money-bill-wave me-2"></i> Dana Komite
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link <?= $page === 'reports' ? 'active' : '' ?>" href="?page=reports">
                    <i class="fas fa-chart-bar me-2"></i> Laporan
                </a>
            </li>
            
            <?php if (hasRole('admin')): ?>
            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link sidebar-dropdown-toggle <?= in_array($page, ['users', 'academic_years', 'school_profile']) ? 'active' : '' ?>" href="#settingsSubmenu">
                    <i class="fas fa-cogs me-2"></i> Pengaturan
                </a>
                <ul class="collapse <?= in_array($page, ['users', 'academic_years', 'school_profile']) ? 'show' : '' ?>" id="settingsSubmenu">
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'users' ? 'active' : '' ?>" href="?page=users">
                            <i class="fas fa-users-cog me-2"></i> Pengguna
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'academic_years' ? 'active' : '' ?>" href="?page=academic_years">
                            <i class="fas fa-calendar-alt me-2"></i> Tahun Ajaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page === 'school_profile' ? 'active' : '' ?>" href="?page=school_profile">
                            <i class="fas fa-school me-2"></i> Profil Madrasah
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
