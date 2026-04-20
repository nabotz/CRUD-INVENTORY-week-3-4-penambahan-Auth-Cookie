<?php
require_once '../auth.php';
include "../koneksi.php";

$id_supplier = $_POST['id_supplier'];
$kode_produk = $_POST['kode_produk'];
$tgl_transaksi = $_POST['tgl_transaksi'];
$tgl_kadaluarsa = !empty($_POST['tgl_kadaluarsa']) ? $_POST['tgl_kadaluarsa'] : null;
$jumlah = (int) $_POST['jumlah'];

// Get harga satuan dari kategori produk
$sql_harga = "SELECT k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori WHERE p.kode_produk = ?";
$stmt_harga = $koneksi->prepare($sql_harga);
$stmt_harga->execute([$kode_produk]);
$row = $stmt_harga->fetch();
$harga_satuan = $row['harga_satuan'] ?? 0;
$stmt_harga = null;

$total_nilai = $jumlah * $harga_satuan;

$sql = "INSERT INTO transaksi_stok (id_supplier, kode_produk, tgl_transaksi, tgl_kadaluarsa, jumlah, total_nilai) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id_supplier, $kode_produk, $tgl_transaksi, $tgl_kadaluarsa, $jumlah, $total_nilai]);
    header('Location: TampilRiwayatStok.php');
} catch (\PDOException $e) {
    error_log("Error insert transaksi stok: " . $e->getMessage());
    header('Location: TampilRiwayatStok.php?error=1');
}

$stmt = null;
exit;
?>
