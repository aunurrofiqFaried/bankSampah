<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['admin']);

$pageTitle = 'Kelola Jenis Sampah';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

$daftarSampah = $pdo->query('SELECT * FROM sampah ORDER BY jenis_sampah')->fetchAll();

// Kalau ada ?edit=ID, ambil datanya untuk ditaruh di form (mode edit).
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM sampah WHERE id_sampah = :id');
    $stmt->execute(['id' => (int)$_GET['edit']]);
    $editData = $stmt->fetch();
}

$activeMenu = 'sampah';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_admin.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Kelola Jenis Sampah</h3>
                <p class="text-muted">Harga di sini yang jadi acuan perhitungan setiap setoran.</p>
            </div>

            <?php if (isset($_GET['sukses'])): ?>
                <div class="alert alert-success"><i class="bi bi-check-circle"></i> Data berhasil disimpan.</div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'terpakai'): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i>
                    Tidak bisa dihapus — jenis sampah ini sudah punya riwayat transaksi.
                    Ubah harganya saja, atau biarkan datanya (jangan dihapus) supaya riwayat lama tetap valid.
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card card-glass">
                        <h5 class="mb-3">Daftar Jenis Sampah</h5>
                        <?php if (empty($daftarSampah)): ?>
                            <p class="text-muted mb-0">Belum ada data. Tambahkan lewat form di samping.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-glass align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Jenis</th>
                                        <th>Harga/kg</th>
                                        <th>Stok (kg)</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($daftarSampah as $s): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($s['jenis_sampah']) ?></td>
                                        <td>Rp <?= number_format($s['harga_per_kg'], 0, ',', '.') ?></td>
                                        <td><?= number_format($s['stok_kg'], 2) ?></td>
                                        <td class="text-end">
                                            <a href="?edit=<?= $s['id_sampah'] ?>" class="btn btn-outline-hijau btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="<?= BASE_URL ?>admin/proses_sampah.php" class="d-inline"
                                                  onsubmit="return confirm('Hapus jenis sampah ini?');">
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="id_sampah" value="<?= $s['id_sampah'] ?>">
                                                <button type="submit" class="btn btn-sm" style="color:#dc2626;">
                                                    <i class="bi bi-trash"></i>
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

                <div class="col-lg-5">
                    <div class="card card-glass">
                        <h5 class="mb-3"><?= $editData ? 'Edit Jenis Sampah' : 'Tambah Jenis Sampah' ?></h5>
                        <form method="POST" action="<?= BASE_URL ?>admin/proses_sampah.php">
                            <input type="hidden" name="aksi" value="<?= $editData ? 'edit' : 'tambah' ?>">
                            <?php if ($editData): ?>
                                <input type="hidden" name="id_sampah" value="<?= $editData['id_sampah'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Nama Jenis Sampah</label>
                                <input type="text" name="jenis_sampah" class="form-control" required
                                       value="<?= htmlspecialchars($editData['jenis_sampah'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga per Kg (Rp)</label>
                                <input type="number" name="harga_per_kg" class="form-control" min="0" required
                                       value="<?= htmlspecialchars($editData['harga_per_kg'] ?? '') ?>">
                            </div>

                            <?php if ($editData): ?>
                            <div class="mb-3">
                                <label class="form-label">Stok Saat Ini (kg)</label>
                                <input type="number" step="0.01" name="stok_kg" class="form-control" min="0" required
                                       value="<?= htmlspecialchars($editData['stok_kg']) ?>">
                                <div class="form-text">
                                    Biasanya stok berubah otomatis (dari verifikasi setoran / penjualan ke pengepul).
                                    Ubah manual di sini hanya untuk koreksi data.
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-hijau flex-grow-1">
                                    <?= $editData ? 'Simpan Perubahan' : 'Tambah' ?>
                                </button>
                                <?php if ($editData): ?>
                                    <a href="sampah.php" class="btn btn-outline-hijau">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
