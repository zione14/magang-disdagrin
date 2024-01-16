<?php
include 'akses.php';
@$fitur_id = 64;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$menuadmin = isset($JenisLogin) && $JenisLogin == 'RETRIBUSI PASAR' ? 'LaporanRetribusi' : 'Pelaporan';
$Page = $menuadmin;
$SubPage = 'LapPendapatanKarcis';
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
              <h2 class="no-margin-bottom">Laporan Pendapatan Karcis</h2>
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
							  <h3 class="h4">Pendapatan Karcis</h3>
							  <div class="offset-lg-7">
								<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-primary">Cetak & Export</span></a>-->
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="LapPendapatanKarcis.php">
										<div class="col-lg-7 form-group input-group offset-lg-5">
											<input type="text" name="Bulan" class="form-control" id="time7" value="<?php echo @$BulanTahun; ?>" placeholder="Periode Bulan" required>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>&nbsp;
												
												<a href="cetak/LapPendapatanKarcis.php?Bulan=<?=$BulanTahun?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak Laporan</span></a>
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
									  <th>Jenis Karcis </th>
									  <th>Jumlah Diterima</th>
									  <th>Nominal Penggunaan Karcis</th>
									  <th>Jumlah Dikeluar</th>
									  <th>Nominal Pendapatan Diterima</th>
									</tr>
									
								  </thead>
									<?php
										include '../library/pagination1.php';
										$reload = "LapPendapatanKarcis.php?pagination=true&Bulan=$BulanTahun";
										$sql =  "SELECT p.NamaPasar, k.NamaKB, t.NoTransArusKB, IFNULL(c.JumlahPengiriman, 0) as JumlahPengiriman,  (IFNULL(c.JumlahPengiriman, 0) * k.NilaiKB) as NominalPengiriman, IFNULL(d.JumlahPengeluaran, 0) as JumlahPengeluaran, (IFNULL(d.JumlahPengeluaran, 0) * k.NilaiKB) as NominalPengeluaran 
											FROM traruskbitem i
											JOIN mstkertasberharga k ON i.KodeKB=k.KodeKB
											JOIN traruskb t ON i.NoTransArusKB=t.NoTransArusKB
											JOIN mstpasar p ON t.KodePasar=p.KodePasar
											LEFT JOIN (
												SELECT  SUM(c.JumlahKirim) as JumlahPengiriman,  SUM(c.TotalNominal) as NominalPengiriman,c.KodeKB, d.KodePasar
												FROM traruskbitem c
												JOIN traruskb d ON c.NoTransArusKB=d.NoTransArusKB
												where (date_format(d.TanggalTransaksi, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun') AND d.TipeTransaksi ='PENGIRIMAN'
												GROUP BY c.KodeKB,d.KodePasar
											) c ON c.KodePasar = t.KodePasar AND c.KodeKB=i.KodeKB
											LEFT JOIN (
												SELECT  SUM(c.JumlahKreditKB) as JumlahPengeluaran,  SUM(c.TotalNominal) as NominalPengeluaran,c.KodeKB, d.KodePasar
												FROM traruskbitem c
												JOIN traruskb d ON c.NoTransArusKB=d.NoTransArusKB
												where (date_format(d.TanggalTransaksi, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun') AND d.TipeTransaksi ='PENGELUARAN'
												GROUP BY c.KodeKB,d.KodePasar
											) d ON d.KodePasar = t.KodePasar AND d.KodeKB=i.KodeKB
											GROUP by t.KodePasar, i.KodeKB
											ORDER by t.KodePasar, i.KodeKB";
										$result = mysqli_query($koneksi,$sql);		
										
										
										//pagination config start
										$rpp = 1000; // jumlah record per halaman
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
										$no = 1;
										$NamaPasar = '';

										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											$data = mysqli_fetch_array($result);

											echo '<tr class="odd gradeX">';
												if($data['NamaPasar'] !== $NamaPasar){
						                            echo '<tr style="background:#f7f7f7;">
						                            <td colspan="6"><strong>'.strtoupper($data['NamaPasar']).'</strong></td>
						                            </tr>';
						                            $NamaPasar = $data['NamaPasar'];
						                        }
						                        echo '<td>'.$no.'</td>';
						                        echo '<td width="20%">'.$data['NamaKB'].'</td>';
						                        echo '<td align="right">'.number_format($data['JumlahPengiriman']).'</td>';
						                        echo '<td align="right">'.number_format($data['NominalPengiriman']).'</td>';
						                        echo '<td align="right">'.number_format($data['JumlahPengeluaran']).'</td>';
						                        echo '<td align="right">'.number_format($data['NominalPengeluaran']).'</td>';
						                    echo '</tr>';		
                        
											++$no;
											$i++; 
											$count++;
										}
										
										if($tcount==0){
											echo '
											<tr>
												<td colspan="6" align="center">
													<strong>Data Tidak Ada</strong>
												</td>
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