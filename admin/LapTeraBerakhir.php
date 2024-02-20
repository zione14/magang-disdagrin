<?php
include 'akses.php';
@$fitur_id = 12;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Page = 'Laporan';
$SubPage = 'LapTeraBerakhir';
@$Pilihan = isset($_REQUEST['Bulan']) && $_REQUEST['Bulan'] != null ? @htmlspecialchars($_REQUEST['Bulan']) : '3'; 
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
              <h2 class="no-margin-bottom">Laporan Tera UTTP Jatuh Tempo</h2>
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
							  <h3 class="h4">Jumlah UTTP Jatuh Tempo</h3>
							  <div class="offset-lg-7">
							  <?php if ($cek_fitur['PrintData'] =='1'){ ?>
								<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-secondary">Cetak & Export</span></a>-->
								<?php } ?>
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="">
									<div class="col-lg-6 form-group input-group">
										<select  name="Bulan" class="form-control">
										  <option value="3" <?php if(@$Pilihan=='3'){echo 'selected';} ?>>3 Bulan</option>
										  <option value="2" <?php if(@$Pilihan=='2'){echo 'selected';} ?>>2 Bulan</option>
										  <option value="1" <?php if(@$Pilihan=='1'){echo 'selected';} ?>>1 Bulan</option>
										  <option value="4" <?php if(@$Pilihan=='4'){echo 'selected';} ?>>Sudah Berakhir</option>
										</select>
										<span class="input-group-btn">
											<button class="btn btn-large btn-info" type="submit">Cari</button>
										 </span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Timbangan</th>
									  <th>Alamat Timbangan</th>
									  <th>Tanggal Tera</th>
									  <th>Jatuh Tempo Tera</th>
									</tr>
								  </thead>
									  <?php
									include '../library/pagination1.php';
									// mengatur variabel reload dan sql
									$reload = "LapTeraBerakhir.php?pagination=true&Bulan=$Pilihan";
									$sql =  "SELECT b.AlamatLokasi,a.KoorLat,a.KoorLong,a.NamaTimbangan,a.IDTimbangan,e.NamaKabupaten,c.NamaKecamatan,d.NamaDesa,MAX(g.TglTera) as Tanggal,g.NoTransaksi,h.NamaPerson,i.MasaTera,  ADDDATE(MAX(g.TglTera), INTERVAL i.MasaTera YEAR) as hahaha 
									FROM timbanganperson a 
									JOIN lokasimilikperson b ON (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) 
									JOIN mstkab e ON b.KodeKab=e.KodeKab JOIN mstkec c ON b.KodeKec = c.KodeKec 
									JOIN mstdesa d ON b.KodeDesa=d.KodeDesa 
									join trtimbanganitem f on (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(f.IDTimbangan,f.KodeLokasi,f.IDPerson) 
									join tractiontimbangan g on f.NoTransaksi=g.NoTransaksi 
									join mstperson h on h.IDPerson=a.IDPerson 
									join msttimbangan i on a.KodeTimbangan=i.KodeTimbangan 
									WHERE b.KodeKab='".KodeKab($koneksi)."' AND h.UserName !='dinas' and g.StatusTransaksi='SELESAI'";
									
									$sql .="  GROUP by f.IDTimbangan DESC";
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
									$No = 1;
									//pagination config end				
								?>
									<tbody>
									<?php
									while(($count<$rpp) && ($i<$tcount)) {
										mysqli_data_seek($result,$i);
									$data = mysqli_fetch_array($result);
									$HariIni = date("Y-m-d");
									$tgl1 = $data['Tanggal'];
									$period = $data['MasaTera'];
									$TeraUlang = date('Y-m-d', strtotime('+'.$period.' year', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 1 tahun
									$tanggal = TanggalIndo($TeraUlang);
									$Tgl1Bulan = date('Y-m-d', strtotime('-1 month', strtotime($TeraUlang)));
									$Tgl2Bulan = date('Y-m-d', strtotime('-2 month', strtotime($TeraUlang)));
									$Tgl3Bulan = date('Y-m-d', strtotime('-3 month', strtotime($TeraUlang)));

									if(@$Pilihan == '1'){
										if(strtotime($HariIni) >= strtotime($Tgl1Bulan) && strtotime($HariIni) < strtotime($TeraUlang)){ ?>
											<tr class="odd gradeX">
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo $data[3]." <br>No Transaksi : <br>".$data[4]; ?>
													
												</td>
												<td>
													<?php echo ucwords($data[0]).", ".$data[7].", ".$data[6].", ".$data[5];?> 
												</td>
												<td align="center">
													<?php echo TanggalIndo($tgl1);
														
													?>
												</td>
												<td align="center">
													<?php echo TanggalIndo($TeraUlang);
														
													?>
												</td>
											</tr>
									<?php } 
									} elseif(@$Pilihan == '2'){
										if(strtotime($HariIni) >= strtotime($Tgl2Bulan) && strtotime($HariIni) < strtotime($Tgl1Bulan)){  ?>
											<tr class="odd gradeX">
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo $data[3]." <br>No Transaksi : <br>".$data[4]; ?>
													
												</td>
												<td>
													<?php echo ucwords($data[0]).", ".$data[7].", ".$data[6].", ".$data[5];?> 
												</td>
												<td align="center">
													<?php echo TanggalIndo($tgl1);
														
													?>
												</td>
												<td align="center">
													<?php echo TanggalIndo($TeraUlang);
														
													?>
												</td>
											</tr>	
									<?php } 
									}elseif(@$Pilihan == '3'){ 
										if(strtotime($HariIni) >= strtotime($Tgl3Bulan) && strtotime($HariIni) < strtotime($Tgl2Bulan)){										?>
											<tr class="odd gradeX">
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo $data[3]." <br>No Transaksi : <br>".$data[4]; ?>
													
												</td>
												<td>
													<?php echo ucwords($data[0]).", ".$data[7].", ".$data[6].", ".$data[5];?> 
												</td>
												<td align="center">
													<?php echo TanggalIndo($tgl1);
														
													?>
												</td>
												<td align="center">
													<?php echo TanggalIndo($TeraUlang);
														
													?>
												</td>
											</tr>	
									<?php } 
									}elseif(@$Pilihan == '4') { 
										if(strtotime($HariIni) > strtotime($TeraUlang)){ ?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php echo $data[3]." <br>No Transaksi : <br>".$data[4]; ?>
												
											</td>
											<td>
												<?php echo ucwords($data[0]).", ".$data[7].", ".$data[6].", ".$data[5];?> 
											</td>
											<td align="center">
												<?php echo TanggalIndo($tgl1);
													
												?>
											</td>
											<td align="center">
												<?php echo TanggalIndo($TeraUlang);
													
												?>
											</td>
										</tr>	
									<?php } 
									} ?>
										
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
										// echo '
										// <tr style="background-color: #9e9999;">
											// <td colspan="3" align="center" style="color:white"><strong>Total Timbangan</strong></td>
											// <td align="center" style="color:white"><strong>'.number_format(array_sum(@$Jumlah)).'</strong></td>
										// </tr>
										// ';
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
					<select id="Kode" name="KodeKec" class="form-control" >
						<?php
							echo "<option value=''>--- Kecamatan ---</option>";
							$sqla = "SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab= ?  ORDER BY NamaKecamatan";
							$ab = KodeKab($koneksi);
							$oke1 = $koneksi->prepare($sqla);
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
					<label>Pilih Desa</label>
					<select id="KodeDes" name="KodeDesa" class="form-control">
						<option value=''>--- Desa ---</option>
						<?php
							$sqlb = "SELECT * FROM mstdesa WHERE KodeKec= ? ORDER BY NamaDesa";
							$ac = $_REQUEST['KodeKec'];
							$oke2 = $koneksi->prepare($sqlb);
							$oke2->bind_param($ac);
							$oke2->execute();
							$menu = $oke2->get_result();
							while($kode = mysqli_fetch_array($menu)){
								if($kode['KodeDesa']==$_REQUEST['KodeDesa']){
									echo "<option value=\"".$kode['KodeDesa']."\" selected>".$kode['NamaDesa']."</option>\n";
								}else{
									echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
								}
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<label>Pilih Timbangan</label>
					<select  name="kode" class="form-control">
						<?php
							echo "<option value=''>--- Timbangan ---</option>";
							$sqlc = "SELECT a.KodeTimbangan,b.KodeKelas,c.KodeUkuran,a.NamaTimbangan,b.NamaKelas,c.NamaUkuran FROM msttimbangan a LEFT JOIN kelas b on a.KodeTimbangan=b.KodeTimbangan Join detilukuran c on (b.KodeTimbangan,b.KodeKelas)=(c.KodeTimbangan,c.KodeKelas) ORDER by a.NamaTimbangan ASC";
							$oke3 = $koneksi->prepare($sqlc);
							$oke3->execute();
							$menu = $oke3->get_result();
							while($kode = mysqli_fetch_array($menu)){
									if($kode['KodeTimbangan'] === $Timbangan){
										echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" selected='selected'>".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
									}else{
										echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" >".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
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
	
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>
	  $("#Kode").change(function(){
		var KodeKec = $("#Kode").val();
		$.ajax({
			url: "../library/Dropdown/ambil-desa.php",
			data: "KodeKec="+KodeKec,
			cache: false,
			success: function(msg){
				$("#KodeDes").html(msg);
			}
		});
	  });
	  
	});
	</script>
	<?php 
		if(isset($_POST['Cetak'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			$KodeDesa 			= htmlspecialchars($_POST['KodeDesa']);
			
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			
			$desa				= mysqli_query($koneksi, ("select NamaDesa from mstdesa where KodeDesa='$KodeDesa'"));
			$row 				= mysqli_fetch_array($desa);
			@$id		 		= htmlspecialchars($_POST['kode']);

			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapJumlahPerNama.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'&nmd='.base64_encode($row[0]).'&id='.base64_encode($id).'&ds='.base64_encode($KodeDesa).'";</script>';
		}

		if(isset($_POST['Export'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			$KodeDesa 			= htmlspecialchars($_POST['KodeDesa']);
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			$desa				= mysqli_query($koneksi, ("select NamaDesa from mstdesa where KodeDesa='$KodeDesa'"));
			$row 				= mysqli_fetch_array($desa);
			@$id		 		= htmlspecialchars($_POST['kode']);
			
			echo '<script language="javascript">document.location="../library/Export/LapJumlahPerNama.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'&nmd='.base64_encode($row[0]).'&id='.base64_encode($id).'&ds='.base64_encode($KodeDesa).'";</script>';
			
			
		}
		
			
	?>
	
  </body>
</html>