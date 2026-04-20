<?php
require_once '../auth.php';
include "../koneksi.php";
include_once '../includes/image_helper.php';

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilKategori.php');
    exit;
}

$sql_foto = "SELECT foto FROM kategori WHERE id_kategori = ?";
$stmt_foto = $koneksi->prepare($sql_foto);
$stmt_foto->execute([$id]);
$data = $stmt_foto->fetch();

// Hapus foto utama + thumbnail (thumbs/) sekaligus
if (!empty($data['foto'])) {
    hapus_gambar($data['foto'], 'uploads/');
}

$sql = "DELETE FROM kategori WHERE id_kategori = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id]);
    header('Location: TampilKategori.php');
} catch (\PDOException $e) {
    error_log("Error hapus kategori: " . $e->getMessage());
    header('Location: TampilKategori.php?error=1');
}

$stmt = null;
$koneksi = null;
exit;
?>
