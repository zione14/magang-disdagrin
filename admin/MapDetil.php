<?php
include '../admin/akses.php';
$Page = 'index';
include '../library/tgl-indo.php';
$Page = 'Informasi';
$KodePasar = htmlspecialchars(base64_decode($_GET['p']));
$IDLapak   = htmlspecialchars(base64_decode($_GET['l']));
$NamaPasar = htmlspecialchars(base64_decode($_GET['n']));
$sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet, e.NamaPerson, c.IDPerson, c.TanggalTerakhirBayar, d.LokasiFile
  FROM lapakpasar l 
  LEFT JOIN (
    SELECT p.NamaPerson, lp.IDPerson, lp.KodePasar, lp.IDLapak
    FROM lapakperson lp 
    JOIN mstperson p ON lp.IDPerson=p.IDPerson
    WHERE lp.IsAktif=b'1'
  ) e ON e.KodePasar = l.KodePasar AND e.IDLapak = l.IDLapak
  LEFT JOIN (
    SELECT t.NoTransRet, MAX(t.TanggalTrans) as TanggalTerakhirBayar, t.KodePasar, t.IDLapak, t.IDPerson
    FROM trretribusipasar t
    JOIN lapakperson lp ON t.IDLapak=lp.IDLapak AND t.KodePasar=lp.KodePasar
    WHERE lp.IsAktif=b'1'
    GROUP by t.KodePasar, t.IDLapak
  ) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
  LEFT JOIN (
    SELECT d.KodePasar, d.IDLapak, d.JenisDokumen, d.LokasiFile
    FROM dokumenlapak d
    WHERE d.JenisDokumen='FOTO'
  ) d ON d.KodePasar = l.KodePasar AND d.IDLapak = l.IDLapak
  WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$IDLapak' ORDER BY IDLapak ASC";
  $res_lapakpr = $koneksi->query($sql_lapakpr);
  $row_lapakpr = $res_lapakpr->fetch_assoc();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include '../admin/title.php';?>
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
    <?php include '../admin/style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php 
	  include 'header.php'; ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu_lapak.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Lapak Pasar <?=$NamaPasar?></h2>
            </div>
          </header>
		    <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="card-header d-flex align-items-center">
      				  <h3 class="h4">Informasi Lapak Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h3>
      				</div>
  		  		  <div class="row bg-white has-shadow">
                
                <div class="row">
                  <div class="col-lg-4">
                    <img id="image-preview-1" width="100%" height="auto" <?php echo isset($row_lapakpr['LokasiFile']) && $row_lapakpr['LokasiFile'] != '' ? 'src="../images/Dokumen/Pasar/'.$row_lapakpr['LokasiFile'].'"' : 'src="../images/Assets/thumb_noimage.png"'; ?> />
                  </div>
                  <div class="col-lg-8">
                    <div class="table-responsive">  
                      <table class="table table-striped">
                        <thead>
                          <tr>
                          <td>Kode Lapak</td>
                          <td>:</td>
                          <td><?=$IDLapak?></td>
                        </tr>
                        <tr>
                          <td>Nama Lapak</td>
                          <td>:</td>
                          <td>Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></td>
                        </tr>
                        <tr>
                          <td>Nama Pemilik Lapak</td>
                          <td>:</td>
                          <td><?=$row_lapakpr['NamaPerson']?></td>
                        </tr>
                        <tr>
                          <td>Tanggal Terakhir Bayar</td>
                          <td>:</td>
                          <td><?=isset($row_lapakpr['TanggalTerakhirBayar']) && $row_lapakpr['TanggalTerakhirBayar'] != NULL ? TanggalIndo($row_lapakpr['TanggalTerakhirBayar']) : ''?></td>
                        </tr>
                        <tr>
                          <td>Nominal Retribusi</td>
                          <td>:</td>
                          <td><?=number_format($row_lapakpr['Retribusi'])?></td>
                        </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
	
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>
	
  </body>
</html>