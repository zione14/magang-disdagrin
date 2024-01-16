<?php
include 'akses.php';
@$fitur_id = 58;
include '../library/lock-menu.php';
$Page = 'MappingPasar';
$SubPage = 'MapCitraNiaga';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$KodePasar = 'PSR-2019-0000004';
?>

<?php
$sql_lapak = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet
FROM lapakpasar l 
LEFT JOIN (
SELECT t.NoTransRet, t.TanggalTrans, t.KodePasar, t.IDLapak
FROM trretribusipasar t

WHERE DATE_FORMAT(t.TanggalTrans, '%Y-%m-%d') = CURDATE() 
) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
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
                        <h2 class="no-margin-bottom">Mapping Pasar Citra Niaga</h2>
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
                                            <h3 class="h4">Denah Pasar Citra Niaga</h3>
                                        </div>
                                        <div class="card-body"> 
                                            <div class="table-responsive">  
                                                <div id="map_demo" >
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img style="width:100%;border:0;" id="usa_image" src="../images/Assets/MapCitraNiaga1.png" usemap="#usa" >
                                                    </div>
                                                </div>      
                                                <!-- <area href="#" id="E161" data-nama="E16" state="LPK-0000000117" full="Lapak BLOK E NOMOR 16" shape="rect" coords="0,0,798,1127"  onclick="lapakPasar('LPK-0000000117', '<?=$KodePasar?>', 16, 'E')"> -->

                                                    <!-- <area href="#" id="B683" data-nama="B683" state="Pintu" full="Lapak BLOK B NOMOR 683" shape="poly" coords="118,72,393,183,342,408,375,419,369,473,342,472,339,520,346,523,338,593,696,674,658,840,649,854,628,954,528,924,510,917,500,917,422,911,342,890,258,855,232,850,243,800,70,770"> -->

                                                <map id="usa_image_map" name="usa">

                                                    <!-- <area href="#" id="B684" data-nama="B684" state="LPK-0000000047" full="Lapak BLOK B NOMOR 684" shape="poly" coords="655,665,680,673,676,695,668,693,666,702,648,698"  onclick="lapakPasar('LPK-0000000047', '<?=$KodePasar?>', 'B', '684')"> -->


                                                    <!--Blok B 890 -->
                                                    <area href="#" id="B890" data-nama="B890" state="LPK-0000000092" full="Lapak BLOK B NOMOR 890" shape="poly" coords="296,586,323,590,319,620,309,618,295,603 "  onclick="lapakPasar('LPK-0000000092', '<?=$KodePasar?>', 'B', '890')">


                                                    <!--Blok B 684 sampai 707 -->
                                                    <area href="#" id="B684" data-nama="B684" state="LPK-0000000047" full="Lapak BLOK B NOMOR 684" shape="poly" coords="655,665,680,670,676,695,668,693,666,702,648,698"  onclick="lapakPasar('LPK-0000000047', '<?=$KodePasar?>', 'B', '684')">
                                                    <area href="#" id="B685" data-nama="B685" state="LPK-0000000048" full="Lapak BLOK B NOMOR 685" shape="poly" coords="638,662,654,665,648,698,632,694"  onclick="lapakPasar('LPK-0000000048', '<?=$KodePasar?>', 'B', '685')">
                                                    <area href="#" id="B686" data-nama="B686" state="LPK-0000000049" full="Lapak BLOK B NOMOR 686" shape="poly" coords="622,658,638,662,632,694,616,690"  onclick="lapakPasar('LPK-0000000049', '<?=$KodePasar?>', 'B', '686')">
                                                    <area href="#" id="B687" data-nama="B687" state="LPK-0000000050" full="Lapak BLOK B NOMOR 687" shape="poly" coords="606,654,622,658,616,690,600,686"  onclick="lapakPasar('LPK-0000000050', '<?=$KodePasar?>', 'B', '687')">
                                                    <area href="#" id="B688" data-nama="B688" state="LPK-0000000051" full="Lapak BLOK B NOMOR 688" shape="poly" coords="590,650,606,654,600,686,584,682"  onclick="lapakPasar('LPK-0000000051', '<?=$KodePasar?>', 'B', '688')">
                                                    <area href="#" id="B689" data-nama="B689" state="LPK-0000000052" full="Lapak BLOK B NOMOR 689" shape="poly" coords="574,646,590,650,584,682,568,678"  onclick="lapakPasar('LPK-0000000052', '<?=$KodePasar?>', 'B', '689')">
                                                    <area href="#" id="B690" data-nama="B690" state="LPK-0000000053" full="Lapak BLOK B NOMOR 690" shape="poly" coords="558,642,574,646,568,678,552,674"  onclick="lapakPasar('LPK-0000000053', '<?=$KodePasar?>', 'B', '690')">
                                                    <area href="#" id="B691" data-nama="B691" state="LPK-0000000054" full="Lapak BLOK B NOMOR 691" shape="poly" coords="542,638,558,642,552,674,536,670"  onclick="lapakPasar('LPK-0000000054', '<?=$KodePasar?>', 'B', '691')">
                                                    <area href="#" id="B692" data-nama="B692" state="LPK-0000000055" full="Lapak BLOK B NOMOR 692" shape="poly" coords="526,634,542,638,536,670,520,666"  onclick="lapakPasar('LPK-0000000055', '<?=$KodePasar?>', 'B', '692')">
                                                    <area href="#" id="B693" data-nama="B693" state="LPK-0000000056" full="Lapak BLOK B NOMOR 693" shape="poly" coords="510,631,526,634,520,666,504,663"  onclick="lapakPasar('LPK-0000000056', '<?=$KodePasar?>', 'B', '693')">
                                                    <area href="#" id="B694" data-nama="B694" state="LPK-0000000057" full="Lapak BLOK B NOMOR 694" shape="poly" coords="495,628,510,631,504,663,489,660"  onclick="lapakPasar('LPK-0000000057', '<?=$KodePasar?>', 'B', '694')">
                                                    <area href="#" id="B695" data-nama="B695" state="LPK-0000000058" full="Lapak BLOK B NOMOR 695" shape="poly" coords="482,625,495,628,489,660,476,657"  onclick="lapakPasar('LPK-0000000058', '<?=$KodePasar?>', 'B', '695')">
                                                    <area href="#" id="B696" data-nama="B696" state="LPK-0000000059" full="Lapak BLOK B NOMOR 696" shape="poly" coords="469,622,482,625,476,657,463,654"  onclick="lapakPasar('LPK-0000000059', '<?=$KodePasar?>', 'B', '696')">
                                                    <area href="#" id="B697" data-nama="B697" state="LPK-0000000060" full="Lapak BLOK B NOMOR 697" shape="poly" coords="456,619,469,622,463,654,450,651"  onclick="lapakPasar('LPK-0000000060', '<?=$KodePasar?>', 'B', '697')">
                                                    <area href="#" id="B698" data-nama="B698" state="LPK-0000000061" full="Lapak BLOK B NOMOR 698" shape="poly" coords="443,616,456,619,450,651,437,649"  onclick="lapakPasar('LPK-0000000061', '<?=$KodePasar?>', 'B', '698')">
                                                    <area href="#" id="B699" data-nama="B699" state="LPK-0000000062" full="Lapak BLOK B NOMOR 699" shape="poly" coords="430,613,443,616,437,649,424,647"  onclick="lapakPasar('LPK-0000000062', '<?=$KodePasar?>', 'B', '699')">
                                                    <area href="#" id="B700" data-nama="B700" state="LPK-0000000063" full="Lapak BLOK B NOMOR 700" shape="poly" coords="417,611,430,613,424,647,411,645"  onclick="lapakPasar('LPK-0000000063', '<?=$KodePasar?>', 'B', '700')">
                                                    <area href="#" id="B701" data-nama="B701" state="LPK-0000000064" full="Lapak BLOK B NOMOR 701" shape="poly" coords="404,609,417,611,411,645,398,642"  onclick="lapakPasar('LPK-0000000064', '<?=$KodePasar?>', 'B', '701')">
                                                    <area href="#" id="B702" data-nama="B702" state="LPK-0000000065" full="Lapak BLOK B NOMOR 702" shape="poly" coords="391,606,404,609,398,642,385,638"  onclick="lapakPasar('LPK-0000000065', '<?=$KodePasar?>', 'B', '702')">
                                                    <area href="#" id="B703" data-nama="B703" state="LPK-0000000066" full="Lapak BLOK B NOMOR 703" shape="poly" coords="378,602,391,606,385,638,372,634"  onclick="lapakPasar('LPK-0000000066', '<?=$KodePasar?>', 'B', '703')">
                                                    <area href="#" id="B704" data-nama="B704" state="LPK-0000000067" full="Lapak BLOK B NOMOR 704" shape="poly" coords="365,598,378,602,372,634,359,630"  onclick="lapakPasar('LPK-0000000067', '<?=$KodePasar?>', 'B', '704')">
                                                    <area href="#" id="B705" data-nama="B705" state="LPK-0000000068" full="Lapak BLOK B NOMOR 705" shape="poly" coords="352,594,365,598,359,630,346,626"  onclick="lapakPasar('LPK-0000000068', '<?=$KodePasar?>', 'B', '705')"> 
                                                    <area href="#" id="B706" data-nama="B706" state="LPK-0000000069" full="Lapak BLOK B NOMOR 706" shape="poly" coords="339,591,352,594,346,626,333,622"  onclick="lapakPasar('LPK-0000000069', '<?=$KodePasar?>', 'B', '706')">
                                                    <area href="#" id="B707" data-nama="B707" state="LPK-0000000070" full="Lapak BLOK B NOMOR 707" shape="poly" coords="323,589,339,591,333,622,319,620"  onclick="lapakPasar('LPK-0000000070', '<?=$KodePasar?>', 'B', '707')">

                                                    



                                                    <!--Blok B 882 sampai 889 -->
                                                    <area href="#" id="B882" data-nama="B882" state="LPK-0000000084" full="Lapak BLOK B NOMOR 882" shape="poly" coords="321,468,343,472,342,491,310,487,312,474,320,474"  onclick="lapakPasar('LPK-0000000084', '<?=$KodePasar?>', 'B', '882')">
                                                    <area href="#" id="B883" data-nama="B883" state="LPK-0000000085" full="Lapak BLOK B NOMOR 883" shape="poly" coords="310,487,342,491,341,505,308,501"  onclick="lapakPasar('LPK-0000000085', '<?=$KodePasar?>', 'B', '883')">
                                                    <area href="#" id="B884" data-nama="B884" state="LPK-0000000086" full="Lapak BLOK B NOMOR 884" shape="poly" coords="308,501,341,505,340,520,306,516"  onclick="lapakPasar('LPK-0000000086', '<?=$KodePasar?>', 'B', '884')">
                                                    <area href="#" id="B885" data-nama="B885" state="LPK-0000000087" full="Lapak BLOK B NOMOR 885" shape="poly" coords="306,516,346,520,345,534,304,530"  onclick="lapakPasar('LPK-0000000087', '<?=$KodePasar?>', 'B', '885')">
                                                    <area href="#" id="B886" data-nama="B886" state="LPK-0000000088" full="Lapak BLOK B NOMOR 886" shape="poly" coords="304,530,345,534,343,548,302,544"  onclick="lapakPasar('LPK-0000000088', '<?=$KodePasar?>', 'B', '886')">
                                                    <area href="#" id="B887" data-nama="B887" state="LPK-0000000089" full="Lapak BLOK B NOMOR 887" shape="poly" coords="302,544,343,548,341,562,300,558"  onclick="lapakPasar('LPK-0000000089', '<?=$KodePasar?>', 'B', '887')">
                                                    <area href="#" id="B888" data-nama="B888" state="LPK-0000000090" full="Lapak BLOK B NOMOR 888" shape="poly" coords="300,558,341,562,340,576,298,572"  onclick="lapakPasar('LPK-0000000090', '<?=$KodePasar?>', 'B', '888')">
                                                    <area href="#" id="B889" data-nama="B889" state="LPK-0000000091" full="Lapak BLOK B NOMOR 889" shape="poly" coords="298,572,340,576,338,590,296,586"  onclick="lapakPasar('LPK-0000000091', '<?=$KodePasar?>', 'B', '889')">


                                                    <!--Blok B 648 sampai 659 -->
                                                    <area href="#" id="B648" data-nama="B648" state="LPK-0000000013" full="Lapak BLOK B NOMOR 648" shape="poly" coords="159,436,182,437,182,447,192,447,191,467,158,466"  onclick="lapakPasar('LPK-0000000013', '<?=$KodePasar?>', 'B', '648')">
                                                    <area href="#" id="B649" data-nama="B649" state="LPK-0000000014" full="Lapak BLOK B NOMOR 649" shape="poly" coords="158,466,191,468,189,487,158,485"  onclick="lapakPasar('LPK-0000000014', '<?=$KodePasar?>', 'B', '649')">
                                                    <area href="#" id="B650" data-nama="B650" state="LPK-0000000015" full="Lapak BLOK B NOMOR 650" shape="poly" coords="158,485,189,487,188,507,157,505"  onclick="lapakPasar('LPK-0000000015', '<?=$KodePasar?>', 'B', '650')">
                                                    <area href="#" id="B651" data-nama="651" state="LPK-0000000016" full="Lapak BLOK B NOMOR 651" shape="poly" coords="157,505,188,507,187,527,156,525"  onclick="lapakPasar('LPK-0000000016', '<?=$KodePasar?>', 'B', '651')">
                                                    <area href="#" id="B652" data-nama="652" state="LPK-0000000017" full="Lapak BLOK B NOMOR 652" shape="poly" coords="156,525,187,527,186,547,155,545"  onclick="lapakPasar('LPK-0000000017', '<?=$KodePasar?>', 'B', '652')">
                                                    <area href="#" id="B653" data-nama="653" state="LPK-0000000018" full="Lapak BLOK B NOMOR 653" shape="poly" coords="155,545,186,547,185,567,154,565"  onclick="lapakPasar('LPK-0000000018', '<?=$KodePasar?>', 'B', '653')">
                                                    <area href="#" id="B654" data-nama="654" state="LPK-0000000019" full="Lapak BLOK B NOMOR 654" shape="poly" coords="154,565,185,567,184,587,153,585"  onclick="lapakPasar('LPK-0000000019', '<?=$KodePasar?>', 'B', '654')">
                                                    <area href="#" id="B655" data-nama="655" state="LPK-0000000020" full="Lapak BLOK B NOMOR 655" shape="poly" coords="153,585,184,587,183,607,152,605"  onclick="lapakPasar('LPK-0000000020', '<?=$KodePasar?>', 'B', '655')">
                                                    <area href="#" id="B656" data-nama="656" state="LPK-0000000021" full="Lapak BLOK B NOMOR 656" shape="poly" coords="152,605,183,607,182,627,151,625"  onclick="lapakPasar('LPK-0000000021', '<?=$KodePasar?>', 'B', '656')">
                                                    <area href="#" id="B657" data-nama="657" state="LPK-0000000022" full="Lapak BLOK B NOMOR 657" shape="poly" coords="151,625,182,627,181,647,150,645"  onclick="lapakPasar('LPK-0000000022', '<?=$KodePasar?>', 'B', '657')">
                                                    <area href="#" id="B658" data-nama="658" state="LPK-0000000023" full="Lapak BLOK B NOMOR 658" shape="poly" coords="150,645,181,647,180,667,149,665"  onclick="lapakPasar('LPK-0000000023', '<?=$KodePasar?>', 'B', '658')">
                                                    <area href="#" id="B659" data-nama="659" state="LPK-0000000024" full="Lapak BLOK B NOMOR 659" shape="poly" coords="149,665,180,667,179,686,170,685,170,692,149,690"  onclick="lapakPasar('LPK-0000000024', '<?=$KodePasar?>', 'B', '659')">


                                                    <!--Blok B 629 sampai 640 -->
                                                    <area href="#" id="B629" data-nama="B629" state="LPK-0000000001" full="Lapak BLOK B NOMOR 629" shape="poly" coords="133,434,159,436,158,466,126,464,126,444,133,444, "  onclick="lapakPasar('LPK-0000000001', '<?=$KodePasar?>', 'B', '629')">
                                                    <area href="#" id="B630" data-nama="B630" state="LPK-0000000002" full="Lapak BLOK B NOMOR 630" shape="poly" coords="126,464,159,465,158,485,125,483"  onclick="lapakPasar('LPK-0000000002', '<?=$KodePasar?>', 'B', '630')">
                                                    <area href="#" id="B631" data-nama="B631" state="LPK-0000000003" full="Lapak BLOK B NOMOR 631" shape="poly" coords="125,483,158,485,157,505,124,503"  onclick="lapakPasar('LPK-0000000003', '<?=$KodePasar?>', 'B', '631')">
                                                    <area href="#" id="B632" data-nama="B632" state="LPK-0000000004" full="Lapak BLOK B NOMOR 632" shape="poly" coords="124,503,157,505,156,525,123,523"  onclick="lapakPasar('LPK-0000000004', '<?=$KodePasar?>', 'B', '632')">
                                                    <area href="#" id="B633" data-nama="B633" state="LPK-0000000005" full="Lapak BLOK B NOMOR 633" shape="poly" coords="123,523,156,525,155,545,122,543"  onclick="lapakPasar('LPK-0000000005', '<?=$KodePasar?>', 'B', '633')">
                                                    <area href="#" id="B634" data-nama="B634" state="LPK-0000000006" full="Lapak BLOK B NOMOR 634" shape="poly" coords="122,543,155,545,154,565,121,563"  onclick="lapakPasar('LPK-0000000006', '<?=$KodePasar?>', 'B', '634')">
                                                    <area href="#" id="B635" data-nama="B635" state="LPK-0000000007" full="Lapak BLOK B NOMOR 635" shape="poly" coords="121,563,154,565,153,585,120,583"  onclick="lapakPasar('LPK-0000000007', '<?=$KodePasar?>', 'B', '635')">
                                                    <area href="#" id="B636" data-nama="B636" state="LPK-0000000008" full="Lapak BLOK B NOMOR 636" shape="poly" coords="120,583,153,585,152,605,119,603"  onclick="lapakPasar('LPK-0000000008', '<?=$KodePasar?>', 'B', '636')">
                                                    <area href="#" id="B637" data-nama="B637" state="LPK-0000000009" full="Lapak BLOK B NOMOR 637" shape="poly" coords="119,603,152,605,151,625,118,623"  onclick="lapakPasar('LPK-0000000009', '<?=$KodePasar?>', 'B', '637')">
                                                    <area href="#" id="B638" data-nama="B638" state="LPK-0000000010" full="Lapak BLOK B NOMOR 638" shape="poly" coords="118,623,151,625,150,645,117,643"  onclick="lapakPasar('LPK-0000000010', '<?=$KodePasar?>', 'B', '638')">
                                                    <area href="#" id="B639" data-nama="B639" state="LPK-0000000011" full="Lapak BLOK B NOMOR 639" shape="poly" coords="117,643,150,645,149,665,116,663"  onclick="lapakPasar('LPK-0000000011', '<?=$KodePasar?>', 'B', '639')">
                                                    <area href="#" id="B640" data-nama="B640" state="LPK-0000000012" full="Lapak BLOK B NOMOR 640" shape="poly" coords="116,663,149,665,148,690,125,688,125,682,115,681"  onclick="lapakPasar('LPK-0000000012', '<?=$KodePasar?>', 'B', '640')">


                                                    <!--Blok B 662 sampai 670 -->
                                                    <area href="#" id="B662" data-nama="B662" state="LPK-0000000025" full="Lapak BLOK B NOMOR 662" shape="poly" coords="78,771,82,743,86,743,88,737,106,740,99,775"  onclick="lapakPasar('LPK-0000000025', '<?=$KodePasar?>', 'B', '662')">
                                                    <area href="#" id="B663" data-nama="B663" state="LPK-0000000026" full="Lapak BLOK B NOMOR 663" shape="poly" coords="99,775,107,740,125,743,119,779"  onclick="lapakPasar('LPK-0000000026', '<?=$KodePasar?>', 'B', '663')">
                                                    <area href="#" id="B664" data-nama="B664" state="LPK-0000000027" full="Lapak BLOK B NOMOR 664" shape="poly" coords="119,779,126,743,145,747,139,782"  onclick="lapakPasar('LPK-0000000027', '<?=$KodePasar?>', 'B', '664')">
                                                    <area href="#" id="B665" data-nama="B665" state="LPK-0000000028" full="Lapak BLOK B NOMOR 665" shape="poly" coords="139,782,146,747,165,750,159,785"  onclick="lapakPasar('LPK-0000000028', '<?=$KodePasar?>', 'B', '665')">
                                                    <area href="#" id="B666" data-nama="B666" state="LPK-0000000029" full="Lapak BLOK B NOMOR 666" shape="poly" coords="159,785,166,750,185,753,179,789"  onclick="lapakPasar('LPK-0000000029', '<?=$KodePasar?>', 'B', '666')">
                                                    <area href="#" id="B667" data-nama="B667" state="LPK-0000000030" full="Lapak BLOK B NOMOR 667" shape="poly" coords="179,789,186,753,205,756,199,793"  onclick="lapakPasar('LPK-0000000030', '<?=$KodePasar?>', 'B', '667')">
                                                    <area href="#" id="B668" data-nama="B668" state="LPK-0000000031" full="Lapak BLOK B NOMOR 668" shape="poly" coords="199,793,206,756,225,759,219,796"  onclick="lapakPasar('LPK-0000000031', '<?=$KodePasar?>', 'B', '668')">
                                                    <area href="#" id="B669" data-nama="B669" state="LPK-0000000032" full="Lapak BLOK B NOMOR 669" shape="poly" coords="219,796,226,759,245,762,239,799"  onclick="lapakPasar('LPK-0000000032', '<?=$KodePasar?>', 'B', '669')">
                                                    <area href="#" id="B670" data-nama="B670" state="LPK-0000000033" full="Lapak BLOK B NOMOR 670" shape="poly" coords="239,799,246,762,257,764,256,774,265,776,260,803"  onclick="lapakPasar('LPK-0000000033', '<?=$KodePasar?>', 'B', '670')">


                                                     <!-- Lingkaran -->
                                                    <area href="#" id="HPL" data-nama="Lingkaran" state="ZK" full="Kantor" shape="circle" coords="240, 690, 35">
                                                    <!-- Kotak sebelah Lingkaran -->
                                                    <area href="#" id="ZK" data-nama="KOTAK" state="ZK" full="Kantor" shape="poly" coords="280,690,305,695,298,720,272,715">


                                                    <!-- Kantor -->
                                                    <area href="#" id="ZK" data-nama="KANTOR" state="ZK" full="Kantor" shape="poly" coords="224,447,255,449,253,479,221,478 ">

                                                    <!-- PASAR TENGAH -->
                                                    <area href="#" id="ZK" data-nama="PASAR TENGAH" state="ZK" full="Pasar Tengah" shape="poly" coords="346,683,582,739,578,746,576,746,542,896,312,840">

                                                    <!--HALAMAN PARKIR LUAR PASAR TENGAH -->
                                                    <area href="#" id="ZK" data-nama="HPL" state="ZK" full="HALAMAN PARKIR 4" shape="poly" coords="632,700,685,712,668,789,621,776">
                                                    <area href="#" id="ZK" data-nama="HPL" state="ZK" full="HALAMAN PARKIR 7" shape="poly" coords="612,695,632,700,621,776,596,768,600,738,605,738">
                                                    <area href="#" id="ZK" data-nama="HPL" state="ZK" full="HALAMAN PARKIR 5" shape="poly" coords="588,691,612,695,605,738,600,738,596,768,573,761,576,745,579,745,585,735">
                                                    <area href="#" id="ZK" data-nama="HPL" state="ZK" full="HALAMAN PARKIR 2" shape="poly" coords="573,761,668,789,656,841,647,854,558,824">
                                                    <area href="#" id="ZK" data-nama="HPL" state="ZK" full="HALAMAN PARKIR 6" shape="poly" coords="558,824,647,854,634,924,590,912,543,896">

                                                    <!-- HALAMAN PARKIR LUAR-->
                                                    <area href="#" id="HPL" data-nama="HPL" state="ZK" full="HPL" shape="poly" coords="243,800,267,804,258,854,233,849">

                                                    <!--Blok B 671 sampai 683 -->
                                                    <area href="#" id="B683" data-nama="B683" state="LPK-0000000046" full="Lapak BLOK B NOMOR 683" shape="poly" coords="180,140,178,160,148,158,149,143,155,143,156,139"  onclick="lapakPasar('LPK-0000000046', '<?=$KodePasar?>', 'B', '683')">
                                                    <area href="#" id="B682" data-nama="B682" state="LPK-0000000045" full="Lapak BLOK B NOMOR 682" shape="poly" coords="148,159,178,160,177,178,147,177"  onclick="lapakPasar('LPK-0000000046', '<?=$KodePasar?>', 'B', '682')">
                                                    <area href="#" id="B681" data-nama="B681" state="LPK-0000000044" full="Lapak BLOK B NOMOR 681" shape="poly" coords="147,177,177,178,176,196,146,195"  onclick="lapakPasar('LPK-0000000044', '<?=$KodePasar?>', 'B', '681')">
                                                    <area href="#" id="B680" data-nama="B680" state="LPK-0000000043" full="Lapak BLOK B NOMOR 680" shape="poly" coords="146,195,176,196,175,214,145,213 "  onclick="lapakPasar('LPK-0000000043', '<?=$KodePasar?>', 'B', '680')">
                                                    <area href="#" id="B679" data-nama="B679" state="LPK-0000000042" full="Lapak BLOK B NOMOR 679" shape="poly" coords="145,213,175,214,174,232,144,231"  onclick="lapakPasar('LPK-0000000042', '<?=$KodePasar?>', 'B', '679')">
                                                    <area href="#" id="B678" data-nama="B678" state="LPK-0000000041" full="Lapak BLOK B NOMOR 678" shape="poly" coords="144,231,174,232,173,250,143,249"  onclick="lapakPasar('LPK-0000000041', '<?=$KodePasar?>', 'B', '678')">
                                                    <area href="#" id="B677" data-nama="B677" state="LPK-0000000040" full="Lapak BLOK B NOMOR 677" shape="poly" coords="143,249,173,250,172,268,142,267"  onclick="lapakPasar('LPK-0000000040', '<?=$KodePasar?>', 'B', '677')">
                                                    <area href="#" id="B676" data-nama="B676" state="LPK-0000000039" full="Lapak BLOK B NOMOR 676" shape="poly" coords="142,267,172,268,171,286,141,285"  onclick="lapakPasar('LPK-0000000039', '<?=$KodePasar?>', 'B', '676')">
                                                    <area href="#" id="B675" data-nama="B675" state="LPK-0000000038" full="Lapak BLOK B NOMOR 675" shape="poly" coords="141,285,171,286,170,304,140,303"  onclick="lapakPasar('LPK-0000000038', '<?=$KodePasar?>', 'B', '675')">
                                                    <area href="#" id="B674" data-nama="B674" state="LPK-0000000037" full="Lapak BLOK B NOMOR 674" shape="poly" coords="140,303,170,304,169,322,139,321"  onclick="lapakPasar('LPK-0000000037', '<?=$KodePasar?>', 'B', '674')">
                                                    <area href="#" id="B673" data-nama="B673" state="LPK-0000000036" full="Lapak BLOK B NOMOR 673" shape="poly" coords="139,321,169,322,168,340,138,339"  onclick="lapakPasar('LPK-0000000036', '<?=$KodePasar?>', 'B', '673')">
                                                    <area href="#" id="B672" data-nama="B672" state="LPK-0000000035" full="Lapak BLOK B NOMOR 672" shape="poly" coords="138,339,168,340,167,358,137,357"  onclick="lapakPasar('LPK-0000000035', '<?=$KodePasar?>', 'B', '672')">
                                                    <area href="#" id="B671" data-nama="B671" state="LPK-0000000034" full="Lapak BLOK B NOMOR 671" shape="poly" coords="137,357,167,358,166,376,141,375,141,366,136,366"  onclick="lapakPasar('LPK-0000000034', '<?=$KodePasar?>', 'B', '671')">

                                                    <!-- <area href="#" id="B676" data-nama="B676" state="LPK-0000000039" full="Lapak BLOK B NOMOR 676" shape="poly" coords="143,248,173,250,172,269,142,267"  onclick="lapakPasar('LPK-0000000039', '<?=$KodePasar?>', 'B', '676')"> -->

                                                    <!--Blok B 711 sampai 722 -->
                                                    <area href="#" id="B711" data-nama="B711" state="LPK-0000000071" full="Lapak BLOK B NOMOR 711" shape="poly" coords="180,146,199,147,199,156,207,157,206,180,177,178"  onclick="lapakPasar('LPK-0000000071', '<?=$KodePasar?>', 'B', '711')">
                                                    <area href="#" id="B712" data-nama="B712" state="LPK-0000000072" full="Lapak BLOK B NOMOR 712" shape="poly" coords="177,178,206,180,205,198,176,196"  onclick="lapakPasar('LPK-0000000072', '<?=$KodePasar?>', 'B', '712')">
                                                    <area href="#" id="B713" data-nama="B713" state="LPK-0000000073" full="Lapak BLOK B NOMOR 713" shape="poly" coords="176,196,205,198,204,216,175,214"  onclick="lapakPasar('LPK-0000000073', '<?=$KodePasar?>', 'B', '713')">
                                                    <area href="#" id="B714" data-nama="B714" state="LPK-0000000074" full="Lapak BLOK B NOMOR 714" shape="poly" coords="175,214,204,216,203,234,174,232"  onclick="lapakPasar('LPK-0000000074', '<?=$KodePasar?>', 'B', '714')">
                                                    <area href="#" id="B715" data-nama="B715" state="LPK-0000000075" full="Lapak BLOK B NOMOR 715" shape="poly" coords="174,232,203,234,202,252,173,250"  onclick="lapakPasar('LPK-0000000075', '<?=$KodePasar?>', 'B', '715')">
                                                    <area href="#" id="B716" data-nama="B716" state="LPK-0000000076" full="Lapak BLOK B NOMOR 716" shape="poly" coords="173,250,202,252,201,270,172,268"  onclick="lapakPasar('LPK-0000000076', '<?=$KodePasar?>', 'B', '716')">
                                                    <area href="#" id="B717" data-nama="B717" state="LPK-0000000077" full="Lapak BLOK B NOMOR 717" shape="poly" coords="172,268,201,270,200,288,171,286"  onclick="lapakPasar('LPK-0000000077', '<?=$KodePasar?>', 'B', '717')">
                                                    <area href="#" id="B718" data-nama="B718" state="LPK-0000000078" full="Lapak BLOK B NOMOR 718" shape="poly" coords="171,286,200,288,199,306,170,304"  onclick="lapakPasar('LPK-0000000078', '<?=$KodePasar?>', 'B', '718')">
                                                    <area href="#" id="B719" data-nama="B719" state="LPK-0000000079" full="Lapak BLOK B NOMOR 719" shape="poly" coords="170,304,199,306,198,324,169,322"  onclick="lapakPasar('LPK-0000000079', '<?=$KodePasar?>', 'B', '719')">
                                                    <area href="#" id="B720" data-nama="B720" state="LPK-0000000080" full="Lapak BLOK B NOMOR 720" shape="poly" coords="169,322,198,324,197,342,168,340"  onclick="lapakPasar('LPK-0000000080', '<?=$KodePasar?>', 'B', '720')">
                                                    <area href="#" id="B721" data-nama="B721" state="LPK-0000000081" full="Lapak BLOK B NOMOR 721" shape="poly" coords="168,340,197,342,196,360,167,358"  onclick="lapakPasar('LPK-0000000081', '<?=$KodePasar?>', 'B', '721')">
                                                    <area href="#" id="B722" data-nama="B722" state="LPK-0000000082" full="Lapak BLOK B NOMOR 722" shape="poly" coords="167,358,196,360,196,368,191,368,191,378,167,377  "  onclick="lapakPasar('LPK-0000000082', '<?=$KodePasar?>', 'B', '722')">

                                                    <!--Blok B 971 sampai 960 -->
                                                    <area href="#" id="B971" data-nama="B971" state="LPK-0000000117" full="Lapak BLOK B NOMOR 971" shape="poly" coords="250,169,255,169,255,165,275,166,274,184,249,183"  onclick="lapakPasar('LPK-0000000117', '<?=$KodePasar?>', 'B', '971')">
                                                    <area href="#" id="B970" data-nama="B970" state="LPK-0000000116" full="Lapak BLOK B NOMOR 970" shape="poly" coords="249,183,274,184,273,201,248,200 "  onclick="lapakPasar('LPK-0000000116', '<?=$KodePasar?>', 'B', '970')">
                                                    <area href="#" id="B969" data-nama="B969" state="LPK-0000000115" full="Lapak BLOK B NOMOR 969" shape="poly" coords="248,200,273,201,272,219,247,218"  onclick="lapakPasar('LPK-0000000115', '<?=$KodePasar?>', 'B', '969')">
                                                    <area href="#" id="B968" data-nama="B968" state="LPK-0000000114" full="Lapak BLOK B NOMOR 968" shape="poly" coords="247,218,272,219,271,237,246,236"  onclick="lapakPasar('LPK-0000000114', '<?=$KodePasar?>', 'B', '968')">
                                                    <area href="#" id="B967" data-nama="B967" state="LPK-0000000113" full="Lapak BLOK B NOMOR 967" shape="poly" coords="246,236,271,237,270,255,245,254"  onclick="lapakPasar('LPK-0000000113', '<?=$KodePasar?>', 'B', '967')">
                                                    <area href="#" id="B966" data-nama="B966" state="LPK-0000000112" full="Lapak BLOK B NOMOR 966" shape="poly" coords="245,254,270,255,269,273,244,272"  onclick="lapakPasar('LPK-0000000112', '<?=$KodePasar?>', 'B', '966')">
                                                    <area href="#" id="B965" data-nama="B965" state="LPK-0000000111" full="Lapak BLOK B NOMOR 965" shape="poly" coords="244,272,269,273,268,291,243,290"  onclick="lapakPasar('LPK-0000000111', '<?=$KodePasar?>', 'B', '965')">
                                                    <area href="#" id="B964" data-nama="B964" state="LPK-0000000110" full="Lapak BLOK B NOMOR 964" shape="poly" coords="243,290,268,291,267,309,242,308"  onclick="lapakPasar('LPK-0000000110', '<?=$KodePasar?>', 'B', '964')">
                                                    <area href="#" id="B963" data-nama="B963" state="LPK-0000000109" full="Lapak BLOK B NOMOR 963" shape="poly" coords="242,308,267,309,266,327,241,326"  onclick="lapakPasar('LPK-0000000109', '<?=$KodePasar?>', 'B', '963')">
                                                    <area href="#" id="B962" data-nama="B962" state="LPK-0000000108" full="Lapak BLOK B NOMOR 962" shape="poly" coords="241,326,266,327,265,345,240,344"  onclick="lapakPasar('LPK-0000000108', '<?=$KodePasar?>', 'B', '962')">
                                                    <area href="#" id="B961" data-nama="B961" state="LPK-0000000107" full="Lapak BLOK B NOMOR 961" shape="poly" coords="240,344,265,345,264,363,239,362"  onclick="lapakPasar('LPK-0000000107', '<?=$KodePasar?>', 'B', '961')">
                                                    <area href="#" id="B960" data-nama="B960" state="LPK-0000000106" full="Lapak BLOK B NOMOR 960" shape="poly" coords="239,362,264,363,263,381,242,380,242,372,238,372"  onclick="lapakPasar('LPK-0000000106', '<?=$KodePasar?>', 'B', '960')">

                                                    <!--Blok B 972 sampai 982 -->
                                                    <area href="#" id="B972" data-nama="B972" state="LPK-0000000118" full="Lapak BLOK B NOMOR 972" shape="poly" coords="275,171,287,172,287,177,292,177,292,183,297,183,297,202,273,201"  onclick="lapakPasar('LPK-0000000118', '<?=$KodePasar?>', 'B', '972')">
                                                    <area href="#" id="B973" data-nama="B973" state="LPK-0000000119" full="Lapak BLOK B NOMOR 973" shape="poly" coords="273,201,297,202,296,220,272,219"  onclick="lapakPasar('LPK-0000000119', '<?=$KodePasar?>', 'B', '973')">
                                                    <area href="#" id="B974" data-nama="B974" state="LPK-0000000120" full="Lapak BLOK B NOMOR 974" shape="poly" coords="272,219,296,220,295,238,271,237"  onclick="lapakPasar('LPK-0000000120', '<?=$KodePasar?>', 'B', '974')">
                                                    <area href="#" id="B975" data-nama="B975" state="LPK-0000000121" full="Lapak BLOK B NOMOR 975" shape="poly" coords="271,237,295,238,294,256,270,255"  onclick="lapakPasar('LPK-0000000121', '<?=$KodePasar?>', 'B', '975')">
                                                    <area href="#" id="B976" data-nama="B976" state="LPK-0000000122" full="Lapak BLOK B NOMOR 976" shape="poly" coords="270,255,294,256,293,274,269,273"  onclick="lapakPasar('LPK-0000000122', '<?=$KodePasar?>', 'B', '976')">
                                                    <area href="#" id="B977" data-nama="B977" state="LPK-0000000123" full="Lapak BLOK B NOMOR 977" shape="poly" coords="269,273,293,274,292,292,268,291"  onclick="lapakPasar('LPK-0000000123', '<?=$KodePasar?>', 'B', '977')">
                                                    <area href="#" id="B978" data-nama="B978" state="LPK-0000000124" full="Lapak BLOK B NOMOR 978" shape="poly" coords="268,291,292,292,291,310,267,309"  onclick="lapakPasar('LPK-0000000124', '<?=$KodePasar?>', 'B', '978')">
                                                    <area href="#" id="B979" data-nama="B979" state="LPK-0000000125" full="Lapak BLOK B NOMOR 979" shape="poly" coords="267,309,291,310,290,328,266,327"  onclick="lapakPasar('LPK-0000000125', '<?=$KodePasar?>', 'B', '979')">
                                                    <area href="#" id="B980" data-nama="B980" state="LPK-0000000126" full="Lapak BLOK B NOMOR 980" shape="poly" coords="266,327,290,328,289,346,265,345"  onclick="lapakPasar('LPK-0000000126', '<?=$KodePasar?>', 'B', '980')">
                                                    <area href="#" id="B981" data-nama="B981" state="LPK-0000000127" full="Lapak BLOK B NOMOR 981" shape="poly" coords="265,345,289,346,288,364,264,363"  onclick="lapakPasar('LPK-0000000127', '<?=$KodePasar?>', 'B', '981')">
                                                    <area href="#" id="B982" data-nama="B982" state="LPK-0000000128" full="Lapak BLOK B NOMOR 982" shape="poly" coords="264,363,288,364,287,376,281,376,281,382,263,381"  onclick="lapakPasar('LPK-0000000128', '<?=$KodePasar?>', 'B', '982')">

                                                    <!-- Blok 948 Sampai 959 -->
                                                    <area href="#" id="B948" data-nama="B948" state="LPK-0000000094" full="Lapak BLOK B NOMOR 948" shape="poly" coords="346,198,389,203,384,225,343,221"  onclick="lapakPasar('LPK-0000000094', '<?=$KodePasar?>', 'B', '948')">
                                                    <area href="#" id="B949" data-nama="B949" state="LPK-0000000095" full="Lapak BLOK B NOMOR 949" shape="poly" coords="343,221,384,225,380,240,341,236"  onclick="lapakPasar('LPK-0000000095', '<?=$KodePasar?>', 'B', '949')">
                                                    <area href="#" id="B950" data-nama="B950" state="LPK-0000000096" full="Lapak BLOK B NOMOR 950" shape="poly" coords="341,236,380,240,377,254,339,250"  onclick="lapakPasar('LPK-0000000096', '<?=$KodePasar?>', 'B', '950')">
                                                    <area href="#" id="B951" data-nama="B951" state="LPK-0000000097" full="Lapak BLOK B NOMOR 951" shape="poly" coords="339,250,377,254,374,268,337,264"  onclick="lapakPasar('LPK-0000000097', '<?=$KodePasar?>', 'B', '951')">
                                                    <area href="#" id="B952" data-nama="B952" state="LPK-0000000098" full="Lapak BLOK B NOMOR 952" shape="poly" coords="337,264,374,268,371,282,335,278"  onclick="lapakPasar('LPK-0000000098', '<?=$KodePasar?>', 'B', '952')">
                                                    <area href="#" id="B953" data-nama="B953" state="LPK-0000000099" full="Lapak BLOK B NOMOR 953" shape="poly" coords="335,278,371,282,368,296,333,292"  onclick="lapakPasar('LPK-0000000099', '<?=$KodePasar?>', 'B', '953')">
                                                    <area href="#" id="B954" data-nama="B954" state="LPK-0000000100" full="Lapak BLOK B NOMOR 954" shape="poly" coords="333,292,368,296,365,310,331,306"  onclick="lapakPasar('LPK-0000000100', '<?=$KodePasar?>', 'B', '954')">
                                                    <area href="#" id="B955" data-nama="B955" state="LPK-0000000101" full="Lapak BLOK B NOMOR 955" shape="poly" coords="331,306,365,310,362,324,329,320"  onclick="lapakPasar('LPK-0000000101', '<?=$KodePasar?>', 'B', '955')">
                                                    <area href="#" id="B956" data-nama="B956" state="LPK-0000000102" full="Lapak BLOK B NOMOR 956" shape="poly" coords="329,320,361,324,358,338,327,334"  onclick="lapakPasar('LPK-0000000102', '<?=$KodePasar?>', 'B', '956')">
                                                    <area href="#" id="B957" data-nama="B957" state="LPK-0000000103" full="Lapak BLOK B NOMOR 957" shape="poly" coords="327,334,358,338,355,352,325,348"  onclick="lapakPasar('LPK-0000000103', '<?=$KodePasar?>', 'B', '957')">
                                                    <area href="#" id="B958" data-nama="B958" state="LPK-0000000104" full="Lapak BLOK B NOMOR 958" shape="poly" coords="325,348,355,352,352,366,323,362"  onclick="lapakPasar('LPK-0000000104', '<?=$KodePasar?>', 'B', '958')">
                                                    <area href="#" id="B959" data-nama="B959" state="LPK-0000000105" full="Lapak BLOK B NOMOR 959" shape="poly" coords="323,362,352,366,348,380,321,376"  onclick="lapakPasar('LPK-0000000105', '<?=$KodePasar?>', 'B', '959')">

                                                    <!-- Lapak Nomor 903 -->
                                                    <area href="#" id="B903" data-nama="B903" state="LPK-0000000093" full="Lapak BLOK B NOMOR 903" shape="poly" coords="321,376,349,380,342,408,318,406"  onclick="lapakPasar('LPK-0000000093', '<?=$KodePasar?>', 'B', '903')">

                                                    <!-- Lapak Nomor 881 -->
                                                    <area href="#" id="B881" data-nama="B881" state="LPK-0000000083" full="Lapak BLOK B NOMOR 881" shape="poly" coords="334,408,349,409,376,419,369,474,328,470 "  onclick="lapakPasar('LPK-0000000083', '<?=$KodePasar?>', 'B', '881')">




