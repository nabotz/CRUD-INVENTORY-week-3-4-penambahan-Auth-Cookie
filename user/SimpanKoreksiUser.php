<?php
require_once '../auth.php';
csrf_check();
include "../koneksi.php";
include_once '../includes/image_helper.php';

/* ================== FUNGSI SANITASI ================== */
function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/* ================== AMBIL DATA FORM ================== */
$id = bersih($_POST['id'] ?? '');
$username = bersih($_POST['username'] ?? '');
$nama = bersih($_POST['nama'] ?? '');
$email = bersih($_POST['email'] ?? '');
$password = bersih($_POST['password'] ?? '');
$foto_lama = $_POST['foto_lama'] ?? '';

if (empty($id)) {
    header('Location: TampilUser.php?error=id');
    exit;
}

/* ================== FOTO ================== */
$xfoto = $foto_lama;

// Jika user upload foto baru
if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] == 0) {
    $tmpFile = $_FILES['foto']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        header('Location: KoreksiUser.php?id=' . urlencode($id) . '&error=ukuran');
        exit;
    }

    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
        header('Location: KoreksiUser.php?id=' . urlencode($id) . '&error=format');
        exit;
    }

    $mime = mime_content_type($tmpFile);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/gif']) || !getimagesize($tmpFile)) {
        header('Location: KoreksiUser.php?id=' . urlencode($id) . '&error=format');
        exit;
    }

    $namaFotoBaru = proses_gambar($_FILES['foto'], 'uploads/');
    if ($namaFotoBaru) {
        hapus_gambar($foto_lama, 'uploads/');
        $xfoto = $namaFotoBaru;
    }
}

/* ================== UPDATE DATABASE ================== */
try {
    if (!empty($password)) {
        // Update dengan password baru
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET username=?, nama=?, email=?, password=?, foto=? WHERE id=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->execute([$username, $nama, $email, $hashed_password, $xfoto, $id]);
    } else {
        // Update tanpa password
        $sql = "UPDATE users SET username=?, nama=?, email=?, foto=? WHERE id=?";
        $stmt = $koneksi->prepare($sql);
        $stmt->execute([$username, $nama, $email, $xfoto, $id]);
    }
    
    header('Location: TampilUser.php');
} catch (\PDOException $e) {
    error_log("Error update user: " . $e->getMessage());
    header('Location: TampilUser.php?error=1');
}

$stmt = null;
$koneksi = null;
exit;
?>