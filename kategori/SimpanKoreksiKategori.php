<?php
require_once '../auth.php';
include "../koneksi.php";

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

if (!empty($_FILES['foto_kamar']['name'])) {
    $namaFile = $_FILES['foto_kamar']['name'];
    $tmpFile = $_FILES['foto_kamar']['tmp_name'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowedExt)) {
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $namaFotoBaru = uniqid("foto_") . "." . $ext;
        move_uploaded_file($tmpFile, "uploads/" . $namaFotoBaru);
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
