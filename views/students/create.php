
<?php include 'views/layouts/header.php'; ?>
<?php include 'views/layouts/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-md-6">
                <h1 class="page-title"><?= $pageTitle ?></h1>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="?page=students" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        <!-- Display Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Student Form -->
        <div class="card">
            <div class="card-body">
                <form action="?page=students&action=store" method="post" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nisn" name="nisn" required>
                                <div class="invalid-feedback">NISN harus diisi</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Nama harus diisi</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="batch" class="form-label">Angkatan <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <?php if (!empty($batches)): ?>
                                        <select class="form-select" id="batch" name="batch" required>
                                            <option value="">Pilih Angkatan</option>
                                            <?php foreach ($batches as $b): ?>
                                                <option value="<?= htmlspecialchars($b) ?>"><?= htmlspecialchars($b) ?></option>
                                            <?php endforeach; ?>
                                            <option value="new">+ Angkatan Baru</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" class="form-control" id="batch" name="batch" required 
                                               placeholder="Contoh: A24 (angkatan 2024)">
                                    <?php endif; ?>
                                    <div class="invalid-feedback">Angkatan harus diisi</div>
                                </div>
                            </div>
                            <div id="newBatchInput" class="mb-3 d-none">
                                <input type="text" class="form-control" id="newBatch" placeholder="Masukkan angkatan baru">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class_group" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <?php if (!empty($classes)): ?>
                                        <select class="form-select" id="class_group" name="class_group" required>
                                            <option value="">Pilih Kelas</option>
                                            <?php foreach ($classes as $c): ?>
                                                <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
                                            <?php endforeach; ?>
                                            <option value="new">+ Kelas Baru</option>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" class="form-control" id="class_group" name="class_group" required
                                               placeholder="Contoh: A, B, C">
                                    <?php endif; ?>
                                    <div class="invalid-feedback">Kelas harus diisi</div>
                                </div>
                            </div>
                            <div id="newClassInput" class="mb-3 d-none">
                                <input type="text" class="form-control" id="newClass" placeholder="Masukkan kelas baru">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number">
                    </div>
                    
                    <hr>
                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // New batch input handling
        const batchSelect = document.getElementById('batch');
        const newBatchDiv = document.getElementById('newBatchInput');
        const newBatchInput = document.getElementById('newBatch');
        
        if (batchSelect) {
            batchSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newBatchDiv.classList.remove('d-none');
                    newBatchInput.required = true;
                    
                    // When user types in the new batch input, update the hidden field
                    newBatchInput.addEventListener('input', function() {
                        batchSelect.value = this.value;
                    });
                } else {
                    newBatchDiv.classList.add('d-none');
                    newBatchInput.required = false;
                }
            });
        }
        
        // New class input handling
        const classSelect = document.getElementById('class_group');
        const newClassDiv = document.getElementById('newClassInput');
        const newClassInput = document.getElementById('newClass');
        
        if (classSelect) {
            classSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newClassDiv.classList.remove('d-none');
                    newClassInput.required = true;
                    
                    // When user types in the new class input, update the hidden field
                    newClassInput.addEventListener('input', function() {
                        classSelect.value = this.value;
                    });
                } else {
                    newClassDiv.classList.add('d-none');
                    newClassInput.required = false;
                }
            });
        }
        
        // Form validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
</script>

<?php include 'views/layouts/footer.php'; ?>
