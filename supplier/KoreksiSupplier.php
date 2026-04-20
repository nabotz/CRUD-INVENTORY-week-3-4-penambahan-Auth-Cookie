<?php
require_once '../auth.php';
include "../koneksi.php";

$base_url = '../';
$current_page = 'supplier';

$id = $_GET['id'] ?? '';
$stmt = $koneksi->prepare("SELECT * FROM supplier WHERE id_supplier = ?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: TampilSupplier.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Edit Supplier - Sistem Inventori</title>
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
                        <span>Edit</span>
                    </div>
                    <h1 class="page-title">Edit Data Supplier</h1>
                </div>
            </header>

            <div class="content">
                <div class="card" style="max-width: 600px;">
                    <form action="SimpanKoreksiSupplier.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row['id_supplier'] ?>">

                        <div class="form-group">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" name="nama" class="form-control" required minlength="3"
                                value="<?= htmlspecialchars($row['nama']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required
                                rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No Telepon</label>
                            <input type="tel" name="no_telp" class="form-control" required
                                value="<?= htmlspecialchars($row['no_telp']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No NPWP</label>
                            <input type="text" name="no_npwp" class="form-control" maxlength="20"
                                value="<?= htmlspecialchars($row['no_npwp']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Supplier</label>
                            <select name="jenis_supplier" class="form-control" required>
                                <option value="Distributor" <?= $row['jenis_supplier'] == 'Distributor' ? 'selected' : '' ?>>Distributor</option>
                                <option value="Produsen" <?= $row['jenis_supplier'] == 'Produsen' ? 'selected' : '' ?>>Produsen</option>
                                <option value="Agen" <?= $row['jenis_supplier'] == 'Agen' ? 'selected' : '' ?>>Agen</option>
                                <option value="Importir" <?= $row['jenis_supplier'] == 'Importir' ? 'selected' : '' ?>>Importir</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 12px; margin-top: 24px;">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="TampilSupplier.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
