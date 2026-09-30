<?php
require_once __DIR__ . '/../includes/auth_check.php';
requireRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin/sampah.php');
}

$aksi = $_POST['aksi'] ?? '';

if ($aksi === 'tambah') {
    $jenis = trim($_POST['jenis_sampah'] ?? '');
    $harga = (int)($_POST['harga_per_kg'] ?? 0);

    if ($jenis === '' || $harga < 0) {
        redirect('admin/sampah.php');
    }

    $stmt = $pdo->prepare('INSERT INTO sampah (jenis_sampah, harga_per_kg, stok_kg) VALUES (:jenis, :harga, 0)');
    $stmt->execute(['jenis' => $jenis, 'harga' => $harga]);
    redirect('admin/sampah.php?sukses=1');
}

if ($aksi === 'edit') {
    $id    = (int)($_POST['id_sampah'] ?? 0);
    $jenis = trim($_POST['jenis_sampah'] ?? '');
    $harga = (int)($_POST['harga_per_kg'] ?? 0);
    $stok  = (float)($_POST['stok_kg'] ?? 0);

    if ($id <= 0 || $jenis === '' || $harga < 0 || $stok < 0) {
        redirect('admin/sampah.php');
    }

    $stmt = $pdo->prepare(
        'UPDATE sampah SET jenis_sampah = :jenis, harga_per_kg = :harga, stok_kg = :stok WHERE id_sampah = :id'
    );
    $stmt->execute(['jenis' => $jenis, 'harga' => $harga, 'stok' => $stok, 'id' => $id]);
    redirect('admin/sampah.php?sukses=1');
}

if ($aksi === 'hapus') {
    $id = (int)($_POST['id_sampah'] ?? 0);

    // PENTING: tabel `transaksi` punya foreign key ke `sampah` dengan
    // ON DELETE CASCADE — artinya kalau kita hapus jenis sampah yang
    // sudah pernah dipakai, SEMUA riwayat transaksinya IKUT TERHAPUS.
    // Itu bahaya (riwayat keuangan hilang), jadi kita cek dulu manual
    // di sini SEBELUM benar-benar menghapus.
    $cek = $pdo->prepare('SELECT COUNT(*) FROM transaksi WHERE id_sampah = :id');
    $cek->execute(['id' => $id]);

    if ($cek->fetchColumn() > 0) {
        redirect('admin/sampah.php?error=terpakai');
    }

    $stmt = $pdo->prepare('DELETE FROM sampah WHERE id_sampah = :id');
    $stmt->execute(['id' => $id]);
    redirect('admin/sampah.php?sukses=1');
}

redirect('admin/sampah.php');
