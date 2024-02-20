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
    SELECT p.KodePasar, p.IDLapak, datediff(current_date(), p.TglAktif) as selisih, p.TglAktif, p.IDPerson
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
                                                        <img style="width:100%;border:0;" id="usa_image" src="../images/Assets/MapCitraNiagaDalam.png" usemap="#usa" >
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
                                                        echo '<area href="#" id="GL1 A'.$i.'" data-nama="GL1 A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 A'.'\')">';
                                                        $b +=65;
                                                        $d +=65;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="GL1 A4" data-nama="GL1 A4" data-state="LPK-0000000202" full="Lapak BLOK GL1 A NOMOR 4" shape="rect" coords="320,175,345,230"  onclick="lapakPasar('LPK-0000000202', '<?=$KodePasar?>', 4, 'GL1 A')">
                                                    <area href="#" id="GL1 A5" data-nama="GL1 A5" data-state="LPK-0000000203" full="Lapak BLOK GL1 A NOMOR 5" shape="rect" coords="320,230,345,320"  onclick="lapakPasar('LPK-0000000203', '<?=$KodePasar?>', 5, 'GL1 A')">
                                                    <area href="#" id="GL1 A6" data-nama="GL1 A6" data-state="LPK-0000000204" full="Lapak BLOK GL1 A NOMOR 6" shape="rect" coords="320,320,345,370"  onclick="lapakPasar('LPK-0000000204', '<?=$KodePasar?>', 6, 'GL1 A')">

                                                    <?php 
                                                    $kode = 207;
                                                    $a = 380; $b = 175; $c = 410; $d = 245;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 B'.$i.'" data-nama="GL1 B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 B'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="GL1 B2" data-nama="GL1 B2" data-state="LPK-0000000206" full="Lapak BLOK GL1 B NOMOR 2" shape="rect" coords="380,245,410,320"  onclick="lapakPasar('LPK-0000000206', '<?=$KodePasar?>', 2, 'GL1 B')">
                                                    <area href="#" id="GL1 B5" data-nama="GL1 B5" data-state="LPK-0000000209" full="Lapak BLOK GL1 B NOMOR 5" shape="rect" coords="410,245,440,320"  onclick="lapakPasar('LPK-0000000209', '<?=$KodePasar?>', 5, 'GL1 B')">

                                                    <area href="#" id="GL1 B1" data-nama="GL1 B1" data-state="LPK-0000000205" full="Lapak BLOK GL1 B NOMOR 1" shape="rect" coords="380,320,410,370"  onclick="lapakPasar('LPK-0000000205', '<?=$KodePasar?>', 1, 'GL1 B')">
                                                    <area href="#" id="GL1 B6" data-nama="GL1 B6" data-state="LPK-0000000210" full="Lapak BLOK GL1 B NOMOR 6" shape="rect" coords="410,320,440,370"  onclick="lapakPasar('LPK-0000000210', '<?=$KodePasar?>', 6, 'GL1 B')">

                                                   
                                                    <?php 
                                                    $kode = 213;
                                                    $a = 465; $b = 175; $c = 495; $d = 245;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 C'.$i.'" data-nama="GL1 C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 C'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" id="GL1 C2" data-nama="GL1 C2" data-state="LPK-0000000212" full="Lapak BLOK GL1 C NOMOR 2" shape="rect" coords="465,245,495,320"  onclick="lapakPasar('LPK-0000000212', '<?=$KodePasar?>', 2, 'GL1 C')">
                                                    <area href="#" id="GL1 C5" data-nama="GL1 C5" data-state="LPK-0000000215" full="Lapak BLOK GL1 C NOMOR 5" shape="rect" coords="495,245,525,320"  onclick="lapakPasar('LPK-0000000215', '<?=$KodePasar?>', 5, 'GL1 C')">

                                                    <area href="#" id="GL1 C1" data-nama="GL1 C1" data-state="LPK-0000000211" full="Lapak BLOK GL1 C NOMOR 1" shape="rect" coords="465,320,495,370"  onclick="lapakPasar('LPK-0000000211', '<?=$KodePasar?>', 1, 'GL1 C')">
                                                    <area href="#" id="GL1 C6" data-nama="GL1 C6" data-state="LPK-0000000216" full="Lapak BLOK GL1 C NOMOR 6" shape="rect" coords="495,320,525,370"  onclick="lapakPasar('LPK-0000000216', '<?=$KodePasar?>', 6, 'GL1 C')">

                                                    <?php 
                                                    $kode = 218;
                                                    $a = 550; $b = 215; $c = 575; $d = 295;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D'.$i.'" data-nama="GL1 D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 219;
                                                    $a = 575; $b = 215; $c = 600; $d = 268;
                                                    for ($i=3; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D'.$i.'" data-nama="GL1 D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D'.'\')">';
                                                        $b +=53;
                                                        $d +=53;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                   
                                                    <?php 
                                                    $kode = 225;
                                                    $a = 630; $b = 215; $c = 680; $d = 255;
                                                    for ($i=4; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 E'.$i.'" data-nama="GL1 E'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 E NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 E'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 229;
                                                    $a = 710; $b = 215; $c = 765; $d = 255;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 F'.$i.'" data-nama="GL1 F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 F'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 226;
                                                    $a = 765; $b = 215; $c = 820; $d = 255;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 F'.$i.'" data-nama="GL1 F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 F'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 233;
                                                    $a = 850; $b = 215; $c = 875; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 G'.$i.'" data-nama="GL1 G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 G'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 232;
                                                    $a = 875; $b = 215; $c = 900; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 G'.$i.'" data-nama="GL1 G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 G'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 239;
                                                    $a = 925; $b = 215; $c = 950; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 H'.$i.'" data-nama="GL1 H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 H'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 238;
                                                    $a = 950; $b = 215; $c = 975; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 H'.$i.'" data-nama="GL1 H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 H'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 246;
                                                    $a = 1005; $b = 215; $c = 1030; $d = 250;
                                                    for ($i=5; $i <= 7; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 I'.$i.'" data-nama="GL1 I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 I'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 245;
                                                    $a = 1030; $b = 215; $c = 1055; $d = 250;
                                                    for ($i=4; $i >= 2; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 I'.$i.'" data-nama="GL1 I'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 I NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 I'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" id="GL1 I8" data-nama="GL1 I8" data-state="LPK-0000000249" full="Lapak BLOK GL1 I NOMOR 8" shape="rect" coords="1005,320,1030,370"  onclick="lapakPasar('LPK-0000000249', '<?=$KodePasar?>', 8, 'GL1 I')">
                                                    <area href="#" id="GL1 I1" data-nama="GL1 I1" data-state="LPK-0000000242" full="Lapak BLOK GL1 I NOMOR 1" shape="rect" coords="1030,320,1055,370"  onclick="lapakPasar('LPK-0000000242', '<?=$KodePasar?>', 1, 'GL1 I')">

                                                    

                                                    <?php 
                                                    $kode = 253;
                                                    $a = 1090; $b = 215; $c = 1150; $d = 267;
                                                    for ($i=4; $i >= 3; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 J'.$i.'" data-nama="GL1 J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 J'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 251;
                                                    $a = 1090; $b = 320; $c = 1120; $d = 370;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 J'.$i.'" data-nama="GL1 J'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 J NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 J'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?>


                                                    <?php 
                                                    $kode = 257;
                                                    $a = 1180; $b = 215; $c = 1210; $d = 267;
                                                    for ($i=4; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 K'.$i.'" data-nama="GL1 K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 K'.'\')">';
                                                        $b +=52;
                                                        $d +=52;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 256;
                                                    $a = 1210; $b = 215; $c = 1240; $d = 267;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 K'.$i.'" data-nama="GL1 K'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 K NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 K'.'\')">';
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
                                                        echo '<area href="#" id="GL1 A1'.$i.'" data-nama="GL1 A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 A1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 269;
                                                    $a = 320; $b = 410; $c = 345; $d = 455;
                                                    for ($i=10; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 A1'.$i.'" data-nama="GL1 A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 A1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 264;
                                                    $a = 295; $b = 590; $c = 320; $d = 655;
                                                    for ($i=5; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 A1'.$i.'" data-nama="GL1 A1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 A1'.'\')">';
                                                        $a +=25;
                                                        $c +=25;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 270;
                                                    $a = 380; $b = 410; $c = 410; $d = 451;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 B1'.$i.'" data-nama="GL1 B1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 B1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 B1'.'\')">';
                                                        $b +=41;
                                                        $d +=41;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 280;
                                                    $a = 410; $b = 410; $c = 440; $d = 460;
                                                    for ($i=11; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 B1'.$i.'" data-nama="GL1 B1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 B1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 B1'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 281;
                                                    $a = 465; $b = 410; $c = 495; $d = 455;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 C1'.$i.'" data-nama="GL1 C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 C1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 289;
                                                    $a = 495; $b = 410; $c = 525; $d = 455;
                                                    for ($i=9; $i >= 8; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 C1'.$i.'" data-nama="GL1 C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 C1'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 283;
                                                    $a = 465; $b = 500; $c = 495; $d = 580;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 C1'.$i.'" data-nama="GL1 C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 C1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 287;
                                                    $a = 495; $b = 500; $c = 525; $d = 553;
                                                    for ($i=7; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 C1'.$i.'" data-nama="GL1 C1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 C1'.'\')">';
                                                        $b +=53;
                                                        $d +=53;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 290;
                                                    $a = 550; $b = 410; $c = 575; $d = 456;
                                                    for ($i=1; $i <= 2; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D1'.$i.'" data-nama="GL1 D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D1'.'\')">';
                                                        $b +=46;
                                                        $d +=46;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 292;
                                                    $a = 550; $b = 500; $c = 575; $d = 580;
                                                    for ($i=3; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D1'.$i.'" data-nama="GL1 D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 297;
                                                    $a = 575; $b = 410; $c = 600; $d = 456;
                                                    for ($i=8; $i >= 7; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D1'.$i.'" data-nama="GL1 D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D1'.'\')">';
                                                        $b +=46;
                                                        $d +=46;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 295;
                                                    $a = 575; $b = 500; $c = 600; $d = 580;
                                                    for ($i=6; $i >= 5; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 D1'.$i.'" data-nama="GL1 D1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 D1'.'\')">';
                                                        $b +=80;
                                                        $d +=80;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 298;
                                                    $a = 630; $b = 410; $c = 655; $d = 460;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="GL1 E1'.$i.'" data-nama="GL1 E1'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK GL1 E1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'GL1 E1'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <!-- <?php 
                                                    $kode = 179;
                                                    $a = 655; $b = 410; $c = 680; $d = 460;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=50;
                                                        $d +=50;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 181;
                                                    $a = 710; $b = 505; $c = 765; $d = 540;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 183;
                                                    $a = 710; $b = 605; $c = 765; $d = 632;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=27;
                                                        $d +=27;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 185;
                                                    $a = 765; $b = 505; $c = 820; $d = 540;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 187;
                                                    $a = 765; $b = 605; $c = 820; $d = 632;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=27;
                                                        $d +=27;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 189;
                                                    $a = 850; $b = 410; $c = 875; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000190" full="Lapak BLOK E NOMOR 16" shape="rect" coords="850,500,875,580"  onclick="lapakPasar('LPK-0000000190', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 192;
                                                    $a = 850; $b = 580; $c = 875; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 194;
                                                    $a = 875; $b = 410; $c = 900; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000195" full="Lapak BLOK E NOMOR 16" shape="rect" coords="875,500,900,580"  onclick="lapakPasar('LPK-0000000195', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 197;
                                                    $a = 875; $b = 580; $c = 900; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 199;
                                                    $a = 925; $b = 410; $c = 950; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000200" full="Lapak BLOK E NOMOR 16" shape="rect" coords="925,500,950,580"  onclick="lapakPasar('LPK-0000000200', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 202;
                                                    $a = 925; $b = 580; $c = 950; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?>
 -->
                                                    <!-- <?php 
                                                    $kode = 204;
                                                    $a = 950; $b = 410; $c = 975; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000205" full="Lapak BLOK E NOMOR 16" shape="rect" coords="950,500,975,580"  onclick="lapakPasar('LPK-0000000205', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 207;
                                                    $a = 950; $b = 580; $c = 975; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 209;
                                                    $a = 1005; $b = 410; $c = 1030; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000210" full="Lapak BLOK E NOMOR 16" shape="rect" coords="1005,500,1030,580"  onclick="lapakPasar('LPK-0000000210', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 212;
                                                    $a = 1005; $b = 580; $c = 1030; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 214;
                                                    $a = 1030; $b = 410; $c = 1055; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000215" full="Lapak BLOK E NOMOR 16" shape="rect" coords="1030,500,1055,580"  onclick="lapakPasar('LPK-0000000215', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 217;
                                                    $a = 1030; $b = 580; $c = 1055; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 219;
                                                    $a = 1090; $b = 410; $c = 1120; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000220" full="Lapak BLOK E NOMOR 16" shape="rect" coords="1090,500,1120,580"  onclick="lapakPasar('LPK-0000000220', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 222;
                                                    $a = 1090; $b = 580; $c = 1120; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 224;
                                                    $a = 1120; $b = 410; $c = 1150; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                    <!-- <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000220" full="Lapak BLOK E NOMOR 16" shape="rect" coords="1120,500,1150,580"  onclick="lapakPasar('LPK-0000000225', '<?=$KodePasar?>', 16, 'E')"> -->
                                                    <!-- <?php 
                                                    $kode = 227;
                                                    $a = 1120; $b = 580; $c = 1150; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 229;
                                                    $a = 1180; $b = 410; $c = 1210; $d = 455;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 231;
                                                    $a = 1180; $b = 455; $c = 1210; $d = 490;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 233;
                                                    $a = 1180; $b = 490; $c = 1210; $d = 525;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 235;
                                                    $a = 1180; $b = 525; $c = 1210; $d = 575;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 235;
                                                    $a = 1180; $b = 575; $c = 1210; $d = 620;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->

                                                    <!-- <?php 
                                                    $kode = 235;
                                                    $a = 1180; $b = 620; $c = 1210; $d = 660;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode - 1;
                                                    } ?> -->
                                                   


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

    <script type="text/javascript" src="../komponen/ImageMapster-master/src/redist/when.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/core.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/graphics.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/mapimage.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/mapdata.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/areadata.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/areacorners.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/scale.js"></script>
    <script type="text/javascript" src="../komponen/ImageMapster-master/src/tooltip.js"></script>
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
                    fillOpacity: 0.8,
                    strokeOpacity: 0.8,
                    stroke: true
                },
                render_select: {
                    stroke: true,
                    strokeWidth: 2,
                    // strokeOpacity: 0.8,
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
                areas: [
                <?php 
                foreach ($data_lapak as $data) {
                    $color = isset($data['selisih']) && $data['selisih'] < $data['JumlahTransaksi'] ? 'ffab03' : 'e32209';
                    echo '{key: "'.$data['IDLapak'].'",
                    staticState: true,
                    fillOpacity: 1,
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

      

        $(function() {
            var pasar = "<?=$KodePasar?>";
            $('area').each(function(){
                var txt=$(this).data('nama');
                var idlapak=$(this).data('state');
                var coor=$(this).attr('coords');
                var coorA=coor.split(',');
                var num = txt.substr(0, 1);
                var num1 = txt.substr(4, 1);
                var num2 = txt.substr(4, 2);
                if(num1 == 'A' || num1 == 'B' || num1 == 'C' || num1 == 'D' || num1 == 'G' || num1 == 'H' || num1 == 'J' || num1 == 'K'){
                    if(num2 == 'A1'){
                       var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:5pt; writing-mode: vertical-rl"'; 
                    } else if(num2 == 'B1'){
                       var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:5pt; writing-mode: vertical-rl"'; 
                    } else if(num2 == 'C1'){
                       var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:5pt; writing-mode: vertical-rl"'; 
                    } else if(num2 == 'D1'){
                       var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:5pt; writing-mode: vertical-rl"'; 
                    } else{
                       var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:7pt; writing-mode: vertical-rl"'; 
                    }
                } else if(num1 == 'E' || num1 == 'F'){
                    if(num2 == 'E1'){
                        var left=parseFloat(coorA[0])+20;
                       var top=parseFloat(coorA[1])+102;
                       var css ='style="font-size:6pt; writing-mode: vertical-rl"'; 
                    } else {
                        var left=parseFloat(coorA[0])+22;
                        var top=parseFloat(coorA[1])+102;
                        var css ='style="font-size:6pt;"';
                    }
                } else if(num1 == 'I'){
                    var left=parseFloat(coorA[0])+22;
                    var top=parseFloat(coorA[1])+102;
                    var css ='style="font-size:6pt; writing-mode: vertical-rl"';
                } else if (num == 'G'){
                    var left=parseFloat(coorA[0])+23;
                    var top=parseFloat(coorA[1])+110;
                    var css ='style="font-size:8pt"';
                // } else if (num == 671){
                //     var left=parseFloat(coorA[0])+28;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:8pt"';
                // } else if (num == 683){
                //     var left=parseFloat(coorA[0])+18;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:8pt"';
                // } else if(num >= 672 && num <= 682){
                //     var left=parseFloat(coorA[0])+23;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:8pt"';
                // } else if(num >= 712 && num <= 722){
                //     var left=parseFloat(coorA[0])+23;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:8pt"';
                // } else if(num >= 960 && num <= 971){
                //     var left=parseFloat(coorA[0])+22;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:7pt"';
                // } else if(num >= 973 && num <= 982){
                //     var left=parseFloat(coorA[0])+22;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:7pt"';
                // } else if(num == 972){
                //     var left=parseFloat(coorA[0])+21;
                //     var top=parseFloat(coorA[1])+85;
                //     var css ='style="font-size:7pt"';
                // } else if(num >= 629 && num <= 640){
                //     var left=parseFloat(coorA[0])+23;
                //     var top=parseFloat(coorA[1])+80;
                //     var css ='style="font-size:7pt"';
                // } else if(num >= 648 && num <= 659){
                //     var left=parseFloat(coorA[0])+23;
                //     var top=parseFloat(coorA[1])+82;
                //     var css ='style="font-size:7pt"';
                // } else if(num >= 662 && num <= 670){
                //     var left=parseFloat(coorA[0])+24;
                //     var top=parseFloat(coorA[1])+45;
                //     var css ='style="font-size:7pt;  writing-mode: vertical-rl"';
                // } else if(num == 881){
                //     var left=parseFloat(coorA[0])+26;
                //     var top=parseFloat(coorA[1])+95;
                //     var css ='style="font-size:9pt"';
                // } else if(num >= 882 && num <= 889){
                //     var left=parseFloat(coorA[0])+23;
                //     var top=parseFloat(coorA[1])+75;
                //     var css ='style="font-size:7pt"';
                // } else if(num == 890){
                //     var left=parseFloat(coorA[0])+22;
                //     var top=parseFloat(coorA[1])+78;
                //     var css ='style="font-size:7pt"';

                // } else if(num >= 684 && num <= 707){
                //     var left=parseFloat(coorA[0])+18;
                //     var top=parseFloat(coorA[1])+78;
                //     var css ='style="font-size:7pt; writing-mode: vertical-rl"';
                // }else{
                //     var left=parseFloat(coorA[0])+28;
                //     var top=parseFloat(coorA[1])+60;
                //     var css ='style="font-size:8pt"';
                }
                

                var $span=$('<span class="map_title" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" >'+txt+'</span>');
                $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'white'});
                $span.appendTo('#map_demo');
            })
        });


       
    </script>
</body>
</html>