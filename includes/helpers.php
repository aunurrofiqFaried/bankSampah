<?php
/**
 * Kumpulan fungsi kecil yang dipakai di lebih dari satu halaman.
 * Supaya tidak copy-paste function badgeStatus() di setiap file.
 */

if (!function_exists('badgeStatus')) {
    function badgeStatus(string $status): string
    {
        return match ($status) {
            'pending'      => '<span class="badge badge-pending">Menunggu Verifikasi</span>',
            'diverifikasi' => '<span class="badge badge-sukses">Terverifikasi</span>',
            'ditolak'      => '<span class="badge badge-ditolak">Ditolak</span>',
            'selesai'      => '<span class="badge badge-sukses">Selesai</span>',
            default        => '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>',
        };
    }
}
