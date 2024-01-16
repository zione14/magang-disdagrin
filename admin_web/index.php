<?php 
include 'akses.php';
$Page = 'index';
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
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
          <section class="py-4"><br><br>
            <div class="row">
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-red"></div>
                    <div class="text">
                      <h6 class="mb-0">Master Slider</h6><span class="text-gray"><?php echo ResultData('Slider','Konten',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-red"><i class="fas fa-image"></i></div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-warning"></div>
                    <div class="text">
                      <h6 class="mb-0">Berita</h6><span class="text-gray"><?php echo ResultData('Berita','Konten',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-warning"><i class="fas fa-newspaper"></i></div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-success"></div>
                    <div class="text">
                      <h6 class="mb-0">Artikel</h6><span class="text-gray"><?php echo ResultData('Artikel','Konten',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-success"><i class="fas fa-file-alt"></i></div>
                </div>
              </div>
            </div>
          </section>
          
          <section class="py-4">
            <div class="row">
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-blue"></div>
                    <div class="text">
                      <h6 class="mb-0">Galeri Foto</h6><span class="text-gray"><?php echo ResultData('Foto','Konten',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-blue"><i class="fas fa-camera-retro"></i></div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-violet"></div>
                    <div class="text">
                      <h6 class="mb-0">Galeri Video</h6><span class="text-gray"><?php echo ResultData('Video','Konten',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-violet"><i class="fas fa-video"></i></div>
                </div>
              </div>
              <div class="col-xl-4 col-lg-6 mb-3 mb-xl-0">
                <div class="bg-white shadow roundy p-4 h-100 d-flex align-items-center justify-content-between">
                  <div class="flex-grow-1 d-flex align-items-center">
                    <div class="dot mr-3 bg-secondary"></div>
                    <div class="text">
                      <h6 class="mb-0">User Login</h6><span class="text-gray"><?php echo ResultData('1','User',$koneksi);?> Data</span>
                    </div>
                  </div>
                  <div class="icon text-white bg-secondary"><i class="fas fa-user"></i></div>
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
    <script src="assets/js/charts-home.js"></script>
    <script src="assets/js/front.js"></script>
  </body>
  <?php 
	function ResultData($Jenis,$Tabel,$koneksi){
	if($Tabel=='Konten'){
		$Query = mysqli_query($koneksi,"SELECT KodeKonten FROM kontenweb WHERE JenisKonten='$Jenis'");
		$Data = mysqli_num_rows($Query);
	}elseif($Tabel=='User'){
		$Query = mysqli_query($koneksi,"SELECT UserName FROM userlogin WHERE IsAktif='$Jenis'");
		$Data = mysqli_num_rows($Query);
	}

	return($Data);
}
  ?>
  
  
</html>