<?php
require_once 'auth.php';
include 'koneksi.php';

header('Content-Type: application/json');

$sql = "SELECT p.kode_produk, p.lokasi, k.nama_kategori, k.harga_satuan, k.stok_minimum,
               COALESCE(SUM(ts.jumlah), 0) as stok_saat_ini
        FROM produk p
        JOIN kategori k ON p.id_kategori = k.id_kategori
        LEFT JOIN transaksi_stok ts ON p.kode_produk = ts.kode_produk
        GROUP BY p.kode_produk, p.lokasi, k.nama_kategori, k.harga_satuan, k.stok_minimum
        ORDER BY p.kode_produk";

$result = $koneksi->query($sql)->fetchAll();

$produk = [];
foreach ($result as $row) {
    $produk[] = [
        'kode_produk' => $row['kode_produk'],
        'nama_kategori' => $row['nama_kategori'],
        'lokasi' => $row['lokasi'],
        'harga_satuan' => $row['harga_satuan'],
        'harga_format' => number_format($row['harga_satuan'], 0, ',', '.'),
        'stok_minimum' => $row['stok_minimum'],
        'stok_saat_ini' => (int) $row['stok_saat_ini']
    ];
}

echo json_encode([
    'success' => true,
    'produk' => $produk
]);
?>
