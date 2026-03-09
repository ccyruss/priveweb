<?php
date_default_timezone_set('Europe/Istanbul');
error_reporting(0);
ini_set('display_errors', 0);
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

     $now = date('Y-m-d H:i:s');
     $pdo->exec("UPDATE tours SET status = 0 WHERE end_date IS NOT NULL AND end_date < '$now' AND status = 1");

} catch (\PDOException $e) {

     die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>