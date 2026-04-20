<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'produk';

$result = $koneksi->query("SELECT p.*, k.nama_kategori, k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori ORDER BY p.kode_produk")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data Produk - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Data Produk</h1>
                <div class="header-right">
                    <a href="TambahProduk.php" class="btn btn-primary">+ Tambah Produk</a>
                </div>
            </header>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Produk</h3>
                        <span style="color: var(--text-gray); font-size: 14px;">Total: <?= count($result) ?>
                            produk</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Harga Satuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['kode_produk']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                        <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                                        <td class="actions">
                                            <a href="KoreksiProduk.php?id=<?= $row['kode_produk'] ?>"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <a href="HapusProduk.php?id=<?= $row['kode_produk'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus produk ini?')">Hapus</a>
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
