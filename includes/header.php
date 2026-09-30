<?php
// header.php selalu jadi file PERTAMA yang di-include di setiap halaman.
// Di dalamnya kita load config/app.php supaya session & koneksi DB
// otomatis siap di halaman manapun yang include file ini.
require_once __DIR__ . '/../config/app.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Bank Sampah' : 'Bank Sampah' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Style kita sendiri -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<?php if (empty($noBlobs)): // set $noBlobs = true sebelum include header.php untuk halaman auth ?>
<div class="bg-blobs"><span></span><span></span><span></span></div>
<?php endif; ?>
