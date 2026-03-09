<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

$blog_id = (int) ($_GET['id'] ?? 0);
if (!$blog_id)
    redirect('blogs.php');

$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->execute([$blog_id]);
$blog = $stmt->fetch();
if (!$blog)
    redirect('blogs.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = clean($_POST['update_type']);

    if ($type == 'gallery_photos') {
        if (!empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $k => $tmp_name) {
                if (!empty($tmp_name)) {
                    $name = time() . '_bgal_' . $_FILES['gallery_images']['name'][$k];
                    if (move_uploaded_file($tmp_name, '../uploads/blog/' . $name)) {
                        $pdo->prepare("INSERT INTO blog_gallery (blog_id, image) VALUES (?,?)")->execute([$blog_id, $name]);
                    }
                }
            }
            alert('Fotoğraflar galeriye eklendi.', 'success');
        }
    } else if ($type == 'delete_photo') {
        $photo_id = (int) $_POST['photo_id'];
        $photo = $pdo->query("SELECT image FROM blog_gallery WHERE id = $photo_id")->fetch();
        if ($photo) {
            @unlink('../uploads/blog/' . $photo['image']);
            $pdo->prepare("DELETE FROM blog_gallery WHERE id = ?")->execute([$photo_id]);
            alert('Fotoğraf silindi.', 'success');
        }
    }
    redirect("blogs_manage_details.php?id=$blog_id");
}

include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <a href="blogs.php" class="btn btn-dark"><i class="fa fa-arrow-left"></i> Geri Dön</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h4 class="card-title text-white">Gelişmiş Detay Yönetimi (Galeri):
                            <?php echo $blog['title_tr']; ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="update_type" value="gallery_photos">
                            <div class="mb-3">
                                <label class="form-label">Yeni Fotoğraflar Ekle (Birden fazla seçebilirsiniz)</label>
                                <input type="file" name="gallery_images[]" class="form-control" multiple
                                    accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Yükle</button>
                        </form>
                        <hr>
                        <div class="row mt-4">
                            <?php
                            $photos = $pdo->query("SELECT * FROM blog_gallery WHERE blog_id = $blog_id")->fetchAll();
                            foreach ($photos as $p): ?>
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="../uploads/blog/<?php echo $p['image']; ?>" class="card-img-top"
                                            style="height: 150px; object-fit: cover;">
                                        <div class="card-body text-center p-2">
                                            <form method="POST"
                                                onsubmit="return confirm('Bu fotoğrafı silmek istediğinize emin misiniz?')">
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>