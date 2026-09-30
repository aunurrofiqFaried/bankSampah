<?php
require_once __DIR__ . '/../config/app.php';

// Hanya terima request POST. Kalau ada yang buka file ini langsung
// lewat URL (GET), tolak dan lempar balik ke halaman login.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('auth/login.php');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    redirect('auth/login.php?error=kosong');
}

// PENTING: pakai prepared statement (:username), JANGAN concat string SQL.
// Ini yang mencegah SQL Injection.
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

// password_verify() membandingkan password mentah dengan hash di database.
// Kita TIDAK PERNAH menyimpan atau membandingkan password dalam bentuk teks biasa.
if (!$user || !password_verify($password, $user['password'])) {
    redirect('auth/login.php?error=salah');
}

// Login berhasil -> simpan data penting ke session.
// Regenerate session ID untuk mencegah "session fixation attack".
session_regenerate_id(true);

$_SESSION['id_user'] = $user['id_user'];
$_SESSION['nama']    = $user['nama'];
$_SESSION['role']    = $user['role'];

// Redirect sesuai role masing-masing.
switch ($user['role']) {
    case 'admin':
        redirect('admin/index.php');
        break;
    case 'siswa':
    case 'guru':
        redirect('siswa/index.php');
        break;
    case 'pengepul':
        redirect('pengepul/index.php');
        break;
    default:
        redirect('auth/login.php');
}
