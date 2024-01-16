<?php
include 'akses.php';
$fitur_id = 31;
include '../library/lock-menu.php'; 
include '../library/tgl-indo.php'; 


$Page = 'LaporanRetribusi';
$SubPage = 'LapTagihanRetribusi';

date_default_timezone_set('Asia/Jakarta'); $DateTime=date('Y-m-d H:i:s'); $Tahun=date('Y');  $Tanggal=date('Y-m-d'); 
$NamaBulan = array (1 =>   'Januari',
	'Februari',
	'Maret',
	'April',
	'Mei',
	'Juni',
	'Juli',
	'Agustus',
	'September',
	'Oktober',
	'November',
	'Desember'
);

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
			text-align: left;
		}
		.hidden {
		  display: none;
		  visibility: hidden;
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
              <h2 class="no-margin-bottom">Laporan Tagihan Retribusi Pasar</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab">
							<!--<span class="btn btn-primary">Data Lapak</span>-->
							</a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"></a>
							<?php } ?>
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#detil-user" data-toggle="tab"></a>
							<?php } ?>
						</li>
						
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==''  AND $_GET['tr']==''){ echo 'in active show'; }else{ echo 'hidden'; } ?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Tagihan User </h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-7 offset-lg-5">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Orang/Pasar..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>&nbsp;
												
												<a href="../library/html2pdf/cetak/LapTagihanRetribusi.php" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak Laporan</span></a>
												
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr align="left">
									  <th align="left">No</th>
									  <th align="left">Lapak</th>
									  <th align="left">Tagihan</th>
									  <th align="left">Jumlah Hari</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "LapTagihanRetribusi.php?pagination=true&keyword=".@$_REQUEST['keyword'];
										$sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,d.NamaPerson,a.IDLapak,c.NoRekBank,c.IDPerson,a.KodePasar,c.IsAktif FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) join mstperson d ON c.IDPerson=d.IDPerson WHERE 1";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND ((b.NamaPasar LIKE '%".$_REQUEST['keyword']."%') OR (d.NamaPerson LIKE '%".$_REQUEST['keyword']."%')) ";
										}
																				
										$sql .="  ORDER BY b.NamaPasar, d.NamaPerson ASC";
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
											<td width="10%" align="left">
												<?php echo ++$no_urut;?> 
											</td>
											<td width="50%" align="left">
												<strong><?php echo $data ['NamaPasar']." </strong>".$data ['BlokLapak']." ".$data ['NomorLapak']; ?><br>
												<?php echo $data ['NamaPerson']; ?>
												
											</td>
											<td width="20%" align="left">
												<?php 
												$query ="SELECT TanggalTrans,NominalRetribusi from trretribusipasar where IDPerson='".$data['IDPerson']."' and KodePasar='".$data['KodePasar']."' and IDLapak='".$data['IDLapak']."' order by TanggalTrans DESC Limit 1"; 
												$res =mysqli_query($koneksi, $query);
												$result1 =mysqli_fetch_array($res);
												$datetime1 = new DateTime($result1[0]);
												$datetime2 = new DateTime($Tanggal);
												$difference = $datetime1->diff($datetime2);
												$selisih = $difference->days;
													
												if (strtotime($result1[0]) <= strtotime($Tanggal)  ){
													echo "Rp ".number_format($selisih*$result1[1]);
												}else{
													echo 'Rp 0';
												}
												?>
											</td>											
											<td width="20%" align="left">							
												<?php 
												if($result1[0] == '0000-00-00'){
													echo '<span class="fa fa-minus text-danger" >&nbsp;&nbsp;</span> 0 Hari';
												}else{
													if (strtotime($result1[0]) <= strtotime($Tanggal)){
														echo '<span class="fa fa-minus text-danger" >&nbsp;&nbsp;</span>'.$selisih.' Hari';
													}else{
														echo '<span class="fa fa-plus text-info" >&nbsp;&nbsp;</span>'.$selisih.' Hari';
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
	<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
		});
	</script>
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>

	  $("#KodePasar").change(function(){
		var KodePasar = $("#KodePasar").val();
		$.ajax({
			url: "../library/Dropdown/ambil-lapakpasar.php",
			data: "KodePasar="+KodePasar,
			cache: false,
			success: function(msg){
				$("#IDLapak").html(msg);
			}
		});
	  });
	  
	});
	</script>
	
	<script>
	  function goBack() {
		  window.history.back();
	  }
	</script>
	
	<?php
		@$KodePasar			= htmlspecialchars($_POST['KodePasar']);
		@$IDLapak			= htmlspecialchars($_POST['IDLapak']);
		@$IDPerson		 	= htmlspecialchars($_POST['IDPerson']);
		@$NoRekBank		 	= htmlspecialchars($_POST['NoRekBank']);
		@$AnRekBank		 	= htmlspecialchars($_POST['AnRekBank']);
		@$Keterangan	 	= htmlspecialchars($_POST['Keterangan']);
		@$JenisLapak	 	= htmlspecialchars($_POST['JenisLapak']);
		@$StatusKepemilikan	= htmlspecialchars($_POST['StatusKepemilikan']);
	
	
	if(isset($_POST['Simpan'])){
			$lapak = mysqli_query($koneksi,"SELECT BlokLapak,NomorLapak,Retribusi FROM lapakpasar WHERE IDLapak='$IDLapak' and KodePasar='$KodePasar'");
			$dat = mysqli_fetch_array($lapak);
			
			$query = mysqli_query($koneksi,"INSERT lapakperson (KodePasar,IDLapak,BlokLapak,NomorLapak,NoRekBank,AnRekBank,JenisLapak,StatusKepemilikan,Keterangan,IDPerson,Retribusi,	IsAktif,TglAktif) 
			VALUES ('$KodePasar','$IDLapak','".$dat[0]."','".$dat[1]."','$NoRekBank','$AnRekBank','$JenisLapak','$StatusKepemilikan','$Keterangan','$IDPerson','".$dat[2]."',b'1',NOW())");
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