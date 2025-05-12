
<?php
// Application configuration
define('APP_NAME', 'Sistem Keuangan Madrasah');
define('APP_VERSION', '1.0.0');

// Error reporting (turn off in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Timezone setting
date_default_timezone_set('Asia/Jakarta');

// File upload settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'xlsx', 'xls', 'doc', 'docx']);

// Academic year settings
define('CURRENT_ACADEMIC_YEAR', '2024-2025');
?>
