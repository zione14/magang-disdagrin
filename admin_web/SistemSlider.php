<?php 
include 'akses.php';
$Page = 'Sistem';
$Edit = mysqli_query($koneksi,"SELECT KodeKonten, JenisKonten, JudulKonten, Gambar1, Gambar2 FROM kontenweb WHERE KodeKonten='KONTEN-2020-00000000'");
$RowData = mysqli_fetch_assoc($Edit);

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
		echo '<script language="javascript">alert("Update data berhasil !"); document.location="SistemSlider.php"; </script>';
    }else{
		echo '<script language="javascript">alert("Update data gagal !"); document.location="SistemSlider.php"; </script>';
    }
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
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Sistem Setting Slider</h3>
						  </div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
									<form method="post" action="" enctype="multipart/form-data">
										<div class="form-group">
										  <label>Tagline Slider</label>
										  <input type="text" name="JudulKonten" class="form-control" style="border-radius: 10px;" placeholder="Judul Slider" value="<?php echo @$RowData['JudulKonten'];?>" maxlength="255" required>
										  <input type="hidden" name="KodeKonten" value="<?php echo @$RowData['KodeKonten'];?>">
										  <input type="hidden" name="Gambar1_"   value="<?php echo @$RowData['Gambar1'];?>">
										  <input type="hidden" name="Gambar2_"   value="<?php echo @$RowData['Gambar2'];?>">
										</div>
										<div class="form-group">
											<label>Background Slider (Ukuran terbaik 1990x500)</label>
											<br>
											<img id="image-preview-1" src="<?php echo isset($RowData['Gambar1']) ? '../images/Assets/'.$RowData['Gambar1'] : '../images/Assets/img1.jpg'; ?> " class="img-thumbnail" alt="image preview" style="max-width:100%;max-height:200px;"/>
				                            <div class="form-group custom-file mt-2">
				                                <input type="file"  style="display:block;" id="Gambar1" name="Gambar1" onchange="previewImage(1)"/>
				                            </div>
										</div>
										<div class="form-group">
											<label>Icon Slider</label>
											<br>
											<img id="image-preview-2" src="<?php echo isset($RowData['Gambar2']) ? '../images/Assets/'.$RowData['Gambar2'] : '../images/Assets/jombang1.jpeg'; ?> " class="img-thumbnail" alt="image preview" style="max-width:100%;max-height:200px;"/>
				                            <div class="form-group custom-file mt-2">
				                                <input type="file"  style="display:block;" id="Gambar2" name="Gambar2" onchange="previewImage(2)"/>
				                            </div>
										</div>
										<div class="text-left">
											<button type="submit" class="btn btn-success" name="btn-submit">Simpan</button> &nbsp;
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
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	
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
  </body>
</html>