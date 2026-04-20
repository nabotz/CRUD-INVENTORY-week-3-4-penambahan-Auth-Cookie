<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_POST['id'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];

$sql = "UPDATE produk SET id_kategori = ?, lokasi = ? WHERE kode_produk = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id_kategori, $lokasi, $id]);
    header('Location: TampilProduk.php');
} catch (\PDOException $e) {
    error_log("Error update produk: " . $e->getMessage());
    header('Location: TampilProduk.php?error=1');
}

$stmt = null;
exit;
?>
