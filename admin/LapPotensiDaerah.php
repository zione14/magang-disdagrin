<?php
include 'akses.php';
@$fitur_id = 35;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Page = 'Laporan';
$SubPage = 'LapPotensiDaerah';
if(@$_REQUEST['kode']!=null){
	@$bagi_uraian 		= explode("#", $_REQUEST['kode']);
	@$Timbangan 		= $bagi_uraian[0];
	@$Kelas 			= $bagi_uraian[1];
	@$Ukuran 			= $bagi_uraian[2];
}

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
              <h2 class="no-margin-bottom">Laporan Potensi Pendapatan</h2>
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
							  <h3 class="h4">Laporan Potensi Pendapatan </h3>
							  <div class="offset-lg-8">
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="">
									<div class="col-lg-7 offset-lg-5 form-group input-group">
										<select id="KodeKec" name="KodeKec" class="form-control">	
											<?php
												echo "<option value=''>--- Semua Kecamatan ---</option>";
												$menu = mysqli_query($koneksi,"SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeKec']==$_REQUEST['KodeKec']){
														echo "<option value=\"".$kode['KodeKec']."\" selected>".$kode['NamaKecamatan']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
													}
												}
											?>
										</select>&nbsp;&nbsp;
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											<a data-toggle="modal" data-target="#myModal"><span class="btn btn-secondary">Cetak & Export</span></a>
										</span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th align="center"  rowspan="2">No</th>
									  <th align="center" rowspan="2">Jenis UTTP</th>
									  <th align="center"  rowspan="2">Jumlah</th>
									  <th align="center" colspan="2">Nilai Retribusi</th>
									   <th align="center"  colspan="2">Potensi Pendapatan</th>
									</tr>
									<tr height="15">
										<th align="center" >Kantor</th>
										<th align="center" >Lokasi</th>
										<th align="center" >Kantor</th>
										<th align="center" >Lokasi</th>
									</tr>
								  </thead>
									  <?php
									include '../library/pagination1.php';
									// mengatur variabel reload dan sql
									$kec = @$_REQUEST['KodeKec'];
									$reload = "LapPotensiDaerah.php?KodeKec=$kec&pagination=true";
									$sql =  "SELECT a.KodeTimbangan,a.KodeKelas,a.KodeUkuran,c.NamaTimbangan,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,b.KodeKec from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson)=(b.KodeLokasi,b.IDPerson) join msttimbangan c on a.KodeTimbangan=c.KodeTimbangan join kelas d on (a.KodeTimbangan,a.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) join mstperson h on h.IDPerson=a.IDPerson WHERE h.UserName != 'dinas' and a.StatusUTTP='Aktif'  ";
									
									if(@$_REQUEST['KodeKec']!=null){
										$sql .= " AND b.KodeKec='".$_REQUEST['KodeKec']."' ";
									}
									
									
									$sql .="  GROUP by a.KodeTimbangan,a.KodeKelas,a.KodeUkuran order by  a.KodeTimbangan,a.KodeKelas,a.KodeUkuran";
									$result = mysqli_query($koneksi,$sql);									
									
									//pagination config start
									$rpp = 20; // jumlah record per halaman
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
											<?php
												 
											@$Subquery = isset($_REQUEST['KodeKec']) && $_REQUEST['KodeKec'] != null ? 'AND b.KodeKec='.htmlspecialchars($data['KodeKec']) : ''; 
												
											$query = mysqli_query($koneksi, ("select IDTimbangan from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' $Subquery "));
											$nums2 = mysqli_num_rows($query); 
												echo $nums2;
											?>
										</td>
										<td align="right">
											<?php echo number_format($data[6]); ?>
										</td>
										<td align="right">
											<?php echo number_format($data[7]); ?>
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
									</tr>
									<?php
										$i++; 
										$count++;							
									}
									
									if ($tcount == 0){
										echo '
										<tr>
											<td colspan="8" align="center">
												<strong>Tidak Ada Data</strong>
											</td>
										</tr>
										';
									}else{
										echo '
										<tr >
											<td colspan="5" align="center" ><strong>Total Pendapatan</strong></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahKantor)).'</strong></td>
											<td align="center"><strong>'.number_format(array_sum(@$JumlahLokasi)).'</strong></td>
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
							$menu = mysqli_query($koneksi,"SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
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
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			//$('#Datetime2').Zebra_DatePicker({format: 'Y-m-d H:i', direction: 1});
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