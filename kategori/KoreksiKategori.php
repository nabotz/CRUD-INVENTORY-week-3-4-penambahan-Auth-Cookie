<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'kategori';

$id = $_GET['id'] ?? '';
$stmt = $koneksi->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: TampilKategori.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Kategori - Sistem Inventori</title>
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
                        <span>Edit</span>
                    </div>
                    <h1 class="page-title">Edit Kategori</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <?php if (!empty($row['foto'])): ?>
                    <div style="margin-bottom: 20px;">
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt=""
                            style="max-width: 200px; border-radius: 10px;">
                    </div>
                    <?php endif; ?>

                    <form action="SimpanKoreksiKategori.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="id" value="<?= $row['id_kategori'] ?>">
                        <input type="hidden" name="foto_lama" value="<?= $row['foto'] ?>">

                        <div class="form-group">
                            <label class="form-label">Foto Baru (opsional)</label>
                            <input type="file" name="foto_kamar" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" required
                                value="<?= htmlspecialchars($row['nama_kategori']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Harga Satuan (Rp)</label>
                            <input type="number" name="harga_satuan" class="form-control" required min="1"
                                value="<?= $row['harga_satuan'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control" required min="1"
                                value="<?= $row['stok_minimum'] ?>">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="TampilKategori.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
