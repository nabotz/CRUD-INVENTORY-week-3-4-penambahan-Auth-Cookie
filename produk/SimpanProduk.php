<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$kode_produk = bersih($_POST['kode_produk'] ?? '');
$nama_produk = bersih($_POST['nama_produk'] ?? '');
$id_kategori = bersih($_POST['id_kategori'] ?? '');
$lokasi = bersih($_POST['lokasi'] ?? '');

$sql = "INSERT INTO produk (kode_produk, nama_produk, id_kategori, lokasi) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$kode_produk, $nama_produk, $id_kategori, $lokasi]);
    header('Location: TampilProduk.php');
} catch (\PDOException $e) {
    error_log("Error insert produk: " . $e->getMessage());
    header('Location: TampilProduk.php?error=1');
}

$stmt = null;
exit;
?>
