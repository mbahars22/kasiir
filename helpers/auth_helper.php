
<?php
/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user details
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $db = getDbConnection();
        $query = "SELECT id, username, name, role FROM users WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching current user: " . $e->getMessage());
        return null;
    }
}

/**
 * Check if user has specific role
 * @param string|array $roles
 * @return bool
 */
function hasRole($roles) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    if (is_array($roles)) {
        return in_array($user['role'], $roles);
    } else {
        return $user['role'] === $roles;
    }
}

/**
 * Log user in
 * @param string $email
 * @param string $password
 * @param string $academic_year
 * @return bool
 */
function loginUser($email, $password, $academic_year) {
    try {
        $db = getDbConnection();
        $query = "SELECT id, username, password, name, role FROM users WHERE username = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['academic_year'] = $academic_year;
            
            // Update last login time
            $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = :id";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute(['id' => $user['id']]);
            
            return true;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

/**
 * Log user out
 */
function logoutUser() {
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: ' . BASE_URL . '?page=auth&action=login');
    exit;
}
?>
