<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'riwayat_stok';

$id = $_GET['id'] ?? '';
$stmt = $koneksi->prepare("SELECT * FROM transaksi_stok WHERE id_transaksi = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: TampilRiwayatStok.php');
    exit;
}

$supplier_list = $koneksi->query("SELECT * FROM supplier ORDER BY nama")->fetchAll();
$produk_list = $koneksi->query("SELECT p.kode_produk, k.nama_kategori, k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Transaksi Stok - Sistem Inventori</title>
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
                        <a href="TampilRiwayatStok.php">Riwayat Stok</a>
                        <span>→</span>
                        <span>Edit</span>
                    </div>
                    <h1 class="page-title">Edit Transaksi Stok</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanKoreksiTransaksiStok.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id_transaksi'] ?>">

                        <div class="form-group">
                            <label class="form-label">Supplier</label>
                            <select name="id_supplier" class="form-control" required>
                                <?php foreach ($supplier_list as $s): ?>
                                    <option value="<?= $s['id_supplier'] ?>" <?= $s['id_supplier'] == $row['id_supplier'] ? 'selected' : '' ?>><?= htmlspecialchars($s['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Produk</label>
                            <select name="kode_produk" class="form-control" required>
                                <?php foreach ($produk_list as $p): ?>
                                    <option value="<?= $p['kode_produk'] ?>" <?= $p['kode_produk'] == $row['kode_produk'] ? 'selected' : '' ?>><?= $p['kode_produk'] ?> - <?= htmlspecialchars($p['nama_kategori']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tgl_transaksi" class="form-control" required
                                value="<?= $row['tgl_transaksi'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluarsa (opsional)</label>
                            <input type="date" name="tgl_kadaluarsa" class="form-control"
                                value="<?= $row['tgl_kadaluarsa'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah (unit)</label>
                            <input type="number" name="jumlah" class="form-control" required min="1"
                                value="<?= $row['jumlah'] ?>">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="TampilRiwayatStok.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
