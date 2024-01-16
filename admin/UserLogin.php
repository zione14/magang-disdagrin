<?php
include 'akses.php';
$fitur_id = 1;
include '../library/lock-menu.php'; 

$Page = 'Security';
$SubPage = 'UserLogin';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM userlogin WHERE UserName='".base64_decode($_GET['id'])."'");
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
				window.location = "LayananSurat.php";
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
              <h2 class="no-margin-bottom">User Login</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data User Login</span></a>&nbsp;
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
							  <h3 class="h4">Data User Login</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama</th>
									  <th>Alamat</th>
									  <th>Phone</th>
									  <th>Email</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$kosong=null;
										if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
											// jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)pakai ini
											$keyword=$_REQUEST['keyword'];
											$reload = "UserLogin.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM userlogin WHERE (ActualName LIKE '%$keyword%' OR  NamaPegawai LIKE '%$keyword%' ) AND IsAktif=b'1' ANDJenisLogin != 'PEDAGANG' AND JenisLogin != 'ADMIN WEB' ORDER BY NamaPegawai ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "UserLogin.php?pagination=true";
											$sql =  "SELECT * FROM userlogin WHERE IsAktif=b'1' AND JenisLogin != 'PEDAGANG' AND JenisLogin != 'ADMIN WEB' ORDER BY ActualName ASC";
											$result = mysqli_query($koneksi,$sql);
										}
										
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
												<strong><?php echo $data ['ActualName']; ?></strong>
											</td>
											<td>
												<?php echo $data ['Address']; ?>
											</td>
											<td align="center">
												<?php echo $data ['Phone']; ?>
											</td>
											<td>
												<?php echo $data ['Email']; ?>
											</td>
											<td width="100px" align="center">
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="UserLogin.php?id=<?php echo base64_encode($data['UserName']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a> 
												<?php } ?>
												<?php
												if($data['UserName'] === $login_id OR ($data['LevelID'] == '1' AND $data['UserName'] == 'admin')){
													echo '';
												} else {
													if ($cek_fitur['DeleteData'] =='1'){ 
														echo '<a href="UserLogin.php?id='.base64_encode($data['UserName']).'&aksi='.base64_encode('Hapus').'" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													}
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
								  <label class="col-sm-3 form-control-label">Nama Lengkap*</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="150" placeholder="Nama Lengkap" value="<?php echo @$RowData['NamaPegawai'];?>" name="NamaPegawai" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">NIP*</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="NIP" name="NIP" value="<?php echo @$RowData['NIP'];?>" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Jabatan</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="200" placeholder="Jabatan" value="<?php echo @$RowData['Jabatan'];?>" name="Jabatan">
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Alamat*</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="255" placeholder="Alamat" name="Alamat" value="<?php echo @$RowData['Address'];?>" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">No Telepon*</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="No Telepon" name="Telepon" value="<?php echo @$RowData['Phone'];?>" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">No HP</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="No Handphone" name="HP" value="<?php echo @$RowData['HPNo'];?>">
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Email*</label>
								  <div class="col-sm-9">
									<input type="email" class="form-control" maxlength="50" placeholder="Email" name="Email" value="<?php echo @$RowData['Email'];?>" required>
								  </div>
								</div>
								<div class="form-group row">
								 <label class="col-sm-3 form-control-label">Akses Level*</label>
								   <div class="col-sm-9">
									<select name="LevelID" class="form-control" required>	
										<?php
											$menu = mysqli_query($koneksi,"SELECT * FROM accesslevel WHERE IsAktif='1'");
											while($kode = mysqli_fetch_array($menu)){
												if($kode['LevelID']==@$RowData['LevelID']){
													echo "<option value=\"".$kode['LevelID']."\" selected >".$kode['LevelName']."</option>\n";
												}else{
													echo "<option value=\"".$kode['LevelID']."\" >".$kode['LevelName']."</option>\n";
												}
											}
										?>
									</select>
								   </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Username*</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="Username" name="Username" value="<?php echo @$RowData['UserName'];?>" required <?php echo @$Readonly;?>>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Jenis Login*</label>
								  <div class="col-sm-9">
									<select name="JenisLogin" id="JenisLogin" class="form-control">
										<option value="">Pilih Opsi</option>
										<option value="TIMBANGAN" <?php echo isset($RowData['JenisLogin']) && $RowData['JenisLogin'] === "TIMBANGAN" ?"selected" : ""; ?>>Bidang Metrologi</option>
										<option value="HARGA PASAR" <?php echo isset($RowData['JenisLogin']) && $RowData['JenisLogin'] === "HARGA PASAR" ?"selected" : ""; ?>>Bidang Harga Pasar</option>
										<option value="RETRIBUSI PASAR" <?php echo isset($RowData['JenisLogin']) && $RowData['JenisLogin'] === "RETRIBUSI PASAR" ?"selected" : ""; ?>>Bidang Retribusi Pasar</option>
										<option value="PUPUK SUBSIDI" <?php echo isset($RowData['JenisLogin']) && $RowData['JenisLogin'] === "PUPUK SUBSIDI" ?"selected" : ""; ?>>Bidang Pupuk Subsidi</option>
										<option value="SUPER ADMIN" <?php echo isset($RowData['JenisLogin']) && $RowData['JenisLogin'] === "SUPER ADMIN" ?"selected" : ""; ?>>Super Admin</option>
									</select>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Password*</label>
								  <div class="col-sm-9">
									<input type="password" class="form-control" maxlength="255" placeholder="Password" name="Password" required value="<?php echo base64_decode(@$RowData['UserPsw']);?>" >
								  </div>
								</div>
								<?php
									if(@$_GET['id']==null){
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
									}else{
										echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
									}
								?>
								<a href="UserLogin.php"><span class="btn btn-warning">Batalkan</span></a>
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
		@$UserName			= htmlspecialchars($_POST['Username']);
		@$ActualName		= htmlspecialchars($_POST['NamaPegawai']);
		@$Address		 	= htmlspecialchars($_POST['Alamat']);
		@$Phone			 	= htmlspecialchars($_POST['Telepon']);
		@$HPNo			 	= htmlspecialchars($_POST['HP']);
		@$Email			 	= htmlspecialchars($_POST['Email']);
		@$LevelID		 	= htmlspecialchars($_POST['LevelID']);
		@$NIP			 	= htmlspecialchars($_POST['NIP']);
		@$JenisLogin		= htmlspecialchars($_POST['JenisLogin']);
		@$Jabatan		 	= htmlspecialchars($_POST['Jabatan']);
		@$UserPsw		 	= base64_encode($_POST['Password']);
	
	
	if(isset($_POST['Simpan'])){
		//cek apakah username ada yang sama atau tidak
		$cekuser = mysqli_query($koneksi,"select UserName from userlogin where UserName='$UserName'");
		$numuser = mysqli_num_rows($cekuser);
		if($numuser == 1 ){
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " Username sudah ada, Silahkan ganti username Anda! ",
						type: "error"
					  },
					  function () {
						window.location.href = "UserLogin.php";
					  });
					  </script>';
		}else{
			$query = mysqli_query($koneksi,"INSERT into userlogin (UserName,UserPsw,ActualName,Address,Phone,Email,LevelID,NIP,IsAktif,Jabatan,NamaPegawai,HPNo, JenisLogin) 
			VALUES ('$UserName','$UserPsw','$ActualName','$Address','$Phone','$Email','$LevelID','$NIP',b'1','$Jabatan','$ActualName','$HPNo', '$JenisLogin')");
			if($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah User Login atas nama '.$ActualName, $login_id, '', '');
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="UserLogin.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "UserLogin.php";
					  });
					  </script>';
			}
		}
	}
	
	if(isset($_POST['SimpanEdit'])){
		//update data user login berdasarkan username yng di pilih
		$query = mysqli_query($koneksi,"UPDATE userlogin SET ActualName='$ActualName', NamaPegawai='$ActualName',Address='$Address',Phone='$Phone', JenisLogin='$JenisLogin',Email='$Email',UserPsw='$UserPsw',LevelID='$LevelID', Jabatan='$Jabatan', NIP='$NIP', HPNo='$HPNo' WHERE UserName='$UserName'");
		
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah data User Login atas nama '.$ActualName, $login_id, '', '');
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "UserLogin.php";
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
					window.location.href = "UserLogin.php";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$query = mysqli_query($koneksi,"UPDATE userlogin SET IsAktif=b'0' WHERE UserName='".base64_decode($_GET['id'])."'");
		if($query){
			InsertLog($koneksi, 'Hapus Data', 'Menghapus User Login atas nama '.$ActualName, $login_id, '', '');
			echo '<script language="javascript">document.location="UserLogin.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "UserLogin.php";
					  });
					  </script>';
		}
		
	}

	
	?>
  </body>
</html>