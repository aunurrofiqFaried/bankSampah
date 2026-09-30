<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['siswa', 'guru']);

$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Ambil saldo terkini langsung dari database (bukan dari session),
// supaya selalu akurat walau saldo berubah di tab/perangkat lain.
$stmtSaldo = $pdo->prepare('SELECT saldo FROM users WHERE id_user = :id');
$stmtSaldo->execute(['id' => $_SESSION['id_user']]);
$saldo = $stmtSaldo->fetchColumn();

// 5 transaksi setor terakhir milik user ini, join ke tabel sampah
// supaya dapat nama jenisnya, bukan cuma id_sampah.
$stmtRiwayat = $pdo->prepare(
    'SELECT t.*, s.jenis_sampah
     FROM transaksi t
     JOIN sampah s ON s.id_sampah = t.id_sampah
     WHERE t.id_user = :id AND t.tipe = "setor"
     ORDER BY t.tanggal DESC
     LIMIT 5'
);
$stmtRiwayat->execute(['id' => $_SESSION['id_user']]);
$riwayatTerbaru = $stmtRiwayat->fetchAll();

require_once __DIR__ . '/../includes/helpers.php';
$activeMenu = 'dashboard';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_siswa.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Halo, <?= htmlspecialchars($_SESSION['nama']) ?> 👋</h3>
                <p class="text-muted">Ringkasan aktivitas setoran sampah kamu.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-4">
                    <div class="card card-glass stat-card">
                        <div class="icon-circle"><i class="bi bi-wallet2"></i></div>
                        <div class="stat-body">
                            <div class="stat-label">Saldo Kamu</div>
                            <div class="stat-value">Rp <?= number_format((float)$saldo, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card card-glass stat-card">
                        <div class="icon-circle"><i class="bi bi-recycle"></i></div>
                        <div class="stat-body">
                            <div class="stat-label">Punya sampah menumpuk?</div>
                            <a href="<?= BASE_URL ?>siswa/setor.php" class="btn btn-hijau btn-sm mt-1">
                                <i class="bi bi-plus-lg"></i> Setor Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-glass mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Setoran Terbaru</h5>
                    <a href="<?= BASE_URL ?>siswa/setor.php" class="btn btn-hijau btn-sm">
                        <i class="bi bi-plus-lg"></i> Setor Sampah
                    </a>
                </div>

                <?php if (empty($riwayatTerbaru)): ?>
                    <p class="text-muted mb-0">Belum ada riwayat setoran. Yuk mulai setor sampah pertamamu!</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-glass align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Estimasi Rp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayatTerbaru as $r): ?>
                                <tr>
                                    <td><?= date('d M Y, H:i', strtotime($r['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($r['jenis_sampah']) ?></td>
                                    <td><?= number_format((float)$r['berat_kg'], 2) ?></td>
                                    <td>Rp <?= number_format((float)$r['total_rp'], 0, ',', '.') ?></td>
                                    <td><?= badgeStatus($r['status']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?= BASE_URL ?>siswa/riwayat.php" class="small d-inline-block mt-3">Lihat semua riwayat &rarr;</a>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
