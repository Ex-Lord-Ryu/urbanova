<?php
// config.php: Menyimpan konfigurasi koneksi database

$host      = 'localhost';
$db        = 'urbanova';
$db_user   = 'root';
$db_pass   = '';
$charset   = 'utf8mb4';

// Data Source Name
$dsn        = "mysql:host=$host;dbname=$db;charset=$charset";

// Opsi PDO untuk error handling dan mode fetch
$pdo_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
