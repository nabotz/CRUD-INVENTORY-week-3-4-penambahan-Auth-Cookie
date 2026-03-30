<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'user';

$result = $koneksi->query("SELECT * FROM users ORDER BY nama")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Data User - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <h1 class="page-title">Pengaturan User</h1>
                <div class="header-right">
                    <a href="TambahUser.php" class="btn btn-primary">+ Tambah User</a>
                </div>
            </header>

            <div class="content">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">Terjadi kesalahan saat menyimpan data.</div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar User</h3>
                        <span style="color: var(--text-gray); font-size: 14px;">Total: <?= count($result) ?>
                            user</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($result as $row): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><img src="uploads/<?= htmlspecialchars($row['foto']) ?>"
                                                alt="<?= htmlspecialchars($row['nama']) ?>"></td>
                                        <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="actions">
                                            <a href="KoreksiUser.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <a href="HapusUser.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus user ini?')">Hapus</a>
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