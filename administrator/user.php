<?php
include "../lib/db.php";
session_start();

$query = "SELECT * FROM petugas";

$exquery = mysqli_query($con, $query);
$getData = mysqli_fetch_all($exquery, MYSQLI_ASSOC);

if (isset($_GET['id_petugas'])) {
    $id_petugas = $_GET['id_petugas'];
    $sql = $con->query("DELETE FROM petugas WHERE id_petugas ='$id_petugas';");

    if ($sql) {
?>
        <script type="text/javascript">
            alert("Data Berhasil dihapus");
            window.location.href = "./user.php";
        </script>
<?php
    } else {
        '<script>alert("Data gagal dihapus")</script>';
    }
}



include "../layout/header.php"
?>


<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Data Admin</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Petugas</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Telepon</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getData as $data) {
                    $no += 1;
                    ?>
                    <tr>
                        <td><?=$no ?></td>
                        <td><?=$data['nama_petugas'] ?></td>
                        <td><?=$data['username'] ?></td>
                        <td><?=$data['password'] ?></td>
                        <td><?=$data['telp'] ?></td>
                        <td><?=$data['level'] ?></td>             
                        <td>
                            <!-- <a href='?nik=<?=$data['nik']?>' class='btn btn-danger'>Hapus</a> -->
                            <a class='btn btn-danger' href="?id_petugas=<?=$data['id_petugas']?>" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="col-lg-4 col-md-4">
            <div class="mt-2">
                <!-- Button trigger modal -->
                <a href="./registrasi.php" class="btn btn-primary mb-3 mt-2">
                    Tambah Data Petugas
                </a>
            </div>
        </div>
    </div>
</div>
<!--/ Bootstrap Dark Table -->


<?php include "../layout/footer.php"; ?>