<?php
session_start();
include "../lib/db.php";

// Menampilkan data verifikasi dan validasi
$showData = "SELECT p.id_pengaduan, m.nama, p.tgl_pengaduan, p.foto, p.isi_laporan, p.status
            FROM pengaduan p JOIN masyarakat m WHERE p.nik = m.nik AND p.status = 'proses'";
$exShow = mysqli_query($con, $showData);
$getData = mysqli_fetch_all($exShow, MYSQLI_ASSOC);

//Update data
if (isset($_GET['id'])) {
    session_start();
    $id = $_GET['id'];
    $queryValid = mysqli_query($con, "UPDATE pengaduan SET status = 'selesai' WHERE id_pengaduan = '$id';");

    if ($queryValid) {
        header('location:./proses.php');
    } else {
        echo '<script>alert("Data Gagal Di Validasi")</script>';
    }
}



include "../layout/header.php";
?>

<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Pengaduan dalam proses</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama Pengadu</th>
                    <th>Tanggal Pengaduan</th>
                    <th>Foto Penunjang</th>
                    <th>Isi Aduan</th>
                    <th>Status</th>
                    <th>Tanggapi</th>
                    <th>Verifikasi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getData as $data) {
                    $no += 1;
                    if ($data['status'] == 'proses') {
                        $status = 'proses';
                    } elseif ($data['sttus'] == 'selesai') {
                        $status = 'selesai';
                    } else {
                        $status = $data['status'];
                    }
                    echo "
                        <tr>
                            <td>$no</td>
                            <td>$data[nama]</td>
                            <td>$data[tgl_pengaduan]</td>
                            <td>
                                <img src=$data[foto] width=150px>
                            </td>
                            <td>$data[isi_laporan]</td>
                            <td>$status</td>
                            <td>
                                <a href='./tanggapan_proses.php?id=$data[id_pengaduan]' class='btn btn-danger'>
                                    Tanggapi
                                </a>
                            </td>
                            <td>
                                <a href='?id=$data[id_pengaduan]' class='btn btn-info'> Validasi</a>
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