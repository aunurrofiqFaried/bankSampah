<?php
$activeMenu = $activeMenu ?? '';
?>
<div class="col-md-3 col-lg-2 sidebar-banksampah">
    <div class="nav-label">Menu</div>
    <nav class="nav flex-column">
        <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/index.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link <?= $activeMenu === 'verifikasi' ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/verifikasi.php">
            <i class="bi bi-check2-square"></i> Verifikasi Setoran
        </a>
        <a class="nav-link <?= $activeMenu === 'sampah' ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/sampah.php">
            <i class="bi bi-trash"></i> Kelola Jenis Sampah
        </a>
        <a class="nav-link disabled" href="#" tabindex="-1">
            <i class="bi bi-shop"></i> Jual ke Pengepul <span class="badge bg-secondary ms-1" style="font-size:.6rem;">Step 5</span>
        </a>
        <a class="nav-link disabled" href="#" tabindex="-1">
            <i class="bi bi-cash-coin"></i> Tarik Saldo <span class="badge bg-secondary ms-1" style="font-size:.6rem;">Step 6</span>
        </a>
        <a class="nav-link disabled" href="#" tabindex="-1">
            <i class="bi bi-people"></i> Kelola Pengguna <span class="badge bg-secondary ms-1" style="font-size:.6rem;">Nanti</span>
        </a>
    </nav>
</div>
