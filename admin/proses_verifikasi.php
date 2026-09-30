<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/verifikasi.php');
}

$idTransaksi = (int)($_POST['id_transaksi'] ?? 0);
$aksi        = $_POST['aksi'] ?? '';

if ($idTransaksi <= 0 || !in_array($aksi, ['terima', 'tolak'], true)) {
    redirect('admin/verifikasi.php');
}

// Ambil transaksi-nya dulu, dan pastikan statusnya MASIH pending.
// Kenapa dicek lagi di sini (bukan cuma percaya tombol yang diklik)?
// Supaya kalau ada dua admin klik "Terima" bersamaan pada detik yang sama,
// atau admin klik tombol dua kali, saldo TIDAK ditambahkan dua kali.
$stmt = $pdo->prepare('SELECT * FROM transaksi WHERE id_transaksi = :id AND status = "pending" LIMIT 1');
$stmt->execute(['id' => $idTransaksi]);
$transaksi = $stmt->fetch();

if (!$transaksi) {
    // Transaksi tidak ada atau sudah diproses sebelumnya -> abaikan saja.
    redirect('admin/verifikasi.php');
}

// ==========================================================
// TRANSACTION DATABASE: beginTransaction() ... commit()
// Kenapa penting? Ada 2-3 query yang harus SEMUA berhasil atau
// SEMUA gagal bersama — update status transaksi, tambah saldo user,
// tambah stok sampah. Kalau di tengah jalan servernya mati setelah
// saldo ditambah tapi status belum ke-update, data jadi rusak.
// Dengan transaction, kalau salah satu gagal, semuanya dibatalkan (rollback).
// ==========================================================
try {
    $pdo->beginTransaction();

    $statusBaru = $aksi === 'terima' ? 'diverifikasi' : 'ditolak';

    $update = $pdo->prepare(
        'UPDATE transaksi
         SET status = :status, diverifikasi_oleh = :admin, tanggal_verifikasi = NOW()
         WHERE id_transaksi = :id'
    );
    $update->execute([
        'status' => $statusBaru,
        'admin'  => $_SESSION['id_user'],
        'id'     => $idTransaksi,
    ]);

    if ($aksi === 'terima') {
        // Tambah saldo pengirim
        $tambahSaldo = $pdo->prepare('UPDATE users SET saldo = saldo + :nilai WHERE id_user = :id');
        $tambahSaldo->execute([
            'nilai' => $transaksi['total_rp'],
            'id'    => $transaksi['id_user'],
        ]);

        // Tambah stok sampah sekolah (fisik sampahnya sekarang ada di admin)
        $tambahStok = $pdo->prepare('UPDATE sampah SET stok_kg = stok_kg + :berat WHERE id_sampah = :id');
        $tambahStok->execute([
            'berat' => $transaksi['berat_kg'],
            'id'    => $transaksi['id_sampah'],
        ]);
    }
    // Kalau ditolak: tidak ada perubahan saldo maupun stok sama sekali.

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    die('Gagal memproses verifikasi: ' . $e->getMessage());
}

redirect('admin/verifikasi.php?sukses=' . $aksi);
