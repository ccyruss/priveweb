<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Galeri öğesi silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $data = [
        'type' => $type,
        'title_tr' => clean($_POST['title_tr']),
        'title_en' => clean($_POST['title_en']),
        'status' => (int) $_POST['status']
    ];

    if ($type == 'photo' && !empty($_FILES['file']['name'])) {
        $name = time() . '_g_' . $_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/gallery/' . $name);
        $data['file'] = $name;
    } else {
        $data['file'] = clean($_POST['video_url']);
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $sql = "UPDATE gallery SET type=:type, title_tr=:title_tr, title_en=:title_en, status=:status";
        if (isset($data['file']))
            $sql .= ", file=:file";
        $sql .= " WHERE id = :id";
        $data['id'] = $_POST['id'];
        $pdo->prepare($sql)->execute($data);
    } else {
        if (!isset($data['file']))
            $data['file'] = '';
        $pdo->prepare("INSERT INTO gallery (type, file, title_tr, title_en, status) VALUES (:type, :file, :title_tr, :title_en, :status)")
            ->execute($data);
    }
    alert('Galeri öğesi kaydedildi.', 'success');
}

$gallery = $pdo->query("SELECT * FROM gallery ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addGallery">Yeni Ekle</button></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Galeri Yönetimi</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tip</th>
                                    <th>Önizleme</th>
                                    <th>Başlık (TR)</th>
                                    <th class="text-center">Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($gallery as $g): ?>
                                    <tr>
                                        <td>
                                            <?php echo $g['type'] == 'photo' ? 'Foto' : 'Video'; ?>
                                        </td>
                                        <td>
                                            <?php if ($g['type'] == 'photo'): ?>
                                                <img src="../uploads/gallery/<?php echo $g['file']; ?>" width="50">
                                            <?php else: ?>
                                                <i class="fa fa-video"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $g['title_tr']; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                <input class="form-check-input status-toggle m-0" type="checkbox"
                                                    role="switch" data-id="<?php echo $g['id']; ?>" data-table="gallery"
                                                    <?php echo $g['status'] ? 'checked' : ''; ?>
                                                    style="cursor: pointer; width: 40px; height: 20px;">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs sharp edit-gallery" data-bs-toggle="modal"
                                                data-bs-target="#addGallery" data-id="<?php echo $g['id']; ?>"
                                                data-type="<?php echo $g['type']; ?>" data-file="<?php echo $g['file']; ?>"
                                                data-title-tr="<?php echo htmlspecialchars($g['title_tr']); ?>"
                                                data-title-en="<?php echo htmlspecialchars($g['title_en']); ?>"
                                                data-status="<?php echo $g['status']; ?>"><i
                                                    class="fa fa-pencil"></i></button>
                                            <a href="?delete=<?php echo $g['id']; ?>" class="btn btn-danger btn-xs sharp"
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

<div class="modal fade" id="addGallery">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="gallery.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Galeri İşlemi</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="gallery_id">
                    <div class="mb-3"><label>Tip</label><select name="type" class="form-control">
                            <option value="photo">Fotoğraf</option>
                            <option value="video">Video (YouTube)</option>
                        </select></div>
                    <div class="mb-3"><label>Görsel (Sadece Foto için)</label><input type="file" name="file"
                            class="form-control"></div>
                    <div class="mb-3"><label>Video URL (Sadece Video için)</label><input type="text" name="video_url"
                            class="form-control"></div>
                    <div class="mb-3"><label>Başlık (TR)</label><input type="text" name="title_tr" class="form-control">
                    </div>
                    <div class="mb-3"><label>Başlık (EN)</label><input type="text" name="title_en" class="form-control">
                    </div>
                    <div class="mb-3"><label>Durum</label><select name="status" id="gallery_status"
                            class="form-control">
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
        $('.edit-gallery').click(function () {
            var id = $(this).data('id');
            var type = $(this).data('type');
            $('#gallery_id').val(id);
            $('select[name="type"]').val(type);
            $('input[name="title_tr"]').val($(this).data('title-tr'));
            $('input[name="title_en"]').val($(this).data('title-en'));
            $('#gallery_status').val($(this).data('status'));

            if(type == 'video') {
                $('input[name="video_url"]').val($(this).data('file'));
            } else {
                $('input[name="video_url"]').val('');
            }
        });

        $('#addGallery').on('hidden.bs.modal', function () {
            $('#gallery_id').val('');
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