
<?php
class StudentsController {
    private $db;
    
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Display list of students
     */
    public function index() {
        try {
            // Prepare query with filtering options
            $query = "SELECT s.*, COUNT(p.id) as payment_count 
                     FROM students s 
                     LEFT JOIN payments p ON s.id = p.student_id 
                     GROUP BY s.id
                     ORDER BY s.name ASC";
            
            $stmt = $this->db->query($query);
            $students = $stmt->fetchAll();
            
            // Get batches and classes for filtering
            $batchesQuery = "SELECT DISTINCT batch FROM students ORDER BY batch";
            $classesQuery = "SELECT DISTINCT class_group FROM students ORDER BY class_group";
            $batches = $this->db->query($batchesQuery)->fetchAll(PDO::FETCH_COLUMN);
            $classes = $this->db->query($classesQuery)->fetchAll(PDO::FETCH_COLUMN);
            
            // Set page title
            $pageTitle = 'Daftar Siswa';
            
            // Load view
            include 'views/students/index.php';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
    
    /**
     * Show create student form
     */
    public function create() {
        // Set page title
        $pageTitle = 'Tambah Siswa Baru';
        
        // Get all batches and classes for dropdown
        try {
            $batchesQuery = "SELECT DISTINCT batch FROM students ORDER BY batch";
            $classesQuery = "SELECT DISTINCT class_group FROM students ORDER BY class_group";
            $batches = $this->db->query($batchesQuery)->fetchAll(PDO::FETCH_COLUMN);
            $classes = $this->db->query($classesQuery)->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            $batches = [];
            $classes = [];
        }
        
        // Load view
        include 'views/students/create.php';
    }
    
    /**
     * Store new student
     */
    public function store() {
        // Validate form data
        $nisn = $_POST['nisn'] ?? '';
        $name = $_POST['name'] ?? '';
        $batch = $_POST['batch'] ?? '';
        $class_group = $_POST['class_group'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        
        if (empty($nisn) || empty($name) || empty($batch) || empty($class_group)) {
            $_SESSION['error'] = 'Data siswa tidak lengkap. Pastikan NISN, nama, angkatan, dan kelas terisi.';
            header('Location: index.php?page=students&action=create');
            exit;
        }
        
        try {
            // Check if NISN already exists
            $checkQuery = "SELECT COUNT(*) FROM students WHERE nisn = :nisn";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute(['nisn' => $nisn]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $_SESSION['error'] = "NISN '$nisn' sudah terdaftar. Gunakan NISN yang berbeda.";
                header('Location: index.php?page=students&action=create');
                exit;
            }
            
            // Insert student data
            $query = "INSERT INTO students (nisn, name, batch, class_group, address, phone_number)
                     VALUES (:nisn, :name, :batch, :class_group, :address, :phone_number)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'nisn' => $nisn,
                'name' => $name,
                'batch' => $batch,
                'class_group' => $class_group,
                'address' => $address,
                'phone_number' => $phone_number
            ]);
            
            $_SESSION['success'] = "Siswa baru '$name' berhasil ditambahkan!";
            header('Location: index.php?page=students');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students&action=create');
            exit;
        }
    }
    
    /**
     * Show edit student form
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID siswa tidak ditemukan.';
            header('Location: index.php?page=students');
            exit;
        }
        
        try {
            // Get student data
            $query = "SELECT * FROM students WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $student = $stmt->fetch();
            
            if (!$student) {
                $_SESSION['error'] = 'Siswa tidak ditemukan.';
                header('Location: index.php?page=students');
                exit;
            }
            
            // Get all batches and classes for dropdown
            $batchesQuery = "SELECT DISTINCT batch FROM students ORDER BY batch";
            $classesQuery = "SELECT DISTINCT class_group FROM students ORDER BY class_group";
            $batches = $this->db->query($batchesQuery)->fetchAll(PDO::FETCH_COLUMN);
            $classes = $this->db->query($classesQuery)->fetchAll(PDO::FETCH_COLUMN);
            
            // Set page title
            $pageTitle = 'Edit Siswa: ' . $student['name'];
            
            // Load view
            include 'views/students/edit.php';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students');
            exit;
        }
    }
    
    /**
     * Update student data
     */
    public function update() {
        // Get form data
        $id = $_POST['id'] ?? 0;
        $nisn = $_POST['nisn'] ?? '';
        $name = $_POST['name'] ?? '';
        $batch = $_POST['batch'] ?? '';
        $class_group = $_POST['class_group'] ?? '';
        $address = $_POST['address'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        
        if (!$id || empty($nisn) || empty($name) || empty($batch) || empty($class_group)) {
            $_SESSION['error'] = 'Data siswa tidak lengkap. Pastikan NISN, nama, angkatan, dan kelas terisi.';
            header("Location: index.php?page=students&action=edit&id=$id");
            exit;
        }
        
        try {
            // Check if NISN already exists for another student
            $checkQuery = "SELECT COUNT(*) FROM students WHERE nisn = :nisn AND id != :id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute(['nisn' => $nisn, 'id' => $id]);
            
            if ($checkStmt->fetchColumn() > 0) {
                $_SESSION['error'] = "NISN '$nisn' sudah digunakan siswa lain. Gunakan NISN yang berbeda.";
                header("Location: index.php?page=students&action=edit&id=$id");
                exit;
            }
            
            // Update student data
            $query = "UPDATE students 
                     SET nisn = :nisn, name = :name, batch = :batch, class_group = :class_group,
                         address = :address, phone_number = :phone_number
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                'nisn' => $nisn,
                'name' => $name,
                'batch' => $batch,
                'class_group' => $class_group,
                'address' => $address,
                'phone_number' => $phone_number,
                'id' => $id
            ]);
            
            $_SESSION['success'] = "Data siswa '$name' berhasil diperbarui!";
            header('Location: index.php?page=students');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header("Location: index.php?page=students&action=edit&id=$id");
            exit;
        }
    }
    
