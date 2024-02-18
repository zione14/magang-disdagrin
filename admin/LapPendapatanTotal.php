<?php
include 'akses.php';
@$fitur_id = 63;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$menuadmin = isset($JenisLogin) && $JenisLogin == 'RETRIBUSI PASAR' ? 'LaporanRetribusi' : 'Pelaporan';
$Page = $menuadmin;
$SubPage = 'LapPendapatanTotal';
$BulanTahun = isset($_REQUEST['Bulan']) ? mysqli_real_escape_string($koneksi,$_REQUEST['Bulan']) : date('Y-m');
?>
<!DOCTYPE html>
<html>
  <head>
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
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<style>
	.table thead th {
		vertical-align: middle;
		text-align: center;
		
		border: 2px solid #dee2e6;
	}
	td {
		border: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">Laporan Potensi Pendapatan UTTP & Retribusi </h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Potensi Pendapatan UTTP & Retribusi</h3>
							  <div class="offset-lg-7">
								<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-primary">Cetak & Export</span></a>-->
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="LapPendapatanTotal.php">
										<div class="col-lg-7 form-group input-group offset-lg-5">
											<input type="text" name="Bulan" class="form-control" id="time7" value="<?php echo @$BulanTahun; ?>" placeholder="Periode Bulan" required>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>&nbsp;
												
												<a href="../library/html2pdf/cetak/LapPendapatanTotal.php?Bulan=<?=$BulanTahun?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak Laporan</span></a>
												<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-secondary">Cetak & Export</span></a>-->
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama </th>
									  <th>Potensi Pendapatan UTTP Kantor</th>
									  <th>Potensi Pendapatan UTTP Lokasi</th>
									  <th>Potensi Pendapatan Retribusi</th>
									  <th>Realisasi Pendapatan UTTP Kantor</th>
									  <th>Realisasi Pendapatan UTTP Lokasi</th>
									  <th>Realisasi Pendapatan Retribusi</th>
									</tr>
									
								  </thead>
									<?php
										include '../library/pagination1.php';
										$reload = "LapPendapatanTotal.php?pagination=true&Bulan=$BulanTahun";
										$sql = "SELECT a.NamaPasar,a.KodePasar,a.NamaKepalaPasar,c.Jumlah, d.JumlahPotensi, IFNULL(e.RealisasiUTTPLokasi, 0) as RealisasiUTTPLokasi, IFNULL(f.PotensiKantor, 0) as PotensiKantor, IFNULL(f.PotensiLokasi, 0) as PotensiLokasi, IFNULL(g.RealisasiUTTPKantor, 0) as RealisasiUTTPKantor
										from mstpasar a 
										LEFT JOIN (
											SELECT  SUM(Retribusi) as JumlahPotensi,c.KodePasar
											FROM lapakpasar c
											WHERE c.KodePasar=KodePasar
											GROUP BY c.KodePasar
										) d ON d.KodePasar = a.KodePasar
										LEFT JOIN (
											SELECT  SUM(NominalDiterima) as Jumlah,c.KodePasar
											FROM trretribusipasar c
											where (date_format(c.TanggalTrans, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun')
											GROUP BY c.KodePasar
										) c ON c.KodePasar = a.KodePasar
										LEFT JOIN (
											SELECT l.KodeLokasi, l.IDPerson, IFNULL(SUM(i.NominalRetribusi), 0) as RealisasiUTTPLokasi, l.KodePasar
											FROM lokasimilikperson l
											JOIN trtimbanganitem i ON l.KodeLokasi=i.KodeLokasi AND l.IDPerson=i.IDPerson
											JOIN tractiontimbangan t ON i.NoTransaksi=t.NoTransaksi
											WHERE l.KodePasar=KodePasar AND i.HasilAction1 = 'TERA SAH' AND (date_format(t.TglAmbil, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun')
											AND t.JenisTransaksi='TERA DI LOKASI'
											GROUP BY l.KodePasar
										) e ON e.KodePasar = a.KodePasar
										LEFT JOIN (
											SELECT l.KodeLokasi, l.IDPerson, IFNULL(SUM(i.NominalRetribusi), 0) as RealisasiUTTPKantor, l.KodePasar
											FROM lokasimilikperson l
											JOIN trtimbanganitem i ON l.KodeLokasi=i.KodeLokasi AND l.IDPerson=i.IDPerson
											JOIN tractiontimbangan t ON i.NoTransaksi=t.NoTransaksi
											WHERE l.KodePasar=KodePasar AND i.HasilAction1 = 'TERA SAH' AND (date_format(t.TglAmbil, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun')
											AND t.JenisTransaksi='TERA DI KANTOR'
											GROUP BY l.KodePasar
										) g ON g.KodePasar = a.KodePasar
										LEFT JOIN (
											SELECT l.KodePasar, p.IDTimbangan, SUM(d.RetribusiDikantor) as PotensiKantor , SUM(d.RetribusiDiLokasi) as PotensiLokasi
											FROM lokasimilikperson l
											JOIN timbanganperson p ON l.KodeLokasi=p.KodeLokasi AND l.IDPerson=p.IDPerson
											JOIN detilukuran d ON p.KodeTimbangan=d.KodeTimbangan AND p.KodeKelas=d.KodeKelas AND p.KodeUkuran=d.KodeUkuran
											WHERE l.KodePasar=KodePasar 
											GROUP BY l.KodePasar
										) f ON f.KodePasar = a.KodePasar
										GROUP by a.KodePasar ORDER BY a.KodePasar ASC";
										$oke = $koneksi->prepare($sql);
										$oke->execute();										
										$result = $oke->get_result();		
										
										//pagination config start
										$rpp = 20; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										
										<?php
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo @ strtoupper($data['NamaPasar']); ?></strong><br>
												
											</td>
											<td align="right">
												<?php 
													echo number_format($data['PotensiKantor']);
													$PotensiKantor[] = $data['PotensiKantor']; 	
												?>
											</td>
											<td align="right">
												<?php 
													echo number_format($data['PotensiLokasi']);
													$PotensiLokasi[] = $data['PotensiLokasi']; 	
												?>
											</td>
											<td align="right">
												<?php 
													echo number_format($data['JumlahPotensi']);
													$JumlahPotensi[] = $data['JumlahPotensi']; 	
												?>
											</td>
											<td align="right">
												<?php 
													echo number_format($data['RealisasiUTTPKantor']);
													$RealisasiUTTPKantor[] = $data['RealisasiUTTPKantor']; 	
												?>
											</td>
											<td align="right">
												<?php 
													echo number_format($data['RealisasiUTTPLokasi']);
													$RealisasiUTTPLokasi[] = $data['RealisasiUTTPLokasi']; 	
												?>
											</td>
											<td align="right">
												<?php 
													echo number_format($data['Jumlah']);
													$Jumlah[] = $data['Jumlah']; 	
												?>
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										
										if($tcount==0){
											echo '
											<tr>
												<td colspan="4" align="center">
													<strong>Data Tidak Ada</strong>
												</td>
											</tr>
											';
										}else{
											echo '
											<tr style="background-color: #9e9999;">
												<td colspan="2" align="left" style="color:white"><strong>JUMLAH </strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($PotensiKantor),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($PotensiLokasi),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($JumlahPotensi),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($RealisasiUTTPKantor),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($RealisasiUTTPLokasi),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($Jumlah),0,',','.').'</strong></td>
											</tr>
											<tr style="background-color: #9e9999;">
												<td colspan="2" align="left" style="color:white"><strong>SUB TOTAL</strong></td>
												<td colspan="2" align="right" style="color:white"><strong>'.number_format(array_sum($PotensiKantor)+array_sum($PotensiLokasi),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($JumlahPotensi),0,',','.').'</strong></td>
												<td colspan="2" align="right" style="color:white"><strong>'.number_format(array_sum($RealisasiUTTPKantor)+array_sum($RealisasiUTTPLokasi),0,',','.').'</strong></td>
												<td align="right" style="color:white"><strong>'.number_format(array_sum($Jumlah),0,',','.').'</strong></td>
											</tr>
											<tr style="background-color: #9e9999;">
												<td colspan="2" align="left" style="color:white"><strong>TOTAL PENDAPATAN</strong></td>
												<td colspan="3" align="right" style="color:white"><strong> Rp '.number_format(array_sum($PotensiKantor)+array_sum($PotensiLokasi)+array_sum($JumlahPotensi),0,',','.').'</strong></td>
												<td colspan="3" align="right" style="color:white"><strong> Rp '.number_format(array_sum($RealisasiUTTPKantor)+array_sum($RealisasiUTTPLokasi)+array_sum($Jumlah),0,',','.').'</strong></td>
											</tr>
											';
										}
										?>
									</tbody>
								</table>
								<!-- <div><?php echo paginate_one($reload, $page, $tpages); ?></div> -->
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
	  <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
		<div role="document" class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 id="exampleModalLabel" class="modal-title">Cetak Laporan Pendapatan Retribusi</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<form method="post" target="_BLANK">
			<div class="modal-body">
				<input type="hidden" name="tanggal1" value="<?php echo @$_REQUEST['Tanggal1']; ?>" class="form-control">
				<input type="hidden" name="tanggal2" value="<?php echo @$_REQUEST['Tanggal2']; ?>" class="form-control">
				<div class="form-group">  
				</div>
			</div>
			<div class="modal-footer">
			  <input type="submit" name="Cetak" class="btn btn-primary" value="Cetak">
			  <input type="submit" name="Export" class="btn btn-danger" value="Export Excel">
			  <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
			</div>
			</form>
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
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>
	<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
		});
	</script>
	<?php 
		if(isset($_POST['Cetak'])){
			$Tanggl1 = htmlspecialchars($_POST['tanggal1']);
			$Tanggl2 = htmlspecialchars($_POST['tanggal2']);
			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapPendapatan.php?tgl1='.base64_encode($Tanggl1).'&tgl2='.base64_encode($Tanggl2).'";</script>';
		}

		if(isset($_POST['Export'])){
			$Tanggl1 = htmlspecialchars($_POST['tanggal1']);
			$Tanggl2 = htmlspecialchars($_POST['tanggal2']);
			echo '<script language="javascript">document.location="../library/Export/LapPendapatan.php?tgl1='.base64_encode($Tanggl1).'&tgl2='.base64_encode($Tanggl2).'";</script>';
		}
	?>
	
  </body>
</html>