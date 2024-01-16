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
	<span class="heading">Main</span>
	<!--========================================================================================================================================================== -->
	<li <?php if(@$Page=='index'){echo 'class="active"';} ?>><a href="index.php"> <i class="icon-home"></i>Dashboard </a></li>
	
	<?php if ($JenisLogin == 'SUPER ADMIN') { ?>
	<li <?php if(@$Page=='PengaduanMasyarakat'){echo 'class="active"';} ?>><a href="PengaduanMasyarakat.php"> <i class="fa fa-envelope"></i>Pengaduan Masyarakat </a></li>
	
	<?php } ?>
	<?php if ($JenisLogin == 'RETRIBUSI PASAR') { ?>
	<li <?php if($Page=='MasterPerson'){echo 'class="active"';} ?>><a href="MasterPerson.php?v=Pedagang"> <i class="fa fa-user"></i> Data User </a></li>
	<?php } ?>
	
	<?php if ($JenisLogin == 'TIMBANGAN') { ?>
	<li <?php if($Page=='MasterPerson'){echo 'class="active"';} ?>><a href="MasterPerson.php?v=Timbangan"> <i class="fa fa-user"></i> Data User </a></li>
	
	<?php } ?>
	<?php if ($JenisLogin == 'SUPER ADMIN') { ?>
	<li <?php if($Page=='MasterPerson'){echo 'class="active"';} ?>><a href="MasterPerson.php?v=#"> <i class="fa fa-user"></i> Data User </a></li>
	
	<?php } ?>
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'HARGA PASAR') { ?>
	<span class="heading" style="margin-top:10px;">Manajemen Pasar</span>

	<li><a href="#Master"  <?php if(@$Page=='MasterData'){
			echo 'aria-expanded="true"';
		}else{ 
			echo 'aria-expanded="false"';
		} ?> data-toggle="collapse"> <i class="fa fa-list"></i>Master Data</a>
		<ul id="Master" class="collapse list-unstyled <?php if(@$Page=='MasterData'){echo 'show';}?>">
			
			<li <?php if(@$SubPage=='MasterPasar'){echo 'class="active"';} ?>><a href="MasterPasar.php?view=1">Data Pasar</a></li>
			<li <?php if(@$SubPage=='MasterGrup'){echo 'class="active"';} ?>><a href="MasterGrup.php">Data Grup Barang</a></li>
			<li <?php if(@$SubPage=='MasterBarang'){echo 'class="active"';} ?>><a href="MasterBarang.php">Data Barang</a></li>
		</ul>
	</li>

	<li <?php if($Page=='HargaHarian'){echo 'class="active"';} ?>><a href="HargaHarian.php"> <i class="fa fa-money"></i>Harga Konsumen </a></li>
	<li <?php if($Page=='HargaProdusen'){echo 'class="active"';} ?>><a href="HargaProdusen.php"> <i class="fa fa-usd"></i>Harga Produsen </a></li>
	<li <?php if($Page=='Ketersediaan'){echo 'class="active"';} ?>><a href="Ketersediaan.php"> <i class="fa fa-cart-plus"></i>Ketersediaan </a></li>
	<li <?php if($Page=='HargaHarianLaporan'){echo 'class="active"';} ?>><a href="HargaHarian-Laporan.php"> <i class="fa fa-line-chart"></i>Tabel dan Grafik </a></li>

	<li><a href="#Rekap"  <?php if(@$Page=='Rekap'){
			echo 'aria-expanded="true"';
		}else{ 
			echo 'aria-expanded="false"';
		} ?> data-toggle="collapse">  <i class="icon-padnote"></i>Laporan</a>
		<ul id="Rekap" class="collapse list-unstyled <?php if(@$Page=='Rekap'){echo 'show';}?>">
			<li <?php if(@$SubPage=='LaporanPasar'){echo 'class="active"';} ?>><a href="DataHargaPasar.php">Laporan Pasar</a></li>
			<li <?php if(@$SubPage=='Harian'){echo 'class="active"';} ?>><a href="Rekaphpp-Harian.php">Rekap Harian</a></li>
			<!-- <li <?php if(@$SubPage=='Mingguan'){echo 'class="active"';} ?>><a href="Rekaphpp-Mingguan.php">Rekap Mingguan</a></li> -->
			<li <?php if(@$SubPage=='Bulanan'){echo 'class="active"';} ?>><a href="Rekaphpp-Bulanan.php">Rekap Bulanan</a></li>
			<li <?php if(@$SubPage=='Tahunan'){echo 'class="active"';} ?>><a href="Rekaphpp-Tahunan.php">Rekap Tahunan</a></li>
			<li <?php if(@$SubPage=='RataRata'){echo 'class="active"';} ?>><a href="RekapSemuaPasar.php">Rata Rata Harga</a></li>
		</ul>
	</li>
	
	
	<?php } ?>
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'PUPUK SUBSIDI') { ?>
	<span class="heading" style="margin-top:10px;">Manajemen Distribusi Pupuk</span>
	<li <?php if($Page=='MasterPupuk'){echo 'class="active"';} ?>><a href="MasterPupuk.php"> <i class="fa fa-indent"></i>Data Pupuk Subsidi</a></li>
	<li <?php if($Page=='MasterPerson'){echo 'class="active"';} ?>><a href="MasterPerson.php?v=PupukSub"> <i class="fa fa-car"></i> Data Distributor </a></li>
	<li <?php if($Page=='ImportLap'){echo 'class="active"';} ?>><a href="ImportLapPupuk.php"> <i class="fa fa-file-excel-o"></i> Import Laporan </a></li>
	
	<!--<li <?php if($Page=='MasterDistribusi'){echo 'class="active"';} ?>><a href="MasterDistribusi.php"> <i class="fa fa-car"></i>Data Distributor</a></li>-->
	<li><a href="#LapPupuk"  <?php if(@$Page=='LapPupuk'){
			echo 'aria-expanded="true"';
		}else{ 
			echo 'aria-expanded="false"';
		} ?> data-toggle="collapse">  <i class="fa fa-globe"></i>Laporan Distribusi Pupuk</a>
		<ul id="LapPupuk" class="collapse list-unstyled <?php if(@$Page=='LapPupuk'){echo 'show';}?>">
			<li <?php if(@$SubPage=='LapStokDistribusi'){echo 'class="active"';} ?>><a href="LapStokDistribusi.php">Laporan Stok Distributor</a></li>
			<li <?php if(@$SubPage=='LapDistribusiPupuk'){echo 'class="active"';} ?>><a href="LapDistribusiPupuk.php">Laporan Distribusi Pupuk</a></li>
			<li <?php if(@$SubPage=='LapTokoDistribusi'){echo 'class="active"';} ?>><a href="LapTokoDistribusi.php">Laporan Toko Distributor</a></li>
			<li <?php if(@$SubPage=='LapRekapRekapitulasi'){echo 'class="active"';} ?>><a href="LapRekapRekapitulasi.php">Laporan Rekapitulasi Penyaluran Pupuk</a></li>
		</ul>
	</li>
	<?php } ?>
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN' OR $JenisLogin == 'TIMBANGAN') { ?>
	<span class="heading" style="margin-top:10px;">Metrologi</span>
	
	<li <?php if(@$Page=='PengajuanOnline'){echo 'class="active"';} ?>><a href="PengajuanOnline.php"> <i class="fa fa-bell-o"></i>Pengajuan Tera Ulang</a></li>
	
	<li <?php if($Page=='TimbanganDinas'){echo 'class="active"';} ?>><a href="TimbanganUserDetil.php?user=<?php echo base64_encode('PRS-2019-0000000')?>"> <i class="fa fa-university"></i>Standart Ukur </a></li>
	
	<li <?php if($Page=='MasterUTTP'){echo 'class="active"';} ?>><a href="MasterUTTP.php"> <i class="fa fa-balance-scale"></i>Data Alat UTTP </a></li>
	<!--<li <?php if($Page=='VerifikasiUser'){echo 'class="active"';} ?>><a href="VefUser.php"> <i class="fa fa-check"></i>Verfikasi User </a></li>-->
	
	<li><a href="#TrMetrologi"  <?php if(@$Page=='TrMetrologi'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="fa fa-file-text-o"></i>Pelayanan Tera/Tera Ulang</a>
		<ul id="TrMetrologi" class="collapse list-unstyled <?php if(@$Page=='TrMetrologi'){echo 'show';}?>">
			<li <?php if(@$SubPage=='TrTerimaTimbangan'){echo 'class="active"';} ?>><a href="TrTerimaTimbangan.php">Penerimaan UTTP User</a></li>
			<li <?php if(@$SubPage=='TrSidangTera'){echo 'class="active"';} ?>><a href="TrSidangTera.php">Hasil Pelayanan</a></li>
			<li <?php if(@$SubPage=='TrTerimaPembayaran'){echo 'class="active"';} ?>><a href="TrTerimaPembayaran.php">Terima Pembayaran</a></li>
			<li <?php if(@$SubPage=='TrPengambilan'){echo 'class="active"';} ?>><a href="TrPengambilan.php">Pengambilan Alat UTTP</a></li>
			<li <?php if(@$SubPage=='TrTeraDilokasi'){echo 'class="active"';} ?>><a href="TrTeraDilokasi.php">Tera Dilokasi</a></li>
			<li <?php if(@$SubPage=='TrTeraStandart'){echo 'class="active"';} ?>><a href="TrTeraStandart.php">Tera Standar Ukur</a></li>
		</ul>
	</li>
	<li><a href="#Register"  <?php if(@$Page=='Register'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="fa fa-history"></i>Register</a>
		<ul id="Register" class="collapse list-unstyled <?php if(@$Page=='Register'){echo 'show';}?>">
			<li <?php if(@$SubPage=='TrRiwayatTera'){echo 'class="active"';} ?>><a href="TrRiwayatTera.php">Riwayat Tera UTTP</a></li>
			<li <?php if(@$SubPage=='LapTeraDinas'){echo 'class="active"';} ?>><a href="LapTeraDinas.php">Riwayat Tera Standart Ukur</a></li>
		</ul>
	</li>
	<li><a href="#Laporan"  <?php if(@$Page=='Laporan'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="icon-padnote"></i>Laporan</a>
		<ul id="Laporan" class="collapse list-unstyled <?php if(@$Page=='Laporan'){echo 'show';}?>">
			<li <?php if(@$SubPage=='LapPendapatan'){echo 'class="active"';} ?>><a href="LapPendapatan.php">Lap. UTTP Retribusi</a></li>
			<li <?php if(@$SubPage=='LapJumlahPerNama'){echo 'class="active"';} ?>><a href="LapJumlahPerNama.php">Lap. Per Jenis UTTP</a></li>
			<li <?php if(@$SubPage=='LapTotalPerDesa'){echo 'class="active"';} ?>><a href="LapTotalPerDesa.php">Lap. UTTP Per Desa</a></li>
			<li <?php if(@$SubPage=='LapTeraBerakhir'){echo 'class="active"';} ?>><a href="LapTeraBerakhir.php">Lap. Jatuh Tempo UTTP</a></li>
			<li <?php if(@$SubPage=='LapSebaranTimbangan'){echo 'class="active"';} ?>><a href="LapSebaranTimbangan.php">Lap. Sebaran UTTP</a></li>
			<li <?php if(@$SubPage=='LapPotensiDaerah'){echo 'class="active"';} ?>><a href="LapPotensiDaerah.php">Lap. Pontensi Pendapatan</a></li>
			<li <?php if(@$SubPage=='LapUTTPUser'){echo 'class="active"';} ?>><a href="LapUTTPUser.php">Lap. UTTP User</a></li>
		</ul>
	</li>
	<li <?php if(@$Page=='TargetTahunan'){echo 'class="active"';} ?>><a href="TargetTahunan.php"> <i class="fa fa-clock-o"></i>Target Tahunan (PAD)</a></li>
	<?php } ?>
	
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'RETRIBUSI PASAR') { ?>
	<span class="heading" style="margin-top:10px;">Retribusi Pasar</span>
	<li><a href="#MasterDataPasar"  <?php if(@$Page=='MasterDataPasar'){
			echo 'aria-expanded="true"';
		}else{ 
			echo 'aria-expanded="false"';
		} ?> data-toggle="collapse"> <i class="fa fa-list"></i>Master Data</a>
		<ul id="MasterDataPasar" class="collapse list-unstyled <?php if(@$Page=='MasterDataPasar'){echo 'show';}?>">
			<li <?php if(@$SubPage=='MasterLapakPasar'){echo 'class="active"';} ?>><a href="MasterLapakPasar.php">Data Lapak Pasar</a></li>
			<li <?php if(@$SubPage=='MasterLapakPerson'){echo 'class="active"';} ?>><a href="MasterLapakPersonBJ.php">Data Lapak User</a></li>
		</ul>
	</li>
	<li <?php if($Page=='TrRetribusiPasarBJ'){echo 'class="active"';} ?>><a href="TrRetribusiPasarBJ.php"> <i class="fa fa-sign-language"></i>Transaksi Retribusi Pasar </a></li>
	<li><a href="#LaporanRetribusi"  <?php if(@$Page=='LaporanRetribusi'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="icon-padnote"></i>Laporan</a>
		<ul id="LaporanRetribusi" class="collapse list-unstyled <?php if(@$Page=='LaporanRetribusi'){echo 'show';}?>">
			<li <?php if(@$SubPage=='LapTagihanRetribusi'){echo 'class="active"';} ?>><a href="LapTagihanRetribusi.php">Lap. Tagihan Retribusi Pasar</a></li>
			<li <?php if(@$SubPage=='LapPendapatanRetPasar'){echo 'class="active"';} ?>><a href="LapPendapatanRetPasar.php">Lap. Pendapatan Retribusi Pasar</a></li>
			<li <?php if(@$SubPage=='LapRekapPenPerPasar'){echo 'class="active"';} ?>><a href="LapRekapPenPerPasar.php">Lap. Pembayaran Retribusi Pasar</a></li>
			
		</ul>
	</li>
	<?php } ?>
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN') { ?>
	<span class="heading" style="margin-top:10px;">Laporan Keuangan</span>
	<li><a href="#Keuangan"  <?php if(@$Page=='Keuangan'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="fa fa-file-text"></i>Laporan Keuangan</a>
		<ul id="Keuangan" class="collapse list-unstyled <?php if(@$Page=='Keuangan'){echo 'show';}?>">
			<li <?php if(@$SubPage=='LapKeuanganMetrologi'){echo 'class="active"';} ?>><a href="LapKeuanganMetrologi.php">Laporan Rekap Keuangan Metrologi</a></li>
			<li <?php if(@$SubPage=='LapKeuanganERPAS'){echo 'class="active"';} ?>><a href="LapKeuanganERPAS.php">Laporan Rekap Keuangan Retribusi Pasar</a></li>
			
		</ul>
	</li>
	<?php } ?>
	<!--========================================================================================================================================================== -->
	<?php if ($JenisLogin == 'SUPER ADMIN') { ?>
	<span class="heading" style="margin-top:10px;">Pengaturan</span>
	<li><a href="#Security"  <?php if(@$Page=='Security'){echo 'aria-expanded="true"';}else{ echo 'aria-expanded="false"';} ?> data-toggle="collapse"> <i class="fa fa-lock"></i>Keamanan</a>
		<ul id="Security" class="collapse list-unstyled <?php if(@$Page=='Security'){echo 'show';}?>">
			<li <?php if(@$SubPage=='UserLogin'){echo 'class="active"';} ?>><a href="UserLogin.php">User Login</a></li>
			<li <?php if(@$SubPage=='AksesLevel'){echo 'class="active"';} ?>><a href="AksesLevel.php">Akses Level</a></li>
			<li <?php if(@$SubPage=='MasterDusun'){echo 'class="active"';} ?>><a href="MasterDusun.php">Data Dusun</a></li>
			<li <?php if(@$SubPage=='LogServer'){echo 'class="active"';} ?>><a href="LogServer.php">Log Server</a></li>
		</ul>
	</li>
	<?php } ?>
</ul>
</nav>