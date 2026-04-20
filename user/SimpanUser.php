<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";
include_once '../includes/image_helper.php';

/* ================== FUNGSI SANITASI ================== */
function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/* ================== AMBIL DATA FORM ================== */
$username = bersih($_POST['username'] ?? '');
$nama = bersih($_POST['nama'] ?? '');
$email = bersih($_POST['email'] ?? '');
$password = bersih($_POST['password'] ?? '');
$confirm_password = bersih($_POST['confirm_password'] ?? '');

/* ================== CEK USERNAME DUPLIKAT ================== */
$stmt_cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$stmt_cek->execute([$username]);

if ($stmt_cek->fetch()) {
    $stmt_cek = null;
    header('Location: TambahUser.php?error=username');
    exit;
}
$stmt_cek = null;

/* ================== VALIDASI PASSWORD ================== */
if ($password !== $confirm_password) {
    header('Location: TambahUser.php?error=password');
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

/* ================== PROSES UPLOAD GAMBAR ================== */
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] != 0) {
    header('Location: TambahUser.php?error=foto');
    exit;
}

if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
    header('Location: TambahUser.php?error=ukuran');
    exit;
}

$tmpFile = $_FILES['foto']['tmp_name'];
$ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
    header('Location: TambahUser.php?error=format');
    exit;
}

$mime = mime_content_type($tmpFile);
if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif']) || !getimagesize($tmpFile)) {
    header('Location: TambahUser.php?error=format');
    exit;
}

$namaFotoBaru = proses_gambar($_FILES['foto'], 'uploads/');
if (!$namaFotoBaru) {
    header('Location: TambahUser.php?error=upload');
    exit;
}

/* ================== SIMPAN KE DATABASE ================== */
$sql = "INSERT INTO users (username, password, nama, email, foto) VALUES (?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$username, $hashed_password, $nama, $email, $namaFotoBaru]);
    header('Location: TampilUser.php');
} catch (\PDOException $e) {
    error_log("Error insert user: " . $e->getMessage());
    header('Location: TampilUser.php?error=1');
}

$stmt = null;
$koneksi = null;
exit;
?>