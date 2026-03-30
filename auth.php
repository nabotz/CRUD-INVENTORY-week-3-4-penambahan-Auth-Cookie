<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Cek cookie remember me untuk auto-login
    if (isset($_COOKIE['remember_token']) && isset($_COOKIE['remember_user'])) {
        include_once __DIR__ . '/koneksi.php';

        $tokenHash = hash('sha256', $_COOKIE['remember_token']);
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ? AND remember_token = ? LIMIT 1");
        $stmt->execute([$_COOKIE['remember_user'], $tokenHash]);
        $user = $stmt->fetch();

        if ($user) {
            // Auto-login berhasil, set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['foto'] = $user['foto'];
        } else {
            // Cookie tidak valid, hapus
            setcookie("remember_token", "", time() - 3600, "/");
            setcookie("remember_user", "", time() - 3600, "/");
            header('Location: index.php');
            exit;
        }
    } else {
        header('Location: index.php');
        exit;
    }
}
?>