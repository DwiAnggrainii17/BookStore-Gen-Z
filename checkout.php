<?php
session_start();
include "config.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data user dengan prepared statement
$user_query = mysqli_prepare($conn, "SELECT * FROM user WHERE id_user = ?");
mysqli_stmt_bind_param($user_query, "i", $_SESSION['user_id']);
mysqli_stmt_execute($user_query);
$user_result = mysqli_stmt_get_result($user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Ambil data keranjang user dengan prepared statement
$cart_query = mysqli_prepare($conn, "
    SELECT produk.*, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = ?
");
mysqli_stmt_bind_param($cart_query, "i", $_SESSION['user_id']);
mysqli_stmt_execute($cart_query);
$cart_result = mysqli_stmt_get_result($cart_query);

// Hitung total harga dan siapkan item
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($cart_result)) {
    $row['subtotal'] = $row['harga'] * $row['jumlah'];
    $total += $row['subtotal'];
    $items[] = $row;
}

// Generate order ID
$order_id = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | BookStore Gen-Z</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-hover: #4338ca;
            --secondary: #f8f9fa;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --light-bg: #f9fafb;
            --dark-bg: #111827;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--light-bg);
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 992px) {
            .container {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        h1, h2, h3, h4 {
            color: var(--text);
            margin-bottom: 1.25rem;
            font-weight: 600;
        }

        h2 {
            font-size: 1.75rem;
            position: relative;
            padding-bottom: 0.75rem;
            grid-column: 1 / -1;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 4rem;
            height: 0.25rem;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            border-radius: 0.25rem;
        }

        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-0.25rem);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .order-summary {
            grid-column: 1;
        }

        .checkout-form {
            grid-column: 2;
        }

        @media (max-width: 992px) {
            .order-summary,
            .checkout-form {
                grid-column: 1;
            }
        }

        .order-item {
            display: flex;
            flex-direction: column;
            padding: 1.25rem 0;
            border-bottom: 1px solid var(--border);
            gap: 0.75rem;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .product-name {
            font-weight: 500;
            font-size: 1.1rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .product-name i {
            color: var(--primary);
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            color: var(--text-light);
            font-size: 0.95rem;
        }
        
        .subtotal {
            font-weight: 600;
            color: var(--text);
        }

        .total-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--primary);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .grand-total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text);
            font-size: 0.95rem;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 2.6rem;
            color: var(--text-light);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
            background-color: white;
        }

        textarea {
            padding-left: 1rem;
            min-height: 7.5rem;
        }

        select {
            padding: 0.875rem 1rem;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .payment-methods {
            margin: 1.5rem 0;
        }

        .payment-method {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
        }

        .payment-method:hover {
            border-color: var(--primary);
        }

        .payment-method.active {
            border-color: var(--primary);
            background-color: rgba(79, 70, 229, 0.05);
        }

        .payment-method input {
            width: auto;
            margin-right: 0.75rem;
            accent-color: var(--primary);
        }

        .payment-method label {
            margin-bottom: 0;
            flex: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .payment-icon {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            color: var(--primary);
        }

        .payment-details {
            background-color: var(--secondary);
            padding: 1.25rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-0.5rem); }
            to { opacity: 1; transform: translateY(0); }
        }

        .payment-details.active {
            display: block;
        }

        .bank-info {
            margin-top: 1rem;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .bank-info p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
        }

        .bank-info strong {
            color: var(--text);
            min-width: 8rem;
            display: inline-block;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: block;
            padding: 1.25rem;
            border: 2px dashed var(--border);
            border-radius: 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background-color: white;
        }

        .file-upload-label:hover {
            border-color: var(--primary);
            background-color: rgba(79, 70, 229, 0.05);
        }

        .file-upload-icon {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .file-upload-name {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .btn-submit {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .order-id {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.1);
        }

        .order-id span {
            font-weight: 600;
        }

        .empty-cart {
            text-align: center;
            padding: 2.5rem 0;
        }

        .empty-cart i {
            font-size: 2.5rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .empty-cart p {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        .btn-continue {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-continue:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 0.75rem;
            }
            
            .card {
                padding: 1.25rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }

        /* Animation for cart items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(1rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-item {
            animation: slideIn 0.3s ease-out forwards;
            opacity: 0;
        }

        .order-item:nth-child(1) { animation-delay: 0.1s; }
        .order-item:nth-child(2) { animation-delay: 0.2s; }
        .order-item:nth-child(3) { animation-delay: 0.3s; }
        .order-item:nth-child(4) { animation-delay: 0.4s; }
        .order-item:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>

<body>
    <div class="container">
        <h2>Checkout Pembelian</h2>

        <div class="order-summary">
            <div class="card">
                <h3><i class="fas fa-shopping-bag"></i> Ringkasan Pesanan</h3>
                <div class="order-id">
                    <i class="fas fa-receipt"></i>
                    ID Pesanan: <span><?= $order_id ?></span>
                </div>

                <?php if (empty($items)): ?>
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Keranjang belanja Anda kosong</p>
                        <a href="products.php" class="btn-continue">
                            <i class="fas fa-arrow-left"></i> Lanjutkan Belanja
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($items as $index => $item): ?>
                        <div class="order-item">
                            <div class="product-info">
                                <div class="product-name">
                                    <i class="fas fa-book"></i>
                                    <?= htmlspecialchars($item['nama_produk']) ?>
                                </div>
                                <div class="product-meta">
                                    <span><?= $item['jumlah'] ?> × Rp <?= number_format($item['harga'], 0, ',', '.') ?></span>
                                    <span class="subtotal">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="total-section">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                        <div class="total-row">
                            <span>Ongkos Kirim</span>
                            <span>Rp 0 (Gratis)</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total Pembayaran</span>
                            <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="checkout-form">
            <form action="proses_checkout.php" method="POST" enctype="multipart/form-data" class="card">
                <h3><i class="fas fa-truck"></i> Detail Pengiriman</h3>

                <div class="form-group">
                    <label for="nama_lengkap"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" 
                           value="<?= htmlspecialchars($user_data['nama_lengkap'] ?? '') ?>" required
                           placeholder="Masukkan nama lengkap">
                </div>

                <div class="form-group">
                    <label for="no_telepon"><i class="fas fa-phone"></i> Nomor Telepon</label>
                    <div class="input-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <input type="tel" id="no_telepon" name="no_telepon" 
                           value="<?= htmlspecialchars($user_data['no_telepon'] ?? '') ?>" required
                           placeholder="Masukkan nomor telepon">
                </div>

                <div class="form-group">
                    <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="4" required
                              placeholder="Masukkan alamat lengkap pengiriman"><?= htmlspecialchars($user_data['alamat'] ?? '') ?></textarea>
                </div>

                <h3 style="margin-top: 1.5rem;"><i class="fas fa-credit-card"></i> Metode Pembayaran</h3>

                <div class="payment-methods">
                    <div class="payment-method active">
                        <input type="radio" id="transfer_bank" name="metode_pembayaran" value="transfer_bank" checked>
                        <label for="transfer_bank">
                            <i class="fas fa-university payment-icon"></i>
                            Transfer Bank
                        </label>
                    </div>
                    <div class="payment-method">
                        <input type="radio" id="e_wallet" name="metode_pembayaran" value="e_wallet">
                        <label for="e_wallet">
                            <i class="fas fa-wallet payment-icon"></i>
                            E-Wallet
                        </label>
                    </div>
                </div>

                <div class="payment-details active">
                    <h4><i class="fas fa-info-circle"></i> Instruksi Pembayaran</h4>
                    <p>Silakan transfer ke salah satu rekening berikut:</p>

                    <div class="bank-info">
                        <p><strong><i class="fas fa-bank"></i> Bank :</strong> Bank ABC</p>
                        <p><strong><i class="fas fa-credit-card"></i> No-Rek :</strong> 1234 5678 9012</p>
                        <p><strong><i class="fas fa-user-tie"></i> Atas Nama :</strong> BookStore Gen-Z</p>
                        <p><strong><i class="fas fa-tag"></i> Jumlah :</strong> Rp <?= number_format($total, 0, ',', '.') ?></p>
                        <p><strong><i class="fas fa-file-invoice"></i> Id Pesanan :</strong> <?= $order_id ?></p>
                    </div>

                    <div class="form-group" style="margin-top: 1rem;">
                        <label><i class="fas fa-file-upload"></i> Upload Bukti Transfer</label>
                        <div class="file-upload">
                            <input type="file" id="bukti_transfer" name="bukti_transfer" 
                                   class="file-upload-input" accept="image/jpeg, image/png, .pdf" required>
                            <label for="bukti_transfer" class="file-upload-label">
                                <div class="file-upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <span>Seret file ke sini atau klik untuk memilih</span>
                                <div class="file-upload-name" id="file-name">Format: JPG/PNG/PDF, maksimal 2MB</div>
                            </label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <input type="hidden" name="total_harga" value="<?= $total ?>">

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Konfirmasi Pesanan
                </button>
            </form>
        </div>
    </div>

    <script>
        // Menampilkan nama file yang dipilih
        document.getElementById('bukti_transfer').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Format: JPG/PNG/PDF, maksimal 2MB';
            document.getElementById('file-name').textContent = fileName;
        });

        // Toggle payment details dan active class
        document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove active class from all payment methods
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.classList.remove('active');
                });
                
                // Add active class to selected payment method
                this.closest('.payment-method').classList.add('active');
                
                // Show payment details (in this case always show the same details)
                document.querySelector('.payment-details').classList.add('active');
            });
        });

        // Add animation to form elements
        const formGroups = document.querySelectorAll('.form-group');
        formGroups.forEach((group, index) => {
            group.style.opacity = '0';
            group.style.transform = 'translateY(1rem)';
            group.style.animation = `fadeIn 0.4s ease-out ${index * 0.1 + 0.3}s forwards`;
        });
    </script>
</body>
</html>