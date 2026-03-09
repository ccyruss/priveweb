<?php
session_start();
$lang = $_GET['l'] ?? 'tr';
if (in_array($lang, ['tr', 'en'])) {
    $_SESSION['lang'] = $lang;
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?: 'index.php'));
exit;
?>