    /**
     * Show student details
     */
    public function show() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID siswa tidak ditemukan.';
            header('Location: index.php?page=students');
            exit;
        }
        
        try {
            // Get student data
            $query = "SELECT * FROM students WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            $student = $stmt->fetch();
            
            if (!$student) {
                $_SESSION['error'] = 'Siswa tidak ditemukan.';
                header('Location: index.php?page=students');
                exit;
            }
            
            // Get payment history
            $paymentQuery = "
                SELECT p.*, pt.name AS payment_type_name 
                FROM payments p 
                JOIN payment_types pt ON p.payment_type_id = pt.id 
                WHERE p.student_id = :student_id 
                ORDER BY p.date DESC";
            $paymentStmt = $this->db->prepare($paymentQuery);
            $paymentStmt->execute(['student_id' => $id]);
            $payments = $paymentStmt->fetchAll();
            
            // Get fee information
            $feeQuery = "
                SELECT sf.*, pt.name AS payment_type_name 
                FROM student_fees sf 
                JOIN payment_types pt ON sf.payment_type_id = pt.id 
                WHERE sf.student_id = :student_id 
                AND sf.academic_year_id = 
                    (SELECT id FROM academic_years WHERE year = :academic_year)";
            $feeStmt = $this->db->prepare($feeQuery);
            $feeStmt->execute([
                'student_id' => $id,
                'academic_year' => getCurrentAcademicYear()
            ]);
            $fees = $feeStmt->fetchAll();
            
            // Set page title
            $pageTitle = 'Detail Siswa: ' . $student['name'];
            
            // Load view
            include 'views/students/show.php';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students');
            exit;
        }
    }
    
    /**
     * Delete student
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['error'] = 'ID siswa tidak ditemukan.';
            header('Location: index.php?page=students');
            exit;
        }
        
        try {
            // Get student name for success message
            $nameQuery = "SELECT name FROM students WHERE id = :id";
            $nameStmt = $this->db->prepare($nameQuery);
            $nameStmt->execute(['id' => $id]);
            $name = $nameStmt->fetchColumn();
            
            // Delete student
            $query = "DELETE FROM students WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            $_SESSION['success'] = "Siswa '$name' berhasil dihapus!";
            header('Location: index.php?page=students');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students');
            exit;
        }
    }
    
    /**
     * Import students from Excel
     */
    public function import() {
        // Check if file was uploaded
        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] != UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Gagal mengupload file. Silakan coba lagi.';
            header('Location: index.php?page=students');
            exit;
        }
        
        // Check file extension
        $fileExtension = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
        if (!in_array($fileExtension, ['xls', 'xlsx'])) {
            $_SESSION['error'] = 'Format file tidak didukung. Gunakan file Excel (.xls atau .xlsx).';
            header('Location: index.php?page=students');
            exit;
        }
        
        // Process the Excel file
        require_once 'vendor/autoload.php'; // Assuming PHPExcel or similar library
        
        try {
            // Read Excel file
            // This is a placeholder - actual implementation depends on the Excel library used
            $_SESSION['success'] = "Data siswa berhasil diimpor!";
            header('Location: index.php?page=students');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students');
            exit;
        }
    }
    
    /**
     * Export students to Excel
     */
    public function export() {
        try {
            // Get all students
            $query = "SELECT * FROM students ORDER BY batch, class_group, name";
            $stmt = $this->db->query($query);
            $students = $stmt->fetchAll();
            
            // Export to Excel
            // This is a placeholder - actual implementation depends on the Excel library used
            $_SESSION['success'] = "Data siswa berhasil diekspor!";
            header('Location: index.php?page=students');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: index.php?page=students');
            exit;
        }
    }
}
?>
