<?php
include 'akses.php';
@$fitur_id = 58;
include '../library/lock-menu.php';
// include '../library/cek.php';
$Page = 'MappingPasar';
$SubPage = 'MapCitraNiaga';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$KodePasar = 'PSR-2019-0000004';
?>

<?php
$sql_lapak = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar, c.selisih, c.TglAktif, c.IDPerson, d.JumlahTransaksi
FROM lapakpasar l 
LEFT JOIN (
    SELECT p.KodePasar, p.IDLapak, datediff(subdate(current_date, 1), p.TglAktif) as selisih, p.TglAktif, p.IDPerson
    FROM lapakperson p 
    WHERE p.IsAktif=b'1'
    GROUP BY p.KodePasar, p.IDLapak
) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
LEFT JOIN (
    SELECT COUNT(t.NoTransRet) as JumlahTransaksi, t.KodePasar, t.IDPerson, t.IDLapak
    FROM trretribusipasar t
    
    GROUP BY t.KodePasar, t.IDLapak, t.IDLapak
) d ON d.KodePasar = l.KodePasar AND d.IDLapak = l.IDLapak AND d.IDLapak = c.IDLapak
WHERE l.KodePasar='$KodePasar' ORDER BY IDLapak ASC";
$oke = $koneksi->prepare($sql_lapak);
$oke->execute();
$res_lapak = $oke->get_result();
$data_lapak = array();
while ($row_lapak = $res_lapak->fetch_assoc()) {
   array_push($data_lapak, $row_lapak);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <meta name="description" content="">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="../komponen/css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <?php include 'style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
    <!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
    <style type="text/css">
        .spinner {
            width: 100%; height: 100%;
            text-align: center;
        }

        .modal-content-yo {
          margin: auto;
          display: block;
          width: 20%;
          margin-top: 150px; 
          /*max-width: 650px;*/
        }

    </style>

</head>
<body>
    <div class="page">
        <!-- Main Navbar-->
        <?php include 'header.php';?>
        <div class="page-content d-flex align-items-stretch"> 
            <!-- Side Navbar -->
            <?php include 'menu.php';?>
            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Mapping Pasar Citra Niaga Tengah</h2>
                    </div>
                </header>
                <!-- Dashboard Counts Section-->
                <section class="tables"> 
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active show" id="home-pills">
                                        <div class="card-header d-flex align-items-center">
                                            <h3 class="h4">Denah Pasar Citra Niaga Tengah</h3>
                                        </div>
                                        
                                        <div class="card-body" > 
                                            <div class="spinner" style="display:none">
                                              <img id="img-spinner" src="../images/Assets/spin.gif" height="10%" width="10%" >
                                            </div>
                                            <div class="table-responsive">  
                                                <div id="map_demo" >
                                                    <h3><u>Lantai 1</u></h3>
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img style="width:100%;border:0;" id="usa_image" 
                                                        src="../images/Assets/MapCitraNiagaDalam3.png" usemap="#usa" >
                                                    </div>
                                                </div>      

                                                <map id="usa_image_map" name="usa">
                                                    <!-- ATAS KIRI-->
                                                    <area href="#" id="G65" data-nama="G65" data-state="LPK-0000000186" full="Lapak BLOK G NOMOR 65" shape="rect" coords="140,73,260,145"  onclick="lapakPasar('LPK-0000000186', '<?=$KodePasar?>', 65, 'E')">

                                                   <?php 
                                                    $kode = 187;
                                                    $a = 140; $b = 145; $c = 200; $d = 200;
                                                    for ($i=66; $i <= 67; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=60;
                                                        $c +=60;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="G68" data-nama="G68" data-state="LPK-0000000189" full="Lapak BLOK G NOMOR 68" shape="rect" coords="140,200,240,250"  onclick="lapakPasar('LPK-0000000189', '<?=$KodePasar?>', 68, 'G')">
                                                    <area href="#" id="G1" data-nama="G1" data-state="LPK-0000000122" full="Lapak BLOK G NOMOR 1" shape="rect" coords="140,250,240,300"  onclick="lapakPasar('LPK-0000000122', '<?=$KodePasar?>', 1, 'G')">

                                                  

                                                     <?php 
                                                    $kode = 185;
                                                    $a = 260; $b = 73; $c = 320; $d = 145;
                                                    for ($i=64; $i >= 61; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=60;
                                                        $c +=60;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <!--ATAS TENGAH -->
                                                    <?php 
                                                    $kode = 181;
                                                    $a = 600; $b = 73; $c = 654; $d = 170;
                                                    for ($i=60; $i >= 55; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=54;
                                                        $c +=54;
                                                        $kode = $kode - 1;
                                                    } ?>


                                                    <!--ATAS KANAN -->
                                                    <?php 
                                                    $kode = 175;
                                                    $a = 1145; $b = 73; $c = 1208; $d = 170;
                                                    for ($i=54; $i >= 53; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=63;
                                                        $c +=63;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 173;
                                                    $a = 1271; $b = 73; $c = 1381; $d = 133;
                                                    for ($i=52; $i >= 50; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=60;
                                                        $d +=60;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="G49" data-nama="G49" data-state="LPK-0000000170" full="Lapak BLOK G NOMOR 49" shape="rect" coords="1301,253,1381,313"  onclick="lapakPasar('LPK-0000000170', '<?=$KodePasar?>', 49, 'G')">

                                                    <!--BAWAH KIRI -->
                                                    <!-- <area href="#" id="E23" data-nama="E16" data-state="LPK-0000000023" full="Lapak BLOK E NOMOR 16" shape="rect" coords="140,510,240,570"  onclick="lapakPasar('LPK-0000000023', '<?=$KodePasar?>', 16, 'E')"> -->

                                                    <?php 
                                                    $kode = 123;
                                                    $a = 140; $b = 570; $c = 200; $d = 630;
                                                    for ($i=2; $i <= 3; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=60;
                                                        $c +=60;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 125;
                                                    $a = 140; $b = 630; $c = 200; $d = 690;
                                                    for ($i=4; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=60;
                                                        $c +=60;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="G6" data-nama="G6" data-state="LPK-0000000127" full="Lapak BLOK G6 NOMOR 6" shape="rect" coords="140,690,260,755"  onclick="lapakPasar('LPK-0000000127', '<?=$KodePasar?>', 6, 'G')">

                                                    <?php 
                                                    $kode = 128;
                                                    $a = 140; $b = 755; $c = 200; $d = 815;
                                                    for ($i=7; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=60;
                                                        $c +=60;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 130;
                                                    $a = 260; $b = 690; $c = 320; $d = 752;
                                                    for ($i=9; $i <= 10; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 132;
                                                    $a = 320; $b = 690; $c = 380; $d = 752;
                                                    for ($i=11; $i <= 12; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 134;
                                                    $a = 380; $b = 690; $c = 440; $d = 752;
                                                    for ($i=13; $i <= 14; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 136;
                                                    $a = 440; $b = 690; $c = 500; $d = 752;
                                                    for ($i=15; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <!--BAWAH TENGAH -->
                                                    <?php 
                                                    $kode = 138;
                                                    $a = 555; $b = 690; $c = 605; $d = 752;
                                                    for ($i=17; $i <= 18; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 140;
                                                    $a = 605; $b = 690; $c = 655; $d = 752;
                                                    for ($i=19; $i <= 20; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 142;
                                                    $a = 655; $b = 690; $c = 705; $d = 752;
                                                    for ($i=21; $i <= 22; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 144;
                                                    $a = 705; $b = 690; $c = 755; $d = 752;
                                                    for ($i=23; $i <= 24; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 146;
                                                    $a = 755; $b = 690; $c = 805; $d = 752;
                                                    for ($i=25; $i <= 26; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 148;
                                                    $a = 805; $b = 690; $c = 855; $d = 752;
                                                    for ($i=27; $i <= 28; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 150;
                                                    $a = 855; $b = 690; $c = 905; $d = 752;
                                                    for ($i=29; $i <= 30; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 152;
                                                    $a = 905; $b = 690; $c = 955; $d = 752;
                                                    for ($i=31; $i <= 32; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>


                                                    <!--BAWAH KANAN -->
                                                    <area href="#" id="G48" data-nama="G48" data-state="LPK-0000000169" full="Lapak BLOK G NOMOR 48" shape="rect" coords="1301,510,1381,570"  onclick="lapakPasar('LPK-0000000169', '<?=$KodePasar?>', 48, 'G')">

                                                    <?php 
                                                    $kode = 167;
                                                    $a = 1271; $b = 570; $c = 1326; $d = 630;
                                                    for ($i=46; $i <= 47; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=55;
                                                        $c +=55;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 166;
                                                    $a = 1271; $b = 630; $c = 1326; $d = 690;
                                                    for ($i=45; $i >= 44; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=55;
                                                        $c +=55;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="G43" data-nama="G43" data-state="LPK-0000000164" full="Lapak BLOK G NOMOR 43" shape="rect" coords="1271,690,1381,755"  onclick="lapakPasar('LPK-0000000164', '<?=$KodePasar?>', 43, 'G')">

                                                    <?php 
                                                    $kode = 154;
                                                    $a = 1010; $b = 690; $c = 1075; $d = 752;
                                                    for ($i=33; $i <= 34; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 156;
                                                    $a = 1075; $b = 690; $c = 1140; $d = 752;
                                                    for ($i=35; $i <= 36; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 158;
                                                    $a = 1140; $b = 690; $c = 1205; $d = 752;
                                                    for ($i=37; $i <= 38; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 160;
                                                    $a = 1205; $b = 690; $c = 1270; $d = 752;
                                                    for ($i=39; $i <= 40; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=62;
                                                        $d +=62;
                                                        $kode = $kode + 1;
                                                    } ?>


                                                    <?php 
                                                    $kode = 162;
                                                    $a = 1271; $b = 755; $c = 1326; $d = 815;
                                                    for ($i=41; $i <= 42; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $a +=55;
                                                        $c +=55;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <!-- TENGAH ATAS-->
                                                    <?php 
                                                    $kode = 201;
                                                    $a = 295; $b = 175; $c = 320; $d = 240;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A'.$i.'" data-nama="A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A'.'\')">';
                                                        $b +=65;
                                                        $d +=65;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="A4" data-nama="A4" data-state="LPK-0000000202" full="Lapak BLOK A NOMOR 4" shape="rect" coords="320,175,345,230"  onclick="lapakPasar('LPK-0000000202', '<?=$KodePasar?>', 4, 'A')">
                                                    <area href="#" id="A5" data-nama="A5" data-state="LPK-0000000203" full="Lapak BLOK A NOMOR 5" shape="rect" coords="320,230,345,320"  onclick="lapakPasar('LPK-0000000203', '<?=$KodePasar?>', 5, 'A')">
                                                    <area href="#" id="A6" data-nama="A6" data-state="LPK-0000000204" full="Lapak BLOK A NOMOR 6" shape="rect" coords="320,320,345,370"  onclick="lapakPasar('LPK-0000000204', '<?=$KodePasar?>', 6, 'A')">

                                                    <?php 
                                                    $kode = 207;
                                                    $a = 380; $b = 175; $c = 410; $d = 245;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B'.$i.'" data-nama="B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="B2" data-nama="B2" data-state="LPK-0000000206" full="Lapak BLOK B NOMOR 2" shape="rect" coords="380,245,410,320"  onclick="lapakPasar('LPK-0000000206', '<?=$KodePasar?>', 2, 'B')">
                                                    <area href="#" id="B5" data-nama="B5" data-state="LPK-0000000209" full="Lapak BLOK B NOMOR 5" shape="rect" coords="410,245,440,320"  onclick="lapakPasar('LPK-0000000209', '<?=$KodePasar?>', 5, 'B')">

                                                    <area href="#" id="B1" data-nama="B1" data-state="LPK-0000000205" full="Lapak BLOK B NOMOR 1" shape="rect" coords="380,320,410,370"  onclick="lapakPasar('LPK-0000000205', '<?=$KodePasar?>', 1, 'B')">
                                                    <area href="#" id="B6" data-nama="B6" data-state="LPK-0000000210" full="Lapak BLOK B NOMOR 6" shape="rect" coords="410,320,440,370"  onclick="lapakPasar('LPK-0000000210', '<?=$KodePasar?>', 6, 'B')">

                                                   
                                                    <?php 
                                                    $kode = 213;
                                                    $a = 465; $b = 175; $c = 495; $d = 245;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="C2" data-nama="C2" data-state="LPK-0000000212" full="Lapak BLOK C NOMOR 2" shape="rect" coords="465,245,495,320"  onclick="lapakPasar('LPK-0000000212', '<?=$KodePasar?>', 2, 'C')">
                                                    <area href="#" id="C5" data-nama="C5" data-state="LPK-0000000215" full="Lapak BLOK C NOMOR 5" shape="rect" coords="495,245,525,320"  onclick="lapakPasar('LPK-0000000215', '<?=$KodePasar?>', 5, 'C')">

                                                    <area href="#" id="C1" data-nama="C1" data-state="LPK-0000000211" full="Lapak BLOK C NOMOR 1" shape="rect" coords="465,320,495,370"  onclick="lapakPasar('LPK-0000000211', '<?=$KodePasar?>', 1, 'C')">
                                                    <area href="#" id="C6" data-nama="C6" data-state="LPK-0000000216" full="Lapak BLOK C NOMOR 6" shape="rect" coords="495,320,525,370"  onclick="lapakPasar('LPK-0000000216', '<?=$KodePasar?>', 6, 'C')">

                                                    <?php 
                                                    $kode = 218;
                                                    $a = 550; $b = 215; $c = 575; $d = 295;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 219;
                                                    $a = 575; $b = 215; $c = 600; $d = 268;
                                                    for ($i=3; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=53;
                                                        $d +=53;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                   
                                                    <?php 
                                                    $kode = 225;
                                                    $a = 630; $b = 215; $c = 680; $d = 255;
                                                    for ($i=4; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E'.$i.'" data-nama="E'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 229;
                                                    $a = 710; $b = 215; $c = 765; $d = 255;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F'.$i.'" data-nama="F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 226;
                                                    $a = 765; $b = 215; $c = 820; $d = 255;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F'.$i.'" data-nama="F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 233;
                                                    $a = 850; $b = 215; $c = 875; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 232;
                                                    $a = 875; $b = 215; $c = 900; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 239;
                                                    $a = 925; $b = 215; $c = 950; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 238;
                                                    $a = 950; $b = 215; $c = 975; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 246;
                                                    $a = 1005; $b = 215; $c = 1030; $d = 250;
                                                    for ($i=5; $i <= 7; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I'.$i.'" data-nama="I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 245;
                                                    $a = 1030; $b = 215; $c = 1055; $d = 250;
                                                    for ($i=4; $i >= 2; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I'.$i.'" data-nama="I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="I8" data-nama="I8" data-state="LPK-0000000249" full="Lapak BLOK I NOMOR 8" shape="rect" coords="1005,320,1030,370"  onclick="lapakPasar('LPK-0000000249', '<?=$KodePasar?>', 8, 'I')">
                                                    <area href="#" id="I1" data-nama="I1" data-state="LPK-0000000242" full="Lapak BLOK I NOMOR 1" shape="rect" coords="1030,320,1055,370"  onclick="lapakPasar('LPK-0000000242', '<?=$KodePasar?>', 1, 'I')">

                                                    

                                                    <?php 
                                                    $kode = 253;
                                                    $a = 1090; $b = 215; $c = 1150; $d = 267;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J'.$i.'" data-nama="J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 251;
                                                    $a = 1090; $b = 320; $c = 1120; $d = 370;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J'.$i.'" data-nama="J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?>


                                                    <?php 
                                                    $kode = 257;
                                                    $a = 1180; $b = 215; $c = 1210; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="K'.$i.'" data-nama="K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'K'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 256;
                                                    $a = 1210; $b = 215; $c = 1240; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="K'.$i.'" data-nama="K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'K'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <!-- TENGAH BAWAH-->

                                                    <?php 
                                                    $kode = 260;
                                                    $a = 295; $b = 410; $c = 320; $d = 455;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A1'.$i.'" data-nama="A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 269;
                                                    $a = 320; $b = 410; $c = 345; $d = 455;
                                                    for ($i=10; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A1'.$i.'" data-nama="A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 264;
                                                    $a = 295; $b = 590; $c = 320; $d = 655;
                                                    for ($i=5; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A1'.$i.'" data-nama="A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A1'.'\')">';
                                                        $a +=25;
                                                        $c +=25;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 270;
                                                    $a = 380; $b = 410; $c = 410; $d = 451;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B1'.$i.'" data-nama="B1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B1'.'\')">';
                                                        $b +=41;
                                                        $d +=41;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 280;
                                                    $a = 410; $b = 410; $c = 440; $d = 460;
                                                    for ($i=11; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B1'.$i.'" data-nama="B1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B1'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 281;
                                                    $a = 465; $b = 410; $c = 495; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C1'.$i.'" data-nama="C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 289;
                                                    $a = 495; $b = 410; $c = 525; $d = 455;
                                                    for ($i=9; $i >= 8; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C1'.$i.'" data-nama="C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 283;
                                                    $a = 465; $b = 500; $c = 495; $d = 580;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C1'.$i.'" data-nama="C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 287;
                                                    $a = 495; $b = 500; $c = 525; $d = 553;
                                                    for ($i=7; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C1'.$i.'" data-nama="C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C1'.'\')">';
                                                        $b +=53;
                                                        $d +=53;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 290;
                                                    $a = 550; $b = 410; $c = 575; $d = 456;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=46;
                                                        $d +=46;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 292;
                                                    $a = 550; $b = 500; $c = 575; $d = 580;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 297;
                                                    $a = 575; $b = 410; $c = 600; $d = 456;
                                                    for ($i=8; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=46;
                                                        $d +=46;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 295;
                                                    $a = 575; $b = 500; $c = 600; $d = 580;
                                                    for ($i=6; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 298;
                                                    $a = 630; $b = 410; $c = 655; $d = 460;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E1'.$i.'" data-nama="E1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E1'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 307;
                                                    $a = 655; $b = 410; $c = 680; $d = 460;
                                                    for ($i=10; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E1'.$i.'" data-nama="E1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E1'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 312;
                                                    $a = 710; $b = 505; $c = 765; $d = 540;
                                                    for ($i=5; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F1'.$i.'" data-nama="F1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F1'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 314;
                                                    $a = 710; $b = 605; $c = 765; $d = 632;
                                                    for ($i=7; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F1'.$i.'" data-nama="F1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F1'.'\')">';
                                                        $b +=27;
                                                        $d +=27;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 311;
                                                    $a = 765; $b = 505; $c = 820; $d = 540;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F1'.$i.'" data-nama="F1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F1'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 309;
                                                    $a = 765; $b = 605; $c = 820; $d = 632;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F1'.$i.'" data-nama="F1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F1'.'\')">';
                                                        $b +=27;
                                                        $d +=27;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 325;
                                                    $a = 850; $b = 410; $c = 875; $d = 455;
                                                    for ($i=10; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G1'.$i.'" data-nama="G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="G1 8" data-nama="G1 8" data-state="LPK-0000000323" full="Lapak BLOK G1 NOMOR 8" shape="rect" coords="850,500,875,580"  onclick="lapakPasar('LPK-0000000323', '<?=$KodePasar?>', 8, 'G1')">
                                                    <?php 
                                                    $kode = 322;
                                                    $a = 850; $b = 580; $c = 875; $d = 620;
                                                    for ($i=7; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G1'.$i.'" data-nama="G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 316;
                                                    $a = 875; $b = 410; $c = 900; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G1'.$i.'" data-nama="G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <area href="#" id="G13" data-nama="G1 3" data-state="LPK-0000000318" full="Lapak BLOK G1 NOMOR 3" shape="rect" coords="875,500,900,580"  onclick="lapakPasar('LPK-0000000318', '<?=$KodePasar?>', 3, 'G1')">
                                                    <?php 
                                                    $kode = 319;
                                                    $a = 875; $b = 580; $c = 900; $d = 620;
                                                    for ($i=4; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G1'.$i.'" data-nama="G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 335;
                                                    $a = 925; $b = 410; $c = 950; $d = 455;
                                                    for ($i=10; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H1'.$i.'" data-nama="H1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="H18" data-nama="H18" data-state="LPK-0000000333" full="Lapak BLOK H1 NOMOR 8" shape="rect" coords="925,500,950,580"  onclick="lapakPasar('LPK-0000000333', '<?=$KodePasar?>', 8, 'H1')">
                                                    <?php 
                                                    $kode = 332;
                                                    $a = 925; $b = 580; $c = 950; $d = 620;
                                                    for ($i=7; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H1'.$i.'" data-nama="H1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 326;
                                                    $a = 950; $b = 410; $c = 975; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H1'.$i.'" data-nama="H1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <area href="#" id="H13" data-nama="H13" data-state="LPK-0000000328" full="Lapak BLOK H1 NOMOR 3" shape="rect" coords="950,500,975,580"  onclick="lapakPasar('LPK-0000000328', '<?=$KodePasar?>', 3, 'H1')">
                                                    <?php 
                                                    $kode = 329;
                                                    $a = 950; $b = 580; $c = 975; $d = 620;
                                                    for ($i=4; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H1'.$i.'" data-nama="H1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 345;
                                                    $a = 1005; $b = 410; $c = 1030; $d = 455;
                                                    for ($i=10; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I1'.$i.'" data-nama="I1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="I18" data-nama="I18" data-state="LPK-0000000343" full="Lapak BLOK I1 NOMOR 8" shape="rect" coords="1005,500,1030,580"  onclick="lapakPasar('LPK-0000000343', '<?=$KodePasar?>', 8, 'I1')">
                                                    <?php 
                                                    $kode = 342;
                                                    $a = 1005; $b = 580; $c = 1030; $d = 620;
                                                    for ($i=7; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I1'.$i.'" data-nama="I1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 336;
                                                    $a = 1030; $b = 410; $c = 1055; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I1'.$i.'" data-nama="I1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <area href="#" id="I13" data-nama="I13" data-state="LPK-0000000338" full="Lapak BLOK I1 NOMOR 3" shape="rect" coords="1030,500,1055,580"  onclick="lapakPasar('LPK-0000000338', '<?=$KodePasar?>', 3, 'I1')">
                                                    <?php 
                                                    $kode = 339;
                                                    $a = 1030; $b = 580; $c = 1055; $d = 620;
                                                    for ($i=4; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="I1'.$i.'" data-nama="I1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'I1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 355;
                                                    $a = 1090; $b = 410; $c = 1120; $d = 455;
                                                    for ($i=10; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J1'.$i.'" data-nama="J1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="J18" data-nama="J18" data-state="LPK-0000000353" full="Lapak BLOK J1 NOMOR 8" shape="rect" coords="1090,500,1120,580"  onclick="lapakPasar('LPK-0000000353', '<?=$KodePasar?>', 8, 'J1')">
                                                    <?php 
                                                    $kode = 352;
                                                    $a = 1090; $b = 580; $c = 1120; $d = 620;
                                                    for ($i=7; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J1'.$i.'" data-nama="J1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 346;
                                                    $a = 1120; $b = 410; $c = 1150; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J1'.$i.'" data-nama="J1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <area href="#" id="J13" data-nama="J13" data-state="LPK-0000000348" full="Lapak BLOK J1 NOMOR 3" shape="rect" coords="1120,500,1150,580"  onclick="lapakPasar('LPK-0000000348', '<?=$KodePasar?>', 3, 'J1')">
                                                    <?php 
                                                    $kode = 349;
                                                    $a = 1120; $b = 580; $c = 1150; $d = 620;
                                                    for ($i=4; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J1'.$i.'" data-nama="J1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'J1'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="K112" data-nama="K112" data-state="LPK-0000000367" full="Lapak BLOK K1 NOMOR 12" shape="rect" coords="1180,410,1210,455"  onclick="lapakPasar('LPK-0000000367', '<?=$KodePasar?>', 12, 'K1')">
                                                    <area href="#" id="K11" data-nama="K111" data-state="LPK-0000000356" full="Lapak BLOK K1 NOMOR 1" shape="rect" coords="1210,410,1240,455"  onclick="lapakPasar('LPK-0000000356', '<?=$KodePasar?>', 1, 'K1')">

                                                    <area href="#" id="K111" data-nama="K111" data-state="LPK-0000000366" full="Lapak BLOK K1 NOMOR 11" shape="rect" coords="1180,455,1210,490"  onclick="lapakPasar('LPK-0000000366', '<?=$KodePasar?>', 11, 'K1')">
                                                    <area href="#" id="K12" data-nama="K12" data-state="LPK-0000000357" full="Lapak BLOK K1 NOMOR 2" shape="rect" coords="1210,455,1240,490"  onclick="lapakPasar('LPK-0000000357', '<?=$KodePasar?>', 2, 'K1')">

                                                    <area href="#" id="K110" data-nama="K110" data-state="LPK-0000000365" full="Lapak BLOK K1 NOMOR 10" shape="rect" coords="1180,490,1210,525"  onclick="lapakPasar('LPK-0000000365', '<?=$KodePasar?>', 10, 'K1')">
                                                    <area href="#" id="K13" data-nama="K13" data-state="LPK-0000000358" full="Lapak BLOK K1 NOMOR 3" shape="rect" coords="1210,490,1240,525"  onclick="lapakPasar('LPK-0000000358', '<?=$KodePasar?>', 3, 'K1')">

                                                    <area href="#" id="K19" data-nama="K19" data-state="LPK-0000000364" full="Lapak BLOK K1 NOMOR 9" shape="rect" coords="1180,525,1210,575"  onclick="lapakPasar('LPK-0000000364', '<?=$KodePasar?>', 9, 'K1')">
                                                    <area href="#" id="K14" data-nama="K14" data-state="LPK-0000000359" full="Lapak BLOK K1 NOMOR 4" shape="rect" coords="1210,525,1240,575"  onclick="lapakPasar('LPK-0000000359', '<?=$KodePasar?>', 4, 'K1')">

                                                    <area href="#" id="K18" data-nama="K18" data-state="LPK-0000000363" full="Lapak BLOK K1 NOMOR 8" shape="rect" coords="1180,575,1210,620"  onclick="lapakPasar('LPK-0000000363', '<?=$KodePasar?>', 8, 'K1')">
                                                    <area href="#" id="K15" data-nama="K15" data-state="LPK-0000000360" full="Lapak BLOK K1 NOMOR 5" shape="rect" coords="1210,575,1240,620"  onclick="lapakPasar('LPK-0000000360', '<?=$KodePasar?>', 5, 'K1')">

                                                    <area href="#" id="K17" data-nama="K17" data-state="LPK-0000000362" full="Lapak BLOK K1 NOMOR 7" shape="rect" coords="1180,620,1210,660"  onclick="lapakPasar('LPK-0000000362', '<?=$KodePasar?>', 7, 'K1')">
                                                    <area href="#" id="K16" data-nama="K16" data-state="LPK-0000000361" full="Lapak BLOK K1 NOMOR 6" shape="rect" coords="1210,620,1240,660"  onclick="lapakPasar('LPK-0000000361', '<?=$KodePasar?>', 6, 'K1')">
                                                </map>
                                                <br>
                                            <div class="table-responsive">
                                                <div id="map_demo2">
                                                    <h3><u>Lantai 2</u></h3>
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img src="../images/Assets/Mapcitraniagalt2.jpg"
                                                        usemap="#image-map" id="usa_image_lt2">
                                                    </div>
                                                </div>


                                                <map name="image-map">
                                                    <?php 
                                                    $kode = 374;//LPK-0000000374
                                                    $a = 129; $b = 61; $c = 81; $d = 81;
                                                    for ($i=7; $i >= 4; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2'.$i.'" data-nama="GL2'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2'.'\')">';
                                                        $a +=52;
                                                        $c +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="GL23" data-nama="GL23" data-state="LPK-0000000370" full="Lapak BLOK GL2 NOMOR 3" shape="rect" coords="364,82,338,62" onclick="lapakPasar('LPK-0000000370', '<?=$KodePasar?>', 3, 'GL2')">
                                                    <area href="#" id="GL22" data-nama="GL22" data-state="LPK-0000000369" full="Lapak BLOK GL2 NOMOR 2" shape="rect" coords="417,82,365,62" onclick="lapakPasar('LPK-0000000369', '<?=$KodePasar?>', 2, 'GL2')">
                                                    <area href="#" id="GL21" data-nama="GL21" data-state="LPK-0000000368" full="Lapak BLOK GL2 NOMOR 1" shape="rect" coords="466,82,417,62" onclick="lapakPasar('LPK-0000000368', '<?=$KodePasar?>', 1, 'GL2')">

                                                    <!-- DAGING -->
                                                    <area target="" alt="" title="" href="" coords="675,322,467,62" data-nama="" shape="rect">

                                                    <!--KANAN -->
                                                    <?php 
                                                    $kode = 401;
                                                    $a = 676; $b = 363; $c = 651; $d = 325;
                                                    for ($i=34; $i >= 24; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2'.$i.'" data-nama="GL2'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <!--Kurungan -->
                                                    <area target="" alt="" title="" href="" coords="675,847,599,786" data-nama="" shape="rect">

                                                    <?php
                                                    $kode = 611;//LPK-0000000611
                                                    $a = 546; $b = 725; $c = 521; $d = 645;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2J1 '.$i.'" data-nama="GL2J1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2J1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2J1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 613;//LPK-0000000613
                                                    $a = 521; $b = 725; $c = 546; $d = 786;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2J1 '.$i.'" data-nama="GL2J1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2J1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2J1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <!-- Pujasera -->
                                                    <?php
                                                    $kode = 628;//LPK-0000000628
                                                    $a = 495; $b = 847; $c = 468; $d = 826;
                                                    for ($i=20; $i >= 15; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="PUJASERA'.$i.'" data-nama="PUJASERA'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK PUJASERA NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'PUJASERA'.'\')">';
                                                        $a -=26;
                                                        $c -=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 627;//LPK-0000000627
                                                    $a = 495; $b = 827; $c = 468; $d = 806;
                                                    for ($i=14; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="PUJASERA'.$i.'" data-nama="PUJASERA'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK PUJASERA NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'PUJASERA'.'\')">';
                                                        $a -=26;
                                                        $c -=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 618;//LPK-0000000618
                                                    $a = 287; $b = 847; $c = 248; $d = 826;
                                                    for ($i=8; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="PUJASERA'.$i.'" data-nama="PUJASERA'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK PUJASERA NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'PUJASERA'.'\')">';
                                                        $a -=39;
                                                        $c -=39;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>
                                                    <?php                                                   
                                                    $kode = 617;//LPK-0000000617
                                                    $a = 287; $b = 827; $c = 248; $d = 806;
                                                    for ($i=4; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="PUJASERA'.$i.'" data-nama="PUJASERA'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK PUJASERA NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'PUJASERA'.'\')">';
                                                        $a -=39;
                                                        $c -=39;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!--KIRI-->

                                                    <?php
                                                    $kode = 389;//LPK-0000000389
                                                    $a = 54; $b = 724; $c = 81; $d = 744;
                                                    for ($i=22; $i >= 18; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2'.$i.'" data-nama="GL2'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2'.'\')">';
                                                        $b -=20;
                                                        $d -=20;
                                                        $kode = $kode - 1;
                                                    }
                                                    
                                                    $i = 23;
                                                    $_kode = str_pad(390, 10, "0", STR_PAD_LEFT);
                                                    echo '<area href="#" id="GL2'.$i.'" data-nama="GL2'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2 NOMOR '.$i.'" shape="rect" coords="54,745,80,786"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2'.'\')">';                                                 
                                                    ?>

                                                    <?php
                                                    $kode = 384;//LPK-0000000380
                                                    $a = 54; $b = 482; $c = 81; $d = 443;
                                                    for ($i=17; $i >= 8; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2'.$i.'" data-nama="GL2'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2'.'\')">';
                                                        $b -=40;
                                                        $d -=40;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOK A -->

                                                    <?php
                                                    $kode = 402;//LPK-0000000402
                                                    $a = 157; $b = 122; $c = 131; $d = 102;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2A'.$i.'" data-nama="GL2A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2A'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 413;//LPK-0000000413
                                                    $a = 157; $b = 142; $c = 131; $d = 122;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2A'.$i.'" data-nama="GL2A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2A'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>


                                                    <?php
                                                    $kode = 534;//LPK-0000000534
                                                    $a = 364; $b = 122; $c = 338; $d = 102;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2A1 '.$i.'" data-nama="GL2A1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2A1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2A1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 541;//LPK-0000000541
                                                    $a = 364; $b = 142; $c = 338; $d = 122;
                                                    for ($i=8; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2A1 '.$i.'" data-nama="GL2A1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2A1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2A1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOK B -->
                                                    <?php
                                                    $kode = 414;//LPK-0000000414
                                                    $a = 157; $b = 182; $c = 131; $d = 162;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2B'.$i.'" data-nama="GL2B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2B'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 425;//LPK-0000000425
                                                    $a = 157; $b = 202; $c = 131; $d = 182;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2B'.$i.'" data-nama="GL2B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2B'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 542;//LPK-0000000542
                                                    $a = 364; $b = 182; $c = 338; $d = 162;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2B1 '.$i.'" data-nama="GL2B1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2B1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2B1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 549;//LPK-0000000549
                                                    $a = 364; $b = 202; $c = 338; $d = 182;
                                                    for ($i=8; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2B1 '.$i.'" data-nama="GL2B1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2B1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2B1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOK C -->
                                                    <?php
                                                    $kode = 426;//LPK-0000000426
                                                    $a = 157; $b = 242; $c = 131; $d = 222;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2C'.$i.'" data-nama="GL2C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2C'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 437;//LPK-0000000437
                                                    $a = 157; $b = 262; $c = 131; $d = 242;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2C'.$i.'" data-nama="GL2C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2C'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 550;//LPK-0000000550
                                                    $a = 364; $b = 242; $c = 338; $d = 222;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2C1 '.$i.'" data-nama="GL2C1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2C1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2C1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 557;//LPK-0000000557
                                                    $a = 364; $b = 262; $c = 338; $d = 242;
                                                    for ($i=8; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2C1 '.$i.'" data-nama="GL2C1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2C1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2C1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG D  -->

                                                    <?php
                                                    $kode = 438;//LPK-0000000438
                                                    $a = 157; $b = 302; $c = 131; $d = 282;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2D'.$i.'" data-nama="GL2D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2D'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 449;//LPK-0000000449
                                                    $a = 157; $b = 322; $c = 131; $d = 302;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2D'.$i.'" data-nama="GL2D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2D'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG E  -->

                                                    <?php
                                                    $kode = 450;//LPK-0000000450
                                                    $a = 157; $b = 362; $c = 131; $d = 342;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2E'.$i.'" data-nama="GL2E'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2E NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2E'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 461;//LPK-0000000461
                                                    $a = 157; $b = 382; $c = 131; $d = 362;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2E'.$i.'" data-nama="GL2E'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2E NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2E'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>



                                                    <?php
                                                    $kode = 558;//LPK-0000000558
                                                    $a = 495; $b = 382; $c = 469; $d = 362;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2D1 '.$i.'" data-nama="GL2D1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2D1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2D1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 567;//LPK-0000000567
                                                    $a = 495; $b = 402; $c = 469; $d = 382;
                                                    for ($i=10; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2D1 '.$i.'" data-nama="GL2D1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2D1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2D1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <!-- BLOG F  -->

                                                    <?php
                                                    $kode = 462;//LPK-0000000462
                                                    $a = 157; $b = 422; $c = 131; $d = 402;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2F'.$i.'" data-nama="GL2F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2F'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 473;//LPK-0000000473
                                                    $a = 157; $b = 442; $c = 131; $d = 422;
                                                    for ($i=6; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2F'.$i.'" data-nama="GL2F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2F'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 568;//LPK-0000000568
                                                    $a = 495; $b = 442; $c = 469; $d = 422;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2E1 '.$i.'" data-nama="GL2E1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2E1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2E1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 577;//LPK-0000000577
                                                    $a = 495; $b = 462; $c = 469; $d = 442;
                                                    for ($i=10; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2E1 '.$i.'" data-nama="GL2E1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2E1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2E1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <!-- BLOG G  -->

                                                    <?php
                                                    $kode = 474;//LPK-0000000474
                                                    $a = 157; $b = 482; $c = 131; $d = 462;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2G'.$i.'" data-nama="GL2G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2G'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 485;//LPK-0000000485
                                                    $a = 157; $b = 502; $c = 131; $d = 482;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2G'.$i.'" data-nama="GL2G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2G'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG H  -->

                                                    <?php
                                                    $kode = 486;//LPK-0000000486
                                                    $a = 157; $b = 542; $c = 131; $d = 522;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2H'.$i.'" data-nama="GL2H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2H'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 497;//LPK-0000000497
                                                    $a = 157; $b = 562; $c = 131; $d = 542;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2H'.$i.'" data-nama="GL2H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2H'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 578;//LPK-0000000578
                                                    $a = 495; $b = 522; $c = 469; $d = 502;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2F1 '.$i.'" data-nama="GL2F1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2F1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2F1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 587;//LPK-0000000587
                                                    $a = 495; $b = 542; $c = 469; $d = 522;
                                                    for ($i=10; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2F1 '.$i.'" data-nama="GL2F1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2F1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2F1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG i -->

                                                    <?php
                                                    $kode = 498;//LPK-0000000498
                                                    $a = 157; $b = 602; $c = 131; $d = 582;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2I'.$i.'" data-nama="GL2I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2I'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 509;//LPK-0000000509
                                                    $a = 157; $b = 622; $c = 131; $d = 602;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2I'.$i.'" data-nama="GL2I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2I'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 588;//LPK-0000000588
                                                    $a = 495; $b = 602; $c = 469; $d = 582;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2G1 '.$i.'" data-nama="GL2G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2G1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2G1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>
                                                    <?php
                                                    $kode = 597;//LPK-0000000597
                                                    $a = 495; $b = 622; $c = 469; $d = 602;
                                                    for ($i=10; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2G1 '.$i.'" data-nama="GL2G1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2G1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2G1 '.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG j-->

                                                    <?php
                                                    $kode = 510;//LPK-0000000510
                                                    $a = 157; $b = 662; $c = 131; $d = 642;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2J'.$i.'" data-nama="GL2J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2J'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 521;//LPK-0000000521
                                                    $a = 157; $b = 682; $c = 131; $d = 662;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2J'.$i.'" data-nama="GL2J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2J'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 598;//LPK-0000000598
                                                    $a = 338; $b = 662; $c = 390; $d = 642;
                                                    for ($i=1; $i <= 3; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2H1 '.$i.'" data-nama="GL2H1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2H1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2H1 '.'\')">';
                                                        $a +=52;
                                                        $c +=52;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 603;//LPK-0000000603
                                                    $a = 338; $b = 682; $c = 390; $d = 662;
                                                    for ($i=6; $i >= 4; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2H1 '.$i.'" data-nama="GL2H1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2H1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2H1 '.'\')">';
                                                        $a +=52;
                                                        $c +=52;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <!-- BLOG k-->

                                                    <?php
                                                    $kode = 522;//LPK-0000000522
                                                    $a = 157; $b = 722; $c = 131; $d = 702;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2K'.$i.'" data-nama="GL2K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2K'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 533;//LPK-0000000533
                                                    $a = 157; $b = 742; $c = 131; $d = 722;
                                                    for ($i=12; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2K'.$i.'" data-nama="GL2K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2K'.'\')">';
                                                        $a +=26;
                                                        $c +=26;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 604;//LPK-0000000604
                                                    $a = 338; $b = 722; $c = 390; $d = 702;
                                                    for ($i=1; $i <= 3; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2I1 '.$i.'" data-nama="GL2I1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2I1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2I1 '.'\')">';
                                                        $a +=52;
                                                        $c +=52;
                                                        $kode = $kode + 1;
                                                    }
                                                    ?>

                                                    <?php
                                                    $kode = 609;//LPK-0000000609
                                                    $a = 338; $b = 742; $c = 390; $d = 722;
                                                    for ($i=6; $i >= 4; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL2I1 '.$i.'" data-nama="GL2I1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL2I1  NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL2I1 '.'\')">';
                                                        $a +=52;
                                                        $c +=52;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                </map>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Modal-->
    <div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

    <div id="myModal" class="modal">
      <!-- <span class="close">&times;</span> -->
      <img class="modal-content-yo" id="img01">
      <!-- <div id="caption"></div> -->
    </div>

    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <!-- <script src="../komponen/js/charts-home.js"></script> -->
    <!--ImageMapster-->

    <script type="text/javascript" src="../komponen/ImageMapster-master/src/redist/when.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/core.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/graphics.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/mapimage.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/mapdata.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/areadata.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/areacorners.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/scale.js?1"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/tooltip.js?1"></script>
    <!-- <script src="../komponen/js/jquery.imagemapster.min.js"></script> -->
    <!-- Sweet Alerts -->
    <script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script> 

    <script type="text/javascript">
        var modal = document.getElementById("myModal");
        var img = document.getElementById("img-spinner");
        var modalImg = document.getElementById("img01");

        $(document).ready(function () {
            var $statelist, $usamap, ratio,
            mapsterConfigured = function () {
                var opts = $usamap.mapster('get_options', null, true);
                if (!ratio) {
                    ratio = $usamap.width() / $usamap.height();
                }
                $('#stroke_highlight').prop("checked", opts.render_highlight.stroke);
                $('#strokewidth_highlight').val(opts.render_highlight.strokeWidth);
                $('#fill_highlight').val(opts.render_highlight.fillOpacity);
                $('#strokeopacity_highlight').val(opts.render_highlight.strokeOpacity);
                $('#stroke_select').prop("checked", opts.render_select.stroke);
                $('#strokewidth_select').val(opts.render_select.strokeWidth);
                $('#fill_select').val(opts.render_select.fillOpacity);
                $('#strokeopacity_select').val(opts.render_select.strokeOpacity);
                $('#mouseout-delay').val(opts.mouseoutDelay);
                $('#img_width').val($usamap.width());
            },
            default_options =
            {
              
                render_highlight: {
                    fillColor: 'e6e4e1',
                    fillOpacity: 0.3,
                    strokeOpacity: 0.5,
                    stroke: true
                },
                render_select: {
                    stroke: true,
                    strokeWidth: 2,
                    // strokeOpacity: 0.8,
                    strokeColor: '6e7570'
                    // strokeColor: 'ffffff'
                },
                mouseoutDelay: 0,
                fadeInterval: 50,
                isSelectable: true,
                singleSelect: false,
                mapKey: 'data-state',
                mapValue: 'full',
                listKey: 'name',
                sortList: "asc",

                onConfigured: mapsterConfigured,
                showToolTip: false,
                toolTipClose: ["area-mouseout"],
                areas: [
                <?php 
                foreach ($data_lapak as $data) {
                    // $color = isset($data['IDPerson']) && $data['IDPerson'] != '' ? isset($data['selisih']) && $data['selisih'] < $data['JumlahTransaksi'] ? 'fefcfd' : 'fefcfd' :  'fefcfd' ;
                    $color = isset($data['IDPerson']) && $data['IDPerson'] != '' ? isset($data['selisih']) && $data['selisih'] < $data['JumlahTransaksi'] ? 'ffab03' : 'e32209' :  'bab9b8' ;
                    // $color = isset($data['selisih']) && $data['selisih'] < $data['JumlahTransaksi'] ? 'ffab03' : 'e32209';
                    echo '{key: "'.$data['IDLapak'].'",
                    staticState: true,
                    fillOpacity: 0.7,
                    fillColor: "'.$color.'"}, ';
                }
                echo '{key: "Kantor",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';  
                echo '{key: "Fasum",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';
                echo '{key: "Fasum1",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';    
                echo '{key: "HPL2",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, '; 
                echo '{key: "HPL3",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';
                echo '{key: "HPL4",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';
                echo '{key: "HPL5",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';
                echo '{key: "HPL6",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';
                echo '{key: "HPL7",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';      
                echo '{key: "ZK",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, '; 
                ?>
                ]
            };
            $statelist = $('#statelist');
            $usamap = $('#usa_image');
            $usamap.mapster(default_options);
        });

        $(document).ready(function () {
            var $statelistlt2, $usamaplt2, ratio2,
            mapsterConfigured = function () {
                var opts = $usamaplt2.mapster('get_options', null, true);
                if (!ratio2) {
                    ratio2 = $usamaplt2.width() / $usamaplt2.height();
                }
                $('#stroke_highlight').prop("checked", opts.render_highlight.stroke);
                $('#strokewidth_highlight').val(opts.render_highlight.strokeWidth);
                $('#fill_highlight').val(opts.render_highlight.fillOpacity);
                $('#strokeopacity_highlight').val(opts.render_highlight.strokeOpacity);
                $('#stroke_select').prop("checked", opts.render_select.stroke);
                $('#strokewidth_select').val(opts.render_select.strokeWidth);
                $('#fill_select').val(opts.render_select.fillOpacity);
                $('#strokeopacity_select').val(opts.render_select.strokeOpacity);
                $('#mouseout-delay').val(opts.mouseoutDelay);
                $('#img_width').val($usamaplt2.width());
            },
            default_options1 = {
                render_highlight: {
                    fillColor: 'e6e4e1',
                    fillOpacity: 0.3,
                    strokeOpacity: 0.5,
                    stroke: true
                },
                render_select: {
                    stroke: true,
                    strokeWidth: 2,
                    strokeColor: '6e7570'
                },
                mouseoutDelay: 0,
                fadeInterval: 50,
                isSelectable: true,
                singleSelect: false,
                mapKey: 'data-state',
                mapValue: 'full',
                listKey: 'name',
                sortList: "asc",

                onConfigured: mapsterConfigured,
                showToolTip: false,
                toolTipClose: ["area-mouseout"],
                areas: [ <?php
                    foreach($data_lapak as $data) {
                        $color = isset($data['IDPerson']) && $data['IDPerson'] != '' ? isset($data['selisih']) && $data['selisih'] < $data['JumlahTransaksi'] ? 'ffab03' : 'e32209' :  'bab9b8' ;
                            echo '{key: "'.$data['IDLapak'].
                            '",
                            staticState: true,
                            fillOpacity: 0.7,
                            fillColor: "'.$color.'"
                        }, ';
                    }
                    echo '{key: "Kantor",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';  
                    echo '{key: "Fasum",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';
                    echo '{key: "Fasum1",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';    
                    echo '{key: "HPL2",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, '; 
                    echo '{key: "HPL3",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';
                    echo '{key: "HPL4",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';
                    echo '{key: "HPL5",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';
                    echo '{key: "HPL6",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';
                    echo '{key: "HPL7",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';      
                    echo '{key: "ZK",
                    staticState: true,
                    fillColor: "7bacd1",
                    fillOpacity: 0}, ';  ?>]
                };
                $statelistlt2 = $('#statelistlt2');
                $usamaplt2 = $('#usa_image_lt2');
                $usamaplt2.mapster(default_options1);
            });

        function lapakPasar(kodeLapak, kodepasar) {
            var namaPasar = "Blimbing";
            $.ajax({
                url: "MapModal.php",
                type: "GET",
                data : {KodeLapak: kodeLapak, KodePasar: kodepasar, NamaPasar:namaPasar},
                beforeSend: function(){
                    modal.style.display = "block";
                    modalImg.src = document.getElementById("img-spinner").src;
                },
                success: function (ajaxData){
                    $('#myModal').modal('hide');
                    $("#ModalEdit").html(ajaxData);
                    $("#ModalEdit").modal('show',{backdrop: 'true'});
                }
            });
            $('#myModal').modal('show');
        }

        //Lantai2
        // $(function() {
        //     var pasar = "<?=$KodePasar?>";
        //     $('area').each(function(){
        //         var txt=$(this).data('nama');
        //         var idlapak=$(this).data('state');
        //         var coor=$(this).attr('coords');
        //         var coorA=coor.split(',');
        //         //var num = txt.substr(1, 4);
                
        //         if(txt.includes("GL2")){
        //             var res = txt.replace("GL2", "");
        //             if(Number.isInteger(parseInt(res))){
        //                 if(parseInt(res) <=7 && parseInt(res) >= 4){
        //                     var left=parseFloat(coorA[0])-12;
        //                     var top=parseFloat(coorA[1])+710;
        //                     var css ='style="font-size:8pt"';
        //                     txt = 'G '+parseInt(res);
        //                 }else if(parseInt(res) <=2 && parseInt(res) >= 1){
        //                     var left=parseFloat(coorA[0])-13;
        //                     var top=parseFloat(coorA[1])+690;
        //                     var css ='style="font-size:8pt"';
        //                     txt = 'G '+parseInt(res);
        //                 }else if(parseInt(res) === 3){
        //                     var left=parseFloat(coorA[0])+2;
        //                     var top=parseFloat(coorA[1])+690;
        //                     var css ='style="font-size:7pt"';
        //                     txt = 'G '+parseInt(res);
        //                 }else if(parseInt(res) <=17 && parseInt(res) >= 8){
        //                     var left=parseFloat(coorA[0])+25;
        //                     var top=parseFloat(coorA[1])+690;
        //                     var css ='style="font-size:7pt"';
        //                     txt = 'G '+parseInt(res);
        //                 }else if(parseInt(res) <=22 && parseInt(res) >= 18){
        //                     var left=parseFloat(coorA[0])+25;
        //                     var top=parseFloat(coorA[1])+715;
        //                     var css ='style="font-size:7pt"';
        //                     txt = 'G '+parseInt(res);
        //                 }else if(parseInt(res) === 23){
        //                     var left=parseFloat(coorA[0])+26;
        //                     var top=parseFloat(coorA[1])+693;
        //                     var css ='style="font-size:7pt"';
        //                     txt = parseInt(res);
        //                 }else if(parseInt(res) <=34 && parseInt(res) >= 24){
        //                     var left=parseFloat(coorA[0])-1;
        //                     var top=parseFloat(coorA[1])+653;
        //                     var css ='style="font-size:7pt"';
        //                     txt = parseInt(res);
        //                 }
        //             }else{
        //                 if(txt.includes("GL2A1 ")){
        //                     var res = txt.replace("GL2A1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'A1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'A1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2B1 ")){
        //                     var res = txt.replace("GL2B1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'B1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'B1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2C1 ")){
        //                     var res = txt.replace("GL2C1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'C1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'C1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2D1 ")){
        //                     var res = txt.replace("GL2D1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'D1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'D1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2E1 ")){
        //                     var res = txt.replace("GL2E1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'E1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'E1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2F1 ")){
        //                     var res = txt.replace("GL2F1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'F1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'F1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2G1 ")){
        //                     var res = txt.replace("GL2G1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'G1 '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'G1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2H1 ")){
        //                     var res = txt.replace("GL2H1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])+41;
        //                             var top=parseFloat(coorA[1])+669;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'H1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2I1 ")){
        //                     var res = txt.replace("GL2I1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0])+41;
        //                             var top=parseFloat(coorA[1])+669;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = '11 '+parseInt(res);
        //                         }
        //                     }
        //                 }else if(txt.includes("GL2J1 ")){
        //                     var res = txt.replace("GL2J1 ", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=2 && parseInt(res) >= 1){
        //                             var left=parseFloat(coorA[0]);
        //                             var top=parseFloat(coorA[1])+635;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'J1 '+parseInt(res);
        //                         }else if(parseInt(res) <=4 && parseInt(res) >= 3){
        //                             var left=parseFloat(coorA[0])+26;
        //                             var top=parseFloat(coorA[1])+720;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'J1 '+parseInt(res);
        //                         }
        //                     }
        //                 }else{
        //                     var res = txt.replace("GL2A", "");
        //                     if(Number.isInteger(parseInt(res))){
        //                         if(parseInt(res) <=6 && parseInt(res) >= 1){
        //                         var left=parseFloat(coorA[0])-2;
        //                         var top=parseFloat(coorA[1])+670;
        //                         var css ='style="font-size:8pt"';                   
        //                         txt = 'A '+parseInt(res);
        //                         }else if(parseInt(res) <=12 && parseInt(res) >= 7){
        //                             var left=parseFloat(coorA[0])-2;
        //                             var top=parseFloat(coorA[1])+670;
        //                             var css ='style="font-size:8pt"';                   
        //                             txt = 'A '+parseInt(res);
        //                         }
        //                     }
        //                 }               
        //             }
        //             var $span=$('<span class="map_title text-center" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" ><b>'+txt+'</b></span>');
        //             $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'black'});
        //             $span.appendTo('#map_demo2');
        //         }
        //     })
        // });

      
        //Lantai1
        // $(function() {
        //     var pasar = "<?=$KodePasar?>";
        //     $('area').each(function(){
        //         var txt=$(this).data('nama');
        //         var idlapak=$(this).data('state');
        //         var coor=$(this).attr('coords');
        //         var coorA=coor.split(',');
        //         var num = txt.substr(0, 1);
        //         var num1 = txt.substr(0, 3);
        //         var num2 = txt.substr(0, 2);
        //         if(num == 'A' || num == 'B' || num == 'C' || num == 'D' || num2 == 'G ' || num == 'H' || num == 'J' || num == 'K' || num1 == 'G1 '){
                    
        //            var left=parseFloat(coorA[0])+20;
        //            var top=parseFloat(coorA[1])+102;
        //            var css ='style="font-size:7pt; writing-mode: vertical-rl"'; 
        //         } else if(num == 'E' || num == 'F'){
        //             if(num1 == 'E1 '){
        //                 var left=parseFloat(coorA[0])+20;
        //                var top=parseFloat(coorA[1])+102;
        //                var css ='style="font-size:7pt; writing-mode: vertical-rl"'; 
        //             } else {
        //                 var left=parseFloat(coorA[0])+22;
        //                 var top=parseFloat(coorA[1])+102;
        //                 var css ='style="font-size:7pt;"';
        //             }
        //         } else if(num == 'I'){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+102;
        //             var css ='style="font-size:7pt; writing-mode: vertical-rl"';
        //         } else if (num == 'G'){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+110;
        //             var css ='style="font-size:8pt"';
        //         }
                

        //         var $span=$('<span class="map_title" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" ><b>'+txt+'</b></span>');
        //         $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'black'});
        //         $span.appendTo('#map_demo');
        //     })
        // });


       
    </script>
</body>
</html>