<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM messages WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Mesaj silindi.', 'success');
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?")->execute([$id]);
    $msg = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
    $msg->execute([$id]);
    $m = $msg->fetch();
}

$messages = $pdo->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <?php if (isset($m)): ?>
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Mesaj Detayı</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Gönderen:</strong>
                                <?php echo $m['full_name']; ?> (
                                <?php echo $m['email']; ?>)
                            </p>
                            <p><strong>Firma Adı:</strong>
                                <?php echo $m['company_name']; ?>
                            </p>
                            <p><strong>Telefon:</strong>
                                <?php echo $m['phone']; ?>
                            </p>
                            <p><strong>Konu:</strong>
                                <?php echo $m['subject']; ?>
                            </p>
                            <p><strong>Tarih:</strong>
                                <?php echo date('d.m.Y H:i', strtotime($m['created_at'])); ?>
                            </p>
                            <hr>
                            <p><strong>Mesaj:</strong><br>
                                <?php echo nl2br($m['message']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gelen Mesajlar</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Durum</th>
                                    <th>Ad Soyad</th>
                                    <th>Konu</th>
                                    <th>Tarih</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg): ?>
                                    <tr class="<?php echo $msg['is_read'] ? '' : 'table-info'; ?>">
                                        <td>
                                            <?php echo $msg['is_read'] ? 'Okundu' : 'Yeni'; ?>
                                        </td>
                                        <td>
                                            <?php echo $msg['full_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $msg['subject']; ?>
                                        </td>
                                        <td>
                                            <?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?>
                                        </td>
                                        <td>
                                            <a href="?id=<?php echo $msg['id']; ?>"
                                                class="btn btn-primary btn-xs sharp me-1"><i class="fa fa-eye"></i></a>
                                            <a href="?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-xs sharp"
                                                onclick="return confirm('Silinsin mi?')"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>