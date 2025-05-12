
-- Database schema untuk Sistem Keuangan Sekolah

-- Buat database
CREATE DATABASE IF NOT EXISTS keuangan_sekolah;
USE keuangan_sekolah;

-- Tabel Pengguna
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'committee', 'observer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Tahun Ajaran
CREATE TABLE academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year VARCHAR(10) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Siswa
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nisn VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    batch VARCHAR(10) NOT NULL,
    class_group VARCHAR(5) NOT NULL,
    address TEXT,
    phone_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_batch_class (batch, class_group)
);

-- Tabel Jenis Pembayaran
CREATE TABLE payment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    description TEXT,
    allow_installment BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Biaya Siswa (termasuk diskon)
CREATE TABLE student_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    payment_type_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    original_amount DECIMAL(12, 2) NOT NULL,
    discounted_amount DECIMAL(12, 2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_type_id) REFERENCES payment_types(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    UNIQUE KEY unique_student_fee (student_id, payment_type_id, academic_year_id)
);

-- Tabel Pembayaran
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    payment_type_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    date DATE NOT NULL,
    status ENUM('Lunas', 'Belum Lunas', 'Dicicil') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_type_id) REFERENCES payment_types(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    INDEX idx_student_payment (student_id, payment_type_id)
);

-- Tabel Dana Komite
CREATE TABLE committee_funds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(50) NOT NULL UNIQUE,
    date DATE NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    amount DECIMAL(12, 2) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    academic_year_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    INDEX idx_date_type (date, type)
);

-- Masukkan data awal
INSERT INTO users (username, password, name, role) VALUES 
('admin', '$2y$10$K9XLXZXLAs1UVQVuM.sCb.CHGyjULtpZd2aYI3KaHHAF0KVnZPVvO', 'Administrator', 'admin'), -- password: admin123
('komite', '$2y$10$jxXf9UPoQVUPLQKiQpfmDu3UdPgOVWrxILKkmQoRYuBCtMM4QF15.', 'Komite Sekolah', 'committee'), -- password: komite123
('pengamat', '$2y$10$QQtFR7/9hD3KJaZyNQrZuuv8MvZEYjFKxY1P4fkLbLJTFZjVA0jlC', 'Pengamat', 'observer'); -- password: pengamat123

INSERT INTO academic_years (year, is_active) VALUES 
('2022/2023', FALSE),
('2023/2024', TRUE);

INSERT INTO payment_types (name, amount, description, allow_installment) VALUES 
('SPP Bulanan', 150000, 'Pembayaran SPP bulanan', FALSE),
('Uang Gedung', 2500000, 'Pembayaran uang gedung', TRUE),
('Uang Seragam', 750000, 'Pembayaran seragam sekolah', TRUE);
