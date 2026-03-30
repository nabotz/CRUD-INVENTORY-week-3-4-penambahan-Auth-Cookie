<?php
require_once 'auth.php';
include 'koneksi.php';

// Query statistik overview
$stok_masuk_hari_ini = $koneksi->query("SELECT COUNT(*) FROM transaksi_stok WHERE DATE(tgl_transaksi) = CURDATE()")->fetchColumn();
$stok_keluar_hari_ini = $koneksi->query("SELECT COUNT(*) FROM transaksi_stok WHERE DATE(tgl_kadaluarsa) = CURDATE()")->fetchColumn();
$total_transaksi = $koneksi->query("SELECT COUNT(*) FROM transaksi_stok")->fetchColumn();
$total_produk = $koneksi->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_supplier = $koneksi->query("SELECT COUNT(*) FROM supplier")->fetchColumn();

// Query kategori dengan jumlah produk dan harga
$kategori_list = $koneksi->query(
    "SELECT k.*, COUNT(p.kode_produk) as jumlah_produk
     FROM kategori k
     LEFT JOIN produk p ON k.id_kategori = p.id_kategori
     GROUP BY k.id_kategori
     ORDER BY k.nama_kategori"
)->fetchAll();

// Data nilai stok per bulan untuk chart (12 bulan terakhir)
$nilai_chart = $koneksi->query(
    "SELECT DATE_FORMAT(tgl_transaksi, '%Y-%m') as periode, MONTH(tgl_transaksi) as bulan, YEAR(tgl_transaksi) as tahun, COALESCE(SUM(total_nilai),0) as total
     FROM transaksi_stok
     WHERE tgl_transaksi >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
     GROUP BY YEAR(tgl_transaksi), MONTH(tgl_transaksi)
     ORDER BY YEAR(tgl_transaksi), MONTH(tgl_transaksi)"
)->fetchAll();

$bulan_nama = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$chart_labels = [];
$chart_data = [];
foreach ($nilai_chart as $row) {
    $chart_labels[] = $bulan_nama[$row['bulan']] . ' ' . $row['tahun'];
    $chart_data[] = (int) $row['total'];
}

// 5 Transaksi terbaru
$transaksi_terbaru = $koneksi->query(
    "SELECT ts.*, s.nama FROM transaksi_stok ts
     JOIN supplier s ON ts.id_supplier = s.id_supplier
     ORDER BY ts.id_transaksi DESC LIMIT 5"
)->fetchAll();

// Total nilai masuk bulan ini
$nilai_bulan = $koneksi->query(
    "SELECT COALESCE(SUM(total_nilai),0) FROM transaksi_stok
     WHERE MONTH(tgl_transaksi) = MONTH(CURDATE())"
)->fetchColumn();

// Total stok keseluruhan
$total_stok = $koneksi->query("SELECT COALESCE(SUM(jumlah),0) FROM transaksi_stok")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css">
    <script src="js/chart.js"></script>
</head>

