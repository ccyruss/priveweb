<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (isset($_SESSION['admin_auth'])) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = clean($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($pass, $admin['password'])) {
        $_SESSION['admin_auth'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        redirect('index.php');
    } else {
        $error = "Geçersiz kullanıcı adı veya şifre.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Prive | Admin Girişi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../admin-template/xhtml/vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="../admin-template/xhtml/css/style.css" rel="stylesheet">
</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-3">
                                        <a href="index.php"><img src="../assets/images/logo/logo.png"
                                                style="max-width: 150px;" alt=""></a>
                                    </div>
                                    <h4 class="text-center mb-4">Admin Paneline Giriş Yap</h4>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form action="login.php" method="POST">
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Kullanıcı Adı</strong></label>
                                            <input type="text" name="username" class="form-control" required>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="mb-1"><strong>Şifre</strong></label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../admin-template/xhtml/vendor/global/global.min.js"></script>
    <script src="../admin-template/xhtml/js/custom.min.js"></script>
</body>

</html>