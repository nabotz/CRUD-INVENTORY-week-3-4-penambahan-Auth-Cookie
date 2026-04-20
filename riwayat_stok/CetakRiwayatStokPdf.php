<?php
require __DIR__ . '/fpdf186/fpdf.php';
require '../koneksi.php';

class PDF extends FPDF
{
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? date('Y');
$bulan_nama = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$sql = "SELECT ts.*, s.nama, p.kode_produk as kode, k.nama_kategori
        FROM transaksi_stok ts
        JOIN supplier s ON ts.id_supplier = s.id_supplier
        JOIN produk p ON ts.kode_produk = p.kode_produk
        JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE YEAR(ts.tgl_transaksi) = '$tahun'";

if (!empty($bulan)) {
    $sql .= " AND MONTH(ts.tgl_transaksi) = '$bulan'";
}
$sql .= " ORDER BY ts.tgl_transaksi DESC";

$result = $koneksi->query($sql)->fetchAll();

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(277, 10, 'Laporan Riwayat Stok Masuk', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
if (!empty($bulan)) {
    $periode = "Periode: " . $bulan_nama[(int) $bulan] . " " . $tahun;
} else {
    $periode = "Periode: Tahun " . $tahun;
}
$pdf->Cell(277, 8, $periode, 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(277, 8, 'Tanggal Cetak: ' . date('d-m-Y H:i:s'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(10, 10, 'No', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Supplier', 1, 0, 'C', 1);
$pdf->Cell(25, 10, 'Kode Produk', 1, 0, 'C', 1);
$pdf->Cell(35, 10, 'Kategori', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Tgl Transaksi', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Tgl Kadaluarsa', 1, 0, 'C', 1);
$pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Total Nilai', 1, 1, 'C', 1);

$pdf->SetFont('Arial', '', 10);
$no = 1;
$grandTotal = 0;

foreach ($result as $row) {
    $pdf->Cell(10, 8, $no, 1, 0, 'C');
    $pdf->Cell(50, 8, $row['nama'], 1, 0, 'L');
    $pdf->Cell(25, 8, $row['kode'], 1, 0, 'C');
    $pdf->Cell(35, 8, $row['nama_kategori'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['tgl_transaksi'], 1, 0, 'C');
    $pdf->Cell(30, 8, $row['tgl_kadaluarsa'] ?? '-', 1, 0, 'C');
    $pdf->Cell(20, 8, $row['jumlah'] . ' unit', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Rp ' . number_format($row['total_nilai'], 0, ',', '.'), 1, 1, 'R');
    $grandTotal += $row['total_nilai'];
    $no++;
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(200, 10, 'GRAND TOTAL', 1, 0, 'R', 1);
$pdf->Cell(40, 10, 'Rp ' . number_format($grandTotal, 0, ',', '.'), 1, 1, 'R', 1);

$pdf->Output();
?>
