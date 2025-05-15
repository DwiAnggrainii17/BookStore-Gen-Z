<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id_pesanan'])) {
    header("Location: index.php");
    exit;
}

$id_pesanan = (int)$_GET['id_pesanan'];
$query = mysqli_prepare($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = ? 
    AND id_user = ?
");
mysqli_stmt_bind_param($query, "ii", $id_pesanan, $_SESSION['user_id']);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

// Format status
$status = ucwords(str_replace('_', ' ', $pesanan['status']));
$status_class = '';
switch ($pesanan['status']) {
    case 'menunggu_pembayaran':
        $status_class = 'status-waiting';
        break;
    case 'diproses':
        $status_class = 'status-processing';
        break;
    case 'dikirim':
        $status_class = 'status-shipped';
        break;
    case 'selesai':
        $status_class = 'status-completed';
        break;
    default:
        $status_class = 'status-default';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan #<?= $pesanan['id_pesanan'] ?> | Nama Toko Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray: #6b7280;
            --light-gray: #f3f4f6;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f9fafb;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .confirmation-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header .icon {
            font-size: 60px;
            color: var(--success);
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 28px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .header p {
            color: var(--gray);
        }

        .order-details {
            margin-bottom: 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--gray);
            font-weight: 500;
        }

        .detail-value {
            font-weight: 600;
            text-align: right;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .status-waiting {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-shipped {
            background-color: #ecfccb;
            color: #365314;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #065f46;
        }

        .payment-instructions {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            border-left: 4px solid var(--primary);
        }

        .payment-instructions h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .bank-info {
            margin-top: 15px;
        }

        .bank-info p {
            margin-bottom: 8px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .text-center {
            text-align: center;
            margin-top: 30px;
        }

        .order-id {
            font-size: 18px;
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 24px;
            color: var(--primary);
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="confirmation-card">
            <div class="header">
                <div class="icon">✓</div>
                <h1>Pesanan Berhasil Dibuat!</h1>
                <p>Terima kasih telah berbelanja di toko kami</p>
            </div>

            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">ID Pesanan</span>
                    <span class="detail-value order-id">#<?= $pesanan['id_pesanan'] ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Pesanan</span>
                    <span class="detail-value"><?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Pembayaran</span>
                    <span class="detail-value total-amount">Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="status <?= $status_class ?>"><?= $status ?></span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Metode Pembayaran</span>
                    <span class="detail-value"><?= strtoupper(str_replace('_', ' ', $pesanan['metode_pembayaran'])) ?></span>
                </div>
            </div>

            <?php if ($pesanan['metode_pembayaran'] == 'transfer_bank'): ?>
                <div class="payment-instructions">
                    <h3>Instruksi Pembayaran</h3>
                    <p>Silakan transfer sesuai dengan detail berikut:</p>
                    
                    <div class="bank-info">
                        <p><strong>Bank Tujuan:</strong> BANK ABC</p>
                        <p><strong>Nomor Rekening:</strong> 1234567890</p>
                        <p><strong>Atas Nama:</strong> Nama Toko Anda</p>
                        <p><strong>Jumlah Transfer:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></p>
                        <p><strong>Kode Referensi:</strong> ORDER-<?= $pesanan['id_pesanan'] ?></p>
                    </div>
                    
                    <p style="margin-top: 15px; font-size: 14px; color: var(--gray);">
                        Harap lakukan pembayaran dalam waktu 24 jam untuk menghindari pembatalan pesanan otomatis.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <a href="index.php" class="btn">Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>