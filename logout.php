<?php
session_start();
include 'koneksi.php';

// Hapus remember token dari database
if (isset($_SESSION['user_id'])) {
    $stmt = $koneksi->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Hapus cookie remember me
setcookie("remember_token", "", time() - 3600, "/");
setcookie("remember_user", "", time() - 3600, "/");

session_destroy();
header('Location: index.php');
exit;
?>