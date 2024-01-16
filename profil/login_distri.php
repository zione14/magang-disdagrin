<?php
session_start();
include "../library/config.php";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../admin_web/assets/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Google fonts - Popppins for copy-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,800">
    <!-- orion icons-->
    <link rel="stylesheet" href="../admin_web/assets/css/orionicons.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="../admin_web/assets/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../admin_web/assets/css/custom.css">
    <!-- Sweet Alerts -->
	<link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
  </head>
  <body>
    <div class="page-holder d-flex align-items-center">
      <div class="container">
        <div class="row align-items-center py-5">
          <div class="col-5 col-lg-7 mx-auto mb-5 mb-lg-0">
            <div class="pr-lg-5"><img src="../images/Assets/5.png" alt="" class="img-fluid"></div>
            <!--<div class="pr-lg-5"><img src="../images/Assets/illustration.svg" alt="" class="img-fluid"></div>
			 <div class="pr-lg-5"><img src="../assets/image/login.png" alt="" class="img-fluid"></div>-->
          </div>
          <div class="col-lg-5 px-lg-4">
            <h1 class="text-base text-primary text-uppercase mb-4">Kabupaten Jombang</h1>
            <h2 class="mb-4">Selamat Datang Distributor!</h2>
            <p class="text-muted">Sistem Manajemen Pupuk Subsidi, Silahkan Login terlebih dahulu..</p>
            <form id="loginForm" action="" method="post" class="mt-4">
              <div class="form-group mb-4">
                <input type="text" name="username" placeholder="NIK" class="form-control border-0 shadow form-control-lg">
              </div>
              <div class="form-group mb-4">
                <input type="password" name="password" placeholder="Password" id="Password" class="form-control border-0 shadow form-control-lg text-violet">
              </div>
              <div class="form-group mb-4">
                <div class="custom-control custom-checkbox">
				  <input  id="customCheck1" type="checkbox" onclick="myFunction()"  class="custom-control-input"> 
                  <label for="customCheck1" class="custom-control-label">Show Password</label>
                </div>
              </div>
              <button type="submit" name="submit" class="btn btn-primary shadow px-5">Login</button>
			  <a href="index.php" class="btn btn-primary shadow px-5">Back</a>
            </form>
          </div>
        </div>
        <p class="mt-5 mb-0 text-gray-400 text-center">Design by <a href="https://afindo-inf.com" target="_BLANK" class="external text-gray-400"> Afindo Informatika</a></p>
        
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="../admin_web/assets/vendor/jquery/jquery.min.js"></script>
    <script src="../admin_web/assets/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../admin_web/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../admin_web/assets/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../admin_web/assets/vendor/chart.js/Chart.min.js"></script>
    <script src="../admin_web/assets/js/front.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<script type="text/javascript">
      function myFunction() {
        var x = document.getElementById("Password");
        if (x.type === "password"){
          x.type = "text";
        } else {
          x.type = "password";
        }
      }
    </script>
	
  </body>
</html>
<?php
//porses submit login
if(isset($_POST['submit'])){
	if(empty($_POST['username']) || empty ($_POST ['password'])) {
		// user and pass kosong
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Maaf!",
				text: " Username atau Password Kosong ",
				type: "error"
			  },
			  function () {
				window.location.href = "login_distri.php";
			  });
			  </script>';
		@session_destroy();
	}else{		
		// Variabel username dan password
		$username = @stripslashes($_POST['username']);
		$password = @stripslashes(base64_encode($_POST['password']));
		$waktu    = time()+25200; //(GMT+7)
		$expired  = 300000;
		
		// Mencegah MySQL injection dan XSS
		$check_user = @htmlspecialchars(addslashes($username));
		$check_pass = @htmlspecialchars(addslashes($password));
		
		// SQL query untuk memeriksa apakah user terdapat di database?
		$query = "SELECT * FROM mstperson WHERE replace(UserName,' ','') = replace('{$check_user}',' ','') AND 	Password = '{$check_pass}' AND IsVerified=b'1'";
		//echo $query;exit();
		$query = @mysqli_query($koneksi, $query);
		echo $cari = @mysqli_num_rows($query); 
			
			if($cari === 0){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Login Gagal!",
						text: " Username / Password Salah atau Pengguna Tidak Aktif ",
						type: "error"
					  },
					  function () {
						window.location.href = "login_distri.php";
					  });
					  </script>';
				@session_destroy();
			}else{
				$row = @mysqli_fetch_array($query);
				//session
				@$_SESSION['user_login']   = $row['UserName'];
				@$_SESSION['user_pass']    = $row['Password'];
				@$_SESSION['timeout'] 	   = $waktu + $expired; // Membuat Sesi Waktu
				
				echo '<script type="text/javascript">
				  sweetAlert({
					title: "Login Sukses!",
					text: " Anda Berhasil Login ",
					type: "success"
				  },
				  function () {
					window.location.href = "../distributor/index.php";
				  });
				  </script>';
			}
			@mysqli_close(); // Menutup koneksi
	}
}
?>