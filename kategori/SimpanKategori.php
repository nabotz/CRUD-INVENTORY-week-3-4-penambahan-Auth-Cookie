<?php
require_once '../auth.php';
include "../koneksi.php";

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$nama_kategori = bersih($_POST['nama_kategori'] ?? '');
$harga_satuan = bersih($_POST['harga_satuan'] ?? '');
$stok_minimum = bersih($_POST['stok_minimum'] ?? '');

$namaFotoBaru = null;

if (isset($_FILES['foto_kamar']) && $_FILES['foto_kamar']['error'] == 0) {
    $namaFile = $_FILES['foto_kamar']['name'];
    $tmpFile = $_FILES['foto_kamar']['tmp_name'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowedExt)) {
        header('Location: TambahKategori.php?error=format');
        exit;
    }

    $namaFotoBaru = uniqid("foto_") . "." . $ext;
    $folderUpload = "uploads/";

    if (!file_exists($folderUpload)) {
        mkdir($folderUpload, 0777, true);
    }

    if (!move_uploaded_file($tmpFile, $folderUpload . $namaFotoBaru)) {
        header('Location: TambahKategori.php?error=upload');
        exit;
    }
}

$sql = "INSERT INTO kategori (nama_kategori, harga_satuan, stok_minimum, foto) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$nama_kategori, $harga_satuan, $stok_minimum, $namaFotoBaru]);
    header('Location: TampilKategori.php');
} catch (\PDOException $e) {
    error_log("Error insert kategori: " . $e->getMessage());
    header('Location: TampilKategori.php?error=1');
}

$stmt = null;
$koneksi = null;
exit;
?>
