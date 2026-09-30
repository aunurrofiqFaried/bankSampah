<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['pengepul']);

$pageTitle = 'Dashboard Pengepul';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="container-fluid">
    <div class="page-content">
        <div class="page-header">
            <h3>Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h3>
            <p class="text-muted">Riwayat pembelian sampah dari sekolah akan tampil di sini
            mulai Step 5 (modul penjualan ke pengepul).</p>
        </div>

        <div class="card card-glass">
            <p class="mb-0 text-muted">Belum ada data untuk ditampilkan.</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