<body>
    <div class="dashboard">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <span class="logo-text">Menu</span>
            </div>

            <ul class="nav-menu">
                <li><a href="menu.php" class="active"><img src="includes/images/dashboard.png" class="nav-icon" alt="">
                        Dashboard</a></li>
                <li><a href="stok_masuk.php"><img src="includes/images/transaksibaru.png" class="nav-icon" alt="">
                        Stok Masuk</a></li>
                <li><a href="supplier/TampilSupplier.php"><img src="includes/images/penyewa.png" class="nav-icon" alt="">
                        Supplier</a></li>
                <li><a href="produk/TampilProduk.php"><img src="includes/images/kamar.png" class="nav-icon" alt="">
                        Produk</a></li>
                <li><a href="kategori/TampilKategori.php"><img src="includes/images/tipekamar.png" class="nav-icon"
                            alt=""> Kategori</a></li>
                <li><a href="riwayat_stok/TampilRiwayatStok.php"><img src="includes/images/transaksi.png" class="nav-icon"
                            alt=""> Riwayat Stok</a></li>
                <li><a href="user/TampilUser.php"><img src="includes/images/usericon.png" class="nav-icon" alt="">
                        Kelola User</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="sidebar-brand">Inventori</div>
                <div class="user-card">
                    <div class="user-avatar">
                        <?php if (!empty($_SESSION['foto'])): ?>
                            <img src="user/uploads/<?= htmlspecialchars($_SESSION['foto']) ?>" alt="Avatar"
                                style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <span style="color: #6b7280;">&#128100;</span>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?= htmlspecialchars($_SESSION['nama']) ?></div>
                        <div class="user-role">Admin</div>
                    </div>
                    <a href="logout.php" class="logout-link" title="Logout"><img src="includes/images/logout.png"
                            alt="Logout" style="width: 16px; height: 16px; filter: brightness(0) invert(0.4);"></a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <!-- Header with tabs -->
            <header class="header">
                <div class="header-top">
                    <h1 class="page-title">Dashboard</h1>
                </div>
                <nav class="header-tabs">
                    <a href="menu.php" class="header-tab active">Overview</a>
                    <a href="riwayat_stok/TampilRiwayatStok.php" class="header-tab">Transaksi</a>
                    <a href="kategori/TampilKategori.php" class="header-tab">Kategori</a>
                    <a href="produk/TampilProduk.php" class="header-tab">Produk</a>
                </nav>
            </header>

            <!-- Content -->
            <div class="content">
                <!-- Top row: Stats + Profit card -->
                <div class="top-grid">
                    <!-- Left: Cash flow equivalent -->
                    <div>
                        <div class="section-header">
                            <div class="section-title">Ringkasan Stok</div>
                            <div class="section-subtitle">Pantau arus stok masuk dan keluar dari inventori</div>
                        </div>

                        <div class="filter-pills">
                            <span class="pill active">Semua</span>
                            <span class="pill">Hari Ini</span>
                            <span class="pill">Bulan Ini</span>
                        </div>

                        <!-- 3 Colored stat cards -->
                        <div class="stat-cards">
                            <div class="stat-card green">
                                <div class="stat-card-icon">&#8595;</div>
                                <div class="stat-card-value"><?= $total_stok ?></div>
                                <div class="stat-card-label">Total Stok Masuk</div>
                            </div>
                            <div class="stat-card yellow">
                                <div class="stat-card-icon">&#215;</div>
                                <div class="stat-card-value"><?= $total_produk ?></div>
                                <div class="stat-card-label">Total Produk</div>
                            </div>
                            <div class="stat-card dark">
                                <div class="stat-card-icon">&#8593;</div>
                                <div class="stat-card-value"><?= $total_supplier ?></div>
                                <div class="stat-card-label">Total Supplier</div>
                            </div>
                        </div>

                        <!-- Mini chart -->
                        <div class="mini-chart">
                            <div class="chart-container">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Profit and loss equivalent -->
                    <div class="profit-card">
                        <div class="profit-card-header">
                            <div>
                                <div class="profit-card-title">Total Nilai Masuk</div>
                                <div class="profit-card-subtitle">Nilai stok bulan ini (termasuk semua transaksi)</div>
                            </div>
                        </div>
                        <div class="profit-big-number"><?= $total_transaksi ?></div>
                        <div class="profit-big-label">Total Transaksi</div>
                        <div class="profit-chart-area">
                            <canvas id="profitChart"></canvas>
                        </div>
                        <div class="profit-metrics">
                            <div class="profit-metric" style="text-align: left;">
                                <div class="profit-metric-value">Rp <?= number_format($nilai_bulan, 0, ',', '.') ?></div>
                                <div class="profit-metric-label">Bulan ini</div>
                            </div>
                            <div class="profit-metric" style="margin-left: auto;">
                                <div class="profit-metric-value"><?= $stok_masuk_hari_ini ?></div>
                                <div class="profit-metric-label">Stok masuk hari ini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom row: Table + Transactions -->
                <div class="bottom-grid">
                    <!-- Left: Net Income table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik</h3>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Metrik</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Produk</td>
                                    <td><?= $total_produk ?></td>
                                </tr>
                                <tr>
                                    <td>Total Kategori</td>
                                    <td><?= count($kategori_list) ?></td>
                                </tr>
                                <tr>
                                    <td>Total Supplier</td>
                                    <td><?= $total_supplier ?></td>
                                </tr>
                                <tr>
                                    <td>Total Transaksi</td>
                                    <td><?= $total_transaksi ?></td>
                                </tr>
                                <tr>
                                    <td>Stok Masuk Hari Ini</td>
                                    <td><?= $stok_masuk_hari_ini ?></td>
                                </tr>
                                <tr>
                                    <td>Stok Keluar Hari Ini</td>
                                    <td><?= $stok_keluar_hari_ini ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Right: Transactions (Payable & Owing equivalent) -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Transaksi Terbaru</h3>
                            <a href="riwayat_stok/TampilRiwayatStok.php" class="card-link">Lihat Semua</a>
                        </div>
                        <?php if (count($transaksi_terbaru) > 0): ?>
                            <?php foreach ($transaksi_terbaru as $t): ?>
                                <div class="transaction-item">
                                    <div class="transaction-info">
                                        <div class="transaction-avatar">&#9660;</div>
                                        <div>
                                            <div class="transaction-name"><?= htmlspecialchars($t['nama']) ?></div>
                                            <div class="transaction-amount">Rp
                                                <?= number_format($t['total_nilai'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="transaction-room"><?= $t['kode_produk'] ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">Belum ada transaksi</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Bar chart (bottom of Cash flow section)
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Nilai Stok Masuk',
                    data: <?= json_encode($chart_data) ?>,
                    backgroundColor: '#a3e635',
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, family: 'Inter' }, color: '#9ca3af' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f3f4f6' },
                        ticks: {
                            font: { size: 11, family: 'Inter' },
                            color: '#9ca3af',
                            callback: function (value) {
                                if (value >= 1000000) return (value / 1000000) + ' jt';
                                if (value >= 1000) return (value / 1000) + ' rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Line chart (Profit card - dark bg)
        const ctx2 = document.getElementById('profitChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [{
                    label: 'Nilai',
                    data: <?= json_encode($chart_data) ?>,
                    borderColor: '#a3e635',
                    backgroundColor: 'rgba(163, 230, 53, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointHoverBackgroundColor: '#a3e635'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { font: { size: 10, family: 'Inter' }, color: '#555' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: {
                            font: { size: 10, family: 'Inter' },
                            color: '#555',
                            callback: function (value) {
                                if (value >= 1000000) return (value / 1000000) + 'jt';
                                if (value >= 1000) return (value / 1000) + 'rb';
                                return value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>