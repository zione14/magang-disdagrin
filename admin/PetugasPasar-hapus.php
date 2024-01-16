<?php

include '../admin/akses.php';
$Page = 'MasterData';
$SubPage='MasterPasar';
$fitur_id = 19;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

$KodePasar = false;
$DataPasar = array();
if(isset($_GET['id'])){
    $KodePasar = base64_decode($_GET['id']);
    $sql = "SELECT p.*
    FROM mstpasar p 
    WHERE p.KodePasar = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $KodePasar);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //$num_of_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            if ($row != null) {
                $DataPasar = $row;
            }
        }
        $stmt->free_result();
        $stmt->close();
    }

    $aktif = $_GET['aktif'];

    $Username = base64_decode($_GET['uname']);
    $sql = "";
    if($aktif == 1){
        $sql = "UPDATE userlogin SET userlogin.IsAktif = '1'
    WHERE userlogin.UserName = ? AND userlogin.KodePasar = ?";
    }else{
        $sql = "UPDATE userlogin SET userlogin.IsAktif = '0'
    WHERE userlogin.UserName = ? AND userlogin.KodePasar = ?";
    }
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ss', $Username, $KodePasar);
    if ($stmt->execute()) {
        echo '<script>
                alert("data petugas berhasil diubah");
                window.location.href = "PetugasPasar.php?id='.base64_encode($KodePasar).'";
            </script>';
    }
    echo '<script>
            alert("data petugas gagal dihapus");
            window.location.href = "PetugasPasar.php?id='.base64_encode($KodePasar).'";
        </script>';
}