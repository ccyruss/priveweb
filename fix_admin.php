<?php
require_once 'inc/db.php';
$new_pass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = 'admin'");
if ($stmt->execute([$new_pass])) {
    echo "<h1>Başarılı!</h1>";
    echo "<p>Admin şifresi 'admin123' olarak güncellendi.</p>";
    echo "<p>Güvenliğiniz için bu dosyayı (fix_admin.php) silmeyi unutmayın.</p>";
} else {
    echo "<h1>Hata!</h1>";
    echo "<p>Şifre güncellenemedi.</p>";
}
?>