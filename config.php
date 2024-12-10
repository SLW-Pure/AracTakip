<?php
// Veritabanı bilgileri
define('DB_HOST', 'localhost');
define('DB_NAME', 'arac');
define('DB_USER', 'arac');
define('DB_PASS', 'arac12.,.');

// PDO bağlantısı
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
