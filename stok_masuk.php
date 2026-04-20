<?php
require_once 'auth.php';
include 'koneksi.php';

$base_url = '';
$current_page = 'stok_masuk';
$today = date('Y-m-d');

$produk_list = $koneksi->query("SELECT p.kode_produk, k.nama_kategori, k.harga_satuan, p.lokasi FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk")->fetchAll();
$supplier_list = $koneksi->query("SELECT * FROM supplier ORDER BY nama")->fetchAll();

$error = isset($_SESSION['stok_masuk_error']) ? $_SESSION['stok_masuk_error'] : '';
$success = isset($_SESSION['stok_masuk_success']) ? $_SESSION['stok_masuk_success'] : '';
unset($_SESSION['stok_masuk_error'], $_SESSION['stok_masuk_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Stok Masuk - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Stok Masuk</h1>
            </header>

            <div class="content">
                <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form action="SimpanStokMasuk.php" method="POST" id="formStokMasuk">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <!-- Data Supplier -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Supplier</h3>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tipe Supplier</label>
                                <div style="display: flex; gap: 20px;">
                                    <label><input type="radio" name="tipe_supplier" value="baru" id="supplier_baru" checked onclick="toggleSupplier()"> Supplier Baru</label>
                                    <label><input type="radio" name="tipe_supplier" value="existing" id="supplier_existing" onclick="toggleSupplier()"> Supplier Terdaftar</label>
                                </div>
                            </div>

                            <div id="form_baru">
                                <div class="form-group">
                                    <label class="form-label">Nama Supplier</label>
                                    <input type="text" name="nama" id="nama" class="form-control" minlength="3" placeholder="Masukkan nama supplier">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="2" placeholder="Masukkan alamat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No Telepon</label>
                                    <input type="tel" name="no_telp" id="no_telp" class="form-control" placeholder="021-12345678">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No NPWP</label>
                                    <input type="text" name="no_npwp" id="no_npwp" class="form-control" placeholder="Opsional">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Jenis Supplier</label>
                                    <select name="jenis_supplier" class="form-control">
                                        <option value="Distributor">Distributor</option>
                                        <option value="Produsen">Produsen</option>
                                        <option value="Agen">Agen</option>
                                        <option value="Importir">Importir</option>
                                    </select>
                                </div>
                            </div>

                            <div id="form_existing" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">Pilih Supplier</label>
                                    <select name="id_supplier" id="id_supplier" class="form-control">
                                        <option value="">-- Pilih Supplier --</option>
                                        <?php foreach ($supplier_list as $s): ?>
                                        <option value="<?= $s['id_supplier'] ?>"><?= htmlspecialchars($s['nama']) ?> (<?= $s['jenis_supplier'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Data Stok -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Stok Masuk</h3>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Transaksi</label>
                                <input type="date" name="tgl_transaksi" class="form-control" required value="<?= $today ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Kadaluarsa (opsional)</label>
                                <input type="date" name="tgl_kadaluarsa" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Pilih Produk</label>
                                <select name="kode_produk" id="kode_produk" class="form-control" required onchange="updateHarga()">
                                    <option value="">-- Pilih Produk --</option>
                                    <?php foreach ($produk_list as $p): ?>
                                    <option value="<?= $p['kode_produk'] ?>" data-harga="<?= $p['harga_satuan'] ?>">
                                        <?= $p['kode_produk'] ?> - <?= htmlspecialchars($p['nama_kategori']) ?> (<?= htmlspecialchars($p['lokasi']) ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Jumlah (unit)</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" required min="1"
                                    placeholder="Masukkan jumlah" oninput="updateTotal()">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" id="display_harga" class="form-control" readonly
                                    style="background: #f5f5f5;" value="-">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Total Nilai</label>
                                <input type="text" id="display_total" class="form-control" readonly
                                    style="background: #f5f5f5; font-weight: 700; color: #84cc16;" value="-">
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                                Simpan Stok Masuk
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function toggleSupplier() {
            var isBaru = document.getElementById('supplier_baru').checked;
            document.getElementById('form_baru').style.display = isBaru ? 'block' : 'none';
            document.getElementById('form_existing').style.display = isBaru ? 'none' : 'block';

            document.getElementById('nama').required = isBaru;
            document.getElementById('no_telp').required = isBaru;
            document.getElementById('id_supplier').required = !isBaru;
        }

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

        toggleSupplier();
    </script>
</body>
</html>
