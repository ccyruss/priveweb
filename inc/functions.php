<?php
// CORE PHP - Helper Functions

/**
 * Base URL helper
 */
function base_url($path = '')
{
    // Adjust this to your project subdirectory if needed
    $base = '/Prive-Web/';
    return $base . ltrim($path, '/');
}

/**
 * Sanitize input
 */
function clean($data)
{
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    return htmlspecialchars(trim($data));
}

/**
 * Generate SEO friendly URL slug
 */
function slugify($text)
{
    if (empty($text))
        return '';

    // First, decode entities to handle things like &#039;
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

    // Turkish characters replacement
    $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '‘', '’', '“', '”', "'", '"');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', '', '', '', '', '', '');
    $text = str_replace($find, $replace, $text);

    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Get setting value
 */
function getSetting($key)
{
    global $pdo;
    $stmt = $pdo->query("SELECT $key FROM settings WHERE id = 1");
    return $stmt->fetchColumn();
}

/**
 * Language helper
 */
function lang($tr, $en)
{
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'tr';
    return ($lang == 'tr') ? $tr : $en;
}

/**
 * Redirect
 */
function redirect($url)
{
    header("Location: $url");
    exit;
}

/**
 * Alert message
 */
function alert($msg, $type = 'success')
{
    $_SESSION['alert'] = ['msg' => $msg, 'type' => $type];
}

/**
 * Shorten text and decode entities
 */
function shorten($text, $limit = 100)
{
    if (empty($text))
        return '';
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    if (mb_strlen($text) <= $limit)
        return $text;
    return mb_substr($text, 0, $limit) . '...';
}
/**
 * Get Turkish month name
 */
function getMonthNameTurkish($monthNumber)
{
    $months = [
        1 => 'Ocak',
        2 => 'Şubat',
        3 => 'Mart',
        4 => 'Nisan',
        5 => 'Mayıs',
        6 => 'Haziran',
        7 => 'Temmuz',
        8 => 'Ağustos',
        9 => 'Eylül',
        10 => 'Ekim',
        11 => 'Kasım',
        12 => 'Aralık'
    ];
    return isset($months[$monthNumber]) ? $months[$monthNumber] : '';
}
?>