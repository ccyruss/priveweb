<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth'])) {
    redirect('login.php');
}

$unread_count = $pdo->query("SELECT COUNT(*) FROM messages WHERE is_read = 0")->fetchColumn();
?>
<?php include 'header.php'; ?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-xxl-6 col-sm-6">
                <div class="widget-stat card">
                    <div class="card-body p-4">
                        <div class="media ai-icon">
                            <span class="me-3 bgl-primary text-primary">
                                <i class="ti-user"></i>
                            </span>
                            <div class="media-body">
                                <p class="mb-1">Yeni Mesajlar</p>
                                <h4 class="mb-0">
                                    <?php echo $unread_count; ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- More stats can be added here -->
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Son Gelen Mesajlar</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><strong>Ad Soyad</strong></th>
                                        <th><strong>Email</strong></th>
                                        <th><strong>Konu</strong></th>
                                        <th><strong>Tarih</strong></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $msgs = $pdo->query("SELECT * FROM messages ORDER BY id DESC LIMIT 5")->fetchAll();
                                    foreach ($msgs as $m):
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $m['full_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $m['email']; ?>
                                            </td>
                                            <td>
                                                <?php echo $m['subject']; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($m['created_at'])); ?>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="messages.php?id=<?php echo $m['id']; ?>"
                                                        class="btn btn-primary shadow btn-xs sharp me-1"><i
                                                            class="fa fa-eye"></i></a>
                                                </div>
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
</div>

<?php include 'footer.php'; ?>