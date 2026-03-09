<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM tours WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Tur silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tr = clean($_POST['title_tr']);
    $en = clean($_POST['title_en']);
    $data = [
        'cat_id' => (int) $_POST['cat_id'],
        'title_tr' => $tr,
        'title_en' => $en,
        'slug_tr' => slugify($tr),
        'slug_en' => slugify($en),
        'location_tr' => clean($_POST['location_tr']),
        'location_en' => clean($_POST['location_en']),
        'duration_tr' => clean($_POST['duration_tr']),
        'duration_en' => clean($_POST['duration_en']),
        'short_desc_tr' => clean($_POST['short_desc_tr']),
        'short_desc_en' => clean($_POST['short_desc_en']),
        'content_tr' => $_POST['content_tr'],
        'content_en' => $_POST['content_en'],

        // NEW FIELDS
        'advance_facilities_tr' => $_POST['advance_facilities_tr'] ?? '',
        'advance_facilities_en' => $_POST['advance_facilities_en'] ?? '',
        'expect_desc_tr' => $_POST['expect_desc_tr'] ?? '',
        'expect_desc_en' => $_POST['expect_desc_en'] ?? '',
        'departure_location_tr' => clean($_POST['departure_location_tr'] ?? ''),
        'departure_location_en' => clean($_POST['departure_location_en'] ?? ''),
        'departure_time' => clean($_POST['departure_time'] ?? ''),
        'return_time' => clean($_POST['return_time'] ?? ''),
        'price' => (float) ($_POST['price'] ?? 0),
        'original_price' => (float) ($_POST['original_price'] ?? 0),
        'max_guests' => (int) ($_POST['max_guests'] ?? 0),
        'map_iframe' => $_POST['map_iframe'] ?? '',

        'video_url' => clean($_POST['video_url']),
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
        'is_featured' => (int) ($_POST['is_featured'] ?? 0),
        'status' => (int) $_POST['status']
    ];

    if (!empty($_FILES['main_image']['name'])) {
        $name = time() . '_m_' . $_FILES['main_image']['name'];
        move_uploaded_file($_FILES['main_image']['tmp_name'], '../uploads/tours/' . $name);
        $data['main_image'] = $name;
    }
    if (!empty($_FILES['hover_image']['name'])) {
        $name = time() . '_h_' . $_FILES['hover_image']['name'];
        move_uploaded_file($_FILES['hover_image']['tmp_name'], '../uploads/tours/' . $name);
        $data['hover_image'] = $name;
    }
    if (!empty($_FILES['banner_image']['name'])) {
        $name = time() . '_b_' . $_FILES['banner_image']['name'];
        move_uploaded_file($_FILES['banner_image']['tmp_name'], '../uploads/tours/' . $name);
        $data['banner_image'] = $name;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $sql = "UPDATE tours SET cat_id=:cat_id, title_tr=:title_tr, title_en=:title_en, slug_tr=:slug_tr, slug_en=:slug_en, 
                location_tr=:location_tr, location_en=:location_en, duration_tr=:duration_tr, duration_en=:duration_en, 
                short_desc_tr=:short_desc_tr, short_desc_en=:short_desc_en, content_tr=:content_tr, content_en=:content_en, 
                advance_facilities_tr=:advance_facilities_tr, advance_facilities_en=:advance_facilities_en,
                expect_desc_tr=:expect_desc_tr, expect_desc_en=:expect_desc_en, departure_location_tr=:departure_location_tr,
                departure_location_en=:departure_location_en, departure_time=:departure_time, return_time=:return_time,
                price=:price, original_price=:original_price, max_guests=:max_guests, map_iframe=:map_iframe,
                video_url=:video_url, start_date=:start_date, end_date=:end_date, is_featured=:is_featured, status=:status";
        if (isset($data['main_image']))
            $sql .= ", main_image=:main_image";
        if (isset($data['hover_image']))
            $sql .= ", hover_image=:hover_image";
        if (isset($data['banner_image']))
            $sql .= ", banner_image=:banner_image";
        $sql .= " WHERE id = :id";
        $data['id'] = $_POST['id'];
        $pdo->prepare($sql)->execute($data);
    } else {
        // Insert
        if (!isset($data['main_image']))
            $data['main_image'] = '';
        if (!isset($data['hover_image']))
            $data['hover_image'] = '';
        if (!isset($data['banner_image']))
            $data['banner_image'] = '';
        $pdo->prepare("INSERT INTO tours (cat_id, title_tr, title_en, slug_tr, slug_en, location_tr, location_en, duration_tr, duration_en, 
                        short_desc_tr, short_desc_en, content_tr, content_en, advance_facilities_tr, advance_facilities_en, expect_desc_tr, expect_desc_en, departure_location_tr, departure_location_en, departure_time, return_time, price, original_price, max_guests, map_iframe, video_url, start_date, end_date, is_featured, status, main_image, hover_image, banner_image) 
                       VALUES (:cat_id, :title_tr, :title_en, :slug_tr, :slug_en, :location_tr, :location_en, :duration_tr, :duration_en, 
                        :short_desc_tr, :short_desc_en, :content_tr, :content_en, :advance_facilities_tr, :advance_facilities_en, :expect_desc_tr, :expect_desc_en, :departure_location_tr, :departure_location_en, :departure_time, :return_time, :price, :original_price, :max_guests, :map_iframe, :video_url, :start_date, :end_date, :is_featured, :status, :main_image, :hover_image, :banner_image)")
            ->execute($data);
    }
    alert('Tur başarıyla kaydedildi.', 'success');
}

