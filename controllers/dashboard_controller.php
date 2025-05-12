
<?php
class DashboardController {
    public function index() {
        // Get current user
        $user = getCurrentUser();
        
        // Get current academic year
        $academicYear = getCurrentAcademicYear();
        
        // Get summary data
        $summary = $this->getDashboardSummary();
        
        // Include view
        include 'views/dashboard/index.php';
    }
    
    private function getDashboardSummary() {
        try {
            $db = getDbConnection();
            $academicYear = getCurrentAcademicYear();
            $academicYearId = $this->getAcademicYearId($academicYear);
            
            // Get total students
            $studentsQuery = "SELECT COUNT(*) as count FROM students";
            $studentsStmt = $db->query($studentsQuery);
            $totalStudents = $studentsStmt->fetch()['count'];
            
            // Get total payments this month
            $month = date('m');
            $year = date('Y');
            $paymentsQuery = "SELECT SUM(amount) as total FROM payments WHERE MONTH(date) = :month AND YEAR(date) = :year AND academic_year_id = :academic_year_id";
            $paymentsStmt = $db->prepare($paymentsQuery);
            $paymentsStmt->execute(['month' => $month, 'year' => $year, 'academic_year_id' => $academicYearId]);
            $paymentsThisMonth = $paymentsStmt->fetch()['total'] ?? 0;
            
            // Get total committee funds balance
            $incomeQuery = "SELECT SUM(amount) as total FROM committee_funds WHERE type = 'income' AND academic_year_id = :academic_year_id";
            $incomeStmt = $db->prepare($incomeQuery);
            $incomeStmt->execute(['academic_year_id' => $academicYearId]);
            $totalIncome = $incomeStmt->fetch()['total'] ?? 0;
            
            $expenseQuery = "SELECT SUM(amount) as total FROM committee_funds WHERE type = 'expense' AND academic_year_id = :academic_year_id";
            $expenseStmt = $db->prepare($expenseQuery);
            $expenseStmt->execute(['academic_year_id' => $academicYearId]);
            $totalExpense = $expenseStmt->fetch()['total'] ?? 0;
            
            $committeeBalance = $totalIncome - $totalExpense;
            
            // Get recent payments (last 5)
            $recentPaymentsQuery = "
                SELECT p.id, p.amount, p.date, p.status, s.name as student_name, s.nisn, pt.name as payment_type 
                FROM payments p
                JOIN students s ON p.student_id = s.id
                JOIN payment_types pt ON p.payment_type_id = pt.id
                WHERE p.academic_year_id = :academic_year_id
                ORDER BY p.date DESC
                LIMIT 5
            ";
            $recentPaymentsStmt = $db->prepare($recentPaymentsQuery);
            $recentPaymentsStmt->execute(['academic_year_id' => $academicYearId]);
            $recentPayments = $recentPaymentsStmt->fetchAll();
            
            return [
                'totalStudents' => $totalStudents,
                'paymentsThisMonth' => $paymentsThisMonth,
                'committeeBalance' => $committeeBalance,
                'recentPayments' => $recentPayments
            ];
        } catch (PDOException $e) {
            error_log("Error getting dashboard summary: " . $e->getMessage());
            return [
                'totalStudents' => 0,
                'paymentsThisMonth' => 0,
                'committeeBalance' => 0,
                'recentPayments' => []
            ];
        }
    }
    
    private function getAcademicYearId($yearString) {
        try {
            $db = getDbConnection();
            $query = "SELECT id FROM academic_years WHERE year = :year";
            $stmt = $db->prepare($query);
            $stmt->execute(['year' => $yearString]);
            $result = $stmt->fetch();
            
            return $result ? $result['id'] : 1; // Default to 1 if not found
        } catch (PDOException $e) {
            error_log("Error getting academic year ID: " . $e->getMessage());
            return 1; // Default to 1 if error
        }
    }
}
?>
