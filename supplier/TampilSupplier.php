<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'supplier';

$result = $koneksi->query("SELECT * FROM supplier ORDER BY nama")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data Supplier - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Data Supplier</h1>
                <div class="header-right">
                    <a href="TambahSupplier.php" class="btn btn-primary">+ Tambah Supplier</a>
                </div>
            </header>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Supplier</h3>
                        <span style="color: var(--text-gray); font-size: 14px;">Total: <?= count($result) ?>
                            supplier</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No Telp</th>
                                    <th>No NPWP</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                                        <td><?= htmlspecialchars($row['no_telp']) ?></td>
                                        <td><?= htmlspecialchars($row['no_npwp']) ?></td>
                                        <td><?= htmlspecialchars($row['jenis_supplier']) ?></td>
                                        <td class="actions">
                                            <a href="KoreksiSupplier.php?id=<?= $row['id_supplier'] ?>"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <a href="HapusSupplier.php?id=<?= $row['id_supplier'] ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus supplier ini?')">Hapus</a>
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
