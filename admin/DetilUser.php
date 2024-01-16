<?php
include 'akses.php';

$Mode = htmlspecialchars($_GET['v']);
@include '../library/tgl-indo.php';
$Page = 'MasterPerson';


if(@$_GET['id']!=null){
	$Sebutan = 'Detail Informasi User';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT  a.NamaPerson,a.NoRekeningBank,a.AnRekBank,a.AlamatLengkapPerson,a.NIK,a.IsPerusahaan,a.JenisPerson,a.PJPerson,b.NamaPerson as Perusahaan,b.AlamatLengkapPerson as AlamatUsaha,a.IDPerson, (SELECT COUNT(PJPerson) From mstperson Where PJPerson= a.IDPerson) as Jumlah
	FROM mstperson a 
	LEFT JOIN mstperson b on b.IDPerson=a.PJPerson
	WHERE a.IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	@$row = explode("#", $RowData['JenisPerson']);
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
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<style>
	th {
		text-align: center;
	}
	.Zebra_DatePicker_Icon_Wrapper {
			width:100% !important;
	}
		
	.Zebra_DatePicker_Icon {
		top: 11px !important;
		right: 12px;
	}
	
	</style>
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
              <h2 class="no-margin-bottom">Detail Informasi User</h2>
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
							<a href="#tambah-user" data-toggle="tab"></a>&nbsp;
						</li>
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id'] != null ){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
								   <h5>Informasi User</h5>
									<div class="table-responsive">  
									<table class="table table-striped">
									  <tbody>
										<tr><td width="40%">NIK</td><td width="1%">:</td><td><?php echo $RowData['NIK'];?></td></tr>
										<tr><td width="50%">Nama Lengkap</td><td width="1%">:</td><td><?php echo strtoupper($RowData['NamaPerson']);?></td></tr>
										<tr><td>Alamat Lengkap</td><td>:</td><td><?php echo $RowData['AlamatLengkapPerson'];?></td></tr>
										<tr><td>No Rekening</td><td>:</td><td><?php echo $RowData['NoRekeningBank'];?></td></tr>
										<tr><td>Atas Nama Rekening</td><td>:</td><td><?php echo $RowData['AnRekBank'];?></td></tr>
									  </tbody>
									</table>
									</div>
									</div>
									<?php if ($RowData['PJPerson'] != null OR $RowData['PJPerson'] != '') { ?>
									<div class="col-lg-6">
								   <h5>Penanggung Jawab</h5>
									<div class="table-responsive">  
									<table class="table table-striped">
									  <tbody>
										<tr><td width="35%">Nama Perusahaan</td><td width="1%">:</td><td><?php echo strtoupper($RowData['Perusahaan']);?></td></tr>
										<tr><td width="35%">Alamat Perusahaan</td><td width="1%">:</td><td><?php echo $RowData['AlamatUsaha'];?></td></tr>
										
									  </tbody>
									</table>
									</div>
									</div>
									<?php } ?>
									<hr>
									<div class="col-lg-12">
									<?php if ($RowData['IsPerusahaan'] != 1)  { ?>
									
									<?php if($row[0] == 'Timbangan') { ?>
									<h5>Timbangan User</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>ID Timbangan</th>
											  <th>Nama Timbangan</th>
											  <th>Alamat</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,b.IDPerson,b.IDTimbangan 
												FROM timbanganperson b 
												join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan 
												join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) 
												join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) 
												join lokasimilikperson f on (b.KodeLokasi,b.IDPerson)=(f.KodeLokasi,f.IDPerson) 
												WHERE b.IDPerson='".$RowData['IDPerson']."' GROUP BY b.IDTimbangan");
												$no_urut = 0;
												$count = mysqli_num_rows($sql);
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($res = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo $res['IDTimbangan']; ?>
												</td>
												<td>
													<?php echo "<strong>".$res['NamaTimbangan']."</strong> ".$res['NamaKelas']." ".$res['NamaUkuran']; ?>
												</td>
												<td>
													<?php echo $res['NamaLokasi']; ?>
												</td>
												
											</tr>
												<?php } ?>
											
											<?php } ?>
										  </tbody>
										</table>
									</div><hr>
									<?php } ?>
									
									
									<?php if($row[1] == 'Pedagang') { ?>
									<h5>Lapak User</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>Lapak Person</th>
											  <th>Retribusi</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,c.NoRekBank,c.IDPerson,a.KodePasar,c.IsAktif FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) where c.IDPerson='".$RowData['IDPerson']."' order by b.NamaPasar");
												$no_urut = 0;
												$count = mysqli_num_rows($sql);
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($data = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													ID Lapak : <strong><?php echo $data ['IDLapak']." </strong>"; ?><br>
													<strong><?php echo $data ['NamaPasar']." </strong>".$data ['BlokLapak']." ".$data ['NomorLapak']; ?>
												</td>
												<td align="right">
													<?php echo "Rp ".number_format($data['Retribusi']);?>
												</td>
												
												
											</tr>
												<?php } ?>
											
											<?php } ?>
										  </tbody>
										</table>
									</div><hr>
									<?php } ?>
									
									<?php if ($RowData['Jumlah'] > 0) { ?>
									
										
									
									<?php
										$sql =mysqli_query($koneksi, "SELECT IDPerson,NamaPerson,AlamatLengkapPerson From mstperson where PJPerson ='".$RowData['IDPerson']."' and JenisPerson LIKE '%PupukSub%'");
										$no_urut = 0;
										$count = mysqli_num_rows($sql);
										if($count != null OR $count != 0){
									?>
									
									
									<h5>Distribusi Pupuk</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>ID Person</th>
											  <th>Nama Lengkap</th>
											  <th>Alamat</th>
											</tr>
										  </thead>
										  <tbody>
											<?php 
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($data = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													
													<strong><?php echo $data ['IDPerson']." </strong>"; ?>
												</td>
												<td>
													<?php echo $data['NamaPerson'];?>
												</td>
												<td>
													<?php echo $data['AlamatLengkapPerson'];?>
												</td>
												
												
											</tr>
												<?php } ?>
											
											<?php } ?>
										  </tbody>
										</table>
									</div><hr>
									
									<?php } ?>
									<?php
										$sql =mysqli_query($koneksi, "SELECT IDPerson,NamaPerson,AlamatLengkapPerson From mstperson where PJPerson ='".$RowData['IDPerson']."' and JenisPerson LIKE '%Toko%'");
										$no_urut = 0;
										$count = mysqli_num_rows($sql);
										if($count != null OR $count != 0){
									?>
									<h5>Data Toko</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>Nama Toko</th>
											  <th>Alamat</th>
											  <th>No Telepon</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
											
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($data = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo $data ['NamaPerson'];?>
												</td>
												<td>
													<?php echo $data ['AlamatLengkapPerson'];?>
												</td>
												<td>
													<?php echo $data ['NoHP'];?>
												</td>
												
											</tr>
												<?php } ?>
											
											<?php } ?>
										  </tbody>
										</table>
									</div><hr>
									<?php } ?>
									
									<?php } ?>
									<?php } ?>
									</div>
									<div class="col-lg-12">
										<a href="MasterPerson.php?v=<?=$Mode?>"><span class="btn btn-danger">Kembali</span></a>
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
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

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
	<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
	<script type="text/javascript">
		// open modal lihat progress
	   $(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var no_trans = $(this).data("notransaksi");
		
		  var user = $(this).data("user");
		  var no_urut = $(this).data("nourut");
		  var aksi = $(this).data("aksi");
		  	   $.ajax({
					   url: "Modal/AddTeraTimbangan.php",
					   type: "GET",
					   data : {NoTransaksi: no_trans,login_id: user,NoUrutTrans: no_urut,Aksi: aksi},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		// open modal lihat progress
	   $(document).ready(function () {
	   $(".open_modal_edit").click(function(e) {
		  var no_trans = $(this).data("notransaksi");
		  var user = $(this).data("user");
		  var no_urut = $(this).data("nourut");
		  var aksi = $(this).data("aksi");
		  	   $.ajax({
					   url: "Modal/EditTeraTimbangan.php",
					   type: "GET",
					   data : {NoTransaksi: no_trans,login_id: user,NoUrutTrans: no_urut,Aksi: aksi},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>

	<?php 
		
		@$NoTransaksi	= htmlspecialchars($_POST['NoTransaksi']);
		@$NoRefDibayar	= htmlspecialchars($_POST['NoRefDibayar']);
		@$Keterangan	= htmlspecialchars($_POST['Keterangan']);
		@$IsDibayar		= htmlspecialchars($_POST['IsDibayar']);
	
	//Simpan Transaksi Sidang Tera
	if(isset($_POST['SimpanTransaksi'])){
		//update 
		$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglDibayar='$TanggalTransaksi', NoRefDibayar='$NoRefDibayar', KeteranganBayar='$Keterangan', IsDibayar='$IsDibayar' WHERE NoTransaksi='$NoTransaksi'");
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Transaksi Terima Pembayaran ', $login_id, $NoTransaksi, 'Transaksi Terima Pembayaran');
			echo '<script language="javascript">document.location="TrTerimaPembayaran.php";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrTerimaPembayaran.php";
			  });
			  </script>';
		}
	}
	
	//Hapus Transaksi Penerimaan
	if(base64_decode(@$_GET['aksi'])=='HapusTransaksi'){
		mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglTera=NULL, NoRefTera=NULL, KeteranganTera=NULL, IsDibayar=NULL, StatusTransaksi='PENERIMAAN' WHERE NoTransaksi='".htmlspecialchars(base64_decode(@$_GET['tr']))."'");
		
		$HapusGambar = mysqli_query($koneksi,"SELECT FotoAction1,FotoAction2,FotoAction3 FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."'");
		$data=mysqli_fetch_array($HapusGambar);	
		
		unlink("../images/TeraTimbangan/".$data['FotoAction1']."");
		unlink("../images/TeraTimbangan/thumb_".$data['FotoAction1']."");
		unlink("../images/TeraTimbangan/".$data['FotoAction2']."");
		unlink("../images/TeraTimbangan/thumb_".$data['FotoAction2']."");
		unlink("../images/TeraTimbangan/".$data['FotoAction3']."");
		unlink("../images/TeraTimbangan/thumb_".$data['FotoAction3']."");
		
		//update 
		$edit = mysqli_query($koneksi,"UPDATE trtimbanganitem SET  FotoAction1=NULL, FotoAction2=NULL, FotoAction3=NULL, HasilAction1=NULL, HasilAction2=NULL, HasilAction3=NULL WHERE NoTransaksi='".htmlspecialchars(base64_decode(@$_GET['tr']))."'");
		if($edit){
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Transaksi Penerimaan Timbangan', $login_id, base64_decode(@$_GET['tr']), 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrSidangTera.php";
					  });
					  </script>';
		}
		
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$HapusGambar = mysqli_query($koneksi,"SELECT FotoAction1,FotoAction2,FotoAction3 FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."' and NoUrutTrans='".htmlspecialchars(base64_decode($_GET['nm']))."'");
		$data=mysqli_fetch_array($HapusGambar);
		
		$query = mysqli_query($koneksi,"update trtimbanganitem set FotoAction1=NULL, FotoAction2=NULL, FotoAction3=NULL, HasilAction1=NULL, HasilAction2=NULL, HasilAction3=NULL  WHERE  NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."' and NoUrutTrans='".htmlspecialchars(base64_decode($_GET['nm']))."'");
		if($query){
			//hapus gambar terlebih dahulu
		
			unlink("../images/TeraTimbangan/".$data['FotoAction1']."");
			unlink("../images/TeraTimbangan/thumb_".$data['FotoAction1']."");
			unlink("../images/TeraTimbangan/".$data['FotoAction2']."");
			unlink("../images/TeraTimbangan/thumb_".$data['FotoAction2']."");
			unlink("../images/TeraTimbangan/".$data['FotoAction3']."");
			unlink("../images/TeraTimbangan/thumb_".$data['FotoAction3']."");
			
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Hasil Sidang Tera Timbangan ', $login_id, base64_decode(@$_GET['id']), 'Transaksi Proses Sidang Tera');
				
			echo '<script language="javascript">document.location="TrSidangTera.php?NoTransaksi='.base64_decode($_GET['id']).'"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrSidangTera.php?user='.base64_decode($_GET['id']).'";
					  });
					  </script>';
		}
	}
	
	//Simpan Edit Item Timbangan
	if(isset($_POST['SimpanEdit'])){
		//update 
		$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET HasilAction1='$HasilAction1', HasilAction2='$HasilAction2', HasilAction3='$HasilAction3' WHERE NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
		if ($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Transaksi Hasil Sidang Tera ', $login_id, $NoTransaksi, 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php?NoTransaksi='.$NoTransaksi.'";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrSidangTera.php";
			  });
			  </script>';
		}
	}
	
	?>
  </body>
</html>