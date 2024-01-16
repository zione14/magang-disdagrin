<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi=date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
	 <!-- Sweet Alerts -->
	<link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
  </head>
  <body>
    <!-- navbar-->
    <!-- header -->
	 <?php include 'header.php';?>
    <!-- header -->
    <div class="d-flex align-items-stretch">
	<!-- menu -->
	 <?php include 'menu.php';?>
    <!-- menu -->
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <section class="py-5">
            <div class="row">
              <!-- Form Elements -->
              <div class="col-lg-12 mb-5">
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Sambutan Kepala Dinas</h3>
						  </div>
							<div class="card-body">
								<?php 
								// $Profil = mysqli_query($koneksi,"SELECT * FROM kontenweb WHERE IsAktif='1' and JenisKonten='Sambutan'");
								$Profil = mysqli_query($koneksi,"SELECT * FROM kontenweb WHERE IsAktif='1'");
								$DataProfil = mysqli_fetch_assoc($Profil);
								// var_dump($DataProfil);
								
								if(isset($_POST['EditProfil'])):
							?>
								<div class="form-group col-lg-12">
									<form method="post" action="">
										<textarea name="IsiKonten" class="ckeditor" rows="4"><?php echo $DataProfil['IsiKonten'];?></textarea><br/>
										<input type="submit" class="btn btn-success" style="border-radius: 2px;" name="SimpanEditProfil" value="Simpan">
									</form>
								</div>	
								<div class="form-group col-lg-8">
									<form id="form1" method="post" action="">
									<label>Upload foto atau gambar berextensi .jpg/.jpeg./.png (Max 3 Mb)</label><br/>
										<div class="input-group">
											<label class="input-group-btn">
												<span class="btn btn-danger" style="border-radius: 2px;">
													Cari File&hellip; <input type="file" id="media" name="media" style="display: none;" required>
												</span>
											</label>
											<input type="text" class="form-control input-md" size="40" readonly required>
											<label class="input-group-btn">
												<span class="btn btn-primary" style="border-radius: 2px;">
													Upload <input type="submit" value="Kirim" style="display: none;">
												</span>
											</label>
										</div>
									</form>
								</div>
								<br/>
								<div class="form-group col-lg-8">
									<div class="progress" style="display:none">
										<div id="progressBar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
											<span class="sr-only">0%</span>
										</div>
									</div>
									<div class="msg alert alert-info text-left" style="display:none"></div>
									<div class="clearfix"></div>
								</div>
							<?php else: ?>
								<p><?php echo $DataProfil['IsiKonten']; ?></p>
								<img src="../images/Assets/<?php echo $DataProfil['Gambar1'] ?>" class="img img-thumbnail img-responsive">
								<hr>
								<form method="post" action="">
									<input type="submit" class="btn btn-primary" style="border-radius:2px;" name="EditProfil" value="Edit Data">
								</form>
							<?php endif ?>	
								
							</div>
						</div>
					</div>
                </div>
				
				
              </div>
            </div>
          </section>
        </div>
       <?php include 'footer.php';?>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="assets/vendor/chart.js/Chart.min.js"></script>
    <script src="assets/js/front.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<!-- ckeditor JS -->
	<script  src="../library/ckeditor/Fix.js"></script>
	<script type="text/javascript" src="../library/ckeditor/ckeditor.js"></script>
	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript">
	$(document).ready(function() {
		$('#form1').on('submit', function(event){
			event.preventDefault();
			var formData = new FormData($('#form1')[0]);

			$('.msg').hide();
			$('.progress').show();
			
			$.ajax({
				xhr : function() {
					var xhr = new window.XMLHttpRequest();
					xhr.upload.addEventListener('progress', function(e){
						if(e.lengthComputable){
							console.log('Bytes Loaded : ' + e.loaded);
							console.log('Total Size : ' + e.total);
							console.log('Persen : ' + (e.loaded / e.total));
							
							var percent = Math.round((e.loaded / e.total) * 100);
							
							$('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
						}
					});
					return xhr;
				},
				
				type : 'POST',
				url : 'SimpanData/UploadSambutan.php',
				data : formData,
				processData : false,
				contentType : false,
				success : function(response){
					//$('#form1')[0].reset();
					$('.progress').hide();
					$('.msg').show();
					if(response == ""){
						alert('File gagal di upload');
					}else if(response === "Gagal upload file."){
						var msg = response;
						$('.msg').html(msg);
					}else if(response === "Extension file harus .jpg/.jpeg/.png"){
						var msg = response;
						$('.msg').html(msg);
					}else if(response === "Upload Gagal ! Ukuran file maksimal 3 MB."){
						var msg = response;
						$('.msg').html(msg);
					}else{
						$('#form1')[0].reset();
						var msg = response;
						$('.msg').html(msg);
						// alert(msg);document.location="StrukturOrg.php";
					}
				}
			});
		});
	});
	</script>
	
	<script>
	$(function() {
	  $(document).on('change', ':file', function() {
		var input = $(this),
			numFiles = input.get(0).files ? input.get(0).files.length : 1,
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
	  });

	  $(document).ready( function() {
		  $(':file').on('fileselect', function(event, numFiles, label) {

			  var input = $(this).parents('.input-group').find(':text'),
				  log = numFiles > 1 ? numFiles + ' files selected' : label;

			  if( input.length ) {
				  input.val(log);
			  } else {
				  if( log ) alert(log);
			  }

		  });
	  });
	  
	});
	</script>
    <!-- =============================================end Progres Bar=================================-->

	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
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

			if( !$('#imageInput').val()) //check empty input filed
			{
				$("#output").html("Masukkan gambar terlebih dahulu!");
				return false
			}
			
			var fsize = $('#imageInput')[0].files[0].size; //get file size
			var ftype = $('#imageInput')[0].files[0].type; // get file type
			
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
			if(fsize>2048576) 
			{
				$("#output").html("<b>"+bytesToSize(fsize) +"</b> File Gambar Terlalu Besar <br />Masksimal upload 2 MB.");
				return false
			}
			
			//Progress bar
			

					
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
	
  </body>
  <?php 
	//POST DATA
		@$IsiKonten		 	= $_POST['IsiKonten'];
		
	//Tambah Data
		if(isset($_POST['SimpanEditProfil'])){
			
			$query = mysqli_query($koneksi,"UPDATE kontenweb SET IsiKonten='$IsiKonten', TanggalKonten='$TanggalTransaksi' WHERE JenisKonten='Sambutan'");
			
				if($query){
					
					echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="Sambutan.php";</script>';
				}else{
					echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "Sambutan.php";
						  });
						  </script>';
				}
			
		}
		
  ?>
</html>