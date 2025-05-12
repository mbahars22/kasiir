
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Keuangan Madrasah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-image: url('assets/images/kemenag-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .login-logo {
            width: auto;
            height: 80px;
            margin-bottom: 20px;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .login-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .demo-btn {
            font-size: 0.85rem;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <img src="assets/images/kemenag-logo.png" alt="Logo Kemenag" class="login-logo">
                        <h1 class="login-title">SISTEM KEUANGAN MADRASAH</h1>
                        <p class="login-subtitle">Silakan masuk untuk mengakses sistem</p>
                    </div>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="?page=auth&action=login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="email@madrasah.com" required
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Masukkan password Anda" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="academicYear" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="academicYear" name="academicYear">
                                <?php foreach ($academicYears as $year): ?>
                                    <option value="<?= htmlspecialchars($year['year']) ?>" 
                                            <?= $year['is_active'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['year']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if (empty($academicYears)): ?>
                                    <option value="2024-2025">2024-2025</option>
                                    <option value="2023-2024">2023-2024</option>
                                    <option value="2022-2023">2022-2023</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Masuk</button>
                        </div>
                    </form>
                    
                    <hr>
                    <div class="text-center">
                        <p class="small text-muted mb-2">Akun Demo</p>
                        <div class="demo-accounts">
                            <button type="button" class="btn btn-outline-secondary demo-btn" 
                                   onclick="setDemoAccount('admin@madrasah.com', 'password123')">
                                Admin
                            </button>
                            <button type="button" class="btn btn-outline-secondary demo-btn" 
                                   onclick="setDemoAccount('committee@madrasah.com', 'password123')">
                                Komite
                            </button>
                            <button type="button" class="btn btn-outline-secondary demo-btn" 
                                   onclick="setDemoAccount('observer@madrasah.com', 'password123')">
                                Pengamat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setDemoAccount(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
