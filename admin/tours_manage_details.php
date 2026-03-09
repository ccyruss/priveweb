<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

$tour_id = (int) ($_GET['id'] ?? 0);
if (!$tour_id)
    redirect('tours.php');

$stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ?");
$stmt->execute([$tour_id]);
$tour = $stmt->fetch();
if (!$tour)
    redirect('tours.php');

// Handle dynamic insertions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = clean($_POST['update_type']);

    if ($type == 'expects') {
        $pdo->prepare("DELETE FROM tour_expect_list WHERE tour_id = ?")->execute([$tour_id]);
        if (isset($_POST['e_tr'])) {
            foreach ($_POST['e_tr'] as $k => $v) {
                if (!empty($v)) {
                    $pdo->prepare("INSERT INTO tour_expect_list (tour_id, text_tr, text_en) VALUES (?,?,?)")
                        ->execute([$tour_id, clean($v), clean($_POST['e_en'][$k])]);
                }
            }
        }
        alert('Sizi Neler Bekliyor listesi güncellendi.', 'success');
    } else if ($type == 'includes') {
        $pdo->prepare("DELETE FROM tour_includes WHERE tour_id = ?")->execute([$tour_id]);
        if (isset($_POST['i_tr'])) {
            foreach ($_POST['i_tr'] as $k => $v) {
                if (!empty($v)) {
                    $pdo->prepare("INSERT INTO tour_includes (tour_id, text_tr, text_en, is_included) VALUES (?,?,?,?)")
                        ->execute([$tour_id, clean($v), clean($_POST['i_en'][$k]), (int) $_POST['i_status'][$k]]);
                }
            }
        }
        alert('Dahil Olanlar/Hariç liste güncellendi.', 'success');
    } else if ($type == 'amenities') {
        $pdo->prepare("DELETE FROM tour_amenities WHERE tour_id = ?")->execute([$tour_id]);
        if (isset($_POST['a_tr'])) {
            foreach ($_POST['a_tr'] as $k => $v) {
                if (!empty($v)) {
                    $pdo->prepare("INSERT INTO tour_amenities (tour_id, text_tr, text_en) VALUES (?,?,?)")
                        ->execute([$tour_id, clean($v), clean($_POST['a_en'][$k])]);
                }
            }
        }
        alert('Tur Olanakları güncellendi.', 'success');
    } else if ($type == 'itinerary') {
        // Clear all days and items for this tour
        $days = $pdo->query("SELECT id FROM tour_itineraries WHERE tour_id = " . $tour_id)->fetchAll();
        foreach ($days as $d) {
            $pdo->prepare("DELETE FROM tour_itinerary_items WHERE itinerary_id = ?")->execute([$d['id']]);
        }
        $pdo->prepare("DELETE FROM tour_itineraries WHERE tour_id = ?")->execute([$tour_id]);

        // Re-insert days
        if (isset($_POST['day_num'])) {
            foreach ($_POST['day_num'] as $index => $dnum) {
                if (!empty($_POST['day_title_tr'][$index])) {
                    $stmt = $pdo->prepare("INSERT INTO tour_itineraries (tour_id, day_number, title_tr, title_en, content_tr, content_en) VALUES (?,?,?,?,?,?)");
                    $stmt->execute([
                        $tour_id,
                        (int) $dnum,
                        clean($_POST['day_title_tr'][$index]),
                        clean($_POST['day_title_en'][$index]),
                        $_POST['day_content_tr'][$index],
                        $_POST['day_content_en'][$index]
                    ]);
                    $new_itn_id = $pdo->lastInsertId();

                    // Insert items for this day
                    if (isset($_POST['item_tr'][$index])) {
                        foreach ($_POST['item_tr'][$index] as $i_index => $i_tr) {
                            if (!empty($i_tr)) {
                                $i_en = $_POST['item_en'][$index][$i_index] ?? '';
                                $pdo->prepare("INSERT INTO tour_itinerary_items (itinerary_id, text_tr, text_en) VALUES (?,?,?)")
                                    ->execute([$new_itn_id, clean($i_tr), clean($i_en)]);
                            }
                        }
                    }
                }
            }
        }
        alert('Tur Planı güncellendi.', 'success');
    } else if ($type == 'faqs') {
        $pdo->prepare("DELETE FROM tour_faqs WHERE tour_id = ?")->execute([$tour_id]);
        if (isset($_POST['f_q_tr'])) {
            foreach ($_POST['f_q_tr'] as $k => $v) {
                if (!empty($v)) {
                    $pdo->prepare("INSERT INTO tour_faqs (tour_id, question_tr, question_en, answer_tr, answer_en) VALUES (?,?,?,?,?)")
                        ->execute([
                            $tour_id,
                            clean($v),
                            clean($_POST['f_q_en'][$k]),
                            $_POST['f_a_tr'][$k],
                            $_POST['f_a_en'][$k]
                        ]);
                }
            }
        }
        alert('SSS güncellendi.', 'success');
    } else if ($type == 'gallery_photos') {
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $k => $tmp_name) {
                if (!empty($tmp_name)) {
                    $name = time() . '_gal_' . $_FILES['gallery_images']['name'][$k];
                    if (move_uploaded_file($tmp_name, '../uploads/tours/' . $name)) {
                        $pdo->prepare("INSERT INTO tour_gallery (tour_id, image) VALUES (?,?)")->execute([$tour_id, $name]);
                    }
                }
            }
            alert('Fotoğraflar galeriye eklendi.', 'success');
        }
    } else if ($type == 'delete_photo') {
        $photo_id = (int) $_POST['photo_id'];
        $photo = $pdo->query("SELECT image FROM tour_gallery WHERE id = $photo_id")->fetch();
        if ($photo) {
            @unlink('../uploads/tours/' . $photo['image']);
            $pdo->prepare("DELETE FROM tour_gallery WHERE id = ?")->execute([$photo_id]);
            alert('Fotoğraf silindi.', 'success');
        }
    } else if ($type == 'gallery_videos') {
        $pdo->prepare("DELETE FROM tour_videos WHERE tour_id = ?")->execute([$tour_id]);
        if (isset($_POST['v_url'])) {
            foreach ($_POST['v_url'] as $v) {
                if (!empty($v)) {
                    $pdo->prepare("INSERT INTO tour_videos (tour_id, video_url) VALUES (?,?)")
                        ->execute([$tour_id, clean($v)]);
                }
            }
        }
        alert('Video galerisi güncellendi.', 'success');
    }
    redirect("tours_manage_details.php?id=$tour_id");
}

