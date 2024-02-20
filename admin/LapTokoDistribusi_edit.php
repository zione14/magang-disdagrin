<?php
include 'akses.php';
$Page = 'LapPupuk';
$SubPage = 'LapTokoDistribusi';
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
	.table thead th {
		
		text-align: center;
		
		border: 2px solid #dee2e6;
	}
	td {
		border: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">Edit Data</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Edit Data Toko </h3>
							</div>
							<div class="card-body">							  
								<div class="row">
								  <div class="col-lg-7">
								  	<?php
								  		$id = base64_decode(mysqli_real_escape_string($koneksi, $_GET['id']));

								  		$sql =  "SELECT a.NamaPerson as Distributor, b.NamaPerson, b.AlamatLengkapPerson, b.NoHP,b.IDPerson 
										FROM mstperson a 
										join mstperson b on (a.IDPerson=b.ID_Distributor) 
										where a.JenisPerson LIKE '%PupukSub%'  AND a.IsVerified=b'1' AND b.IDPerson='$id'";
										$oke = $koneksi->prepare($sql);
										$oke->execute();
										$result = $oke->get_result();
										$data = mysqli_fetch_array($result);

								  	?>
								  	<form method="post" action="LapTokoDistribusi_edit.php">
								  		<div class="form-group">
										  <label>Nama Distributor</label>
										  <input type="text" class="form-control" placeholder="Nama Distributor" value="<?php echo @$data['Distributor'];?>" readonly>
										</div>
										<div class="form-group">
										  <label>Nama Toko</label>
										  <input type="text" class="form-control" placeholder="Nama Toko" value="<?php echo @$data['NamaPerson'];?>" readonly>
										</div>
										<div class="form-group">
										  <label>Alamat Toko</label>
										  <input type="text" name="AlamatLengkapPerson" class="form-control" placeholder="Alamat Lengkap" value="<?php echo @$data['AlamatLengkapPerson'];?>" maxlength="255" required>
										</div>
										<div class="form-group">
										  <label>No Telephone</label>
										  <input type="text" name="NoHP" class="form-control" placeholder="No Telephone" value="<?php echo @$data['NoHP'];?>" maxlength="255" required>
										</div>
										<div class="form-group-material">

										<input type="hidden" name="IDPerson" value="<?=$data['IDPerson']?>">
										<button type="submit" class="btn btn-primary" name="Edit">Simpan</button> &nbsp;
										<a href="LapTokoDistribusi.php"><span class="btn btn-warning">Batalkan</span></a>
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
	
	<?php

	//Post data user simpan data baru
	@$AlamatLengkapPerson	= htmlspecialchars($_POST['AlamatLengkapPerson']);
	@$NoHP	= htmlspecialchars($_POST['NoHP']);
	@$IDPerson	= htmlspecialchars($_POST['IDPerson']);
		
	if(isset($_POST['Edit'])){
		
		//query update
		$query = mysqli_query($koneksi,"UPDATE mstperson SET AlamatLengkapPerson='$AlamatLengkapPerson',NoHP='$NoHP' WHERE IDPerson='$IDPerson'");

		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Data Toko atas id '.$IDPerson, $login_id, $IDPerson, 'Laporan Toko Distributor');
			echo '<script language="javascript">document.location="LapTokoDistribusi.php?s=1";</script>';

		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterUser.php";
				  });
				  </script>';
		}
	}
	
	
	?>
  </body>
</html>