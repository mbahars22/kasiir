
<?php
/**
 * Database setup script for Madrasah Financial System
 * Run this script to create the database schema and populate with initial data
 */

// Include configuration
require_once 'config/config.php';

// Database connection parameters
$host = 'localhost';
$dbname = 'keuangan_madrasah';
$user = 'root';
$pass = '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$host", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database `$dbname` created successfully or already exists.<br>";
    
    // Use the database
    $conn->exec("USE `$dbname`");
    
    // Create users table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `role` ENUM('admin', 'committee', 'observer') NOT NULL,
        `last_login` DATETIME DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `users` created successfully.<br>";
    
    // Create academic_years table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `academic_years` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `year` VARCHAR(10) NOT NULL UNIQUE,
        `is_active` BOOLEAN DEFAULT FALSE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `academic_years` created successfully.<br>";
    
    // Create students table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `students` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `nisn` VARCHAR(20) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `batch` VARCHAR(10) NOT NULL,
        `class_group` VARCHAR(5) NOT NULL,
        `address` TEXT,
        `phone_number` VARCHAR(20),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_batch_class (batch, class_group)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `students` created successfully.<br>";
    
    // Create payment_types table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `payment_types` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `amount` DECIMAL(12, 2) NOT NULL,
        `description` TEXT,
        `allow_installment` BOOLEAN DEFAULT FALSE,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `payment_types` created successfully.<br>";
    
    // Create student_fees table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `student_fees` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` INT NOT NULL,
        `payment_type_id` INT NOT NULL,
        `academic_year_id` INT NOT NULL,
        `original_amount` DECIMAL(12, 2) NOT NULL,
        `discounted_amount` DECIMAL(12, 2) NOT NULL,
        `notes` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (payment_type_id) REFERENCES payment_types(id) ON DELETE CASCADE,
        FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
        UNIQUE KEY unique_student_fee (student_id, payment_type_id, academic_year_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `student_fees` created successfully.<br>";
    
    // Create payments table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `payments` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` INT NOT NULL,
        `payment_type_id` INT NOT NULL,
        `academic_year_id` INT NOT NULL,
        `amount` DECIMAL(12, 2) NOT NULL,
        `date` DATE NOT NULL,
        `status` ENUM('Lunas', 'Belum Lunas', 'Dicicil') NOT NULL,
        `notes` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (payment_type_id) REFERENCES payment_types(id) ON DELETE CASCADE,
        FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
        INDEX idx_student_payment (student_id, payment_type_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `payments` created successfully.<br>";
    
    // Create committee_funds table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `committee_funds` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `reference` VARCHAR(50) NOT NULL UNIQUE,
        `date` DATE NOT NULL,
        `category` VARCHAR(100) NOT NULL,
        `description` TEXT,
        `amount` DECIMAL(12, 2) NOT NULL,
        `type` ENUM('income', 'expense') NOT NULL,
        `academic_year_id` INT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
        INDEX idx_date_type (date, type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `committee_funds` created successfully.<br>";
    
    // Create school_profile table
    $conn->exec("
    CREATE TABLE IF NOT EXISTS `school_profile` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `school_name` VARCHAR(100) NOT NULL,
        `address` TEXT,
        `phone` VARCHAR(20),
        `email` VARCHAR(100),
        `website` VARCHAR(100),
        `principal_name` VARCHAR(100),
        `logo` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "Table `school_profile` created successfully.<br>";
    
    // Insert default users
    // Default password: password123
    $defaultPassword = password_hash('password123', PASSWORD_DEFAULT);
    
    // Check if users already exist
    $stmt = $conn->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    if ($userCount == 0) {
        $conn->exec("
        INSERT INTO `users` (`username`, `password`, `name`, `role`) VALUES 
        ('admin@madrasah.com', '$defaultPassword', 'Administrator', 'admin'),
        ('committee@madrasah.com', '$defaultPassword', 'Komite Sekolah', 'committee'),
        ('observer@madrasah.com', '$defaultPassword', 'Pengamat', 'observer')
        ");
        echo "Default users created successfully.<br>";
    } else {
        echo "Users already exist. Skipping user creation.<br>";
    }
    
    // Insert academic years
    $stmt = $conn->query("SELECT COUNT(*) FROM academic_years");
    $yearCount = $stmt->fetchColumn();
    
    if ($yearCount == 0) {
        $conn->exec("
        INSERT INTO `academic_years` (`year`, `is_active`) VALUES 
        ('2022-2023', FALSE),
        ('2023-2024', FALSE),
        ('2024-2025', TRUE),
        ('2025-2026', FALSE)
        ");
        echo "Default academic years created successfully.<br>";
    } else {
        echo "Academic years already exist. Skipping creation.<br>";
    }
    
    // Insert payment types
    $stmt = $conn->query("SELECT COUNT(*) FROM payment_types");
    $paymentTypeCount = $stmt->fetchColumn();
    
    if ($paymentTypeCount == 0) {
        $conn->exec("
        INSERT INTO `payment_types` (`name`, `amount`, `description`, `allow_installment`) VALUES 
        ('SPP Bulanan', 150000, 'Pembayaran SPP bulanan', FALSE),
        ('Uang Gedung', 2500000, 'Pembayaran uang gedung', TRUE),
        ('Uang Seragam', 750000, 'Pembayaran seragam sekolah', TRUE)
        ");
        echo "Default payment types created successfully.<br>";
    } else {
        echo "Payment types already exist. Skipping creation.<br>";
    }
    
    // Insert school profile
    $stmt = $conn->query("SELECT COUNT(*) FROM school_profile");
    $profileCount = $stmt->fetchColumn();
    
    if ($profileCount == 0) {
        $conn->exec("
        INSERT INTO `school_profile` (`school_name`, `address`, `phone`, `email`, `website`, `principal_name`) VALUES 
        ('Madrasah Aliyah Negeri 1', 'Jl. Pendidikan No. 123, Jakarta Selatan', '021-12345678', 'info@madrasah1.sch.id', 'www.madrasah1.sch.id', 'Drs. H. Ahmad Fauzi, M.Pd')
        ");
        echo "Default school profile created successfully.<br>";
    } else {
        echo "School profile already exists. Skipping creation.<br>";
    }
    
    echo "<br>Database setup completed successfully! <a href='index.php'>Click here</a> to go to the application.";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
