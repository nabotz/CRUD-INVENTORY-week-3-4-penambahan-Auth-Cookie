<?php
require_once '../auth.php';
include "../koneksi.php";

$kode_produk = $_POST['kode_produk'];
$id_kategori = $_POST['id_kategori'];
$lokasi = $_POST['lokasi'];

$sql = "INSERT INTO produk (kode_produk, id_kategori, lokasi) VALUES (?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$kode_produk, $id_kategori, $lokasi]);
    header('Location: TampilProduk.php');
} catch (\PDOException $e) {
    error_log("Error insert produk: " . $e->getMessage());
    header('Location: TampilProduk.php?error=1');
}

$stmt = null;
exit;
?>
