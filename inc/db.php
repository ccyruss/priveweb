<?php
date_default_timezone_set('Europe/Istanbul');
// CORE PHP - Database Connection
$host = 'localhost';
$db = 'prive_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
     PDO::ATTR_EMULATE_PREPARES => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);

     // Auto-passive logic for tours
     $now = date('Y-m-d H:i:s');
     $pdo->exec("UPDATE tours SET status = 0 WHERE end_date IS NOT NULL AND end_date < '$now' AND status = 1");

} catch (\PDOException $e) {
     // For production, you might want to log this instead of showing it
     die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>