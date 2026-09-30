<?php
/**
 * Konfigurasi umum aplikasi.
 * File ini di-include di HAMPIR SEMUA halaman (lewat header.php),
 * jadi tempat yang tepat untuk hal-hal global seperti session.
 */

// session_start() harus dipanggil SEBELUM ada output HTML apapun,
// makanya file ini selalu di-include paling atas.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL — nanti dipakai untuk href/src supaya path gak berantakan
// kalau folder proyek dipindah. Sesuaikan dengan nama folder di htdocs/www kamu.
define('BASE_URL', '/banksampah/');

// Koneksi database
require_once __DIR__ . '/database.php';

/**
 * Helper kecil: redirect lalu berhenti eksekusi.
 * Dipakai berkali-kali nanti di step 2 (auth) dan seterusnya.
 */
function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}