include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <a href="tours.php" class="btn btn-dark"><i class="fa fa-arrow-left"></i> Geri Dön</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white">Gelişmiş Detay Yönetimi:
                            <?php echo $tour['title_tr']; ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#expects">Sizi Neler Bekliyor</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#includes">Dahil/Hariç</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#amenities">Olanaklar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#itinerary">Tur Planı (Günler)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#faqs">SSS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#gallery_photos">Foto Galeri</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#gallery_videos">Video Galeri</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content border border-top-0 p-4">

                            <!-- 1. EXPECTS -->
                            <div class="tab-pane fade show active" id="expects">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="expects">
                                    <div id="expects-container">
                                        <?php
                                        $exps = $pdo->query("SELECT * FROM tour_expect_list WHERE tour_id = $tour_id")->fetchAll();
                                        foreach ($exps as $e): ?>
                                            <div class="row row-item mb-2 align-items-center">
                                                <div class="col-md-5"><input type="text" name="e_tr[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($e['text_tr']); ?>"
                                                        placeholder="Maddeler (TR)"></div>
                                                <div class="col-md-5"><input type="text" name="e_en[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($e['text_en']); ?>"
                                                        placeholder="Maddeler (EN)"></div>
                                                <div class="col-md-2"><button type="button"
                                                        class="btn btn-danger btn-sm remove-row">X</button></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2"
                                        onclick="addRow('expects-container', 'e_tr[]', 'e_en[]')">+ Yeni Satır
                                        Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                            <!-- 2. INCLUDES -->
                            <div class="tab-pane fade" id="includes">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="includes">
                                    <div id="includes-container">
                                        <?php
                                        $incs = $pdo->query("SELECT * FROM tour_includes WHERE tour_id = $tour_id")->fetchAll();
                                        foreach ($incs as $i): ?>
                                            <div class="row row-item mb-2 align-items-center">
                                                <div class="col-md-4"><input type="text" name="i_tr[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($i['text_tr']); ?>"
                                                        placeholder="Madde (TR)"></div>
                                                <div class="col-md-4"><input type="text" name="i_en[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($i['text_en']); ?>"
                                                        placeholder="Madde (EN)"></div>
                                                <div class="col-md-2">
                                                    <select name="i_status[]" class="form-control">
                                                        <option value="1" <?php if ($i['is_included'] == 1)
                                                            echo 'selected'; ?>
                                                            >Dahil</option>
                                                        <option value="0" <?php if ($i['is_included'] == 0)
                                                            echo 'selected'; ?>
                                                            >Hariç (+ Ücretli)</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2"><button type="button"
                                                        class="btn btn-danger btn-sm remove-row">X</button></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2"
                                        onclick="addIncludeRow('includes-container')">+ Yeni Satır Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                            <!-- 3. AMENITIES -->
                            <div class="tab-pane fade" id="amenities">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="amenities">
                                    <div id="amenities-container">
                                        <?php
                                        $amns = $pdo->query("SELECT * FROM tour_amenities WHERE tour_id = $tour_id")->fetchAll();
                                        foreach ($amns as $a): ?>
                                            <div class="row row-item mb-2 align-items-center">
                                                <div class="col-md-5"><input type="text" name="a_tr[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($a['text_tr']); ?>"
                                                        placeholder="Olanak (TR)"></div>
                                                <div class="col-md-5"><input type="text" name="a_en[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($a['text_en']); ?>"
                                                        placeholder="Olanak (EN)"></div>
                                                <div class="col-md-2"><button type="button"
                                                        class="btn btn-danger btn-sm remove-row">X</button></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2"
                                        onclick="addRow('amenities-container', 'a_tr[]', 'a_en[]')">+ Yeni Satır
                                        Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                            <!-- 4. ITINERARY (TOUR PLAN) -->
                            <div class="tab-pane fade" id="itinerary">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="itinerary">
                                    <div id="itinerary-container">
                                        <?php
                                        $itns = $pdo->query("SELECT * FROM tour_itineraries WHERE tour_id = $tour_id ORDER BY day_number ASC")->fetchAll();
                                        $day_idx = 0;
                                        foreach ($itns as $it): ?>
                                            <div class="card border border-primary mb-3 itn-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-2 mb-2"><label>Gün No</label><input type="number"
                                                                name="day_num[<?php echo $day_idx; ?>]" class="form-control"
                                                                value="<?php echo $it['day_number']; ?>"></div>
                                                        <div class="col-md-5 mb-2"><label>Gün Başlığı (TR)</label><input
                                                                type="text" name="day_title_tr[<?php echo $day_idx; ?>]"
                                                                class="form-control"
                                                                value="<?php echo htmlspecialchars($it['title_tr']); ?>">
                                                        </div>
                                                        <div class="col-md-5 mb-2"><label>Gün Başlığı (EN)</label><input
                                                                type="text" name="day_title_en[<?php echo $day_idx; ?>]"
                                                                class="form-control"
                                                                value="<?php echo htmlspecialchars($it['title_en']); ?>">
                                                        </div>
                                                        <div class="col-md-6 mb-2"><label>İçerik (TR)</label><textarea
                                                                name="day_content_tr[<?php echo $day_idx; ?>]"
                                                                class="form-control"><?php echo htmlspecialchars($it['content_tr']); ?></textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-2"><label>İçerik (EN)</label><textarea
                                                                name="day_content_en[<?php echo $day_idx; ?>]"
                                                                class="form-control"><?php echo htmlspecialchars($it['content_en']); ?></textarea>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <label>Maddeler / Alt Öğeler</label>
                                                            <div class="items-wrap" id="items_wrap_<?php echo $day_idx; ?>">
                                                                <?php
                                                                $items = $pdo->query("SELECT * FROM tour_itinerary_items WHERE itinerary_id = " . $it['id'])->fetchAll();
                                                                foreach ($items as $itm): ?>
                                                                    <div class="row row-item mb-1 align-items-center">
                                                                        <div class="col-md-5"><input type="text"
                                                                                name="item_tr[<?php echo $day_idx; ?>][]"
                                                                                class="form-control form-control-sm"
                                                                                value="<?php echo htmlspecialchars($itm['text_tr']); ?>">
                                                                        </div>
                                                                        <div class="col-md-5"><input type="text"
                                                                                name="item_en[<?php echo $day_idx; ?>][]"
                                                                                class="form-control form-control-sm"
                                                                                value="<?php echo htmlspecialchars($itm['text_en']); ?>">
                                                                        </div>
                                                                        <div class="col-md-2"><button type="button"
                                                                                class="btn btn-danger btn-xs remove-row">X</button>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn btn-info btn-xs mt-1"
                                                                onclick="addItineraryItem('items_wrap_<?php echo $day_idx; ?>', <?php echo $day_idx; ?>)">+
                                                                Alt Madde Ekle</button>
                                                        </div>
                                                        <div class="col-12 text-end mt-3"><button type="button"
                                                                class="btn btn-danger btn-sm remove-card">Bu Günü
                                                                Sil</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $day_idx++; endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2" onclick="addItineraryDay()">+
                                        Yeni Gün Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                            <!-- 5. FAQS -->
                            <div class="tab-pane fade" id="faqs">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="faqs">
                                    <div id="faqs-container">
                                        <?php
                                        $fqs = $pdo->query("SELECT * FROM tour_faqs WHERE tour_id = $tour_id")->fetchAll();
                                        foreach ($fqs as $f): ?>
                                            <div class="card border border-warning mb-2 faq-card">
                                                <div class="card-body row">
                                                    <div class="col-md-6 mb-2"><label>Soru (TR)</label><input type="text"
                                                            name="f_q_tr[]" class="form-control"
                                                            value="<?php echo htmlspecialchars($f['question_tr']); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2"><label>Soru (EN)</label><input type="text"
                                                            name="f_q_en[]" class="form-control"
                                                            value="<?php echo htmlspecialchars($f['question_en']); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-2"><label>Cevap (TR)</label><textarea
                                                            name="f_a_tr[]"
                                                            class="form-control"><?php echo htmlspecialchars($f['answer_tr']); ?></textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-2"><label>Cevap (EN)</label><textarea
                                                            name="f_a_en[]"
                                                            class="form-control"><?php echo htmlspecialchars($f['answer_en']); ?></textarea>
                                                    </div>
                                                    <div class="col-12 text-end"><button type="button"
                                                            class="btn btn-danger btn-sm remove-card">Sil</button></div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2" onclick="addFaqRow()">+ Yeni SSS
                                        Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                            <!-- 6. PHOTO GALLERY -->
                            <div class="tab-pane fade" id="gallery_photos">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="update_type" value="gallery_photos">
                                    <div class="mb-3">
                                        <label class="form-label">Yeni Fotoğraflar Ekle (Birden fazla seçebilirsiniz)</label>
                                        <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Yükle</button>
                                </form>
                                <hr>
                                <div class="row mt-4">
                                    <?php
                                    $photos = $pdo->query("SELECT * FROM tour_gallery WHERE tour_id = $tour_id")->fetchAll();
                                    foreach ($photos as $p): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="../uploads/tours/<?php echo $p['image']; ?>" class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <div class="card-body text-center p-2">
                                                    <form method="POST" onsubmit="return confirm('Bu fotoğrafı silmek istediğinize emin misiniz?')">
                                                        <input type="hidden" name="update_type" value="delete_photo">
                                                        <input type="hidden" name="photo_id" value="<?php echo $p['id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-xs">Sil</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- 7. VIDEO GALLERY -->
                            <div class="tab-pane fade" id="gallery_videos">
                                <form method="POST">
                                    <input type="hidden" name="update_type" value="gallery_videos">
                                    <div id="videos-container">
                                        <?php
                                        $vids = $pdo->query("SELECT * FROM tour_videos WHERE tour_id = $tour_id")->fetchAll();
                                        foreach ($vids as $v): ?>
                                            <div class="row row-item mb-2 align-items-center">
                                                <div class="col-md-10">
                                                    <input type="text" name="v_url[]" class="form-control"
                                                        value="<?php echo htmlspecialchars($v['video_url']); ?>"
                                                        placeholder="YouTube Video URL (Örn: https://www.youtube.com/watch?v=...)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2"
                                        onclick="addVideoRow()">+ Yeni Video URL Ekle</button>
                                    <hr>
                                    <button type="submit" class="btn btn-primary">Kaydet</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic for dynamic row addition and removal
    document.addEventListener('click', function (e) {
        if(e.target && e.target.classList.contains('remove-row')) {
            e.target.closest('.row-item').remove();
        }
        if(e.target && e.target.classList.contains('remove-card')) {
            e.target.closest('.card').remove();
        }
    });

    function addRow(containerId, nameTr, nameEn) {
        let tf = `<div class="row row-item mb-2 align-items-center">
        <div class="col-md-5"><input type="text" name="${nameTr}" class="form-control" placeholder="TR"></div>
        <div class="col-md-5"><input type="text" name="${nameEn}" class="form-control" placeholder="EN"></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-row">X</button></div>
    </div>`;
        document.getElementById(containerId).insertAdjacentHTML('beforeend', tf);
    }

    function addIncludeRow(containerId) {
        let tf = `<div class="row row-item mb-2 align-items-center">
        <div class="col-md-4"><input type="text" name="i_tr[]" class="form-control" placeholder="TR"></div>
        <div class="col-md-4"><input type="text" name="i_en[]" class="form-control" placeholder="EN"></div>
        <div class="col-md-2">
            <select name="i_status[]" class="form-control">
                <option value="1">Dahil</option><option value="0">Hariç</option>
            </select>
        </div>
        <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-row">X</button></div>
    </div>`;
        document.getElementById(containerId).insertAdjacentHTML('beforeend', tf);
    }

    function addFaqRow() {
        let tf = `<div class="card border border-warning mb-2 faq-card">
        <div class="card-body row">
            <div class="col-md-6 mb-2"><label>Soru (TR)</label><input type="text" name="f_q_tr[]" class="form-control"></div>
            <div class="col-md-6 mb-2"><label>Soru (EN)</label><input type="text" name="f_q_en[]" class="form-control"></div>
            <div class="col-md-6 mb-2"><label>Cevap (TR)</label><textarea name="f_a_tr[]" class="form-control"></textarea></div>
            <div class="col-md-6 mb-2"><label>Cevap (EN)</label><textarea name="f_a_en[]" class="form-control"></textarea></div>
            <div class="col-12 text-end"><button type="button" class="btn btn-danger btn-sm remove-card">Sil</button></div>
        </div>
    </div>`;
        document.getElementById('faqs-container').insertAdjacentHTML('beforeend', tf);
    }

    let dayIndexCounter = 999;
    function addItineraryDay() {
        dayIndexCounter++;
        let idx = dayIndexCounter;
        let tf = `<div class="card border border-primary mb-3 itn-card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 mb-2"><label>Gün No</label><input type="number" name="day_num[${idx}]" class="form-control" value="1"></div>
                <div class="col-md-5 mb-2"><label>Gün Başlığı (TR)</label><input type="text" name="day_title_tr[${idx}]" class="form-control"></div>
                <div class="col-md-5 mb-2"><label>Gün Başlığı (EN)</label><input type="text" name="day_title_en[${idx}]" class="form-control"></div>
                <div class="col-md-6 mb-2"><label>İçerik (TR)</label><textarea name="day_content_tr[${idx}]" class="form-control"></textarea></div>
                <div class="col-md-6 mb-2"><label>İçerik (EN)</label><textarea name="day_content_en[${idx}]" class="form-control"></textarea></div>
                <div class="col-12 mt-2">
                    <label>Maddeler / Alt Öğeler</label>
                    <div class="items-wrap" id="items_wrap_${idx}"></div>
                    <button type="button" class="btn btn-info btn-xs mt-1" onclick="addItineraryItem('items_wrap_${idx}', ${idx})">+ Alt Madde Ekle</button>
                </div>
                <div class="col-12 text-end mt-3"><button type="button" class="btn btn-danger btn-sm remove-card">Bu Günü Sil</button></div>
            </div>
        </div>
    </div>`;
        document.getElementById('itinerary-container').insertAdjacentHTML('beforeend', tf);
    }

    function addItineraryItem(containerId, parentIdx) {
        let tf = `<div class="row row-item mb-1 align-items-center">
        <div class="col-md-5"><input type="text" name="item_tr[${parentIdx}][]" class="form-control form-control-sm" placeholder="Madde TR"></div>
        <div class="col-md-5"><input type="text" name="item_en[${parentIdx}][]" class="form-control form-control-sm" placeholder="Madde EN"></div>
        <div class="col-md-2"><button type="button" class="btn btn-danger btn-xs remove-row">X</button></div>
    </div>`;
        document.getElementById(containerId).insertAdjacentHTML('beforeend', tf);
    }

    function addVideoRow() {
        let tf = `<div class="row row-item mb-2 align-items-center">
            <div class="col-md-10">
                <input type="text" name="v_url[]" class="form-control" placeholder="YouTube Video URL">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
            </div>
        </div>`;
        document.getElementById('videos-container').insertAdjacentHTML('beforeend', tf);
    }
</script>

<?php include 'footer.php'; ?>