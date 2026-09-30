<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['siswa', 'guru']);

$pageTitle = 'Setor Sampah';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

// Ambil daftar jenis sampah yang stoknya diatur admin (Step 4).
// Kita kirim juga harga_per_kg ke JS supaya bisa hitung estimasi
// SECARA REAL-TIME di sisi user — TAPI ini cuma untuk preview.
// Perhitungan yang SAH tetap dihitung ulang di server (proses_setor.php),
// jangan pernah percaya angka yang dikirim dari browser.
$daftarSampah = $pdo->query('SELECT id_sampah, jenis_sampah, harga_per_kg FROM sampah ORDER BY jenis_sampah')->fetchAll();

$activeMenu = 'setor';
?>

<div class="container-fluid">
    <div class="row">
        <?php require __DIR__ . '/../includes/sidebar_siswa.php'; ?>

        <div class="col-md-9 col-lg-10">
        <div class="page-content">
            <div class="page-header">
                <h3>Setor Sampah</h3>
                <p class="text-muted">Setoran kamu akan berstatus <strong>menunggu verifikasi</strong> sampai dicek admin.</p>
            </div>

            <?php if (empty($daftarSampah)): ?>
                <div class="card card-glass">
                    <p class="mb-0 text-muted">
                        Belum ada jenis sampah yang terdaftar. Minta admin menambahkan
                        data jenis sampah &amp; harga terlebih dahulu di menu Kelola Sampah.
                    </p>
                </div>
            <?php else: ?>
            <div class="card card-glass" style="max-width: 520px;">
                <form method="POST" action="<?= BASE_URL ?>siswa/proses_setor.php" id="formSetor">
                    <div class="mb-3">
                        <label class="form-label">Jenis Sampah</label>
                        <select name="id_sampah" id="id_sampah" class="form-select" required>
                            <option value="" disabled selected>-- Pilih jenis sampah --</option>
                            <?php foreach ($daftarSampah as $s): ?>
                                <option value="<?= $s['id_sampah'] ?>" data-harga="<?= $s['harga_per_kg'] ?>">
                                    <?= htmlspecialchars($s['jenis_sampah']) ?>
                                    (Rp <?= number_format($s['harga_per_kg'], 0, ',', '.') ?>/kg)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Berat (kg)</label>
                        <input type="number" step="0.01" min="0.01" name="berat_kg" id="berat_kg"
                               class="form-control" placeholder="Contoh: 2.5" required>
                    </div>

                    <div class="alert alert-success d-flex justify-content-between align-items-center" style="background: var(--hijau-muda); border: none;">
                        <span>Estimasi Nilai</span>
                        <strong id="estimasiRp">Rp 0</strong>
                    </div>
                    <p class="text-muted small">
                        * Nilai final ditentukan setelah admin menimbang &amp; memverifikasi ulang.
                    </p>

                    <button type="submit" class="btn btn-hijau w-100 mt-2">
                        <i class="bi bi-send-check"></i> Kirim Setoran
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</div>

<script>
// Estimasi harga real-time — HANYA untuk kenyamanan user melihat perkiraan.
// Nilai sesungguhnya selalu dihitung ulang di server, JANGAN percaya
// input dari JavaScript untuk keputusan uang.
const selectSampah = document.getElementById('id_sampah');
const inputBerat   = document.getElementById('berat_kg');
const hasilEstimasi = document.getElementById('estimasiRp');

function hitungEstimasi() {
    const opsi = selectSampah.options[selectSampah.selectedIndex];
    const harga = parseFloat(opsi?.dataset?.harga || 0);
    const berat = parseFloat(inputBerat.value || 0);
    const total = harga * berat;
    hasilEstimasi.textContent = 'Rp ' + total.toLocaleString('id-ID', { maximumFractionDigits: 0 });
}

selectSampah?.addEventListener('change', hitungEstimasi);
inputBerat?.addEventListener('input', hitungEstimasi);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
