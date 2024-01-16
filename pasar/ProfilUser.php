<?php
include 'akses.php';
$Page = '';
$Edit = mysqli_query($koneksi,"SELECT * FROM userlogin WHERE UserName ='$login_id'");
$RowData = mysqli_fetch_assoc($Edit);

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
                      <div class="client-avatar"><img src="<?php echo '../images/ProfilUser/user-icon.jpg'; ?>" id="avatar2" alt="..." class="img-fluid rounded-circle">
                        <div class="status bg-blue"></div>
                      </div>
                      <div class="client-title">
                        <h3><?php echo $login_nama; ?></h3><span></span>
						<!--<a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['IDPerson'];?>' data-url='<?php echo 'ProfilUser';?>'>Ubah Foto</a>-->
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
					       <input type="text" value="<?php echo @$RowData['Address'];?>" name="Address" maxlength="200" class="input-material">
					       <label class="label-material">Alamat</label>
					   </div>
					   <div class="form-group-material">
					       <input type="number" value="<?php echo @$RowData['HPNo'];?>" name="HPNo" maxlength="50" class="input-material">
					       <label class="label-material">No Handphone</label>
					   </div>

					   <div class="form-group-material">
					       <input type="text" value="<?php echo @$RowData['UserName'];?>" name="UserName" maxlength="50" class="input-material" readonly>
					       <label class="label-material">Username</label>
					   </div>
					   <div class="form-group-material">
					       <input type="password" value="<?php echo @base64_decode($RowData['UserPsw']);?>" name="UserPsw" maxlength="255" class="input-material">
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
					   url: "UploadFoto.php",
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
		@$Address			= htmlspecialchars($_POST['Address']);
		@$NamaPegawai		= htmlspecialchars($_POST['NamaPegawai']);
		@$HPNo			 	= htmlspecialchars($_POST['HPNo']);
		@$UserName			= htmlspecialchars($_POST['UserName']);
		@$UserPsw		 	= base64_encode($_POST['UserPsw']);
		
		if(isset($_POST['SimpanEdit'])){
		
		// update data user login berdasarkan username yng di pilih
		$query = mysqli_query($koneksi,"UPDATE userlogin SET NamaPegawai='$NamaPegawai', Address='$Address', HPNo='$HPNo', UserPsw='$UserPsw' WHERE UserName='$UserName'");
		
		if($query){
			
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
		// closing connection 
		mysqli_close($koneksi); 	
	}
	?>
  </body>
</html>