<?php
/**
 * =========================================================
 * SETUP SEKALI PAKAI — buat akun awal (admin/siswa/guru/pengepul)
 * =========================================================
 * PENTING: HAPUS FILE INI setelah kamu selesai membuat akun-akun
 * awal yang dibutuhkan. Jangan biarkan file ini ada di server
 * production, karena siapapun yang tahu URL-nya bisa membuat akun.
 *
 * Kenapa bukan hash yang saya tulis manual di SQL?
 * password_hash() menghasilkan salt acak setiap kali dipanggil,
 * jadi hash HARUS dibuat oleh PHP kamu sendiri saat itu juga,
 * bukan ditempel dari contoh orang lain.
 */
require_once __DIR__ . '/../config/app.php';

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? '';

    if ($nama && $username && $password && in_array($role, ['admin','siswa','guru','pengepul'], true)) {
        // Cek username belum dipakai
        $cek = $pdo->prepare('SELECT id_user FROM users WHERE username = :u');
        $cek->execute(['u' => $username]);

        if ($cek->fetch()) {
            $pesan = '<div class="alert alert-danger">Username sudah dipakai.</div>';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                'INSERT INTO users (nama, username, password, role, saldo)
                 VALUES (:nama, :username, :password, :role, 0)'
            );
            $stmt->execute([
                'nama' => $nama,
                'username' => $username,
                'password' => $hash,
                'role' => $role,
            ]);
            $pesan = '<div class="alert alert-success">Akun <strong>' . htmlspecialchars($username) . '</strong> ('. htmlspecialchars($role) .') berhasil dibuat.</div>';
        }
    } else {
        $pesan = '<div class="alert alert-danger">Semua field wajib diisi.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Setup Akun Awal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 480px;">
    <div class="alert alert-warning">
        ⚠️ Halaman ini hanya untuk setup awal. <strong>Hapus file <code>auth/setup_akun.php</code></strong> setelah selesai membuat akun yang kamu butuhkan.
    </div>

    <?= $pesan ?>

    <div class="card p-4">
        <h5 class="mb-3">Buat Akun Baru</h5>
        <form method="POST">
            <div class="mb-2">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="pengepul">Pengepul</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Buat Akun</button>
        </form>
    </div>
</div>
</body>
</html>
