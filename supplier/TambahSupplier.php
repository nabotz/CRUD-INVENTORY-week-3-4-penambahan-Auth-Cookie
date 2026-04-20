<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'supplier';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Tambah Supplier - Sistem Inventori</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard">
        <?php include '../includes/sidebar.php'; ?>

        <main class="main">
            <header class="header">
                <div>
                    <div class="breadcrumb">
                        <a href="TampilSupplier.php">Supplier</a>
                        <span>→</span>
                        <span>Tambah Baru</span>
                    </div>
                    <h1 class="page-title">Tambah Supplier Baru</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanSupplier.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <div class="form-group">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" name="nama" class="form-control" required minlength="3"
                                placeholder="Masukkan nama supplier">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required rows="3"
                                placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">No Telepon</label>
                            <input type="tel" name="no_telp" class="form-control" required pattern="[0-9\-]{8,20}"
                                placeholder="Contoh: 021-12345678">
                        </div>

                        <div class="form-group">
                            <label class="form-label">No NPWP</label>
                            <input type="text" name="no_npwp" class="form-control" maxlength="20"
                                placeholder="Contoh: 01.234.567.8-901.000">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jenis Supplier</label>
                            <select name="jenis_supplier" class="form-control" required>
                                <option value="Distributor">Distributor</option>
                                <option value="Produsen">Produsen</option>
                                <option value="Agen">Agen</option>
                                <option value="Importir">Importir</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Supplier</button>
                            <a href="TampilSupplier.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
