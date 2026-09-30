<?php
require_once __DIR__ . '/config/app.php';

// Kalau belum login -> ke halaman login.
// Kalau sudah login -> langsung ke dashboard sesuai role, jangan suruh login lagi.
if (empty($_SESSION['id_user'])) {
    redirect('auth/login.php');
}

switch ($_SESSION['role']) {
    case 'admin':
        redirect('admin/index.php');
    case 'siswa':
    case 'guru':
        redirect('siswa/index.php');
    case 'pengepul':
        redirect('pengepul/index.php');
    default:
        redirect('auth/login.php');
}
