
-- Data contoh untuk Sistem Keuangan Sekolah
USE keuangan_sekolah;

-- Siswa contoh
INSERT INTO students (nisn, name, batch, class_group, address, phone_number) VALUES 
('1001', 'Ahmad Farhan', 'A24', 'A', 'Jl. Mawar No. 10, Jakarta', '081234567890'),
('1002', 'Siti Nurhaliza', 'A23', 'B', 'Jl. Melati No. 15, Jakarta', '081234567891'),
('1003', 'Budi Santoso', 'A24', 'A', 'Jl. Anggrek No. 5, Jakarta', '081234567892'),
('1004', 'Rina Fatimah', 'A22', 'C', 'Jl. Dahlia No. 8, Jakarta', '081234567893'),
('1005', 'Dian Puspita', 'A23', 'A', 'Jl. Kenanga No. 12, Jakarta', '081234567894'),
('1006', 'Rizki Ramadhan', 'A24', 'B', 'Jl. Cempaka No. 7, Jakarta', '081234567895'),
('1007', 'Anisa Putri', 'A23', 'C', 'Jl. Lotus No. 9, Jakarta', '081234567896'),
('1008', 'Farhan Rizki', 'A24', 'A', 'Jl. Flamboyan No. 22, Jakarta', '081234567897'),
('1009', 'Maya Sari', 'A22', 'A', 'Jl. Bougenville No. 11, Jakarta', '081234567898'),
('1010', 'Aldi Wijaya', 'A23', 'B', 'Jl. Teratai No. 14, Jakarta', '081234567899');

-- Ambil ID tahun ajaran aktif
SET @active_year_id = (SELECT id FROM academic_years WHERE is_active = TRUE LIMIT 1);

-- Biaya siswa (dengan beberapa diskon)
INSERT INTO student_fees (student_id, payment_type_id, academic_year_id, original_amount, discounted_amount, notes) VALUES 
(1, 1, @active_year_id, 150000, 150000, ''),
(1, 2, @active_year_id, 2500000, 2000000, 'Diskon prestasi'),
(1, 3, @active_year_id, 750000, 750000, ''),
(2, 1, @active_year_id, 150000, 150000, ''),
(2, 2, @active_year_id, 2500000, 2500000, ''),
(2, 3, @active_year_id, 750000, 750000, ''),
(3, 1, @active_year_id, 150000, 150000, ''),
(3, 2, @active_year_id, 2500000, 2250000, 'Diskon keluarga tidak mampu'),
(3, 3, @active_year_id, 750000, 750000, ''),
(4, 1, @active_year_id, 150000, 120000, 'Diskon anak guru'),
(5, 1, @active_year_id, 150000, 150000, '');

-- Pembayaran contoh
INSERT INTO payments (student_id, payment_type_id, academic_year_id, amount, date, status, notes) VALUES 
(1, 1, @active_year_id, 150000, '2024-05-01', 'Lunas', ''),
(2, 2, @active_year_id, 1000000, '2024-04-15', 'Dicicil', 'Cicilan pertama'),
(3, 1, @active_year_id, 150000, '2024-05-05', 'Lunas', ''),
(4, 3, @active_year_id, 750000, '2024-03-20', 'Lunas', ''),
(5, 1, @active_year_id, 150000, '2024-05-02', 'Lunas', ''),
(1, 3, @active_year_id, 750000, '2024-05-10', 'Lunas', ''),
(2, 2, @active_year_id, 500000, '2024-05-15', 'Dicicil', 'Cicilan kedua'),
(6, 1, @active_year_id, 150000, '2024-05-03', 'Lunas', ''),
(7, 1, @active_year_id, 150000, '2024-05-04', 'Lunas', ''),
(8, 2, @active_year_id, 2500000, '2024-04-10', 'Lunas', '');

-- Dana komite contoh
INSERT INTO committee_funds (reference, date, category, description, amount, type, academic_year_id) VALUES 
('KM-2024-05-001', '2024-05-01', 'Iuran Komite', 'Penerimaan iuran komite', 5000000, 'income', @active_year_id),
('KM-2024-05-002', '2024-05-05', 'Peralatan Sekolah', 'Pembelian peralatan laboratorium', 1500000, 'expense', @active_year_id),
('KM-2024-05-003', '2024-05-10', 'Sumbangan', 'Sumbangan dari alumni', 2000000, 'income', @active_year_id),
('KM-2024-05-004', '2024-05-15', 'Kegiatan Siswa', 'Pendanaan kegiatan OSIS', 3000000, 'expense', @active_year_id),
('KM-2024-05-005', '2024-05-20', 'Pemeliharaan', 'Perbaikan fasilitas mushola', 1250000, 'expense', @active_year_id),
('KM-2024-05-006', '2024-05-25', 'Beasiswa', 'Bantuan beasiswa siswa tidak mampu', 2000000, 'expense', @active_year_id);
