<?php
$pageTitle = 'Masuk';
$noBlobs = true; // halaman ini sudah punya background gradasi animasi sendiri
require_once __DIR__ . '/../includes/header.php';
// Catatan: halaman login TIDAK include navbar.php, karena user belum login.
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="row g-0">
            <div class="col-md-5 brand-side">
                <div class="icon-circle mb-3" style="background: rgba(255,255,255,.18); color:#fff; width:64px; height:64px; font-size:1.7rem;">
                    <i class="bi bi-recycle"></i>
                </div>
                <h2 class="mt-1">Bank Sampah</h2>
                <p class="mb-0" style="opacity:.9;">
                    Kelola setoran sampah, verifikasi, dan saldo
                    siswa &amp; guru dalam satu sistem yang rapi.
                </p>
            </div>
            <div class="col-md-7 form-side">
                <h4 class="mb-1">Selamat Datang 👋</h4>
                <p class="text-muted mb-4">Masuk untuk melanjutkan</p>

                <?php
                // Tampilkan pesan error kalau ada, berdasarkan ?error=... dari proses_login.php
                $pesanError = match ($_GET['error'] ?? null) {
                    'kosong' => 'Username dan password wajib diisi.',
                    'salah'  => 'Username atau password salah.',
                    default  => null,
                };
                ?>
                <?php if ($pesanError): ?>
                    <div class="alert alert-danger py-2 small">
                        <i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($pesanError) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>auth/proses_login.php">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-hijau w-100 mt-2">Masuk</button>
                </form>

                <p class="text-center text-muted mt-4 mb-0" style="font-size:.9rem;">
                    Belum punya akun? Hubungi admin sekolah untuk didaftarkan.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
