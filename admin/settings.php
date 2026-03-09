<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    file_put_contents('post_log.txt', print_r($_POST, true));
    $data = [
        'title' => clean($_POST['title']),
        'email' => clean($_POST['email']),
        'phone' => clean($_POST['phone']),
        'address' => clean($_POST['address']),
        'instagram' => clean($_POST['instagram']),
        'keywords' => clean($_POST['keywords']),
        'description_tr' => clean($_POST['description_tr']),
        'description_en' => clean($_POST['description_en']),
        'about_content_tr' => $_POST['about_content_tr'],
        'about_content_en' => $_POST['about_content_en'],
        'corporate_content_tr' => $_POST['corporate_content_tr'],
        'corporate_content_en' => $_POST['corporate_content_en'],
        'about_status' => (int) $_POST['about_status'],
        'home_mission_title_tr' => clean($_POST['home_mission_title_tr']),
        'home_mission_title_en' => clean($_POST['home_mission_title_en']),
        'home_mission_desc_tr' => $_POST['home_mission_desc_tr'],
        'home_mission_desc_en' => $_POST['home_mission_desc_en'],
        'smtp_host' => clean($_POST['smtp_host']),
        'smtp_user' => clean($_POST['smtp_user']),
        'smtp_pass' => clean($_POST['smtp_pass']),
        'smtp_port' => (int) $_POST['smtp_port'],
        'map_iframe' => $_POST['map_iframe'],
        'sustainability_content_tr' => $_POST['sustainability_content_tr'],
        'sustainability_content_en' => $_POST['sustainability_content_en']
    ];

    // Handle About Image Uploads
    if (!empty($_FILES['about_img']['name'])) {
        $name = time() . '_1_' . $_FILES['about_img']['name'];
        move_uploaded_file($_FILES['about_img']['tmp_name'], '../uploads/' . $name);
        $data['about_img'] = $name;
    }
    if (!empty($_FILES['about_img2']['name'])) {
        $name = time() . '_2_' . $_FILES['about_img2']['name'];
        move_uploaded_file($_FILES['about_img2']['tmp_name'], '../uploads/' . $name);
        $data['about_img2'] = $name;
    }

    // Handle Banner Uploads
    $banners = ['banner_about', 'banner_tours', 'banner_blog', 'banner_contact', 'banner_gallery', 'banner_faq', 'banner_sustainability', 'banner_mission_vision'];
    foreach ($banners as $b) {
        if (!empty($_FILES[$b]['name'])) {
            $name = time() . '_' . $b . '_' . $_FILES[$b]['name'];
            move_uploaded_file($_FILES[$b]['tmp_name'], '../uploads/' . $name);
            $data[$b] = $name;
        }
    }

    $sql = "UPDATE settings SET title=:title, email=:email, phone=:phone, address=:address, instagram=:instagram, 
            keywords=:keywords, description_tr=:description_tr, description_en=:description_en, 
            about_content_tr=:about_content_tr, about_content_en=:about_content_en,
            corporate_content_tr=:corporate_content_tr, corporate_content_en=:corporate_content_en,
            home_mission_title_tr=:home_mission_title_tr, home_mission_title_en=:home_mission_title_en,
            home_mission_desc_tr=:home_mission_desc_tr, home_mission_desc_en=:home_mission_desc_en,
            about_status=:about_status,
            smtp_host=:smtp_host, smtp_user=:smtp_user, smtp_pass=:smtp_pass, smtp_port=:smtp_port,
            map_iframe=:map_iframe,
            sustainability_content_tr=:sustainability_content_tr,
            sustainability_content_en=:sustainability_content_en";
    if (isset($data['about_img']))
        $sql .= ", about_img=:about_img";
    if (isset($data['about_img2']))
        $sql .= ", about_img2=:about_img2";

    foreach ($banners as $b) {
        if (isset($data[$b]))
            $sql .= ", $b=:$b";
    }

    $sql .= " WHERE id = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    alert('Ayarlar başarıyla güncellendi.', 'success');
}

$settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="settings.php" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Genel Ayarlar</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Site Başlığı</label>
                                    <input type="text" name="title" class="form-control"
                                        value="<?php echo $settings['title']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">E-posta</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo $settings['email']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?php echo $settings['phone']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Instagram</label>
                                    <input type="text" name="instagram" class="form-control"
                                        value="<?php echo $settings['instagram']; ?>">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Adres</label>
                                    <textarea name="address"
                                        class="form-control"><?php echo $settings['address']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">SEO Ayarları</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Keywords</label>
                                <input type="text" name="keywords" class="form-control"
                                    value="<?php echo $settings['keywords']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Açıklama (TR)</label>
                                <textarea name="description_tr"
                                    class="form-control"><?php echo $settings['description_tr']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Açıklama (EN)</label>
                                <textarea name="description_en"
                                    class="form-control"><?php echo $settings['description_en']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Hakkımızda Ayarları</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Hakkımızda Görsel 1</label>
                                <input type="file" name="about_img" class="form-control">
                                <?php if ($settings['about_img']): ?>
                                    <img src="../uploads/<?php echo $settings['about_img']; ?>" width="100" class="mt-2">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hakkımızda Görsel 2</label>
                                <input type="file" name="about_img2" class="form-control">
                                <?php if ($settings['about_img2']): ?>
                                    <img src="../uploads/<?php echo $settings['about_img2']; ?>" width="100" class="mt-2">
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hakkımızda İçerik (TR)</label>
                                <textarea name="about_content_tr" class="form-control"
                                    rows="5"><?php echo $settings['about_content_tr']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hakkımızda İçerik (EN)</label>
                                <textarea name="about_content_en" class="form-control"
                                    rows="5"><?php echo $settings['about_content_en']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hakkımızda Durumu</label>
                                <select name="about_status" class="form-control">
                                    <option value="1" <?php echo $settings['about_status'] == 1 ? 'selected' : ''; ?>>
                                        Aktif</option>
                                    <option value="0" <?php echo $settings['about_status'] == 0 ? 'selected' : ''; ?>>
                                        Pasif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Sayfa Bannerları (Üst Görseller)</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hakkımızda Banner</label>
                                    <input type="file" name="banner_about" class="form-control">
                                    <?php if ($settings['banner_about']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_about']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Turlar Banner</label>
                                    <input type="file" name="banner_tours" class="form-control">
                                    <?php if ($settings['banner_tours']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_tours']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Blog Banner</label>
                                    <input type="file" name="banner_blog" class="form-control">
                                    <?php if ($settings['banner_blog']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_blog']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">İletişim Banner</label>
                                    <input type="file" name="banner_contact" class="form-control">
                                    <?php if ($settings['banner_contact']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_contact']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Galeri / Diğer Banner</label>
                                    <input type="file" name="banner_gallery" class="form-control">
                                    <?php if ($settings['banner_gallery']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_gallery']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">S.S.S. Banner</label>
                                    <input type="file" name="banner_faq" class="form-control">
                                    <?php if ($settings['banner_faq']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_faq']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sürdürülebilirlik Banner</label>
                                    <input type="file" name="banner_sustainability" class="form-control">
                                    <?php if ($settings['banner_sustainability']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_sustainability']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Misyon & Vizyon Banner</label>
                                    <input type="file" name="banner_mission_vision" class="form-control">
                                    <?php if ($settings['banner_mission_vision']): ?>
                                        <img src="../uploads/<?php echo $settings['banner_mission_vision']; ?>" width="200"
                                            class="mt-2 d-block">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Harita Ayarları (İletişim Sayfası)</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Google Harita Iframe Kodu</label>
                                <textarea name="map_iframe" class="form-control"
                                    rows="5"><?php echo $settings['map_iframe']; ?></textarea>
                                <small class="text-muted">Google Haritalar'dan aldığınız "Harita yerleştir" (iframe)
                                    kodunu buraya yapıştırın.</small>
                            </div>
                            <?php if (!empty($settings['map_iframe'])): ?>
                                <div class="mt-3">
                                    <label class="form-label d-block">Mevcut Harita Önizleme:</label>
                                    <div style="width: 100%; height: 300px;">
                                        <?php echo $settings['map_iframe']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Prive Neden Biz? (Ana Sayfa)</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Başlık (TR)</label>
                                <input type="text" name="home_mission_title_tr" class="form-control"
                                    value="<?php echo $settings['home_mission_title_tr']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Başlık (EN)</label>
                                <input type="text" name="home_mission_title_en" class="form-control"
                                    value="<?php echo $settings['home_mission_title_en']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Açıklama (TR)</label>
                                <textarea name="home_mission_desc_tr" class="form-control"
                                    rows="3"><?php echo $settings['home_mission_desc_tr']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Açıklama (EN)</label>
                                <textarea name="home_mission_desc_en" class="form-control"
                                    rows="3"><?php echo $settings['home_mission_desc_en']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Kurumsal Ayarları (Misyon & Vizyon)</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Misyon & Vizyon İçerik (TR)</label>
                                <textarea name="corporate_content_tr" class="form-control"
                                    rows="10"><?php echo $settings['corporate_content_tr']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Misyon & Vizyon İçerik (EN)</label>
                                <textarea name="corporate_content_en" class="form-control"
                                    rows="10"><?php echo $settings['corporate_content_en']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Sürdürülebilirlik Ayarları</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Sürdürülebilirlik İçerik (TR)</label>
                                <textarea name="sustainability_content_tr" class="form-control"
                                    rows="10"><?php echo $settings['sustainability_content_tr']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sürdürülebilirlik İçerik (EN)</label>
                                <textarea name="sustainability_content_en" class="form-control"
                                    rows="10"><?php echo $settings['sustainability_content_en']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">SMTP Ayarları</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label>Host</label><input type="text" name="smtp_host"
                                        class="form-control" value="<?php echo $settings['smtp_host']; ?>"></div>
                                <div class="col-md-6 mb-3"><label>User</label><input type="text" name="smtp_user"
                                        class="form-control" value="<?php echo $settings['smtp_user']; ?>"></div>
                                <div class="col-md-6 mb-3"><label>Password</label><input type="password"
                                        name="smtp_pass" class="form-control"
                                        value="<?php echo $settings['smtp_pass']; ?>"></div>
                                <div class="col-md-6 mb-3"><label>Port</label><input type="number" name="smtp_port"
                                        class="form-control" value="<?php echo $settings['smtp_port']; ?>"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mb-5">
                        <button type="submit" class="btn btn-primary btn-lg">Ayarları Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>