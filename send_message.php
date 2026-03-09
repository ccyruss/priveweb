<?php
require_once 'inc/db.php';
require_once 'inc/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = clean($_POST['full_name']);
    $email = clean($_POST['email']);
    $phone = clean($_POST['phone'] ?? '');
    $subject = clean($_POST['subject']);
    $message = clean($_POST['message']);

    // Log to DB
    $stmt = $pdo->prepare("INSERT INTO messages (full_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$full_name, $email, $phone, $subject, $message]);

    // Send Mail
    require 'vendor/autoload.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();

        $mail->isSMTP();
        $mail->Host = $settings['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $settings['smtp_user'];
        $mail->Password = $settings['smtp_pass'];
        $mail->Port = $settings['smtp_port'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($settings['smtp_user'], $settings['title']);
        $mail->addAddress($settings['email']);
        $mail->addReplyTo($email, $full_name);

        $mail->isHTML(true);
        $mail->Subject = 'Yeni Mesaj: ' . $subject;
        $mail->Body = "
            <h3>İletişim Formu Mesajı</h3>
            <p><strong>Ad Soyad:</strong> $full_name</p>
            <p><strong>E-posta:</strong> $email</p>
            <p><strong>Telefon:</strong> $phone</p>
            <p><strong>Konu:</strong> $subject</p>
            <p><strong>Mesaj:</strong><br>$message</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Log mail error if needed
    }

    alert(lang('Mesajınız başarıyla iletildi.', 'Your message has been sent successfully.'), 'success');
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['status' => 'success', 'message' => lang('Mesajınız başarıyla iletildi.', 'Your message has been sent successfully.')]);
        exit;
    }
}
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    exit;
}
redirect('contact.php');
?>