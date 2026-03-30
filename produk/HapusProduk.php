<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilProduk.php');
    exit;
}

$sql = "DELETE FROM produk WHERE kode_produk = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id]);
    header('Location: TampilProduk.php');
} catch (\PDOException $e) {
    error_log("Error hapus produk: " . $e->getMessage());
    header('Location: TampilProduk.php?error=1');
}

$stmt = null;
exit;
?>
