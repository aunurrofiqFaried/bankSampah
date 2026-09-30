<?php
/**
 * auth_check.php
 * Include file ini di BARIS PALING ATAS setiap halaman yang butuh login
 * (dashboard admin, siswa, pengepul), SEBELUM include header.php.
 *
 * Kenapa dua fungsi terpisah?
 * - requireLogin()       -> cukup pastikan sudah login (siapapun rolenya)
 * - requireRole([...])   -> pastikan sudah login DAN rolenya sesuai
 *   Kalau siswa coba akses halaman admin, dia akan ditolak, bukan cuma
 *   disembunyikan dari menu. Menyembunyikan menu saja tidak cukup aman.
 */

require_once __DIR__ . '/../config/app.php';

function requireLogin(): void
{
    if (empty($_SESSION['id_user'])) {
        redirect('auth/login.php');
    }
}

/**
 * @param string[] $allowedRoles contoh: ['admin'] atau ['siswa', 'guru']
 */
function requireRole(array $allowedRoles): void
{
    requireLogin();
    if (!in_array($_SESSION['role'], $allowedRoles, true)) {
        // Jangan tampilkan halaman kosong saja — kasih pesan yang jelas.
        http_response_code(403);
        die('Akses ditolak. Halaman ini bukan untuk role "' . htmlspecialchars($_SESSION['role']) . '".');
    }
}
