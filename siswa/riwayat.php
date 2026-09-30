<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['siswa', 'guru']);
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Riwayat Setoran';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Filter status via query string: ?status=pending / diverifikasi / ditolak
$filterStatus = $_GET['status'] ?? '';
$statusValid  = ['pending', 'diverifikasi', 'ditolak'];

$sql = 'SELECT t.*, s.jenis_sampah
        FROM transaksi t
        JOIN sampah s ON s.id_sampah = t.id_sampah
        WHERE t.id_user = :id AND t.tipe = "setor"';
$params = ['id' => $_SESSION['id_user']];

if (in_array($filterStatus, $statusValid, true)) {
    $sql .= ' AND t.status = :status';
    $params['status'] = $filterStatus;
}
$sql .= ' ORDER BY t.tanggal DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$riwayat = $stmt->fetchAll();

$activeMenu = 'riwayat';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_siswa.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Riwayat Setoran</h3>
                <p class="text-muted">Semua setoran sampah yang pernah kamu kirim.</p>
            </div>

            <?php if (isset($_GET['sukses'])): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> Setoran berhasil dikirim, menunggu verifikasi admin.
                </div>
            <?php endif; ?>

            <div class="btn-group mb-3">
                <a href="?" class="btn btn-sm <?= $filterStatus === '' ? 'btn-hijau' : 'btn-outline-hijau' ?>">Semua</a>
                <a href="?status=pending" class="btn btn-sm <?= $filterStatus === 'pending' ? 'btn-hijau' : 'btn-outline-hijau' ?>">Menunggu</a>
                <a href="?status=diverifikasi" class="btn btn-sm <?= $filterStatus === 'diverifikasi' ? 'btn-hijau' : 'btn-outline-hijau' ?>">Terverifikasi</a>
                <a href="?status=ditolak" class="btn btn-sm <?= $filterStatus === 'ditolak' ? 'btn-hijau' : 'btn-outline-hijau' ?>">Ditolak</a>
            </div>

            <div class="card card-glass">
                <?php if (empty($riwayat)): ?>
                    <p class="text-muted mb-0">Tidak ada data untuk filter ini.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-glass align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Nilai Rp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $r): ?>
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
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
