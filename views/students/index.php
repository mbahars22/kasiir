
<?php include 'views/layouts/header.php'; ?>
<?php include 'views/layouts/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1 class="page-title"><?= $pageTitle ?></h1>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="?page=students&action=create" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Tambah Siswa
                </a>
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
                <a href="?page=students&action=export" class="btn btn-outline-success">
                    <i class="fas fa-file-export"></i> Export Excel
                </a>
            </div>
        </div>
        
        <!-- Display Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="index.php" class="row g-3">
                    <input type="hidden" name="page" value="students">
                    
                    <div class="col-md-3">
                        <label for="filter_batch" class="form-label">Angkatan</label>
                        <select name="filter_batch" id="filter_batch" class="form-select">
                            <option value="">Semua Angkatan</option>
                            <?php foreach ($batches as $batch): ?>
                                <option value="<?= htmlspecialchars($batch) ?>"
                                    <?= (isset($_GET['filter_batch']) && $_GET['filter_batch'] == $batch) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($batch) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filter_class" class="form-label">Kelas</label>
                        <select name="filter_class" id="filter_class" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= htmlspecialchars($class) ?>"
                                    <?= (isset($_GET['filter_class']) && $_GET['filter_class'] == $class) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Siswa</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari nama atau NISN" 
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Students Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Angkatan</th>
                                <th>Kelas</th>
                                <th>No. Telepon</th>
                                <th>Total Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data siswa</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['nisn']) ?></td>
                                        <td><?= htmlspecialchars($student['name']) ?></td>
                                        <td><?= htmlspecialchars($student['batch']) ?></td>
                                        <td><?= htmlspecialchars($student['class_group']) ?></td>
                                        <td><?= htmlspecialchars($student['phone_number'] ?? '-') ?></td>
                                        <td><?= $student['payment_count'] ?> pembayaran</td>
                                        <td>
                                            <a href="?page=students&action=show&id=<?= $student['id'] ?>" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="?page=students&action=edit&id=<?= $student['id'] ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirmDelete(<?= $student['id'] ?>, '<?= htmlspecialchars($student['name']) ?>')" 
                                               class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="?page=students&action=import" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" required accept=".xls,.xlsx">
                        <div class="form-text">Format: .xls atau .xlsx</div>
                    </div>
                    <div class="mb-3">
                        <a href="?page=students&action=downloadTemplate" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    <div class="alert alert-info">
                        <strong>Petunjuk:</strong>
                        <ul class="mb-0">
                            <li>Gunakan template yang disediakan</li>
                            <li>Isi semua kolom yang wajib (NISN, Nama, Angkatan, Kelas)</li>
                            <li>Pastikan NISN tidak duplikat</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus siswa <strong id="deleteStudentName"></strong>?</p>
                <p class="text-danger">Perhatian: Semua data pembayaran siswa ini juga akan dihapus!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteStudentBtn" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteStudentName').textContent = name;
        document.getElementById('deleteStudentBtn').href = '?page=students&action=delete&id=' + id;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
    
    // Initialize DataTable
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            },
            order: [[1, 'asc']]
        });
    });
</script>

<?php include 'views/layouts/footer.php'; ?>
