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
                                            <div class="spinner" style="display:none">
                                              <img id="img-spinner" src="../images/Assets/spin.gif" height="10%" width="10%" >
                                            </div>
                                            <div class="table-responsive">  
                                                <div id="map_demo" >
                                                    <div style="width:100%; border:0; overflow: hidden; float:left;">
                                                        <img style="width:100%;border:0;" id="usa_image" src="../images/Assets/MapCitraNiaga.png" usemap="#usa" >
                                                    </div>
                                                </div>      

                                                <map id="usa_image_map" name="usa">
                                                    <!--Blok E 1 -->
                                                    <area href="#" id="E1" data-nama="E1" data-state="LPK-0000000087" full="Lapak BLOK E NOMOR 1" shape="poly" coords="296,586,323,590,319,620,309,618,295,603 "  onclick="lapakPasar('LPK-0000000087', '<?=$KodePasar?>', '1', '1')">

                                                     <!--Blok F -->
                                                    <area href="#" id="F1" data-nama="F1" data-state="LPK-0000000098" full="Lapak BLOK F NOMOR 684" shape="poly" coords="655,665,680,670,676,695,668,693,666,702,648,698"  onclick="lapakPasar('LPK-0000000098', '<?=$KodePasar?>', 'F', '1')">
                                                    <area href="#" id="F2" data-nama="F2" data-state="LPK-0000000099" full="Lapak BLOK F NOMOR 2" shape="poly" coords="638,662,654,665,648,698,632,694"  onclick="lapakPasar('LPK-0000000099', '<?=$KodePasar?>', 'F', '2')">
                                                    <area href="#" id="F3" data-nama="F3" data-state="LPK-0000000100" full="Lapak BLOK F NOMOR 3" shape="poly" coords="622,658,638,662,632,694,616,690"  onclick="lapakPasar('LPK-0000000100', '<?=$KodePasar?>', 'F', '3')">
                                                    <area href="#" id="F4" data-nama="F4" data-state="LPK-0000000101" full="Lapak BLOK F NOMOR 4" shape="poly" coords="606,654,622,658,616,690,600,686"  onclick="lapakPasar('LPK-0000000101', '<?=$KodePasar?>', 'F', '4')">
                                                    <area href="#" id="F5" data-nama="F5" data-state="LPK-0000000102" full="Lapak BLOK F NOMOR 5" shape="poly" coords="590,650,606,654,600,686,584,682" onclick="lapakPasar('LPK-0000000102', '<?=$KodePasar?>', 'F', '5')">
                                                    <area href="#" id="F6" data-nama="F6" data-state="LPK-0000000103" full="Lapak BLOK F NOMOR 6" shape="poly" coords="574,646,590,650,584,682,568,678"  onclick="lapakPasar('LPK-0000000103', '<?=$KodePasar?>', 'F', '6')">
                                                    <area href="#" id="F7" data-nama="F7" data-state="LPK-0000000104" full="Lapak BLOK F NOMOR 7" shape="poly" coords="558,642,574,646,568,678,552,674"  onclick="lapakPasar('LPK-0000000104', '<?=$KodePasar?>', 'F', '7')">
                                                    <area href="#" id="F8" data-nama="F8" data-state="LPK-0000000105" full="Lapak BLOK F NOMOR 8" shape="poly" coords="542,638,558,642,552,674,536,670"  onclick="lapakPasar('LPK-0000000105', '<?=$KodePasar?>', 'F', '8')">
                                                    <area href="#" id="F9" data-nama="F9" data-state="LPK-0000000106" full="Lapak BLOK F NOMOR 9" shape="poly" coords="526,634,542,638,536,670,520,666"  onclick="lapakPasar('LPK-0000000106', '<?=$KodePasar?>', 'F', '9')">
                                                    <area href="#" id="F10" data-nama="F10" data-state="LPK-0000000107" full="Lapak BLOK F NOMOR 10" shape="poly" coords="510,631,526,634,520,666,504,663"  onclick="lapakPasar('LPK-0000000107', '<?=$KodePasar?>', 'F', '10')">
                                                    <area href="#" id="F11" data-nama="F11" data-state="LPK-0000000108" full="Lapak BLOK F NOMOR 11" shape="poly" coords="495,628,510,631,504,663,489,660"  onclick="lapakPasar('LPK-0000000108', '<?=$KodePasar?>', 'F', '11')">
                                                    <area href="#" id="F12" data-nama="F12" data-state="LPK-0000000109" full="Lapak BLOK F NOMOR 12" shape="poly" coords="482,625,495,628,489,660,476,657"  onclick="lapakPasar('LPK-0000000109', '<?=$KodePasar?>', 'F', '12')">
                                                    <area href="#" id="F13" data-nama="F13" data-state="LPK-0000000110" full="Lapak BLOK F NOMOR 13" shape="poly" coords="469,622,482,625,476,657,463,654"  onclick="lapakPasar('LPK-0000000110', '<?=$KodePasar?>', 'F', '13')">
                                                    <area href="#" id="F14" data-nama="F14" data-state="LPK-0000000111" full="Lapak BLOK F NOMOR 14" shape="poly" coords="456,619,469,622,463,654,450,651"  onclick="lapakPasar('LPK-0000000111', '<?=$KodePasar?>', 'F', '14')">
                                                    <area href="#" id="F15" data-nama="F15" data-state="LPK-0000000112" full="Lapak BLOK F NOMOR 15" shape="poly" coords="443,616,456,619,450,651,437,649"  onclick="lapakPasar('LPK-0000000112', '<?=$KodePasar?>', 'F', '15')">
                                                    <area href="#" id="F16" data-nama="F16" data-state="LPK-0000000113" full="Lapak BLOK F NOMOR 16" shape="poly" coords="430,613,443,616,437,649,424,647"  onclick="lapakPasar('LPK-0000000113', '<?=$KodePasar?>', 'F', '16')">
                                                    <area href="#" id="F17" data-nama="F17" data-state="LPK-0000000114" full="Lapak BLOK F NOMOR 17" shape="poly" coords="417,611,430,613,424,647,411,645"  onclick="lapakPasar('LPK-0000000114', '<?=$KodePasar?>', 'F', '17')">
                                                    <area href="#" id="F18" data-nama="F18" data-state="LPK-0000000115" full="Lapak BLOK F NOMOR 18" shape="poly" coords="404,609,417,611,411,645,398,642"  onclick="lapakPasar('LPK-0000000115', '<?=$KodePasar?>', 'F', '18')">
                                                    <area href="#" id="F19" data-nama="F19" data-state="LPK-0000000116" full="Lapak BLOK F NOMOR 19" shape="poly" coords="391,606,404,609,398,642,385,638"  onclick="lapakPasar('LPK-0000000116', '<?=$KodePasar?>', 'F', '19')">
                                                    <area href="#" id="F20" data-nama="F20" data-state="LPK-0000000117" full="Lapak BLOK F NOMOR 20" shape="poly" coords="378,602,391,606,385,638,372,634"  onclick="lapakPasar('LPK-0000000117', '<?=$KodePasar?>', 'F', '20')">
                                                    <area href="#" id="F21" data-nama="F21" data-state="LPK-0000000118" full="Lapak BLOK F NOMOR 21" shape="poly" coords="365,598,378,602,372,634,359,630"  onclick="lapakPasar('LPK-0000000118', '<?=$KodePasar?>', 'F', '21')">
                                                    <area href="#" id="F22" data-nama="F22" data-state="LPK-0000000119" full="Lapak BLOK F NOMOR 22" shape="poly" coords="352,594,365,598,359,630,346,626"  onclick="lapakPasar('LPK-0000000119', '<?=$KodePasar?>', 'F', '22')"> 
                                                    <area href="#" id="F23" data-nama="F23" data-state="LPK-0000000120" full="Lapak BLOK F NOMOR 23" shape="poly" coords="339,591,352,594,346,626,333,622"  onclick="lapakPasar('LPK-0000000120', '<?=$KodePasar?>', 'F', '23')">
                                                    <area href="#" id="F24" data-nama="F24" data-state="LPK-0000000121" full="Lapak BLOK F NOMOR 24" shape="poly" coords="323,589,339,591,333,622,319,620"  onclick="lapakPasar('LPK-0000000121', '<?=$KodePasar?>', 'F', '24')">

                                                    <!--Blok E -->
                                                    <area href="#" id="E9" data-nama="E9" data-state="LPK-0000000095" full="Lapak BLOK E NOMOR 9" shape="poly" coords="312,474,320,474,321,468,343,472,342,491,310,487"  onclick="lapakPasar('LPK-0000000095', '<?=$KodePasar?>', 'E', '9')">
                                                    <area href="#" id="E8" data-nama="E8" data-state="LPK-0000000094" full="Lapak BLOK E NOMOR 8" shape="poly" coords="310,487,342,491,341,505,308,501"  onclick="lapakPasar('LPK-0000000094', '<?=$KodePasar?>', 'E', '8')">
                                                    <area href="#" id="E7" data-nama="E7" data-state="LPK-0000000093" full="Lapak BLOK E NOMOR 7" shape="poly" coords="308,501,341,505,340,520,306,516"  onclick="lapakPasar('LPK-0000000093', '<?=$KodePasar?>', 'E', '7')">
                                                    <area href="#" id="E6" data-nama="E6" data-state="LPK-0000000092" full="Lapak BLOK E NOMOR 6" shape="poly" coords="306,516,346,520,345,534,304,530"  onclick="lapakPasar('LPK-0000000092', '<?=$KodePasar?>', 'E', '6')">
                                                    <area href="#" id="E5" data-nama="E5" data-state="LPK-0000000091" full="Lapak BLOK E NOMOR 5" shape="poly" coords="304,530,345,534,343,548,302,544"  onclick="lapakPasar('LPK-0000000091', '<?=$KodePasar?>', 'E', '5')">
                                                    <area href="#" id="E4" data-nama="E4" data-state="LPK-0000000090" full="Lapak BLOK E NOMOR 4" shape="poly" coords="302,544,343,548,341,562,300,558"  onclick="lapakPasar('LPK-0000000090', '<?=$KodePasar?>', 'E', '4')">
                                                    <area href="#" id="E3" data-nama="E3" data-state="LPK-0000000089" full="Lapak BLOK E NOMOR 3" shape="poly" coords="300,558,341,562,340,576,298,572"  onclick="lapakPasar('LPK-0000000089', '<?=$KodePasar?>', 'E', '3')">
                                                    <area href="#" id="E2" data-nama="E2" data-state="LPK-0000000088" full="Lapak BLOK E NOMOR 2" shape="poly" coords="298,572,340,576,338,590,296,586"  onclick="lapakPasar('LPK-0000000088', '<?=$KodePasar?>', 'E', '2')">

                                                    <!--Blok A 13 SAMPAI 24 -->
                                                    <area href="#" id="A13" data-nama="A13" data-state="LPK-0000000013" full="Lapak BLOK A NOMOR 13" shape="poly" coords="159,436,182,437,182,447,192,447,191,467,158,466"  onclick="lapakPasar('LPK-0000000013', '<?=$KodePasar?>', 'B', '13')">
                                                    <area href="#" id="A14" data-nama="A14" data-state="LPK-0000000014" full="Lapak BLOK A NOMOR 14" shape="poly" coords="158,466,191,468,189,487,158,485"  onclick="lapakPasar('LPK-0000000014', '<?=$KodePasar?>', 'A', '14')">
                                                    <area href="#" id="A15" data-nama="A15" data-state="LPK-0000000015" full="Lapak BLOK A NOMOR 15" shape="poly" coords="158,485,189,487,188,507,157,505"  onclick="lapakPasar('LPK-0000000015', '<?=$KodePasar?>', 'A', '15')">
                                                    <area href="#" id="A16" data-nama="A16" data-state="LPK-0000000016" full="Lapak BLOK A NOMOR 16" shape="poly" coords="157,505,188,507,187,527,156,525"  onclick="lapakPasar('LPK-0000000016', '<?=$KodePasar?>', 'A', '16')">
                                                    <area href="#" id="A17" data-nama="A17" data-state="LPK-0000000017" full="Lapak BLOK A NOMOR 17" shape="poly" coords="156,525,187,527,186,547,155,545"  onclick="lapakPasar('LPK-0000000017', '<?=$KodePasar?>', 'A', '17')">
                                                    <area href="#" id="A18" data-nama="A18" data-state="LPK-0000000018" full="Lapak BLOK A NOMOR 18" shape="poly" coords="155,545,186,547,185,567,154,565"  onclick="lapakPasar('LPK-0000000018', '<?=$KodePasar?>', 'A', '18')">
                                                    <area href="#" id="A19" data-nama="A19" data-state="LPK-0000000019" full="Lapak BLOK A NOMOR 19" shape="poly" coords="154,565,185,567,184,587,153,585"  onclick="lapakPasar('LPK-0000000019', '<?=$KodePasar?>', 'A', '19')">
                                                    <area href="#" id="A20" data-nama="A20" data-state="LPK-0000000020" full="Lapak BLOK A NOMOR 20" shape="poly" coords="153,585,184,587,183,607,152,605"  onclick="lapakPasar('LPK-0000000020', '<?=$KodePasar?>', 'A', '20')">
                                                    <area href="#" id="A21" data-nama="A21" data-state="LPK-0000000021" full="Lapak BLOK A NOMOR 21" shape="poly" coords="152,605,183,607,182,627,151,625"  onclick="lapakPasar('LPK-0000000021', '<?=$KodePasar?>', 'A', '21')">
                                                    <area href="#" id="A22" data-nama="A657" data-state="LPK-0000000022" full="Lapak BLOK A NOMOR 22" shape="poly" coords="151,625,182,627,181,647,150,645"  onclick="lapakPasar('LPK-0000000022', '<?=$KodePasar?>', 'A', '22')">
                                                    <area href="#" id="A23" data-nama="A23" data-state="LPK-0000000023" full="Lapak BLOK A NOMOR 23" shape="poly" coords="150,645,181,647,180,667,149,665"  onclick="lapakPasar('LPK-0000000023', '<?=$KodePasar?>', 'A', '23')">
                                                    <area href="#" id="A24" data-nama="A24" data-state="LPK-0000000024" full="Lapak BLOK A NOMOR 24" shape="poly" coords="149,665,180,667,179,686,170,685,170,692,149,690"  onclick="lapakPasar('LPK-0000000024', '<?=$KodePasar?>', 'B', '24')">

                                                    <!--Blok B 12 SAMPAI 1 -->
                                                    <area href="#" id="A12" data-nama="A12" data-state="LPK-0000000012" full="Lapak BLOK A NOMOR 12" shape="poly" coords="133,434,159,436,158,466,126,464,126,444,133,444, "  onclick="lapakPasar('LPK-0000000012', '<?=$KodePasar?>', 'A', '12')">
                                                    <area href="#" id="A11" data-nama="A11" data-state="LPK-0000000011" full="Lapak BLOK A NOMOR 11" shape="poly" coords="126,464,159,465,158,485,125,483"  onclick="lapakPasar('LPK-0000000011', '<?=$KodePasar?>', 'A', '11')">
                                                    <area href="#" id="A10" data-nama="A10" data-state="LPK-0000000010" full="Lapak BLOK A NOMOR 10" shape="poly" coords="125,483,158,485,157,505,124,503"  onclick="lapakPasar('LPK-0000000010', '<?=$KodePasar?>', 'A', '631')">
                                                    <area href="#" id="A9" data-nama="A9" data-state="LPK-0000000009" full="Lapak BLOK A NOMOR 9" shape="poly" coords="124,503,157,505,156,525,123,523"  onclick="lapakPasar('LPK-0000000009', '<?=$KodePasar?>', 'A', '9')">
                                                    <area href="#" id="A8" data-nama="A8" data-state="LPK-0000000008" full="Lapak BLOK A NOMOR 8" shape="poly" coords="123,523,156,525,155,545,122,543"  onclick="lapakPasar('LPK-0000000008', '<?=$KodePasar?>', 'A', '8')">
                                                    <area href="#" id="A7" data-nama="A7" data-state="LPK-0000000007" full="Lapak BLOK A NOMOR 7" shape="poly" coords="122,543,155,545,154,565,121,563"  onclick="lapakPasar('LPK-0000000007', '<?=$KodePasar?>', 'A', '7')">
                                                    <area href="#" id="A6" data-nama="A6" data-state="LPK-0000000006" full="Lapak BLOK A NOMOR 6" shape="poly" coords="121,563,154,565,153,585,120,583"  onclick="lapakPasar('LPK-0000000006', '<?=$KodePasar?>', 'A', '6')">
                                                    <area href="#" id="A5" data-nama="A5" data-state="LPK-0000000005" full="Lapak BLOK A NOMOR 5" shape="poly" coords="120,583,153,585,152,605,119,603"  onclick="lapakPasar('LPK-0000000005', '<?=$KodePasar?>', 'A', '5')">
                                                    <area href="#" id="A4" data-nama="A4" data-state="LPK-0000000004" full="Lapak BLOK A NOMOR 4" shape="poly" coords="119,603,152,605,151,625,118,623"  onclick="lapakPasar('LPK-0000000004', '<?=$KodePasar?>', 'A', '4')">
                                                    <area href="#" id="A3" data-nama="A3" data-state="LPK-0000000003" full="Lapak BLOK A NOMOR 3" shape="poly" coords="118,623,151,625,150,645,117,643"  onclick="lapakPasar('LPK-0000000003', '<?=$KodePasar?>', 'A', '3')">
                                                    <area href="#" id="A2" data-nama="A2" data-state="LPK-0000000002" full="Lapak BLOK A NOMOR 2" shape="poly" coords="117,643,150,645,149,665,116,663"  onclick="lapakPasar('LPK-0000000002', '<?=$KodePasar?>', 'A', '2')">
                                                    <area href="#" id="A1" data-nama="A1" data-state="LPK-0000000001" full="Lapak BLOK A NOMOR 1" shape="poly" coords="116,663,149,665,148,690,125,688,125,682,115,681"  onclick="lapakPasar('LPK-0000000001', '<?=$KodePasar?>', 'A', '1')">

                                                    <!--Blok H -->
                                                    <area href="#" id="H1" data-nama="H1" data-state="LPK-0000000190" full="Lapak BLOK H NOMOR 1" shape="poly" coords="78,771,82,743,86,743,88,737,106,740,99,775"  onclick="lapakPasar('LPK-0000000190', '<?=$KodePasar?>', 'H', '1')">
                                                    <area href="#" id="H2" data-nama="H2" data-state="LPK-0000000191" full="Lapak BLOK H NOMOR 2" shape="poly" coords="99,775,107,740,125,743,119,779"  onclick="lapakPasar('LPK-0000000191', '<?=$KodePasar?>', 'H', '2')">
                                                    <area href="#" id="H3" data-nama="H3" data-state="LPK-0000000192" full="Lapak BLOK H NOMOR 3" shape="poly" coords="119,779,126,743,145,747,139,782"  onclick="lapakPasar('LPK-0000000192', '<?=$KodePasar?>', 'H', '3')">
                                                    <area href="#" id="H4" data-nama="H4" data-state="LPK-0000000193" full="Lapak BLOK H NOMOR 4" shape="poly" coords="139,782,146,747,165,750,159,785"  onclick="lapakPasar('LPK-0000000193', '<?=$KodePasar?>', 'H', '4')">
                                                    <area href="#" id="H5" data-nama="H5" data-state="LPK-0000000194" full="Lapak BLOK H NOMOR 5" shape="poly" coords="159,785,166,750,185,753,179,789"  onclick="lapakPasar('LPK-0000000194', '<?=$KodePasar?>', 'H', '5')">
                                                    <area href="#" id="H6" data-nama="H6" data-state="LPK-0000000195" full="Lapak BLOK H NOMOR 6" shape="poly" coords="179,789,186,753,205,756,199,793"  onclick="lapakPasar('LPK-0000000195', '<?=$KodePasar?>', 'H', '6')">
                                                    <area href="#" id="H7" data-nama="H7" data-state="LPK-0000000196" full="Lapak BLOK H NOMOR 7" shape="poly" coords="199,793,206,756,225,759,219,796"  onclick="lapakPasar('LPK-0000000196', '<?=$KodePasar?>', 'H', '7')">
                                                    <area href="#" id="H8" data-nama="H8" data-state="LPK-0000000197" full="Lapak BLOK H NOMOR 8" shape="poly" coords="219,796,226,759,245,762,239,799"  onclick="lapakPasar('LPK-0000000197', '<?=$KodePasar?>', 'H', '8')">
                                                    <area href="#" id="H9" data-nama="H9" data-state="LPK-0000000198" full="Lapak BLOK H NOMOR 9" shape="poly" coords="239,799,246,762,257,764,256,774,265,776,260,803"  onclick="lapakPasar('LPK-0000000198', '<?=$KodePasar?>', 'H', '9')">

                                                     <!-- Fasilitas Umum -->
                                                    <area href="#" id="HPL" data-nama="" data-state="Fasum" full="Fasilitas Umum" shape="circle" coords="240, 690, 35">
                                                    <!-- Kotak sebelah Lingkaran -->
                                                    <area href="#" id="ZK" data-nama="" data-state="Fasum1" full="Kantor" shape="poly" coords="280,690,305,695,298,720,272,715">


                                                    <!-- Kantor -->
                                                    <area href="#" id="ZK" data-nama="" data-state="Kantor" full="Kantor" shape="poly" coords="224,447,255,449,253,479,221,478 ">

                                                    <!-- PASAR TENGAH -->
                                                    <area href="#" id="ZK" data-nama="" data-state="ZK" full="Pasar Tengah" shape="poly" coords="346,683,582,739,578,746,576,746,542,896,312,840" onclick="pindahPasar()">

                                                    <!--HALAMAN PARKIR LUAR PASAR TENGAH -->
                                                    <area href="#" id="ZK" data-nama="" data-state="HPL4" full="HALAMAN PARKIR 4" shape="poly" coords="632,700,685,712,668,789,621,776">
                                                    <area href="#" id="ZK" data-nama="" data-state="HPL7" full="HALAMAN PARKIR 7" shape="poly" coords="612,695,632,700,621,776,596,768,600,738,605,738">
                                                    <area href="#" id="ZK" data-nama="" data-state="HPL5" full="HALAMAN PARKIR 5" shape="poly" coords="588,691,612,695,605,738,600,738,596,768,573,761,576,745,579,745,585,735">
                                                    <area href="#" id="ZK" data-nama="" data-state="HPL2" full="HALAMAN PARKIR 2" shape="poly" coords="573,761,668,789,656,841,647,854,558,824">
                                                    <area href="#" id="ZK" data-nama="" data-state="HPL6" full="HALAMAN PARKIR 6" shape="poly" coords="558,824,647,854,634,924,590,912,543,896">

                                                    <!-- HALAMAN PARKIR LUAR-->
                                                    <area href="#" id="HPL" data-nama="" data-state="HPL3" full="HPL" shape="poly" coords="243,800,267,804,258,854,233,849">

                                                    <!--Blok B 1 sampai 13 -->
                                                    <area href="#" id="B13" data-nama="B13" data-state="LPK-0000000037" full="Lapak BLOK B NOMOR 13" shape="poly" coords="155,143,156,139,180,140,178,160,148,158,149,143"  onclick="lapakPasar('LPK-0000000037', '<?=$KodePasar?>', 'B', '13')">
                                                    <area href="#" id="B12" data-nama="B12" data-state="LPK-0000000036" full="Lapak BLOK B NOMOR 12" shape="poly" coords="148,159,178,160,177,178,147,177"  onclick="lapakPasar('LPK-0000000036', '<?=$KodePasar?>', 'B', '12')">
                                                    <area href="#" id="B11" data-nama="B11" data-state="LPK-0000000035" full="Lapak BLOK B NOMOR 11" shape="poly" coords="147,177,177,178,176,196,146,195"  onclick="lapakPasar('LPK-0000000035', '<?=$KodePasar?>', 'B', '11')">
                                                    <area href="#" id="B10" data-nama="B10" data-state="LPK-0000000034" full="Lapak BLOK B NOMOR 10" shape="poly" coords="146,195,176,196,175,214,145,213 "  onclick="lapakPasar('LPK-0000000034', '<?=$KodePasar?>', 'B', '10')">
                                                    <area href="#" id="B9" data-nama="B9" data-state="LPK-0000000033" full="Lapak BLOK B NOMOR 9" shape="poly" coords="145,213,175,214,174,232,144,231"  onclick="lapakPasar('LPK-0000000033', '<?=$KodePasar?>', 'B', '9')">
                                                    <area href="#" id="B8" data-nama="B8" data-state="LPK-0000000032" full="Lapak BLOK B NOMOR 8" shape="poly" coords="144,231,174,232,173,250,143,249"  onclick="lapakPasar('LPK-0000000032', '<?=$KodePasar?>', 'B', '8')">
                                                    <area href="#" id="B7" data-nama="B7" data-state="LPK-0000000031" full="Lapak BLOK B NOMOR 7" shape="poly" coords="143,249,173,250,172,268,142,267"  onclick="lapakPasar('LPK-0000000031', '<?=$KodePasar?>', 'B', '7')">
                                                    <area href="#" id="B6" data-nama="B6" data-state="LPK-0000000030" full="Lapak BLOK B NOMOR 6" shape="poly" coords="142,267,172,268,171,286,141,285"  onclick="lapakPasar('LPK-0000000030', '<?=$KodePasar?>', 'B', '6')">
                                                    <area href="#" id="B5" data-nama="B5" data-state="LPK-0000000029" full="Lapak BLOK B NOMOR 5" shape="poly" coords="141,285,171,286,170,304,140,303"  onclick="lapakPasar('LPK-0000000029', '<?=$KodePasar?>', 'B', '5')">
                                                    <area href="#" id="B4" data-nama="B4" data-state="LPK-0000000028" full="Lapak BLOK B NOMOR 4" shape="poly" coords="140,303,170,304,169,322,139,321"  onclick="lapakPasar('LPK-0000000028', '<?=$KodePasar?>', 'B', '4')">
                                                    <area href="#" id="B3" data-nama="B3" data-state="LPK-0000000027" full="Lapak BLOK B NOMOR 3" shape="poly" coords="139,321,169,322,168,340,138,339"  onclick="lapakPasar('LPK-0000000027', '<?=$KodePasar?>', 'B', '3')">
                                                    <area href="#" id="B2" data-nama="B2" data-state="LPK-0000000026" full="Lapak BLOK B NOMOR 2" shape="poly" coords="138,339,168,340,167,358,137,357"  onclick="lapakPasar('LPK-0000000026', '<?=$KodePasar?>', 'B', '2')">
                                                    <area href="#" id="B1" data-nama="B1" data-state="LPK-0000000025" full="Lapak BLOK B NOMOR 1" shape="poly" coords="137,357,167,358,166,376,141,375,141,366,136,366"  onclick="lapakPasar('LPK-0000000025', '<?=$KodePasar?>', 'B', '1')">

                                                     <!--Blok B 14 sampai 25 -->
                                                    <area href="#" id="B14" data-nama="B14" data-state="LPK-0000000038" full="Lapak BLOK B NOMOR 14" shape="poly" coords="180,146,199,147,199,156,207,157,206,180,177,178"  onclick="lapakPasar('LPK-0000000038', '<?=$KodePasar?>', 'B', '711')">
                                                    <area href="#" id="B15" data-nama="B15" data-state="LPK-0000000039" full="Lapak BLOK B NOMOR 15" shape="poly" coords="177,178,206,180,205,198,176,196"  onclick="lapakPasar('LPK-0000000039', '<?=$KodePasar?>', 'B', '15')">
                                                    <area href="#" id="B16" data-nama="B16" data-state="LPK-0000000040" full="Lapak BLOK B NOMOR 16" shape="poly" coords="176,196,205,198,204,216,175,214"  onclick="lapakPasar('LPK-0000000040', '<?=$KodePasar?>', 'B', '16')">
                                                    <area href="#" id="B17" data-nama="B17" data-state="LPK-0000000041" full="Lapak BLOK B NOMOR 17" shape="poly" coords="175,214,204,216,203,234,174,232"  onclick="lapakPasar('LPK-0000000041', '<?=$KodePasar?>', 'B', '17')">
                                                    <area href="#" id="B18" data-nama="B18" data-state="LPK-0000000042" full="Lapak BLOK B NOMOR 18" shape="poly" coords="174,232,203,234,202,252,173,250"  onclick="lapakPasar('LPK-0000000042', '<?=$KodePasar?>', 'B', '18')">
                                                    <area href="#" id="B19" data-nama="B19" data-state="LPK-0000000043" full="Lapak BLOK B NOMOR 19" shape="poly" coords="173,250,202,252,201,270,172,268"  onclick="lapakPasar('LPK-0000000043', '<?=$KodePasar?>', 'B', '19')">
                                                    <area href="#" id="B20" data-nama="B20" data-state="LPK-0000000044" full="Lapak BLOK B NOMOR 20" shape="poly" coords="172,268,201,270,200,288,171,286"  onclick="lapakPasar('LPK-0000000044', '<?=$KodePasar?>', 'B', '20')">
                                                    <area href="#" id="B21" data-nama="B21" data-state="LPK-0000000045" full="Lapak BLOK B NOMOR 21" shape="poly" coords="171,286,200,288,199,306,170,304"  onclick="lapakPasar('LPK-0000000045', '<?=$KodePasar?>', 'B', '21')">
                                                    <area href="#" id="B22" data-nama="B22" data-state="LPK-0000000046" full="Lapak BLOK B NOMOR 22" shape="poly" coords="170,304,199,306,198,324,169,322"  onclick="lapakPasar('LPK-0000000046', '<?=$KodePasar?>', 'B', '22')">
                                                    <area href="#" id="B23" data-nama="B23" data-state="LPK-0000000047" full="Lapak BLOK B NOMOR 23" shape="poly" coords="169,322,198,324,197,342,168,340"  onclick="lapakPasar('LPK-0000000047', '<?=$KodePasar?>', 'B', '23')">
                                                    <area href="#" id="B24" data-nama="B24" data-state="LPK-0000000048" full="Lapak BLOK B NOMOR 24" shape="poly" coords="168,340,197,342,196,360,167,358"  onclick="lapakPasar('LPK-0000000048', '<?=$KodePasar?>', 'B', '24')">
                                                    <area href="#" id="B25" data-nama="B25" data-state="LPK-0000000049" full="Lapak BLOK B NOMOR 25" shape="poly" coords="167,358,196,360,196,368,191,368,191,378,167,377  "  onclick="lapakPasar('LPK-0000000049', '<?=$KodePasar?>', 'B', '25')">

                                                    <!--Blok B 971 sampai 960 -->
                                                    <area href="#" id="C12" data-nama="C12" data-state="LPK-0000000061" full="Lapak BLOK C NOMOR 12" shape="poly" coords="250,169,255,169,255,165,275,166,274,184,249,183"  onclick="lapakPasar('LPK-0000000061', '<?=$KodePasar?>', 'B', '12')">
                                                    <area href="#" id="C11" data-nama="C11" data-state="LPK-0000000060" full="Lapak BLOK C NOMOR 11" shape="poly" coords="249,183,274,184,273,201,248,200 "  onclick="lapakPasar('LPK-0000000060', '<?=$KodePasar?>', 'C', '11')">
                                                    <area href="#" id="C10" data-nama="C10" data-state="LPK-0000000059" full="Lapak BLOK C NOMOR 10" shape="poly" coords="248,200,273,201,272,219,247,218"  onclick="lapakPasar('LPK-0000000059', '<?=$KodePasar?>', 'C', '10')">
                                                    <area href="#" id="C9" data-nama="C9" data-state="LPK-0000000058" full="Lapak BLOK C NOMOR 9" shape="poly" coords="247,218,272,219,271,237,246,236"  onclick="lapakPasar('LPK-0000000058', '<?=$KodePasar?>', 'C', '9')">
                                                    <area href="#" id="C8" data-nama="C8" data-state="LPK-0000000057" full="Lapak BLOK C NOMOR 8" shape="poly" coords="246,236,271,237,270,255,245,254"  onclick="lapakPasar('LPK-0000000057', '<?=$KodePasar?>', 'C', '8')">
                                                    <area href="#" id="C7" data-nama="C7" data-state="LPK-0000000056" full="Lapak BLOK C NOMOR 7" shape="poly" coords="245,254,270,255,269,273,244,272"  onclick="lapakPasar('LPK-0000000056', '<?=$KodePasar?>', 'C', '7')">
                                                    <area href="#" id="C6" data-nama="C6" data-state="LPK-0000000055" full="Lapak BLOK C NOMOR 6" shape="poly" coords="244,272,269,273,268,291,243,290"  onclick="lapakPasar('LPK-0000000055', '<?=$KodePasar?>', 'C', '6')">
                                                    <area href="#" id="C5" data-nama="C5" data-state="LPK-0000000054" full="Lapak BLOK C NOMOR 5" shape="poly" coords="243,290,268,291,267,309,242,308"  onclick="lapakPasar('LPK-0000000054', '<?=$KodePasar?>', 'C', '5')">
                                                    <area href="#" id="C4" data-nama="C4" data-state="LPK-0000000053" full="Lapak BLOK C NOMOR 4" shape="poly" coords="242,308,267,309,266,327,241,326"  onclick="lapakPasar('LPK-0000000053', '<?=$KodePasar?>', 'C', '4')">
                                                    <area href="#" id="C3" data-nama="C3" data-state="LPK-0000000052" full="Lapak BLOK C NOMOR 3" shape="poly" coords="241,326,266,327,265,345,240,344"  onclick="lapakPasar('LPK-0000000052', '<?=$KodePasar?>', 'C', '3')">
                                                    <area href="#" id="C2" data-nama="C2" data-state="LPK-0000000051" full="Lapak BLOK C NOMOR 2" shape="poly" coords="240,344,265,345,264,363,239,362"  onclick="lapakPasar('LPK-0000000051', '<?=$KodePasar?>', 'C', '2')">
                                                    <area href="#" id="C1" data-nama="C1" data-state="LPK-0000000050" full="Lapak BLOK C NOMOR 1" shape="poly" coords="239,362,264,363,263,381,242,380,242,372,238,372"  onclick="lapakPasar('LPK-0000000050', '<?=$KodePasar?>', 'B', '1')">

                                                    <!--Blok C 972 sampai 982 -->
                                                    <area href="#" id="C13" data-nama="C13" data-state="LPK-0000000062" full="Lapak BLOK C NOMOR 972" shape="poly" coords="275,171,287,172,287,177,292,177,292,183,297,183,297,202,273,201"  onclick="lapakPasar('LPK-0000000062', '<?=$KodePasar?>', 'C', '972')">
                                                    <area href="#" id="C14" data-nama="C14" data-state="LPK-0000000063" full="Lapak BLOK C NOMOR 973" shape="poly" coords="273,201,297,202,296,220,272,219"  onclick="lapakPasar('LPK-0000000063', '<?=$KodePasar?>', 'C', '973')">
                                                    <area href="#" id="C15" data-nama="C15" data-state="LPK-0000000064" full="Lapak BLOK C NOMOR 974" shape="poly" coords="272,219,296,220,295,238,271,237"  onclick="lapakPasar('LPK-0000000064', '<?=$KodePasar?>', 'C', '974')">
                                                    <area href="#" id="C16" data-nama="C16" data-state="LPK-0000000065" full="Lapak BLOK C NOMOR 975" shape="poly" coords="271,237,295,238,294,256,270,255"  onclick="lapakPasar('LPK-0000000065', '<?=$KodePasar?>', 'C', '975')">
                                                    <area href="#" id="C17" data-nama="C17" data-state="LPK-0000000066" full="Lapak BLOK C NOMOR 976" shape="poly" coords="270,255,294,256,293,274,269,273"  onclick="lapakPasar('LPK-0000000066', '<?=$KodePasar?>', 'C', '976')">
                                                    <area href="#" id="C18" data-nama="C18" data-state="LPK-0000000067" full="Lapak BLOK C NOMOR 977" shape="poly" coords="269,273,293,274,292,292,268,291"  onclick="lapakPasar('LPK-0000000067', '<?=$KodePasar?>', 'C', '977')">
                                                    <area href="#" id="C19" data-nama="C19" data-state="LPK-0000000068" full="Lapak BLOK C NOMOR 978" shape="poly" coords="268,291,292,292,291,310,267,309"  onclick="lapakPasar('LPK-0000000068', '<?=$KodePasar?>', 'C', '978')">
                                                    <area href="#" id="C20" data-nama="C20" data-state="LPK-0000000069" full="Lapak BLOK C NOMOR 979" shape="poly" coords="267,309,291,310,290,328,266,327"  onclick="lapakPasar('LPK-0000000069', '<?=$KodePasar?>', 'C', '979')">
                                                    <area href="#" id="C21" data-nama="C21" data-state="LPK-0000000070" full="Lapak BLOK C NOMOR 980" shape="poly" coords="266,327,290,328,289,346,265,345"  onclick="lapakPasar('LPK-0000000070', '<?=$KodePasar?>', 'C', '980')">
                                                    <area href="#" id="C22" data-nama="C22" data-state="LPK-0000000071" full="Lapak BLOK C NOMOR 981" shape="poly" coords="265,345,289,346,288,364,264,363"  onclick="lapakPasar('LPK-0000000071', '<?=$KodePasar?>', 'C', '981')">
                                                    <area href="#" id="C23" data-nama="C23" data-state="LPK-0000000072" full="Lapak BLOK BC NOMOR 982" shape="poly" coords="264,363,288,364,287,376,281,376,281,382,263,381"  onclick="lapakPasar('LPK-0000000072', '<?=$KodePasar?>', 'C', '982')">

                                                    <!-- Blok D 1 Sampai 14 -->
                                                    <area href="#" id="D13" data-nama="D13" data-state="LPK-0000000085" full="Lapak BLOK D NOMOR 13" shape="poly" coords="346,198,389,203,384,225,343,221"  onclick="lapakPasar('LPK-0000000085', '<?=$KodePasar?>', 'D', '13')">
                                                    <area href="#" id="D12" data-nama="D12" data-state="LPK-0000000084" full="Lapak BLOK D NOMOR 12" shape="poly" coords="343,221,384,225,380,240,341,236"  onclick="lapakPasar('LPK-0000000084', '<?=$KodePasar?>', 'B', '12')">
                                                    <area href="#" id="D11" data-nama="D11" data-state="LPK-0000000083" full="Lapak BLOK D NOMOR 11" shape="poly" coords="341,236,380,240,377,254,339,250"  onclick="lapakPasar('LPK-0000000083', '<?=$KodePasar?>', 'B', '11')">
                                                    <area href="#" id="D10" data-nama="D10" data-state="LPK-0000000082" full="Lapak BLOK D NOMOR 10" shape="poly" coords="339,250,377,254,374,268,337,264"  onclick="lapakPasar('LPK-0000000082', '<?=$KodePasar?>', 'B', '10')">
                                                    <area href="#" id="D9" data-nama="D9" data-state="LPK-0000000081" full="Lapak BLOK D NOMOR 9" shape="poly" coords="337,264,374,268,371,282,335,278"  onclick="lapakPasar('LPK-0000000081', '<?=$KodePasar?>', 'B', '9')">
                                                    <area href="#" id="D8" data-nama="D8" data-state="LPK-0000000080" full="Lapak BLOK D NOMOR 8" shape="poly" coords="335,278,371,282,368,296,333,292"  onclick="lapakPasar('LPK-0000000080', '<?=$KodePasar?>', 'B', '8')">
                                                    <area href="#" id="D7" data-nama="D7" data-state="LPK-0000000079" full="Lapak BLOK D NOMOR 7" shape="poly" coords="333,292,368,296,365,310,331,306"  onclick="lapakPasar('LPK-0000000079', '<?=$KodePasar?>', 'B', '7')">
                                                    <area href="#" id="D6" data-nama="D6" data-state="LPK-0000000078" full="Lapak BLOK D NOMOR 6" shape="poly" coords="331,306,365,310,362,324,329,320"  onclick="lapakPasar('LPK-0000000078', '<?=$KodePasar?>', 'B', '6')">
                                                    <area href="#" id="D5" data-nama="D5" data-state="LPK-0000000077" full="Lapak BLOK D NOMOR 5" shape="poly" coords="329,320,361,324,358,338,327,334"  onclick="lapakPasar('LPK-0000000077', '<?=$KodePasar?>', 'B', '5')">
                                                    <area href="#" id="D4" data-nama="D4" data-state="LPK-0000000076" full="Lapak BLOK D NOMOR 4" shape="poly" coords="327,334,358,338,355,352,325,348"  onclick="lapakPasar('LPK-0000000076', '<?=$KodePasar?>', 'B', '4')">
                                                    <area href="#" id="D3" data-nama="D3" data-state="LPK-0000000075" full="Lapak BLOK D NOMOR 3" shape="poly" coords="325,348,355,352,352,366,323,362"  onclick="lapakPasar('LPK-0000000075', '<?=$KodePasar?>', 'B', '3')">
                                                    <area href="#" id="D2" data-nama="D2" data-state="LPK-0000000074" full="Lapak BLOK D NOMOR 2" shape="poly" coords="323,362,352,366,348,380,321,376"  onclick="lapakPasar('LPK-0000000074', '<?=$KodePasar?>', 'D', '2')">
                                                    <area href="#" id="D1" data-nama="D1" data-state="LPK-0000000073" full="Lapak BLOK D NOMOR 1" shape="poly" coords="321,376,349,380,342,408,318,406"  onclick="lapakPasar('LPK-0000000073', '<?=$KodePasar?>', 'D', '1')">
                                                    

                                                    <!-- Lapak E 10 SAMPAI 11 -->
                                                    <area href="#" id="E11" data-nama="E11" data-state="LPK-0000000097" full="Lapak BLOK E NOMOR 11" shape="poly" coords="334,408,349,409,376,419,372,446,328,442 "  onclick="lapakPasar('LPK-0000000097', '<?=$KodePasar?>', 'E', '11')">
                                                    <area href="#" id="E10" data-nama="E10" data-state="LPK-0000000096" full="Lapak BLOK E NOMOR 10" shape="poly" coords="328,442,372,446,368,474,321,469"  onclick="lapakPasar('LPK-0000000096', '<?=$KodePasar?>', 'B', '10')">
                                                   
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

        function pindahPasar() {
             location.href = "MapCitraNiagaDalam.php";
        }

        function lapakPasar(kodeLapak, kodepasar) {
            var namaPasar = "CitraNiaga";
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
        //         var num = txt.substr(0, 1);
        //         if(num == 'D'){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:9pt"';
        //         } else if (num == 903 || num == 711){
        //             var left=parseFloat(coorA[0])+21;
        //             var top=parseFloat(coorA[1])+80;
        //             var css ='style="font-size:8pt"';
        //         } else if (num == 671){
        //             var left=parseFloat(coorA[0])+28;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:8pt"';
        //         } else if (num == 683){
        //             var left=parseFloat(coorA[0])+18;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:8pt"';
        //         } else if(num >= 672 && num <= 682){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:8pt"';
        //         } else if(num >= 712 && num <= 722){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:8pt"';
        //         } else if(num >= 960 && num <= 971){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:7pt"';
        //         } else if(num >= 973 && num <= 982){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:7pt"';
        //         } else if(num == 972){
        //             var left=parseFloat(coorA[0])+21;
        //             var top=parseFloat(coorA[1])+85;
        //             var css ='style="font-size:7pt"';
        //         } else if(num == 'C'){
        //             if(txt == 'C13'){
        //                 var left=parseFloat(coorA[0])+24;
        //                 var top=parseFloat(coorA[1])+90;
        //                 var css ='style="font-size:9pt"';
        //             } else {
        //                 var left=parseFloat(coorA[0])+24;
        //                 var top=parseFloat(coorA[1])+73;
        //                 var css ='style="font-size:9pt"';
        //             }
        //         } else if(num == 'B'){
        //             if(txt == 'B14'){
        //                 var left=parseFloat(coorA[0])+24;
        //                 var top=parseFloat(coorA[1])+90;
        //                 var css ='style="font-size:9pt"';
        //             } else {
        //                 var left=parseFloat(coorA[0])+24;
        //                 var top=parseFloat(coorA[1])+73;
        //                 var css ='style="font-size:9pt"';
        //             }
        //         } else if(num == 'A'){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+78;
        //             var css ='style="font-size:9pt"';
        //         } else if(num >= 662 && num <= 670){
        //             var left=parseFloat(coorA[0])+24;
        //             var top=parseFloat(coorA[1])+45;
        //             var css ='style="font-size:7pt;  writing-mode: vertical-rl"';
        //         } else if(num == 881){
        //             var left=parseFloat(coorA[0])+26;
        //             var top=parseFloat(coorA[1])+95;
        //             var css ='style="font-size:9pt"';
        //         } else if(num >= 882 && num <= 889){
        //             var left=parseFloat(coorA[0])+23;
        //             var top=parseFloat(coorA[1])+75;
        //             var css ='style="font-size:7pt"';
        //         } else if(num == 'E'){
        //             var left=parseFloat(coorA[0])+22;
        //             var top=parseFloat(coorA[1])+74;
        //             var css ='style="font-size:9pt"';

        //         } else if(num == 'F'){
        //             var left=parseFloat(coorA[0])+18;
        //             var top=parseFloat(coorA[1])+78;
        //             var css ='style="font-size:8pt; writing-mode: vertical-rl"';
        //         }else{
        //             var left=parseFloat(coorA[0])+28;
        //             var top=parseFloat(coorA[1])+60;
        //             var css ='style="font-size:8pt"';
        //         }
                

        //         var $span=$('<span class="map_title" '+css+' onclick="lapakPasar(\'' + idlapak + '\', \'' + pasar + '\')" ><b>'+txt+'<b></span>');
        //         $span.css({top: top+'px', left: left+'px', position:'absolute', cursor: 'pointer', color:'black'});
        //         $span.appendTo('#map_demo');
        //     })
        // });

    </script>
</body>
</html>