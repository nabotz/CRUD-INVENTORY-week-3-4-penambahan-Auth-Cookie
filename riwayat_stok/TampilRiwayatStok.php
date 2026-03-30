<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'riwayat_stok';

$result = $koneksi->query(
    "SELECT ts.*, s.nama, p.kode_produk as kode, k.nama_kategori, k.harga_satuan
     FROM transaksi_stok ts
     JOIN supplier s ON ts.id_supplier = s.id_supplier
     JOIN produk p ON ts.kode_produk = p.kode_produk
     JOIN kategori k ON p.id_kategori = k.id_kategori
     ORDER BY ts.id_transaksi DESC"
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Riwayat Stok - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Riwayat Stok</h1>
                <div class="header-right" style="display: flex; gap: 12px; align-items: center;">
                    <form action="CetakRiwayatStokPdf.php" method="GET" target="_blank"
                        style="display: flex; gap: 8px; align-items: center;">
                        <select name="bulan" class="form-control" style="width: auto; padding: 8px 12px;">
                            <option value="">-- Semua Bulan --</option>
                            <?php
                            $bulan_nama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $bulan_query = $koneksi->query("SELECT DISTINCT MONTH(tgl_transaksi) as bulan FROM transaksi_stok ORDER BY bulan")->fetchAll();
                            foreach ($bulan_query as $b): ?>
                                <option value="<?= $b['bulan'] ?>"><?= $bulan_nama[$b['bulan']] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="tahun" class="form-control" style="width: auto; padding: 8px 12px;">
                            <?php
                            $tahun_query = $koneksi->query("SELECT DISTINCT YEAR(tgl_transaksi) as tahun FROM transaksi_stok ORDER BY tahun DESC")->fetchAll();
                            foreach ($tahun_query as $t): ?>
                                <option value="<?= $t['tahun'] ?>"><?= $t['tahun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-secondary">🖨️ Cetak PDF</button>
                    </form>
                    <a href="TambahTransaksiStok.php" class="btn btn-primary">+ Tambah Transaksi</a>
                </div>
            </header>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Riwayat Stok</h3>
                        <span style="color: var(--text-gray); font-size: 14px;">Total: <?= count($result) ?>
                            transaksi</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Supplier</th>
                                    <th>Kode Produk</th>
                                    <th>Kategori</th>
                                    <th>Tgl Transaksi</th>
                                    <th>Tgl Kadaluarsa</th>
                                    <th>Jumlah</th>
                                    <th>Total Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['kode']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($row['tgl_transaksi'])) ?></td>
                                        <td><?= $row['tgl_kadaluarsa'] ? date('d/m/Y', strtotime($row['tgl_kadaluarsa'])) : '-' ?></td>
                                        <td><?= $row['jumlah'] ?> unit</td>
                                        <td><strong style="color: var(--primary);">Rp
                                                <?= number_format($row['total_nilai'], 0, ',', '.') ?></strong></td>
                                        <td class="actions">
                                            <a href="KoreksiTransaksiStok.php?id=<?= $row['id_transaksi'] ?>"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <a href="HapusTransaksiStok.php?id=<?= $row['id_transaksi'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
