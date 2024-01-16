<?php
include "../library/config.php";
date_default_timezone_set('Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<title>E-Katalog Bahan Pokok Penting (Harga Pasar) | DINAS PERDAGANGAN | Kab.Jombang</title>
<link rel="shortcut icon" href="img/map-close.png">
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
<!-- <link href="../assets/css/mycss.css" rel="stylesheet" /> -->

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
<header>
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
<a href="#">Ketersediann Komoditas</a>
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
<?php
include '../library/pagination1.php';
date_default_timezone_set('Asia/Jakarta');

$date = date('Y-m-d');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : $date;
$date_minus_sebulan = date('Y-m-d', strtotime($Tanggal.' -1 month')); 

$sql_p = "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa
FROM mstpasar p
INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$datapasar = array();
while ($row_p = $res_p->fetch_assoc()) {
	array_push($datapasar, $row_p);
}
?>

<style>
/* Always set the map height explicitly to define the size of the div
* element that contains the map. */
#map {
height: 100%;
}
</style>

<section id="content">
<div class="container">
<div class="row">
<div class="span12">
<h4>Halaman Peta Persebaran Pasar</h4>
</div>
<div class="span12">
<label>Koordinat Lokasi</label>
<div id="mappengambilan" style="height:450px;width:100%;margin-bottom:30px;"></div>
</div>
</div>
</div>
</section>


<footer>
<div class="container">
<div class="row">
<div class="span4">
<div class="widget">
<h5 class="widgetheading">Profil Kami</h5>
<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Minus molestias illum distinctio quam doloribus numquam rerum suscipit vel cum dolorum officia aliquid, doloremque quas nihil vero modi ullam sunt nisi!</p>
</div>
</div>
<div class="span4">
<div class="widget">
<h5 class="widgetheading">Kontak Kami</h5>
<p><strong>Dinas Perdagangan dan Perindustrian Jombang</strong><br>
Jl. Wahid Hasyim No.143 Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</p>
</div>
</div>
</div>
</div>
<div id="sub-footer">
<div class="container">
<div class="row">
<div class="span6">
<div class="copyright">
<p><span>&copy; 2019. All right reserved</span></p>
</div>

</div>

<div class="span6">
<div class="credits">
Designed by <a href="#" Target="_BLANK">Afindo Inf</a>
</div>
</div>
</div>
</div>
</div>
</footer>
<!-- end footer -->
</div>
<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bglight icon-2x active"></i></a>

<!-- javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/jquery.easing.1.3.js"></script>
<script src="../assets/js/bootstrap.js"></script>

<script src="../assets/js/modernizr.custom.js"></script>
<script src="../assets/js/toucheffects.js"></script>
<script src="../assets/js/google-code-prettify/prettify.js"></script>
<script src="../assets/js/jquery.bxslider.min.js"></script>
<script src="../assets/js/camera/camera.js"></script>
<script src="../assets/js/camera/setting.js"></script>

<script src="../assets/js/jquery.prettyPhoto.js"></script>
<script src="../assets/js/portfolio/jquery.quicksand.js"></script>
<script src="../assets/js/portfolio/setting.js"></script>

<script src="../assets/js/jquery.flexslider.js"></script>
<script src="../assets/js/animate.js"></script>
<script src="../assets/js/inview.js"></script>

<!-- Template Custom JavaScript File -->
<script src="../assets/js/custom.js"></script>

<script type="text/javascript">

function initAutocomplete() {
	var map_ambil = new google.maps.Map(document.getElementById('mappengambilan'), {
center: {lat: -7.556032627191996, lng: 112.221},
zoom: 12,
		//mapTypeId: 'satellite'
mapTypeControl: false,
	});
	
	var markers = [];
	var datapasar = <?php echo json_encode($datapasar); ?>;
	for (let i = 0; i < datapasar.length; i++) {
		const pasar = datapasar[i];
		var contentString = '<h4>'+pasar.NamaPasar+'</h4><table class="table stripped"><tbody><tr><td>Nama Kepala</td><td>'+pasar.NamaKepalaPasar+'</td></tr><tr><td>No Telepon</td><td>'+pasar.NoTelpPasar+'</td></tr><tr><td>Alamat Lengkap</td><td>'+pasar.NamaDesa+', '+pasar.NamaKecamatan+', '+pasar.NamaKabupaten+'</td></tr><tr><td colspan="2"><a href="hargapokok-detailpasar.php?psr='+encode(pasar.KodePasar)+'" class="btn btn-sm btn-primary">Lihat Harga Pokok</a></td></tr></tbody></table>';
		var marker = new google.maps.Marker({
position: {lat: parseFloat(pasar.KoorLat), lng: parseFloat(pasar.KoorLong)},
map: map_ambil,
title: pasar.NamaPasar
		});
		markers.push({
marker:marker, contentinfo:contentString
		});
	}

	for (let j = 0; j < markers.length; j++) {
		const mm = markers[j];
		addInfoWindow(map_ambil, mm.marker, mm.contentinfo);
	}
}

function addInfoWindow(map, marker, message) {
	var infoWindow = new google.maps.InfoWindow({
content: message
	});

	google.maps.event.addListener(marker, 'click', function () {
		infoWindow.open(map, marker);
	});
}

// Function to encode a string to base64 format
function encode(str) {
	encodedString = btoa(str);
	return encodedString;
}

// Function to decode a string from base64 format
function decode(str) {
	decodedString = atob(str);
	return decodedString;
}
</script>

<!-- Maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaX_RkNdrKRsaMYOp9PCOj11JgrZNgR6w&language=id&region=ID&libraries=places&callback=initAutocomplete"></script>
<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
</body>
</html>

