<?php
include 'akses.php';
@$fitur_id = 4;
include '../library/lock-menu.php';

if (base64_decode(@$_GET['user']) != 'PRS-2019-0000000'){
	$Page = 'MasterPerson';
}else{
	$Page = 'TimbanganDinas';
}


// Tanggal dan Tahun

$DateTime=date('Y-m-d H:i:s');
$LAT = 0; $LONG = 0;
//getdata
@$IDPerson = base64_decode($_GET['user']);
@$NamaPerson 		 = NamaPerson($koneksi, $IDPerson);
if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson WHERE KodeLokasi='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['user']))."'");
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
	
	#map #infowindow-content {
		display: inline;
	}

	.pac-card {
	  margin: 10px 10px 0 0;
	  border-radius: 2px 0 0 2px;
	  box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  outline: none;
	  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	  background-color: #fff;
	  font-family: Roboto;
	}

	#pac-container {
	  padding-bottom: 12px;
	  margin-right: 12px;
	}

	.pac-controls {
	  display: inline-block;
	  padding: 5px 11px;
	}

	.pac-controls label {
	  font-family: Roboto;
	  font-size: 13px;
	  font-weight: 300;
	}

	#pacinput,#pacinputpengambilan {
	  background-color: #fff;
	  font-family: Roboto;
	  font-size: 15px;
	  font-weight: 300;
	  margin: 10px 12px;
	  padding: 5px;
	  text-overflow: ellipsis;
	  width: 250px;
	}

	#pacinput:focus {
	  border-color: #4d90fe;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "LokasiUserDetil.php";
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
              <h2 class="no-margin-bottom">Detil Lokasi User <?php echo $NamaPerson; ?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Lokasi</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="LokasiUserAdd.php?usr=<?php echo $_GET['user'];?>"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>&nbsp;
							<?php } ?>
						</li>
						<li>
							<?php 	
								if (base64_decode(@$_GET['user']) != 'PRS-2019-0000000'){
									echo '<a href="MasterPerson.php?v=Timbangan"><span class="btn btn-primary">Kembali</span></a>';
								}else{
									echo '<a href="TimbanganUserDetil.php?user='.$_GET['user'].'"><span class="btn btn-primary">Kembali</span></a>';
								} 
							?>
							
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Lokasi..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama Lokasi</th>
									  <!--<th>Jenis Lokasi</th>-->
									  <th>Alamat</th>
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
											$reload = "LokasiUserDetil.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT b.NamaLokasi,b.AlamatLokasi,b.KodeLokasi,a.NamaPerson,a.IDPerson FROM mstperson a join lokasimilikperson b on a.IDPerson=b.IDPerson WHERE b.NamaLokasi LIKE '%$keyword%' and a.IsVerified=b'1' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='$IDPerson' ORDER BY b.KodeLokasi ASC";
											$oke = $koneksi->prepare($sql);
											$oke->execute();
											$result = $oke->get_result();
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "LokasiUserDetil.php?pagination=true";
											$sql =  "SELECT b.NamaLokasi,b.AlamatLokasi,b.KodeLokasi,a.NamaPerson,a.IDPerson FROM mstperson a join lokasimilikperson b on a.IDPerson=b.IDPerson WHERE  a.IsVerified=b'1' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='$IDPerson' ORDER BY b.KodeLokasi ASC";
											$oke1 = $koneksi->prepare($sql);
											$oke1->execute();
											@$result = $oke1->get_result();
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
												<strong><?php echo $data ['NamaLokasi']; ?></strong>
											</td>
											<!--<td>
												<?php echo $data ['JenisTimbangan']; ?>
											</td>-->
											
											<td>
												<?php echo $data ['AlamatLokasi']; ?>
											</td>
											
											<td width="100px" align="center">
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
												<!-- Tombol Edit Data -->
													<a href="LokasiUserAdd.php?id=<?php echo base64_encode($data['KodeLokasi']);?>&usr=<?php echo base64_encode($data['IDPerson']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a>
												<?php } ?>
												<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
												<!-- Tombol Edit Data -->												
													<a href="LokasiUserDetil.php?id=<?php echo base64_encode($data['KodeLokasi']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($login_id);?>&tm=<?php echo base64_encode($data['NamaLokasi']);?>&prs=<?php echo base64_encode($data['IDPerson']);?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>
												<?php } ?>
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
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									<form method="post" action="">
										<div class="form-group input-group">						
											<select name="Lokasi" class="form-control" autocomplete="off">	
												<option value="0" <?php if(@$_REQUEST['Lokasi']=='0'){echo 'selected';} ?>>Lokasi Manual</option>
												<option value="1" <?php if(@$_REQUEST['Lokasi']=='1'){echo 'selected';} ?>>Lokasi Pasar</option>
											</select>
											<span class="input-group-btn">
												<button class="btn btn-info" type="submit">Pilih</button>
												
											</span>
										</div><hr>
									</form>
									   <form action="" method="post" >
								  </div>
								  <div class="col-lg-6">
										<div class="form-group-material">
										  <label>Nama Lokasi</label>
										  <input type="text" name="NamaLokasi" class="form-control" placeholder="Nama Lokasi" value="<?php echo @$RowData['NamaLokasi'];?>" maxlength="150">
										</div>
										<!--<div class="form-group-material">
										  <label>Jenis Lokasi</label>
										  <input type="text" name="JenisLokasi" class="form-control" placeholder="Jenis Lokasi" value="<?php echo @$RowData['JenisLokasi'];?>" maxlength="150">
										</div>-->
										<div class="form-group-material">
										  <label>Alamat Lokasi</label>
										  <input type="text" name="AlamatLokasi" class="form-control" placeholder="Alamat Lokasi" value="<?php echo @$RowData['AlamatLokasi'];?>" maxlength="150">
										</div>
										
										<div class="form-group">
										  <label>Keterangan</label>
										  <input type="text" name="Keterangan" class="form-control" placeholder="Keterangan" value="<?php echo @$RowData['Keterangan'];?>">
										</div>
										<div class="form-group-material">
											<label>Nama Kecamatan</label>
											<select id="KodeKec" name="KodeKec" class="form-control" >	
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
											<select id="KodeDesa" class="form-control" name="KodeDesa" >
											<?php if(@$RowData['KodeDesa'] !== NULL OR $RowData['KodeDesa'] !== '') { ?>
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
														
														$LAT = $RowData['KoorLat'];
														$LONG = $RowData['KoorLong'];
													}
												?>
											<?php } else { ?>
												<option value=''>--- Desa ---</option>
											<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group-material">
											<script>
												var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
												var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
											</script>
											<?php include '../library/latlong.php'; ?>
										</div>
										<div class="text-right">
										<?php
											if(@$_GET['id']==null){
												echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
												echo ' <input type="hidden" name="IDPerson" value="'.$IDPerson.'">';
												echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
												echo '<button type="submit"  id="submit-btn"  class="btn btn-primary" name="Simpan">Simpan</button>';
											}else{
												echo '<input type="hidden" name="IDPerson" value="'.$RowData['IDPerson'].'"> ';
												echo '<input type="hidden" name="KodeLokasi" value="'.$RowData['KodeLokasi'].'"> ';
												echo '<input type="hidden" name="login_id" value="'.$login_id.'"> ';
												echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
												echo '<a href="LokasiUser.php"><span class="btn btn-warning">Batalkan</span></a>';
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
	
	<script>
	  function goBack() {
		  window.history.back();
	  }
	</script>
	
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
		@$NamaLokasi			= htmlspecialchars($_POST['NamaLokasi']);
		// @$JenisLokasi			= htmlspecialchars($_POST['JenisLokasi']);
		@$AlamatLokasi			= htmlspecialchars($_POST['AlamatLokasi']);
		@$Keterangan			= htmlspecialchars($_POST['Keterangan']);
		@$Lat					= htmlspecialchars($_POST['Lat']);
		@$LngAsli				= htmlspecialchars($_POST['Lng']);
		@$KodeKec				= htmlspecialchars($_POST['KodeKec']);
		@$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
		@$KodeKab				= htmlspecialchars($_POST['KodeKab']);
		@$IdPerson				= htmlspecialchars($_POST['IDPerson']);
		@$login_id				= htmlspecialchars($_POST['login_id']);
		@$KodeLokasi			= htmlspecialchars($_POST['KodeLokasi']);
		
		
		if(isset($_POST['Simpan'])){
			$Tahun=date('Y');
			$sql = "SELECT RIGHT(KodeLokasi,8) AS kode FROM lokasimilikperson WHERE KodeLokasi LIKE '%".$Tahun."%' AND IDPerson='$IdPerson' ORDER BY KodeLokasi DESC LIMIT 1";
			$oke3 = $koneksi->prepare($sql);
			$oke3->execute();
			$res = $oke3->get_result();
			$result = mysqli_fetch_array($res);
			if ($result['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$result['kode'];
			}
			$bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
			$kode_jadi	 = "LKS-".$Tahun."-".$bikin_kode;
			
			$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='$LngAsli'");
			$num2 = mysqli_num_rows($cek2);
			if($num2 > 0 ){
				@$Lng = $LngAsli+0.0001;
			}else{
				@$Lng = $LngAsli;
			}
			
			$query = mysqli_query($koneksi,"INSERT into lokasimilikperson (KodeLokasi,NamaLokasi,AlamatLokasi,KoorLat,KoorLong,Keterangan,IDPerson,KodeKec,KodeDesa,KodeKab) 
			VALUES ('$kode_jadi','$NamaLokasi','$AlamatLokasi','$Lat','$Lng','$Keterangan','$IdPerson','$KodeKec','$KodeDesa','$KodeKab')");
			if($query){
			
				
				InsertLog($koneksi, 'Tambah Data', 'Menambah Lokasi User dengan ID '.$IdPerson, $login_id, $kode_jadi, 'Lokasi User');
				echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.base64_encode($IdPerson).'";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "LokasiUserDetil.php";
					  });
					  </script>';
			}
			 
		}
	
	if(isset($_POST['SimpanEdit'])){
		$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='$LngAsli'");
		$num2 = mysqli_num_rows($cek2);
		if($num2 > 0 ){
			@$Lng = $LngAsli+0.0001;
		}else{
			@$Lng = $LngAsli;
		}
			
		//query update  
		$query = mysqli_query($koneksi,"UPDATE lokasimilikperson SET NamaLokasi='$NamaLokasi',AlamatLokasi='$AlamatLokasi',Keterangan='$Keterangan',KodeKec='$KodeKec',KodeDesa='$KodeDesa',KoorLat='$Lat',KoorLong='$Lng' WHERE IDPerson='$IdPerson' AND KodeLokasi='$KodeLokasi'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Lokasi User dengan ID '.$IdPerson, $login_id, $KodeLokasi, 'Lokasi User');
			echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.base64_encode($IdPerson).'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "LokasiUserDetil.php";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){	
		$query = mysqli_query($koneksi,"delete from lokasimilikperson WHERE KodeLokasi='".htmlspecialchars(base64_decode($_GET['id']))."' and IDPerson='".htmlspecialchars(base64_decode($_GET['prs']))."'");
		if($query){
					
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Lokasi User atas id '.base64_decode($_GET['id']).' dengan nama lokasi '.base64_decode(@$_GET['tm']), base64_decode(@$_GET['nm']), base64_decode(@$_GET['id']), 'Lokasi User');
				
			echo '<script language="javascript">document.location="LokasiUserDetil.php?user='.$_GET['prs'].'"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "LokasiUserDetil.php?user='.$_GET['prs'].'";
					  });
					  </script>';
		}
	}
	
	function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	?>
  </body>
</html>