<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM instagram_feed WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Instagram gönderisi silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'link' => $_POST['link'],
        'sort_order' => (int) $_POST['sort_order'],
        'status' => (int) $_POST['status']
    ];

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $name = time() . '_' . $_FILES['image']['name'];
        if (!is_dir('../uploads/instagram'))
            mkdir('../uploads/instagram', 0777, true);
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/instagram/' . $name);
        $data['image'] = $name;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $sql = "UPDATE instagram_feed SET link=:link, sort_order=:sort_order, status=:status";
        if (isset($data['image']))
            $sql .= ", image=:image";
        $sql .= " WHERE id=:id";
        $pdo->prepare($sql)->execute($data + ['id' => $_POST['id']]);
        alert('Gönderi güncellendi.', 'success');
    } else {
        $sql = "INSERT INTO instagram_feed (image, link, sort_order, status) VALUES (:image, :link, :sort_order, :status)";
        $pdo->prepare($sql)->execute($data);
        alert('Gönderi eklendi.', 'success');
    }
}

$feeds = $pdo->query("SELECT * FROM instagram_feed ORDER BY sort_order ASC, id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Instagram Akışı</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInsta">Yeni
                            Ekle</button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Görsel</th>
                                    <th>Link</th>
                                    <th>Sıra</th>
                                    <th class="text-center">Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($feeds as $f): ?>
                                    <tr>
                                        <td>
                                            <img src="../uploads/instagram/<?php echo $f['image']; ?>" width="50"
                                                height="50" style="object-fit: cover; border-radius: 5px;">
                                        </td>
                                        <td><a href="<?php echo $f['link']; ?>" target="_blank" class="text-primary">
                                                <?php echo substr($f['link'], 0, 30); ?>...
                                            </a></td>
                                        <td>
                                            <?php echo $f['sort_order']; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                <input class="form-check-input status-toggle m-0" type="checkbox"
                                                    role="switch" data-id="<?php echo $f['id']; ?>"
                                                    data-table="instagram_feed" <?php echo $f['status'] ? 'checked' : ''; ?>
                                                    style="cursor: pointer; width: 40px; height: 20px;">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-xs sharp me-1" data-bs-toggle="modal"
                                                data-bs-target="#editInsta<?php echo $f['id']; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a href="?delete=<?php echo $f['id']; ?>" class="btn btn-danger btn-xs sharp"
                                                onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editInsta<?php echo $f['id']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="instagram.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Gönderi Düzenle</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Görsel</label>
                                                            <input type="file" name="image" class="form-control">
                                                            <img src="../uploads/instagram/<?php echo $f['image']; ?>"
                                                                width="100" class="mt-2">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Link</label>
                                                            <input type="url" name="link" class="form-control"
                                                                value="<?php echo $f['link']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Sıra</label>
                                                            <input type="number" name="sort_order" class="form-control"
                                                                value="<?php echo $f['sort_order']; ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Durum</label>
                                                            <select name="status" class="form-control">
                                                                <option value="1" <?php echo $f['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                                                <option value="0" <?php echo $f['status'] == 0 ? 'selected' : ''; ?>>Pasif</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Güncelle</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addInsta">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="instagram.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Instagram Gönderisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Görsel</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Link</label>
                        <input type="url" name="link" class="form-control" placeholder="https://instagram.com/p/..."
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Sıra</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Durum</label>
                        <select name="status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        // AJAX Status Toggle
        $(document).on('change', '.status-toggle', function () {
            var id = $(this).data('id');
            var table = $(this).data('table');
            var status = $(this).is(':checked') ? 1 : 0;
            var column = $(this).data('column') || 'status';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    action: 'update_status',
                    id: id,
                    table: table,
                    status: status,
                    column: column
                },
                success: function (response) {
                    var res = JSON.parse(response);
                    if(res.status != 'success') {
                        alert('Güncelleme başarısız: ' + res.message);
                    }
                },
                error: function () {
                    alert('Bir hata oluştu.');
                }
            });
        });
    });
</script>