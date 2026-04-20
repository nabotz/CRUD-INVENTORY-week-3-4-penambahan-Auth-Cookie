<?php
require_once '../auth.php';
include "../koneksi.php";

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

$namaFile = $_FILES['foto']['name'];
$tmpFile = $_FILES['foto']['tmp_name'];
$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
$allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $allowedExt)) {
    header('Location: TambahUser.php?error=format');
    exit;
}

$namaFotoBaru = uniqid("foto_") . "." . $ext;
$folderUpload = "uploads/";

if (!file_exists($folderUpload)) {
    mkdir($folderUpload, 0777, true);
}

if (!move_uploaded_file($tmpFile, $folderUpload . $namaFotoBaru)) {
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