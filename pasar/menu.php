<nav class="side-navbar">
	<!-- Sidebar Header-->
	<div class="sidebar-header d-flex align-items-center">
		<div class="avatar">
			<a href="ProfilUser.php" ><img src="<?php echo '../images/ProfilUser/user-icon.jpg'; ?>" alt="..." class="img-fluid rounded-circle"></a>
		</div>
		<div class="title">
			<p>Selamat Datang</p>
			<h1 class="h4"><?php echo @$login_nama; 
			echo '<br/><font color="#796AEE" size="3px">';echo '</font>';?></h1> 
		</div>
	</div>
	<!-- Sidebar Navidation Menus-->
	<ul class="list-unstyled">
		<span class="heading">Main</span>
		<!-- <li <?php if(@$Page=='index'){echo 'class="active"';} ?>><a href="index.php"> <i class="icon-home"></i>Dashboard </a></li> -->
		<li <?php if($Page=='Request'){echo 'class="active"';} ?>><a href="Request.php"> <i class="fa fa-commenting"></i> Request Karcis </a></li>
		<li <?php if($Page=='Verifikasi'){echo 'class="active"';} ?>><a href="Verifikasi.php"> <i class="fa fa-check"></i> Verifikasi Penerimaan </a></li>
		<li <?php if($Page=='Pengeluaran'){echo 'class="active"';} ?>><a href="Pengeluaran.php"> <i class="fa fa-external-link"></i> Pengeluaran Karcis </a></li>
		<li <?php if($Page=='TrRetribusiPasar'){echo 'class="active"';} ?>><a href="TrRetribusiPasar.php"> <i class="fa fa-credit-card"></i>Pembayaran Retribusi</a></li>
		<li <?php if($Page=='Histori'){echo 'class="active"';} ?>><a href="Histori.php"> <i class="fa fa-refresh"></i> Histori Pengeluaran Karcis </a></li>
		<li <?php if($Page=='Laporan'){echo 'class="active"';} ?>><a href="Laporan.php"> <i class="fa fa-file"></i> Laporan Stok Karcis</a></li>
	</ul>
</nav>