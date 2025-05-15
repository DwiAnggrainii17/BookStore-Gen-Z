<?php
include "../config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    if (empty($_POST['username']) || empty($_POST['password'])) {
        die("Username dan password harus diisi");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi panjang username dan password
    if (strlen($username) < 4 || strlen($username) > 20) {
        die("Username harus antara 4-20 karakter");
    }

    if (strlen($password) < 8) {
        die("Password minimal 8 karakter");
    }

    // Cek apakah username sudah ada
    $check_query = "SELECT id_admin FROM admin WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Username sudah digunakan, silakan pilih username lain");
    }
    $stmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database menggunakan prepared statement
    $query = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php?register=success");
        exit();
    } else {
        error_log("Gagal register: " . $stmt->error);
        die("Terjadi kesalahan saat registrasi. Silakan coba lagi.");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register.php");
    exit();
}