$tours = $pdo->query("SELECT * FROM tours ORDER BY id DESC")->fetchAll();
$cats = $pdo->query("SELECT * FROM categories WHERE status = 1")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end mb-3"><button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#addTour">Yeni Tur Ekle</button></div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tur Yönetimi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Görsel</th>
                                        <th>Tur Adı</th>
                                        <th>Lokasyon</th>
                                        <th class="text-center">Öne Çıkan</th>
                                        <th class="text-center">Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tours as $t): ?>
                                        <tr>
                                            <td><img src="../uploads/tours/<?php echo $t['main_image']; ?>" width="50"></td>
                                            <td>
                                                <?php echo $t['title_tr']; ?>
                                            </td>
                                            <td>
                                                <?php echo $t['location_tr']; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                    <input class="form-check-input status-toggle m-0" type="checkbox" role="switch"
                                                        data-id="<?php echo $t['id']; ?>" data-table="tours"
                                                        data-column="is_featured"
                                                        <?php echo $t['is_featured'] ? 'checked' : ''; ?>
                                                        style="cursor: pointer; width: 40px; height: 20px;">
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                    <input class="form-check-input status-toggle m-0" type="checkbox"
                                                        role="switch" data-id="<?php echo $t['id']; ?>" data-table="tours"
                                                        <?php echo $t['status'] ? 'checked' : ''; ?>
                                                        style="cursor: pointer; width: 40px; height: 20px;">
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs sharp edit-tour" data-bs-toggle="modal"
                                                    data-bs-target="#addTour" data-id="<?php echo $t['id']; ?>"
                                                    data-cat_id="<?php echo $t['cat_id']; ?>"
                                                    data-price="<?php echo $t['price']; ?>"
                                                    data-original_price="<?php echo $t['original_price']; ?>"
                                                    data-title_tr="<?php echo htmlspecialchars($t['title_tr']); ?>"
                                                    data-title_en="<?php echo htmlspecialchars($t['title_en']); ?>"
                                                    data-location_tr="<?php echo htmlspecialchars($t['location_tr']); ?>"
                                                    data-location_en="<?php echo htmlspecialchars($t['location_en']); ?>"
                                                    data-duration_tr="<?php echo htmlspecialchars($t['duration_tr']); ?>"
                                                    data-duration_en="<?php echo htmlspecialchars($t['duration_en']); ?>"
                                                    data-departure_location_tr="<?php echo htmlspecialchars($t['departure_location_tr']); ?>"
                                                    data-departure_location_en="<?php echo htmlspecialchars($t['departure_location_en']); ?>"
                                                    data-departure_time="<?php echo $t['departure_time']; ?>"
                                                    data-return_time="<?php echo $t['return_time']; ?>"
                                                    data-max_guests="<?php echo $t['max_guests']; ?>"
                                                    data-map_iframe="<?php echo htmlspecialchars($t['map_iframe']); ?>"
                                                    data-short_desc_tr="<?php echo htmlspecialchars($t['short_desc_tr']); ?>"
                                                    data-short_desc_en="<?php echo htmlspecialchars($t['short_desc_en']); ?>"
                                                    data-content_tr="<?php echo htmlspecialchars($t['content_tr']); ?>"
                                                    data-content_en="<?php echo htmlspecialchars($t['content_en']); ?>"
                                                    data-advance_facilities_tr="<?php echo htmlspecialchars($t['advance_facilities_tr']); ?>"
                                                    data-advance_facilities_en="<?php echo htmlspecialchars($t['advance_facilities_en']); ?>"
                                                    data-expect_desc_tr="<?php echo htmlspecialchars($t['expect_desc_tr']); ?>"
                                                    data-expect_desc_en="<?php echo htmlspecialchars($t['expect_desc_en']); ?>"
                                                    data-video_url="<?php echo $t['video_url']; ?>"
                                                    data-start_date="<?php echo !empty($t['start_date']) ? date('Y-m-d\TH:i', strtotime($t['start_date'])) : ''; ?>"
                                                    data-end_date="<?php echo !empty($t['end_date']) ? date('Y-m-d\TH:i', strtotime($t['end_date'])) : ''; ?>"
                                                    data-is_featured="<?php echo $t['is_featured']; ?>"
                                                    data-banner_image="<?php echo $t['banner_image']; ?>"
                                                    data-status="<?php echo $t['status']; ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                <a href="tours_manage_details.php?id=<?php echo $t['id']; ?>"
                                                    class="btn btn-warning btn-xs"
                                                    title="Gelişmiş Düzenleme (Plan, SSS vb)"><i class="fa fa-list"></i>
                                                    Detaylar</a>
                                                <a href="?delete=<?php echo $t['id']; ?>"
                                                    class="btn btn-danger btn-xs sharp"
                                                    onclick="return confirm('Bu turu silmek istediğinize emin misiniz?');"><i
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

