<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilRiwayatStok.php');
    exit;
}

$sql = "DELETE FROM transaksi_stok WHERE id_transaksi = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id]);
    header('Location: TampilRiwayatStok.php');
} catch (\PDOException $e) {
    error_log("Error hapus transaksi stok: " . $e->getMessage());
    header('Location: TampilRiwayatStok.php?error=1');
}

$stmt = null;
exit;
?>
