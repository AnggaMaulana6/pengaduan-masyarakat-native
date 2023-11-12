<?php
session_start();
include "../lib/db.php";

// Menampilkan data verifikasi dan validasi
$showData = "SELECT p.id_pengaduan, m.nama, p.tgl_pengaduan, p.foto, p.isi_laporan, p.status
            FROM pengaduan p JOIN masyarakat m WHERE p.nik = m.nik AND p.status is NULL";
$exShow = mysqli_query($con, $showData);
$getData = mysqli_fetch_all($exShow, MYSQLI_ASSOC);

//Update data
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $queryValid = mysqli_query($con, "UPDATE pengaduan SET status = '0' WHERE id_pengaduan = '$id';");

    if($queryValid){
        header('location:./nonvalid.php');
    }else{
        echo '<script>alert("Data Gagal Di Validasi")</script>';
    }
}



include "../layout/header.php";
?>

<!-- Bootstrap Dark Table -->
<div class="card">
    <h5 class="card-header">Pengaduan Non Valid</h5>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
               <?php 
                    $no = 0;
                    foreach($getData as $data){
                        $no +=1;
                        if($data['status'] == NULL){
                            $status = 'Belum Valid';
                        }elseif($data['sttus'] == '0'){
                            $status = 'Valid';
                        }else{
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