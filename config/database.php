<?php
/**
 * Koneksi database — PAKAI PDO, bukan mysqli lama.
 * Kenapa PDO? Karena support prepared statements native,
 * lebih aman dari SQL Injection, dan lebih gampang di-maintain.
 */

// Ganti sesuai environment kamu (XAMPP/Laragon biasanya seperti ini)
define('DB_HOST', 'localhost');
define('DB_NAME', 'banksampah');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // biar error kelihatan jelas saat development
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // hasil query jadi array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                   // pakai prepared statement asli dari MySQL
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Di production, JANGAN tampilkan $e->getMessage() ke user.
    // Untuk sekarang (development) kita tampilkan biar gampang debug.
    die("Koneksi database gagal: " . $e->getMessage());
}
