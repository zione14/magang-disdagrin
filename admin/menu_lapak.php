<nav class="side-navbar">
	<!-- Sidebar Header-->
	<div class="sidebar-header d-flex align-items-center">
		<div class="avatar">
			<a href="ProfilUser.php" ><img src="<?php echo isset($FotoProfil) ? '../images/ProfilUser/'.$FotoProfil : '../images/ProfilUser/user-icon.jpg'; ?>" alt="..." class="img-fluid rounded-circle"></a>
		</div>
		<div class="title">
			<p>Selamat Datang</p>
			<h1 class="h4"><?php echo @$login_nama; 
			echo '<br/><font color="#796AEE" size="3px">'; echo ucwords(strtolower(@$login_levelname));echo '</font>';?></h1> 
		</div>
	</div>
	<!-- Sidebar Navidation Menus-->
	<ul class="list-unstyled">
	<span class="heading">Detil Lapak</span>
	<!--========================================================================================================================================================== -->
	<li <?php if(@$Page=='Informasi'){echo 'class="active"';} ?>><a href="MapDetil.php?l=<?php echo base64_encode($IDLapak); ?>&p=<?php echo base64_encode($KodePasar)?>&n=<?php echo base64_encode($NamaPasar); ?>"> <i class="fa fa-address-card-o"></i>Informasi Lapak </a></li>
	<li <?php if(@$Page=='Pembayaran'){echo 'class="active"';} ?>><a href="MapPembayaran.php?l=<?php echo base64_encode($IDLapak); ?>&p=<?php echo base64_encode($KodePasar)?>&n=<?php echo base64_encode($NamaPasar); ?>"> <i class="fa fa-refresh"></i>Histori Pembayaran</a></li>
	<li <?php if(@$Page=='UTTP'){echo 'class="active"';} ?>><a href="MapUTTP.php?l=<?php echo base64_encode($IDLapak); ?>&p=<?php echo base64_encode($KodePasar)?>&n=<?php echo base64_encode($NamaPasar); ?>"> <i class="fa fa-balance-scale "></i>Keberadaan UTTP</a></li>
	<li <?php if(@$Page=='Pemilik'){echo 'class="active"';} ?>><a href="MapPemilik.php?l=<?php echo base64_encode($IDLapak); ?>&p=<?php echo base64_encode($KodePasar)?>&n=<?php echo base64_encode($NamaPasar); ?>"> <i class="fa fa-user-plus"></i>Histori Pemilik</a></li>
	<li <?php if(@$Page=='Kembali'){echo 'class="active"';} ?>><a href="Map<?=$NamaPasar?>.php"> <i class="fa fa-arrow-circle-left"></i>Kembali</a></li>
	
	
</ul>
</nav>