<?php
include 'akses.php';
@$fitur_id = 48;
include '../library/lock-menu.php';
$Page = 'Keuangan';
$SubPage = 'LapKeuanganMetrologi';
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
              <h2 class="no-margin-bottom">Potensi Pendapatan Bidang Metrologi</h2>
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
							  <h3 class="h4">Laporan Rekap Keuangan Bidang Metrologi</h3>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="">
									<div class="col-lg-7 offset-lg-5 form-group input-group">
										<input type="text" name="Periode" class="form-control" id="time7" value="<?php echo @$Bulan; ?>" placeholder="Periode Bulan">&nbsp;&nbsp;
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											<a href="../library/Export/LapKeuanganMetrologi.php?bl=<?=@$Bulan?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Export</span></a>
										</span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th align="center" rowspan="2">No</th>
									  <th align="center" rowspan="2">Jenis UTTP</th>
									  <th align="center" colspan="2">Retribusi</th>
									  <th align="center" colspan="3">Potensi Pendapatan</th>
									  <th align="center" colspan="3">Realisasi Pendapatan</th>
									</tr>
									<tr height="15">
										<th align="center" >Kantor</th>
										<th align="center" >Lokasi</th>
										<th align="center" >Jumlah</th>
										<th align="center" >Kantor</th>
										<th align="center" >Lokasi</th>
										<th align="center" >Jumlah</th>
										<th align="center" >Kantor</th>
										<th align="center" >Lokasi</th>
									</tr>
								  </thead>
									  <?php
									include '../library/pagination1.php';
									// mengatur variabel reload dan sql
									$bl = @$_REQUEST['Periode'];
									$reload = "LapKeuanganMetrologi.php?Periode=$bl&pagination=true";
									$sql =  "SELECT a.KodeTimbangan,a.KodeKelas,a.KodeUkuran,c.NamaTimbangan,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,b.KodeKec from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson)=(b.KodeLokasi,b.IDPerson) join msttimbangan c on a.KodeTimbangan=c.KodeTimbangan join kelas d on (a.KodeTimbangan,a.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) join mstperson h on h.IDPerson=a.IDPerson WHERE h.UserName != 'dinas' and a.StatusUTTP='Aktif'  ";
									
									
									
									$sql .="  GROUP by a.KodeTimbangan,a.KodeKelas,a.KodeUkuran order by  a.KodeTimbangan,a.KodeKelas,a.KodeUkuran";
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
									$data = mysqli_fetch_array($result);
									?>
									<tr class="odd gradeX">
										<td width="50px">
											<?php echo ++$no_urut;?> 
										</td>
										<td>
											<?php echo ucwords($data[3])." ".$data[4]." ".$data[5];?> 
										</td>
										<td align="right">
											<?php echo number_format($data[6]); ?>
										</td>
										<td align="right">
											<?php echo number_format($data[7]); ?>
										</td>
										<td align="right">
											<?php
												 
											@$Subquery = isset($_REQUEST['KodeKec']) && $_REQUEST['KodeKec'] != null ? 'AND b.KodeKec='.htmlspecialchars($data['KodeKec']) : ''; 
												
											$query = mysqli_query($koneksi, ("select IDTimbangan from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' $Subquery "));
											$nums2 = mysqli_num_rows($query); 
												echo $nums2;
											?>
										</td>
										<td align="right">
											<?php
												$kantor=$data[6]*$nums2;
												echo number_format($kantor);
												$JumlahKantor[] = $kantor;
											?>
										</td>
										<td align="right">
											<?php
												$lokasi=$data[7]*$nums2;
												echo number_format($lokasi);
												$JumlahLokasi[] = $lokasi;
											?>
										</td>
										<td align="right">
											<?php
												$query2 = "select b.IDTimbangan 
												from trtimbanganitem b
												Join timbanganperson a on a.IDTimbangan = b.IDTimbangan 
												where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' and LEFT(b.TanggalTransaksi,7) = '$Bulan' ";
												$oke = $koneksi->prepare($query2);
												$oke->execute();
												$conn  = $oke->get_result();
												$nums3 = mysqli_num_rows($conn); 
												echo $nums3;
											?>
										</td>
										<td align="right">
											<?php
												$realisasikantor=$data[6]*$nums3;
												echo number_format($realisasikantor);
												$JumlahRealisasiKantor[] = $realisasikantor;
											?>
										</td>
										<td align="right">
											<?php
												$realisasilokasi=$data[7]*$nums3;
												echo number_format($realisasilokasi);
												$JumlahRealisasiLokasi[] = $realisasilokasi;
											?>
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
											<td></td>
											<td></td>
											<td></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahKantor)).'</strong></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahLokasi)).'</strong></td>
											<td></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahRealisasiKantor)).'</strong></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahRealisasiLokasi)).'</strong></td>
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
	<!-- Modal-->
	  <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
		<div role="document" class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 id="exampleModalLabel" class="modal-title">Cetak Laporan Potensi Pendapatan</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			  <form method="post" target="_BLANK">
				<div class="form-group">
					<label>Pilih Kecamatan</label>
					<select id="Kode" name="KodeKec" class="form-control" >
						<?php
							echo "<option value=''>--- Semua Kecamatan ---</option>";
							$sqlku = "SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab= ?  ORDER BY NamaKecamatan";
							$ab = KodeKab($koneksi);
							$oke1 = $koneksi->prepare($sqlku);
							$oke1->bind_param('s', $ab);
							$oke1->execute();
							$menu = $oke1->get_result();
							while($kode = mysqli_fetch_array($menu)){
								if($kode['KodeKec']==$_REQUEST['KodeKec']){
									echo "<option value=\"".$kode['KodeKec']."\" selected>".$kode['NamaKecamatan']."</option>\n";
								}else{
									echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
								}
							}
						?>
					</select>
				</div>
				<div class="form-group">       
				  <input type="submit" name="Cetak" class="btn btn-primary" value="Cetak">
				  <input type="submit" name="Export" class="btn btn-danger" value="Export Excel">
				</div>
			  </form>
			</div>
			<div class="modal-footer">
			  <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
			</div>
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
	<?php 
		if(isset($_POST['Cetak'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			
			
			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapPotensiDaerah.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'";</script>';
		}

		if(isset($_POST['Export'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			
			
			echo '<script language="javascript">document.location="../library/Export/LapPotensiDaerah.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'";</script>';
			
			
		}
		
			
	?>
	
  </body>
</html>