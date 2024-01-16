<?php
session_start();
include "../library/config.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>DISDAGRIN | Kab.Jombang</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/title.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<!-- Sweet Alerts -->
	<link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-04.jpg);">
					<span class="login100-form-title-1">
						Login Administrator 
						<?php 
							if (base64_decode($_GET['nm']) == 'TIMBANGAN'){
								echo 'SiMOLEG';
							}elseif(base64_decode($_GET['nm']) == 'HARGA PASAR'){
								echo 'SAUDAGAR';
							}elseif(base64_decode($_GET['nm']) == 'RETRIBUSI PASAR'){
								echo 'eRPAS';
							}elseif(base64_decode($_GET['nm']) == 'PUPUK SUBSIDI'){
								echo 'PUPUK SUBSIDI';
							}
						
						?>
					</span>
				</div>

				<form class="login100-form validate-form" action="" method="post">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="username" placeholder="Enter username">
						<span class="focus-input100"></span>
					</div>
					<input type="hidden" name="jenis" value="<?php echo base64_decode($_GET['nm']); ?>">
					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" id="Password" name="password" placeholder="Enter password">
						<span class="focus-input100"></span>
					</div>
					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" onclick="myFunction()" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Show Password
							</label>
						</div>
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" style="border-radius:2px"  name="submit">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
<!--===============================================================================================-->
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
<!--===============================================================================================-->
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
<!--===============================================================================================-->
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
				window.location.href = "index.php?nm='.base64_encode($_POST['jenis']).'";
			  });
			  </script>';
		@session_destroy();
	}else{		
		// Variabel username dan password
		$username = @stripslashes($_POST['username']);
		$jenis 	  = @stripslashes($_POST['jenis']);
		$password = @stripslashes(base64_encode($_POST['password']));
		$waktu    = time()+25200; //(GMT+7)
		$expired  = 300000;
		
		// Mencegah MySQL injection dan XSS
		$check_user  = @htmlspecialchars(addslashes($username));
		$check_pass  = @htmlspecialchars(addslashes($password));
		$check_jenis = @htmlspecialchars(addslashes($jenis));
		
		// SQL query untuk memeriksa apakah user terdapat di database?
		$query = "SELECT * FROM userlogin WHERE replace(UserName,' ','') = replace('{$check_user}',' ','') AND UserPsw = '{$check_pass}' AND IsAktif=b'1' AND ( JenisLogin='$check_jenis' OR JenisLogin='SUPER ADMIN' )";
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
						window.location.href = "index.php?nm='.base64_encode($_POST['jenis']).'";
					  });
					  </script>';
				@session_destroy();
			}else{
				$row = @mysqli_fetch_array($query);
				//session
				@$_SESSION['user_login']   = $row['UserName'];
				@$_SESSION['user_pass']    = $row['UserPsw'];
				@$_SESSION['timeout'] 	 = $waktu + $expired; // Membuat Sesi Waktu
				
				echo '<script type="text/javascript">
				  sweetAlert({
					title: "Login Sukses!",
					text: " Anda Berhasil Login ",
					type: "success"
				  },
				  function () {
					window.location.href = "../admin/index.php";
				  });
				  </script>';
			}
			@mysqli_close(); // Menutup koneksi
	}
}
?>

</body>
</html>