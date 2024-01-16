<?php
include 'akses.php';
// @$fitur_id = 2;
// include '../library/lock-menu.php';
$Page = 'MasterPerson';
$Mode = htmlspecialchars($_GET['v']);

$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	@$res = explode("#", $RowData['JenisPerson']);
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
              <h2 class="no-margin-bottom">Master User </h2>
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
							<?php if (@$cek_fitur['AddData'] =='1'){ ?>
							<a href="MasterPersonAdd.php" ></a>
							<?php } ?>
						</li>
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Tambah User</h3>
								<div class="offset-lg-9">
									<a href="MasterPerson.php?v=<?=$Mode?>"><span class="btn btn-secondary">Kembali</span></a>
								</div>
							</div>
							<div class="card-body">	
								<div class="row">
								  <div class="col-lg-12">
									<form method="post" action="">
										<div class="form-group input-group">						
											<select name="IsPerusahaan" class="form-control" autocomplete="off">	
												<option value="0" <?php if(@$_REQUEST['IsPerusahaan']=='0'){echo 'selected';} ?>>Perorangan</option>
												<option value="1" <?php if(@$_REQUEST['IsPerusahaan']=='1'){echo 'selected';} ?>>Perusahaan</option>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-info" type="submit">Pilih</button>
												
											</span>
										</div><hr>
									</form>
									 <form method="post" <?php if(@$_GET['id']!=null){ echo 'action=""'; }else{ echo 'action="SimpanData/UploadFotoPerson.php" enctype="multipart/form-data"'; }?> >
								  </div>
								  <?php if (@$_REQUEST['IsPerusahaan'] != '' OR @$_REQUEST['IsPerusahaan'] != null) { ?>
								  <div class="col-lg-6">
										<h4><u><?php if (@$_REQUEST['IsPerusahaan'] == '1') { echo 'Data Perusahaan'; }else{ echo 'Data User'; } ?></u></h4><br>
										<div class="form-group-material">
										  <label><?php if (@$_REQUEST['IsPerusahaan'] == '1') { echo 'Nama Instansi/Pemilik'; }else{ echo 'Nama Pemilik'; } ?></label>
										  <input type="text" name="NamaPerson" class="form-control" placeholder="Nama " value="<?php echo @$RowData['NamaPerson'];?>" maxlength="255" required>
										   <input type="hidden" name="Perusahaan" value="<?php echo @$_REQUEST['IsPerusahaan'];?>" >
										   <input type="hidden" name="Mode" value="<?php echo @$Mode;?>" >
										   <?php 
											if ($Mode == 'Timbangan'){
												echo '<input type="hidden" name="Timbangan" value="Timbangan">';
											}elseif($Mode == 'Pedagang'){
												echo '<input type="hidden" name="Pedagang" value="Pedagang">';
											}elseif($Mode == 'PupukSub' ){
												echo '<input type="hidden" name="PupukSub" value="PupukSub">';											
											}elseif($Mode == 'Toko'){
												echo '<input type="hidden" name="Toko" value="Toko">';
											}
										   ?>
										</div>
										<?php if (@$_REQUEST['IsPerusahaan'] == '0') { ?>
										<div class="form-group-material">
										  <label>No KTP</label>
										  <input type="number" name="NIK" class="form-control" placeholder="No KTP" value="<?php echo @$RowData['NIK'];?>" maxlength="20" required>
										</div>
										<?php } ?>
										<div class="form-group-material">
										  <label>No Telephon atau Handphone</label>
										  <input type="number" name="NoHP" class="form-control" placeholder="No Telephon atau Handphone" value="<?php echo @$RowData['NoHP'];?>" maxlength="16">
										</div>
										<div class="form-group-material">
										  <label>No Rekening Bank</label>
										  <input type="text" name="NoRekeningBank" class="form-control" placeholder="No Rekening Bank" value="<?php echo @$RowData['NoRekeningBank'];?>" maxlength="255">
										</div>
										<div class="form-group-material">
										  <label>Atas Nama Rekening Bank</label>
										  <input type="text" name="AnRekBank" class="form-control" placeholder="Atas Nama Rekening Bank" value="<?php echo @$RowData['AnRekBank'];?>" maxlength="255">
										</div>
										<div class="form-group-material">
										  <label>Alamat Lengkap</label>
										  <input type="text" name="AlamatLengkapPerson" class="form-control" placeholder="Alamat Lengkap" value="<?php echo @$RowData['AlamatLengkapPerson'];?>" maxlength="255" required>
										</div>
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
									</div>
									<div class="col-lg-6">
										<?php if(@$_REQUEST['IsPerusahaan'] == '1'){ ?>
										<h4><u>Data Penanggung Jawab</u></h4><br>
										<input type="checkbox" name="Timbangan" value="Timbangan" id="myCheck" onclick="myFunction()"> Pilih Data Penanggung Jawab yang ada
										<div class="form-group" id="haha"  style="display:none">
										</br><label>Pilih Penanggung Jawab</label>
										<?php $jsArray = "var arrData = new Array();\n"; ?>
										<select id="CariPenduduk" name="IDPerson" onchange="changeValue(this.value)" style="width: 100% !important;">
										  <option value=""></option>
										  <?php
											
											$menu = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE IsPerusahaan = '0'  and UserName != 'dinas' and IsVerified='1' and JenisPerson LIKE '%Timbangan%' ORDER BY NamaPerson");
											while($kode = mysqli_fetch_array($menu)){
											  echo "<option value=\"".$kode['IDPerson']."\" >".$kode['NamaPerson']."</option>\n";
												$jsArray .= "arrData['".$kode['IDPerson']."'] = {
												  IDPerson:'".addslashes($kode['IDPerson'])."',
												  NIK:'".addslashes($kode['NIK'])."',
												  NamaPerson:'".addslashes($kode['NamaPerson'])."'
												  
												};\n";
											}
										  ?>
										  </select>
										</div>
										<br><div class="form-group">
										  <label>Nama Penanggung Jawab</label>
										  <input type="text" id="arrPJ" name="PJPerson" class="form-control" placeholder="Nama Penanggung Jawab" value="<?php echo @$RowData['PJPerson'];?>" maxlength="255" required><small class="form-text">Silhakan Masukkan Nama Jika Belum ada di Pilihan </small>
										</div>
										<?php if (@$_REQUEST['IsPerusahaan'] == '1') { ?>
										<div class="form-group-material">
										  <label>No KTP</label>
										  <input type="number" id="arrNIK" name="NIK" class="form-control" placeholder="No KTP" value="<?php echo @$RowData['NIK'];?>" maxlength="20" required>
										</div>
										<?php } ?>
										<div class="form-group-material">
										  <input type="hidden" id="arrID" name="IDPerson" class="form-control" placeholder="ID Person" value="<?php echo @$RowData['IDPerson'];?>" maxlength="255" required>
										</div>
										<?php } ?>
										<h4><u>Data Foto</u></h4><br>
										<div class="form-group-material">
											<label>Upload Foto Pemohon type .jpg/.png 2MB </label>
											<div class="form-group">
												<br>
												<input type="file" name="filefoto1" id="filefoto1" style="width: 100%;" >
											</div><br>
											
											<label>Upload Foto KTP type .jpg/.png 2MB </label>
											<div class="form-group">
												<br><input type="file" name="filefoto2" id="filefoto2" style="width: 100%;" >
											</div>
											
											<img src="../web/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
												<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
											<div class="text-center">
												<div align="center" id="output"></div>
											</div>
										</div>
										<h4><u>Data Lokasi</u></h4><br>
										<div class="form-group-material">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
										<div class="form-group-material alert alert-success">
											<p>Nb.</p>
											<p>Username login adalah NIK </p>
											<p>Password default <i>123456</i></p>
										</div>
									</div>
									 <div class="col-lg-12">
										<div class="text-right">
											<?php
											if(@$_GET['id']==null){
												echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
												echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
												echo '<button type="submit" class="btn btn-primary" id="submit-btn" name="Simpan">Simpan</button>';
											}else{
												echo '<input type="hidden" name="IDPerson" value="'.$RowData['IDPerson'].'"> ';
												echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
												echo '<a href="MasterUser.php"><span class="btn btn-warning">Batalkan</span></a>';
											}
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
								  <div class="col-lg-12">
									 <form method="post" action="">
									<select name="IsPerusahaan" class="form-control" autocomplete="off" disabled>	
										<option value="0" <?php if(@$RowData['IsPerusahaan']=='0'){echo 'selected';} ?>>Perorangan</option>
										<option value="1" <?php if(@$RowData['IsPerusahaan']=='1'){echo 'selected';} ?>>Perusahaan</option>
									</select><hr>
								  </div>
								  <div class="col-lg-6">
										<h4><u><?php if (@$RowData['IsPerusahaan'] == '1') { echo 'Data Perusahaan'; }else{ echo 'Data User'; } ?></u></h4><br>
										<div class="form-group-material">
										  <label><?php if (@$RowData['IsPerusahaan'] == '1') { echo 'Nama Instansi/Pemilik'; }else{ echo 'Nama Pemilik'; } ?></label>
										  <input type="text" name="NamaPerson" class="form-control" placeholder="Nama " value="<?php echo @$RowData['NamaPerson'];?>" maxlength="255" required>
										   <input type="hidden" name="Perusahaan" value="<?php echo @$RowData['IsPerusahaan'];?>" >
										   <input type="hidden" name="JenisPerson" value="<?php echo @$RowData['JenisPerson'];?>" >
										   <input type="hidden" name="KlasifikasiUser" value="<?php echo @$RowData['KlasifikasiUser'];?>" >
										</div>
										<?php if (@$RowData['IsPerusahaan'] == '0') { ?>
										<div class="form-group-material">
										  <label>No KTP</label>
										  <input type="number" name="NIK" class="form-control" placeholder="No KTP" value="<?php echo @$RowData['NIK'];?>" maxlength="20" required>
										</div>
										<?php } ?>
										<div class="form-group-material">
										  <label>No Telephon atau Handphone</label>
										  <input type="number" name="NoHP" class="form-control" placeholder="No Telephon atau Handphone" value="<?php echo @$RowData['NoHP'];?>" maxlength="16">
										</div>
										<div class="form-group-material">
										  <label>No Rekening Bank</label>
										  <input type="text" name="NoRekeningBank" class="form-control" placeholder="No Rekening Bank" value="<?php echo @$RowData['NoRekeningBank'];?>" maxlength="255">
										</div>
										<div class="form-group-material">
										  <label>Atas Nama Rekening Bank</label>
										  <input type="text" name="AnRekBank" class="form-control" placeholder="Atas Nama Rekening Bank" value="<?php echo @$RowData['AnRekBank'];?>" maxlength="255">
										</div>
										<div class="form-group-material">
										  <label>Alamat Lengkap</label>
										  <input type="text" name="AlamatLengkapPerson" class="form-control" placeholder="Alamat Lengkap" value="<?php echo @$RowData['AlamatLengkapPerson'];?>" maxlength="255" required>
										</div>
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
										<?php	
											echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
											echo '<input type="hidden" name="KodePerson" value="'.$RowData['IDPerson'].'"> ';
											echo '<input type="hidden" name="Mode" value="'.$Mode.'"> ';
											echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
											echo '<a href="MasterPerson.php?v='.$Mode.'"><span class="btn btn-warning">Batalkan</span></a>'; ?>
									</div>
									<div class="col-lg-6">
										<?php if(@$RowData['IsPerusahaan'] == '1'){ ?>
										<h4><u>Data Penanggung Jawab</u></h4><br>
										<input type="checkbox" name="Timbangan" value="Timbangan" id="myCheck" onclick="myFunction()"> Pilih Data Penanggung Jawab yang ada
										<div class="form-group" id="haha"  style="display:none">
										</br><label>Pilih Penanggung Jawab</label>
										<?php $jsArray = "var arrData = new Array();\n"; ?>
										<select id="CariPenduduk" name="IDPerson" onchange="changeValue(this.value)" style="width: 100% !important;">
										  <option value=""></option>
										  <?php
											
											$menu = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE IsPerusahaan = '0'  and UserName != 'dinas' and IsVerified='1' and JenisPerson LIKE '%Timbangan%' ORDER BY NamaPerson");
											while($kode = mysqli_fetch_array($menu)){
											  echo "<option value=\"".$kode['IDPerson']."\" >".$kode['NamaPerson']."</option>\n";
												$jsArray .= "arrData['".$kode['IDPerson']."'] = {
												  IDPerson:'".addslashes($kode['IDPerson'])."',
												   NIK:'".addslashes($kode['NIK'])."',
												  NamaPerson:'".addslashes($kode['NamaPerson'])."'
												};\n";
											}
										  ?>
										  </select>
										</div>
										<br><div class="form-group">
										  <label>Nama Penanggung Jawab</label>
										  <input type="text" id="arrPJ" name="PJPerson" class="form-control" placeholder="Nama Penanggung Jawab" value="<?php echo @ NamaPerson($koneksi, $RowData['PJPerson']);?>" maxlength="255" required><small class="form-text">Silhakan Masukkan Nama Jika Belum ada di Pilihan </small>
										</div>
										<div class="form-group-material">
										  <label>No KTP</label>
										  <input type="number" id="arrNIK" name="NIK" class="form-control" placeholder="No KTP" value="<?php echo @$RowData['NIK'];?>" maxlength="20" required>
										</div>
										<?php } ?>
										
										<h4><u>Data Lokasi</u></h4><br>
										<div class="form-group-material">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
									 </form>
									 <h4><u>Data Foto</u></h4><br>
									  <div class="form-group-material">
											<label>Upload Gambar Person type .jpg/.png 2MB</label>
											<div class="row">
												<form action="SimpanData/UploadFotoPerson.php?id=<?php echo base64_encode($RowData['IDPerson']);?>&login=<?php echo base64_encode($login_id); ?>"  method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto"  class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="Mode" value="<?=$Mode?>">
														<input type="hidden" name="nourut" value=" <?php echo '1'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  class="btn btn-info">Upload</button>
															<?php if ($RowData['GambarPerson'] != '' OR $RowData['GambarPerson']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['GambarPerson'];?>' data-url='<?php echo 'FotoPerson';?>'><i  class="btn btn-success ">Preview</i></a>
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<div class="row">
											&nbsp;&nbsp;&nbsp;<label>Upload Gambar KTP Pemohon type .jpg/.png 2MB</label>
												<form action="SimpanData/UploadFotoPerson.php?id=<?php echo base64_encode($RowData['IDPerson']);?>&login=<?php echo base64_encode($login_id); ?>" method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="Mode" value="<?=$Mode?>">
														<input type="hidden" name="nourut" value=" <?php echo '2'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  class="btn btn-info">Upload</button>
															<?php if ($RowData['FotoKTP'] != '' OR $RowData['FotoKTP']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoKTP'];?>' data-url='<?php echo 'FotoPerson';?>'><i  class="btn btn-success ">Preview</i></a>
															
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<div class="form-group-material alert alert-success">
												<p>Nb.</p>
												<p>Username login adalah NIK </p>
												<p>Password default <i>123456</i></p>
											</div>
										</div>
									</div>
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
	<div id="ModalDokumen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

	
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
	function myFunction() {
	  // Get the checkbox
	  var checkBox = document.getElementById("myCheck");
	  // Get the output text
	  var text = document.getElementById("haha");

	  // If the checkbox is checked, display the output text
	  if (checkBox.checked == true){
		text.style.display = "block";
	  } else {
		text.style.display = "none";
	  }
	}

	$(document).ready(function () {
		$("#CariPenduduk").select2({
			placeholder: "Nama Penanggung Jawab"
		});
	  });
	
	<?php echo $jsArray; ?>
	function changeValue(id){
		document.getElementById('arrNIK').value = arrData[id].NIK;
		document.getElementById('arrPJ').value = arrData[id].NamaPerson;
		document.getElementById('arrID').value = arrData[id].IDPerson;
	
	};
	</script>
	<script type="text/javascript">
		//open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_view").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
		  var url_foto  = $(this).data("url");
			   $.ajax({
					   url: "ViewFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen, URLocation: url_foto},
					   success: function (ajaxData){
					   $("#ModalDokumen").html(ajaxData);
					   $("#ModalDokumen").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript" src="../komponen/js/jquery.form.min.js"></script>
	
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
	</script>
	
	<?php
		//Post data user simpan data baru
		@$NamaPerson			= htmlspecialchars($_POST['NamaPerson']);
		@$PJPerson				= htmlspecialchars($_POST['PJPerson']);
		@$AlamatLengkapPerson	= htmlspecialchars($_POST['AlamatLengkapPerson']);
		@$IsPerusahaan			= htmlspecialchars($_POST['Perusahaan']);
		@$KodeKec				= htmlspecialchars($_POST['KodeKec']);
		@$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
		@$KodeKab				= htmlspecialchars($_POST['KodeKab']);
		@$KodeDusun				= htmlspecialchars($_POST['KodeDusun']);
		@$Lat					= htmlspecialchars($_POST['Lat']);
		@$Lng					= htmlspecialchars($_POST['Lng']);
		@$IDPerson				= htmlspecialchars($_POST['IDPerson']);
		@$NoRekeningBank		= htmlspecialchars($_POST['NoRekeningBank']);
		@$AnRekBank				= htmlspecialchars($_POST['AnRekBank']);
		@$NIK					= htmlspecialchars($_POST['NIK']);
		@$KodePerson			= htmlspecialchars($_POST['KodePerson']);
		@$KlasifikasiUser		= htmlspecialchars($_POST['KlasifikasiUser']);
		@$JenisPerson			= htmlspecialchars($_POST['JenisPerson']);
		@$NoHP					= htmlspecialchars($_POST['NoHP']);
		@$Password				= base64_encode('123456');
		@$Mode					= htmlspecialchars($_POST['Mode']);

	if(isset($_POST['SimpanEdit'])){
		if ($IsPerusahaan == '0' ){
				
			$query = mysqli_query($koneksi,"UPDATE mstperson SET NamaPerson='$NamaPerson',AlamatLengkapPerson='$AlamatLengkapPerson',KodeKec='$KodeKec',KodeDesa='$KodeDesa',NamaJalan='$AlamatLengkapPerson',KoorLat='$Lat',KoorLong='$Lng', NoRekeningBank='$NoRekeningBank', AnRekBank='$AnRekBank',KodeDusun='$KodeDusun',NIK='$NIK',NoHP='$NoHP' WHERE IDPerson='$KodePerson'");
				
				
		}else{
			if ($IDPerson == '' OR $IDPerson == null){
				//kode jadi
					$sql1 	 = mysqli_query($koneksi,'SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1');  
					$num1	 = mysqli_num_rows($sql1);
					if($num1 <> 0){
						$data1 = mysqli_fetch_array($sql1);
						$kode1 = $data1['kode1'] + 1;
					}else{
						$kode1 = 1;
					}
					//mulai bikin kode
					 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
					 $kode_jadi2 = "PRS-".$Tahun."-".$bikin_kode1;	
		 
					 
				$result = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,NoRekeningBank,AnRekBank,NIK,KodeDusun,KlasifikasiUser,NoHP) VALUES ('$kode_jadi2','$PJPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NoRekeningBank','$AnRekBank','$NIK','$KodeDusun','$KlasifikasiUser','$NoHP')");
							
				if ($result){
					$query = mysqli_query($koneksi,"UPDATE mstperson SET PJPerson='$kode_jadi2',NamaPerson='$NamaPerson',AlamatLengkapPerson='$AlamatLengkapPerson',KodeKec='$KodeKec',KodeDesa='$KodeDesa',NamaJalan='$AlamatLengkapPerson',KoorLat='$Lat',KoorLong='$Lng', NoRekeningBank='$NoRekeningBank', AnRekBank='$AnRekBank',KodeDusun='$KodeDusun',NoHP='$NoHP' WHERE IDPerson='$KodePerson'");
				}
			}else{
			
				$query = mysqli_query($koneksi,"UPDATE mstperson SET NamaPerson='$NamaPerson',PJPerson='$IDPerson',AlamatLengkapPerson='$AlamatLengkapPerson',KodeKec='$KodeKec',KodeDesa='$KodeDesa',NamaJalan='$AlamatLengkapPerson',KoorLat='$Lat',KoorLong='$Lng', NoRekeningBank='$NoRekeningBank', AnRekBank='$AnRekBank',KodeDusun='$KodeDusun',NoHP='NoHP' WHERE IDPerson='$KodePerson'");
			
			} 			
		}
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Master User atas nama '.$NamaPerson, $login_id, $KodePerson, 'Master User');
			echo '<script language="javascript">document.location="MasterPerson.php?v='.$Mode.'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterPerson.php?v='.$Mode.'";
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