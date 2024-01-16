<?php
@session_start();
@session_destroy(); 
?>

<!DOCTYPE html>
<html>
<head>
 <title>DISDAGRIN | Kab.Jombang</title>
	<!-- Favicon -->
	<link rel="shortcut icon" href="../web/images/title.png">
	<!-- Sweet Alerts -->
	<link rel="stylesheet" href="sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Bootstrap CSS-->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<?php $id = base64_decode(@$_GET['id']); 
if(@$id === "error"){ ?>
<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script type="text/javascript">
	sweetAlert({
	title: "Maaf!",
	text: " Anda Harus Sign In ",
	type: "error"
	},
	function () {
	window.location.href = "../profil/home.php";
	});
</script>
<?php } elseif(@$id === "timeout"){ ?>
<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script type="text/javascript">
	sweetAlert({
	title: "Maaf!",
	text: " Anda Harus Sign In Kembali ",
	type: "error"
	},
	function () {
	window.location.href = "../profil/home.php";
	});
</script>
<?php } else { ?>
<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script type="text/javascript">
	sweetAlert({
	title: "Terima Kasih!",
	text: " Anda Telah Sign Out ",
	type: "info"
	},
	function () {
	window.location.href = "../profil/home.php";
	});
</script>
<?php } ?>
</body>
</html>




