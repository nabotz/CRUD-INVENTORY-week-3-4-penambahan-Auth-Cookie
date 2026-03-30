<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'produk';

$kategori = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah Produk - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <div>
                    <div class="breadcrumb">
                        <a href="TampilProduk.php">Produk</a>
                        <span>→</span>
                        <span>Tambah Baru</span>
                    </div>
                    <h1 class="page-title">Tambah Produk Baru</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanProduk.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" required
                                pattern="[A-Za-z0-9]{1,10}" placeholder="Contoh: P001, ELK01">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori</label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $k): ?>
                                    <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?> - Rp
                                        <?= number_format($k['harga_satuan'], 0, ',', '.') ?>/unit</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required maxlength="20"
                                placeholder="Contoh: Rak A-1, Gudang B">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Produk</button>
                            <a href="TampilProduk.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
