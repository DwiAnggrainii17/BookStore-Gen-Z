<?php
session_start();
include "../config.php";

$id_pesanan = (int)$_GET['id'];

// Get order data + payment proof
$query_pesanan = mysqli_query($conn, "
    SELECT pesanan.*, user.username 
    FROM pesanan 
    JOIN user ON pesanan.id_user = user.id_user
    WHERE pesanan.id_pesanan = $id_pesanan
");
$pesanan = mysqli_fetch_assoc($query_pesanan);

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
    <title>Detail Pesanan #<?= $id_pesanan ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--dark);
        }
        
        .order-card {
            background: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-top: 2rem;
            margin-bottom: 2rem;
            padding: 2rem;
        }
        
        .order-header {
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        
        .order-title {
            color: var(--primary);
            font-weight: 700;
        }
        
        .info-card {
            background-color: var(--light);
            border-radius: 0.35rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-card h3 {
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark);
            min-width: 150px;
            display: inline-block;
        }
        
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #e3e6f0;
        }
        
        .payment-img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #e3e6f0;
            margin-top: 1rem;
        }
        
        .status-badge {
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .status-menunggu_verifikasi {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-verified {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-diproses {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-dikirim {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .table th {
            background-color: var(--light);
            color: var(--primary);
            font-weight: 700;
        }
        
        .back-btn {
            transition: all 0.3s;
            font-weight: 600;
        }
        
        .back-btn:hover {
            transform: translateX(-3px);
        }
        
        .total-row {
            font-weight: 700;
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        .action-btns .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .order-card {
                padding: 1rem;
            }
            
            .info-label {
                min-width: 120px;
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
        <div class="order-card">
            <div class="order-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h1 class="order-title mb-3 mb-md-0">
                        <i class="fas fa-receipt"></i> Detail Pesanan #<?= $id_pesanan ?>
                    </h1>
                    <a href="index.php" class="btn btn-outline-primary back-btn">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-card">
                        <h3><i class="fas fa-info-circle"></i> Informasi Pesanan</h3>
                        <div class="mb-3">
                            <span class="info-label">Pelanggan:</span>
                            <span><?= htmlspecialchars($pesanan['username']) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Tanggal Pesan:</span>
                            <span><?= date('d F Y H:i', strtotime($pesanan['created_at'])) ?></span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-<?= $pesanan['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $pesanan['status'])) ?>
                            </span>
                        </div>
                        <div class="mb-3">
                            <span class="info-label">Total:</span>
                            <span class="fw-bold">Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="info-card">
                        <h3><i class="fas fa-truck"></i> Pengiriman</h3>
                        <div class="mb-3">
                            <span class="info-label">Alamat:</span>
                            <div class="p-2 bg-light rounded mt-2">
                                <?= nl2br(htmlspecialchars($pesanan['alamat'])) ?>
                            </div>
                        </div>
                        <?php if (!empty($pesanan['no_resi'])): ?>
                        <div class="mb-3">
                            <span class="info-label">Nomor Resi:</span>
                            <span><?= htmlspecialchars($pesanan['no_resi']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Payment Proof Section -->
            <div class="info-card">
                <h3><i class="fas fa-money-bill-wave"></i> Bukti Transfer</h3>
                <?php if ($pesanan['bukti_transfer']): ?>
                    <div class="d-flex flex-column flex-md-row align-items-start gap-4">
                        <div>
                            <img src="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" alt="Bukti Transfer" class="payment-img" style="max-height: 300px;">
                        </div>
                        <div class="action-btns mt-3 mt-md-0">
                            <a href="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" download class="btn btn-primary">
                                <i class="fas fa-download me-2"></i> Download Bukti
                            </a>
                            <a href="../bukti_transfer/<?= $pesanan['bukti_transfer'] ?>" target="_blank" class="btn btn-info text-white">
                                <i class="fas fa-expand me-2"></i> Lihat Full Size
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i> Belum mengupload bukti transfer.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Order Items Section -->
            <div class="info-card">
                <h3><i class="fas fa-shopping-basket"></i> Item Pesanan</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="35%">Produk</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Jumlah</th>
                                <th width="15%">Subtotal</th>
                                <th width="20%">Gambar</th>
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
                                        <img src="uploads/<?= $item['gambar'] ?>" class="product-img" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <tr class="total-row">
                                <td colspan="2" class="text-end fw-bold">Total Item:</td>
                                <td class="fw-bold"><?= $total_items ?></td>
                                <td colspan="2" class="fw-bold">Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>