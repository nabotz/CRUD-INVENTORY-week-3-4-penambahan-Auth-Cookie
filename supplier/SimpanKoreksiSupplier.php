<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$no_telp = $_POST['no_telp'];
$no_npwp = $_POST['no_npwp'];
$jenis_supplier = $_POST['jenis_supplier'];

$sql = "UPDATE supplier SET nama = ?, alamat = ?, no_telp = ?, no_npwp = ?, jenis_supplier = ? WHERE id_supplier = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$nama, $alamat, $no_telp, $no_npwp, $jenis_supplier, $id]);
    header('Location: TampilSupplier.php');
} catch (\PDOException $e) {
    error_log("Error update supplier: " . $e->getMessage());
    header('Location: TampilSupplier.php?error=1');
}

$stmt = null;
exit;
?>
