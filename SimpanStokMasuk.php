<?php
require_once 'auth.php';
csrf_check();
include 'koneksi.php';

function bersih($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$tipe_supplier = bersih($_POST['tipe_supplier'] ?? '');
$kode_produk = bersih($_POST['kode_produk'] ?? '');
$tgl_transaksi = bersih($_POST['tgl_transaksi'] ?? '');
$tgl_kadaluarsa = !empty($_POST['tgl_kadaluarsa']) ? bersih($_POST['tgl_kadaluarsa']) : null;
$jumlah = (int) ($_POST['jumlah'] ?? 0);

if ($jumlah <= 0) {
    $_SESSION['stok_masuk_error'] = 'Jumlah harus lebih dari 0!';
    header('Location: stok_masuk.php');
    exit;
}

// Tentukan id_supplier
if ($tipe_supplier == 'baru') {
    $nama = bersih($_POST['nama'] ?? '');
    $alamat = bersih($_POST['alamat'] ?? '');
    $no_telp = bersih($_POST['no_telp'] ?? '');
    $no_npwp = bersih($_POST['no_npwp'] ?? '');
    $jenis_supplier = bersih($_POST['jenis_supplier'] ?? '');

    $sql = "INSERT INTO supplier (nama, alamat, no_telp, no_npwp, jenis_supplier) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);

    try {
        $stmt->execute([$nama, $alamat, $no_telp, $no_npwp, $jenis_supplier]);
        $id_supplier = $koneksi->lastInsertId();
    } catch (\PDOException $e) {
        error_log("Error insert supplier: " . $e->getMessage());
        $_SESSION['stok_masuk_error'] = 'Gagal menambahkan supplier baru!';
        header('Location: stok_masuk.php');
        exit;
    }
    $stmt = null;
} else {
    $id_supplier = (int) ($_POST['id_supplier'] ?? 0);
}

// Get harga satuan dari kategori produk
$sql_harga = "SELECT k.harga_satuan FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori WHERE p.kode_produk = ?";
$stmt_harga = $koneksi->prepare($sql_harga);
$stmt_harga->execute([$kode_produk]);
$row = $stmt_harga->fetch();
$harga_satuan = $row['harga_satuan'] ?? 0;
$stmt_harga = null;

$total_nilai = $jumlah * $harga_satuan;

// Insert transaksi stok
$sql = "INSERT INTO transaksi_stok (id_supplier, kode_produk, tgl_transaksi, tgl_kadaluarsa, jumlah, total_nilai) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

try {
    $stmt->execute([$id_supplier, $kode_produk, $tgl_transaksi, $tgl_kadaluarsa, $jumlah, $total_nilai]);
    $_SESSION['stok_masuk_success'] = 'Stok masuk berhasil disimpan! Total: Rp ' . number_format($total_nilai, 0, ',', '.') . ' (' . $jumlah . ' unit)';
} catch (\PDOException $e) {
    error_log("Error insert transaksi stok: " . $e->getMessage());
    $_SESSION['stok_masuk_error'] = 'Gagal menyimpan transaksi stok!';
}

$stmt = null;
header('Location: stok_masuk.php');
exit;
?>
