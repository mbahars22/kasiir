
<?php include 'views/layouts/header.php'; ?>
<?php include 'views/layouts/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title">Dashboard</h1>
                <p class="text-muted">Selamat datang, <?= htmlspecialchars($user['name']) ?>! Tahun Ajaran: <?= htmlspecialchars($academicYear) ?></p>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-details">
                            <h3 class="summary-value"><?= $summary['totalStudents'] ?></h3>
                            <p class="summary-label">Jumlah Siswa</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-icon bg-success">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="summary-details">
                            <h3 class="summary-value"><?= formatRupiah($summary['paymentsThisMonth']) ?></h3>
                            <p class="summary-label">Pembayaran Bulan Ini</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-icon bg-info">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="summary-details">
                            <h3 class="summary-value"><?= formatRupiah($summary['committeeBalance']) ?></h3>
                            <p class="summary-label">Saldo Dana Komite</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Payments -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pembayaran Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($summary['recentPayments'])): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada data pembayaran</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($summary['recentPayments'] as $payment): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($payment['nisn']) ?></td>
                                                <td><?= htmlspecialchars($payment['student_name']) ?></td>
                                                <td><?= htmlspecialchars($payment['payment_type']) ?></td>
                                                <td><?= formatRupiah($payment['amount']) ?></td>
                                                <td><?= formatDate($payment['date']) ?></td>
                                                <td>
                                                    <span class="badge <?= $payment['status'] === 'Lunas' ? 'bg-success' : ($payment['status'] === 'Dicicil' ? 'bg-warning' : 'bg-danger') ?>">
                                                        <?= htmlspecialchars($payment['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="?page=payments" class="btn btn-sm btn-outline-primary">Lihat Semua Pembayaran</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Akses Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <a href="?page=students&action=create" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-user-plus mb-2"></i><br>
                                    Tambah Siswa
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="?page=payments&action=create" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-money-check-alt mb-2"></i><br>
                                    Catat Pembayaran
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="?page=reports&action=payment" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-chart-bar mb-2"></i><br>
                                    Laporan Pembayaran
                                </a>
                            </div>
                            <div class="col-md-3 col-6">
                                <a href="?page=committee_funds&action=create" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-hand-holding-usd mb-2"></i><br>
                                    Dana Komite
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
