<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM faqs WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('SSS silindi.', 'success');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'question_tr' => $_POST['question_tr'],
        'question_en' => $_POST['question_en'],
        'answer_tr' => $_POST['answer_tr'],
        'answer_en' => $_POST['answer_en'],
        'sort_order' => (int) $_POST['sort_order'],
        'status' => (int) $_POST['status']
    ];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $pdo->prepare("UPDATE faqs SET question_tr=:question_tr, question_en=:question_en, answer_tr=:answer_tr, answer_en=:answer_en, sort_order=:sort_order, status=:status WHERE id=:id")
            ->execute($data + ['id' => $_POST['id']]);
        alert('SSS güncellendi.', 'success');
    } else {
        $pdo->prepare("INSERT INTO faqs (question_tr, question_en, answer_tr, answer_en, sort_order, status) VALUES (:question_tr, :question_en, :answer_tr, :answer_en, :sort_order, :status)")
            ->execute($data);
        alert('SSS eklendi.', 'success');
    }
}

$faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC, id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sıkça Sorulan Sorular</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFaq">Yeni
                            Ekle</button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Soru (TR)</th>
                                    <th>Sıra</th>
                                    <th class="text-center">Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($faqs as $f): ?>
                                    <tr>
                                        <td>
                                            <?php echo $f['question_tr']; ?>
                                        </td>
                                        <td>
                                            <?php echo $f['sort_order']; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch p-0 d-flex justify-content-center">
                                                <input class="form-check-input status-toggle m-0" type="checkbox" role="switch"
                                                    data-id="<?php echo $f['id']; ?>" data-table="faqs"
                                                    <?php echo $f['status'] ? 'checked' : ''; ?>
                                                    style="cursor: pointer; width: 40px; height: 20px;">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-xs sharp me-1" data-bs-toggle="modal"
                                                data-bs-target="#editFaq<?php echo $f['id']; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a href="?delete=<?php echo $f['id']; ?>" class="btn btn-danger btn-xs sharp"
                                                onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editFaq<?php echo $f['id']; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="faqs.php" method="POST">
                                                    <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">SSS Düzenle</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Soru (TR)</label>
                                                            <input type="text" name="question_tr" class="form-control"
                                                                value="<?php echo $f['question_tr']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Soru (EN)</label>
                                                            <input type="text" name="question_en" class="form-control"
                                                                value="<?php echo $f['question_en']; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Cevap (TR)</label>
                                                            <textarea name="answer_tr" class="form-control" rows="4"
                                                                required><?php echo $f['answer_tr']; ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Cevap (EN)</label>
                                                            <textarea name="answer_en" class="form-control" rows="4"
                                                                required><?php echo $f['answer_en']; ?></textarea>
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

<div class="modal fade" id="addFaq">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="faqs.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Yeni SSS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Soru (TR)</label>
                        <input type="text" name="question_tr" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Soru (EN)</label>
                        <input type="text" name="question_en" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Cevap (TR)</label>
                        <textarea name="answer_tr" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Cevap (EN)</label>
                        <textarea name="answer_en" class="form-control" rows="4" required></textarea>
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