<!-- 
                                                     <area href="#" id="B683" data-nama="B683" state="LPK-0000000046" full="Lapak BLOK B NOMOR 683" shape="poly" coords="130,263,249,166"  onclick="lapakPasar('LPK-0000000046', '<?=$KodePasar?>', 'B', '683')">
                                                      <area href="#" id="B683" data-nama="B683" state="LPK-0000000046" full="Lapak BLOK B NOMOR 683" shape="poly" coords="130,263,249,166"  onclick="lapakPasar('LPK-0000000046', '<?=$KodePasar?>', 'B', '683')"> -->
                                                    
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
                fillOpacity: 1,
                render_highlight: {
                    fillColor: 'bfbebb',
                    stroke: true
                },
                render_select: {
                    stroke: true,
                    strokeWidth: 2,
                    strokeColor: '000000'
                },
                mouseoutDelay: 0,
                fadeInterval: 50,
                isSelectable: true,
                singleSelect: false,
                mapKey: 'state',
                mapValue: 'full',
                listKey: 'name',
                sortList: "asc",

                onConfigured: mapsterConfigured,
                showToolTip: false,
                toolTipClose: ["area-mouseout"],
                areas: [
                <?php 
                foreach ($data_lapak as $data) {
                    $color = isset($data['NoTransRet']) && $data['NoTransRet'] != NULL ? 'ffab03' : 'e32209';
                    echo '{key: "'.$data['IDLapak'].'",
                    staticState: true,
                    fillOpacity: 1,
                    fillColor: "'.$color.'"}, ';
                }
                echo '{key: "Pintu",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 0}, ';  
                echo '{key: "HPL",
                staticState: true,
                fillColor: "7bacd1",
                fillOpacity: 1}, ';  
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

        function lapakPasar(kodeLapak, kodepasar, blok, nomor) {
            var namaPasar = "CitraNiaga";
            $.ajax({
                url: "MapModal.php",
                type: "GET",
                data : {KodeLapak: kodeLapak, KodePasar: kodepasar, Blok:blok, Nomor:nomor, NamaPasar:namaPasar},
                success: function (ajaxData){
                    $("#ModalEdit").html(ajaxData);
                    $("#ModalEdit").modal('show',{backdrop: 'true'});
                }
            });
            $('#myModal').modal('show');
        }

        $(function() {
            $('#E16').each(function(){
                var txt=$(this).data('nama');
                var left=80;
                var top=145;
                var $span=$('<span class="map_title">'+txt+'</span>');        
                $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'white'});
                $span.appendTo('#map_demo');
            })

        });

        function previewImage(num) {
            alert('hahah');
        };
    </script>
</body>
</html>