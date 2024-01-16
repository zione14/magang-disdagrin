<?php
include 'akses.php';
@$fitur_id = 49;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'Keuangan';
$SubPage = 'LapKeuanganERPAS';
$Sekarang = date('Y-m');
$KodePasar = isset($_GET['psr']) && $_GET['psr'] != null ? htmlspecialchars(base64_decode($_GET['psr'])) : '';
$Bulan     = isset($_GET['bln']) && $_GET['bln'] != null ? htmlspecialchars(base64_decode($_GET['bln'])) : $Sekarang;
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
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
              <h2 class="no-margin-bottom">Detail Potensi Pendapatan Bidang Retribusi Pasar <?php echo strtoupper($BulanIndo[date('m', strtotime($Bulan)) - 1].' '.date('Y', strtotime($Bulan))) ?></h2>
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
							  <h3 class="h4"></h3>
							   <div class="offset-lg-11">
							   		<a href="LapKeuanganERPAS.php?Periode=<?=@$Bulan?>" ><span class="btn btn-primary btn-sm">Kembali</span></a>
							  </div>
							</div>
							<div class="card-body">
								<!--<div class="col-lg-12">
									<form method="post" action="">
									<div class="col-lg-7 offset-lg-5 form-group input-group">
										<input type="text" name="Periode" class="form-control" id="time7" value="<?php echo @$Bulan; ?>" placeholder="Periode Bulan">&nbsp;&nbsp;
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											<a href="../library/Export/LapKeuanganMetrologi.php?bl=<?=@$_REQUEST['Periode']?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Export</span></a>
										</span>
									</div>
								</form>
								</div>-->
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Tanggal Bayar</th>
									  <th>Nama</th>
									  <th>Lapak</th>
									  <th>Nilai Dibayar</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$bln =base64_encode($Bulan);
										$psr =base64_encode($KodePasar);
										$reload = "LapKeuanganERPAS_view.php?pagination=true&bln=$bln&psr=$psr";
										$sql =  "SELECT a.TanggalTrans,a.JmlHariDibayar,a.NominalDiterima,b.NamaPasar,b.KodeBJ,c.Keterangan as KetLapak,c.NomorLapak,a.NoTransRet,d.NamaPerson
										FROM trretribusipasar a
										JOIN mstpasar b on a.KodePasar=b.KodePasar
										JOIN lapakperson c on (a.KodePasar,a.IDLapak,a.IDPerson)=(c.KodePasar,c.IDLapak,c.IDPerson)
										JOIN mstperson d on a.IDPerson=d.IDPerson WHERE LEFT(a.TanggalTrans,7) = '$Bulan' and a.KodePasar= '$KodePasar'";
										
										$sql .=" ORDER BY a.TanggalTrans DESC";
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										$rpp = 20; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										<?php
										if($tcount == null OR $tcount === 0){
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><strong>Tidak ada data</strong></td></tr>';
										} else {
											while(($count<$rpp) && ($i<$tcount)) {
												mysqli_data_seek($result,$i);
												@$data = mysqli_fetch_array($result);
											
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php echo ''.@TanggalIndo($data['TanggalTrans']); ?>
											</td>
											<td>
												<strong><?php echo $data ['NamaPerson']; ?></strong><br>
											</td>
											<td>
												<?php echo $data['KetLapak']."<br>".$data['NamaPasar']; ?>
											</td>											
											<td align="center">
												<?php echo "Rp ".number_format($data['NominalDiterima']); ?>
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
											} 
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