<?php
session_start();
include "config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_pesanan = (int)$_GET['id'];

// Get order data
$query_pesanan = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = {$_SESSION['user_id']}
");
$pesanan = mysqli_fetch_assoc($query_pesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

// Get order items
$query_items = mysqli_query($conn, "
    SELECT detail_pesanan.*, produk.nama_produk, produk.gambar
    FROM detail_pesanan
    JOIN produk ON detail_pesanan.id_produk = produk.id_produk
    WHERE detail_pesanan.id_pesanan = $id_pesanan
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $pesanan['id_pesanan'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .order-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        
        .order-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        
        .order-title {
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .info-card {
            background-color: var(--secondary-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-card h5 {
            color: var(--primary-color);
            margin-bottom: 1.2rem;
        }
        
        .info-label {
            font-weight: 600;
            color: #5a5c69;
        }
        
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e3e6f0;
        }
        
        .status-badge {
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.75rem;
            display: inline-block;
        }
        
        .status-menunggu-verifikasi {
            background-color: #f8f4e6;
            color: #856404;
        }
        
        .status-diproses {
            background-color: #e6f3fa;
            color: #0c5460;
        }
        
        .status-dikirim {
            background-color: #e6f7ee;
            color: #155724;
        }
        
        .status-selesai {
            background-color: #e6f7ee;
            color: #155724;
        }
        
        .table th {
            background-color: var(--secondary-color);
            color: #4e73df;
            font-weight: 700;
        }
        
        .back-btn {
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            transform: translateX(-3px);
        }
        
        .total-row {
            font-weight: 700;
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        @media (max-width: 768px) {
            .order-container {
                padding: 1rem;
            }
            
            .product-img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="order-container">
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="order-title mb-0">Detail Pesanan #<?= $pesanan['id_pesanan'] ?></h1>
                    <a href="index.php" class="btn btn-outline-primary back-btn">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Pesanan</h5>
                        <div class="mb-3">
                            <span class="info-label">Tanggal:</span>
                            <span class="d-block"><?= date('d F Y H:i', strtotime($pesanan['created_at'])) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Status:</span>
                            <span class="d-block">
                                <span class="status-badge status-<?= str_replace('_', '-', $pesanan['status']) ?>">
                                    <?php
                                    $status = [
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'diproses' => 'Diproses',
                                        'dikirim' => 'Dikirim',
                                        'selesai' => 'Selesai'
                                    ];
                                    echo $status[$pesanan['status']] ?? $pesanan['status'];
                                    ?>
                                </span>
                            </span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Total:</span>
                            <span class="d-block text-success fw-bold">Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <h5><i class="fas fa-truck me-2"></i>Pengiriman</h5>
                        <div>
                            <span class="info-label">Alamat:</span>
                            <div class="d-block p-2 bg-light rounded">
                                <?= nl2br(htmlspecialchars($pesanan['alamat'])) ?>
                            </div>
                        </div>
                        <?php if (!empty($pesanan['no_resi'])): ?>
                        <div class="mt-3">
                            <span class="info-label">Nomor Resi:</span>
                            <span class="d-block"><?= htmlspecialchars($pesanan['no_resi']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <h5 class="mb-3"><i class="fas fa-shopping-cart me-2"></i>Item Pesanan</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40%">Produk</th>
                            <th width="15%">Harga</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Subtotal</th>
                            <th width="15%">Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_items = 0;
                        while ($item = mysqli_fetch_assoc($query_items)): 
                            $total_items += $item['jumlah'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <img src="admin/uploads/<?= $item['gambar'] ?>" class="product-img" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <tr class="total-row">
                            <td colspan="2" class="text-end"><strong>Total Item:</strong></td>
                            <td><strong><?= $total_items ?></strong></td>
                            <td colspan="2"><strong>Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <?php if ($pesanan['status'] == 'menunggu_verifikasi' && !empty($pesanan['bukti_transfer'])): ?>
            <div class="info-card mt-4">
                <h5><i class="fas fa-receipt me-2"></i>Bukti Transfer</h5>
                <img src="admin/uploads/bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" class="img-fluid rounded bukti-transfer" alt="Bukti Transfer">
                <p class="mt-2 text-muted">Bukti transfer sedang diverifikasi oleh admin.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>