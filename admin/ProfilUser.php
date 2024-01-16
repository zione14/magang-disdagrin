<?php
include '../admin/akses.php';
$Page = '';
$Edit = mysqli_query($koneksi,"SELECT * FROM userlogin WHERE UserName='$login_id'");
$RowData = mysqli_fetch_assoc($Edit);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include '../admin/title.php';?>
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
    <?php include '../admin/style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">

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
              <h2 class="no-margin-bottom">Profil <?php echo $login_nama; ?></h2>
            </div>
          </header>
		   <!-- Client Section-->
          <section class="client">
            <div class="container-fluid">
              <div class="row">
               
                <!-- Client Profile -->
                <div class="col-lg-4">
                  <div class="client card">
                    
                    <div class="card-body text-center">
                      <div class="client-avatar"><img src="<?php echo isset($RowData['FotoProfil']) ? '../images/ProfilUser/'.$RowData['FotoProfil'] : '../images/ProfilUser/user-icon.jpg'; ?>" id="avatar2" alt="..." class="img-fluid rounded-circle">
                        <div class="status bg-green"></div>
                      </div>
                      <div class="client-title">
                        <h3><?php echo $login_nama; ?></h3><span><?php echo $login_levelname; ?></span>
						<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['UserName'];?>' data-url='<?php echo 'ProfilUser';?>'>Ubah Foto</a>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Overdue             -->
                <div class="col-lg-8">
                  <div class="overdue card">
                    <div class="card-body">
                      <h3>Profil <?php echo $login_nama; ?></h3><br><!--<small></small>-->
					   <form method="post" action="">
                       <div class="form-group-material">
					       <input type="text" value="<?php echo @$RowData['NamaPegawai'];?>" name="NamaPegawai" maxlength="150" class="input-material">
					       <label class="label-material">Nama Lengkap</label>
					   </div>
					   <div class="form-group-material">
					       <input type="number" value="<?php echo @$RowData['NIP'];?>" name="NIP" maxlength="50" class="input-material">
					       <label class="label-material">NIP</label>
					   </div>
					   <div class="form-group-material">
					       <input type="text" value="<?php echo @$RowData['Jabatan'];?>" name="Jabatan" maxlength="200" class="input-material">
					       <label class="label-material">Jabatan</label>
					   </div>
					   <div class="form-group-material">
					       <input type="text" value="<?php echo @$RowData['Address'];?>" name="Alamat" maxlength="200" class="input-material">
					       <label class="label-material">Alamat</label>
					   </div>
					   <div class="form-group-material">
					       <input type="number" value="<?php echo @$RowData['HPNo'];?>" name="HP" maxlength="50" class="input-material">
					       <label class="label-material">No Handphone</label>
					   </div>
					   <div class="form-group-material">
					       <input type="email" value="<?php echo @$RowData['Email'];?>" name="Email" maxlength="50" class="input-material">
					       <label class="label-material">Email</label>
					   </div>
					   <div class="form-group-material">
					       <input type="text" value="<?php echo @$RowData['UserName'];?>" name="Username" maxlength="50" class="input-material" readonly>
					       <label class="label-material">Username</label>
					   </div>
					   <div class="form-group-material">
					       <input type="password" value="<?php echo @base64_decode($RowData['UserPsw']);?>" name="Password" maxlength="255" class="input-material">
					       <label class="label-material">Password</label>
					   </div>
					   <div align="right">
						   <button type="submit" name="SimpanEdit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i> Update Profile</button>
					   </div>
					   </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- Feeds Section-->
         
        </div>
      </div>
    </div>
	
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalDokumen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
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
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		//open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_view").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
		  var url_foto  = $(this).data("url");
			   $.ajax({
					   url: "Modal/UploadFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen, URLocation: url_foto},
					   success: function (ajaxData){
					   $("#ModalDokumen").html(ajaxData);
					   $("#ModalDokumen").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
	
	<?php 
		@$ActualName		= htmlspecialchars($_POST['NamaPegawai']);
		@$Address		 	= htmlspecialchars($_POST['Alamat']);
		@$HPNo			 	= htmlspecialchars($_POST['HP']);
		@$Email			 	= htmlspecialchars($_POST['Email']);
		@$LevelID		 	= htmlspecialchars($_POST['LevelID']);
		@$NIP			 	= htmlspecialchars($_POST['NIP']);
		@$JenisLogin		= htmlspecialchars($_POST['JenisLogin']);
		@$Jabatan		 	= htmlspecialchars($_POST['Jabatan']);
		@$UserPsw		 	= base64_encode($_POST['Password']);
		
		if(isset($_POST['SimpanEdit'])){
		
		// update data user login berdasarkan username yng di pilih
		$query = mysqli_query($koneksi,"UPDATE userlogin SET ActualName='$ActualName', NamaPegawai='$ActualName',Address='$Address',Email='$Email',UserPsw='$UserPsw',Jabatan='$Jabatan', NIP='$NIP', HPNo='$HPNo' WHERE UserName='$login_id'");
		
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah data User Login atas nama '.$ActualName, $login_id, '', '');
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "ProfilUser.php";
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
					window.location.href = "ProfilUser.php";
				  });
				  </script>';
		}
	}
	?>
  </body>
</html>