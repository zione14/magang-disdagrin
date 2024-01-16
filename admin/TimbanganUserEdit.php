<?php
include 'akses.php';
@$fitur_id = 4;
include '../library/lock-menu.php';
$Page = 'DataPemilik';
$SubPage = 'MasterPerson';
// Tanggal dan Tahun
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']!=null){
	$Sebutan = 'Edit Data';	
	@$Edit = mysqli_query($koneksi,"SELECT a.* FROM timbanganperson a WHERE a.IDTimbangan='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	@$ket 	  = explode('#', $RowData['Keterangan']); 
}

@$NamaPerson  = NamaPerson($koneksi, $RowData['IDPerson']);

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
              <h2 class="no-margin-bottom">Edit Timbangan User <?php echo $NamaPerson; ?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
									   <form action="" method="post">
										<div class="form-group-material">
										  <label>ID UTTP</label>
										  <input type="text" class="form-control" placeholder="ID Timbangan" value="<?php echo @$RowData['IDTimbangan'];?>" readonly>
										</div>
										<div class="form-group-material">
										  <label>Nama UTTP</label>
										  <input type="text" name="NamaTimbangan" class="form-control" placeholder="Nama Timbangan" value="<?php echo @$RowData['NamaTimbangan'];?>" maxlength="150">
										</div>
										<div class="form-group-material">
										  <label>Jenis UTTP</label>
										  <select id="KodeTimbangan" name="KodeTimbangan" class="form-control" >	
												<?php
													echo "<option value=''>--- Timbangan ---</option>";
													$menu = mysqli_query($koneksi,"SELECT KodeTimbangan,NamaTimbangan from msttimbangan ORDER by NamaTimbangan ASC");
													while($kode = mysqli_fetch_array($menu)){
														
														if($kode['KodeTimbangan'] === $RowData['KodeTimbangan']){
															echo "<option value=\"".$kode['KodeTimbangan']."\" selected='selected'>".$kode['NamaTimbangan']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodeTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
														}
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
										  <label>Nama Kelas</label>
										  <select id="KodeKelas" name="KodeKelas" class="form-control" >	
												<?php
													echo "<option value=''>--- Nama Kelas ---</option>";
													$menu = mysqli_query($koneksi,"SELECT KodeKelas,NamaKelas,Keterangan from kelas where KodeTimbangan='".$RowData['KodeTimbangan']."' ORDER by NamaKelas ASC");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeKelas'] === $RowData['KodeKelas']){
															if($RowData['NamaKelas'] != '' OR $RowData['NamaKelas'] != null){
																echo "<option value=\"".$kode['KodeKelas']."\" selected='selected'>".$kode['NamaKelas']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeKelas']."\" selected='selected'>".$kode['Keterangan']."</option>\n";	
															}
														}else{
															echo "<option value=\"".$kode['KodeKelas']."\" >".$kode['NamaKelas']."</option>\n";
														}
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
										  <label>Nama Ukuran</label>
										  <select id="KodeUkuran" name="KodeUkuran" class="form-control" >	
												<?php
													echo "<option value=''>--- Nama Ukuran ---</option>";
													$menu = mysqli_query($koneksi,"SELECT KodeUkuran,NamaUkuran from detilukuran where KodeKelas='".$RowData['KodeKelas']."' ORDER by NamaUkuran ASC");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeUkuran'] === $RowData['KodeUkuran']){
															echo "<option value=\"".$kode['KodeUkuran']."\" selected='selected'>".$kode['NamaUkuran']."</option>\n";	
															
														}else{
															echo "<option value=\"".$kode['KodeUkuran']."\" >".$kode['NamaUkuran']."</option>\n";
														}
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
										  <label>Nama Lokasi</label>
										  <select id="KodeLokasi" name="KodeLokasi" class="form-control" >	
												<?php
													echo "<option value=''>--- Pilih Lokasi ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson where IDPerson='". $RowData['IDPerson']."' ORDER BY NamaLokasi");
													while($kode = mysqli_fetch_array($menu)){
														
														if($kode['KodeLokasi'] === @$RowData['KodeLokasi']){
															echo "<option value=\"".$kode['KodeLokasi']."\" selected='selected'>".$kode['NamaLokasi']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodeLokasi']."\" >".$kode['NamaLokasi']."</option>\n";
														}
														
														$LAT = $RowData['KoorLat'];
														$LONG = $RowData['KoorLong'];
													}
												?>
											</select>
										</div>
										<!--<div class="form-group-material">
										  <label>Alamat Timbangan</label>
										  <input type="text" name="AlamatTimbangan" class="form-control" placeholder="Alamat Timbangan" value="<?php echo @$RowData['AlamatTimbangan'];?>" maxlength="150">
										</div>-->
										<div class="form-group-material">
										  <label>Ukuran Real Timbangan</label>
										  <input type="number" name="UkuranRealTimbangan" class="form-control"  placeholder="Kapasitas Timbangan" value="<?php echo @$RowData['UkuranRealTimbangan'];?>" maxlength="150">
										</div>
										<div class="form-group">
										  <label>Satuan</label>
										  <input type="text" name="Satuan" class="form-control" placeholder="Satuan" value="<?php echo @$RowData['Satuan'];?>">
										</div>
										<div class="form-group">
										  <label>Merk</label>
										  <input type="text" name="Merk" class="form-control" placeholder="Merk" value="<?php echo @$ket[0];?>">
										</div>
										<div class="form-group">
										  <label>Type</label>
										  <input type="text" name="Type" class="form-control" placeholder="Type" value="<?php echo @$ket[1];?>">
										</div>
										<div class="form-group">
										  <label>Nomor Seri</label>
										  <input type="text" name="Seri" class="form-control" placeholder="Seri" value="<?php echo @$ket[2];?>">
										</div>
										<br>
										
										<div class="text-left">
										<?php
											echo ' <input type="hidden" name="type" value="edit">';
											echo ' <input type="hidden" name="IDPerson" value="'.$RowData['IDPerson'].'">';
											echo ' <input type="hidden" name="IDTimbangan" value="'.$RowData['IDTimbangan'].'">';
											echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
											echo '<button type="submit"  id="submit-btn"  class="btn btn-primary" name="SimpanEdit">Simpan</button>&nbsp;';
											echo '<a href="TimbanganUserDetil.php?user='.base64_encode($RowData['IDPerson']).'"><span class="btn btn-warning">Batalkan</span></a>';
										?>
									</div>
									
									</div>
									<div class="col-lg-6">
										<div class="form-group">
										  <label>Keterangan</label>
										  <input type="text" name="Jumlah" class="form-control" placeholder="Keterangan" value="<?php echo @$ket[5];?>">
										</div>
										<div class="form-group">
										  <label>Timbangan Buatan</label>
										  <input type="text" name="Buatan" class="form-control" placeholder="Buatan" value="<?php echo @$ket[3];?>">
										</div>
										<div class="form-group">
										  <label>Medium</label>
										  <input type="text" name="Medium" class="form-control" placeholder="Medium" value="<?php echo @$ket[4];?>">
										</div>
										<div class="form-group-material">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
										</form>
										<div class="form-group-material">
											<label>Upload Gambar Timbangan type .jpg/.png 2MB</label>
											<div class="row">
												<form action="SimpanData/UploadFotoTimbangan.php?id=<?php echo base64_encode($RowData['IDTimbangan']);?>&login=<?php echo base64_encode($login_id); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File*"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="nourut" value=" <?php echo '1'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
															<!-- modal preview foto -->
															<?php if ($RowData['FotoTimbangan1'] != '' OR $RowData['FotoTimbangan1']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoTimbangan1'];?>'><i  class="btn btn-success ">Preview</i></a>
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<div class="row">
												<form action="SimpanData/UploadFotoTimbangan.php?id=<?php echo base64_encode($RowData['IDTimbangan']);?>&login=<?php echo base64_encode($login_id); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="nourut" value=" <?php echo '2'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
															<?php if ($RowData['FotoTimbangan2'] != '' OR $RowData['FotoTimbangan2']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoTimbangan2'];?>'><i  class="btn btn-success ">Preview</i></a>
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<div class="row">
												<form action="SimpanData/UploadFotoTimbangan.php?id=<?php echo base64_encode($RowData['IDTimbangan']);?>&login=<?php echo base64_encode($login_id); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="nourut" value=" <?php echo '3'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
															<?php if ($RowData['FotoTimbangan3'] != '' OR $RowData['FotoTimbangan3']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoTimbangan3'];?>'><i  class="btn btn-success ">Preview</i></a>
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<div class="row">
												<form action="SimpanData/UploadFotoTimbangan.php?id=<?php echo base64_encode($RowData['IDTimbangan']);?>&login=<?php echo base64_encode($login_id); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
												<div class="col-lg-12">
													<div class="form-group-material">
														<div class="input-group">
														<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
														<input type="hidden" name="type" value="edit">
														<input type="hidden" name="nourut" value=" <?php echo '4'; ?>">
														<span class="input-group-btn">
															<!-- tombol submit -->
															<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
															<?php if ($RowData['FotoTimbangan4'] != '' OR $RowData['FotoTimbangan4']!= NULL) {?>
															<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoTimbangan4'];?>'><i  class="btn btn-success ">Preview</i></a>
															<?php } ?>
														</span>
														</div>
													</div>
												</div>
												</form>
											</div>
											<img src="../web/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
												<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
											<div class="text-center">
												<div align="center" id="output"></div>
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
	
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>
	  $("#KodeTimbangan").change(function(){
		var KodeTimbangan = $("#KodeTimbangan").val();
		$.ajax({
			url: "../library/Dropdown/ambil-kelas.php",
			data: "KodeTimbangan="+KodeTimbangan,
			cache: false,
			success: function(msg){
				$("#KodeKelas").html(msg);
				
			}
		});
		
		$.ajax({
			url: "../library/Dropdown/ambil-ukurantim.php",
			data: "KodeTimbangan="+KodeTimbangan,
			cache: false,
			success: function(msg){
				$("#KodeUkuran").html(msg);
				
			}
		});
		
	  });
	  
	  $("#KodeKelas").change(function(){
		  
		var KodeKelas = $("#KodeKelas").val();
		console.log(KodeKelas);
		$.ajax({
			url: "../library/Dropdown/ambil-ukuran.php",
			data: "KodeKelas="+KodeKelas,
			cache: false,
			success: function(msg){
				$("#KodeUkuran").html(msg);
			}
		});
	  });
	  
	});
	</script>
	
	
	<script type="text/javascript">
		//open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_view").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
			   $.ajax({
					   url: "ViewFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen},
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
	<script type="text/javascript">
	$(document).ready(function() { 

		var progressbox     = $('#progressbox');
		var progressbar     = $('#progressbar');
		var statustxt       = $('#statustxt');
		var completed       = '0%';
		
		var options = { 
				target:   '#output',   // target element(s) to be updated with server response 
				beforeSubmit:  beforeSubmit,  // pre-submit callback 
				uploadProgress: OnProgress,
				success:       afterSuccess,  // post-submit callback 
				resetForm: true        // reset the form after successful submit 
			}; 
			
		 $('#MyUploadForm').submit(function() { 
				$(this).ajaxSubmit(options);  			
				// return false to prevent standard browser submit and page navigation 
				return false; 
			});
		
	//when upload progresses	
	function OnProgress(event, position, total, percentComplete)
	{
		//Progress bar
		progressbar.width(percentComplete + '%') //update progressbar percent complete
		statustxt.html(percentComplete + '%'); //update status text
		if(percentComplete>50)
			{
				statustxt.css('color','#fff'); //change status text to white after 50%
			}
	}

	//after succesful upload
	function afterSuccess()
	{
		$('#submit-btn').show(); //hide submit button
		$('#loading-img').hide(); //hide submit button

	}

	//function to check file size before uploading.
	function beforeSubmit(){
		//check whether browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{

			if( !$('#filefoto').val()) //check empty input filed
			{
				$("#output").html("Masukkan gambar terlebih dahulu!");
				return false
			}
			
			var fsize = $('#filefoto')[0].files[0].size; //get file size
			var ftype = $('#filefoto')[0].files[0].type; // get file type
			
			//allow only valid image file types 
			switch(ftype)
			{
				case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
					break;
				default:
					$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
					return false
			}
			
			//Allowed file size is less than 1 MB (1048576)
			if(fsize>3048576) 
			{
				$("#output").html("<b>"+bytesToSize(fsize) +"</b> File Gambar Terlalu Besar <br />Masksimal upload 3 MB.");
				return false
			}
			
			//Progress bar
			progressbox.show(); //show progressbar
			progressbar.width(completed); //initial value 0% of progressbar
			statustxt.html(completed); //set status text
			statustxt.css('color','#000'); //initial color of status text

					
			$('#submit-btn').hide(); //hide submit button
			$('#loading-img').show(); //hide submit button
			$("#output").html("");  
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
			return false;
		}
	}

	//function to format bites bit.ly/19yoIPO
	function bytesToSize(bytes) {
	   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	   if (bytes == 0) return '0 Bytes';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}

	}); 

	</script>
	
	<?php 
		if(isset($_POST['SimpanEdit'])){
		@$IDPerson 			= htmlspecialchars($_POST['IDPerson']);
		@$NamaTimbangan		= htmlspecialchars($_POST['NamaTimbangan']);
		// @$KodeTimbangan		= htmlspecialchars($_POST['KodeTimbangan']);
		@$KodeLokasi		= htmlspecialchars($_POST['KodeLokasi']);
		@$Lat				 = htmlspecialchars($_POST['Lat']);
		@$LngAsli				 = htmlspecialchars($_POST['Lng']);
		@$UkuranRealTimbangan	= htmlspecialchars($_POST['UkuranRealTimbangan']);
		// @$Keterangan		= htmlspecialchars($_POST['Keterangan']);
		@$idtimbangan		= htmlspecialchars($_POST['IDTimbangan']);
		@$NamaPerson 		= NamaPerson($koneksi, $IDPerson);
		// @$bagi_uraian 		= explode("#", $KodeTimbangan);
		@$Timbangan 		 = htmlspecialchars($_POST['KodeTimbangan']);
		@$Kelas 			 = htmlspecialchars($_POST['KodeKelas']);
		@$Ukuran 			 = htmlspecialchars($_POST['KodeUkuran']);
		@$Satuan 			 = htmlspecialchars($_POST['Satuan']);
		@$Merk 				 = htmlspecialchars($_POST['Merk']);
		@$Type 			 	 = htmlspecialchars($_POST['Type']);
		@$Seri 			 	 = htmlspecialchars($_POST['Seri']);
		@$Buatan		 	 = htmlspecialchars($_POST['Buatan']);
		@$Medium		 	 = htmlspecialchars($_POST['Medium']);
		@$Jumlah		 	 = htmlspecialchars($_POST['Jumlah']);
		@$Keterangan		 = $Merk."#".$Type."#".$Seri."#".$Buatan."#".$Medium."#".$Jumlah;
		
		$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='$LngAsli'");
		$num2 = mysqli_num_rows($cek2);
		if($num2 > 0 ){
			@$Lng = $LngAsli+0.0001;
		}else{
			@$Lng = $LngAsli;
		}
		
		// query update
		$query = mysqli_query($koneksi,"UPDATE timbanganperson SET NamaTimbangan='$NamaTimbangan',KodeTimbangan='$Timbangan',KodeLokasi='$KodeLokasi',Keterangan='$Keterangan',KodeKelas='$Kelas',KodeUkuran='$Ukuran', UkuranRealTimbangan='$UkuranRealTimbangan',KoorLat='$Lat',KoorLong='$Lng',Satuan='$Satuan' WHERE IDTimbangan='$idtimbangan' AND IDPerson='$IDPerson'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Timbangan User atas nama '.$NamaPerson.' dengan nama timbangan '.$NamaTimbangan, $login_id, $idtimbangan, 'Timbangan User');
			echo '<script language="javascript">document.location="TimbanganUserDetil.php?user='.base64_encode($IDPerson).'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "TimbanganUserDetil.php?user='.base64_encode($IDPerson).'";
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