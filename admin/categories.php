<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Kategori silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tr = clean($_POST['name_tr']);
    $en = clean($_POST['name_en']);
    $data = [
        'name_tr' => $tr,
        'name_en' => $en,
        'slug_tr' => slugify($tr),
        'slug_en' => slugify($en),
        'status' => (int) $_POST['status']
    ];

    // Handle Image Upload
    if (!empty($_FILES['image']['name'])) {
        $name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/categories/' . $name);
        $data['image'] = $name;
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $sql = "UPDATE categories SET name_tr=:name_tr, name_en=:name_en, slug_tr=:slug_tr, slug_en=:slug_en, status=:status";
        if (isset($data['image']))
            $sql .= ", image=:image";
        $sql .= " WHERE id=:id";
        $pdo->prepare($sql)->execute($data + ['id' => $_POST['id']]);
        alert('Kategori güncellendi.', 'success');
    } else {
        $sql = "INSERT INTO categories (name_tr, name_en, slug_tr, slug_en, status";
        if (isset($data['image']))
            $sql .= ", image";
        $sql .= ") VALUES (:name_tr, :name_en, :slug_tr, :slug_en, :status";
        if (isset($data['image']))
            $sql .= ", :image";
        $sql .= ")";
        $pdo->prepare($sql)->execute($data);
        alert('Kategori eklendi.', 'success');
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Kategori Yönetimi</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCat">Yeni
                            Ekle</button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Görsel</th>
                                    <th>Kategori Adı (TR)</th>
                                    <th class="text-center">Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $c): ?>
                                    <tr>
                                        <td>
                                            <?php if ($c['image']): ?>
                                                <img src="../uploads/categories/<?php echo $c['image']; ?>" width="50"
                                                    height="50" style="object-fit: cover; border-radius: 5px;">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $c['name_tr']; ?></td>
                                        <td class="text-center">
                                            <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                <input class="form-check-input status-toggle m-0" type="checkbox"
                                                    role="switch" data-id="<?php echo $c['id']; ?>" data-table="categories"
                                                    <?php echo $c['status'] ? 'checked' : ''; ?>
                                                    style="cursor: pointer; width: 40px; height: 20px;">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-xs sharp me-1" data-bs-toggle="modal"
                                                data-bs-target="#editCat<?php echo $c['id']; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a href="?delete=<?php echo $c['id']; ?>" class="btn btn-danger btn-xs sharp"
                                                onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editCat<?php echo $c['id']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="categories.php" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Kategori Düzenle</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Ad (TR)</label>
                                                            <input type="text" name="name_tr" class="form-control"
                                                                value="<?php echo $c['name_tr']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Ad (EN)</label>
                                                            <input type="text" name="name_en" class="form-control"
                                                                value="<?php echo $c['name_en']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Görsel</label>
                                                            <input type="file" name="image" class="form-control">
                                                            <?php if ($c['image']): ?>
                                                                <img src="../uploads/categories/<?php echo $c['image']; ?>"
                                                                    width="100" class="mt-2">
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Durum</label>
                                                            <select name="status" class="form-control">
                                                                <option value="1" <?php echo $c['status'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                                                <option value="0" <?php echo $c['status'] == 0 ? 'selected' : ''; ?>>Pasif</option>
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

<div class="modal fade" id="addCat">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="categories.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Ad (TR)</label>
                        <input type="text" name="name_tr" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Ad (EN)</label>
                        <input type="text" name="name_en" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Görsel</label>
                        <input type="file" name="image" class="form-control">
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