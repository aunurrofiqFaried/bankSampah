<?php
// navbar.php TIDAK dipakai di halaman login/register (mereka pakai layout sendiri).
// Ini dipakai mulai step 2-3 setelah user login.
// Nama & role diambil dari session (diisi saat login di step 2).
$namaUser = $_SESSION['nama'] ?? 'Pengguna';
$roleUser = $_SESSION['role'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-banksampah sticky-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand brand-font" href="<?= BASE_URL ?>">
            <i class="bi bi-recycle"></i> Bank Sampah
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item me-3">
                    <span class="nav-link">
                        <i class="bi bi-person-circle"></i>
                        <?= htmlspecialchars($namaUser) ?>
                        <span class="badge bg-light text-success ms-1"><?= htmlspecialchars(ucfirst($roleUser)) ?></span>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>auth/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
