<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if (isset($_SESSION['register_error'])) {
    $error = $_SESSION['register_error'];
    unset($_SESSION['register_error']);
}
if (isset($_SESSION['register_success'])) {
    $success = $_SESSION['register_success'];
    unset($_SESSION['register_success']);
}

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Register - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>
    <div class="auth-container register">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">📦</div>
                <h1 class="auth-title">Buat Akun Baru</h1>
                <p class="auth-subtitle">Sistem Manajemen Inventori</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form action="SimpanRegister.php" method="POST" enctype="multipart/form-data" id="formRegister">
                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <div class="file-input-wrapper">
                        <input type="file" name="foto" accept="image/*" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" minlength="3" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="4-20 karakter, huruf dan angka" pattern="[a-zA-Z0-9]{4,20}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 karakter" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password" minlength="6" required>
                    </div>
                </div>

                <button type="submit" class="btn-auth">Daftar</button>
            </form>

            <div class="auth-link">
                Sudah punya akun? <a href="index.php">Masuk disini</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('formRegister').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm_password').value;
            if (password !== confirm) {
                alert('Password dan Konfirmasi Password harus sama!');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>