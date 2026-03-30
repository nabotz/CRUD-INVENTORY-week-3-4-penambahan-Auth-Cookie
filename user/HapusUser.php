<?php
require_once '../auth.php';
include "../koneksi.php";

$id = $_GET['id'] ?? '';

if (empty($id)) {
    header('Location: TampilUser.php');
    exit;
}

// Ambil data user untuk menghapus foto dengan prepared statement
$sql_foto = "SELECT foto FROM users WHERE id = ?";
$stmt_foto = $koneksi->prepare($sql_foto);
$stmt_foto->execute([$id]);
$data = $stmt_foto->fetch();

// Hapus foto jika ada
if (!empty($data['foto']) && file_exists('uploads/' . $data['foto'])) {
    unlink('uploads/' . $data['foto']);
}

// Hapus user dengan prepared statement
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id]);
    header('Location: TampilUser.php');
} catch (\PDOException $e) {
    error_log("Error hapus user: " . $e->getMessage());
    header('Location: TampilUser.php?error=1');
}

$stmt = null;
exit;
?>