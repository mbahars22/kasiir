
<?php
// Start session
session_start();

// Base URL configuration
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/madrasah-finance');

// Application root path
define('ROOT_PATH', __DIR__);

// Include configuration files
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'helpers/auth_helper.php';
require_once 'helpers/general_helper.php';

// Simple router
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Check if user is logged in
if (!isLoggedIn() && $page != 'auth') {
    header('Location: ' . BASE_URL . '?page=auth&action=login');
    exit;
}

// Include controller based on page
$controller_file = 'controllers/' . $page . '_controller.php';
if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller_name = ucfirst($page) . 'Controller';
    $controller = new $controller_name();
    
    // Call the appropriate method
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        // If action doesn't exist, show 404 page
        include 'views/errors/404.php';
    }
} else {
    // If controller doesn't exist, show 404 page
    include 'views/errors/404.php';
}
?>
