
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME ?></title>
    
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
            <div class="container-fluid">
                <!-- Sidebar Toggle Button -->
                <button class="btn btn-link text-light sidebar-toggle me-3" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <!-- Logo -->
                <a class="navbar-brand" href="?page=dashboard">
                    <img src="assets/images/kemenag-logo-small.png" alt="Logo" height="30" class="d-inline-block align-text-top me-2">
                    <?= APP_NAME ?>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- Academic Year Selector -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="academicYearDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?= isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : CURRENT_ACADEMIC_YEAR ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="academicYearDropdown">
                                <?php foreach (getAcademicYears() as $year): ?>
                                    <li>
                                        <a class="dropdown-item <?= $year['year'] === (isset($_SESSION['academic_year']) ? $_SESSION['academic_year'] : CURRENT_ACADEMIC_YEAR) ? 'active' : '' ?>" 
                                           href="?page=settings&action=changeAcademicYear&year=<?= urlencode($year['year']) ?>">
                                            <?= htmlspecialchars($year['year']) ?>
                                            <?= $year['is_active'] ? ' <span class="badge bg-success">Aktif</span>' : '' ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <?= isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'User' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="?page=users&action=profile">
                                    <i class="fas fa-user-cog me-2"></i> Profil
                                </a></li>
                                <li><a class="dropdown-item" href="?page=users&action=changePassword">
                                    <i class="fas fa-key me-2"></i> Ganti Password
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="?page=auth&action=logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
