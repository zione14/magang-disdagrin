<?php
include 'akses.php';
@$fitur_id = 11;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Page = 'Laporan';
$SubPage = 'LapTotalPerDesa';
if(@$_REQUEST['KodeKec']!=null){
	$kec = @$_REQUEST['KodeKec'];
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
		border-bottom: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">Laporan UTTP Per Desa</h2>
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
							  <h3 class="h4">Total UTTP Per Desa</h3>
							  <div class="offset-lg-7">
								<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-primary">Cetak & Export</span></a>-->
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12 offset-lg-5">
									<form method="post" action="">
									<div class="col-lg-7 form-group input-group">
										<select id="KodeKec" name="KodeKec" class="form-control" required>	
											<?php
												echo "<option value=''>--- Kecamatan ---</option>";
												$sqla = "SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab= ?  ORDER BY NamaKecamatan";
												$ab = KodeKab($koneksi);
												$oke = $koneksi->prepare($sqla);
												$oke->bind_param('S', $ab);
												$oke->execute();
												$menu = $oke->get_result();
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeKec']==$kec){
														echo "<option value=\"".$kode['KodeKec']."\" selected>".$kode['NamaKecamatan']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
													}
												}
											?>
										</select>&nbsp;&nbsp;
										<!--<select id="KodeDesa" name="KodeDesa" class="form-control">
											<option value=''>--- Desa ---</option>
											<?php
												$sqlb = "SELECT * FROM mstdesa WHERE KodeKec= ? ORDER BY NamaDesa";
												$ab = $kec;
												$oke1 = $koneksi->prepare($sqlb);
												$oke1->bind_param('s', $ab);
												$oke1->execute();
												$menu = $oke1->get_result();
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeDesa']==$desa){
														echo "<option value=\"".$kode['KodeDesa']."\" selected>".$kode['NamaDesa']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
													}
												}
											?>
										</select>&nbsp;&nbsp;-->
										<!--<select  name="keyword" class="form-control">
											<?php
												echo "<option value=''>--- Timbangan ---</option>";
												$sqlc = "SELECT a.KodeTimbangan,b.KodeKelas,c.KodeUkuran,a.NamaTimbangan,b.NamaKelas,c.NamaUkuran FROM msttimbangan a LEFT JOIN kelas b on a.KodeTimbangan=b.KodeTimbangan Join detilukuran c on (b.KodeTimbangan,b.KodeKelas)=(c.KodeTimbangan,c.KodeKelas) ORDER by a.NamaTimbangan ASC";
												$oke2 =  $koneksi->prepare($sqlc);
												$oke2->execute();
												$menu = $oke2->get_result();
												while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeTimbangan'] === $RowData['KodeTimbangan']){
															echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" selected='selected'>".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" >".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
														}
												}
											?>
										</select>-->
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											<?php if ($cek_fitur['PrintData'] =='1'){ ?>
												<a data-toggle="modal" data-target="#myModal"><span class="btn btn-secondary">Cetak & Export</span></a>
											<?php } ?>
										 </span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>No </th>
									  <th>Nama </th>
									  <th>Jumlah Timbangan</th>
									</tr>
								  </thead>
									  <?php
									include '../library/pagination1.php';
									// mengatur variabel reload dan sql
									$kosong=null;
									//jika tidak ada pencarian pakai ini
									@$reload = "LapTotalPerDesa.php?pagination=true&KodeKec=$kec";
									@$sql =  "SELECT NamaDesa,KodeDesa FROM mstdesa WHERE KodeKec= ? ORDER BY NamaDesa";
									$ac = $kec;
									$oke3 = $koneksi->prepare($sql);
									$oke3->bind_param('s', $ac);
									$oke3->execute();
									$result = $oke3->get_result();										
									
									//pagination config start
									$rpp = 25; // jumlah record per halaman
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
									if (@$kec == null){
										echo '
											<tr>
												<td colspan="8" align="center">
													<strong>Silahkan Pilih Kecamatan Terlebih Dahulu!</strong>
												</td>
											</tr>
											';
									}else{

									while(($count<$rpp) && ($i<$tcount)) {
										mysqli_data_seek($result,$i);
									$data = mysqli_fetch_array($result);
									?>
									<tr class="odd gradeX">
										<td width="50px">
											<?php echo ++$no_urut;?> 
										</td>
										<td>
											<?php echo ucwords($data['NamaDesa']);?> 
										</td>
										<td align="center">
											<?php
												$done = ResultData($data[1],$kec,$koneksi);
												$Jumlah[] = $done;
												echo $done;
											?>
										</td>
									</tr>
									<?php
										$i++; 
										$count++;							
									}
										echo '
										<tr style="background-color: #9e9999;">
											<td colspan="2" align="center" style="color:white"><strong>Total Timbangan</strong></td>
											<td align="center" style="color:white"><strong>'.number_format(array_sum($Jumlah)).'</strong></td>
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
			  <h4 id="exampleModalLabel" class="modal-title">Cetak Laporan Total Timbangan Per Desa</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			  <form method="post" target="_BLANK">
				<div class="form-group">
					<label>Pilih Kecamatan</label>
					<select name="KodeKec" class="form-control" required>
						<?php
							echo "<option value=''>--- Kecamatan ---</option>";
							$menu = mysqli_query($koneksi,"SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
							while($kode = mysqli_fetch_array($menu)){
								if($kode['KodeKec']==$kec){
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
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>
	  $("#KodeKec").change(function(){
		var KodeKec = $("#KodeKec").val();
		$.ajax({
			url: "../library/Dropdown/ambil-desa.php",
			data: "KodeKec="+KodeKec,
			cache: false,
			success: function(msg){
				$("#KodeDesa").html(msg);
			}
		});
	  });
	  
	});
	</script>
	<?php 
		if(isset($_POST['Cetak'])){
			$KodeKec = htmlspecialchars($_POST['KodeKec']);
			$sql = mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res = mysqli_fetch_array($sql);
			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapTotalPerDesa.php?kec='.base64_encode($KodeKec).'&nm='.$res[0].'";</script>';
		}

		if(isset($_POST['Export'])){
			$KodeKec = htmlspecialchars($_POST['KodeKec']);
			$sql = mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res = mysqli_fetch_array($sql);
			echo '<script language="javascript">document.location="../library/Export/LapTotalPerDesa.php?kec='.base64_encode($KodeKec).'&nm='.$res[0].'";</script>';
			
		}
		
		function ResultData($KodeDesa,$KodeKec,$koneksi){
			$sql2 = @mysqli_query($koneksi, "SELECT a.IDTimbangan FROM timbanganperson a JOIN lokasimilikperson b ON (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) join mstperson c on c.IDPerson=a.IDPerson WHERE b.KodeDesa='$KodeDesa' and b.KodeKec='$KodeKec' and c.UserName != 'dinas' AND  a.StatusUTTP='Aktif' GROUP BY a.IDTimbangan"); 
			$nums2 = @mysqli_num_rows($sql2); 
			return($nums2);
		}
	
		
		
	?>
	
  </body>
</html>