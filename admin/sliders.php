<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $pdo->prepare("DELETE FROM sliders WHERE id = ?")->execute([$id]);
    alert('Slider başarıyla silindi.', 'success');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'title_tr' => clean($_POST['title_tr']),
        'title_en' => clean($_POST['title_en']),
        'subtitle_tr' => clean($_POST['subtitle_tr']),
        'subtitle_en' => clean($_POST['subtitle_en']),
        'button_text_tr' => clean($_POST['button_text_tr']),
        'button_text_en' => clean($_POST['button_text_en']),
        'button_link' => clean($_POST['button_link']),
        'video_url' => clean($_POST['video_url']),
        'status' => (int) $_POST['status'],
        'sort_order' => (int) $_POST['sort_order']
    ];

    if (!empty($_FILES['image']['name'])) {
        $name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/sliders/' . $name);
        $data['image'] = $name;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $sql = "UPDATE sliders SET title_tr=:title_tr, title_en=:title_en, subtitle_tr=:subtitle_tr, subtitle_en=:subtitle_en, 
                button_text_tr=:button_text_tr, button_text_en=:button_text_en, button_link=:button_link, video_url=:video_url, 
                status=:status, sort_order=:sort_order";
        if (isset($data['image']))
            $sql .= ", image=:image";
        $sql .= " WHERE id = :id";
        $data['id'] = $_POST['id'];
        $pdo->prepare($sql)->execute($data);
        alert('Slider güncellendi.', 'success');
    } else {
        // Insert
        if (!isset($data['image']))
            $data['image'] = '';
        $pdo->prepare("INSERT INTO sliders (title_tr, title_en, subtitle_tr, subtitle_en, button_text_tr, button_text_en, button_link, video_url, status, sort_order, image) 
                       VALUES (:title_tr, :title_en, :subtitle_tr, :subtitle_en, :button_text_tr, :button_text_en, :button_link, :video_url, :status, :sort_order, :image)")
            ->execute($data);
        alert('Slider eklendi.', 'success');
    }
}

$sliders = $pdo->query("SELECT * FROM sliders ORDER BY sort_order ASC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Slider Yönetimi</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlider">Yeni
                            Ekle</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Görsel</th>
                                        <th>Başlık (TR)</th>
                                        <th>Sıra</th>
                                        <th class="text-center">Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sliders as $s): ?>
                                        <tr>
                                            <td><img src="../uploads/sliders/<?php echo $s['image']; ?>" width="50"></td>
                                            <td>
                                                <?php echo $s['title_tr']; ?>
                                            </td>
                                            <td>
                                                <?php echo $s['sort_order']; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                    <input class="form-check-input status-toggle m-0" type="checkbox"
                                                        role="switch" data-id="<?php echo $s['id']; ?>" data-table="sliders"
                                                        <?php echo $s['status'] ? 'checked' : ''; ?>
                                                        style="cursor: pointer; width: 40px; height: 20px;">
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs sharp edit-slider" data-bs-toggle="modal"
                                                    data-bs-target="#addSlider" data-id="<?php echo $s['id']; ?>"
                                                    data-title-tr="<?php echo htmlspecialchars($s['title_tr']); ?>"
                                                    data-title-en="<?php echo htmlspecialchars($s['title_en']); ?>"
                                                    data-subtitle-tr="<?php echo htmlspecialchars($s['subtitle_tr']); ?>"
                                                    data-subtitle-en="<?php echo htmlspecialchars($s['subtitle_en']); ?>"
                                                    data-button-text-tr="<?php echo htmlspecialchars($s['button_text_tr']); ?>"
                                                    data-button-text-en="<?php echo htmlspecialchars($s['button_text_en']); ?>"
                                                    data-button-link="<?php echo htmlspecialchars($s['button_link'] ?? ''); ?>"
                                                    data-video-url="<?php echo htmlspecialchars($s['video_url']); ?>"
                                                    data-sort-order="<?php echo $s['sort_order']; ?>"
                                                    data-status="<?php echo $s['status']; ?>"><i
                                                        class="fa fa-pencil"></i></button>
                                                <a href="?delete=<?php echo $s['id']; ?>"
                                                    class="btn btn-danger btn-xs sharp"
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
</div>

<!-- Add Slider Modal -->
<div class="modal fade" id="addSlider">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="sliders.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Slider İşlemi</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="slider_id">
                    <div class="row">
                        <div class="col-6 mb-3"><label>Görsel</label><input type="file" name="image"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>YouTube URL (Opsiyonel)</label><input type="text"
                                name="video_url" class="form-control"></div>
                        <div class="col-6 mb-3"><label>Başlık (TR)</label><input type="text" name="title_tr"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Başlık (EN)</label><input type="text" name="title_en"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Alt Başlık (TR)</label><input type="text" name="subtitle_tr"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Alt Başlık (EN)</label><input type="text" name="subtitle_en"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Buton Metni (TR)</label><input type="text" name="button_text_tr"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Buton Metni (EN)</label><input type="text" name="button_text_en"
                                class="form-control"></div>
                        <div class="col-6 mb-3"><label>Buton Link</label><input type="text" name="button_link"
                                class="form-control"></div>
                        <div class="col-3 mb-3"><label>Sıralama</label><input type="number" name="sort_order"
                                class="form-control" value="0"></div>
                        <div class="col-3 mb-3"><label>Durum</label><select name="status" id="slider_status"
                                class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Kaydet</button></div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        $('.edit-slider').click(function () {
            var id = $(this).data('id');
            $('#slider_id').val(id);
            $('input[name="title_tr"]').val($(this).data('title-tr'));
            $('input[name="title_en"]').val($(this).data('title-en'));
            $('input[name="subtitle_tr"]').val($(this).data('subtitle-tr'));
            $('input[name="subtitle_en"]').val($(this).data('subtitle-en'));
            $('input[name="button_text_tr"]').val($(this).data('button-text-tr'));
            $('input[name="button_text_en"]').val($(this).data('button-text-en'));
            $('input[name="button_link"]').val($(this).data('button-link'));
            $('input[name="video_url"]').val($(this).data('video-url'));
            $('input[name="sort_order"]').val($(this).data('sort-order'));
            $('#slider_status').val($(this).data('status'));
        });

        $('#addSlider').on('hidden.bs.modal', function () {
            $('#slider_id').val('');
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