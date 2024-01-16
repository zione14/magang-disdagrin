<?php 
include_once '../library/config.php';
include '../library/sistemsetting.php';
$Page='Layanan';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <?php include 'title.php';?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Your page description here" />
  <meta name="author" content="" />

  <!-- css -->
  <link href="https://fonts.googleapis.com/css?family=Handlee|Open+Sans:300,400,600,700,800" rel="stylesheet">
  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="css/flexslider.css" rel="stylesheet" />
  <link href="css/prettyPhoto.css" rel="stylesheet" />
  <link href="css/camera.css" rel="stylesheet" />
  <link href="css/jquery.bxslider.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />

  <!-- Theme skin -->
  <link href="color/default.css" rel="stylesheet" />

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png" />
  

  <!-- =======================================================
    Theme Name: Eterna
    Theme URL: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->
</head>

<body>

  <div id="wrapper">

    <!-- start header -->
    <?php include 'header.php';?>
    <!-- end header -->

    <section id="inner-headline">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="inner-heading">
              <ul class="breadcrumb">
                <li><a href="index.php">Beranda</a> <i class="icon-angle-right"></i></li>
                <li class="active">Layanan Masyarakat</li>
              </ul>
              <h2>Sistem Layanan Tera & Tera Ulang UTTP</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="content">
      <div class="container">
        <div class="row">
          <div class="span8">
            <h4> Pengajuan Surat Permohonan Tera Ulang</h4>
            <form action="aksi_upload.php" method="post" enctype="multipart/form-data" id="MyUploadForm" >
              <div class="row contactForm">
				<div class="span8 form-group">
                  <input type="text" name="NIK" placeholder="No KTP"  required>
                </div>
                <div class="span8 form-group">
                  <input type="text" name="telepon"  placeholder="Nomor yang bisa dihubungi" required>
                </div>
				<div class="table-responsive">
					<table id=datatable>
					  <thead>
						<tr>
						  <th></th>
						  <th>ID Timbangan</th>
						  <th>Aksi</th>
						</tr>
					  </thead>
						<tbody>
						<tr>
							<td>
								<input type="hidden" name='nourut[]' class="form-control input-md" placeholder="1"value="1" readonly>
							</td>
							
							<td>
								<div class="span7 form-group">
									<input type="text" name="IDTimbangan[]" placeholder="2019-0000001"  required>
								</div>
							</td>
							<td>
								<div class="span1 form-group">
									<i class="btn-info btn" style="border-radius: 3px" onclick=tambah()><span class='icon-plus'></span></i>
								</div>
							</td>
						</tr>
						</tbody>
					</table>	
				</div><hr>
                <div class="span8 form-group">
					<label> Dokumen Permohonan </label>
					<input name="ImageFile" id="imageInput" type="file" class="form-control input-md" placeholder="Nama File"  required>
					<p><font style="font-size: 13px">Upload Dokumen Permohonan dengan ekstensi .pdf maksimal 2 MB</font></p>
                  
                  <div class="text-center">
					<input type="submit" name="submit"  class="btn btn-theme btn-medium margintop10" id="submit-btn" value="Kirim Permohonan" /><br><br>
					<img src="img/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
                  </div>
                </div>
              </div>
            </form>
			<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div><br>
			<div align="center" id="output"></div>
          </div>
          <div class="span4">
            <div class="clearfix"></div>
            <aside class="right-sidebar">

              <div class="widget">
                <h5 class="widgetheading">INFORMASI KONTAK<span></span></h5>
                <ul class="contact-info">
				  <li><label>Alamat :</label> <?php echo sistemSetting($koneksi, '4', 'value'); ?></li>
                  <li><label>Telephone & Fax :</label> <?php echo sistemSetting($koneksi, '2', 'value'); ?></li>
                  <li><label>Email : </label> <?php echo sistemSetting($koneksi, '3', 'value'); ?></li>
                </ul>

              </div>
            </aside>
          </div>
        </div>
      </div>
    </section>

    <!-- start footer -->
		<?php include 'footer.php';?>
    <!-- end footer -->
  </div>
  <!-- <a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bglight icon-2x active"></i></a> -->
  <!-- <a href="Pengaduan.php" class="scrollup"><i class="icon-comments-alt icon-circled icon-bgsuccess icon-2x "></i></a> -->

  <!-- javascript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="js/jquery.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/bootstrap.js"></script>

  <script src="js/modernizr.custom.js"></script>
  <script src="js/toucheffects.js"></script>
  <script src="js/google-code-prettify/prettify.js"></script>
  <script src="js/jquery.bxslider.min.js"></script>

  <script src="js/jquery.prettyPhoto.js"></script>
  <script src="js/portfolio/jquery.quicksand.js"></script>
  <script src="js/portfolio/setting.js"></script>

  <script src="js/jquery.flexslider.js"></script>
  <script src="js/animate.js"></script>
  <script src="js/inview.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Custom JavaScript File -->
  <script src="js/custom2.js"></script>
  <!-- <script src="js/custom.js"></script> -->
  
  <!-- =============================================Progres Bar=================================-->
	<link href="../komponen/css/upload-img.css" rel="stylesheet">
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
				$("#output").html("Masukkan dokumen terlebih dahulu!");
				return false
			}
			
			var fsize = $('#imageInput')[0].files[0].size; //get file size
			var ftype = $('#imageInput')[0].files[0].type; // get file type
			
			//allow only valid image file types 
			switch(ftype)
			{
				case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg': case 'application/pdf':
					break;
				default:
					$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
					return false
			}
			
			//Allowed file size is less than 1 MB (1048576)
			if(fsize>4048576) 
			{
				$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
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
  
   <script>
		var idrow = 2;
		function tambah(){
			var x=document.getElementById('datatable').insertRow(idrow);
			var td1=x.insertCell(0);
			var td2=x.insertCell(1);
			var td3=x.insertCell(2);
			td1.innerHTML="<input type='hidden' name='nourut[]' class='form-control input-md' value='"+idrow+"' readonly>";
			td2.innerHTML="<div class='span7 form-group'><input type='text' name='IDTimbangan[]' placeholder='2019-0000001'  required></div>";
			td3.innerHTML="<div class='span1 form-group'><i class='btn-success btn' style='border-radius: 3px' onclick=hapus()><span class='icon-minus'></span></i><div>";
			idrow++;
		}

		function hapus(){
			if(idrow>2){
				var x=document.getElementById('datatable').deleteRow(idrow-1);
				idrow--;
			}
		}
	</script>

</body>
</html>
