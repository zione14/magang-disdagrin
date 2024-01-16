<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");
$Edit1 = mysqli_query($koneksi,"SELECT KodeKonten, JenisKonten, JudulKonten, Gambar1, Gambar2 FROM kontenweb WHERE KodeKonten='KONTEN-2020-00000000'");
$RowData1 = mysqli_fetch_assoc($Edit1);

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Slider'");
	$RowData = mysqli_fetch_assoc($Edit);
}
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
				<ul class="nav nav-pills">
					<li <?php if(@$id==null){echo 'class="active"';} ?>>
						<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Slider</span></a>&nbsp;
					</li>
					<li>
						<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo @$Sebutan; ?></span></a>
					</li>
				</ul><br/>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Master Slider</h3>
						  </div>
							<div class="card-body">
							  <form method="post" action="" enctype="multipart/form-data">
								<div class="row">
								  <div class="col-lg-6">
									<h3>Setting Slider Utama</h3>
									<div class="form-group">
									  <label>Tagline Slider</label>
									  <input type="text" name="JudulKonten" class="form-control" style="border-radius: 10px;" placeholder="Judul Slider" value="<?php echo @$RowData1['JudulKonten'];?>" maxlength="255" required>
									  <input type="hidden" name="KodeKonten" value="<?php echo @$RowData1['KodeKonten'];?>">
									  <input type="hidden" name="Gambar1_"   value="<?php echo @$RowData1['Gambar1'];?>">
									  <input type="hidden" name="Gambar2_"   value="<?php echo @$RowData1['Gambar2'];?>">
									</div>
									<div class="form-group">
										<label>Background Slider (Ukuran terbaik 1990x500)</label>
										<br>
										<img id="image-preview-1" src="<?php echo isset($RowData1['Gambar1']) ? '../images/Assets/'.$RowData1['Gambar1'] : '../images/Assets/img1.jpg'; ?> " class="img-thumbnail" alt="image preview" style="max-width:100%;max-height:200px;"/>
			                            <div class="form-group custom-file mt-2">
			                                <input type="file"  style="display:block;" id="Gambar1" name="Gambar1" onchange="previewImage(1)"/>
			                            </div>
									</div>
								  </div>
								  <div class="col-lg-6">
								  	<h3 style="visibility:hidden;">..</h3>
							  	    <div class="form-group">
										<label>Icon Slider</label>
										<br>
										<img id="image-preview-2" src="<?php echo isset($RowData1['Gambar2']) ? '../images/Assets/'.$RowData1['Gambar2'] : '../images/Assets/jombang1.jpeg'; ?> " class="img-thumbnail" alt="image preview" style="max-width:100%;max-height:200px;"/>
			                            <div class="form-group custom-file mt-2">
			                                <input type="file"  style="display:block;" id="Gambar2" name="Gambar2" onchange="previewImage(2)"/>
			                            </div>
									</div>
									<div class="text-right">
										<button type="submit" class="btn btn-info" name="btn-submit">Simpan</button> &nbsp;
									</div>
								  </div>
								</div>
							  </form>
							  <hr>
							  <!--===========================================================================================================================-->

								<div class="col-lg-6">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Judul..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" style="border-radius:2px;" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
								<div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Judul</th>
									  <th>Isi </th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$kosong=null;
										if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
											// jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)pakai ini
											$keyword=$_REQUEST['keyword'];
											$reload = "MstSlider.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM kontenweb WHERE 	JudulKonten LIKE '%$keyword%' and JenisKonten='Slider' ORDER BY TanggalKonten ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MstSlider.php?pagination=true";
											$sql =  "SELECT * FROM kontenweb where JenisKonten='Slider' ORDER BY TanggalKonten ASC";
											@$result = mysqli_query($koneksi,$sql);
										}
										
										//pagination config start
										$rpp = 15; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										<?php
										if($tcount==0){
											echo '<tr><td colspan="8" align="center"><strong>Tidak ada data</strong></td></tr>';
										}else{
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo $data ['JudulKonten']; ?></strong>
											</td>
											<td>
												<?php
													$num_char = 75;
													$cut_text = substr($data['IsiKonten'], 0, $num_char);
													$str_num = str_word_count($data['IsiKonten']);
													if($str_num >= 10){
														if ($data['IsiKonten'][$num_char - 1] != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
														$new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
														$cut_text = substr($data['IsiKonten'], 0, $new_pos);
														}
														echo @$cut_text . '...';	
													}else{
														echo @$data['IsiKonten'];
														
													}?>
											</td>
											<td width="150px">
												<!-- Edit Data-->
												<a href="MstSlider.php?id=<?php echo base64_encode($data['KodeKonten']);?>" title='Edit'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a> 
												<!-- Hapus Data-->
												<a href="MstSlider.php?id=<?php echo base64_encode($data['KodeKonten']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										}
										?>
									</tbody>
								</table>
								<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
							  </div>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0"><?php echo $Sebutan; ?></h3>
						  </div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
									<form method="post" action="">
										<div class="form-group">
										  <label>Judul Slider</label>
										  <input type="text" name="JudulKonten" class="form-control" placeholder="Judul Slider" value="<?php echo @$RowData['JudulKonten'];?>" maxlength="255" required>
										</div>
										<div class="form-group">
										  <label>Uraian</label>
										  <textarea name="IsiKonten" class="ckeditor" rows="4"><?php echo @$RowData['IsiKonten']; ?></textarea>
										</div>
										<div class="text-left">
											<?php
											if(@$_GET['id']==null){
												echo '<input type="hidden" name="JenisKonten" value="Slider"> ';
												echo '<button type="submit" class="btn btn-success" name="Simpan">Simpan</button>';
											}else{
												echo '<input type="hidden" name="KodeKonten" value="'.$RowData['KodeKonten'].'"> ';
												echo '<button type="submit" class="btn btn-success" name="SimpanEdit">Simpan</button> &nbsp;';
											}
											?>
											<a href="MstSlider.php"><span class="btn btn-danger">Batalkan</span></a>
										</div>
									</form>
								  </div>
								  <div class="col-lg-6">
									   	<div class="form-group" <?php if(@$_GET['id'] == null){ echo 'style="display:none"';}?>>
										  <h3>Upload Foto Slider</h3>
											<?php 
											if (@$RowData['Gambar1'] != null OR $RowData['Gambar1'] !=''){ ?>
												<img src="../images/web_profil/slider/thumb_<?php echo @$RowData['Gambar1']; ?>" class="img img-responsive img-thumbnail"><br>
											<?php }else{ ?>
												<img src="../images/Assets/thumb_noimage.png" class="img img-responsive img-thumbnail" style="width:60%;"><br>
											<?php } ?>
											
											<label><br>Upload gambar dengan type .jpg .png dengan ukuran max 2 mb</label>
											<form action="SimpanData/UploadFotoKonten.php?id=<?php echo base64_encode($RowData['KodeKonten']);?>" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
												<div class="input-group">
													<input type="file" name="ImageFile"  id="imageInput" class="form-control" placeholder="Nama File" readonly>
													<span class="input-group-btn">
														<button type="submit" id="submit-btn" class="btn btn-primary" style="border-radius:2px" name="SubmitFile">Proses</button>
													</span>
												</div>
												<img src="../images/Assets/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
												<input type="hidden" name="JenisKonten" class="form-control input-md" value="Slider" required>
											</form>
											<br><div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
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
	<script type="text/javascript" src="assets/js/jquery.form.min.js"></script>
	<script type="text/javascript">
	 function previewImage(num) {
        document.getElementById("image-preview-"+num).style.display = "block";
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("Gambar"+num).files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("image-preview-"+num).src = oFREvent.target.result;
        };
     };
	</script>
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
	
	function confirmation() {
	var answer = confirm("Apakah Anda yakin menghapus data ini ?")
	if (answer == true){
		window.location = "MstSlider.php";
		}
	else{
	alert("Terima Kasih !");	return false; 	
		}
	}
	</script>
	
  </body>
  <?php 
	//POST DATA
		@$JudulKonten		= htmlspecialchars($_POST['JudulKonten']);
		@$IsiKonten		 	= $_POST['IsiKonten'];
		@$JenisKonten		= $_POST['JenisKonten'];
		@$KodeKonten		= $_POST['KodeKonten'];
		
	//Tambah Data
		if(isset($_POST['Simpan'])){
			include '../library/kode-konten.php';
			$Kode = KodeKonten($JenisKonten, $koneksi);
			
			
			$simpan = mysqli_query($koneksi,"INSERT INTO kontenweb (KodeKonten,TanggalKonten,JenisKonten,JudulKonten,IsiKonten,username,IsAktif) VALUES 
			 ('$Kode','$TanggalTransaksi','$JenisKonten','$JudulKonten','$IsiKonten','$login_id',b'0')");
			
			if($simpan){
				echo '<script language="javascript">document.location="MstSlider.php?id='.base64_encode($Kode).'";</script>';
				
			}else{
				 echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "MstSlider.php";
						  });
						  </script>';
			
			}
		}
	
			
	//Edit Data	
		if(isset($_POST['SimpanEdit'])){
			//update data user login berdasarkan username yng di pilih
			$query = mysqli_query($koneksi,"UPDATE kontenweb SET IsiKonten='$IsiKonten', JudulKonten='$JudulKonten',TanggalKonten='$TanggalTransaksi' WHERE KodeKonten='$KodeKonten' AND JenisKonten='Slider'");
			
			if($query){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MstSlider.php";
					  });
					  </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MstSlider.php";
					  });
					  </script>';
			}
		}
	
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Hapus'){
			$HapusGambar = mysqli_query($koneksi,"SELECT Gambar1 FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Slider'");
			$data=mysqli_fetch_array($HapusGambar);
				
			
			$hapus = mysqli_query($koneksi,"DELETE FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Slider'");
			if($hapus){
				@unlink("../images/web_profil/slider/".$data['Gambar1']."");
				@unlink("../images/web_profil/slider/thumb_".$data['Gambar1']."");
				echo '<script language="javascript">document.location="MstSlider.php"; </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MstSlider.php";
					  });
					  </script>';
			}
		}
	
	if (isset($_POST['btn-submit'])) {

	    $JudulKonten = strip_tags($_POST['JudulKonten']);
	    $Gambar1_ = $_POST['Gambar1_'];
	    $Gambar2_ = $_POST['Gambar2_'];

	    $Gambar1 = false;
	    if (!empty($_FILES['Gambar1']['name'])) {
	        $errors = array();
	        $file_name = $_FILES['Gambar1']['name'];
	        $file_size = $_FILES['Gambar1']['size'];
	        $file_tmp = $_FILES['Gambar1']['tmp_name'];
	        $file_type = $_FILES['Gambar1']['type'];
	        $file_ext = strtolower(end(explode('.', $_FILES['Gambar1']['name'])));

	        $extensions = array("jpeg", "jpg", "png");

	        if (in_array($file_ext, $extensions) === false) {
	            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
	        }

	        if ($file_size > 2097152) {
	            $errors[] = 'File size must be excately 2 MB';
	        }

	        if (!getimagesize($file_tmp)) {
	            $errors[] = 'this file is not image';
	        }

	        $newfilename = 'ast-'.date('YmdHis').'-1.'.$file_ext;
	        if (empty($errors) == true) {
	            move_uploaded_file($file_tmp, "../images/Assets/" . $newfilename);
	            $Gambar1 = $newfilename;
	            unlink("../images/Assets/$Gambar1_");
	        }
	    }else{
	    	$Gambar1 = $_POST['Gambar1_'];
	    }

	    $Gambar2 = false;
	    if (!empty($_FILES['Gambar2']['name'])) {
	        $errors = array();
	        $file_name = $_FILES['Gambar2']['name'];
	        $file_size = $_FILES['Gambar2']['size'];
	        $file_tmp = $_FILES['Gambar2']['tmp_name'];
	        $file_type = $_FILES['Gambar2']['type'];
	        $file_ext = strtolower(end(explode('.', $_FILES['Gambar2']['name'])));

	        $extensions = array("jpeg", "jpg", "png");

	        if (in_array($file_ext, $extensions) === false) {
	            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
	        }

	        if ($file_size > 2097152) {
	            $errors[] = 'File size must be excately 2 MB';
	        }

	        if (!getimagesize($file_tmp)) {
	            $errors[] = 'this file is not image';
	        }

	        $newfilename = 'ast-'.date('YmdHis').'-2.'.$file_ext;
	        if (empty($errors) == true) {
	            move_uploaded_file($file_tmp, "../images/Assets/" . $newfilename);
	            $Gambar2 = $newfilename;
	            unlink("../images/Assets/$Gambar2_");
	        }
	    }else{
	    	$Gambar2 = $_POST['Gambar2_'];
	    }

	   
	     
	    $sql = "UPDATE kontenweb set Gambar1='$Gambar1', Gambar2='$Gambar2', JudulKonten='$JudulKonten' where JenisKonten='SettingSlider'";
	    $query = mysqli_query($koneksi, $sql);
	    if($query){
			echo '<script language="javascript">alert("Update data berhasil !"); document.location="MstSlider.php"; </script>';
	    }else{
			echo '<script language="javascript">alert("Update data gagal !"); document.location="MstSlider.php"; </script>';
	    }
	}
  ?>
</html>