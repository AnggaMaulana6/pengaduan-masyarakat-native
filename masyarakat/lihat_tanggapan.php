<?php
session_start();
include "../lib/db.php";

if ($_SESSION['level'] != 'masyarakat') {
    header('location:../logout.php');
}
if (isset($_GET['id_pengaduan'])) {
    $id_pengaduan = $_GET['id_pengaduan'];
    $id_user = $_SESSION['id'];

    $queryAd = "SELECT pengaduan.*,petugas.*, tanggapan.tanggapan, tanggapan.tgl_tanggapan FROM pengaduan 
            JOIN tanggapan ON pengaduan.id_pengaduan = tanggapan.id_pengaduan 
                JOIN petugas ON petugas.id_petugas = tanggapan.id_petugas WHERE tanggapan.id_pengaduan = '$id_pengaduan'";
    $execQueryAd = mysqli_query($con, $queryAd);
    $tampil = mysqli_fetch_assoc($execQueryAd);
    if($tampil['status'] == '0'){
        $status = 'Valid';
    }else{
        $status = $tampil['status'];
    }
}

$query = "SELECT pengaduan.*,petugas.*, tanggapan.tanggapan, tanggapan.tgl_tanggapan FROM pengaduan 
            JOIN tanggapan ON pengaduan.id_pengaduan = tanggapan.id_pengaduan 
                JOIN petugas ON petugas.id_petugas = tanggapan.id_petugas WHERE tanggapan.id_pengaduan = '$id_pengaduan'";
$execQuery = mysqli_query($con, $query);
$getData = mysqli_fetch_all($execQuery, MYSQLI_ASSOC);

include "../layout/header.php";
?>

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data</span> Tanggapan</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Foto Aduan</h5>
                </div>
                <div class="card-body">
                    <!-- Bootstrap Dark Table -->
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <td>
                                        <img src=<?= $tampil['foto'] ?> class="img img-thumbnail">
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/ Bootstrap Dark Table -->
                </div>
            </div>
        </div>
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Pengaduan</h5>
                </div>
                <div class="card-body">
                    <form class="">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label class="form-label" for="basic-icon-default-fullname">Nama Pengadu</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="text" class="form-control" id="basic-icon-default-fullname" value=<?= $_SESSION['nama']  ?> readonly />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="basic-icon-default-fullname">NIK</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="number" class="form-control" id="basic-icon-default-fullname" value=<?php echo $tampil['nik'] ?> readonly />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-company">Isi Tanggapan</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-company2" class="input-group-text"><i class="bx bx-buildings"></i></span>
                                <textarea name="isi_laporan" class="form-control" readonly><?= $tampil['isi_laporan'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label class="form-label" for="basic-icon-default-fullname">Status</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="text" class="form-control" name="nama_petugas" value=<?php echo $status?> readonly />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="basic-icon-default-fullname">Tanggal Laporan</label>
                                <div class="input-group input-group-merge">
                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i class="bx bx-user"></i></span>
                                    <input type="date" class="form-control" value=<?php echo $tampil['tgl_pengaduan'] ?> readonly />
                                </div>
                            </div>
                        </div>
                        <br>
                        <a href="./pengaduan.php" class="btn btn-primary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- / Content -->

<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Data Admin</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Tanggapan</th>
                    <th>Tanggapan</th>
                    <th>Nama Penanggap</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getData as $data) {
                    $no += 1;
                    if($data['status'] == '0'){
                        $status = 'Valid';
                    }else{
                        $status = $data['status'];
                    }
                    echo "
                        <tr>
                            <td>$no</td>
                            <td>$data[tgl_tanggapan]</td>
                            <td>$data[tanggapan]</td>
                            <td>$data[nama_petugas]</td>
                            <td>$status</td>
                        </tr>
                        ";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!--/ Bootstrap Dark Table -->

<?php include "../layout/footer.php"; ?>