<?php
session_start();
include 'koneksi.php';

// Cek jika sudah login
if (isset($_SESSION['username'])) {
    header("location:menu.php");
    exit;
}

// Proses login (harus sebelum output HTML)
$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['foto'] = $user['foto'];

            // Remember Me: set cookie jika di-centang
            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);
                $stmt = $koneksi->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->execute([$tokenHash, $user['id']]);
                setcookie("remember_token", $token, time() + (86400 * 30), "/", "", false, true);
                setcookie("remember_user", $user['id'], time() + (86400 * 30), "/", "", false, true);
            }

            header("location:menu.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Login - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">📦</div>
                <h1 class="auth-title">Selamat Datang</h1>
                <p class="auth-subtitle">Sistem Manajemen Inventori</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                        autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>
                <div class="form-group remember-me">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1"> Ingat Saya
                    </label>
                </div>
                <button type="submit" name="login" class="btn-auth">Masuk</button>
            </form>

            <div class="auth-link">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
        </div>
    </div>
</body>

</html>