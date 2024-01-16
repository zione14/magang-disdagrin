<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM settinf WHERE id='".base64_decode($_GET['id'])."' AND nama='SOSMED'");
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
						<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Sosmed</span></a>&nbsp;
					</li>
					<li>
						<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo @$Sebutan; ?></span></a>
					</li>
				</ul><br/>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Master Sosial Media</h3>
						  </div>
							<div class="card-body">
							  <!--===========================================================================================================================-->

								<!--<div class="col-lg-6">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Judul..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" style="border-radius:2px;" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>-->
								<div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Gambar</th>
									  <th>URL</th>
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
											$reload = "MstSosmed.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM setting WHERE nama ='SOSMED'";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MstSosmed.php?pagination=true";
											$sql =  "SELECT * FROM setting where nama ='SOSMED' ORDER BY id DESC";
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
												<img src="../images/Assets/<?=$data['file']?>" style="width: 64px; height: 64px;">
											</td>
											<td>
												<strong><?php echo $data ['value']; ?></strong>
											</td>
											<td width="150px">
												<!-- Edit Data-->
												<!-- <a href="MstLink.php?id=<?php echo base64_encode($data['KodeKonten']);?>" title='Edit'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a>  -->
												<!-- Hapus Data-->
												<a href="MstSosmed.php?id=<?php echo base64_encode($data['id']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
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
									<form method="post" action="" enctype="multipart/form-data">
										<div class="form-group">
											<label>Jenis Sosial Meedia</label>
											<select name="file"  id="pokemonList" class="form-control">
					                            <option value="facebook.png">Facebook</option>
					                            <option value="instagram.png">Instagram</option>
					                            <option value="linkedin.png">Linkedin</option>
					                            <option value="twitter.png">Twitter</option>
					                            <option value="whatsapp.png">Whatsapp</option>
					                            <option value="youtube.png">Youtube</option>
					                            <option value="gmail.png">Email</option>
					                        </select>
										</div>
										<div class="form-group">
										  <label>Alamat Link Sosial Media</label>
										  <input type="text" name="value" class="form-control" placeholder="Alamat Link Sosial Media" value="<?php echo @$RowData['value'];?>" maxlength="255" required>
										</div>
										<!-- <div class="form-group"><img id="image" /></div> -->
										
										<div class="text-left">
											<?php
											if(@$_GET['id']==null){
												echo '<input type="hidden" name="nama" value="SOSMED"> ';
												echo '<button type="submit" class="btn btn-success" name="Simpan">Simpan</button>';
											}else{
												echo '<input type="hidden" name="id" value="'.$RowData['id'].'"> ';
												echo '<button type="submit" class="btn btn-success" name="SimpanEdit">Simpan</button> &nbsp;';
											}
											?>
											<a href="MstSosmed.php"><span class="btn btn-danger">Batalkan</span></a>
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



 //     var images = {
	//     Instagram:
	//         "https://upload.wikimedia.org/wikipedia/en/2/28/Pok%C3%A9mon_Bulbasaur_art.png",
	//     Facebook:
	//         "../images/Assets/facebook.png",
	//     default:
	//         "https://vignette.wikia.nocookie.net/pokemon/images/4/44/Pok%C3%A9_Ball.jpg/revision/latest?cb=20090507215041"
	// };
	// var changePokemonImage = function() {
	//     const value = this.options[this.selectedIndex].value;
	//     var imageURL = images[value];
	//     document.getElementById("image").src = imageURL;
	// };

	// var pokemonList = document.getElementById("pokemonList");
	// pokemonList.addEventListener("change", changePokemonImage, false);

	// document.getElementById("image").src = images["default"];
	</script>
	<script type="text/javascript">
	
	function confirmation() {
	var answer = confirm("Apakah Anda yakin menghapus data ini ?")
	if (answer == true){
		window.location = "MstSosmed.php";
		}
	else{
	alert("Terima Kasih !");	return false; 	
		}
	}
	</script>
	
  </body>
  <?php 
	//POST DATA
		@$nama		= htmlspecialchars($_POST['nama']);
		@$value		= $_POST['value'];
		@$file		= $_POST['file'];
		// @$KodeKonten		= $_POST['KodeKonten'];
		
	//Tambah Data
		if(isset($_POST['Simpan'])){
			//buat no urut
			$AmbilNoUrut=mysqli_query($koneksi,"SELECT MAX(id) as NoSaatIni FROM setting");
			$Data=mysqli_fetch_assoc($AmbilNoUrut);
			$NoSekarang = $Data['NoSaatIni'];
			$Urutan = $NoSekarang+1;

			// $Gambar1 = false;
		 //    if (!empty($_FILES['Gambar1']['name'])) {
		 //        $errors = array();
		 //        $file_name = $_FILES['Gambar1']['name'];
		 //        $file_size = $_FILES['Gambar1']['size'];
		 //        $file_tmp = $_FILES['Gambar1']['tmp_name'];
		 //        $file_type = $_FILES['Gambar1']['type'];
		 //        $file_ext = strtolower(end(explode('.', $_FILES['Gambar1']['name'])));

		 //        $extensions = array("jpeg", "jpg", "png");

		 //        if (in_array($file_ext, $extensions) === false) {
		 //            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
		 //        }

		 //        if ($file_size > 2097152) {
		 //            $errors[] = 'File size must be excately 2 MB';
		 //        }

		 //        if (!getimagesize($file_tmp)) {
		 //            $errors[] = 'this file is not image';
		 //        }

		 //        $newfilename = 'sosmed-'.date('YmdHis').'.'.$file_ext;
		 //        if (empty($errors) == true) {
		 //            move_uploaded_file($file_tmp, "../images/Assets/" . $newfilename);
		 //            $Gambar1 = $newfilename;
		           
		 //        }
		 //    }
			
			
			$simpan = mysqli_query($koneksi,"INSERT INTO setting (id,nama,value,file) VALUES ('$Urutan','$nama','$value','$file')");
			
			if($simpan){
				echo '<script language="javascript">document.location="MstSosmed.php";</script>';
				
			}else{
				 echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "MstSosmed.php";
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
						window.location.href = "MstLink.php";
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
						window.location.href = "MstLink.php";
					  });
					  </script>';
			}
		}
	
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Hapus'){
			// $HapusGambar = mysqli_query($koneksi,"SELECT file FROM setting WHERE id='".base64_decode($_GET['id'])."' AND nama='SOSMED'");
			// $data=mysqli_fetch_array($HapusGambar);
				
			
			$hapus = mysqli_query($koneksi,"DELETE FROM setting WHERE id='".base64_decode($_GET['id'])."' AND nama='SOSMED'");
			if($hapus){
				// @unlink("../images/Assets/".$data['file']."");
				echo '<script language="javascript">document.location="MstSosmed.php"; </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MstSosmed.php";
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
			echo '<script language="javascript">alert("Update data berhasil !"); document.location="MstSosmed.php"; </script>';
	    }else{
			echo '<script language="javascript">alert("Update data gagal !"); document.location="MstSosmed.php"; </script>';
	    }
	}
  ?>
</html>