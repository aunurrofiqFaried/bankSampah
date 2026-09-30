<?php
// $activeMenu diisi di masing-masing halaman sebelum include file ini,
// nilainya salah satu dari: 'dashboard', 'setor', 'riwayat', 'tarik'
$activeMenu = $activeMenu ?? '';
?>
<div class="col-md-3 col-lg-2 sidebar-banksampah">
    <div class="nav-label">Menu</div>
    <nav class="nav flex-column">
        <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= BASE_URL ?>siswa/index.php">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
        <a class="nav-link <?= $activeMenu === 'setor' ? 'active' : '' ?>" href="<?= BASE_URL ?>siswa/setor.php">
            <i class="bi bi-recycle me-1"></i> Setor Sampah
        </a>
        <a class="nav-link <?= $activeMenu === 'riwayat' ? 'active' : '' ?>" href="<?= BASE_URL ?>siswa/riwayat.php">
            <i class="bi bi-clock-history me-1"></i> Riwayat Setoran
        </a>
        <a class="nav-link disabled text-muted" href="#" tabindex="-1">
            <i class="bi bi-cash-coin me-1"></i> Tarik Saldo <span class="badge bg-secondary ms-1" style="font-size:.6rem;">Step 6</span>
        </a>
    </nav>
</div>
