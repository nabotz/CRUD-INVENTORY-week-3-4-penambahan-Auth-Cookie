<?php
session_start();
include 'koneksi.php';
include_once 'includes/csrf.php';
include_once 'includes/image_helper.php';
csrf_check();

/* ================== FUNGSI SANITASI ================== */
function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/* ================== AMBIL DATA FORM ================== */
$username = bersih($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
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

if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
    $_SESSION['register_error'] = 'Ukuran file maksimal 2MB!';
    header('Location: register.php');
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpFile);
finfo_close($finfo);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif'])) {
    $_SESSION['register_error'] = 'Tipe file tidak valid!';
    header('Location: register.php');
    exit;
}
if (!getimagesize($tmpFile)) {
    $_SESSION['register_error'] = 'File bukan gambar yang valid!';
    header('Location: register.php');
    exit;
}

$namaFotoBaru = proses_gambar($_FILES['foto'], 'user/uploads/');
if (!$namaFotoBaru) {
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
    error_log("Error register: " . $e->getMessage());
    $_SESSION['register_error'] = 'Registrasi gagal, coba lagi.';
}

$stmt = null;
$koneksi = null;

header('Location: register.php');
?>