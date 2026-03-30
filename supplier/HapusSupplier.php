<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilSupplier.php');
    exit;
}

$sql = "DELETE FROM supplier WHERE id_supplier = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id]);
    header('Location: TampilSupplier.php');
} catch (\PDOException $e) {
    error_log("Error hapus supplier: " . $e->getMessage());
    header('Location: TampilSupplier.php?error=1');
}

$stmt = null;
exit;
?>
