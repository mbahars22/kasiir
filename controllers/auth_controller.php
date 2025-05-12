
<?php
class AuthController {
    public function login() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $academicYear = $_POST['academicYear'] ?? CURRENT_ACADEMIC_YEAR;
            
            if (empty($email) || empty($password)) {
                $error = 'Silakan isi email dan password.';
            } else {
                // Attempt to login
                $success = loginUser($email, $password, $academicYear);
                
                if ($success) {
                    // Redirect to dashboard
                    header('Location: ' . BASE_URL . '?page=dashboard');
                    exit;
                } else {
                    $error = 'Email atau password salah. Silakan coba lagi.';
                }
            }
        }
        
        // Get available academic years
        $academicYears = getAcademicYears();
        
        // Load view
        include 'views/auth/login.php';
    }
    
    public function logout() {
        logoutUser();
    }
}
?>
