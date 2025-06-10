<?php
session_start();
require 'config.php';

// Proses logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Proses login
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC); // pastikan ini associative

            if ($user && password_verify($password, $user['password'])) {
                // Set session login
                $_SESSION['user'] = [
                    'id'        => $user['id'],
                    'username'  => $user['username'],
                    'full_name' => $user['full_name'],
                    'email'     => $user['email'],
                    'role'      => $user['role']
                ];

                // Update waktu login terakhir jika kolomnya ada
                $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $update->execute([$user['id']]);

                header('Location: index.php');
                exit;
                
            } else {
                $error = 'Username atau password salah';
            }
        } catch (PDOException $e) {
            // Gunakan log untuk keamanan
            error_log($e->getMessage());
            $error = 'Terjadi kesalahan sistem. Coba lagi nanti.';
             $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }

    } elseif (isset($_POST['register'])) {
        // Registration process
        $username = trim($_POST['reg_username']);
        $password = trim($_POST['reg_password']);
        $confirm_password = trim($_POST['confirm_password']);
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        
        // Validation
        if (empty($username) || empty($password) || empty($confirm_password) || empty($full_name) || empty($email)) {
            $error = 'Semua field harus diisi';
        } elseif ($password !== $confirm_password) {
            $error = 'Password dan konfirmasi password tidak cocok';
        } elseif (strlen($password) < 8) {
            $error = 'Password harus minimal 8 karakter';
        } else {
            try {
                // Check if username or email already exists
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                $existing_user = $stmt->fetch();
                
                if ($existing_user) {
                    $error = 'Username atau email sudah terdaftar';
                } else {
                    // Hash password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert new user with default role 'user'
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, full_name, email, role, created_at) VALUES (?, ?, ?, ?, 'user', NOW())");
                    $stmt->execute([$username, $hashed_password, $full_name, $email]);
                    
                    $success = 'Registrasi berhasil! Silakan login';
                }
            } catch (PDOException $e) {
                $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-card {
            width: 100%;
            max-width: 400px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .auth-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            text-align: center;
            color: white;
        }
        
        .auth-body {
            background: white;
            padding: 30px;
        }
        
        .form-control {
            height: 45px;
            border-radius: 8px;
        }
        
        .btn-auth {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            height: 45px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-auth:hover {
            background: linear-gradient(to right, #5a6fd1, #6a4196);
        }
        
        .nav-tabs {
            border-bottom: none;
            justify-content: center;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 10px 25px;
        }
        
        .nav-tabs .nav-link.active {
            color: #667eea;
            background: transparent;
            border-bottom: 3px solid #667eea;
        }
        
        .tab-content {
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="auth-card mx-auto">
                    <div class="auth-header">
                        <h3><i class="fas fa-lock me-2"></i> ADMIN PANEL</h3>
                        <p class="mb-0">Silakan masuk atau daftar dengan akun Anda</p>
                    </div>
                    
                    <div class="auth-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>
                        
                        <ul class="nav nav-tabs" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="authTabsContent">
                            <!-- Login Tab -->
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form method="POST" action="">
                                    <input type="hidden" name="login" value="1">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-auth text-white">
                                            <i class="fas fa-sign-in-alt me-2"></i> LOGIN
                                        </button>
                                    </div>
                                    
                                    <div class="text-center">
                                        <a href="#" class="text-decoration-none">Lupa password?</a>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Register Tab -->
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form method="POST" action="">
                                    <input type="hidden" name="register" value="1">
                                    <div class="mb-3">
                                        <label for="reg_username" class="form-label">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="reg_username" name="reg_username" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Nama Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="reg_password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" class="form-control" id="reg_password" name="reg_password" required>
                                        </div>
                                        <small class="text-muted">Minimal 8 karakter</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-auth text-white">
                                            <i class="fas fa-user-plus me-2"></i> REGISTER
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>