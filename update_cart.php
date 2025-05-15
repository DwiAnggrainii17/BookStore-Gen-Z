<?php
session_start();
include "config.php";

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Validasi input
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_produk'], $_POST['action'])) {
    $product_id = (int)$_POST['id_produk'];
    $user_id = (int)$_SESSION['user_id'];
    $action = $_POST['action'];
    
    // Ambil data jumlah saat ini dari database
    $query = "SELECT jumlah FROM keranjang WHERE id_user = ? AND id_produk = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $current = mysqli_fetch_assoc($result);
    
    if ($current) {
        $new_quantity = $current['jumlah'];
        
        // Update jumlah berdasarkan aksi
        if ($action == 'increase') {
            $new_quantity++;
        } elseif ($action == 'decrease') {
            $new_quantity = max(1, $current['jumlah'] - 1); // Minimal 1
        }
        
        // Update database
        $update_query = "UPDATE keranjang SET jumlah = ? WHERE id_user = ? AND id_produk = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "iii", $new_quantity, $user_id, $product_id);
        mysqli_stmt_execute($update_stmt);
        
        // Periksa apakah update berhasil
        if (mysqli_stmt_affected_rows($update_stmt) > 0) {
            $_SESSION['success'] = "Keranjang berhasil diperbarui";
        } else {
            $_SESSION['error'] = "Gagal memperbarui keranjang";
        }
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan di keranjang";
    }
    
    // Redirect kembali ke halaman keranjang
    header("Location: keranjang.php");
    exit;
} else {
    // Jika akses tidak valid
    $_SESSION['error'] = "Akses tidak valid";
    header("Location: keranjang.php");
    exit;
}