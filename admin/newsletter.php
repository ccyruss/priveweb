<?php
require_once '../inc/db.php';
require_once '../inc/functions.php';
session_start();

if (!isset($_SESSION['admin_auth']))
    redirect('login.php');

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM newsletter WHERE id = ?")->execute([(int) $_GET['delete']]);
    alert('Abone silindi.', 'success');
}

$subscribers = $pdo->query("SELECT * FROM newsletter ORDER BY id DESC")->fetchAll();
include 'header.php';
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-end mb-4">
                <a href="javascript:void(0)" onclick="exportToCSV()" class="btn btn-success btn-sm"><i
                        class="fa fa-file-excel"></i> Excel'e Aktar</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bülten Aboneleri</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="newsletterTable">
                                <thead>
                                    <tr>
                                        <th>E-posta</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subscribers as $sub): ?>
                                        <tr>
                                            <td>
                                                <?php echo $sub['email']; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d.m.Y H:i', strtotime($sub['created_at'])); ?>
                                            </td>
                                            <td>
                                                <a href="?delete=<?php echo $sub['id']; ?>"
                                                    class="btn btn-danger btn-xs sharp"
                                                    onclick="return confirm('Silinsin mi?')"><i class="fa fa-trash"></i></a>
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

<script>
    function exportToCSV() {
        let csv = [];
        let rows = document.querySelectorAll("#newsletterTable tr");

        for(let i = 0;i < rows.length;i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");

            for(let j = 0;j < cols.length - 1;j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        let csv_string = csv.join("\n");
        let filename = "bülten_aboneleri_" + new Date().toLocaleDateString() + ".csv";
        let link = document.createElement("a");
        link.style.display = 'none';
        link.setAttribute('target', '_blank');
        link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<?php include 'footer.php'; ?>