<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";
include_once '../includes/image_helper.php';

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$id = bersih($_POST['id'] ?? '');
$nama_kategori = bersih($_POST['nama_kategori'] ?? '');
$harga_satuan = bersih($_POST['harga_satuan'] ?? '');
$stok_minimum = bersih($_POST['stok_minimum'] ?? '');
$foto_lama = $_POST['foto_lama'] ?? '';

if (empty($id)) {
    header('Location: TampilKategori.php?error=id');
    exit;
}

$xfoto = $foto_lama;

if (!empty($_FILES['foto_kamar']['name']) && $_FILES['foto_kamar']['error'] == 0) {
    $tmpFile = $_FILES['foto_kamar']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['foto_kamar']['name'], PATHINFO_EXTENSION));

    if ($_FILES['foto_kamar']['size'] > 2 * 1024 * 1024) {
        header('Location: KoreksiKategori.php?id=' . urlencode($id) . '&error=ukuran');
        exit;
    }

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        header('Location: KoreksiKategori.php?id=' . urlencode($id) . '&error=format');
        exit;
    }

    $mime = mime_content_type($tmpFile);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif']) || !getimagesize($tmpFile)) {
        header('Location: KoreksiKategori.php?id=' . urlencode($id) . '&error=format');
        exit;
    }

    $namaFotoBaru = proses_gambar($_FILES['foto_kamar'], 'uploads/');
    if ($namaFotoBaru) {
        hapus_gambar($foto_lama, 'uploads/');
        $xfoto = $namaFotoBaru;
    }
}

$sql = "UPDATE kategori SET nama_kategori=?, harga_satuan=?, stok_minimum=?, foto=? WHERE id_kategori=?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$nama_kategori, $harga_satuan, $stok_minimum, $xfoto, $id]);
    header('Location: TampilKategori.php');
} catch (\PDOException $e) {
    error_log("Error update kategori: " . $e->getMessage());
    header('Location: TampilKategori.php?error=1');
}

$stmt = null;
$koneksi = null;
exit;
?>
