<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'user';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah User - Hotel System</title>
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
                        <span>Tambah Baru</span>
                    </div>
                    <h1 class="page-title">Tambah User Baru</h1>
                </div>
            </header>

            <div class="content">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger" style="max-width: 600px;">
                        <?php
                        switch ($_GET['error']) {
                            case 'username':
                                echo 'Username sudah digunakan!';
                                break;
                            case 'password':
                                echo 'Password tidak cocok!';
                                break;
                            case 'foto':
                                echo 'Upload foto gagal!';
                                break;
                            case 'format':
                                echo 'Format gambar tidak valid!';
                                break;
                            default:
                                echo 'Terjadi kesalahan!';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="card" style="max-width: 600px;">
                    <form action="SimpanUser.php" method="POST" enctype="multipart/form-data" id="formUser">
                        <div class="form-group">
                            <label class="form-label">Foto</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required pattern="[a-zA-Z0-9]{4,20}"
                                placeholder="4-20 karakter alfanumerik">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required minlength="3"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="Masukkan email">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                minlength="6" placeholder="Minimal 6 karakter">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                required minlength="6" placeholder="Ulangi password">
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan User</button>
                            <a href="TampilUser.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('formUser').addEventListener('submit', function (e) {
            if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                alert('Password dan Konfirmasi tidak sama!');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>