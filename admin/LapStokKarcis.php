<?php
include 'akses.php';
$Page = 'Security';
$SubPage = 'AksesLevel';
$fitur_id = 60;
include '../library/lock-menu.php'; 
@include '../library/tgl-indo.php';
$Page = 'LapKarcis';
$SubPage = 'LapStokKarcis';
$Tahun=date('Y');
@$KodeKB    = isset($_REQUEST['KodeKB'])  ? mysqli_escape_string($koneksi, $_REQUEST['KodeKB']) : 'KTS-2020-0000001';
@$keyword   = isset($_REQUEST['keyword'])  ? mysqli_escape_string($koneksi, $_REQUEST['keyword']) : '' ;
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
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
   <style>
		 th {
			text-align: center;
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
              <h2 class="no-margin-bottom">Laporan Stok Karcis Retribusi Dinas</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Data Stok Karcis Retribusi Dinas</h3>
					</div>
					<div class="card-body">
					  <div class="col-lg-12 col-md-12 mb-20">
						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>" id="admin">
								<div class="table-responsive">  
									<table class="table">
									  <thead>
										<tr>
										  <th>NO</th>
										  <th>JENIS KARCIS</th>
										  <th>STOK</th>
										  <th>NILAI KARCIS</th>
										  <th>NOMINAL KARCIS</th>
										</tr>
									  </thead>
										<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "LapPerJenisKarcis.php?pagination=true&KodeKB=$KodeKB&keyword=$keyword";
										$sql =  "SELECT k.NamaKB, k.NilaiKB, IFNULL(d.JumlahStok, 0) as JumlahStok, (k.NilaiKB*IFNULL(d.JumlahStok, 0)) as NominalKarcis
										FROM mstkertasberharga k
										LEFT JOIN (
											SELECT IFNULL(SUM(JumlahDebetKB), 0)-IFNULL(SUM(JumlahKirim), 0) as JumlahStok, i.KodeKB
											FROM traruskb t
											JOIN traruskbitem i ON t.NoTransArusKB=i.NoTransArusKB
											JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
											LEFT JOIN mstpasar p ON t.KodePasar = p.KodePasar
											WHERE (t.TipeTransaksi='PENCETAKAN' OR t.TipeTransaksi='PENGELUARAN')
											GROUP by i.KodeKB
										) d ON d.KodeKB=k.KodeKB
										WHERE k.IsAktif=b'1'
										ORDER BY k.KodeKB ASC";
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										$rpp = 15; // jumlah record per halaman
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
												<?php echo $data['NamaKB']; ?>
											</td>
											<td align="right">
												<?php echo number_format($data['JumlahStok']); ?>
											</td>
											<td align="right">
												<?php echo number_format($data['NilaiKB']); ?>
											</td>
											<td align="right">
												<?php echo number_format($data['NominalKarcis']); ?>
											</td>
										</tr>
										<?php
											$NominalKarcis[] = $data['NominalKarcis']; 
											$i++; 
											$count++;
											} 

											echo '
											<tr>
												<td colspan="4" align="left"><strong>Total Nilai Karcis Retribusi</strong></td>
												<td align="right"><strong> Rp '.number_format(array_sum($NominalKarcis),0,',','.').'</strong></td>
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
            </div>
          </section> 
        </div>
      </div>
    </div>
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
  </body>
</html>