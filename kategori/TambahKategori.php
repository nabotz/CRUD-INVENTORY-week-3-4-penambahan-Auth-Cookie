<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'kategori';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah Kategori - Sistem Inventori</title>
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
                        <a href="TampilKategori.php">Kategori</a>
                        <span>→</span>
                        <span>Tambah Baru</span>
                    </div>
                    <h1 class="page-title">Tambah Kategori Baru</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanKategori.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="form-label">Foto Kategori</label>
                            <input type="file" name="foto_kamar" class="form-control" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" required minlength="3"
                                maxlength="50" placeholder="Contoh: Elektronik, Furnitur">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harga Satuan (Rp)</label>
                            <input type="number" name="harga_satuan" class="form-control" required min="1"
                                placeholder="Contoh: 500000">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control" required min="1"
                                placeholder="Batas minimum stok">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                            <a href="TampilKategori.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
