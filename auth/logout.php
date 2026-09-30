<?php
require_once __DIR__ . '/../config/app.php';

// Kosongkan semua data session, lalu hancurkan session-nya.
$_SESSION = [];
session_destroy();

redirect('auth/login.php');
