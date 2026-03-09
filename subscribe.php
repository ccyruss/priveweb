<?php
require_once 'inc/db.php';
require_once 'inc/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => lang('Lütfen geçerli bir e-posta adresi girin.', 'Please enter a valid email address.')]);
        exit;
    }

    // Check if already subscribed
    $stmt = $pdo->prepare("SELECT id FROM newsletter WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => lang('Bu e-posta adresi zaten kayıtlı.', 'This email is already subscribed.')]);
        exit;
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (?)");
    if ($stmt->execute([$email])) {
        echo json_encode(['status' => 'success', 'message' => lang('Bültenimize başarıyla abone oldunuz!', 'You have successfully subscribed to our newsletter!')]);
    } else {
        echo json_encode(['status' => 'error', 'message' => lang('Bir hata oluştu, lütfen tekrar deneyin.', 'An error occurred, please try again.')]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>