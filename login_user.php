<?php 
session_start();

// Koneksi ke database
$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$nama_db = "tokobuku";
$koneksi = new mysqli($host_db, $user_db, $pass_db, $nama_db);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi variabel
$error = '';
$username = isset($_COOKIE['cookie_username']) ? $_COOKIE['cookie_username'] : '';
$ingataku = isset($_COOKIE['cookie_username']) ? 'checked' : '';

// Jika tombol login ditekan
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $ingataku = isset($_POST['ingataku']) ? 'checked' : '';

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Silakan masukkan username dan password.";
    } else {
        // Gunakan prepared statement untuk menghindari SQL Injection
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $error = "Username tidak ditemukan.";
        } else {
            $user = $result->fetch_assoc();
            
            if (!password_verify($password, $user['password'])) {
                $error = "Password yang dimasukkan tidak sesuai.";
            } else {
                // Set session
                $_SESSION['user'] = $user['username'];
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['password'] = $user['password'];
                
                // Set cookie jika "Ingat Aku" dicentang
                if (isset($_POST['ingataku'])) {
                    setcookie("cookie_username", $username, time() + (30 * 24 * 60 * 60), "/");
                } else {
                    setcookie("cookie_username", "", time() - 3600, "/");
                }
                
                // Redirect ke halaman utama
                header("Location: index.php");
                exit();
            }
        }
        $stmt->close();
    }
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            min-height: 100vh;
            align-items: center;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .login-header h3 {
            margin: 0;
            font-weight: 600;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border: none;
            height: 45px;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: var(--secondary-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        
        .password-container {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #95a5a6;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .divider span {
            padding: 0 10px;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
        }
        
        .login-footer a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h3><i class="fas fa-book-open me-2"></i>Toko Buku Gen Z</h3>
                </div>
                <div class="login-body">
                    <?php if (isset($_GET['message']) && $_GET['message'] == 'need_login'): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Silakan login untuk mengakses layanan kami.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($username); ?>" 
                                   placeholder="Masukkan username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Masukkan password" required>
                                <span class="toggle-password" onclick="togglePassword()">
                                    <i class="far fa-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="ingataku" id="rememberMe" <?php echo $ingataku; ?>>
                            <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                        </div>
                        
                        <button type="submit" name="login" class="btn btn-primary btn-login w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                        
                        <div class="text-center mb-3">
                            <a href="lupa_password.php" class="login-footer">Lupa Password?</a>
                        </div>
                        
                        <div class="divider">
                            <span>ATAU</span>
                        </div>
                        
                        <div class="text-center">
                            <p class="login-footer">Belum punya akun? <a href="register_user.php">Daftar Sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>