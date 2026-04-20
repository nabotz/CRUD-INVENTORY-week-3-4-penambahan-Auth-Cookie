<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'kategori';

$result = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Kategori - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Kategori Produk</h1>
                <div class="header-right">
                    <a href="TambahKategori.php" class="btn btn-primary">+ Tambah Kategori</a>
                </div>
            </header>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Kategori</h3>
                        <span style="color: var(--text-gray); font-size: 14px;">Total: <?= count($result) ?>
                            kategori</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama Kategori</th>
                                    <th>Harga Satuan</th>
                                    <th>Stok Minimum</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($row['foto'])): ?>
                                                <img src="uploads/<?= htmlspecialchars($row['foto']) ?>"
                                                    alt="<?= htmlspecialchars($row['nama_kategori']) ?>">
                                            <?php else: ?>
                                                <span style="color:#94a3b8;">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($row['nama_kategori']) ?></strong></td>
                                        <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                                        <td><?= $row['stok_minimum'] ?> unit</td>
                                        <td class="actions">
                                            <a href="KoreksiKategori.php?id=<?= $row['id_kategori'] ?>"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <a href="HapusKategori.php?id=<?= $row['id_kategori'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus kategori ini?')">Hapus</a>
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
