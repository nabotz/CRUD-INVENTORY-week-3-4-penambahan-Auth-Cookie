<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$id = bersih($_POST['id'] ?? '');
$nama_produk = bersih($_POST['nama_produk'] ?? '');
$id_kategori = bersih($_POST['id_kategori'] ?? '');
$lokasi = bersih($_POST['lokasi'] ?? '');

$sql = "UPDATE produk SET nama_produk = ?, id_kategori = ?, lokasi = ? WHERE kode_produk = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$nama_produk, $id_kategori, $lokasi, $id]);
    header('Location: TampilProduk.php');
} catch (\PDOException $e) {
    error_log("Error update produk: " . $e->getMessage());
    header('Location: TampilProduk.php?error=1');
}

$stmt = null;
exit;
?>
