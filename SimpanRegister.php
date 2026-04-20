<?php
session_start();
include 'koneksi.php';

/* ================== FUNGSI SANITASI ================== */
function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/* ================== AMBIL DATA FORM ================== */
$username = bersih($_POST['username'] ?? '');
$password = bersih($_POST['password'] ?? '');
$confirm_password = bersih($_POST['confirm_password'] ?? '');
$nama = bersih($_POST['nama'] ?? '');

/* ================== VALIDASI PASSWORD ================== */
if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'Password tidak cocok!';
    header('Location: register.php');
    exit;
}

/* ================== CEK USERNAME ================== */
$stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);

if ($stmt->fetch()) {
    $_SESSION['register_error'] = 'Username sudah digunakan!';
    header('Location: register.php');
    exit;
}

/* ================== PROSES UPLOAD GAMBAR ================== */
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
    $_SESSION['register_error'] = 'Upload foto gagal!';
    header('Location: register.php');
    exit;
}

$namaFile = $_FILES['foto']['name'];
$tmpFile = $_FILES['foto']['tmp_name'];
$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowedExt)) {
    $_SESSION['register_error'] = 'Format gambar tidak diizinkan!';
    header('Location: register.php');
    exit;
}

$namaFotoBaru = uniqid("foto_") . "." . $ext;
$folderUpload = "user/uploads/";

// Buat folder jika belum ada
if (!file_exists($folderUpload)) {
    mkdir($folderUpload, 0777, true);
}

if (!move_uploaded_file($tmpFile, $folderUpload . $namaFotoBaru)) {
    $_SESSION['register_error'] = 'Gagal menyimpan foto!';
    header('Location: register.php');
    exit;
}

/* ================== SIMPAN KE DATABASE ================== */
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, password, nama, foto) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$username, $hashed_password, $nama, $namaFotoBaru]);
    $_SESSION['register_success'] = 'Registrasi berhasil! Silakan login.';
} catch (\PDOException $e) {
    $_SESSION['register_error'] = 'Error: ' . $e->getMessage();
}

$stmt = null;
$koneksi = null;

header('Location: register.php');
?>