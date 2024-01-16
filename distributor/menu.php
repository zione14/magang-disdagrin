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
		<li <?php if(@$Page=='index'){echo 'class="active"';} ?>><a href="index.php"> <i class="icon-home"></i>Dashboard </a></li>
		<!--<li <?php if(@$Page=='ManajemenToko'){echo 'class="active"';} ?>><a href="ManajemenToko.php"> <i class="fa fa-university"></i>Manajemen Toko</a></li>-->
		<li <?php if($Page=='ManajemenToko'){echo 'class="active"';} ?>><a href="MasterPerson.php?v=Toko"> <i class="fa fa-university"></i> Manajemen Toko </a></li>
		<li><a href="#Transaksi"  <?php if(@$Page=='Transaksi'){
				echo 'aria-expanded="true"';
			}else{ 
				echo 'aria-expanded="false"';
			} ?> data-toggle="collapse"> <i class="fa fa-list"></i>Distribusi Pupuk</a>
			<ul id="Transaksi" class="collapse list-unstyled <?php if(@$Page=='Transaksi'){echo 'show';}?>">
				<li <?php if(@$SubPage=='Penerimaan'){echo 'class="active"';} ?>><a href="Penerimaan.php">Penerimaan Pupuk</a></li>
				<li <?php if(@$SubPage=='Penjualan'){echo 'class="active"';} ?>><a href="Penjualan.php">Pengeluaran Pupuk</a></li>
			</ul>
		</li>
		<li <?php if($Page=='LaporanStok'){echo 'class="active"';} ?>><a href="LaporanStok.php"> <i class="fa fa-line-chart"></i>Laporan Stok Barang</a></li>
	</ul>
</nav>