<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses saat form disubmit
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori_produk = $_POST['kategori_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // Upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = "uploads/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar_path = $upload_dir . basename($gambar);

    if (move_uploaded_file($tmp_name, $gambar_path)) {
        $query = "INSERT INTO produk (nama_produk, kategori_produk, gambar, harga, deskripsi, stok) 
                  VALUES ('$nama_produk', '$kategori_produk', '$gambar', '$harga', '$deskripsi', '$stok')";

        if (mysqli_query($conn, $query)) {
            $success_message = "Produk berhasil ditambahkan!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Gagal upload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore Gen-Z</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a86ff;
            --secondary-color: #8338ec;
            --accent-color: #ff006e;
            --text-color: #333;
            --light-text: #777;
            --light-bg: #f9f9f9;
            --border-color: #e0e0e0;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --danger-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            padding-bottom: 40px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header & Navigation */
        header {
            background-color: #2c3e50;
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 30px;
        }

        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
        }

        .admin-logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .admin-logo i {
            margin-right: 10px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-name {
            font-weight: 500;
        }

        .logout-btn {
            padding: 8px 16px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Main Content */
        main {
            padding: 20px 0;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-body {
            padding: 20px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .form-control::placeholder {
            color: #aaa;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2a75e6;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f5f7fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--text-color);
            border-bottom: 2px solid var(--border-color);
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: #f9f9f9;
        }

        .table-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .edit-btn {
            background-color: var(--info-color);
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: var(--danger-color);
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .detail-btn {
            background-color: var(--primary-color);
        }

        .detail-btn:hover {
            background-color: #2a75e6;
        }

        /* Status Colors */
        .status-pending {
            color: var(--warning-color);
            font-weight: 500;
        }

        .status-diproses {
            color: var(--info-color);
            font-weight: 500;
        }

        .status-dikirim {
            color: var(--primary-color);
            font-weight: 500;
        }

        .status-selesai {
            color: var(--success-color);
            font-weight: 500;
        }

        .status-select {
            padding: 6px 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: white;
        }

        /* Alert Messages */
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                gap: 15px;
            }

            .form-row {
                flex-direction: column;
            }

            .form-group {
                width: 100% !important;
            }
        }

        /* Form Row for side by side inputs */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /* Tab Navigation for different sections */
        .tab-nav {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .tab-link {
            padding: 12px 20px;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .tab-link:hover {
            background-color: #f8f9fa;
        }

        .tab-content {
            padding: 20px 0;
        }

        /* Additional utility classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="admin-header">
                <a href="#" class="admin-logo">
                    <i class="fas fa-shield-alt"></i> BookStore Gen-Z
                </a>
                <div class="admin-info">
                    <span class="admin-name">
                        <i class="fas fa-user-circle"></i> 
                        Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Tab Navigation -->
        <div class="tab-nav">
            <a href="#produk" class="tab-link active">Kelola Produk</a>
            <a href="#user" class="tab-link">Data User</a>
            <a href="#pesanan" class="tab-link">Data Pesanan</a>
        </div>

        <!-- Form Tambah Produk -->
        <section id="produk" class="tab-content">
            <h2 class="section-title"><i class="fas fa-plus-circle"></i> Tambah Produk Baru</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <h3>Formulir Produk</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nama Produk:</label>
                                <input type="text" name="nama_produk" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Kategori Produk:</label>
                                <select name="kategori_produk" class="form-control" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Sejarah">Islami</option>
                                    <option value="MaPel">MaPel</option>
                                    <option value="Novel">Novel</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Harga:</label>
                                <input type="number" name="harga" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Stok:</label>
                                <input type="number" name="stok" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gambar Produk:</label>
                            <input type="file" name="gambar" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Deskripsi Produk:</label>
                            <textarea name="deskripsi" class="form-control" required></textarea>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tambah Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Produk -->
            <h2 class="section-title"><i class="fas fa-boxes"></i> Data Produk</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Gambar</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query_produk = "SELECT * FROM produk";
                                $result_produk = mysqli_query($conn, $query_produk);

                                if (mysqli_num_rows($result_produk) > 0) :
                                    while ($row_produk = mysqli_fetch_assoc($result_produk)) :
                                ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td>
                                                <img src="uploads/<?php echo htmlspecialchars($row_produk['gambar']); ?>" 
                                                    alt="<?php echo htmlspecialchars($row_produk['nama_produk']); ?>" 
                                                    class="table-img">
                                            </td>
                                            <td><?php echo htmlspecialchars($row_produk['nama_produk']); ?></td>
                                            <td><?php echo htmlspecialchars($row_produk['kategori_produk']); ?></td>
                                            <td>Rp <?php echo number_format($row_produk['harga'], 0, ',', '.'); ?></td>
                                            <td class="text-truncate"><?php echo htmlspecialchars($row_produk['deskripsi']); ?></td>
                                            <td><?php echo $row_produk['stok']; ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="edit_produk.php?id=<?php echo $row_produk['id_produk']; ?>" class="action-btn edit-btn">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="hapus_produk.php?id=<?php echo $row_produk['id_produk']; ?>" 
                                                       onclick="return confirm('Yakin ingin hapus produk ini?');" 
                                                       class="action-btn delete-btn">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                    endwhile;
                                else :
                                    echo "<tr><td colspan='8' class='text-center'>Tidak ada data produk.</td></tr>";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Data User -->
        <section id="user" class="tab-content" style="display:none;">
            <h2 class="section-title"><i class="fas fa-users"></i> Data User</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Terdaftar Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query_user = "SELECT * FROM user";
                                $result_user = mysqli_query($conn, $query_user);

                                if (mysqli_num_rows($result_user) > 0) :
                                    while ($row_user = mysqli_fetch_assoc($result_user)) :
                                ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row_user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row_user['nama_lengkap']); ?></td>
                                            <td><?php echo htmlspecialchars($row_user['no_telepon']); ?></td>
                                            <td class="text-truncate"><?php echo htmlspecialchars($row_user['alamat']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($row_user['created_at'])); ?></td>
                                        </tr>
                                <?php
                                    endwhile;
                                else :
                                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data user.</td></tr>";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Data Pesanan -->
        <section id="pesanan" class="tab-content" style="display:none;">
            <h2 class="section-title"><i class="fas fa-clipboard-list"></i> Data Pesanan</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_pesanan = "SELECT pesanan.*, user.username 
                                              FROM pesanan 
                                              JOIN user ON pesanan.id_user = user.id_user
                                              ORDER BY pesanan.created_at DESC";
                                $result_pesanan = mysqli_query($conn, $query_pesanan);

                                if (mysqli_num_rows($result_pesanan) > 0) :
                                    while ($row = mysqli_fetch_assoc($result_pesanan)) :
                                ?>
                                        <tr>
                                            <td>#<?= $row['id_pesanan'] ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                                            <td>
                                                <form action="update_status.php" method="POST" style="margin:0;">
                                                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                                    <select name="status" onchange="this.form.submit()" class="status-select">
                                                        <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                                        <option value="diproses" <?= ($row['status'] == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                                                        <option value="dikirim" <?= ($row['status'] == 'dikirim') ? 'selected' : '' ?>>Dikirim</option>
                                                        <option value="selesai" <?= ($row['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <a href="detail_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="action-btn detail-btn">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada pesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Tab navigation functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabLinks.forEach(item => item.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => content.style.display = 'none');
                    
                    // Show selected tab content
                    const targetId = this.getAttribute('href').substring(1);
                    document.getElementById(targetId).style.display = 'block';
                });
            });
        });
    </script>
</body>

</html>