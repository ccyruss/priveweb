<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $table = $_POST['table'];
    $id = (int) $_POST['id'];
    $status = (int) $_POST['status'];
    $column = isset($_POST['column']) ? $_POST['column'] : 'status';

    // Whitelist tables for security
    $allowed_tables = ['tours', 'categories', 'blogs', 'faqs', 'instagram_feed', 'gallery', 'sliders'];
    if (!in_array($table, $allowed_tables)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid table']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE `$table` SET `$column` = :status WHERE id = :id");
    $result = $stmt->execute(['status' => $status, 'id' => $id]);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Update failed']);
    }
    exit;
}
