<?php
include 'akses.php';
@$fitur_id = 2;
include '../library/lock-menu.php';
$Page = 'MasterUTTP';


// Tanggal dan Tahun
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
//getdata
@$KodeTimbangan = htmlspecialchars(base64_decode($_GET['kd']));
@$NamaTimbangan = htmlspecialchars(base64_decode($_GET['nm']));

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Data UTTP';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM detilukuran WHERE KodeUkuran='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	@$res = explode("#", $RowData['JenisPerson']);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="../komponen/css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <?php include 'style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	
	<style>
	th {
		text-align: center;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "TimbanganUserDetil.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php include 'header.php';?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Setting Ukuran & Retrbusi <?php echo $NamaTimbangan; ?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>&nbsp;
							<?php } ?>
						</li>
						<li>
							<a href="MasterUTTP.php"><span class="btn btn-primary">Kembali</span></a>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Detil Data <?php echo $NamaTimbangan; ?></h3>
							</div>
							<div class="card-body">	
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Timbangan..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									 <th>No</th>
								     <th>Nama Timbangan</th>
								     <th>Ukuran</th>
								     <th>Nilai Retribusi</th>
								     <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "MasterUkuran.php?pagination=true&view=3";
										$sql =  "SELECT a.NamaTimbangan,b.KodeKelas,a.KodeTimbangan,b.NamaKelas,c.RetribusiDikantor,c.RetribusiDiLokasi,c.KodeUkuran,c.NamaUkuran FROM msttimbangan a join kelas b on a.KodeTimbangan=b.KodeTimbangan join detilukuran c on (b.KodeKelas,b.KodeTimbangan)=(c.KodeKelas,c.KodeTimbangan) where a.KodeTimbangan='$KodeTimbangan' ";
										
										// if(@$_SESSION['Cari']!=null){
											// $sql .= " AND ( a.NamaTimbangan LIKE '%".$_SESSION['Cari']."%'  OR b.NamaKelas LIKE '%".$_SESSION['Cari']."%' OR c.NamaUkuran LIKE '%".$_SESSION['Cari']."%'  ) ";
										// }
										
										$sql .=" ORDER BY a.NamaTimbangan ASC";
										$result = mysqli_query($koneksi,$sql);
							
										//pagination config start
										$rpp = 20; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										<?php
										if($tcount == null OR $tcount === 0){
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><br><h5>Tidak Ada Data</h5><br></td></tr>';
										} else {
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php echo $data ['NamaTimbangan'].'<br>'.$data['NamaKelas'];?>
											</td>
											<td>
												<?php echo $data ['NamaUkuran'];?>
											</td>
											<td>
												<?php echo 'Retribusi Dikantor : Rp '.number_format($data ['RetribusiDikantor']).'<br> Retribusi Dilokasi : Rp '.number_format($data['RetribusiDiLokasi']);?>
											</td>
											<td width="100px" align="center">
												<?php
													if ($cek_fitur['EditData'] =='1'){ 
															echo '<a href="MasterUkuran.php?id='.base64_encode($data['KodeUkuran']).'&kd='.base64_encode($data['KodeTimbangan']).'&kl='.base64_encode($data['KodeKelas']).'&nm='.base64_encode($data['NamaTimbangan']).'" title="Edit"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
														}
													if ($cek_fitur['DeleteData'] =='1'){ 
														echo ' <a href="MasterUkuran.php?id='.base64_encode($data['KodeUkuran']).'&cd='.base64_encode($data['KodeTimbangan']).'&kl='.base64_encode($data['KodeKelas']).'&nm='.$data['NamaTimbangan'].'&aksi='.base64_encode('Hapus').'" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													}
												?>
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										}
										?>
									</tbody>
								</table>
								<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
							  </div>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Tambah Data UTTP</h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									   <form method="post" action="">
										<div class="card-body" style="background-color: #fafafa;">	
											<label>Nama UTTP</label>
											<div class="form-group ">	
												<input type="hidden" name="view" value="3">
												<select name="KodeTimbangan" id="KodeTimbangan" class="form-control" <?php if ($KodeTimbangan != null) { echo 'disabled'; }else{ echo 'required'; } ?> >
													<option value="" disabled selected> Pilih UTTP </option>';
													<?php
														echo "<option value=''>--- Timbangan ---</option>";
														$menu = mysqli_query($koneksi,"SELECT KodeTimbangan,NamaTimbangan from msttimbangan where KodeTimbangan='$KodeTimbangan' ORDER by NamaTimbangan ASC");
														while($kode = mysqli_fetch_array($menu)){
															if($kode['KodeTimbangan'] === $KodeTimbangan){
																echo "<option value=\"".$kode['KodeTimbangan']."\" selected='selected'>".$kode['NamaTimbangan']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
															}
														}
													?>
												</select>
											</div>
											<div class="form-group-material">
											<label>Nama Kelas</label>
											<select id="KodeKelas" class="form-control" name="KodeKelas" <?php if ($RowData['KodeKelas'] != null) { echo 'disabled'; }else{ echo 'required'; } ?>>
												<?php
													$menu = mysqli_query($koneksi,"SELECT * FROM kelas where KodeTimbangan='$KodeTimbangan' ORDER BY NamaKelas");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeKelas'] !== NULL){
															if($kode['KodeKelas'] === $RowData['KodeKelas']){
																echo "<option value=\"".$kode['KodeKelas']."\" selected='selected'>".$kode['NamaKelas']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeKelas']."\" >".$kode['NamaKelas']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeKelas']."\" >".$kode['NamaKelas']."</option>\n";
														}
													}
												?>
											
											</select>
										</div>
											<div class="form-group">
												<label>Nama Ukuran</label>
												<input type="text" name="NamaUkuran" class="form-control" placeholder="Nama Ukuran" value="<?php echo @$RowData['NamaUkuran']; ?>">
											</div>
											<div class="form-group">
											  <label>Retribusi Di Kantor</label>
											  <div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
												<input type="number" min="0" class="form-control" maxlength="50" placeholder="Nilai Retribusi Di Kantor" name="RetribusiDikantor" value="<?php
                                                echo isset($RowData['RetribusiDikantor']) ? $RowData['RetribusiDikantor'] : '0';?>" required>
												<div class="input-group-append"><span class="input-group-text">.00</span></div>
											  </div>
											</div>
											<div class="form-group">
											  <label>Retribusi Di Lokasi</label>
											  <div class="input-group">
												<div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
												<input type="number" class="form-control" maxlength="50" placeholder="Nilai Retribusi Di Lokasi" min="0" name="RetribusiDiLokasi" value="<?php
                                                echo isset($RowData['RetribusiDiLokasi']) ? $RowData['RetribusiDiLokasi'] : '0';?>" required>
												<div class="input-group-append"><span class="input-group-text">.00</span></div>
											  </div>
											</div>
											<div class="form-group">
												<label>Nilai Tambah</label>
												<input type="number"  min="0" name="NilaiTambah" class="form-control" placeholder="Nilai Tambah" value="<?php
                                                echo isset($RowData['NilaiTambah']) ? $RowData['NilaiTambah'] : '0';?>" >
											</div>
											 <div class="form-row">
												<div class="form-group col-md-6">
												  <label>Penambahan  Retribusi Dikantor (Rp)</label>
												  <input type="number" name="RetPenambahanDikantor" placeholder="Ret Penambahan Dikantor" min="0" value="<?php
                                                echo isset($RowData['RetPenambahanDikantor']) ? $RowData['RetPenambahanDikantor'] : '0';?>"  class="form-control">
												</div>
												<div class="form-group col-md-6">
												  <label>Penambahan Retribusi Dilokasi (Rp)</label>
												  <input type="number" name="RetPenambahanDiLokasi" placeholder="Ret Penambahan DiLokasi" min="0" value="<?php
                                                echo isset($RowData['RetPenambahanDiLokasi']) ? $RowData['RetPenambahanDiLokasi'] : '0';?>"  class="form-control">
												</div>
											 </div>

											<?php
												if(@$RowData['KodeUkuran'] != null) {
													echo '<input type="hidden" name="KodeUTTP" value="'.htmlspecialchars(base64_decode($_GET['kd'])).'">';
													echo '<input type="hidden" name="KodeKelas" value="'.htmlspecialchars(base64_decode($_GET['kl'])).'">';
													echo '<input type="hidden" name="KodeUkuran" value="'.htmlspecialchars(base64_decode($_GET['id'])).'">';
													echo '<input type="hidden" name="NamaUTTP" value="'.htmlspecialchars($_GET['nm']).'">';
													echo '<button class="btn btn-success" type="submit" name="SimpanEdit">Simpan</button>';
													echo '&nbsp;<a href="MasterUkuran.php?kd='.@$_GET['kd'].'&nm='.@$_GET['nm'].'"><span class="btn btn-warning">Kembali</span></a>';
												}else{
													if ($cek_fitur['AddData'] =='1'){ 
														echo '<input type="hidden" name="KodeUTTP" value="'.$KodeTimbangan.'">';
														echo '<input type="hidden" name="NamaUTTP" value="'.htmlspecialchars($_GET['nm']).'">';
														echo '<button class="btn btn-info" type="submit" name="Simpan">Simpan</button>&nbsp;';
														echo '<a href="MasterUkuran.php?kd='.$_GET['kd'].'&nm='.$_GET['nm'].'"><span class="btn btn-warning">Kembali</span></a>';
													}
												}
											?>
										</div>
									</form>
									</div>
								</div>
							</div>
						</div>
					</div>
                  </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>	
	
			
	<?php

		@$KodeTimbangan 	=htmlspecialchars($_POST['KodeTimbangan']);
		@$KodeUTTP		 	=htmlspecialchars($_POST['KodeUTTP']);
		@$NamaUTTP		 	=htmlspecialchars($_POST['NamaUTTP']);
		@$KodeKelas		 	=htmlspecialchars($_POST['KodeKelas']);
		@$NamaKelas		 	=htmlspecialchars($_POST['NamaKelas']);
		@$NilaiTambah	 	=htmlspecialchars($_POST['NilaiTambah']);
		@$NamaUkuran	 	=htmlspecialchars($_POST['NamaUkuran']);
		@$KodeUkuran	 	=htmlspecialchars($_POST['KodeUkuran']);
		@$RetribusiDikantor	=htmlspecialchars($_POST['RetribusiDikantor']);
		@$RetribusiDiLokasi	=htmlspecialchars($_POST['RetribusiDiLokasi']);
		@$RetPenambahanDikantor	 	=htmlspecialchars($_POST['RetPenambahanDikantor']);
		@$RetPenambahanDiLokasi	 	=htmlspecialchars($_POST['RetPenambahanDiLokasi']);
		
	if(isset($_POST['Simpan'])){
		$sql1 = mysqli_query($koneksi,"SELECT RIGHT(KodeUkuran,7) AS kode1 FROM detilukuran ORDER BY KodeUkuran DESC LIMIT 1");  
		$nums1 = mysqli_num_rows($sql1);
		if($nums1 <> 0){
			 $data1 = mysqli_fetch_array($sql1);
			 $kode1 = $data1['kode1'] + 1;
		}else{
			 $kode1 = 1;
		}
		//mulai bikin kode
		 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
		 $kodeukur = "UKR-".$bikin_kode1;
		
		
		$query1 = mysqli_query($koneksi,"INSERT into detilukuran(KodeTimbangan,KodeKelas,KodeUkuran,NamaUkuran,RetribusiDikantor,RetribusiDiLokasi,NilaiBawah,NilaiTambah, 	RetPenambahanDikantor,RetPenambahanDiLokasi) VALUES ('$KodeUTTP','$KodeKelas','$kodeukur','$NamaUkuran','$RetribusiDikantor','$RetribusiDiLokasi','$NilaiTambah','$NilaiTambah','$RetPenambahanDikantor','$RetPenambahanDiLokasi')");
				
		if ($query1){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Master Data Ukuran Timbangan dengan nama '.$NamaUkuran, $login_id, $kodeukur, 'Master Data');
			echo '<script language="javascript">document.location="MasterUkuran.php?kd='.base64_encode($KodeUTTP).'&nm='.$NamaUTTP.'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Simpan Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterUkuran.php?kd='.base64_encode($KodeUTTP).'&nm='.$NamaUTTP.'";
				  });
				  </script>';
		}
	}
	
	if(isset($_POST['SimpanEdit'])){
		$query = mysqli_query($koneksi,"UPDATE detilukuran SET NamaUkuran='$NamaUkuran', RetribusiDikantor='$RetribusiDikantor', RetribusiDiLokasi='$RetribusiDiLokasi', NilaiBawah='$NilaiTambah', NilaiTambah='$NilaiTambah', RetPenambahanDikantor='$RetPenambahanDikantor', RetPenambahanDiLokasi='$RetPenambahanDiLokasi' WHERE KodeTimbangan='$KodeUTTP' and KodeKelas='$KodeKelas' and KodeUkuran='$KodeUkuran' ");
		
			if($query){
				InsertLog($koneksi, 'Edit Data', 'Mengubah Master Data Ukuran Timbangan dengan nama '.$NamaUkuran, $login_id, $KodeUkuran, 'Master Data');
				echo $KodeTimbangan;
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MasterUkuran.php?kd='.base64_encode($KodeUTTP).'&nm='.$NamaUTTP.'";
					  });
					  </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterUkuran.php?kd='.base64_encode($KodeUTTP).'&nm='.$NamaUTTP.'";
					  });
					  </script>';
			}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$query = mysqli_query($koneksi,"DELETE FROM detilukuran WHERE KodeUkuran='".htmlspecialchars(base64_decode($_GET['id']))."' AND KodeKelas='".htmlspecialchars(base64_decode($_GET['kl']))."' AND KodeTimbangan='".htmlspecialchars(base64_decode($_GET['cd']))."'");
			if($query){
				
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Master Data Ukuran Timbangan dengan nama '.@$_GET['nm'], $login_id, base64_decode(@$_GET['id']), 'Master Data');
				echo '<script language="javascript">document.location="MasterUkuran.php?kd='.$_GET['cd'].'&nm='.base64_encode($_GET['nm']).'"; </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data telah digunakan di berbagai transaksi! ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterUkuran.php?kd='.$_GET['cd'].'&nm='.base64_encode($_GET['nm']).'";
					  });
					  </script>';
			}
	}
	
		
	?>
  </body>
</html>