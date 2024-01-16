	<div id="sidebar" class="sidebar py-3">
        <div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">MAIN</div>
        <ul class="sidebar-menu list-unstyled">
			  <!-- ============================================== Index Data ======================================================================= -->
              <li class="sidebar-list-item"><a href="index.php"  <?php if($Page=='index'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="o-home-1 mr-3 text-gray"></i><span>Home</span></a></li>
			  <!-- ============================================== Konten Data ======================================================================= -->
			  <li class="sidebar-list-item"><a href="#" data-toggle="collapse" data-target="#Konten" aria-expanded="false" aria-controls="Konten" <?php if($Page=='Konten'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="o-wireframe-1 mr-3 text-gray"></i><span>Konten Web</span></a>
				<div id="Konten" class="collapse">
				  <ul class="sidebar-menu list-unstyled border-left border-primary border-thick">
				  	<li class="sidebar-list-item"><a href="Sambutan.php" class="sidebar-link text-muted pl-lg-5">Sambutan Kadis</a></li>
					<li class="sidebar-list-item"><a href="MstSlider.php" class="sidebar-link text-muted pl-lg-5">Master Slider</a></li>
					<li class="sidebar-list-item"><a href="MstBerita.php" class="sidebar-link text-muted pl-lg-5">Berita</a></li>
					<li class="sidebar-list-item"><a href="MstAgenda.php" class="sidebar-link text-muted pl-lg-5">Agenda</a></li>
					<li class="sidebar-list-item"><a href="MstArtikel.php" class="sidebar-link text-muted pl-lg-5">Artikel</a></li>
					<li class="sidebar-list-item"><a href="Komentar.php" class="sidebar-link text-muted pl-lg-5">Komentar</a></li>
					<li class="sidebar-list-item"><a href="MstFoto.php" class="sidebar-link text-muted pl-lg-5">Galeri Foto</a></li>
					<li class="sidebar-list-item"><a href="MstVideo.php" class="sidebar-link text-muted pl-lg-5">Galeri Video</a></li>
					<li class="sidebar-list-item"><a href="StrukturOrg.php" class="sidebar-link text-muted pl-lg-5">Struktur Org.</a></li>
					<li class="sidebar-list-item"><a href="Unduhan.php" class="sidebar-link text-muted pl-lg-5">File Unduhan</a></li>
					<li class="sidebar-list-item"><a href="MstLink.php" class="sidebar-link text-muted pl-lg-5">Link Terkait</a></li>
					<li class="sidebar-list-item"><a href="MstSosmed.php" class="sidebar-link text-muted pl-lg-5">Media Sosial</a></li>
				  </ul>
				</div>
			  </li>
			  <!-- ==================================================== Pesan ================================================================== -->
			  <li class="sidebar-list-item"><a href="Sakip.php"  <?php if($Page=='Sakip'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="fas fa-server mr-3 text-gray"></i><span>Dokumen SAKIP</span></a></li>
			  <!-- ==================================================== Pesan ================================================================== -->
			  <li class="sidebar-list-item"><a href="Pesan.php"  <?php if($Page=='Pesan'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="fas fa-envelope mr-3 text-gray"></i><span>Pesan Masuk</span></a></li>
			  <!-- ==================================================== Setting Data ================================================================== -->
			   <li class="sidebar-list-item"><a href="SistemSetting.php"  <?php if($Page=='Setting'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="fas fa-cog mr-3 text-gray"></i><span>Sistem Setting</span></a></li>
			  <!--<li class="sidebar-list-item"><a href="#" data-toggle="collapse" data-target="#Setting" aria-expanded="false" aria-controls="Setting" <?php if($Page=='Setting'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="o-settings-window-1 mr-3 text-gray"></i><span>Setting Data</span></a>
				<div id="Setting" class="collapse">
				  <ul class="sidebar-menu list-unstyled border-left border-primary border-thick">
					<li class="sidebar-list-item"><a href="MstSlider.php" class="sidebar-link text-muted pl-lg-5">Master Slider</a></li>
					<li class="sidebar-list-item"><a href="MstBerita.php" class="sidebar-link text-muted pl-lg-5">Berita</a></li>
					<li class="sidebar-list-item"><a href="MstArtikel" class="sidebar-link text-muted pl-lg-5">Artikel</a></li>
					<li class="sidebar-list-item"><a href="MstPengumuman" class="sidebar-link text-muted pl-lg-5">Pengumuman</a></li>
					<li class="sidebar-list-item"><a href="MstFoto" class="sidebar-link text-muted pl-lg-5">Galeri Foto</a></li>
					<li class="sidebar-list-item"><a href="MstVideo" class="sidebar-link text-muted pl-lg-5">Galeri Video</a></li>
				  </ul>
				</div>
			  </li>-->
			  <!-- <li class="sidebar-list-item"><a href="UserLogin.php"  <?php if($Page=='User'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="fas fa-desktop mr-3 text-gray"></i><span>Setting Slider</span></a></li> -->
			  <!--  <li class="sidebar-list-item"><a href="#" data-toggle="collapse" data-target="#Sistem" aria-expanded="false" aria-controls="Sistem" <?php if($Page=='Sistem'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="fas fa-desktop mr-3 text-gray"></i><span>Pengaturan</span></a>
				<div id="Sistem" class="collapse">
				  <ul class="sidebar-menu list-unstyled border-left border-primary border-thick">
					<li class="sidebar-list-item"><a href="SistemSlider.php" class="sidebar-link text-muted pl-lg-5">Setting Slider</a></li>
					
					<li class="sidebar-list-item"><a href="MstBerita.php" class="sidebar-link text-muted pl-lg-5">Berita</a></li>
					<li class="sidebar-list-item"><a href="MstAgenda.php" class="sidebar-link text-muted pl-lg-5">Agenda</a></li>
					<li class="sidebar-list-item"><a href="MstArtikel.php" class="sidebar-link text-muted pl-lg-5">Artikel</a></li>
					<li class="sidebar-list-item"><a href="MstFoto.php" class="sidebar-link text-muted pl-lg-5">Galeri Foto</a></li>
					<li class="sidebar-list-item"><a href="MstVideo.php" class="sidebar-link text-muted pl-lg-5">Galeri Video</a></li>
					<li class="sidebar-list-item"><a href="StrukturOrg.php" class="sidebar-link text-muted pl-lg-5">Struktur Org.</a></li>
				  </ul>
				</div>
			  </li> -->
			  <!-- ==================================================== User Login ================================================================== -->
			  <li class="sidebar-list-item"><a href="UserLogin.php"  <?php if($Page=='User'){echo 'class="sidebar-link text-muted active"';} ?> class="sidebar-link text-muted" ><i class="o-user-1 mr-3 text-gray"></i><span>User Login</span></a></li>
			  
        </ul>
        <!--<div class="text-gray-400 text-uppercase px-3 px-lg-4 py-4 font-weight-bold small headings-font-family">EXTRAS</div>
        <ul class="sidebar-menu list-unstyled">
              <li class="sidebar-list-item"><a href="#" class="sidebar-link text-muted"><i class="o-database-1 mr-3 text-gray"></i><span>Demo</span></a></li>
              <li class="sidebar-list-item"><a href="#" class="sidebar-link text-muted"><i class="o-imac-screen-1 mr-3 text-gray"></i><span>Demo</span></a></li>
              <li class="sidebar-list-item"><a href="#" class="sidebar-link text-muted"><i class="o-paperwork-1 mr-3 text-gray"></i><span>Demo</span></a></li>
              <li class="sidebar-list-item"><a href="#" class="sidebar-link text-muted"><i class="o-wireframe-1 mr-3 text-gray"></i><span>Demo</span></a></li>
        </ul>-->
    </div>