<div class="modal fade" id="addTour">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="tours.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Yeni Tur</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="tour_id">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Kategori</label>
                            <select name="cat_id" id="cat_id" class="form-control">
                                <?php foreach ($cats as $c): ?>
                                    <option value="<?php echo $c['id']; ?>">
                                        <?php echo $c['name_tr']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3"><label>Fiyat ($)</label><input type="text" name="price" id="price"
                                class="form-control" value="0"></div>
                        <div class="col-md-3 mb-3"><label>Eski Fiyat ($)</label><input type="text" name="original_price"
                                id="original_price" class="form-control" value="0"></div>

                        <div class="col-md-6 mb-3"><label>Tur Başlığı (TR)</label><input type="text" name="title_tr"
                                id="title_tr" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Tur Başlığı (EN)</label><input type="text" name="title_en"
                                id="title_en" class="form-control" required></div>
                        <div class="col-md-3 mb-3"><label>Lokasyon (TR)</label><input type="text" name="location_tr"
                                id="location_tr" class="form-control"></div>
                        <div class="col-md-3 mb-3"><label>Lokasyon (EN)</label><input type="text" name="location_en"
                                id="location_en" class="form-control"></div>
                        <div class="col-md-3 mb-3"><label>Süre (TR)</label><input type="text" name="duration_tr"
                                id="duration_tr" class="form-control"></div>
                        <div class="col-md-3 mb-3"><label>Süre (EN)</label><input type="text" name="duration_en"
                                id="duration_en" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Ana Görsel</label><input type="file" name="main_image"
                                class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Hover Görsel</label><input type="file" name="hover_image"
                                class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Banner Görseli (Detay Sayfası)</label><input type="file" name="banner_image"
                                class="form-control"></div>

                        <!-- NEW FIELDS -->
                        <div class="col-md-4 mb-3"><label>Kalkış/Dönüş Yeri (TR)</label><input type="text"
                                name="departure_location_tr" id="departure_location_tr" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Kalkış/Dönüş Yeri (EN)</label><input type="text"
                                name="departure_location_en" id="departure_location_en" class="form-control"></div>
                        <div class="col-md-2 mb-3"><label>Kalkış Saati</label><input type="text" name="departure_time"
                                id="departure_time" class="form-control"></div>
                        <div class="col-md-2 mb-3"><label>Dönüş Saati</label><input type="text" name="return_time"
                                id="return_time" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Maks. Misafir</label><input type="number" name="max_guests"
                                id="max_guests" class="form-control" value="0"></div>
                        <div class="col-md-8 mb-3"><label>Harita (Iframe)</label><textarea name="map_iframe"
                                id="map_iframe" class="form-control"></textarea></div>

                        <div class="col-12 mb-3"><label>Kısa Açıklama (TR)</label><textarea name="short_desc_tr"
                                id="short_desc_tr" class="form-control"></textarea></div>
                        <div class="col-12 mb-3"><label>Kısa Açıklama (EN)</label><textarea name="short_desc_en"
                                id="short_desc_en" class="form-control"></textarea></div>
                        <div class="col-12 mb-3"><label>İçerik (TR)</label><textarea name="content_tr" id="content_tr"
                                class="form-control summernote"></textarea></div>
                        <div class="col-12 mb-3"><label>İçerik (EN)</label><textarea name="content_en" id="content_en"
                                class="form-control summernote"></textarea></div>

                        <div class="col-12 mb-3"><label>Gelişmiş Tesisler (TR)</label><textarea
                                name="advance_facilities_tr" id="advance_facilities_tr"
                                class="form-control summernote"></textarea></div>
                        <div class="col-12 mb-3"><label>Gelişmiş Tesisler (EN)</label><textarea
                                name="advance_facilities_en" id="advance_facilities_en"
                                class="form-control summernote"></textarea></div>
                        <div class="col-12 mb-3"><label>Sizi Neler Bekliyor Açıklama (TR)</label><textarea
                                name="expect_desc_tr" id="expect_desc_tr" class="form-control summernote"></textarea>
                        </div>
                        <div class="col-12 mb-3"><label>Sizi Neler Bekliyor Açıklama (EN)</label><textarea
                                name="expect_desc_en" id="expect_desc_en" class="form-control summernote"></textarea>
                        </div>

                        <div class="col-md-4 mb-3"><label>YouTube Video URL'si</label><input type="text"
                                name="video_url" id="video_url" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Başlangıç Tarihi</label><input type="datetime-local"
                                name="start_date" id="start_date" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Bitiş Tarihi</label><input type="datetime-local"
                                name="end_date" id="end_date" class="form-control"></div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check"><input type="checkbox" name="is_featured" id="is_featured" value="1"
                                    class="form-check-input"><label class="form-check-label">Ana Sayfa Öne Çıkan</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3"><label>Durum</label><select name="status" id="status"
                                class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Pasif</option>
                            </select></div>
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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.edit-tour').click(function () {
            var data = $(this).data();
            $('#tour_id').val(data.id);
            $('#cat_id').val(data.cat_id);
            $('#price').val(data.price);
            $('#original_price').val(data.original_price);
            $('#title_tr').val(data.title_tr);
            $('#title_en').val(data.title_en);
            $('#location_tr').val(data.location_tr);
            $('#location_en').val(data.location_en);
            $('#duration_tr').val(data.duration_tr);
            $('#duration_en').val(data.duration_en);
            $('#departure_location_tr').val(data.departure_location_tr);
            $('#departure_location_en').val(data.departure_location_en);
            $('#departure_time').val(data.departure_time);
            $('#return_time').val(data.return_time);
            $('#max_guests').val(data.max_guests);
            $('#map_iframe').val(data.map_iframe);
            $('#short_desc_tr').val(data.short_desc_tr);
            $('#short_desc_en').val(data.short_desc_en);

            $('#content_tr').summernote('code', data.content_tr);
            $('#content_en').summernote('code', data.content_en);
            $('#advance_facilities_tr').summernote('code', data.advance_facilities_tr);
            $('#advance_facilities_en').summernote('code', data.advance_facilities_en);
            $('#expect_desc_tr').summernote('code', data.expect_desc_tr);
            $('#expect_desc_en').summernote('code', data.expect_desc_en);

            $('#video_url').val(data.video_url);
            $('#start_date').val(data.start_date);
            $('#end_date').val(data.end_date);
            $('#is_featured').prop('checked', data.is_featured == 1);
            $('#status').val(data.status);

            $('#modalTitle').text('Turu Düzenle: ' + data.title_tr);
        });

        $('#addTour').on('hidden.bs.modal', function () {
            $('#tour_id').val('');
            $('#modalTitle').text('Yeni Tur');
            $('form')[0].reset();
            $('.summernote').summernote('code', '');
        });

        // AJAX Status Toggle
        $('.status-toggle').change(function () {
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
                    if(res.status == 'success') {
                        // Success notification could be added here
                    } else {
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