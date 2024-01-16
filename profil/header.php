
	<header>
	 <div class="top">
		<div class="container">
		  <div class="row">
			<div class="span6">
			   <p class="topcontact">Hubungi Kami di <i class="icon-phone"></i> <?php echo sistemSetting($koneksi, '2', 'value'); ?></p>
			   <?php 
                $sosmed1  = mysqli_query($koneksi,"SELECT * FROM setting where nama='SOSMED'");
                while ($rowsosmed1 = mysqli_fetch_assoc($sosmed1)) {

                  echo '<a href="'.$rowsosmed1['value'].'" Target="_BLANK"><img src="../images/Assets/'.$rowsosmed1['file'].'" style="width: 20px; height: 20px;"></a>&nbsp;'; 
                 
                }
              ?>
			</div>
			<div class="span6">
			  <ul class="social-network">
				<li><a href="home.php" data-placement="bottom" title="Kembali"><i class="icon-reply icon-white"><b style="font-family:arial;"> Kembali</b></i></a></li>
				<li><a href="login.php" data-placement="bottom" title="Login"><i class="icon-user icon-white"><b style="font-family:arial;"> Login</b></i></a></li>
			  </ul>
			</div>
		  </div>
		</div>
	  </div>

	  <div class="container">
		<div class="row nomargin">
		  <div class="span4">
			<div class="logo">
			  <a href="index.php"><img src="katalog/logo.png" alt="" /></a>
			</div>
		  </div>
		  <div class="span8">
			<div class="navbar navbar-static-top">
			  <div class="navigation">
				<nav>
				  <ul class="nav topnav">
					<li <?php if(@$Page=='Index'){echo 'class="dropdown active"';} ?>>
					  <a href="index.php"><i class="icon-home"></i> Beranda</a>
					 
					</li>
					<li class="dropdown <?php if(@$Page=='Profil'){echo 'active';} ?>">
					  <a href="#">Profil <i class="icon-angle-down"></i></a>
					  <ul class="dropdown-menu">
					  	<li><a href="Sambutan.php">Sambutan Kepala Dinas</a></li>
						<li><a href="Profil.php?id=<?php echo base64_encode('5'); ?>">Visi dan Misi</a></li>
						<li><a href="Profil.php?id=<?php echo base64_encode('6'); ?>">Selayang Pandang</a></li>
						<li><a href="Profil.php?id=<?php echo base64_encode('7'); ?>">Sejarah</a></li>
						<li><a href="Organisasi.php">Struktur Organisasi</a></li>
						<li><a href="Profil.php?id=<?php echo base64_encode('8'); ?>">Rencana Strategis</a></li>
						<li><a href="Profil.php?id=<?php echo base64_encode('9'); ?>">Program Kerja</a></li>
					  </ul>
					</li>
					<li class="dropdown <?php if(@$Page=='Informasi'){echo 'active';} ?>">
					  <a href="#">Informasi <i class="icon-angle-down"></i></a>
					  <ul class="dropdown-menu">
						<li><a href="Berita.php">Berita</a></li>
						<li><a href="Artikel.php">Artikel</a></li>
						<li><a href="Agenda.php">Agenda</a></li>
						<li><a href="Unduhan.php">Unduhan</a></li>
						<li><a href="Sakip.php">Dokumen SAKIP</a></li>
					  </ul>
					</li>
					<li class="dropdown">
					  <a href="#">e-OFFICE <i class="icon-angle-down"></i></a>
						<ul class="dropdown-menu">
						  <li><a href="../web/index.php?nm=<?php echo base64_encode('TIMBANGAN'); ?>">SiMOLEG</a></li>
						  <li><a href="../web/index.php?nm=<?php echo base64_encode('HARGA PASAR'); ?>">SAUDAGAR</a></li>
						  <li><a href="../web/index.php?nm=<?php echo base64_encode('RETRIBUSI PASAR'); ?>">e-PRAS</a></li>
						  <li><a href="../web/index.php?nm=<?php echo base64_encode('PUPUK SUBSIDI'); ?>">PUPUK SUBSIDI</a></li>
						</ul>
					</li> 
					<li class="dropdown <?php if(@$Page=='Layanan'){echo 'active';} ?>">
					  <a href="#">Layanan Masyarakat <i class="icon-angle-down"></i></a>
						<ul class="dropdown-menu">
						  <li><a href="../manajemen-pasar/tabel-hargakonsumen.php">E-Katalog Bahan Pokok Penting (Harga Pasar)</a></li>
						  <li><a href="Pengaduan.php">Sistem Layanan Pengaduan Masyarakat</a></li>
						  <li><a href="PermohonanTera.php">Sistem Layanan Tera & Tera Ulang UTTP</a></li>
						  <!-- <li><a href="login_distri.php">Login Distributor</a></li> -->
						  <!--<li><a href="#" onclick="return confirmation()">Sistem Layanan Pembinaan Perusahaan & Industri</a></li>
						  <li><a href="#" onclick="return confirmation()">Sistem Layanan Fasilitasi Perusahaan & Industri</a></li>-->
						</ul>
					</li> 
					<li class="dropdown <?php if(@$Page=='Galeri'){echo 'active';} ?>">
					  <a href="#">Galeri <i class="icon-angle-down"></i></a>
					  <ul class="dropdown-menu">
						<li><a href="GaleriFoto.php">Foto </a></li>
						<li><a href="GaleriVideo.php">Galeri Video</a></li>
					  </ul>
					</li>
				   <!-- <li class="dropdown">
					  <a href="#">Blog <i class="icon-angle-down"></i></a>
					  <ul class="dropdown-menu">
						<li><a href="blog-left-sidebar.html">Blog left sidebar</a></li>
						<li><a href="blog-right-sidebar.html">Blog right sidebar</a></li>
						<li><a href="blog-fullwidth.html">Blog fullwidth</a></li>
						<li><a href="post-left-sidebar.html">Post left sidebar</a></li>
						<li><a href="post-right-sidebar.html">Post right sidebar</a></li>
					  </ul>
					</li>-->
					<li <?php if(@$Page=='Kontak'){echo 'class ="active"';} ?>>
					  <a href="Kontak.php">Kontak Kami </a>
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
	<script type="text/javascript">
	function confirmation() {
		alert("Maaf Sedang Dalam Proses pengembangan");	return false; 	
			
	}
   </script>