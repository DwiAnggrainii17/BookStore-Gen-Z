<?php
include "../config.php";

// Validasi ID produk
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM produk WHERE id_produk = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

// Cek jika data tidak ditemukan
if (!$data) {
    header("Location: index.php?error=product_not_found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --light-text: #858796;
            --border-color: #dddfeb;
        }
        
        body {
            background-color: var(--secondary-color);
            color: var(--text-color);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-radius: 0.35rem 0.35rem 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .form-control, .form-select {
            border: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .img-preview {
            max-width: 200px;
            height: auto;
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            padding: 0.5rem;
            background-color: white;
        }
        
        .img-container {
            position: relative;
            display: inline-block;
        }
        
        .img-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 0.25rem;
        }
        
        .img-container:hover .img-overlay {
            opacity: 1;
        }
        
        textarea {
            min-height: 120px;
        }
        
        @media (max-width: 768px) {
            .img-preview {
                max-width: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h4 class="m-0"><i class="fas fa-edit me-2"></i>BookStore Gen-Z</h4>
                        <a href="index.php" class="btn btn-sm btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="proses_edit.php" method="POST" enctype="multipart/form-data" id="editForm">
                            <input type="hidden" name="id_produk" value="<?php echo htmlspecialchars($data['id_produk']); ?>">
                            <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($data['gambar']); ?>">

                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
                                       value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="kategori_produk" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori_produk" name="kategori_produk" required>
                                    <option value="Islami" <?php echo ($data['kategori_produk'] == 'Islami') ? 'selected' : ''; ?>>Islami</option>
                                    <option value="MaPel" <?php echo ($data['kategori_produk'] == 'MaPel') ? 'selected' : ''; ?>>MaPel</option>
                                    <option value="Novel" <?php echo ($data['kategori_produk'] == 'Novel') ? 'selected' : ''; ?>>Novel</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="harga" class="form-label">Harga (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="harga" name="harga" 
                                               value="<?php echo htmlspecialchars($data['harga']); ?>" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" 
                                           value="<?php echo htmlspecialchars($data['stok']); ?>" min="0" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div class="img-container">
                                    <img src="../uploads/<?php echo htmlspecialchars($data['gambar']); ?>" 
                                         alt="Gambar Produk" 
                                         class="img-preview mb-2"
                                         id="currentImage">
                                    <div class="img-overlay">
                                        <span>Gambar Saat Ini</span>
                                    </div>
                                </div>
                                <div class="form-text">Ukuran disarankan: 800x800 px</div>
                            </div>

                            <div class="mb-4">
                                <label for="gambar" class="form-label">Gambar Baru (Opsional)</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar</div>
                                <div class="mt-2" id="imagePreview"></div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview gambar baru sebelum upload
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = `
                        <div class="img-container mt-2">
                            <img src="${e.target.result}" class="img-preview" alt="Preview Gambar Baru">
                            <div class="img-overlay">
                                <span>Preview Gambar Baru</span>
                            </div>
                        </div>
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        // Validasi form sebelum submit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const harga = document.getElementById('harga').value;
            const stok = document.getElementById('stok').value;
            
            if (harga <= 0) {
                alert('Harga harus lebih dari 0');
                e.preventDefault();
                return false;
            }
            
            if (stok < 0) {
                alert('Stok tidak boleh negatif');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>

</html>