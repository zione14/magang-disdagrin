<?php
include 'akses.php';
$fitur_id = 29;
include '../library/lock-menu.php'; 

$Page = 'MasterDataPasar';
$SubPage = 'MasterLapakPasar';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM lapakpasar WHERE IDLapak='".htmlspecialchars(base64_decode($_GET['id']))."' and KodePasar='".htmlspecialchars(base64_decode($_GET['psr']))."'");
	$RowData = mysqli_fetch_assoc($Edit);
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
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
		<style>
		 th {
			text-align: center;
		}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MasterLapakPasar.php";
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
              <h2 class="no-margin-bottom">Master Lapak Pasar</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Lapak</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>
							<?php } ?>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Lapak Pasar</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-8 offset-lg-4">
									<form method="post" action="">
										<div class="form-group input-group">
											<select name="KodePasar" class="form-control">	
												<option value="">Semua Pasar</option>
												<?php
													$menu = mysqli_query($koneksi,"SELECT * FROM mstpasar");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodePasar']==@$_REQUEST['KodePasar']){
															echo "<option value=\"".$kode['KodePasar']."\" selected >".$kode['NamaPasar']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodePasar']."\" >".$kode['NamaPasar']."</option>\n";
														}
													}
												?>
											</select>&nbsp;						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Blok atau Nomor Lapak..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Pasar</th>
									  <th>Nama Kepala Pasar</th>
									  <th>Retribusi</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$KodePasar=$_REQUEST['KodePasar'];
										@$keyword=$_REQUEST['keyword'];
										$reload = "MasterLapakPasar.php?pagination=true&keyword=$keyword&KodePasar=$KodePasar";
										$sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,a.KodePasar 
										FROM lapakpasar a 
										JOIN mstpasar b on a.KodePasar=b.KodePasar 
										WHERE 1 ";		

										if(@$_REQUEST['KodePasar']!=null){
											$sql .= " AND a.KodePasar = '$KodePasar'  ";
										}
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND (a.NomorLapak LIKE '%".$keyword."%' OR b.NamaPasar LIKE '%".$keyword."%' OR a.BlokLapak LIKE '%".$keyword."%')  ";
										}
										
										$sql .=" ORDER BY b.NamaPasar ASC";									
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										$rpp = 20; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										<?php
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo $data ['NamaPasar']." </strong>".$data ['BlokLapak']." ".$data ['NomorLapak']; ?>
											</td>
											<td>
												<?php echo $data ['NamaKepalaPasar']; ?>
												
											</td>
											<td align="center">
												<?php echo number_format($data ['Retribusi']); ?>
											</td>
											
											<td width="130px" align="center">
												<?php 
													if ($cek_fitur['EditData'] =='1'){ 
														echo '<a href="MasterLapakPasar-dokumen.php?k='.base64_encode($data['KodePasar']).'&l='.base64_encode($data['IDLapak']).'" title="Dokumen Pasar"><i class="btn btn-success btn-sm"><span class="fa fa-file"></span></i></a> ';			
												    } ?>
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="MasterLapakPasar.php?id=<?php echo base64_encode($data['IDLapak']);?>&psr=<?php echo base64_encode($data['KodePasar']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a> 
												<?php } ?>
												<?php
													if ($cek_fitur['DeleteData'] =='1'){ 
														echo '<a href="MasterLapakPasar.php?tr='.base64_encode($data['IDLapak']).'&aksi='.base64_encode('Hapus').'&psr='.base64_encode($data['KodePasar']).'" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													}
												?>
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										if($tcount==0){
											echo '
											<tr>
												<td colspan="6" align="center">
													<strong>Tidak ada data</strong>
												</td>
											</tr>
											';
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
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<form class="form-horizontal" method="post" action="">
								<div class="form-group row">
								 <label class="col-sm-3 form-control-label">Nama Pasar*</label>
								   <div class="col-sm-9">
									<select name="KodePasar" class="form-control" <?php if(@$_GET['id']!=null) { echo 'disabled'; }else{ echo 'required';}?> >	
										<?php
											$menu = mysqli_query($koneksi,"SELECT * FROM mstpasar");
											while($kode = mysqli_fetch_array($menu)){
												if($kode['KodePasar']==@$RowData['KodePasar']){
													echo "<option value=\"".$kode['KodePasar']."\" selected >".$kode['NamaPasar']."</option>\n";
												}else{
													echo "<option value=\"".$kode['KodePasar']."\" >".$kode['NamaPasar']."</option>\n";
												}
											}
										?>
									</select>
								   </div>
								</div>								
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Blok Lapak</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="20" placeholder="Blok Lapak" value="<?php echo @$RowData['BlokLapak'];?>" name="BlokLapak" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Nomor Lapak</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="10" placeholder="Nomor Lapak" name="NomorLapak" value="<?php echo @$RowData['NomorLapak'];?>" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Retribusi</label>
								  <div class="col-sm-9">
									<input type="number" class="form-control" placeholder="Retribusi" value="<?php echo @$RowData['Retribusi'];?>" name="Retribusi">
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Keterangan*</label>
								  <div class="col-sm-9">
									<textarea type="text" name="Keterangan" class="form-control" rows="2" placeholder="Keterangan"><?php echo @$RowData['Keterangan'];?></textarea>
								  </div>
								</div>
								
								<?php
									if(@$_GET['id']==null){
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
									}else{
										echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
										echo ' <input type="hidden" name="IDLapak" value="'.$RowData['IDLapak'].'">';
										echo ' <input type="hidden" name="Kode" value="'.$RowData['KodePasar'].'">';
									}
								?>
								<a href="MasterLapakPasar.php"><span class="btn btn-warning">Batalkan</span></a>
							  </form>
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
		@$KodePasar			= htmlspecialchars($_POST['KodePasar']);
		@$BlokLapak			= htmlspecialchars($_POST['BlokLapak']);
		@$NomorLapak	 	= htmlspecialchars($_POST['NomorLapak']);
		@$Retribusi		 	= htmlspecialchars($_POST['Retribusi']);
		@$Keterangan	 	= htmlspecialchars($_POST['Keterangan']);
		@$IDLapak		 	= htmlspecialchars($_POST['IDLapak']);
		@$Kode		 	    = htmlspecialchars($_POST['Kode']);
	
	
	if(isset($_POST['Simpan'])){
			//kode jabatan
			$sql = mysqli_query($koneksi,'SELECT RIGHT(IDLapak,10) AS kode FROM lapakpasar ORDER BY IDLapak DESC LIMIT 1');  
			$nums = mysqli_num_rows($sql);
			 
			if($nums <> 0){
				$data = mysqli_fetch_array($sql);
				$kode = $data['kode'] + 1;
			}else{
				$kode = 1;
			}
			 
			//mulai bikin kode
			 $bikin_kode = str_pad($kode, 10, "0", STR_PAD_LEFT);
			 $kode_jadi = "LPK-".$bikin_kode;
			 
			$query = mysqli_query($koneksi,"INSERT lapakpasar (KodePasar,BlokLapak,NomorLapak,Retribusi,Keterangan,IDLapak) 
			VALUES ('$KodePasar','$BlokLapak','$NomorLapak','$Retribusi','$Keterangan','$kode_jadi')");
			if($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Data Lapak Pasar '.$BlokLapak, $login_id, $kode_jadi, 'Master Lapak Pasar');
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="MasterLapakPasar.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterLapakPasar.php";
					  });
					  </script>';
			}
		
	}
	
	if(isset($_POST['SimpanEdit'])){
		//update data user login berdasarkan username yng di pilih
		$query = mysqli_query($koneksi,"UPDATE lapakpasar SET BlokLapak='$BlokLapak',NomorLapak='$NomorLapak',Retribusi='$Retribusi', Keterangan='$Keterangan' WHERE IDLapak='$IDLapak' and  KodePasar='$Kode'");
		
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Data Lapak Pasar '.$BlokLapak, $login_id, $IDLapak, 'Master Lapak Pasar');
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "MasterLapakPasar.php";
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
					window.location.href = "MasterLapakPasar.php";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$QueryCekData = @mysqli_query($koneksi, "SELECT IDLapak FROM lapakperson where IDLapak='".htmlspecialchars(base64_decode($_GET['tr']))."' and KodePasar='".htmlspecialchars(base64_decode($_GET['psr']))."'"); 
		$numCek = @mysqli_num_rows($QueryCekData); 
		// echo $numCek;
		if($numCek > 0){
			echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "MasterLapakPasar.php";
						  });
						  </script>';
		}else{
			$query = mysqli_query($koneksi,"delete from lapakpasar WHERE IDLapak='".htmlspecialchars(base64_decode($_GET['tr']))."' and KodePasar='".htmlspecialchars(base64_decode($_GET['psr']))."'");
			if($query){
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Master Lapak Pasar', $login_id, base64_decode($_GET['tr']), 'Master Lapak Pasar');
				echo '<script language="javascript">document.location="MasterLapakPasar.php"; </script>';
			}else{
				echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "MasterLapakPasar.php";
						  });
						  </script>';
			}
		}
	}

	
	?>
  </body>
</html>