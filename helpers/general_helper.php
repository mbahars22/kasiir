
<?php
/**
 * Format number to Indonesian Rupiah format
 * @param float $number
 * @return string
 */
function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Format date to Indonesian format
 * @param string $date
 * @param bool $withTime
 * @return string
 */
function formatDate($date, $withTime = false) {
    $format = $withTime ? 'd F Y H:i' : 'd F Y';
    $timestamp = strtotime($date);
    
    // Set locale to Indonesian
    setlocale(LC_TIME, 'id_ID');
    
    // Format the date
    $formattedDate = strftime($format, $timestamp);
    
    return $formattedDate;
}

/**
 * Get all available academic years
 * @return array
 */
function getAcademicYears() {
    try {
        $db = getDbConnection();
        $query = "SELECT id, year, is_active FROM academic_years ORDER BY year DESC";
        $stmt = $db->query($query);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error fetching academic years: " . $e->getMessage());
        return [];
    }
}

/**
 * Get current academic year
 * @return string
 */
function getCurrentAcademicYear() {
    return isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : CURRENT_ACADEMIC_YEAR;
}

/**
 * Generate success alert HTML
 * @param string $message
 * @return string
 */
function successAlert($message) {
    return '<div class="alert alert-success mb-4">' . $message . '</div>';
}

/**
 * Generate error alert HTML
 * @param string $message
 * @return string
 */
function errorAlert($message) {
    return '<div class="alert alert-danger mb-4">' . $message . '</div>';
}

/**
 * Generate warning alert HTML
 * @param string $message
 * @return string
 */
function warningAlert($message) {
    return '<div class="alert alert-warning mb-4">' . $message . '</div>';
}

/**
 * Sanitize input
 * @param string $input
 * @return string
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>
