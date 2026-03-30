<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'riwayat_stok';

$today = date('Y-m-d');
$supplier_list = $koneksi->query("SELECT * FROM supplier ORDER BY nama")->fetchAll();
$produk_list = $koneksi->query("SELECT p.kode_produk, k.nama_kategori, k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah Transaksi Stok - Sistem Inventori</title>
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
                        <span>Tambah Baru</span>
                    </div>
                    <h1 class="page-title">Tambah Transaksi Stok</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanTransaksiStok.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Supplier</label>
                            <select name="id_supplier" class="form-control" required>
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($supplier_list as $s): ?>
                                    <option value="<?= $s['id_supplier'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Produk</label>
                            <select name="kode_produk" id="kode_produk" class="form-control" required onchange="updateHarga()">
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($produk_list as $p): ?>
                                    <option value="<?= $p['kode_produk'] ?>"
                                        data-harga="<?= $p['harga_satuan'] ?>">
                                        <?= $p['kode_produk'] ?> - <?= htmlspecialchars($p['nama_kategori']) ?> (Rp <?= number_format($p['harga_satuan'], 0, ',', '.') ?>/unit)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="date" name="tgl_transaksi" class="form-control" required
                                value="<?= $today ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Kadaluarsa (opsional)</label>
                            <input type="date" name="tgl_kadaluarsa" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jumlah (unit)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1"
                                placeholder="Masukkan jumlah" oninput="updateTotal()">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Harga Satuan</label>
                            <input type="text" id="display_harga" class="form-control" readonly
                                style="background: #f1f5f9;" value="-">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Total Nilai</label>
                            <input type="text" id="display_total" class="form-control" readonly
                                style="background: #f1f5f9;" value="-">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                            <a href="TampilRiwayatStok.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function formatRupiah(angka) {
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }

        function updateHarga() {
            var select = document.getElementById('kode_produk');
            var selected = select.options[select.selectedIndex];
            var harga = selected.getAttribute('data-harga') || 0;

            document.getElementById('display_harga').value = harga > 0 ? formatRupiah(harga) : '-';
            updateTotal();
        }

        function updateTotal() {
            var select = document.getElementById('kode_produk');
            var selected = select.options[select.selectedIndex];
            var harga = parseFloat(selected.getAttribute('data-harga')) || 0;
            var jumlah = parseInt(document.getElementById('jumlah').value) || 0;

            var total = harga * jumlah;
            document.getElementById('display_total').value = total > 0 ? formatRupiah(total) : '-';
        }
    </script>
</body>

</html>
