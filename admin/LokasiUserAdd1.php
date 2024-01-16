<?php
include 'akses.php';
@$fitur_id = 2;
include '../library/lock-menu.php';
$Page = 'MasterPerson';

$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
@$IDPerson = base64_decode($_GET['usr']);
@$NamaPerson 		 = NamaPerson($koneksi, $IDPerson);
if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson WHERE KodeLokasi='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['usr']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
}
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
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Select2Master -->
	<link rel="stylesheet" href="../library/select2master/css/select2.css"/>
	
	<style>
	th {
		text-align: center;
	}
	
	#map #infowindow-content {
		display: inline;
	}

	.pac-card {
	  margin: 10px 10px 0 0;
	  border-radius: 2px 0 0 2px;
	  box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  outline: none;
	  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	  background-color: #fff;
	  font-family: Roboto;
	}

	#pac-container {
	  padding-bottom: 12px;
	  margin-right: 12px;
	}

	.pac-controls {
	  display: inline-block;
	  padding: 5px 11px;
	}

	.pac-controls label {
	  font-family: Roboto;
	  font-size: 13px;
	  font-weight: 300;
	}

	#pacinput,#pacinputpengambilan {
	  background-color: #fff;
	  font-family: Roboto;
	  font-size: 15px;
	  font-weight: 300;
	  margin: 10px 12px;
	  padding: 5px;
	  text-overflow: ellipsis;
	  width: 250px;
	}

	#pacinput:focus {
	  border-color: #4d90fe;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MasterUser.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
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
              <h2 class="no-margin-bottom">Lokasi User <?php echo $NamaPerson; ?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="MasterPersonAdd.php" ></a>
							<?php } ?>
						</li>
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Tambah Lokasi </h3>
								<div class="offset-lg-9">
									<a href="LokasiUserDetil.php?user=<?php echo $_GET['usr'];?>"><span class="btn btn-secondary">Kembali</span></a>
								</div>
							</div>
							<div class="card-body">	
								<div class="row">
								  <div class="col-lg-12">
									<form method="post" action="">
										<div class="form-group input-group">						
											<select name="Lokasi" class="form-control" autocomplete="off">	
												<option value="0" <?php if(@$_REQUEST['Lokasi']=='0'){echo 'selected';} ?>>Lokasi Di Luar Pasar</option>
												<option value="1" <?php if(@$_REQUEST['Lokasi']=='1'){echo 'selected';} ?>>Lokasi Daerah Pasar</option>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-info" type="submit">Pilih</button>
												
											</span>
										</div><hr>
									</form>
								   </div>
								  
								  <?php if (@$_REQUEST['Lokasi'] != '' OR @$_REQUEST['Lokasi'] != null) { ?>
								  <?php if (@$_REQUEST['Lokasi'] == '1'){ ?>
								  <div class="col-lg-10">
									<div class="form-group input-group">
										<?php $jsArray = "var arrData = new Array();\n"; ?>
										<select id="IDLapak"  onchange="changeValue(this.value)" class="form-control">
										  <?php
											echo "<option value=''>--- Lapak User ---</option>";
											$menu = mysqli_query($koneksi,"SELECT a.NamaPasar,b.BlokLapak,b.NomorLapak,b.IDLapak,a.KodeKec,a.KodeDesa,a.KodePasar FROM lapakperson b join mstpasar a on a.KodePasar=b.KodePasar WHERE b.IDPerson='".$IDPerson."' ORDER BY a.NamaPasar");
											while($kode = mysqli_fetch_array($menu)){
											  echo "<option value=\"".$kode['IDLapak']."\" >".$kode['NamaPasar']." : ".$kode['BlokLapak']." ".$kode['NomorLapak']."</option>\n";
												$jsArray .= "arrData['".$kode['IDLapak']."'] = {
												  IDLapak:'".addslashes($kode['IDLapak'])."',
												  NamaPasar:'".addslashes($kode['NamaPasar'])."',
												  BlokLapak:'".addslashes($kode['BlokLapak'])."',
												  NomorLapak:'".addslashes($kode['NomorLapak'])."',
												  
												};\n";
											}
										  ?>
										</select>
										<span class="input-group-btn">
											&nbsp;&nbsp;
											<a href="#" class='open_modal_item' data-idperson='<?php echo $IDPerson;?>'><span class="btn btn-secondary " title="Tambah Kelas Timbangan">Tambah Lapak</span></a>
											
										</span>
									</div>
								  </div>
								  <?php } ?>
								  
									 <div class="col-lg-6">
									  <form method="post" action="" >
										<div class="form-group-material">
										  <label>Nama Lokasi</label>
										  <input type="text" name="NamaLokasi"  id="arrBlok" class="form-control" placeholder="Nama Lokasi" value="<?php echo @$RowData['NamaLokasi'];?>" maxlength="150" required>
										</div>
										<div class="form-group-material">
										  <label>Alamat Lokasi</label>
										  <input type="text" name="AlamatLokasi" id="arrPasar" class="form-control" placeholder="Alamat Lokasi" value="<?php echo @$RowData['AlamatLokasi'];?>" maxlength="150">
										  <input type="hidden" name="Lapak" id="arrLapak" class="form-control">
										</div>
										
										<div class="form-group">
										  <label>Keterangan</label>
										  <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan" value="<?php echo @$RowData['Keterangan'];?>">
										</div>
										<?php 
										if (@$_REQUEST['Lokasi'] == '0'){ ?>
										<div class="form-group-material">
											<label>Nama Kecamatan</label>
											<select id="KodeKec" name="KodeKec" class="form-control" required>	
												<?php
													echo "<option value=''>--- Kecamatan ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeKec'] !== NULL){
															if($kode['KodeKec'] === $RowData['KodeKec']){
																echo "<option value=\"".$kode['KodeKec']."\" selected='selected'>".$kode['NamaKecamatan']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
														}
														
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
											<label>Nama Desa</label>
											<select id="KodeDesa" class="form-control" name="KodeDesa" required>
											
												<?php
													echo "<option value=''>--- Desa ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstdesa where KodeKec='".$RowData['KodeKec']."' ORDER BY NamaDesa");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeDesa'] !== NULL){
															if($kode['KodeDesa'] === $RowData['KodeDesa']){
																echo "<option value=\"".$kode['KodeDesa']."\" selected='selected'>".$kode['NamaDesa']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
														}
														
														$LAT = $RowData['KoorLat'];
														$LONG = $RowData['KoorLong'];
													}
												?>
											
											</select>
										</div>
										<div class="form-group-material">
											<label>Nama Dusun</label>
											<select id="KodeDusun" class="form-control" name="KodeDusun">
												<?php
													echo "<option value=''>--- Dusun ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstdusun where KodeDusun='".$RowData['KodeDusun']."' ORDER BY NamaDusun");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeDusun'] !== NULL){
															if($kode['KodeDusun'] === $RowData['KodeDusun']){
																echo "<option value=\"".$kode['KodeDusun']."\" selected='selected'>".$kode['NamaDusun']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeDusun']."\" >".$kode['NamaDusun']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeDusun']."\" >".$kode['NamaDusun']."</option>\n";
														}
													}
												?>
											
											</select>
										</div>
										<?php } ?>
									</div>
									<div class="col-lg-6">
										<?php if (@$_REQUEST['Lokasi'] == '0'){ ?>
										<input type="checkbox" name="GPS" value="1" id="myCheck" onclick="myFunction()"> Input Manual Latitude dan Longitude Maps<br>
										
										 <div id='mapGPS'  style="display:none">
										 <div class="form-row">
											<div class="form-group col-md-6">
											   <label>Latitude</label>
											   <input type="text" name="Latitude" id="lat1" class="form-control" placeholder="Latitude" maxlength="150">
											</div>
											<div class="form-group col-md-6">
											  <label>Longitude</label>
											  <input type="text" name="Longitude" id="lng1" class="form-control" placeholder="Longitude" maxlength="150">
											</div>
										</div>
										<div align="right">
											<input type="button" value="Set Lokasi" class="btn btn-secondary" onclick="pan()" />
										</div>
											<br><div id="map-canvas" style="height:350px;width:100%;margin-bottom:30px;"></div>
										 </div>
										<div class="form-group-material" id="mapAsli" style="display:block">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
										<?php } ?>
								  </div>
									 <div class="col-lg-12">
										<div class="text-right">
											<?php
											
												echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
												echo ' <input type="hidden" name="IDPerson" value="'.$IDPerson.'">';
												echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
												echo '<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Simpan</button>';
										
											?>
										</div>
									  </form>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <?php if (@$RowData['IDLapak'] != '' OR @$RowData['IDLapak'] != null){ ?>
								  <div class="col-lg-10">
									<div class="form-group input-group">
										<?php $jsArray = "var arrData = new Array();\n"; ?>
										<select id="IDLapak"  onchange="changeValue(this.value)" class="form-control">
										  <?php
											echo "<option value=''>--- Lapak User ---</option>";
											$menu = mysqli_query($koneksi,"SELECT a.NamaPasar,b.BlokLapak,b.NomorLapak,b.IDLapak,a.KodeKec,a.KodeDesa,a.KodePasar FROM lapakperson b join mstpasar a on a.KodePasar=b.KodePasar WHERE b.IDPerson='".$IDPerson."' ORDER BY a.NamaPasar");
											while($kode = mysqli_fetch_array($menu)){
												
												if($kode['IDLapak'] === $RowData['IDLapak']){
													echo "<option value=\"".$kode['IDLapak']."\" selected='selected'>".$kode['NamaPasar']." : ".$kode['BlokLapak']." ".$kode['NomorLapak']."</option>\n";
												}else{
													echo "<option value=\"".$kode['IDLapak']."\" >".$kode['NamaPasar']." : ".$kode['BlokLapak']." ".$kode['NomorLapak']."</option>\n";
												}
												$jsArray .= "arrData['".$kode['IDLapak']."'] = {
												  IDLapak:'".addslashes($kode['IDLapak'])."',
												  NamaPasar:'".addslashes($kode['NamaPasar'])."',
												  BlokLapak:'".addslashes($kode['BlokLapak'])."',
												  NomorLapak:'".addslashes($kode['NomorLapak'])."',
												  
												};\n";
											}
										  ?>
										</select>
										<span class="input-group-btn">
											&nbsp;&nbsp;
											<a href="#" class='open_modal_item' data-idperson='<?php echo $IDPerson;?>'><span class="btn btn-secondary " title="Tambah Kelas Timbangan">Tambah Lapak</span></a>
											
										</span>
									</div>
								  </div>
								  <?php } ?>
								  
									 <div class="col-lg-6">
									  <form method="post" action="" >
										<div class="form-group-material">
										  <label>Nama Lokasi</label>
										  <input type="text" name="NamaLokasi"  id="arrBlok" class="form-control" placeholder="Nama Lokasi" value="<?php echo @$RowData['NamaLokasi'];?>" maxlength="150" required>
										</div>
										<div class="form-group-material">
										  <label>Alamat Lokasi</label>
										  <input type="text" name="AlamatLokasi" id="arrPasar" class="form-control" placeholder="Alamat Lokasi" value="<?php echo @$RowData['AlamatLokasi'];?>" maxlength="150">
										  <input type="hidden" name="Lapak" id="arrLapak"   value="<?php echo @$RowData['IDLapak'];?>" class="form-control">
										</div>
										
										<div class="form-group">
										  <label>Keterangan</label>
										  <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan" value="<?php echo @$RowData['Keterangan'];?>">
										</div>
										<?php 
										if (@$RowData['IDLapak'] == '' OR @$RowData['IDLapak'] == null){ ?>
										<div class="form-group-material">
											<label>Nama Kecamatan</label>
											<select id="KodeKec" name="KodeKec" class="form-control" >	
												<?php
													echo "<option value=''>--- Kecamatan ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeKec'] !== NULL){
															if($kode['KodeKec'] === $RowData['KodeKec']){
																echo "<option value=\"".$kode['KodeKec']."\" selected='selected'>".$kode['NamaKecamatan']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
														}
														
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
											<label>Nama Desa</label>
											<select id="KodeDesa" class="form-control" name="KodeDesa" >
												<?php
													echo "<option value=''>--- Desa ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstdesa where KodeKec='".$RowData['KodeKec']."' ORDER BY NamaDesa");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeDesa'] !== NULL){
															if($kode['KodeDesa'] === $RowData['KodeDesa']){
																echo "<option value=\"".$kode['KodeDesa']."\" selected='selected'>".$kode['NamaDesa']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
														}
														
														$LAT = $RowData['KoorLat'];
														$LONG = $RowData['KoorLong'];
													}
												?>
											
											</select>
										</div>
										<div class="form-group-material">
											<label>Nama Dusun</label>
											<select id="KodeDusun" class="form-control" name="KodeDusun">
												<?php
													echo "<option value=''>--- Dusun ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstdusun where KodeDusun='".$RowData['KodeDusun']."' ORDER BY NamaDusun");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeDusun'] !== NULL){
															if($kode['KodeDusun'] === $RowData['KodeDusun']){
																echo "<option value=\"".$kode['KodeDusun']."\" selected='selected'>".$kode['NamaDusun']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeDusun']."\" >".$kode['NamaDusun']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeDusun']."\" >".$kode['NamaDusun']."</option>\n";
														}
													}
												?>
											
											</select>
										</div>
										<?php } ?>
									</div>
									<div class="col-lg-6">
										<?php if (@$RowData['IDLapak'] == '' OR @$RowData['IDLapak'] == null){ ?>
										<div class="form-group-material">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
										<?php } ?>
								  </div>
									 <div class="col-lg-12">
										<div class="text-right">
											<?php
												echo '<input type="hidden" name="IDPerson" value="'.$RowData['IDPerson'].'"> ';
												echo '<input type="hidden" name="KodeLokasi" value="'.$RowData['KodeLokasi'].'"> ';
												echo '<input type="hidden" name="login_id" value="'.$login_id.'"> ';
												echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button>&nbsp;';
												echo '<a href="LokasiUserDetil.php?user='.base64_encode($RowData['IDPerson']).'"><span class="btn btn-warning">Batalkan</span></a>';
											?>
										</div>
									  </form>
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
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div>
	
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
	<script src="../library/select2master/js/select2.min.js"></script>
	
	<script type="text/javascript">

	$(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var id_prs = $(this).data("idperson");
		  var sts = $(this).data("status");
		  	   $.ajax({
					   url: "Modal/AddLapak.php",
					   type: "GET",
					   data : {IDPerson: id_prs, Aksi:sts},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	
	<?php echo @$jsArray; ?>
	function changeValue(id){
		document.getElementById('arrPasar').value = arrData[id].NamaPasar;
		document.getElementById('arrBlok').value = arrData[id].BlokLapak+" "+arrData[id].NomorLapak;
		document.getElementById('arrLapak').value = arrData[id].IDLapak;	
	};
	
	</script>
	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript" src="../komponen/js/jquery.form.min.js"></script>
	
	<script>
	function myFunction() {
	  // Get the checkbox
	  var checkBox = document.getElementById("myCheck");
	  // Get the output text
	  var gps = document.getElementById("mapGPS");
	  var asli = document.getElementById("mapAsli");

	  // If the checkbox is checked, display the output text
	  if (checkBox.checked == true){
		gps.style.display = "block";
		asli.style.display = "none";
	  } else {
		gps.style.display = "none";
		asli.style.display = "block";
	  }
	}

	
	
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
	  
	  $("#KodeDesa").change(function(){
		var KodeDesa = $("#KodeDesa").val();
		$.ajax({
			url: "../library/Dropdown/ambil-dusun.php",
			data: "KodeDesa="+KodeDesa,
			cache: false,
			success: function(msg){
				$("#KodeDusun").html(msg);
			}
		});
	  });
	  
	});
	
	var map1;
	var markersArray=[];
	   function initialize() {
		var mapOptions = {
			zoom: 13,
			center: new google.maps.LatLng(-7.556032627191996,112.221),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map1 = new google.maps.Map(document.getElementById('map-canvas'),
		mapOptions);
	 }

	google.maps.event.addDomListener(window, 'load', initialize);
	function pan() {
	deleteOverlays();
		var panPoint = new google.maps.LatLng(document.getElementById("lat1").value,     document.getElementById("lng1").value);
		map1.setCenter(panPoint)
		var marker1 = new google.maps.Marker({
				map: map1,
				position: panPoint,
			});
			markersArray.push(marker1);

	 }

	 function deleteOverlays() {
	   if (markersArray) {
		  for (i in markersArray) {
		   markersArray[i].setMap(null);
		  }
		markersArray.length = 0;
	  }
	 }
	</script>
	
	<?php
		//Post data user simpan data baru
		@$NamaLokasi			= htmlspecialchars($_POST['NamaLokasi']);
		// @$JenisLokasi			= htmlspecialchars($_POST['JenisLokasi']);
		@$AlamatLokasi			= htmlspecialchars($_POST['AlamatLokasi']);
		@$Keterangan			= htmlspecialchars($_POST['Keterangan']);
		@$Lat					= htmlspecialchars($_POST['Lat']);
		@$Lng					= htmlspecialchars($_POST['Lng']);
		@$KodeKec				= htmlspecialchars($_POST['KodeKec']);
		@$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
		@$KodeKab				= htmlspecialchars($_POST['KodeKab']);
		@$IdPerson				= htmlspecialchars($_POST['IDPerson']);
		@$login_id				= htmlspecialchars($_POST['login_id']);
		@$KodeLokasi			= htmlspecialchars($_POST['KodeLokasi']);
		@$IDLapak				= htmlspecialchars($_POST['IDLapak']);
		@$KodeDusun				= htmlspecialchars($_POST['KodeDusun']);
		@$Lapak					= htmlspecialchars($_POST['Lapak']);
		@$GPS					= htmlspecialchars($_POST['GPS']);
		@$Latitude				= htmlspecialchars($_POST['Latitude']);
		@$Longitude				= htmlspecialchars($_POST['Longitude']);
		
		
		if(isset($_POST['Simpan'])){		
			$Tahun=date('Y');
			$sql = "SELECT RIGHT(KodeLokasi,8) AS kode FROM lokasimilikperson WHERE KodeLokasi LIKE '%".$Tahun."%' AND IDPerson='$IdPerson' ORDER BY KodeLokasi DESC LIMIT 1";
			$res = mysqli_query($koneksi, $sql);
			$result = mysqli_fetch_array($res);
			if ($result['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$result['kode'];
			}
			$bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
			$kode_jadi	 = "LKS-".$Tahun."-".$bikin_kode;
			 
			 if($Lapak == '' OR $Lapak == null) {
				if ($GPS == '1') {
					$latAsli = $Latitude;
					$lngAsli = $Longitude;
				}else{
					$latAsli = $Lat;
					 $cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='$Lng'");
					 $num2 = mysqli_num_rows($cek2);
					 if($num2 > 0 ){
						@$lngAsli = $Lng+0.0001;
					 }else{
						@$lngAsli = $Lng;
					 }
				}
				 
				 $query = mysqli_query($koneksi,"INSERT into lokasimilikperson (KodeLokasi,NamaLokasi,AlamatLokasi,KoorLat,KoorLong,Keterangan,IDPerson,KodeKec,KodeDesa,KodeKab,IDLapak,KodeDusun) VALUES ('$kode_jadi','$NamaLokasi','$AlamatLokasi','$latAsli','$lngAsli','$Keterangan','$IdPerson','$KodeKec','$KodeDesa','$KodeKab','$IDLapak','$KodeDusun')");
				 
			 }else{
				 $cek3 = mysqli_query($koneksi,"select KodePasar from lapakpasar where IDLapak='$Lapak'");
				 $num3 = mysqli_fetch_array($cek3);
				 
				$cek1 = mysqli_query($koneksi,"select NamaPasar,KodeDesa,KodeKec,KodeKab,KoorLat,KoorLong from mstpasar where KodePasar='".$num3[0]."'");
				$num1 = mysqli_fetch_array($cek1);
				 
				$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='".$num1[5]."'");
				 $num2 = mysqli_num_rows($cek2);
				 if($num2 > 0 ){
					@$Lng1 = $num1[5]+0.0001;
				 }else{
					@$Lng1 = $num1[5];
				 }
				 
				 mysqli_query($koneksi, "Update mstperson set JenisPerson='Timbangan#Pedagang##' where IDPerson='$IdPerson'");

			
				 $query = mysqli_query($koneksi,"INSERT into lokasimilikperson (KodeLokasi,NamaLokasi,AlamatLokasi,KoorLat,KoorLong,IDPerson,KodeKec,KodeDesa,KodeKab,IDLapak,KodePasar) VALUES ('$kode_jadi','$NamaLokasi','$AlamatLokasi','".$num1['KoorLat']."','$Lng1','$IdPerson','".$num1['KodeKec']."','".$num1['KodeDesa']."','".$num1['KodeKab']."','$Lapak','".$num3[0]."')");
			
			 }
			
			if($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Lokasi User dengan ID '.$IdPerson, $login_id, $kode_jadi, 'Lokasi User');
				echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.base64_encode($IdPerson).'";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "LokasiUserDetil.php?user='.base64_encode($IdPerson).'";
					  });
					  </script>';
			}
			 
		}
	
	if(isset($_POST['SimpanLapak'])){
		@$KodePasar			= htmlspecialchars($_POST['KodePasar']);
		@$BlokLapak			= htmlspecialchars($_POST['BlokLapak']);
		@$NomorLapak	 	= htmlspecialchars($_POST['NomorLapak']);
		@$Retribusi		 	= htmlspecialchars($_POST['Retribusi']);
		@$IDPerson1		 	= htmlspecialchars($_POST['IDPerson']);
		@$Aksi			 	= htmlspecialchars($_POST['Aksi']);
		
		$sql = mysqli_query($koneksi,'SELECT RIGHT(IDLapak,10) AS kode FROM lapakpasar ORDER BY IDLapak DESC LIMIT 1');  
			$nums = mysqli_num_rows($sql);
			 
			if($nums <> 0){
				$data = mysqli_fetch_array($sql);
				$kode = $data['kode'] + 1;
			}else{
				$kode = 1;
			}
			 
			//mulai bikin kode
			 $bikin_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
			 $kode_jadi = "LPK-".$bikin_kode;
			 
			$query = mysqli_query($koneksi,"INSERT lapakpasar (KodePasar,BlokLapak,NomorLapak,Retribusi,IDLapak) 
			VALUES ('$KodePasar','$BlokLapak','$NomorLapak','$Retribusi','$kode_jadi')");
			if($query){
				$person = mysqli_query($koneksi,"INSERT lapakperson (KodePasar,BlokLapak,NomorLapak,Retribusi,IDLapak,IDPerson,TglAktif,IsAktif) 
				VALUES ('$KodePasar','$BlokLapak','$NomorLapak','$Retribusi','$kode_jadi','$IDPerson1', NOW(), b'1' )");
			
				if($person){
					InsertLog($koneksi, 'Tambah Data', 'Menambah Data Lapak Pasar '.$BlokLapak, $login_id, $kode_jadi, 'Master Lapak Pasar');
					echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="LokasiUserAdd.php?usr='.base64_encode($IDPerson1).'&Lokasi=1&id='.base64_encode($Aksi).'";</script>';
				
				}
			
				
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "LokasiUserAdd.php?usr='.base64_encode($IDPerson1).'&Lokasi=1&id='.base64_encode($Aksi).'";
					  });
					  </script>';
			}
			
	}
	if(isset($_POST['SimpanEdit'])){
		
		if($Lapak == '' OR $Lapak == null) {
			

		    $cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='$Lng'");
		    $num2 = mysqli_num_rows($cek2);
			
			if($num2 > 0 ){
				@$Lng1 = $Lng+0.0001;
			}else{
				@$Lng1 = $Lng;
			}
				 
			//query update  
			$query = mysqli_query($koneksi,"UPDATE lokasimilikperson SET NamaLokasi='$NamaLokasi',AlamatLokasi='$AlamatLokasi',Keterangan='$Keterangan',KodeKec='$KodeKec',KodeDesa='$KodeDesa',KoorLat='$Lat',KoorLong='$Lng1',KodeDusun='$KodeDusun' WHERE IDPerson='$IdPerson' AND KodeLokasi='$KodeLokasi'");
				 
		}else{
			
			$cek3 = mysqli_query($koneksi,"select KodePasar from lapakpasar where IDLapak='$Lapak'");
			$num3 = mysqli_fetch_array($cek3);
				 
			$cek1 = mysqli_query($koneksi,"select NamaPasar,KodeDesa,KodeKec,KodeKab,KoorLat,KoorLong,KodeDusun from mstpasar where KodePasar='".$num3[0]."'");
			$num1 = mysqli_fetch_array($cek1);
				 
			$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='".$num1[5]."'");
			$num2 = mysqli_num_rows($cek2);
			 if($num2 > 0 ){
				@$Lng1 = $num1[5]+0.0001;
			 }else{
				@$Lng1 = $num1[5];
			 }
			// query update  
			$query = mysqli_query($koneksi,"UPDATE lokasimilikperson SET NamaLokasi='$NamaLokasi',AlamatLokasi='$AlamatLokasi',Keterangan='$Keterangan',KodeKec='".$num1['KodeKec']."',KodeDesa='".$num1['KodeDesa']."',KoorLat='".$num1['KoorLat']."',KoorLong='$Lng1',KodeDusun='".$num1['KodeDusun']."',IDLapak='$Lapak',KodePasar='".$num3[0]."' WHERE IDPerson='$IdPerson' AND KodeLokasi='$KodeLokasi'");	 
					
			
		}
		
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Lokasi User dengan ID '.$IdPerson, $login_id, $KodeLokasi, 'Lokasi User');
			echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.base64_encode($IdPerson).'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "LokasiUserDetil.php?user='.base64_encode($IdPerson).'";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){	
		$query = mysqli_query($koneksi,"delete from lokasimilikperson WHERE KodeLokasi='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['prs']))."'");
		if($query){
					
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Lokasi User atas id '.base64_decode($_GET['id']).' dengan nama lokasi '.base64_decode(@$_GET['tm']), base64_decode(@$_GET['nm']), base64_decode(@$_GET['id']), 'Lokasi User');
				
			echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.$_GET['prs'].'"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "LokasiUserDetil.php?user='.$_GET['prs'].'";
					  });
					  </script>';
		}
	}
	
	function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	
	?>
  </body>
</html>