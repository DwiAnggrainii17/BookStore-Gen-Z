<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data keranjang
$query = mysqli_query($conn, "
    SELECT produk.id_produk, produk.nama_produk, produk.harga, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = {$_SESSION['user_id']}
");

$total = 0;
$item_count = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .item-price {
            color: #7f8c8d;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-btn {
            background: #3498db;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-btn:hover {
            background: #2980b9;
        }
        .quantity-input {
            width: 40px;
            text-align: center;
            margin: 0 10px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #3498db;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-checkout {
            background: #2ecc71;
            color: white;
        }
        .btn-checkout:hover {
            background: #27ae60;
        }
        .btn-continue {
            background: #3498db;
            color: white;
        }
        .btn-continue:hover {
            background: #2980b9;
        }
        .empty-cart {
            text-align: center;
            padding: 40px 0;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Keranjang Belanja</h2>
        
        <?php if ($item_count == 0): ?>
            <div class="empty-cart">
                <p>Keranjang belanja Anda kosong</p>
                <a href="index.php" class="btn btn-continue">Lanjutkan Belanja</a>
            </div>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="cart-item">
                    <div class="item-info">
                        <div class="item-name"><?php echo $row['nama_produk']; ?></div>
                        <div class="item-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="quantity-control">
                        <form action="update_cart.php" method="post" style="display:flex; align-items:center;">
                            <input type="hidden" name="id_produk" value="<?php echo $row['id_produk']; ?>">
                            <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                            <input type="text" name="quantity" value="<?php echo $row['jumlah']; ?>" class="quantity-input" readonly>
                            <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                        </form>
                    </div>
                </div>
                <?php $total += $row['harga'] * $row['jumlah']; ?>
            <?php endwhile; ?>
            
            <div class="total-section">
                <h4>Total Belanja:</h4>
                <div class="total-amount">Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
            </div>
            
            <div class="action-buttons">
                <a href="index.php" class="btn btn-continue">Lanjutkan Belanja</a>
                <a href="checkout.php" onclick="return validateCheckout()" class="btn btn-checkout">Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function validateCheckout() {
            <?php if ($item_count == 0): ?>
                alert("Keranjang kosong! Tambahkan produk terlebih dahulu.");
                window.location.href = "index.php";
                return false;
            <?php else: ?>
                return confirm("Apakah Anda yakin ingin melanjutkan ke checkout?");
            <?php endif; ?>
        }
    </script>
</body>
</html>