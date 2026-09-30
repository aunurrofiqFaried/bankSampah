<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['siswa', 'guru']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('siswa/setor.php');
}

$idSampah = (int)($_POST['id_sampah'] ?? 0);
$beratKg  = (float)($_POST['berat_kg'] ?? 0);

if ($idSampah <= 0 || $beratKg <= 0) {
    redirect('siswa/setor.php?error=invalid');
}

// ==========================================================
// PENTING — INI KUNCINYA:
// Kita AMBIL ULANG harga_per_kg dari DATABASE, BUKAN dari form.
// Kalau kita percaya harga yang dikirim browser, siswa nakal
// bisa edit HTML/JS di browser-nya dan kirim harga sendiri.
// Server harus selalu jadi sumber kebenaran untuk urusan uang.
// ==========================================================
$stmtHarga = $pdo->prepare('SELECT harga_per_kg FROM sampah WHERE id_sampah = :id');
$stmtHarga->execute(['id' => $idSampah]);
$hargaPerKg = $stmtHarga->fetchColumn();

if ($hargaPerKg === false) {
    redirect('siswa/setor.php?error=invalid');
}

$totalRp = (int)round($hargaPerKg * $beratKg);

// status default 'pending' -> saldo BELUM ditambahkan sampai
// admin memverifikasi (itu terjadi di Step 4, halaman verifikasi admin).
$stmt = $pdo->prepare(
    'INSERT INTO transaksi (id_user, id_sampah, tipe, berat_kg, total_rp, status)
     VALUES (:id_user, :id_sampah, "setor", :berat_kg, :total_rp, "pending")'
);
$stmt->execute([
    'id_user'   => $_SESSION['id_user'],
    'id_sampah' => $idSampah,
    'berat_kg'  => $beratKg,
    'total_rp'  => $totalRp,
]);

redirect('siswa/riwayat.php?sukses=1');
