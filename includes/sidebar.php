<?php
// Sidebar template for all CRUD pages
// Usage: include this file after setting $base_url and $current_page
?>
<aside class="sidebar">
    <div class="logo">
        <span class="logo-text">Menu</span>
    </div>

    <ul class="nav-menu">
        <li><a href="<?= $base_url ?>menu.php" class="<?= $current_page == 'dashboard' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/dashboard.png" class="nav-icon" alt=""> Dashboard</a></li>
        <li><a href="<?= $base_url ?>stok_masuk.php" class="<?= $current_page == 'stok_masuk' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/transaksibaru.png" class="nav-icon" alt=""> Stok Masuk</a>
        </li>
        <li><a href="<?= $base_url ?>supplier/TampilSupplier.php"
                class="<?= $current_page == 'supplier' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/penyewa.png" class="nav-icon" alt=""> Supplier</a></li>
        <li><a href="<?= $base_url ?>produk/TampilProduk.php" class="<?= $current_page == 'produk' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/kamar.png" class="nav-icon" alt=""> Produk</a></li>
        <li><a href="<?= $base_url ?>kategori/TampilKategori.php"
                class="<?= $current_page == 'kategori' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/tipekamar.png" class="nav-icon" alt=""> Kategori</a></li>
        <li><a href="<?= $base_url ?>riwayat_stok/TampilRiwayatStok.php"
                class="<?= $current_page == 'riwayat_stok' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/transaksi.png" class="nav-icon" alt=""> Riwayat Stok</a></li>
        <li><a href="<?= $base_url ?>user/TampilUser.php" class="<?= $current_page == 'user' ? 'active' : '' ?>">
                <img src="<?= $base_url ?>includes/images/usericon.png" class="nav-icon" alt=""> Kelola User</a></li>
    </ul>

    <div class="sidebar-footer">
        <div class="sidebar-brand">Inventori</div>
        <div class="user-card">
            <div class="user-avatar">
                <?php if (!empty($_SESSION['foto'])): ?>
                    <img src="<?= $base_url ?>user/uploads/<?= htmlspecialchars($_SESSION['foto']) ?>" alt="Avatar"
                        style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <span style="color: #6b7280;">&#128100;</span>
                <?php endif; ?>
            </div>
            <div class="user-info">
                <div class="user-name">
                    <?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?>
                </div>
                <div class="user-role">Admin</div>
            </div>
            <a href="<?= $base_url ?>logout.php" class="logout-link" title="Logout"><img
                    src="<?= $base_url ?>includes/images/logout.png" alt="Logout"
                    style="width: 16px; height: 16px; filter: brightness(0) invert(0.4);"></a>
        </div>
    </div>
</aside>

<style>
    .nav-icon {
        width: 18px;
        height: 18px;
        object-fit: contain;
        margin-right: 12px;
        vertical-align: middle;
        filter: brightness(0) invert(0.5);
        transition: filter 0.15s;
    }

    .nav-menu a.active .nav-icon {
        filter: brightness(0) invert(0.85);
    }

    .nav-menu a:hover .nav-icon {
        filter: brightness(0) invert(0.85);
    }
</style>