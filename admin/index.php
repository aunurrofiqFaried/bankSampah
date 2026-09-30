<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['admin']);

$pageTitle = 'Dashboard Admin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Statistik ringkas untuk kartu di dashboard.
// Query kecil-kecil begini aman dijalankan langsung di halaman
// (tidak perlu di-cache) selama jumlah barisnya wajar untuk sekolah.
$jumlahPending   = (int)$pdo->query('SELECT COUNT(*) FROM transaksi WHERE tipe = "setor" AND status = "pending"')->fetchColumn();
$jumlahPengguna  = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$jumlahTarikSaldo= (int)$pdo->query('SELECT COUNT(*) FROM tarik_saldo WHERE status = "pending"')->fetchColumn();
$jumlahJenis     = (int)$pdo->query('SELECT COUNT(*) FROM sampah')->fetchColumn();

$activeMenu = 'dashboard';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h3>
                <p class="text-muted">Ringkasan aktivitas bank sampah sekolah.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <a href="<?= BASE_URL ?>admin/verifikasi.php" class="text-decoration-none">
                        <div class="card card-glass stat-card">
                            <div class="icon-circle" style="background:#fef3c7; color:#92400e;"><i class="bi bi-hourglass-split"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Setoran Pending</div>
                                <div class="stat-value"><?= $jumlahPending ?></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card card-glass stat-card">
                        <div class="icon-circle"><i class="bi bi-people"></i></div>
                        <div class="stat-body">
                            <div class="stat-label">Total Pengguna</div>
                            <div class="stat-value"><?= $jumlahPengguna ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card card-glass stat-card">
                        <div class="icon-circle"><i class="bi bi-cash-coin"></i></div>
                        <div class="stat-body">
                            <div class="stat-label">Tarik Saldo Pending</div>
                            <div class="stat-value"><?= $jumlahTarikSaldo ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <a href="<?= BASE_URL ?>admin/sampah.php" class="text-decoration-none">
                        <div class="card card-glass stat-card">
                            <div class="icon-circle"><i class="bi bi-trash"></i></div>
                            <div class="stat-body">
                                <div class="stat-label">Jenis Sampah</div>
                                <div class="stat-value"><?= $jumlahJenis ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <?php if ($jumlahPending > 0): ?>
            <div class="alert alert-warning mt-4 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bell"></i> Ada <strong><?= $jumlahPending ?></strong> setoran menunggu verifikasi.</span>
                <a href="<?= BASE_URL ?>admin/verifikasi.php" class="btn btn-sm btn-hijau">Verifikasi Sekarang</a>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
