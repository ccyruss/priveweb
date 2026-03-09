<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Blog silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tr = clean($_POST['title_tr']);
    $en = clean($_POST['title_en']);
    $data = [
        'title_tr' => $tr,
        'title_en' => $en,
        'slug_tr' => slugify($tr),
        'slug_en' => slugify($en),
        'content_tr' => $_POST['content_tr'],
        'content_en' => $_POST['content_en'],
        'status' => (int) $_POST['status']
    ];

    if (!empty($_FILES['image']['name'])) {
        $name = time() . '_b_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/blog/' . $name);
        $data['image'] = $name;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $sql = "UPDATE blogs SET title_tr=:title_tr, title_en=:title_en, slug_tr=:slug_tr, slug_en=:slug_en, content_tr=:content_tr, content_en=:content_en, status=:status";
        if (isset($data['image']))
            $sql .= ", image=:image";
        $sql .= " WHERE id = :id";
        $data['id'] = $_POST['id'];
        $pdo->prepare($sql)->execute($data);
    } else {
        if (!isset($data['image']))
            $data['image'] = '';
        $pdo->prepare("INSERT INTO blogs (title_tr, title_en, slug_tr, slug_en, content_tr, content_en, status, image) VALUES (:title_tr, :title_en, :slug_tr, :slug_en, :content_tr, :content_en, :status, :image)")
            ->execute($data);
    }
    alert('Blog kaydedildi.', 'success');
}

$blogs = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addBlog">Yeni Blog Ekle</button></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Blog Yönetimi</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Görsel</th>
                                    <th>Başlık (TR)</th>
                                    <th class="text-center">Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($blogs as $b): ?>
                                    <tr>
                                        <td><img src="../uploads/blog/<?php echo $b['image']; ?>" width="50"></td>
                                        <td>
                                            <?php echo $b['title_tr']; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                <input class="form-check-input status-toggle m-0" type="checkbox" role="switch"
                                                    data-id="<?php echo $b['id']; ?>" data-table="blogs"
                                                    <?php echo $b['status'] ? 'checked' : ''; ?>
                                                    style="cursor: pointer; width: 40px; height: 20px;">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs sharp edit-blog" data-bs-toggle="modal"
                                                data-bs-target="#addBlog" data-id="<?php echo $b['id']; ?>"
                                                data-title-tr="<?php echo htmlspecialchars($b['title_tr']); ?>"
                                                data-title-en="<?php echo htmlspecialchars($b['title_en']); ?>"
                                                data-content-tr="<?php echo htmlspecialchars($b['content_tr']); ?>"
                                                data-content-en="<?php echo htmlspecialchars($b['content_en']); ?>"
                                                data-status="<?php echo $b['status']; ?>"><i
                                                    class="fa fa-pencil"></i></button>
                                            <a href="blogs_manage_details.php?id=<?php echo $b['id']; ?>"
                                                class="btn btn-warning btn-xs"
                                                title="Gelişmiş Düzenleme (Galeri vb)"><i class="fa fa-list"></i>
                                                Detaylar</a>
                                            <a href="?delete=<?php echo $b['id']; ?>" class="btn btn-danger btn-xs sharp"
                                                onclick="return confirm('Silmek istediğinize emin misiniz?')"><i
                                                    class="fa fa-trash"></i></a>
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

<div class="modal fade" id="addBlog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="blogs.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Blog İşlemi</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="blog_id">
                    <div class="mb-3"><label>Görsel</label><input type="file" name="image" class="form-control"></div>
                    <div class="mb-3"><label>Başlık (TR)</label><input type="text" name="title_tr" class="form-control"
                            required></div>
                    <div class="mb-3"><label>Başlık (EN)</label><input type="text" name="title_en" class="form-control"
                            required></div>
                    <div class="mb-3"><label>İçerik (TR)</label><textarea name="content_tr"
                            class="form-control"></textarea></div>
                    <div class="mb-3"><label>İçerik (EN)</label><textarea name="content_en"
                            class="form-control"></textarea></div>
                    <div class="mb-3"><label>Durum</label><select name="status" id="blog_status" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Kaydet</button></div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        $('.edit-blog').click(function () {
            var id = $(this).data('id');
            $('#blog_id').val(id);
            $('input[name="title_tr"]').val($(this).data('title-tr'));
            $('input[name="title_en"]').val($(this).data('title-en'));
            $('textarea[name="content_tr"]').val($(this).data('content-tr'));
            $('textarea[name="content_en"]').val($(this).data('content-en'));
            $('#blog_status').val($(this).data('status'));
        });

        $('#addBlog').on('hidden.bs.modal', function () {
            $('#blog_id').val('');
            $('form').find("input[type=text], input[type=number], input[type=file], textarea").val("");
        });

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
                    if (res.status != 'success') {
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