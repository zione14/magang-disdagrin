<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>E-Katalog Bahan Pokok Penting (Harga Pasar) | DINAS PERDAGANGAN | Kab.Jombang</title>
  <link rel="shortcut icon" href="img/title.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Your page description here" />
  <meta name="author" content="" />

  <!-- css -->
  <link href="https://fonts.googleapis.com/css?family=Handlee|Open+Sans:300,400,600,700,800" rel="stylesheet">
  <link href="../assets/css/bootstrap.css" rel="stylesheet" />
  <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="../assets/css/flexslider.css" rel="stylesheet" />
  <link href="../assets/css/prettyPhoto.css" rel="stylesheet" />
  <link href="../assets/css/camera.css" rel="stylesheet" />
  <link href="../assets/css/jquery.bxslider.css" rel="stylesheet" />
  <link href="../assets/css/style.css" rel="stylesheet" />

	<!-- Font Awesome CSS-->
	<link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css">

  <!-- Theme skin -->
  <link href="../assets/color/default.css" rel="stylesheet" />
  <!--<link href="../assets/css/mycss.css" rel="stylesheet" /> -->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png" />
 
  <!-- =======================================================
    Theme Name: Eterna
    Theme URL: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->

</head>

<body>

  <div id="wrapper">

    <!-- start header -->
    <header style="z-index: 1;">
	 <div class="top">
		<div class="container">
		  <div class="row">
			<div class="span6">
			   <!-- <p class="topcontact">Hubungi Kami di  <i class="icon-phone"></i> 081554477423</p> -->
			</div>
			<div class="span6">
			  <ul class="social-network">
				<li><a href="#" target="_blank" style="text-decoration:none;" data-placement="bottom" title="Login"><i class="icon-user icon-white"><b style="font-family:arial;"> Login</b></i></a></li>
			  </ul>
			</div>
		  </div>
		</div>
	  </div>

	  <div class="container">
		<div class="row nomargin">
		  <div class="span4">
			<div class="logo">
                <a href="index.php"><img src="../web/images/logo.png" style="width: 30px;height: 30px;margin-bottom:0px;" alt="logo" class="logo-img" /></a> 
                <label style="display: inline;margin-left: 20px;">E-Katalog Bahan Pokok Penting (Harga Pasar)</label>
			</div>
		  </div>
		  <div class="span8">
			<div class="navbar navbar-static-top">
			  <div class="navigation">
				<nav>
				  <ul class="nav topnav">
            <li class="dropdown <?php if(basename($_SERVER['PHP_SELF']) === 'tabel-hargakonsumen.php' || basename($_SERVER['PHP_SELF']) === 'tabel-hargaprodusen.php' || basename($_SERVER['PHP_SELF']) === 'hargapokok-detailpasar.php'){ echo 'active'; } ?>">
                <a href="#">Tabel Data</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="tabel-hargakonsumen.php">Harga Konsumen</a>
                    </li>
                    <li>
                        <a href="tabel-hargaprodusen.php">Harga Produsen</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown <?php if(basename($_SERVER['PHP_SELF']) === 'grafik-hargakonsumen.php' || basename($_SERVER['PHP_SELF']) === 'grafik-hargaprodusen.php'){ echo 'active'; } ?>">
                <a href="#">Grafik</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="grafik-hargakonsumen.php">Harga Konsumen</a>
                    </li>
                    <li>
                        <a href="grafik-hargaprodusen.php">Harga Produsen</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown <?php if(basename($_SERVER['PHP_SELF']) === 'grafik-ketersediaan.php' || basename($_SERVER['PHP_SELF']) === 'tabel-ketersediaan.php'){ echo 'active'; } ?>">
                <a href="#">Ketersediaan Komoditas</a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="grafik-ketersediaan.php">Grafik</a>
                    </li>
                    <li>
                        <a href="tabel-ketersediaan.php">Tabel Data</a>
                    </li>
                </ul>
            </li>
            <li class="<?php if(basename($_SERVER['PHP_SELF']) === 'persebaran-pasar.php'){ echo "active"; } ?>">
                <a href="persebaran-pasar.php">Lokasi Pasar</a>
            </li>
				  </ul>
				</nav>
			  </div>
			  <!-- end navigation -->
			</div>
		  </div>
		</div>
	  </div>
	</header>
    <!-- end header --> 

<?php
  include "../library/config.php";
  date_default_timezone_set('Asia/Jakarta');
?>
<!-- <img src="../web/images/back3.png" alt="" srcset="" style="
width: 100%;
height: 160px;
object-fit: cover;"> -->