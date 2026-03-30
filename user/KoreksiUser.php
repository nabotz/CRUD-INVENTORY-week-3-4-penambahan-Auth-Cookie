<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'user';

$id = $_GET['id'] ?? '';
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: TampilUser.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit User - Hotel System</title>
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
                        <a href="TampilUser.php">User</a>
                        <span>→</span>
                        <span>Edit</span>
                    </div>
                    <h1 class="page-title">Edit User</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <div style="margin-bottom: 20px;">
                        <img src="uploads/<?= htmlspecialchars($row['foto']) ?>" alt=""
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    </div>

                    <form action="SimpanKoreksiUser.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="foto_lama" value="<?= $row['foto'] ?>">

                        <div class="form-group">
                            <label class="form-label">Foto Baru (opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required
                                value="<?= htmlspecialchars($row['username']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?= htmlspecialchars($row['nama']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($row['email']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Kosongkan jika tidak diubah">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="TampilUser.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>