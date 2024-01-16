<?php
include 'akses.php';
@$fitur_id = 4;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'TimbanganDinas';


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
 	<link rel="stylesheet" type="text/css" href="../library/datatables/datatables.min.css"/>
	
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
              <h2 class="no-margin-bottom">Riwayat Tera Timbangan ID <?=base64_decode($_GET['id'])?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade in active show" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Detil UTTP User</h3>
							</div>
							<div class="card-body">
							  <div class="table-responsive">  
								<table class="table " id="lookup">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama UTTP</th>
									  <th>Tanggal Tera</th>				  
									  <th>Hasil Tera</th>
									  <th>No Transaksi</th>
									  <th>User Tera</th>
									</tr>
								  </thead>
								  <tbody>
								  	<?php
								  		$sql = "SELECT a.HasilAction1,a.NoTransaksi,a.TanggalTransaksi,b.NamaTimbangan,a.UserName
												FROM trtimbanganitem a
												JOIN timbanganperson b on (a.IDTimbangan,a.IDPerson)=(b.IDTimbangan,b.IDPerson)
												where a.IDTimbangan='".htmlspecialchars(base64_decode($_GET['id']))."'
												ORDER by a.TanggalTransaksi DESC";
										$conn = mysqli_query($koneksi, $sql);
										$no = 0;	
										while($row=mysqli_fetch_assoc($conn)){
											echo '<tr>';
												echo '<td>'.++$no.'</td>';
												echo '<td>'.$row['NamaTimbangan'].'</td>';
												echo '<td>'.TanggalIndo($row['TanggalTransaksi']).'</td>';
												echo '<td>'.$row['HasilAction1'].'</td>';
												echo '<td>'.$row['NoTransaksi'].'</td>';
												echo '<td>'.$row['UserName'].'</td>';
											echo '</tr>';
										}		

								  	?>
										
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
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>	
    <!-- Datatable -->
    <script type="text/javascript" src="../library/datatables/datatables.min.js"></script>
	
	<script>
	$(function () {
        $("#lookup").dataTable();
    });

	</script>
  </body>
</html>