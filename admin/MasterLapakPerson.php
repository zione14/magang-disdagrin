<?php
include 'akses.php';
$fitur_id = 30;
include '../library/lock-menu.php'; 

$Page = 'MasterDataPasar';
$SubPage = 'MasterLapakPerson';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

$nm = isset($_GET['nm']) && $_GET['nm'] != NULL ? base64_decode($_GET['nm']) : '' ;  

if(@$_GET['id'] !=null){
	$Edit = mysqli_query($koneksi,"SELECT * FROM lapakpasar WHERE IDLapak='".htmlspecialchars(base64_decode($_GET['id']))."'");
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
			var answer = confirm("Apakah Anda yakin mengnonaktifkan data ini ?")
			if (answer == true){
				window.location = "MasterLapakPerson.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
	<script type="text/javascript">
		function confirmation1() {
			var answer = confirm("Apakah Anda yakin aktifkan data ini ?")
			if (answer == true){
				window.location = "MasterLapakPerson.php";
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
              <h2 class="no-margin-bottom">Master Lapak User</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Lapak Person</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>
							<?php } ?>
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#detil-user" data-toggle="tab"></a>
							<?php } ?>
						</li>
						
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Lapak User</h3>
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
									  <th>Nama User</th>
									  <th>Alamat User</th>
									  <th>Jumlah Retribusi</th>
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
											$reload = "MasterLapakPerson.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,d.NamaPerson,d.AlamatLengkapPerson,d.IDPerson FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) join mstperson d on c.IDPerson=d.IDPerson where (a.BlokLapak LIKE '%$keyword%' OR  a.NomorLapak LIKE '%$keyword%' OR  b.NamaPasar LIKE '%$keyword%' OR  d.NamaPerson LIKE '%$keyword%' ) GROUP BY d.IDPerson order by d.NamaPerson";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MasterLapakPerson.php?pagination=true";
											$sql =  "SELECT a.BlokLapak,a.NomorLapak,SUM(a.Retribusi) as total,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,d.NamaPerson,d.AlamatLengkapPerson,d.IDPerson FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) join mstperson d on c.IDPerson=d.IDPerson  GROUP BY d.IDPerson order by d.NamaPerson";
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
												<?php echo $data ['NamaPerson']; ?>
												
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
											</td>
											<td align="center">
												
												<?php echo number_format($data ['total']); ?>
												
												
											</td>
											
											<td width="100px" align="center">
												<?php if ($cek_fitur['ViewData'] =='1'){ ?>
													<a href="MasterLapakPerson.php?id=<?php echo base64_encode($data['IDPerson']);?>&nm=<?php echo base64_encode($data['NamaPerson']); ?>" title='Detil Lapak Person'><i class='btn btn-info btn-sm'><span class='fa fa-bar-chart'></span></i></a> 
												<?php } ?>
											
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
						<div class="tab-pane fade" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Tambah Data Baru</h3>
							</div>
							<div class="card-body">
								<form class="form-horizontal" method="post" action="">
								<div class="form-group row">
								 <label class="col-sm-3 form-control-label">Nama Pasar</label>
								   <div class="col-sm-9">
									<select id="KodePasar" name="KodePasar" class="form-control" required>	
										<?php
												echo "<option value=''>--- Nama Pasar ---</option>";
											$menu = mysqli_query($koneksi,"SELECT * FROM mstpasar");
											while($kode = mysqli_fetch_array($menu)){
												if($RowData['KodePasar'] !== NULL){
													if($kode['KodePasar'] === $RowData['KodePasar']){
														echo "<option value=\"".$kode['KodePasar']."\" selected='selected'>".$kode['NamaPasar']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodePasar']."\" >".$kode['NamaPasar']."</option>\n";
													}
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
									<select id="IDLapak" class="form-control" name="IDLapak" required>
										<?php
											echo "<option value=''>--- Lapak Pasar ---</option>";
											$menu = mysqli_query($koneksi,"SELECT a.* FROM lapakpasar a 
											LEFT JOIN lapakperson  b on (a.KodePasar,a.IDLapak)=(b.KodePasar,b.IDLapak)
											where a.KodePasar='".$RowData['KodePasar']."' and b.IDPerson is null ORDER BY BlokLapak");
											while($kode1 = mysqli_fetch_array($menu)){
												if($RowData['IDLapak'] !== NULL){
													if($kode1['IDLapak'] === $RowData['IDLapak']){
														echo "<option value=\"".$kode1['IDLapak']."\" selected='selected'>".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
													}else{
														echo "<option value=\"".$kode1['IDLapak']."\" >".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
													}
												}else{
													echo "<option value=\"".$kode1['IDLapak']."\" >".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
												}
												
												
											}
										?>
									</select>
								   </div>
								</div>	
								<div class="form-group row">
								 <label class="col-sm-3 form-control-label">Nama User</label>
								   <div class="col-sm-9">
									<select name="IDPerson" class="form-control" required>	
										<?php
											$menu = mysqli_query($koneksi,"SELECT NamaPerson,IDPerson FROM mstperson WHERE  JenisPerson LIKE '%Pedagang%' AND IsVerified=b'1'  ORDER BY NamaPerson ASC");
											while($kode = mysqli_fetch_array($menu)){
												if($kode['NamaPerson']==@$nm){
													echo "<option value=\"".$kode['IDPerson']."\" selected >".$kode['NamaPerson']."</option>\n";
												}else{
													echo "<option value=\"".$kode['IDPerson']."\" >".$kode['NamaPerson']."</option>\n";
												}
											}
										?>
									</select>
								   </div>
								</div>	
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">No Rekening Bank</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="No Rekening Bank" value="<?php echo @$RowData['NoRekBank'];?>" name="NoRekBank" required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Atas Nama Rekening</label>
								  <div class="col-sm-9">
									<input type="text" class="form-control" maxlength="50" placeholder="Atas Nama Rekening" name="AnRekBank"  required>
								  </div>
								</div>
								<div class="form-group row">
								  <label class="col-sm-3 form-control-label">Keterangan</label>
								  <div class="col-sm-9">
									<textarea type="text" name="Keterangan" class="form-control" rows="2" placeholder="Keterangan"></textarea>
								  </div>
								</div>
								<?php
									if(@$_GET['id']==null){
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
										
									}else{
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
									}
								?>
								<a href="MasterLapakPerson.php"><span class="btn btn-warning">Batalkan</span></a>
							  </form>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="detil-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Lapak User <?php echo base64_decode(@$_GET['nm']); ?></h3>
							</div>
							<div class="card-body">
								<div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Pasar </th>
									  <th>No Rekening</th>
									  <th>Retribusi</th>
									  <th>Status</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										@$kode = $_GET['id'];
										@$nama = $_GET['nm'];
										$reload = "MasterLapakPerson.php?id=$kode&nm=$nama&pagination=true";
										$sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,c.NoRekBank,c.IDPerson,a.KodePasar,c.IsAktif 
										FROM lapakpasar a 
										join mstpasar b on a.KodePasar=b.KodePasar 
										join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) 
										where c.IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."' order by b.NamaPasar";
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
												<?php echo $data ['NoRekBank']; ?>
											</td>
											<td align="center">
												<?php echo number_format($data['Retribusi']); ?>
											</td>
											<td align="center">
												<?php echo $data ['IsAktif'] > 0 ? '<font class="text-success">Aktif</font>' : '<font class="text-danger">Tidak Aktif</font>';?>
											</td>
											<td width="100px" align="center">
												<?php
													if ($cek_fitur['EditData'] =='1'){ 
														if ($data['IsAktif'] == '1') {
															echo '<a href="MasterLapakPerson.php?id='.base64_encode($data['IDLapak']).'&aksi='.base64_encode('NonAktif').'&prs='.base64_encode($data['IDPerson']).'&psr='.base64_encode($data['KodePasar']).'" title="Non Aktifkan" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-window-close"></span></i></a>';
														}else{
															echo '<a href="MasterLapakPerson.php?id='.base64_encode($data['IDLapak']).'&aksi='.base64_encode('Aktif').'&prs='.base64_encode($data['IDPerson']).'&psr='.base64_encode($data['KodePasar']).'" title="Aktifkan" onclick="return confirmation1()"><i class="btn btn-success btn-sm"><span class="fa fa-check"></span></i></a>';
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

	  $("#KodePasar").change(function(){
		var KodePasar = $("#KodePasar").val();
		$.ajax({
			url: "../library/Dropdown/ambil-lapakperson.php",
			data: "KodePasar="+KodePasar,
			cache: false,
			success: function(msg){
				$("#IDLapak").html(msg);
			}
		});
	  });
	  
	});
	</script>
	
	
	<?php
		@$KodePasar			= htmlspecialchars($_POST['KodePasar']);
		@$IDLapak			= htmlspecialchars($_POST['IDLapak']);
		@$IDPerson		 	= htmlspecialchars($_POST['IDPerson']);
		@$NoRekBank		 	= htmlspecialchars($_POST['NoRekBank']);
		@$AnRekBank		 	= htmlspecialchars($_POST['AnRekBank']);
		@$Keterangan	 	= htmlspecialchars($_POST['Keterangan']);
	
	
	if(isset($_POST['Simpan'])){
			$lapak = mysqli_query($koneksi,"SELECT BlokLapak,NomorLapak,Retribusi FROM lapakpasar WHERE IDLapak='$IDLapak' and KodePasar='$KodePasar'");
			$dat = mysqli_fetch_array($lapak);
			
			$query = mysqli_query($koneksi,"INSERT lapakperson (KodePasar,IDLapak,BlokLapak,NomorLapak,NoRekBank,AnRekBank,Keterangan,IDPerson,Retribusi,	IsAktif,TglAktif, Tagihan) 
			VALUES ('$KodePasar','$IDLapak','".$dat[0]."','".$dat[1]."','$NoRekBank','$AnRekBank','$Keterangan','$IDPerson','0',b'1',NOW(), 0)");
			if($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Data Lapak Person '.$IDPerson, $login_id, $IDLapak, 'Master Lapak User');
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="MasterLapakPerson.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Data Sudah Ada!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterLapakPerson.php";
					  });
					  </script>';
			}
		
	}
	
	
	
	if(base64_decode(@$_GET['aksi'])=='NonAktif'){
		$query = mysqli_query($koneksi,"update lapakperson set IsAktif=b'0' WHERE IDLapak='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['prs']))."' and KodePasar='".htmlspecialchars(base64_decode($_GET['psr']))."'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Menghapus Data Master Lapak User'. base64_decode($_GET['prs']), $login_id, base64_decode($_GET['id']), 'Master Lapak User');
			echo '<script language="javascript">document.location="MasterLapakPerson.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Non Aktifkan Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterLapakPerson.php";
					  });
					  </script>';
		}
	
	}
	
	if(base64_decode(@$_GET['aksi'])=='Aktif'){
		$query = mysqli_query($koneksi,"update lapakperson set IsAktif=b'1' WHERE IDLapak='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['prs']))."' and KodePasar='".htmlspecialchars(base64_decode($_GET['psr']))."'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Menghapus Data Master Lapak User'. base64_decode($_GET['prs']), $login_id, base64_decode($_GET['id']), 'Master Lapak User');
			echo '<script language="javascript">document.location="MasterLapakPerson.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Non Aktifkan Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterLapakPerson.php";
					  });
					  </script>';
		}
	
	}

	
	?>
  </body>
</html>