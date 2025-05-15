<?php
session_start();

// Koneksi ke database
$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$nama_db = "tokobuku";

// Coba buat database jika belum ada
$koneksi_awal = new mysqli($host_db, $user_db, $pass_db);
if ($koneksi_awal->connect_error) {
    die("Koneksi awal gagal: " . $koneksi_awal->connect_error);
}
$koneksi_awal->query("CREATE DATABASE IF NOT EXISTS $nama_db");
$koneksi_awal->close();

// Koneksi ke database yang sudah ada
$koneksi = new mysqli($host_db, $user_db, $pass_db, $nama_db);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Inisialisasi variabel
$error = '';
$username = '';
$password = '';
$nama_lengkap = '';
$no_telepon = '';
$alamat = '';

// Proses registrasi
if (isset($_POST['register'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $no_telepon = trim($_POST['no_telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    // Validasi input
    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($no_telepon) || empty($alamat)) {
        $error = "Semua bidang harus diisi.";
    } elseif (strlen($password) < 8) {
        $error = "Password harus minimal 8 karakter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password harus mengandung minimal satu angka.";
    } else {
        // Periksa apakah username sudah ada
        $cek_user = $koneksi->prepare("SELECT id_user FROM user WHERE username = ?");
        $cek_user->bind_param("s", $username);
        $cek_user->execute();
        $cek_user->store_result();

        if ($cek_user->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            // Simpan ke tabel user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $created_at = date("Y-m-d H:i:s");

            $insert_user = $koneksi->prepare("INSERT INTO user (username, password, nama_lengkap, no_telepon, alamat, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_user->bind_param("ssssss", $username, $hashedPassword, $nama_lengkap, $no_telepon, $alamat, $created_at);

            if ($insert_user->execute()) {
                $insert_user->close();
                $cek_user->close();
                $koneksi->close();
                header("Location: login_user.php?register_success=1");
                exit();
            } else {
                $error = "Terjadi kesalahan saat menyimpan data: " . $koneksi->error;
            }

            $insert_user->close();
        }

        $cek_user->close();
    }
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - BookStore</title>
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
        
        .register-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .register-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .register-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .register-header h3 {
            margin: 0;
            font-weight: 600;
        }
        
        .register-body {
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
        
        .btn-register {
            background-color: var(--primary-color);
            border: none;
            height: 45px;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background-color: var(--secondary-color);
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
        
        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
        }
        
        .register-footer a {
            color: var(--accent-color);
            text-decoration: none;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
        
        .password-requirements {
            font-size: 0.8rem;
            color: #7f8c8d;
            margin-bottom: 1rem;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-card">
                <div class="register-header">
                    <h3><i class="fas fa-book me-2"></i>Daftar Akun</h3>
                </div>
                <div class="register-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="post" id="registrationForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($username); ?>" 
                                   placeholder="Masukkan username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" 
                                   value="<?php echo htmlspecialchars($nama_lengkap); ?>" 
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" name="no_telepon" id="no_telepon" class="form-control" 
                                   value="<?php echo htmlspecialchars($no_telepon); ?>" 
                                   placeholder="Masukkan nomor telepon" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" 
                                      placeholder="Masukkan alamat" required><?php echo htmlspecialchars($alamat); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" class="form-control" 
                                       placeholder="Masukkan password" required>
                                <span class="toggle-password" onclick="togglePassword('password')">
                                    <i class="far fa-eye" id="password_toggle"></i>
                                </span>
                            </div>
                            <div class="password-requirements mt-1">
                            </div>
                        </div>
                        
                        <button type="submit" name="register" class="btn btn-register w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </button>
                        
                        <div class="divider">
                            <span>ATAU</span>
                        </div>
                        
                        <div class="register-footer">
                            Sudah punya akun? <a href="login_user.php">Masuk Sekarang</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(fieldId + '_toggle');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        let errorMessages = [];
        
        if (password.length < 8) {
            errorMessages.push('Password harus minimal 8 karakter.');
        }
        
        if (!/[0-9]/.test(password)) {
            errorMessages.push('Password harus mengandung minimal satu angka.');
        }
        
        if (password !== confirmPassword) {
            errorMessages.push('Password dan konfirmasi tidak cocok.');
        }
        
        if (errorMessages.length > 0) {
            event.preventDefault();
            let errorHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>' + 
                            errorMessages.join('<br>') + '</div>';
            
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.outerHTML = errorHTML;
            } else {
                this.insertAdjacentHTML('beforebegin', errorHTML);
            }
        }
    });
    </script>
</body>
</html>