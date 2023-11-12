<?php
session_start();
include "../lib/db.php";

// Menampilkan data verifikasi dan validasi
$showData = "SELECT p.id_pengaduan, m.nama, p.tgl_pengaduan, p.foto, p.isi_laporan, p.status
            FROM pengaduan p JOIN masyarakat m WHERE p.nik = m.nik AND p.status = 'selesai'";
$exShow = mysqli_query($con, $showData);
$getData = mysqli_fetch_all($exShow, MYSQLI_ASSOC);




include "../layout/header.php";
?>

<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Pengaduan Selesai</h5>
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
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                <?php
                $no = 0;
                foreach ($getData as $data) {
                    $no += 1;
                    if ($data['status'] == 'proses') {
                        $status = 'proses';
                    } elseif ($data['status'] == 'selesai') {
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