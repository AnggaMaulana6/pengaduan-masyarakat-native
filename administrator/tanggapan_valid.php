<?php
session_start();
include "../lib/db.php";

if ($_SESSION['level'] != 'admin' and ($_SESSION['level'] != 'petugas')) {
    header('location:../logout.php');
}

if (empty($_GET['id'])) {
    header('location:../index.php');
}

$id_pengaduan = $_GET['id'];

if (isset($_POST['tanggapi'])) {
    $id_pengaduan = $_GET['id'];
    $tanggapan = $_POST['tanggapan'];
    $id_petugas = $_SESSION['id_petugas'];

    $queryTanggapan = mysqli_query($con, "INSERT INTO tanggapan (id_pengaduan, tgl_tanggapan, tanggapan, id_petugas)
                        VALUES  ('$id_pengaduan', now(), '$tanggapan', '$id_petugas')");
    if ($queryTanggapan) {
        header("location:./valid.php");
    } else {
        '<script>alert("Tanggapan ada yang salah!")</script>';
    }
}

// Menamilkan data aduan
$queryAduan = "SELECT * FROM pengaduan WHERE id_pengaduan = '$id_pengaduan';";

$exAduan = mysqli_query($con, $queryAduan);
$getAduan = mysqli_fetch_all($exAduan, MYSQLI_ASSOC);

// foreach($getAduan as $data) {
//     if ($data['status'] != '0' and ($data['status'] != 'proses'))
//         header('Location:./nonvalid.php');
// }

// Untuk menampilkan data tanggapan dari aduan
$queryTanggapan =  "SELECT t.id_tanggapan, t.id_pengaduan, t.tgl_tanggapan,
                    t.tanggapan, p.nama_petugas FROM tanggapan t
                    JOIN petugas p WHERE t.id_petugas = p.id_petugas
                    AND id_pengaduan = $id_pengaduan";
$ShowTanggapan = mysqli_query($con, $queryTanggapan);
$getDataTanggapan = mysqli_fetch_all($ShowTanggapan, MYSQLI_ASSOC);



include "../layout/header.php";




?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tanggapan</span> Admin & Petugas</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Data Aduan</h5>
                </div>
                <div class="card-body">
                    <!-- Bootstrap Dark Table -->
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Tanggal Aduan</th>
                                        <th>Isi Aduan</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                    foreach ($getAduan as $data) {
                                        echo "
                                        <tr>
                                            <td>
                                                <img src=$data[foto] width=180px>
                                            </td>
                                            <td>$data[tgl_pengaduan]</td>
                                            <td>$data[isi_laporan]</td>
                                        </tr>
                                       ";
                                    }

                                    ?>
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
                    <h5 class="mb-0">Tanggapan Saya</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-message">Isi Tanggapan</label>
                            <div class="input-group input-group-merge">
                                <span id="basic-icon-default-message2" class="input-group-text"><i class="bx bx-comment"></i></span>
                                <textarea name="tanggapan" id="basic-icon-default-message" class="form-control" placeholder="Tuliskan tanggapan anda disini...?" aria-label="Tuliskan tanggapan anda disini..." aria-describedby="basic-icon-default-message2" required></textarea>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary" name="tanggapi" value="Kirim"></input>
                        <a href="./proses.php" class="btn btn-warning">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getDataTanggapan as $data) {
                    $no += 1;
                    echo "
                        <tr>
                            <td>$no</td>
                            <td>$data[tgl_tanggapan]</td>
                            <td>$data[tanggapan]</td>
                            <td>$data[nama_petugas]</td>
                            <td>
                            <button class='btn btn-danger'>hapus</button>
                            </td>
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