<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$id = bersih($_POST['id'] ?? '');
$nama = bersih($_POST['nama'] ?? '');
$alamat = bersih($_POST['alamat'] ?? '');
$no_telp = bersih($_POST['no_telp'] ?? '');
$no_npwp = bersih($_POST['no_npwp'] ?? '');
$jenis_supplier = bersih($_POST['jenis_supplier'] ?? '');

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
