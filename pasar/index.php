<?php
include '../pasar/akses.php';
$Page = 'index';
//data surat
// function ResultData($IDPerson,$Tabel,$koneksi){
// 	if ($Tabel=='Toko'){
// 		$Query = mysqli_query($koneksi,"SELECT IDPerson FROM mstperson where IsVerified=b'1' and ID_Distributor='$IDPerson'");
// 		$RowData = mysqli_num_rows($Query);
// 		$Data = $RowData;
// 	}elseif($Tabel == 'Beli'){
// 		$query = "SELECT SUM(JumlahMasuk) as Penerimaan FROM sirkulasipupuk where IDPerson='$IDPerson'";
// 		$conn = mysqli_query($koneksi, $query);
// 		$result = mysqli_fetch_array($conn);
// 		$Data = $result['Penerimaan'];
// 	}elseif($Tabel == 'Jual'){
// 		$query = "SELECT SUM(JumlahKeluar) as Penjualan FROM sirkulasipupuk where IDPerson='$IDPerson'";
// 		$conn = mysqli_query($koneksi, $query);
// 		$result = mysqli_fetch_array($conn);
// 		$Data = $result['Penjualan'];
		
// 	}elseif($Tabel == 'Stok'){
// 		$query = "SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan FROM sirkulasipupuk where IDPerson='$IDPerson'";
// 		$conn = mysqli_query($koneksi, $query);
// 		$result = mysqli_fetch_array($conn);
// 		$Data = $result['Penerimaan']-$result['Penjualan'];
		
		
// 	}
	
	
// 	return($Data);
// } 

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php 
	  include 'header.php'; ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Dashboard</h2>
            </div>
          </header>
		   
		    <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="card-header d-flex align-items-center">
				  <h3 class="h4">Statistik Data </h3>
				</div>
				  <div class="row bg-white has-shadow">
				
                <!-- Item -->
               <!--  <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="fa fa-check"></i></div>
                    <div class="title"><span>Manajemen Toko</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData($login_id ,'Toko',$koneksi); ?></strong></div>
                  </div>
                </div> -->
				<!-- Item -->
                <!-- <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-orange"><i class="fa fa-sitemap"></i></div>
                    <div class="title"><span>Penerimaan Pupuk</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData($login_id ,'Beli',$koneksi); ?></strong></div>
                  </div>
                </div> -->
				<!-- Item -->
                <!-- <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><i class="fa fa-handshake-o"></i></div>
                    <div class="title"><span>Penjualan Pupuk</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData($login_id ,'Jual',$koneksi); ?></strong></div>
                  </div>
                </div> -->
                <!-- Item -->
                <!-- <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-blue"><i class="fa fa-window-close"></i></div>
                    <div class="title"><span>Stok Sekarang</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-blue"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData($login_id ,'Stok',$koneksi); ?></strong></div>
                  </div>
                </div>
              </div> -->
				
            </div>
          </section><br>
		
		   
		   <section class="client no-padding-top">
            <div class="container-fluid">
              <div class="row">
				<!--<div class="col-lg-3">
					<div class="statistic d-flex align-items-center bg-white has-shadow">
						<div class="icon bg-violet"><i class="fa fa-users"></i></div>
						<div class="text"><strong><?php echo ResultData(null,'VerifikasiUser',$koneksi);?></strong><br><small><a href="VefUser.php" style="text-decoration:none"> Verifikasi  User</a></small></div>
					</div>
                </div>-->
				
				
				<!--<div class="col-lg-3">
					<div class="statistic d-flex align-items-center bg-white has-shadow">
						<div class="icon bg-green"><i class="fa fa-users"></i></div>
						<div class="text"><strong><?php echo ResultData(null,'TotalPegawaiAnjab',$koneksi);?></strong><br><small>Total Pegawai (Anjab)</small></div>
					</div>
                </div>-->
              </div>
            </div>
		   </section>
        </div>
      </div>
    </div>
	
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>
	
  
  </body>
</html>