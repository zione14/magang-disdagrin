<?php
include 'akses.php';
@$fitur_id = 49;
include '../library/lock-menu.php';
$Page = 'Keuangan';
$SubPage = 'LapKeuanganERPAS';
$Sekarang = date('Y-m');
$Bulan = isset($_REQUEST['Periode']) && $_REQUEST['Periode'] != null ? htmlspecialchars($_REQUEST['Periode']) : $Sekarang;
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
              <h2 class="no-margin-bottom">Potensi Pendapatan Bidang Retribusi Pasar</h2>
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
							  <h3 class="h4">Laporan Rekap Keuangan Bidang Retribusi Pasar</h3>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="">
									<div class="col-lg-7 offset-lg-5 form-group input-group">
										<input type="text" name="Periode" class="form-control" id="time7" value="<?php echo @$Bulan; ?>" placeholder="Periode Bulan">&nbsp;&nbsp;
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											<a href="../library/Export/LapKeuanganERPAS.php?bl=<?=@$Bulan?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Export</span></a>
										</span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th align="center">No</th>
									  <th align="center">Nama Pasar</th>
									  <th align="center">Potensi Pendapatan</th>
									  <th align="center">Realisasi Pendapatan</th>
									  <th align="center">Aksi</th>
									</tr>
									
								  </thead>
									  <?php
									include '../library/pagination1.php';
									// mengatur variabel reload dan sql
									$bl = @$_REQUEST['Periode'];
									$reload = "LapKeuanganERPAS.php?Periode=$bl&pagination=true";
									$sql =  "SELECT a.KodePasar,a.NamaPasar,IFNULL(pendapatan.PendapatanLapak,0) AS PendapatanLapak,IFNULL(realisasi.RealisasiRetribusi,0) AS RealisasiRetribusi
										FROM mstpasar a
										LEFT JOIN(
										    SELECT r.KodePasar, SUM(r.Retribusi) AS PendapatanLapak
											FROM lapakperson r
											WHERE r.IsAktif=b'1'
											GROUP by r.KodePasar
										) AS pendapatan ON pendapatan.KodePasar = a.KodePasar
										LEFT JOIN(
										    SELECT r.KodePasar, SUM(r.NominalDiterima) AS RealisasiRetribusi
											FROM trretribusipasar r
											WHERE LEFT(r.TanggalTrans,7) = '$Bulan' 
											GROUP by r.KodePasar
										) AS realisasi ON realisasi.KodePasar = a.KodePasar";
									$sql .="  GROUP by a.KodePasar order by  a.NamaPasar";
									$oke = $koneksi->prepare($sql);
									$oke->execute();
									$result = $oke->get_result();									
									
									//pagination config start
									$rpp = 50; // jumlah record per halaman
									$page = intval(@$_GET["page"]);
									if($page<=0) $page = 1;  
									$tcount = mysqli_num_rows($result);
									$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
									$count = 0;
									$i = ($page-1)*$rpp;
									$no_urut = ($page-1)*$rpp;
									$No = 1;
									//pagination config end				
								?>
									<tbody>
									<?php
									while(($count<$rpp) && ($i<$tcount)) {
										mysqli_data_seek($result,$i);
									$data = mysqli_fetch_assoc($result);
									?>
									<tr class="odd gradeX">
										<td width="50px">
											<?php echo ++$no_urut;?> 
										</td>
										<td>
											<?php echo ucwords($data['NamaPasar']);?> 
										</td>
										<td align="right">
											<?php 
												echo number_format($data['PendapatanLapak']); 
												$Pendapatan[] = $data['PendapatanLapak'];
											?>
										</td>
										<td align="right">
											<?php 
												echo number_format($data['RealisasiRetribusi']); 
												$Realisasi[] = $data['RealisasiRetribusi'];
											?>
										</td>
										<td align="center">
											<a href="LapKeuanganERPAS_view.php?psr=<?=base64_encode($data['KodePasar'])?>&bln=<?=base64_encode($Bulan)?>"><span class="btn btn-sm btn-info" title="Detail Transaksi Retrbusi Pasar"><i class="fa fa-search"></i> LIHAT DETAIL</span></a>
										</td>
										
									</tr>
									<?php
										$i++; 
										$count++;							
									}
									
									if ($tcount == 0){
										echo '
										<tr>
											<td colspan="10" align="center">
												<strong>Tidak Ada Data</strong>
											</td>
										</tr>
										';
									}else{
										echo '
										<tr >
											<td colspan="2" align="center" ><strong>TOTAL</strong></td>
											<td align="right"><strong>'.number_format(array_sum(@$Pendapatan)).'</strong></td>
											<td align="right"><strong>'.number_format(array_sum(@$Realisasi)).'</strong></td>
											<td></td>
											
										</tr>
										';
									}
									?>
									</tbody>
								</table>
								<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
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
			$('#time1').Zebra_DatePicker({format: 'Y-m'});
		});
	</script>
  </body>
</html>