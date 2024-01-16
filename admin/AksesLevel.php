<?php
include 'akses.php';
$fitur_id = 6;
include '../library/lock-menu.php'; 
$Page = 'Security';
$SubPage = 'AksesLevel';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

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
		 th,td {
			text-align: center;
		}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "AksesLevel.php";
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
              <h2 class="no-margin-bottom">Akses Level</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Level ID</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>
							<?php } ?>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['aksi']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data User</h3>
							</div>
							<div class="card-body">	
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Level</th>
									  <th>Status</th>
									  <th>Hak Akses</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "AksesLevel.php?pagination=true";
										$sql =  "SELECT * FROM accesslevel ORDER BY LevelName ASC";
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
											<td class="text-left">
												<strong><?php echo $data ['LevelName']; ?></strong>
											</td>
											<td>
												<?php  if($data['LevelID'] !== '1'){
													if($data ['IsAktif']=='1'){
														echo '<a href="AksesLevel.php?id='.base64_encode($data['LevelID']).'&aksi='.base64_encode('NonAktif').'&nama='.$data["LevelName"].'" title="Klik untuk nonaktifkan level ini"><font color="green">Aktif</font></a>';
													}else{
														echo '<a href="AksesLevel.php?id='.base64_encode($data['LevelID']).'&aksi='.base64_encode('Aktif').'&nama='.$data["LevelName"].'" title="Klik untuk aktifkan level ini"><font color="red">Tidak Aktif</font></a>';
													} 
												} else {
													echo '<a href="#" title="Data Sistem"><i class="btn btn-primary btn-sm">Sistem</i></a>';
												} ?>
											</td>
											<td>
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="FiturLevel.php?id=<?php echo base64_encode($data['LevelID']);?>&nm=<?php echo base64_encode($data['LevelName']); ?>" title='Fitur Akses User'><i class='btn btn-warning btn-sm'><span class='fa fa-unlock-alt'></span> Set Akses</i></a> 
												<?php } ?>
											</td>
											<td width="100px" align="center">	
												<?php
												if($data['LevelID'] !== '1'){
													if ($cek_fitur['DeleteData'] =='1'){ 
														echo '<a href="AksesLevel.php?id='.base64_encode($data["LevelID"]).'&aksi='.base64_encode("Hapus").'&nama='.$data["LevelName"].'"  onclick="return confirmation()"  title="Edit"><i class="btn btn-danger btn-sm" ><span class="fa fa-trash"></span></i></a>';
													}
												} else {
													echo '<a href="#" title="Data Sistem"><i class="btn btn-info btn-sm"><span class="fa fa-gear"></span></i></a>';
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
												<td colspan="7" align="center">
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
						<div class="tab-pane fade <?php if(@$_GET['aksi']=='tampil'){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Tambah User</h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
									  <form method="post" action="">
										<div class="form-group-material">
										  <input type="text" name="Nama" class="form-control" placeholder="Nama Akses Level" required>
										</div>
										
										<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button>
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
	if(isset($_POST['Simpan'])){
		$AmbilNoUrut=mysqli_query($koneksi,"SELECT MAX(LevelID) as NoSaatIni FROM accesslevel");
		$Data=mysqli_fetch_assoc($AmbilNoUrut);
		$NoSekarang = $Data['NoSaatIni'];
		$Urutan = $NoSekarang+1;
		$LevelName  = $_POST['Nama'];
				
		$SimpanData = mysqli_query($koneksi,"INSERT INTO accesslevel (LevelID,LevelName,IsAktif)VALUES('$Urutan','$LevelName','1')");
		if($SimpanData){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Data Akses Level '.$LevelName, $login_id, $Urutan, 'Akses Level');
			echo '<script language="javascript">document.location="AksesLevel.php"; </script>';	
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "AksesLevel.php";
					  });
					  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$sql = mysqli_query($koneksi,"SELECT LevelID FROM userlogin WHERE LevelID = '".base64_decode($_GET['id'])."'");  
		$nums = mysqli_num_rows($sql);
		if($nums > 0){
			echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "AksesLevel.php";
						  });
						  </script>';
		} else {
			
			$query = mysqli_query($koneksi,"DELETE FROM accesslevel WHERE LevelID = '".base64_decode($_GET['id'])."'");
			if($query){
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Akses Level '.$_GET['nama'], $login_id, base64_decode($_GET['id']), 'Akses Level');
			
				echo '<script language="javascript">document.location="AksesLevel.php"; </script>';
			}else{
				echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "AksesLevel.php";
						  });
						  </script>';
			}
		}
	 }
	
	if(base64_decode(@$_GET['aksi'])=='Aktif'){
		$query = mysqli_query($koneksi,"UPDATE accesslevel SET IsAktif=b'1' WHERE LevelID = '".base64_decode($_GET['id'])."'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Set Aktif Access Level '.$_GET['nama'], $login_id, base64_decode($_GET['id']), 'Akses Level');
			echo '<script language="javascript">document.location="AksesLevel.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Aktifasi Akses Level Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "AksesLevel.php";
					  });
					  </script>';
		}
	 }
	 
	 if(base64_decode(@$_GET['aksi'])=='NonAktif'){
		$query = mysqli_query($koneksi,"UPDATE accesslevel SET IsAktif=b'0' WHERE LevelID = '".base64_decode($_GET['id'])."' ");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Set Non Aktif Access Level '.$_GET['nama'], $login_id, base64_decode($_GET['id']), 'Akses Level');
			echo '<script language="javascript">document.location="AksesLevel.php"; </script>';
			
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Non Aktifasi Akses Level Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "AksesLevel.php";
					  });
					  </script>';
		}
	 }
	?>
  </body>
</html>