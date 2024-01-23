<html>
<head>
	<?php include 'title.php';?>
	<link href="css/home1.css" rel="stylesheet" />

</head>
<body style="margin : 0px;font-family:Arial">


	<div class="container">
		<div class="topnav" id="myTopnav" style="text-align:center;">
			<a href="index.php" style="padding: 18px 10px;"> <img src="img/title.png" style="width: 30px; margin-top: -5px;"></a>
			<a href="index.php"> Beranda</a>

			<div class="dropdown">
				<button class="dropbtn">Profil Kami
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
				  <a href="Sambutan.php">Sambutan Kepala Dinas</a>
				  <a href="Profil.php?id=<?php echo base64_encode('5'); ?>">Visi dan Misi</a>
				  <a href="Profil.php?id=<?php echo base64_encode('6'); ?>">Selayang Pandang</a>
				  <a href="Profil.php?id=<?php echo base64_encode('7'); ?>">Sejarah</a>
				  <a href="Organisasi.php">Struktur Organisasi</a>
				  <a href="Profil.php?id=<?php echo base64_encode('8'); ?>">Rencana Strategis</a>
				  <a href="Profil.php?id=<?php echo base64_encode('9'); ?>">Program Kerja</a>
				</div>
			</div> 

			<div class="dropdown">
				<button class="dropbtn">Informasi
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
				  <a href="Berita.php">Berita</a>
				  <a href="Artikel.php">Artikel</a>
				  <a href="Agenda.php">Agenda</a>
				  <a href="Unduhan.php">Unduhan</a>
				  <a href="Sakip.php">Dokumen SAKIP</a>
				</div>
			</div> 

			<div class="dropdown">
				<button class="dropbtn">e-OFFICE
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
				  <a href="../web/index.php?nm=<?php echo base64_encode('TIMBANGAN'); ?>">SiMOLEG</a>
				  <a href="../web/index.php?nm=<?php echo base64_encode('HARGA PASAR'); ?>">SAUDAGAR</a>
				  <a href="../web/index.php?nm=<?php echo base64_encode('RETRIBUSI PASAR'); ?>">e-RPAS</a>
				  <a href="../web/index.php?nm=<?php echo base64_encode('PUPUK SUBSIDI'); ?>">PUPUK SUBSIDI</a>
				  <a href="login_pasar.php">KARCIS RETRIBUSI</a>
				</div>
			</div> 

			<div class="dropdown">
				<button class="dropbtn">Layanan Masyarakat
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
				  <a href="../manajemen-pasar/tabel-hargakonsumen.php">E-Katalog Bahan Pokok Penting (Harga Pasar)</a>
				  <a href="Pengaduan.php">Sistem Layanan Pengaduan Masyarakat</a>
				  <a href="PermohonanTera.php">Sistem Layanan Tera & Tera Ulang UTTP</a>
				  <a href="https://oss.go.id/">Sistem Oss(Perizinan Usaha)</a>
				  <a href="https://ptsp.halal.go.id/">Sihalal</a>
				  <a href="https://merek.dgip.go.id/">Sertifikat HKI</a>
				  <a href="https://sppirt.pom.go.id/">Pengajuan S-PIRT dan BPOM</a>
				  <a href="pendafyaranPKL.php">Pengajuan Izin Usaha Pedagang Kaki Lima</a>
				</div>
			</div> 

			<div class="dropdown">
				<button class="dropbtn">Galeri
					<i class="fa fa-caret-down"></i>
				</button>
				<div class="dropdown-content">
				  <a href="GaleriFoto.php">Foto</a>
				  <a href="GaleriVideo.php">Galeri Video</a>
				</div>
			</div>

			<a href="Kontak.php" >Kontak</a>
			<a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
		</div>
	
		
		<div id="main_image">
			<img src="img/cloud.gif"  style="width: 75%;height:auto;">
		</div>
		<div id="overlay_image">
			<marquee behavior="scroll" direction="left" >
				<img src="img/birds.gif" style="width: 25%;height:auto;">
			</marquee>	
		</div>
		<div id="overlay_koran">
			<img src="img/koran.gif" style="width: 50%;height:auto; ">
		</div>
		<div id="overlay_people1">
			<marquee behavior="scroll" direction="right">
				<img src="img/5.gif" style="width: 450px;">
			</marquee>
		</div>
			<div id="overlay_people2">
			<marquee behavior="scroll"  SCROLLDELAY=180 direction="right" height="5%" width="100%">
				<img src="img/1.gif" style="width: 250px;">
			</marquee>
		</div>
		</div>
		<div id="overlay_cycling">
			<marquee behavior="scroll" direction="left" height="5%" width="100%">
				<img src="img/3.gif" style="width: 230px;">
			</marquee>
		</div>
		
	</div>
	
	<script>
	function myFunction() {
	  var x = document.getElementById("myTopnav");
	  if (x.className === "topnav") {
		x.className += " responsive";
	  } else {
		x.className = "topnav";
	  }
	}
	</script>

</body>
</html>

