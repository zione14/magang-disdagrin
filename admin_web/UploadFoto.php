<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi=date("Y-m-d H:i:s");
@$IdKonten 		= base64_decode(@$_GET['id']); //untuk upload gambar
@$JenisKonten 	= base64_decode(@$_GET['jns']); //untuk upload gambar
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="../komponen/css/orionicons.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="../komponen/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	 <!-- Sweet Alerts -->
	<link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
    
    <script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "UploadFoto.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
   
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
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<div class="horizontal">
								 <div class="row">
									<h3 class="h6 text-uppercase mb-0 col-md-2">Upload Foto</h3>
									<div class="col-md-10" align="right">
										<?php if ($JenisKonten == 'Berita') { ?>
											<a href="MstBerita.php"><span class="btn btn-violet" style="border-radius:4px;">Kembali</span></a>
										<?php }elseif ($JenisKonten == 'Foto') { ?>
											<a href="MstFoto.php"><span class="btn btn-violet" style="border-radius:4px;">Kembali</span></a>
										<?php }else{ ?>
											<a href="MstArtikel.php"><span class="btn btn-violet" style="border-radius:4px;">Kembali</span></a>
										<?php } ?>
									</div>
								  </div>
							</div>
						  </div>
							<div class="card-body">
								<div class="table-responsive">
									<table width="100%" class="table table-hover">
										<thead>
											<tr align="center">
												<td><strong>No</strong></td>
												<td><strong>Gambar</strong></td>
												<td><strong>Keterangan</strong></td>
												<td><strong>Aksi</strong></td>
											</tr>
										</thead>
										<tbody>
											<?php
												$no =1;
												$QueryGambar = mysqli_query($koneksi,"SELECT * FROM detailkonten WHERE KodeKonten='$IdKonten' and JenisKonten='$JenisKonten' ORDER BY Nourut ASC");
												while($RowGambar=mysqli_fetch_array($QueryGambar)){
											?>
											<tr class="odd gradeX">
												<td width="30px">
													<?php echo $no++;?> 
												</td>
												<td align="center">
													<?php if ($JenisKonten == 'Berita') { ?>
														<img src="../images/web_profil/berita/thumb_<?php echo $RowGambar['Dokumen'];?>" class="img img-responsive img-thumbnail">
													<?php }elseif ($JenisKonten == 'Foto') { ?>
														<img src="../images/web_profil/galeri/thumb_<?php echo $RowGambar['Dokumen'];?>" class="img img-responsive img-thumbnail">
													<?php }else{ ?>
														<img src="../images/web_profil/artikel/thumb_<?php echo $RowGambar['Dokumen'];?>" class="img img-responsive img-thumbnail">
													<?php } ?>
												</td>
												<td>
													<?php echo $RowGambar['keterangan'];	?>
												</td>
												<td align="center" width="90px">
													<a href="UploadFoto.php?kode=<?php echo base64_encode($RowGambar['KodeKonten']);?>&no=<?php echo base64_encode($RowGambar['Nourut']);?>&file=<?php echo base64_encode($RowGambar['Dokumen']);?>&aksi=<?php echo base64_encode('hapus_detil'); ?>&jns=<?php echo $JenisKonten; ?>" title='Hapus!' onclick='return confirmation3()'><i class='btn btn-danger'><span class='fa fa-trash'></span></i></a>
												</td>
											</tr>
											<?php
											}
											?>
											<tr class="alert alert-danger">
												<td>+</td>
												<td>
													<form action="SimpanData/UploadFotoKonten.php?id=<?php echo base64_encode($IdKonten);?>" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
														<input name="ImageFile" id="imageInput" type="file" /><br/>
														<p>Upload gambar dengan ekstensi .jgp/.jpeg/.png maksimal 3 MB.</p>
														<img src="../images/Assets/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
														<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
														<div class="text-center">
															<div align="center" id="output"></div>
														</div>
												</td>
												<td>
													<input type="hidden" name="JenisKonten" class="form-control input-md" value="<?php echo $JenisKonten; ?>" required>
													<input type="text" name="Keterangan" class="form-control input-md" placeholder="Keterangan*" required>
												</td>
												<td>
													<input type="submit"  id="submit-btn" class="btn btn-primary" value="Upload" />
												</td>
													</form>
											</tr>
										</tbody>
									</table>
								</div>
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
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/js/front.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<!-- ckeditor JS -->
	<script  src="../library/ckeditor/Fix.js"></script>
	<script type="text/javascript" src="../library/ckeditor/ckeditor.js"></script>
	

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
		//hapus detil gambar
		if(base64_decode(@$_GET['aksi'])=='hapus_detil'){
			@$File				= base64_decode(@$_GET['file']);
			@$No				= base64_decode(@$_GET['no']);
			@$KodeKonten 		= base64_decode(@$_GET['kode']); 
			
			if (@$_GET['jns'] == 'Berita'){
				unlink("../images/web_profil/berita/$File");
				unlink("../images/web_profil/berita/thumb_$File");
				
			}elseif(@$_GET['jns'] == 'Artikel'){
				unlink("../images/web_profil/artikel/$File");
				unlink("../images/web_profil/artikel/thumb_$File");
			
			}elseif(@$_GET['jns'] == 'Foto'){
				unlink("../images/web_profil/galeri/$File");
				unlink("../images/web_profil/galeri/thumb_$File");
				
			}
			mysqli_query($koneksi, "DELETE FROM detailkonten WHERE KodeKonten='$KodeKonten' AND Nourut='$No' AND JenisKonten='".$_GET['jns']."'");
			echo '<script language="javascript">document.location="UploadFoto.php?id='.base64_encode($KodeKonten).'&jns='.base64_encode($_GET['jns']).'"; </script>';
		}
  ?>
</html>