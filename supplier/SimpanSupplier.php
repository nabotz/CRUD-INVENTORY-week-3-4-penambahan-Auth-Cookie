<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$nama = bersih($_POST['nama'] ?? '');
$alamat = bersih($_POST['alamat'] ?? '');
$no_telp = bersih($_POST['no_telp'] ?? '');
$no_npwp = bersih($_POST['no_npwp'] ?? '');
$jenis_supplier = bersih($_POST['jenis_supplier'] ?? '');

$sql = "INSERT INTO supplier (nama, alamat, no_telp, no_npwp, jenis_supplier) VALUES (?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$nama, $alamat, $no_telp, $no_npwp, $jenis_supplier]);
    header('Location: TampilSupplier.php');
} catch (\PDOException $e) {
    error_log("Error insert supplier: " . $e->getMessage());
    header('Location: TampilSupplier.php?error=1');
}

$stmt = null;
exit;
?>
