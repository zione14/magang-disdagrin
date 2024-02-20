<?php
include 'akses.php';
@$fitur_id = 58;
include '../library/lock-menu.php';
// include '../library/cek.php';
$Page = 'MappingPasar';
$SubPage = 'MapBlimbing';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$KodePasar = 'PSR-2019-0000010';
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
$res_lapak = $oke->get_result;
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
                        <h2 class="no-margin-bottom">Mapping Pasar Blimbing</h2>
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
                                            <h3 class="h4">Denah Pasar Blimbing</h3>
                                        </div>
                                        
                                        <div class="card-body"> 
                                            <div class="spinner" style="display:none">
                                              <img id="img-spinner" src="../images/Assets/spin.gif" height="10%" width="10%" >
                                            </div>
                                            <div class="table-responsive">  
                                                <div id="map_demo" >
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img style="width:100%;border:0;" id="usa_image" src="../images/Assets/BlimbingBack2.png" usemap="#usa" >
                                                    </div>
                                                </div>
                                                <map id="usa_image_map" name="usa">
                                                    <area href="#" id="E16" data-nama="E16" data-state="LPK-0000000117" full="Lapak BLOK E NOMOR 16" shape="rect" coords="130,263,249,166"  onclick="lapakPasar('LPK-0000000117', '<?=$KodePasar?>', 16, 'E')">
                                                    <area href="#" id="E15" data-nama="E15" data-state="LPK-0000000116" full="Lapak BLOK E NOMOR 15" shape="rect" coords="130,363,249,266"  onclick="lapakPasar('LPK-0000000116', '<?=$KodePasar?>', 15, 'E')">
                                                    <area href="#" id="E14" data-nama="E14" data-state="LPK-0000000115" full="Lapak BLOK E NOMOR 14" shape="rect" coords="130,463,249,366"  onclick="lapakPasar('LPK-0000000115', '<?=$KodePasar?>', 14, 'E')">
                                                    <area href="#" id="E13" data-nama="E13" data-state="LPK-0000000114" full="Lapak BLOK E NOMOR 13" shape="rect" coords="130,563,249,466"  onclick="lapakPasar('LPK-0000000114', '<?=$KodePasar?>', 13, 'E')">
                                                    
                                                    <area href="#" id="E12" data-nama="E12" data-state="LPK-0000000113" full="Lapak BLOK E NOMOR 12" shape="rect" coords="130,663,249,566"  onclick="lapakPasar('LPK-0000000113', '<?=$KodePasar?>', 12, 'E')">
                                                    <area href="#" id="E11" data-nama="E11" data-state="LPK-0000000112" full="Lapak BLOK E NOMOR 11" shape="rect" coords="130,763,249,666"  onclick="lapakPasar('LPK-0000000112', '<?=$KodePasar?>', 11, 'E')">
                                                    <area href="#" id="PintuBarat" data-nama="" data-state="Pintu" full="PINTU BARAT" shape="rect" coords="130,863,249,766">
                                                    <area href="#" id="E10" data-nama="E10" data-state="LPK-0000000111" full="Lapak BLOK E NOMOR 10" shape="rect" coords="130,963,249,866"  onclick="lapakPasar('LPK-0000000111', '<?=$KodePasar?>', 10, 'E')">
                                                    <area href="#" id="KamarMandi" data-nama="" data-state="KM" full="KAMAR MANDI" shape="rect" coords="130,1063,249,966">
                                                    <area href="#" id="E9" data-nama="E9" data-state="LPK-0000000110" full="Lapak BLOK E NOMOR 9" shape="rect" coords="130,1163,249,1066"  onclick="lapakPasar('LPK-0000000110', '<?=$KodePasar?>', 9, 'E')">
                                                    <area href="#" id="E8" data-nama="E8" data-state="LPK-0000000109" full="Lapak BLOK E NOMOR 8" shape="rect" coords="130,1263,249,1166"  onclick="lapakPasar('LPK-0000000109', '<?=$KodePasar?>', 8, 'E')">
                                                    <area href="#" id="E7" data-nama="E7" data-state="LPK-0000000108" full="Lapak BLOK E NOMOR 7" shape="rect" coords="130,1363,249,1266"  onclick="lapakPasar('LPK-0000000108', '<?=$KodePasar?>', 7, 'E')">
                                                    <area href="#" id="E6" data-nama="E6" data-state="LPK-0000000107" full="Lapak BLOK E NOMOR 6" shape="rect" coords="130,1463,249,1366"  onclick="lapakPasar('LPK-0000000107', '<?=$KodePasar?>', 6, 'E')">
                                                    <area href="#" id="E5" data-nama="E5" data-state="LPK-0000000106" full="Lapak BLOK E NOMOR 5" shape="rect" coords="130,1563,249,1466"  onclick="lapakPasar('LPK-0000000106', '<?=$KodePasar?>', 5, 'E')">
                                                    <area href="#" id="E4" data-nama="E4" data-state="LPK-0000000105" full="Lapak BLOK E NOMOR 4" shape="rect" coords="130,1663,249,1566"  onclick="lapakPasar('LPK-0000000105', '<?=$KodePasar?>', 4, 'E')">
                                                    <area href="#" id="E3" data-nama="E3" data-state="LPK-0000000104" full="Lapak BLOK E NOMOR 3" shape="rect" coords="130,1763,249,1666"  onclick="lapakPasar('LPK-0000000104', '<?=$KodePasar?>', 3, 'E')">
                                                    <area href="#" id="E2" data-nama="E2" data-state="LPK-0000000103" full="Lapak BLOK E NOMOR 2" shape="rect" coords="130,1863,249,1766"  onclick="lapakPasar('LPK-0000000103', '<?=$KodePasar?>', 2, 'E')">
                                                    <area href="#" id="E1" data-nama="E1" data-state="LPK-0000000102" full="Lapak BLOK E NOMOR 1" shape="rect" coords="130,1963,249,1866"  onclick="lapakPasar('LPK-0000000102', '<?=$KodePasar?>', 1, 'E')">
                                                    <!-- ATAS -->
                                                    <area href="#" id="PU1" data-nama="" data-state="Pintu" full="Pintu Utara" shape="rect" coords="250,280,402,166">
                                                    <?php 
                                                    $kode = 101;
                                                    $a = 398; $b = 280; $c = 519; $d = 166;
                                                    for ($i=13; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="D'.$i.'" data-nama="D'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK D NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'"  onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'D'.'\')">';
                                                        $a +=124;
                                                        $c +=124;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="TS" data-nama="" data-state="KM" full="Tempat Sampah" shape="rect" coords="2010,280,2298,166">
                                                    <!-- Vertical Kanan -->
                                                    <?php 
                                                    $kode = 59;
                                                    $a = 2166; $b = 482; $c = 2292; $d = 373;
                                                    for ($i=5; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $b +=111;
                                                        $d +=111;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 63;
                                                    $a = 2166; $b = 964; $c = 2292; $d = 891;
                                                    for ($i=9; $i <= 20; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $b +=75;
                                                        $d +=75;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 55;
                                                    $a = 2296; $b = 1789; $c = 2423; $d = 1702;
                                                    for ($i=1; $i <= 4; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $b -=87;
                                                        $d -=87;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 82;
                                                    $a = 2038; $b = 890; $c = 2160; $d = 957;
                                                    for ($i=28; $i >= 21; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $b +=66;
                                                        $d +=66;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 88;
                                                    $a = 2042; $b = 372; $c = 2164; $d = 444;
                                                    for ($i=34; $i >= 29; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="C'.$i.'" data-nama="C'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK C NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'C'.'\')">';
                                                        $b +=72;
                                                        $d +=73;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 19;
                                                    $a = 130; $b = 2217; $c = 258; $d = 2331;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A'.$i.'" data-nama="A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A'.'\')">';
                                                        $a +=130;
                                                        $c +=130;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="A19" data-nama="A19" data-state="LPK-0000000037" full="Lapak BLOK A NOMOR 19" shape="rect" coords="130,2101,258,2215" onclick="lapakPasar(\''.'LPK-0000000037'.'\', \''.$KodePasar.'\', \''.'19'.'\', \''.'A'.'\')">';
                                                    echo '<area href="#" id="A18" data-nama="A18" data-state="LPK-0000000036" full="Lapak BLOK A NOMOR 18" shape="rect" coords="260,2101,388,2215" onclick="lapakPasar(\''.'LPK-0000000036'.'\', \''.$KodePasar.'\', \''.'18'.'\', \''.'A'.'\')">';
                                                    ?>
                                                    <?php 
                                                    $kode = 35;
                                                    $a = 390; $b = 2101; $c = 477; $d = 2215;
                                                    for ($i=17; $i >= 9; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="A'.$i.'" data-nama="A'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK A NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'A'.'\')">';
                                                        $a +=86;
                                                        $c +=86;
                                                        $kode = $kode - 1;
                                                    }
                                                    ?>
                                                    <?php 
                                                    $kode = 38;
                                                    $a = 2293; $b = 2217; $c = 2424; $d = 2331;
                                                    for ($i=1; $i <= 7; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B'.$i.'" data-nama="B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B'.'\')">';
                                                        $a -=130;
                                                        $c -=130;
                                                        $kode = $kode + 1;
                                                    }
                                                    echo '<area href="#" id="B17" data-nama="B17" data-state="LPK-0000000054" full="Lapak BLOK B NOMOR 17" shape="rect" coords="2293,2101,2423,2215" onclick="lapakPasar(\''.'LPK-0000000054'.'\', \''.$KodePasar.'\', \''.'17'.'\', \''.'B'.'\')">';
                                                    
                                                    $kode = 53;
                                                    $a = 2210; $b = 2101; $c = 2296; $d = 2215;
                                                    for ($i=16; $i >= 8; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="B'.$i.'" data-nama="B'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK B NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'B'.'\')">';
                                                        $a -=87;
                                                        $c -=87;
                                                        $kode = $kode - 1;
                                                    }
                                                    echo '<area href="#" id="KANTOR" data-nama="" data-state="KM" full="KANTOR" shape="rect" coords="1383,2101,1513,2331">';
                                                    ?>
                                                    <?php 
                                                    $kode = 296;
                                                    $a = 335; $b = 367; $c = 400; $d = 439;
                                                    for ($i=12; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="S'.$i.'" data-nama="S'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK S NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'S'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 273;
                                                    $a = 335; $b = 486; $c = 400; $d = 558;
                                                    for ($i=13; $i <= 24; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="R'.$i.'" data-nama="R'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK R NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'R'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <?php 
                                                    $kode = 272;
                                                    $a = 335; $b = 558; $c = 400; $d = 630;
                                                    for ($i=12; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="R'.$i.'" data-nama="R'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK R NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'R'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 249;
                                                    $a = 335; $b = 668; $c = 400; $d = 740;
                                                    for ($i=13; $i <= 24; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="Q'.$i.'" data-nama="Q'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK Q NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'Q'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    <?php 
                                                    $kode = 250;
                                                    $a = 335; $b = 740; $c = 400; $d = 812;
                                                    for ($i=12; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="Q'.$i.'" data-nama="Q'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK Q NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'Q'.'\')">';
                                                        $a +=65;
                                                        $c +=65;
                                                        $kode = $kode - 1;
                                                    } ?>
                                                    <area href="#" id="P4" data-nama="P4" data-state="LPK-0000000232" full="Lapak BLOK P NOMOR 4" shape="rect" coords="335,878,525,950" onclick="lapakPasar('LPK-0000000232', '<?=$KodePasar?>', 4, 'P')">
                                                    <area href="#" id="P5" data-nama="P5" data-state="LPK-0000000233" full="Lapak BLOK P NOMOR 5" shape="rect" coords="525,878,663,950" onclick="lapakPasar('LPK-0000000233', '<?=$KodePasar?>', 5, 'P')">
                                                    <area href="#" id="P6" data-nama="P6" data-state="LPK-0000000234" full="Lapak BLOK P NOMOR 6" shape="rect" coords="663,878,801,950" onclick="lapakPasar('LPK-0000000234', '<?=$KodePasar?>', 6, 'P')">
                                                    <area href="#" id="P7" data-nama="P7" data-state="LPK-0000000235" full="Lapak BLOK P NOMOR 7" shape="rect" coords="801,878,939,950" onclick="lapakPasar('LPK-0000000235', '<?=$KodePasar?>', 7, 'P')">
                                                    <area href="#" id="P8" data-nama="P8" data-state="LPK-0000000236" full="Lapak BLOK P NOMOR 8" shape="rect" coords="939,878,1112,950" onclick="lapakPasar('LPK-0000000236', '<?=$KodePasar?>', 8, 'P')">
                                                    <area href="#" id="P3" data-nama="P3" data-state="LPK-0000000231" full="Lapak BLOK P NOMOR 3" shape="rect" coords="335,950,490,1022" onclick="lapakPasar('LPK-0000000231', '<?=$KodePasar?>', 3, 'P')">
                                                    <area href="#" id="P2" data-nama="P2" data-state="LPK-0000000230" full="Lapak BLOK P NOMOR 2" shape="rect" coords="490,950,853,1022" onclick="lapakPasar('LPK-0000000230', '<?=$KodePasar?>', 2, 'P')">
                                                    <area href="#" id="P1" data-nama="P1" data-state="LPK-0000000229" full="Lapak BLOK P NOMOR 1" shape="rect" coords="853,950,1112,1022" onclick="lapakPasar('LPK-0000000229', '<?=$KodePasar?>', 1, 'P')">

                                                    <area href="#" id="O4" data-nama="O4" data-state="LPK-0000000225" full="Lapak BLOK O NOMOR 4" shape="rect" coords="335,1064,567,1136" onclick="lapakPasar('LPK-0000000225', '<?=$KodePasar?>', 4, 'O')">
                                                    <area href="#" id="O5" data-nama="O5" data-state="LPK-0000000226" full="Lapak BLOK O NOMOR 5" shape="rect" coords="567,1064,684,1136" onclick="lapakPasar('LPK-0000000226', '<?=$KodePasar?>', 5, 'O')">
                                                    <area href="#" id="O6" data-nama="O6" data-state="LPK-0000000227" full="Lapak BLOK O NOMOR 6" shape="rect" coords="684,1064,875,1136" onclick="lapakPasar('LPK-0000000227', '<?=$KodePasar?>', 6, 'O')">
                                                    <area href="#" id="O7" data-nama="O7" data-state="LPK-0000000228" full="Lapak BLOK O NOMOR 7" shape="rect" coords="875,1064,1112,1136" onclick="lapakPasar('LPK-0000000228', '<?=$KodePasar?>', 7, 'O')">

                                                    <area href="#" id="O3" data-nama="O3" data-state="LPK-0000000224" full="Lapak BLOK O NOMOR 3" shape="rect" coords="335,1136,567,1208" onclick="lapakPasar('LPK-0000000224', '<?=$KodePasar?>', 3, 'O')">
                                                    <area href="#" id="O2" data-nama="O2" data-state="LPK-0000000223" full="Lapak BLOK O NOMOR 2" shape="rect" coords="567,1136,799,1208" onclick="lapakPasar('LPK-0000000223', '<?=$KodePasar?>', 2, 'O')">
                                                    <area href="#" id="O1" data-nama="O1" data-state="LPK-0000000222" full="Lapak BLOK O NOMOR 1" shape="rect" coords="799,1136,1112,1208" onclick="lapakPasar('LPK-0000000222', '<?=$KodePasar?>', 1, 'O')">

                                                    <area href="#" id="N5" data-nama="N5" data-state="LPK-0000000217" full="Lapak BLOK N NOMOR 5" shape="rect" coords="335,1251,524,1323" onclick="lapakPasar('LPK-0000000217', '<?=$KodePasar?>', 5, 'N')">
                                                    <area href="#" id="N6" data-nama="N6" data-state="LPK-0000000218" full="Lapak BLOK N NOMOR 6" shape="rect" coords="524,1251,639,1323" onclick="lapakPasar('LPK-0000000218', '<?=$KodePasar?>', 6, 'N')">
                                                    <area href="#" id="N7" data-nama="N7" data-state="LPK-0000000219" full="Lapak BLOK N NOMOR 7" shape="rect" coords="639,1251,754,1323" onclick="lapakPasar('LPK-0000000219', '<?=$KodePasar?>', 7, 'N')">
                                                    <area href="#" id="N8" data-nama="N8" data-state="LPK-0000000220" full="Lapak BLOK N NOMOR 8" shape="rect" coords="754,1251,869,1323" onclick="lapakPasar('LPK-0000000220', '<?=$KodePasar?>', 8, 'N')">
                                                    <area href="#" id="N9" data-nama="N9" data-state="LPK-0000000221" full="Lapak BLOK N NOMOR 9" shape="rect" coords="869,1251,1112,1323" onclick="lapakPasar('LPK-0000000221', '<?=$KodePasar?>', 9, 'N')">

                                                    <area href="#" id="N4" data-nama="N4" data-state="LPK-0000000216" full="Lapak BLOK N NOMOR 4" shape="rect" coords="335,1323,524,1395" onclick="lapakPasar('LPK-0000000216', '<?=$KodePasar?>', 4, 'N')">
                                                    <area href="#" id="N3" data-nama="N3" data-state="LPK-0000000215" full="Lapak BLOK N NOMOR 3" shape="rect" coords="524,1323,713,1395" onclick="lapakPasar('LPK-0000000215', '<?=$KodePasar?>', 3, 'N')">
                                                    <area href="#" id="N2" data-nama="N2" data-state="LPK-0000000214" full="Lapak BLOK N NOMOR 2" shape="rect" coords="713,1323,902,1395" onclick="lapakPasar('LPK-0000000214', '<?=$KodePasar?>', 2, 'N')">
                                                    <area href="#" id="N1" data-nama="N1" data-state="LPK-0000000213" full="Lapak BLOK N NOMOR 1" shape="rect" coords="902,1323,1112,1395" onclick="lapakPasar('LPK-0000000213', '<?=$KodePasar?>', 1, 'N')">
                                                    <?php 
                                                    $kode = 122;
                                                    $a = 335; $b = 1470; $c = 436; $d = 1568;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F'.$i.'" data-nama="F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode - 1;
                                                    }
                                                    $kode = 123;
                                                    $a = 436; $b = 1470; $c = 537; $d = 1568;
                                                    for ($i=6; $i <= 10; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="F'.$i.'" data-nama="F'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK F NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'F'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 132;
                                                    $a = 625; $b = 1470; $c = 726; $d = 1568;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode - 1;
                                                    }
                                                    $kode = 133;
                                                    $a = 726; $b = 1470; $c = 827; $d = 1568;
                                                    for ($i=6; $i <= 10; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="G'.$i.'" data-nama="G'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK G NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'G'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php 
                                                    $kode = 142;
                                                    $a = 910; $b = 1470; $c = 1011; $d = 1568;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode - 1;
                                                    }
                                                    $kode = 143;                                            
                                                    $a = 1011; $b = 1470; $c = 1112; $d = 1568;
                                                    for ($i=6; $i <= 10; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="H'.$i.'" data-nama="H'.$i.'" data-state="LPK-'.$_kode.'" full="Lapak BLOK H NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'H'.'\')">';
                                                        $b +=98;
                                                        $d +=98;
                                                        $kode = $kode + 1;
                                                    } ?>
                                                    
                                                    <?php
                                                    $kode = 212;
                                                    $a = 1431; $b = 367; $c = 1510; $d = 423;
                                                    for ($i=8; $i >= 1; $i--) {
                                                        if($i>5){
                                                            $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="M'.($i-2).'" data-nama="M'.($i-2).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK M NOMOR '.($i-2).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i-2).'\', \''.'M'.'\')">';
                                                        }else{
                                                            $_kode = str_pad(($kode + 105), 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="T'.($i+13).'" data-nama="T'.($i+13).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK T NOMOR '.($i+13).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i+13).'\', \''.'T'.'\')">';
                                                        }
                                                        
                                                        $b +=56;
                                                        $d +=56;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 207;
                                                    $a = 1510; $b = 367; $c = 1589; $d = 423;
                                                    for ($i=1; $i <= 8; $i++) {
                                                        if($i<4){
                                                            $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="M'.($i).'" data-nama="M'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK M NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'M'.'\')">';
                                                        }else{
                                                            $_kode = str_pad(($kode + 105), 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="T'.($i+15).'" data-nama="T'.($i+15).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK T NOMOR '.($i+15).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i+15).'\', \''.'T'.'\')">';
                                                        }
                                                        
                                                        $b +=56;
                                                        $d +=56;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 206;
                                                    $a = 1646; $b = 367; $c = 1715; $d = 441;
                                                    for ($i=27; $i >= 22; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="L'.($i).'" data-nama="L'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK L NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'L'.'\')">';
                                                        $b +=74;
                                                        $d +=74;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 180;
                                                    $a = 1715; $b = 367; $c = 1784; $d = 441;
                                                    for ($i=1; $i <= 6; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="L'.($i).'" data-nama="L'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK L NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'L'.'\')">';
                                                        $b +=74;
                                                        $d +=74;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 179;
                                                    $a = 1851; $b = 367; $c = 1920; $d = 441;
                                                    for ($i=6; $i >= 1; $i--) {
                                                        if($i>3){
                                                            $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="K'.($i+1).'" data-nama="K'.($i+1).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK K NOMOR '.($i+1).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i+1).'\', \''.'K'.'\')">';
                                                        }else{
                                                            $_kode = str_pad(($kode + 159), 10, "0", STR_PAD_LEFT);
                                                            echo '<area href="#" id="T'.($i+36).'" data-nama="T'.($i+36).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK T NOMOR '.($i+36).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i+36).'\', \''.'T'.'\')">';
                                                        }
                                                        $b +=74;
                                                        $d +=74;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 309;
                                                    $a = 1431; $b = 883; $c = 1510; $d = 923;
                                                    for ($i=13; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="T'.($i).'" data-nama="T'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK T NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'T'.'\')">';      
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode - 1;
                                                    }
                                                    $kode = 320;
                                                    $a = 1510; $b = 883; $c = 1589; $d = 923;
                                                    for ($i=24; $i <= 36; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="T'.($i).'" data-nama="T'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK T NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'T'.'\')">';      
                                                        $b +=40;
                                                        $d +=40;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 200;
                                                    $a = 1646; $b = 883; $c = 1715; $d = 949;
                                                    for ($i=21; $i >= 14; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="L'.($i).'" data-nama="L'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK L NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'L'.'\')">';
                                                        $b +=65;
                                                        $d +=65;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 186;
                                                    $a = 1715; $b = 883; $c = 1784; $d = 949;
                                                    for ($i=7; $i <= 13; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        if($i==12){
                                                            echo '<area href="#" data-nama="L90" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'">';    
                                                            $b +=65;
                                                            $d +=65;
                                                        }
                                                        echo '<area href="#" id="L'.($i).'" data-nama="L'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK L NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'L'.'\')">';
                                                        $b +=65;
                                                        $d +=65;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 176;
                                                    $a = 1851; $b = 883; $c = 1920; $d = 1015;
                                                    for ($i=4; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="K'.($i).'" data-nama="K'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK K NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'K'.'\')">';
                                                        $b +=131;
                                                        $d +=131;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 350;
                                                    $a = 1431; $b = 1470; $c = 1510; $d = 1503;
                                                    for ($i=15; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="U'.($i).'" data-nama="U'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK U NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'U'.'\')">';      
                                                        $b +=33;
                                                        $d +=33;
                                                        $kode = $kode - 1;
                                                    }
                                                    $kode = 365;
                                                    $a = 1510; $b = 1470; $c = 1589; $d = 1503;
                                                    for ($i=30; $i >= 16; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="U'.($i).'" data-nama="U'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK U NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'U'.'\')">'; 
                                                        $b +=33;
                                                        $d +=33;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 366;
                                                    $a = 1646; $b = 1481; $c = 1715; $d = 1565;
                                                    for ($i=31; $i <= 32; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="U'.($i).'" data-nama="U'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK U NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'U'.'\')">';
                                                        $b +=84;
                                                        $d +=84;
                                                        $kode = $kode + 1;
                                                    }

                                                    $kode = 368;
                                                    $a = 1715; $b = 1481; $c = 1784; $d = 1565;
                                                    for ($i=33; $i <= 34; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="U'.($i).'" data-nama="U'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK U NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'U'.'\')">';
                                                        $b +=84;
                                                        $d +=84;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php
                                                    $kode = 152;
                                                    $a = 1646; $b = 1649; $c = 1715; $d = 1692;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);  
                                                        if($i==1){
                                                            echo '<area href="#" id="I'.($i).'" data-nama="I'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.',1961" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'I'.'\')">';
                                                        }else{
                                                            echo '<area href="#" id="I'.($i).'" data-nama="I'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'I'.'\')">';
                                                        }
                                                        $b +=43;
                                                        $d +=43;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 153;
                                                    $a = 1715; $b = 1649; $c = 1784; $d = 1692;
                                                    for ($i=6; $i <= 10; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);  
                                                        if($i==10){
                                                            echo '<area href="#" id="I'.($i).'" data-nama="I'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.',1961" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'I'.'\')">';
                                                        }else{
                                                            echo '<area href="#" id="I'.($i).'" data-nama="I'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK I NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'I'.'\')">';
                                                        }
                                                        $b +=43;
                                                        $d +=43;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <?php                                                   
                                                    echo '<area href="#" id="J7" data-nama="J7" data-state="LPK-0000000164" full="Lapak BLOK J NOMOR 7" shape="rect" coords="1872,1481,1941,1557" onclick="lapakPasar(\''.'LPK-0000000164'.'\', \''.$KodePasar.'\', \''.'7'.'\', \''.'J'.'\')">';
                                                    echo '<area href="#" id="J6" data-nama="J6" data-state="LPK-0000000163" full="Lapak BLOK J NOMOR 6" shape="rect" coords="1872,1557,1941,1664" onclick="lapakPasar(\''.'LPK-0000000163'.'\', \''.$KodePasar.'\', \''.'6'.'\', \''.'J'.'\')">';
                                                    $kode = 162;
                                                    $a = 1872; $b = 1664; $c = 1941; $d = 1725;
                                                    for ($i=5; $i >= 1; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J'.($i).'" data-nama="J'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'J'.'\')">';
                                                        $b +=61;
                                                        $d +=61;
                                                        $kode = $kode - 1;
                                                    }

                                                    $kode = 165;
                                                    $a = 1941; $b = 1481; $c = 2010; $d = 1542;
                                                    for ($i=8; $i <= 15; $i++) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="J'.($i).'" data-nama="J'.($i).'" data-state="LPK-'.$_kode.'" full="Lapak BLOK J NOMOR '.($i).'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.($i).'\', \''.'J'.'\')">';
                                                        $b +=61;
                                                        $d +=61;
                                                        $kode = $kode + 1;
                                                    } ?>

                                                    <area href="#" data-nama="" data-state="X0" full="HALAMAN PARKIR DEPAN" shape="rect" coords="130,2500,1050,2375">

                                                    <area href="#" id="HPL9" data-nama="HPL9" data-state="LPK-0000000009" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 9" shape="rect" coords="1050,2500,1166,2375"  onclick="lapakPasar('LPK-0000000009', '<?=$KodePasar?>', 9, 'HPL')">

                                                    <!-- <area href="#" id="X18" data-nama="HPL18" data-state="LPK-0000000018" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 18" shape="rect" coords="130,2500,258,2630"  onclick="lapakPasar('LPK-0000000018', '<?=$KodePasar?>', 9, 'X')"> -->
                                                    <?php 
                                                    $kode = 18;
                                                    $a = 130; $b = 2500; $c = 245; $d = 2615;
                                                    for ($i=18; $i >= 10; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="HPL'.$i.'" data-nama="HPL'.$i.'" data-state="LPK-'.$_kode.'" full="LAPAK BLOK  HALAMAN PARKIR DEPAN NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'X'.'\')">';
                                                        $a +=115;
                                                        $c +=115;
                                                        $kode = $kode - 1;
                                                    } ?>

                                                    <area href="#" data-nama="" data-state="X0" full="KANTOR" shape="rect" coords="1383,2500,2313,2375">
                                                    <area href="#" id="HPL1" data-nama="HPL1" data-state="LPK-0000000001" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 1" shape="rect" coords="2313,2500,2420,2375"  onclick="lapakPasar('LPK-0000000001', '<?=$KodePasar?>', 1, 'HPL')">


                                                    <area href="#" id="HPL8" data-nama="HPL8" data-state="LPK-0000000008" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 8" shape="rect" coords="1383,2500,1533,2615"  onclick="lapakPasar('LPK-0000000008', '<?=$KodePasar?>', 8, 'HPL')">
                                                    <area href="#" id="HPL7" data-nama="HPL7" data-state="LPK-0000000007" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 7" shape="rect" coords="1533,2500,1683,2615"  onclick="lapakPasar('LPK-0000000007', '<?=$KodePasar?>', 7, 'HPL')">
                                                    <area href="#" id="HPL6" data-nama="HPL6" data-state="LPK-0000000006" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 6" shape="rect" coords="1683,2500,1833,2615"  onclick="lapakPasar('LPK-0000000006', '<?=$KodePasar?>', 6, 'HPL')">
                                                    <area href="#" id="HPL5" data-nama="HPL5" data-state="LPK-0000000005" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 5" shape="rect" coords="1833,2500,1983,2615"  onclick="lapakPasar('LPK-0000000005', '<?=$KodePasar?>', 5, 'HPL')">
                                                    <area href="#" id="HPL4" data-nama="HPL4" data-state="LPK-0000000004" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 4" shape="rect" coords="1983,2500,2133,2615"  onclick="lapakPasar('LPK-0000000004', '<?=$KodePasar?>', 4, 'HPL')">
                                                    <area href="#" id="HPL3" data-nama="HPL3" data-state="LPK-0000000003" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 3" shape="rect" coords="2133,2500,2283,2615"  onclick="lapakPasar('LPK-0000000006', '<?=$KodePasar?>', 3, 'HPL')">
                                                    <area href="#" id="HPL2" data-nama="HPL2" data-state="LPK-0000000002" full="LAPAK HALAMAN PARKIR DEPAN NOMOR 2" shape="rect" coords="2283,2500,2420,2615"  onclick="lapakPasar('LPK-0000000002', '<?=$KodePasar?>', 2, 'HPL')">

                                                    <!-- <?php 
                                                    $kode = 8;
                                                    $a = 1383; $b = 2500; $c = 1498; $d = 2615;
                                                    for ($i=8; $i >= 2; $i--) {
                                                        $_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
                                                        echo '<area href="#" id="HPL'.$i.'" data-nama="HPL'.$i.'" data-state="LPK-'.$_kode.'" full="LAPAK BLOK  HALAMAN PARKIR DEPAN NOMOR '.$i.'" shape="rect" coords="'.$a.','.$b.','.$c.','.$d.'" onclick="lapakPasar(\''.'LPK-'.$_kode.'\', \''.$KodePasar.'\', \''.$i.'\', \''.'X'.'\')">';
                                                        $a +=115;
                                                        $c +=115;
                                                        $kode = $kode - 1;
                                                    } 

                                                    ?> -->
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

        // //Label Nama
        // $(function() {
        //     var pasar = "<?=$KodePasar?>";
        //     $('area').each(function(){
        //         var txt=$(this).data('nama');
        //         var idlapak=$(this).data('state');
        //         var coor=$(this).attr('coords');
        //         var coorA=coor.split(',');

        //         var spe = txt.substr(0, 3);
        //         var res = txt.substr(0, 1);
        //         var num = txt.substr(1, 3);
        //         var hpl = txt.substr(3, 5);

        //         if(res == "E" || res == "D"){
        //             var left=parseFloat(coorA[0])+28;
        //             var top=parseFloat(coorA[1])+43;
        //             var css ='style="font-size:11pt"';
        //         } else if (spe == "HPL") {
        //             if(hpl == 9 || hpl == 1){
        //                 var left=parseFloat(coorA[0])+25;
        //                 var top=parseFloat(coorA[1])+37;
        //                 var css ='style="font-size:11pt"';
        //             } else {
        //                 var left=parseFloat(coorA[0])+25;
        //                 var top=parseFloat(coorA[1])+75;
        //                 var css ='style="font-size:9pt"';
        //             }   
        //         } else if (res == "S" || res == "R" || res == "Q" || res == "P" || res == "O" || res == "N" || res == "F" || res == "G" || res == "H" || res == "A" || res == "B" || res == "K" || res == "M" || res == "T" || res == "L" || res == "J") {
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+70;
        //             var css ='style="font-size:9pt"';
        //         } else if (res == "I") {
        //             var left=parseFloat(coorA[0])+25;
        //             var top=parseFloat(coorA[1])+72;
        //             var css ='style="font-size:9pt"';
        //          } else if (res == "U") {
        //             var left=parseFloat(coorA[0])+24;
        //             var top=parseFloat(coorA[1])+70;
        //             var css ='style="font-size:8pt"';
        //         } else if (res == "C") {
        //             if(num <= 20){
        //                 var left=parseFloat(coorA[0])+28;
        //                 var top=parseFloat(coorA[1])+43;
        //                 var css ='style="font-size:9pt"';
        //             } else {
        //                 var left=parseFloat(coorA[0])+28;
        //                 var top=parseFloat(coorA[1])+75;
        //                 var css ='style="font-size:9pt"';
        //             }   
        //         }
                

        //         var $span=$('<span class="map_title" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" ><b>'+txt+'<b></span>');
        //         $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'white'});
        //         $span.appendTo('#map_demo');
        //     })
        // });
    </script>
</body>
</html>