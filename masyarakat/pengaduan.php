<?php
session_start();
include "../lib/db.php";

$id_user = $_SESSION['id'];
$showAduan = "SELECT * FROM pengaduan WHERE nik ='$id_user';";
$exAduan = mysqli_query($con, $showAduan);
$getAllData = mysqli_fetch_all($exAduan, MYSQLI_ASSOC);

if (isset($_POST['aduan'])) {
    $laporan = $_POST['laporan'];

    $locationTemp = $_FILES['foto']['tmp_name'];
    $destinationFile = '../assets/img/pengaduan/';

    $serverName = 'http://localhost/pengaduan_v2/assets/img/pengaduan/';

    $fileName = str_replace(' ', '', $_FILES['foto']['name']);
    $locationUpload = $destinationFile . $fileName;
    move_uploaded_file($locationTemp, $locationUpload);

    $query =
        "INSERT INTO pengaduan (tgl_pengaduan, nik, isi_laporan, foto, status) 
            VALUES (now(), '$id_user', '$laporan', '$serverName$fileName', NULL)";
    $exQuery = mysqli_query($con, $query);
    if ($exAduan) {
        header('location:./pengaduan.php');
    } else {
        echo '<script>alert("Data Aduan ada yang salah")</script>';
    }
    var_dump($exQuery);
}

if (isset($_GET['id'])) {
    $id_pengaduan = $_GET['id'];
    $sql = $con->query("DELETE FROM pengaduan WHERE id_pengaduan ='$id_pengaduan';");

    if ($sql) {
?>
        <script type="text/javascript">
            alert("Data Berhasil dihapus");
            window.location.href = "./pengaduan.php";
        </script>
<?php
    } else {
        '<script>alert("Data gagal dihapus")</script>';
    }
}



include "../layout/header.php";
?>

<div class="col-lg-4 col-md-4">
    <div class="mt-3">
        <!-- Button trigger modal -->
        <div class="card-header py-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                Tambah Data Aduan
            </button>
        </div>

        <!-- Modal -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Aduan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="" class="form-label">Nama</label>
                                    <input type="text" id="" class="form-control" value=<?= $_SESSION['nama'] ?> readonly />
                                </div>
                                <div class="col mb-0">
                                    <label for="" class="form-label">NIK</label>
                                    <input type="number" id="" class="form-control" value=<?= $_SESSION['id'] ?> readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameBasic" class="form-label">Foto Penunjang</label>
                                    <input type="file" id="nameBasic" class="form-control" name="foto" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameBasic" class="form-label">Isi Aduan</label>
                                    <textarea name="laporan" id="" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Kembali
                            </button>
                            <button type="submit" name="aduan" value="Aduan" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Data Aduan</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Aduan</th>
                    <th>Foto</th>
                    <th>Isi Aduan</th>
                    <th>Status</th>
                    <th>Tanggapan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getAllData as $data) {
                    $no += 1;
                    if ($data['status'] == NULL) {
                        $status = 'Belum Valid';
                    } elseif ($data['status'] == '0') {
                        $status = 'Valid';
                    } else {
                        $status = $data['status'];
                    }
                     ?>
                    <tr>
                        <td><?=$no ?></td>
                        <td><?=$data['tgl_pengaduan'] ?></td>
                        <td>
                            <?="<img src=$data[foto] class='img img-thumbnail' width=250px>" ?>
                        </td>
                        <td><?=$data['isi_laporan'] ?></td>
                        <td><?=$status ?></td>
                        <td>
                            <?php
                            if($data['status'] == 'proses' or $data['status'] == '0'){?>
                                <a href="./lihat_tanggapan.php?id_pengaduan=<?=$data['id_pengaduan'] ?>" class='btn btn-warning btn-sm'>Lihat Tanggapan</a>
                            <?php }elseif($data['status'] =='ditolak') { ?>
                                <a href="./lihat_tanggapan?id_pengaduan=<?=$data['id_pengaduan'] ?>" class='btn btn-danger btn-sm'>Tanggapan ditolak</a>
                            <?php }elseif($data['status'] =='selesai') { ?>
                                <div class="btn btn-info btn sm">Aduan Telah Selesai</div>
                            <?php }else{ ?>
                                <div class="btn btn-secondary btn sm">Belum ditanggapi</div>
                            <?php } ?>
                        </td>
                        <td>
                            <a class='btn btn-danger' href="?id=<?=$data['id_pengaduan']?>" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!--/ Bootstrap Dark Table -->

<?php include "../layout/footer.php"; ?>