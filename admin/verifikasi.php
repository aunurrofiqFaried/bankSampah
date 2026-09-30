<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['admin']);
require_once __DIR__ . '/../includes/helpers.php';

$pageTitle = 'Verifikasi Setoran';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Ambil semua setoran yang masih pending, join ke users (siapa yang setor)
// dan sampah (jenis apa), diurutkan dari yang paling lama menunggu.
$stmt = $pdo->query(
    'SELECT t.*, u.nama AS nama_pengirim, u.role AS role_pengirim, s.jenis_sampah
     FROM transaksi t
     JOIN users u  ON u.id_user   = t.id_user
     JOIN sampah s ON s.id_sampah = t.id_sampah
     WHERE t.tipe = "setor" AND t.status = "pending"
     ORDER BY t.tanggal ASC'
);
$daftarPending = $stmt->fetchAll();

$activeMenu = 'verifikasi';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Verifikasi Setoran</h3>
                <p class="text-muted">Setoran yang disetujui akan langsung menambah saldo pengirim dan stok sampah sekolah.</p>
            </div>

            <?php if (isset($_GET['sukses'])): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    <?= $_GET['sukses'] === 'terima' ? 'Setoran disetujui, saldo sudah ditambahkan.' : 'Setoran ditolak.' ?>
                </div>
            <?php endif; ?>

            <div class="card card-glass">
                <?php if (empty($daftarPending)): ?>
                    <p class="text-muted mb-0">
                        <i class="bi bi-emoji-smile"></i> Tidak ada setoran yang menunggu verifikasi saat ini.
                    </p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-glass align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengirim</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Nilai Rp</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daftarPending as $t): ?>
                                <tr>
                                    <td><?= date('d M Y, H:i', strtotime($t['tanggal'])) ?></td>
                                    <td>
                                        <?= htmlspecialchars($t['nama_pengirim']) ?>
                                        <span class="badge bg-light text-success"><?= htmlspecialchars(ucfirst($t['role_pengirim'])) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($t['jenis_sampah']) ?></td>
                                    <td><?= number_format((float)$t['berat_kg'], 2) ?></td>
                                    <td>Rp <?= number_format((float)$t['total_rp'], 0, ',', '.') ?></td>
                                    <td class="text-end">
                                        <form method="POST" action="<?= BASE_URL ?>admin/proses_verifikasi.php" class="d-inline">
                                            <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                                            <input type="hidden" name="aksi" value="terima">
                                            <button type="submit" class="btn btn-hijau btn-sm">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= BASE_URL ?>admin/proses_verifikasi.php" class="d-inline"
                                              onsubmit="return confirm('Tolak setoran ini?');">
                                            <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                                            <input type="hidden" name="aksi" value="tolak">
                                            <button type="submit" class="btn btn-outline-hijau btn-sm" style="border-color:#dc2626; color:#dc2626;">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                        </form>
                                    </td>
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
