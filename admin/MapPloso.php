<?php
include 'akses.php';
@$fitur_id = 58;
include '../library/lock-menu.php';
// include '../library/cek.php';
$Page = 'MappingPasar';
$SubPage = 'MapPloso';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$KodePasar = 'PSR-2019-0000016';
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
$res_lapak = $koneksi->query($sql_lapak);
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

    <style type='text/css'>
      .my-legend .legend-title {
        text-align: left;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 100%;
        }
      .my-legend .legend-scale ul {
        margin: 0;
        margin-bottom: 10px;
        padding: 0;
        float: left;
        list-style: none;
        }
      .my-legend .legend-scale ul li {
        font-size: 100%;
        list-style: none;
        margin-left: 0;
        line-height: 18px;
        margin-bottom: 10px;
        }
      .my-legend ul.legend-labels li span {
        display: block;
        float: left;
        height: 20px;
        width: 40px;
        margin-right: 5px;
        margin-left: 0;
        border: 1px solid #999;
        }
      .my-legend .legend-source {
        font-size: 100%;
        color: #999;
        clear: both;
        }
      .my-legend a {
        color: #777;
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
                        <h2 class="no-margin-bottom">Mapping Pasar Ploso</h2>
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
                                            <h3 class="h4">Denah Pasar Ploso</h3>
                                        </div>
                                        
                                        <div class="card-body"> 
                                            <div class="spinner" style="display:none">
                                              <img id="img-spinner" src="../images/Assets/spin.gif" height="10%" width="10%" >
                                            </div>
                                            <div class="table-responsive">  
                                                <div id="map_demo" >
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img style="width:100%;border:0;" id="usa_image" src="../images/Assets/MapPloso3.png" usemap="#usa" >
                                                    </div>
                                                </div>      

                                                <map id="usa_image_map" name="usa">

                                                    <?php 
                                                    $kode = 1;
                                                    $a = 135; $b = 440; $c = 188; $d = 484;
                                                    for ($i=1; $i <= 7; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A1'.$i.'" data-nama="A1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A1'.'\')">';
                                                        $a +=53;
                                                        $c +=53;
                                                        $kode = $kode + 1;
                                                    } 

                                                    $kode = 19;
                                                    $a = 135; $b = 484; $c = 188; $d = 524;
                                                    for ($i=1; $i <= 7; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B1'.$i.'" data-nama="B1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B1'.'\')">';
                                                        $a +=53;
                                                        $c +=53;
                                                        $kode = $kode + 1;
                                                    } 

                                                    $kode = 8;
                                                    $a = 583; $b = 440; $c = 633; $d = 484;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A2'.$i.'" data-nama="A2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A2'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    } 

                                                    $kode = 26;
                                                    $a = 583; $b = 484; $c = 633; $d = 524;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B2'.$i.'" data-nama="B2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B2'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    } 

                                                    echo '<area href="#" id="A27" data-nama="A2 7" data-state="LPK-0000000014" full="Lapak BLOK A2 NOMOR 7" shape="rect" coords="933,440,983,483" onclick="lapakPasar(\''.'LPK-0000000014'.'\', \''.$KodePasar.'\', \''.'7'.'\', \''.'A2'.'\')">';
                                                    echo '<area href="#" id="B27" data-nama="B27" data-state="LPK-0000000032" full="Lapak BLOK B2 NOMOR 7" shape="rect" coords="933,483,983,527" onclick="lapakPasar(\''.'LPK-0000000032'.'\', \''.$KodePasar.'\', \''.'7'.'\', \''.'B2'.'\')">';


                                                    $kode = 15;
                                                    $a = 1038; $b = 440; $c = 1088; $d = 484;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A3'.$i.'" data-nama="A3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A3'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    } 

                                                    $kode = 33;
                                                    $a = 1038; $b = 484; $c = 1088; $d = 524;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B3'.$i.'" data-nama="B3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B3'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    } 

                                                    $kode = 37;
                                                    $a = 155; $b = 540; $c = 205; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C1'.$i.'" data-nama="C1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C1'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 60;
                                                    $a = 230; $b = 540; $c = 290; $d = 565;
                                                    for ($i=16; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C2'.$i.'" data-nama="C2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C2'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 45;
                                                    $a = 290; $b = 540; $c = 350; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C2'.$i.'" data-nama="C2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C2'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 61;
                                                    $a = 380; $b = 540; $c = 450; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C3'.$i.'" data-nama="C3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C3'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 69;
                                                    $a = 450; $b = 540; $c = 505; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B4'.$i.'" data-nama="B4 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B4 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B4'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 77;
                                                    $a = 583; $b = 540; $c = 633; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B5'.$i.'" data-nama="B5 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B5 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B5'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 85;
                                                    $a = 633; $b = 540; $c = 683; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C4'.$i.'" data-nama="C4 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C4 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C4'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 108;
                                                    $a = 733; $b = 540; $c = 783; $d = 565;
                                                    for ($i=16; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C5'.$i.'" data-nama="C5 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C5 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C5'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode - 1;
                                                    }


                                                    $kode = 93;
                                                    $a = 783; $b = 540; $c = 833; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C5'.$i.'" data-nama="C5 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C5 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C5'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 124;
                                                    $a = 883; $b = 540; $c = 933; $d = 565;
                                                    for ($i=16; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C6'.$i.'" data-nama="C6 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C6 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C6'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 109;
                                                    $a = 933; $b = 540; $c = 983; $d = 565;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C6'.$i.'" data-nama="C6 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C6 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C6'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    echo '<area href="#" id="C81" data-nama="C8 1" data-state="LPK-0000000141" full="Lapak BLOK C8 NOMOR 1" shape="rect" coords="1013,540,1058,562" onclick="lapakPasar(\''.'LPK-0000000141'.'\', \''.$KodePasar.'\', \''.'1'.'\', \''.'C8'.'\')">';

                                                    $kode = 155;
                                                    $a = 1013; $b = 562; $c = 1058; $d = 584;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }

                                                    echo '<area href="#" id="C88" data-nama="C8 8" data-state="LPK-0000000148" full="Lapak BLOK C8 NOMOR 8" shape="rect" coords="1013,738,1058,760" onclick="lapakPasar(\''.'LPK-0000000148'.'\', \''.$KodePasar.'\', \''.'8'.'\', \''.'C8'.'\')">';

                                                    echo '<area href="#" id="C82" data-nama="C82" data-state="LPK-0000000142" full="Lapak BLOK C8 NOMOR 2" shape="rect" coords="1058,540,1098,562" onclick="lapakPasar(\''.'LPK-0000000142'.'\', \''.$KodePasar.'\', \''.'2'.'\', \''.'C8'.'\')">';

                                                    $kode = 163;
                                                    $a = 1058; $b = 562; $c = 1098; $d = 584;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D1'.$i.'" data-nama="D1 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D1 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D1'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }

                                                    echo '<area href="#" id="C89" data-nama="C8 9" data-state="LPK-0000000149" full="Lapak BLOK C8 NOMOR 9" shape="rect" coords="1058,738,1098,760" onclick="lapakPasar(\''.'LPK-0000000149'.'\', \''.$KodePasar.'\', \''.'9'.'\', \''.'C8'.'\')">';

                                                    echo '<area href="#" id="C83" data-nama="C8 3" data-state="LPK-0000000143" full="Lapak BLOK C8 NOMOR 3" shape="rect" coords="1128,540,1168,562" onclick="lapakPasar(\''.'LPK-0000000143'.'\', \''.$KodePasar.'\', \''.'3'.'\', \''.'C8'.'\')">';

                                                    $kode = 171;
                                                    $a = 1128; $b = 562; $c = 1168; $d = 584;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D2'.$i.'" data-nama="D2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D2'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="C810" data-nama="C8 10" data-state="LPK-0000000150" full="Lapak BLOK C8 NOMOR 10" shape="rect" coords="1128,738,1168,760" onclick="lapakPasar(\''.'LPK-0000000150'.'\', \''.$KodePasar.'\', \''.'10'.'\', \''.'C8'.'\')">';


                                                    echo '<area href="#" id="C84" data-nama="C8 4" data-state="LPK-0000000144" full="Lapak BLOK C8 NOMOR 4" shape="rect" coords="1168,540,1208,562" onclick="lapakPasar(\''.'LPK-0000000144'.'\', \''.$KodePasar.'\', \''.'4'.'\', \''.'C8'.'\')">';
                                                    $kode = 179;
                                                    $a = 1168; $b = 562; $c = 1208; $d = 584;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D2'.$i.'" data-nama="D2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D2'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="C811" data-nama="C8 11" data-state="LPK-0000000151" full="Lapak BLOK C8 NOMOR 11" shape="rect" coords="1168,738,1208,760" onclick="lapakPasar(\''.'LPK-0000000151'.'\', \''.$KodePasar.'\', \''.'11'.'\', \''.'C8'.'\')">';


                                                    echo '<area href="#" id="C85" data-nama="C8 5" data-state="LPK-0000000145" full="Lapak BLOK C8 NOMOR 5" shape="rect" coords="1238,540,1278,562" onclick="lapakPasar(\''.'LPK-0000000145'.'\', \''.$KodePasar.'\', \''.'5'.'\', \''.'C8'.'\')">';
                                                    $kode = 187;
                                                    $a = 1238; $b = 562; $c = 1278; $d = 584;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D3'.$i.'" data-nama="D3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D3'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="C812" data-nama="C8 12" data-state="LPK-0000000152" full="Lapak BLOK C8 NOMOR 12" shape="rect" coords="1238,738,1278,760" onclick="lapakPasar(\''.'LPK-0000000152'.'\', \''.$KodePasar.'\', \''.'12'.'\', \''.'C8'.'\')">';

                                                    echo '<area href="#" id="C86" data-nama="C8 6" data-state="LPK-0000000146" full="Lapak BLOK C8 NOMOR 6" shape="rect" coords="1278,540,1318,562" onclick="lapakPasar(\''.'LPK-0000000146'.'\', \''.$KodePasar.'\', \''.'6'.'\', \''.'C8'.'\')">';
                                                    $kode = 195;
                                                    $a = 1278; $b = 562; $c = 1318; $d = 584;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D3'.$i.'" data-nama="D3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D3'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="C813" data-nama="C8 13" data-state="LPK-0000000153" full="Lapak BLOK C8 NOMOR 13" shape="rect" coords="1278,738,1318,760" onclick="lapakPasar(\''.'LPK-0000000153'.'\', \''.$KodePasar.'\', \''.'13'.'\', \''.'C8'.'\')">';

                                                    echo '<area href="#" id="C87" data-nama="C8 7" data-state="LPK-0000000147" full="Lapak BLOK C8 NOMOR 7" shape="rect" coords="1348,540,1388,562" onclick="lapakPasar(\''.'LPK-0000000147'.'\', \''.$KodePasar.'\', \''.'7'.'\', \''.'C8'.'\')">';
                                                    $kode = 203;
                                                    $a = 1348; $b = 562; $c = 1388; $d = 584;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D4'.$i.'" data-nama="D4 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D4 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D4'.'\')">';
                                                        $b +=22;
                                                        $d +=22;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="C814" data-nama="C8 14" data-state="LPK-0000000154" full="Lapak BLOK C8 NOMOR 14" shape="rect" coords="1348,738,1388,760" onclick="lapakPasar(\''.'LPK-0000000154'.'\', \''.$KodePasar.'\', \''.'14'.'\', \''.'C8'.'\')">';

                                                    $kode = 125;
                                                    $a = 405; $b = 765; $c = 450; $d = 790;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C7'.$i.'" data-nama="C7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C7'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 133;
                                                    $a = 450; $b = 765; $c = 495; $d = 790;
                                                    for ($i=9; $i <= 14; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C7'.$i.'" data-nama="C7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C7'.'\')">';
                                                        $b +=25;
                                                        $d +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 131;
                                                    $a = 405; $b = 915; $c = 450; $d = 950;
                                                    for ($i=7; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C7'.$i.'" data-nama="C7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C7'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 139;
                                                    $a = 450; $b = 915; $c = 495; $d = 950;
                                                    for ($i=15; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C7'.$i.'" data-nama="C7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C7'.'\')">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 211;
                                                    $a = 583; $b = 765; $c = 633; $d = 790;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D5'.$i.'" data-nama="D5 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D5 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D5'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 232;
                                                    $a = 583; $b = 790; $c = 633; $d = 815;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E2'.$i.'" data-nama="E2 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E2 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E2'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 252;
                                                    $a = 583; $b = 840; $c = 633; $d = 865;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E4'.$i.'" data-nama="E4 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E4 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E4'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 260;
                                                    $a = 583; $b = 865; $c = 633; $d = 890;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E4'.$i.'" data-nama="E4 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E4 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E4'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 219;
                                                    $a = 1013; $b = 775; $c = 1038; $d = 800;
                                                    for ($i=1; $i <= 12; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D6'.$i.'" data-nama="D6 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D6 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D6'.'\')">';
                                                        $a +=25;
                                                        $c +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 240;
                                                    $a = 1013; $b = 800; $c = 1038; $d = 825;
                                                    for ($i=1; $i <= 12; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E3'.$i.'" data-nama="E3 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E3 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E3'.'\')">';
                                                        $a +=25;
                                                        $c +=25;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 269;
                                                    $a = 880; $b = 915; $c = 930; $d = 955;
                                                    for ($i=2; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E7'.$i.'" data-nama="E7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E7'.'\')">';
                                                        $a +=50;
                                                        $c +=50;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 270;
                                                    $a = 880; $b = 955; $c = 930; $d = 987;
                                                    for ($i=3; $i <= 25; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E7'.$i.'" data-nama="E7 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E7 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E7'.'\')">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 293;
                                                    $a = 405; $b = 1015; $c = 450; $d = 1047;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E8'.$i.'" data-nama="E8 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E8 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E8'.'\')">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 301;
                                                    $a = 450; $b = 1015; $c = 495; $d = 1047;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="E8'.$i.'" data-nama="E8 '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK E8 NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'E8'.'\')">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 313;
                                                    $a = 135; $b = 1300; $c = 212; $d = 1365;
                                                    for ($i=5; $i >= 4; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $a +=77;
                                                        $c +=77;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 311;
                                                    $a = 310; $b = 1300; $c = 375; $d = 1365;
                                                    for ($i=3; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 318;
                                                    $a = 135; $b = 1460; $c = 212; $d = 1525;
                                                    for ($i=10; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $a +=77;
                                                        $c +=77;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 316;
                                                    $a = 310; $b = 1460; $c = 375; $d = 1525;
                                                    for ($i=8; $i >= 6; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode - 1;
                                                    }

                                                    //WARUNG
                                                    echo '<area href="#" id="E1" data-nama="E 1" data-state="LPK-0000000231" full="Lapak BLOK E1 NOMOR 1" shape="rect" coords="145,765,205,802" onclick="lapakPasar(\''.'LPK-0000000231'.'\', \''.$KodePasar.'\', \''.'1'.'\', \''.'E1'.'\')">';
                                                    $kode = 319;
                                                    $a = 145; $b = 802; $c = 205; $d = 839;
                                                    for ($i=1; $i <= 5; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="W'.$i.'" data-nama="W '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK W NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'W'.'\')">';
                                                        $b +=37;
                                                        $d +=37;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 324;
                                                    $a = 160; $b = 1040; $c = 205; $d = 1085;
                                                    for ($i=6; $i <= 9; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="W'.$i.'" data-nama="W '.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK W NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'W'.'\')">';
                                                        $b +=45;
                                                        $d +=45;
                                                        $kode = $kode + 1;
                                                    }
                                                    // END WARUNG



                                                    //TANPA BANGUNAN
                                                    $kode = 1;
                                                    $a = 230; $b = 765; $c = 260; $d = 788;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=23;
                                                        $d +=23;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 9;
                                                    $a = 260; $b = 765; $c = 290; $d = 788;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=23;
                                                        $d +=23;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 17;
                                                    $a = 230; $b = 950; $c = 260; $d = 985;
                                                    for ($i=17; $i <= 18; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 27;
                                                    $a = 310; $b = 765; $c = 350; $d = 788;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=23;
                                                        $d +=23;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 36;
                                                    $a = 350; $b = 765; $c = 385; $d = 788;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=23;
                                                        $d +=23;
                                                        $kode = $kode - 1;
                                                    }

                                                    echo '<area href="#" id="LGL55" data-nama="" data-state="LGL-0000000037" full="LAPAK TANPA BAGUNGAN 55" shape="rect" coords="310,950,350,986">';
                                                    echo '<area href="#" id="LGL56" data-nama="" data-state="LGL-0000000038" full="LAPAK TANPA BAGUNGAN 56" shape="rect" coords="350,950,385,986">';

                                                    $kode = 39;
                                                    $a = 1013; $b = 840; $c = 1043; $d = 890;
                                                    for ($i=1; $i <= 11; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 50;
                                                    $a = 1013; $b = 915; $c = 1043; $d = 955;
                                                    for ($i=1; $i <= 11; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=30;
                                                        $c +=30;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 62;
                                                    $a = 230; $b = 1015; $c = 260; $d = 1047;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 70;
                                                    $a = 260; $b = 1015; $c = 290; $d = 1047;
                                                    for ($i=9; $i <= 16; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode + 1;
                                                    }

                                                    
                                                    $kode = 86;
                                                    $a = 310; $b = 1015; $c = 350; $d = 1047;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 94;
                                                    $a = 350; $b = 1015; $c = 385; $d = 1047;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 104;
                                                    $a = 135; $b = 1390; $c = 172; $d = 1430;
                                                    for ($i=10; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=37;
                                                        $c +=37;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 114;
                                                    $a = 135; $b = 1550; $c = 172; $d = 1590;
                                                    for ($i=10; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=37;
                                                        $c +=37;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 124;
                                                    $a = 135; $b = 1590; $c = 172; $d = 1630;
                                                    for ($i=10; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $a +=37;
                                                        $c +=37;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 132;
                                                    $a = 583; $b = 915; $c = 653; $d = 947;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 140;
                                                    $a = 680; $b = 915; $c = 755; $d = 947;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 148;
                                                    $a = 785; $b = 915; $c = 855; $d = 947;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 156;
                                                    $a = 583; $b = 1207; $c = 653; $d = 1239;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 164;
                                                    $a = 680; $b = 1207; $c = 755; $d = 1239;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 172;
                                                    $a = 785; $b = 1207; $c = 855; $d = 1239;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'"  data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=32;
                                                        $d +=32;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 177;
                                                    $a = 583; $b = 1490; $c = 653; $d = 1525;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 182;
                                                    $a = 680; $b = 1490; $c = 755; $d = 1525;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 187;
                                                    $a = 785; $b = 1490; $c = 855; $d = 1525;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="LGL'.$i.'" data-nama="" data-state="LGL-'.$_kode.'" full="LAPAK TANPA BANGUNAN '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';
                                                        $b +=35;
                                                        $d +=35;
                                                        $kode = $kode - 1;
                                                    }
                                                    // END TANPA BANGUNAN

                                                    ?>
                                                </map>
                                            </div>
                                            <div class="col-md-12">
                                              <div class='my-legend'>
                                                <br>
                                                <h3>INFORMASI</h3>
                                                <hr>
                                                <div class='legend-scale'>
                                                  <ul class='legend-labels'>
                                                    <li><span style="background: #e32209;"></span>Lapak Masih Mempunyai Tunggakan Retribusi</li>
                                                    <li><span style="background: #ffab03;"></span>Lapak Tidak Mempunyai Tunggakan Retribusi</li>
                                                    <li><span style="background: #bab9b8;"></span>Lapak Kosong atau Belum Ada Penyewanya</li>
                                                    <li><span style="background: #3677f7;"></span>Lapak Tanpa Bangunan</li>
                                                  </ul>
                                                </div>
                                              </div>
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

                $kode = 1;
                for ($i=1; $i <= 200; $i++) {
                    $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                    // $color = 'fefcfd';
                    $color = '3677f7';
                    echo '{key: "LGL-'.$_kode.'",
                    staticState: true,
                    fillOpacity: 0.7,
                    fillColor: "'.$color.'"}, ';
                    $kode = $kode + 1;
                }

                echo '{key: "Pintu",
                staticState: true,
                fillOpacity: 0,}, ';     

                echo '{key: "KM",
                staticState: true,
                fillOpacity: 0,}, ';

                echo '{key: "X0",
                staticState: true,
                fillOpacity: 0,}, ';
                ?>
                ]
            };
            $statelist = $('#statelist');
            $usamap = $('#usa_image');
            $usamap.mapster(default_options);
        });

        function lapakPasar(kodeLapak, kodepasar) {
            var namaPasar = "Ploso";
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

        // $(function() {
        //     var pasar = "<?=$KodePasar?>";
        //     $('area').each(function(){
        //         var txt=$(this).data('nama');
        //         var idlapak=$(this).data('state');
        //         var coor=$(this).attr('coords');
        //         var coorA=coor.split(',');

        //         var spe = txt.substr(0, 3);
        //         var res = txt.substr(0, 2);
        //         var num = txt.substr(1, 3);
        //         var hpl = txt.substr(3, 5);

        //         if(res == "C8" || res == "D1" || res == "D2" || res == "D3" || res == "D4"){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+72;
        //             var css ='style="font-size:7pt"';
        //         }else if(res == "C7"){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+72;
        //             var css ='style="font-size:7pt"';
        //         }else if(res == "E8" || res == "E4" || res == "D5" || res == "E2"){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+72;
        //             var css ='style="font-size:8pt"';
        //         }else if(res == "D6" || res == "E3"){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+72;
        //             var css ='style="font-size:5pt; writing-mode: vertical-rl"';
        //         }else{
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+74;
        //             var css ='style="font-size:9pt"';
        //         }

        //         var $span=$('<span class="map_title" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" ><b>'+txt+'</b></span>');
        //         $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'black'});
        //         $span.appendTo('#map_demo');
        //     })
        // });


    </script>
</body>
</html>