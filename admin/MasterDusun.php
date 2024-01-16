<?php
include 'akses.php';
@$fitur_id = 34;
include '../library/lock-menu.php';
$Page = 'Security';
$SubPage = 'MasterDusun';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM mstdusun WHERE KodeDusun='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	
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
				window.location = "MasterDusun.php";
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
              <h2 class="no-margin-bottom">Master Dusun </h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Dusun</span></a>&nbsp;
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
							  <h3 class="h4">Data Dusun</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-6 offset-lg-6">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-warning" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Dusun</th>
									  <th>Desa</th>
									  <th>Kecamatan</th>
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
											$reload = "MasterDusun.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT a.NamaDusun,a.KodeDusun,b.NamaDesa,c.NamaKecamatan FROM mstdusun a join mstdesa b on (a.KodeDesa,a.KodeKec)=(b.KodeDesa,b.KodeKec) join mstkec c on a.KodeKec=c.KodeKec WHERE a.NamaDusun LIKE '%$keyword%' ORDER BY a.NamaDusun,b.NamaDesa,c.NamaKecamatan ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MasterDusun.php?pagination=true";
											$sql =  "SELECT a.NamaDusun,a.KodeDusun,b.NamaDesa,c.NamaKecamatan FROM mstdusun a join mstdesa b on (a.KodeDesa,a.KodeKec)=(b.KodeDesa,b.KodeKec) join mstkec c on a.KodeKec=c.KodeKec ORDER BY a.NamaDusun,b.NamaDesa,c.NamaKecamatan ASC";
											@$result = mysqli_query($koneksi,$sql);
										}
										
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
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo $data ['NamaDusun']; ?></strong><br>
												
											<td>
												<?php echo $data ['NamaDesa']; ?>
											</td>
											<td align="center">
												<?php echo $data ['NamaKecamatan']; ?>
											</td>
											<td width="120px" align="center">
													<!-- Tombol Edit Data -->
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="MasterDusun.php?id=<?php echo base64_encode($data['KodeDusun']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a>
												<?php } ?>
												<!-- Tombol Hapus Data 												
													<a href="MasterUser.php?id=<?php echo base64_encode($data['IDPerson']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($data['NamaPerson']);  ?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>-->
												
												
												
												


											</td>
										</tr>
										<?php
											$i++; 
											$count++;
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
								<div class="row">
								  <div class="col-lg-6">
									  <form method="post" action="">
										<div class="form-group-material">
											<label>Nama Kecamatan</label>
											<select id="KodeKec" name="KodeKec" class="form-control" required>	
												<?php
													echo "<option value=''>--- Kecamatan ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeKec'] !== NULL){
															if($kode['KodeKec'] === $RowData['KodeKec']){
																echo "<option value=\"".$kode['KodeKec']."\" selected='selected'>".$kode['NamaKecamatan']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
														}
														
													}
												?>
											</select>
										</div>
										<div class="form-group-material">
											<label>Nama Desa</label>
											<select id="KodeDesa" class="form-control" name="KodeDesa" required>
											
												<?php
													echo "<option value=''>--- Desa ---</option>";
													$menu = mysqli_query($koneksi,"SELECT * FROM mstdesa where KodeKec='".$RowData['KodeKec']."' ORDER BY NamaDesa");
													while($kode = mysqli_fetch_array($menu)){
														if($RowData['KodeDesa'] !== NULL){
															if($kode['KodeDesa'] === $RowData['KodeDesa']){
																echo "<option value=\"".$kode['KodeDesa']."\" selected='selected'>".$kode['NamaDesa']."</option>\n";
															}else{
																echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
															}
														}else{
															echo "<option value=\"".$kode['KodeDesa']."\" >".$kode['NamaDesa']."</option>\n";
														}
													}
												?>
											
											</select>
										</div>
										<div class="form-group-material">
										  <label>Nama Dusun</label>
										  <input type="text" name="NamaDusun" class="form-control" placeholder="Nama Dusun" value="<?php echo @$RowData['NamaDusun'];?>" maxlength="150" required>
										</div>
										<br><div class="text-left">
										<?php
										if(@$_GET['id']==null){
											echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
											echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
											echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button>';
										}else{
											echo '<input type="hidden" name="KodeDusun" value="'.$RowData['KodeDusun'].'"> ';
											echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
											echo '<a href="MasterDusun.php"><span class="btn btn-warning">Batalkan</span></a>';
										}
										?>
										</div>
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
	
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>

	  $("#KodeKec").change(function(){
		var KodeKec = $("#KodeKec").val();
		$.ajax({
			url: "../library/Dropdown/ambil-desa.php",
			data: "KodeKec="+KodeKec,
			cache: false,
			success: function(msg){
				$("#KodeDesa").html(msg);
			}
		});
	  });
	  
	});
	</script>
	
	<?php
		//Post data user simpan data baru
		@$NamaDusun				= htmlspecialchars($_POST['NamaDusun']);
		@$KodeKec				= htmlspecialchars($_POST['KodeKec']);
		@$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
		@$KodeKab				= htmlspecialchars($_POST['KodeKab']);
		@$KodeDusun				= htmlspecialchars($_POST['KodeDusun']);
		
	if(isset($_POST['Simpan'])){
			$Tahun=date('Y');
			$sql = "SELECT RIGHT(KodeDusun,6) AS kode FROM mstdusun WHERE KodeDesa='$KodeDesa' AND KodeKec='$KodeKec' ORDER BY KodeDusun DESC LIMIT 1";
			$res = mysqli_query($koneksi, $sql);
			$result = mysqli_fetch_array($res);
			if ($result['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$result['kode'];
			}
			$bikin_kode = str_pad($kode, 6, "0", STR_PAD_LEFT);
			$kode_jadi	 = "DSN-".$bikin_kode;
			
			
			
			$query = mysqli_query($koneksi,"INSERT into mstdusun (KodeDusun,NamaDusun,KodeDesa,KodeKab,KodeKec) 
			VALUES ('$kode_jadi','$NamaDusun','$KodeDesa','$KodeKab','$KodeKec')");
			if($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Data Dusun dengan ID '.$kode_jadi, $login_id, $kode_jadi, 'Master Dusun');
				echo '<script language="javascript">document.location="MasterDusun.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterDusun.php";
					  });
					  </script>';
			}
			 
		}	
		
	
	if(isset($_POST['SimpanEdit'])){
		
		//query update
		$query = mysqli_query($koneksi,"UPDATE mstdusun SET NamaDusun='$NamaDusun',KodeDesa='$KodeDesa',KodeKec='$KodeKec' WHERE KodeDusun='$KodeDusun'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Master Dusun atas nama '.$NamaDusun, $login_id, $KodeDusun, 'Master Dusun');
			echo '<script language="javascript">document.location="MasterDusun.php";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterDusun.php";
				  });
				  </script>';
		}
	}
	
	// if(base64_decode(@$_GET['aksi'])=='Hapus'){
		// hapus transaksi timbangan user
		// $HapusGambar = mysqli_query($koneksi,"SELECT FotoKTP,GambarPerson FROM mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
		// $data=mysqli_fetch_array($HapusGambar);
		
		// $query = mysqli_query($koneksi,"delete from mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
		// if($query){
			// unlink("../images/FotoPerson/".$data['GambarPerson']."");
			// unlink("../images/FotoPerson/".$data['FotoKTP']."");
			// InsertLog($koneksi, 'Hapus Data', 'Menghapus Master User atas nama '.base64_decode(@$_GET['nm']), $login_id, base64_decode(@$_GET['id']), 'Master User');
			// echo '<script language="javascript">document.location="MasterUser.php"; </script>';
		// }else{
			// echo '<script type="text/javascript">
					  // sweetAlert({
						// title: "Hapus Data Gagal!",
						// text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						// type: "error"
					  // },
					  // function () {
						// window.location.href = "MasterUser.php";
					  // });
					  // </script>';
		// }
	// }
	
	
	?>
  </body>
</html>