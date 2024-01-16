<?php
include 'akses.php';
@$fitur_id = 16;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'TrMetrologi';
$SubPage = 'TrPengambilan';
$TanggalTransaksi = date("Y-m-d H:i:s");
$Tanggal = date('Ymd');

if(@$_GET['id']!=null){
	$Sebutan = 'Pengembalian Timbangan';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT a.NamaPerson,b.IDPerson,b.NoTransaksi,c.IDTimbangan,b.TglTransaksi,b.NoRefAmbil,b.KeteranganAmbil,b.IsDiambil	 FROM mstperson a join tractiontimbangan b on a.IDPerson=b.IDPerson left join trtimbanganitem c on b.NoTransaksi=c.NoTransaksi WHERE b.NoTransaksi='".base64_decode($_GET['id'])."'");
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
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "TrPengambilan.php";
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
              <h2 class="no-margin-bottom">Pengambilan Timbangan User</h2>
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
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Pengambilan Timbangan</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama</th>
									  <th>Alamat</th>
									  <th>Jumlah Retribusi</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "TrPengambilan.php?pagination=true";
										$sql =  "SELECT a.NoTransaksi,b.NamaPerson,b.AlamatLengkapPerson,b.IDPerson,a.TotalRetribusi,a.TglTransaksi FROM tractiontimbangan a join mstperson b on a.IDPerson=b.IDPerson Where JenisPerson LIKE '%Timbangan%' AND a.IsDibayar=b'1' AND StatusTransaksi='PROSES SIDANG' AND (a.IsDiambil=b'0' OR a.IsDiambil is null )";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND b.NamaPerson LIKE '%".$_REQUEST['keyword']."%'  ";
										}
										
										$sql .=" ORDER BY a.TglTransaksi ASC";
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
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><strong>Tidak ada data</strong></td></tr>';
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
												<strong><?php echo $data ['NamaPerson']; ?></strong><br>
												<?php echo $data['NoTransaksi']."<br>"; ?>
												<?php echo 'Pada : '.@TanggalIndo($data['TglTransaksi']); ?>
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
											</td>
											<td align="center">
												<?php echo "Rp ".number_format($data['TotalRetribusi']); ?>
												<p style="color:red">Sudah Dibayar</p>
											</td>
											<td width="100px" align="center">
												<?php if ($cek_fitur['ViewData'] =='1'){ ?>
													<a href="TrPengambilan.php?id=<?php echo base64_encode($data['NoTransaksi']);?>" title='Pengembalian Timbangan'><i class='btn btn-info btn-sm'><span class='fa fa-info'></span></i></a> 
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
						<div class="tab-pane fade <?php if(@$_GET['id'] != null ){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									<h5>Informasi Transaksi</h5>
									<div class="table-responsive">  
									<table class="table table-striped">
									  <tbody>
										<tr><td width="35%">Nama Lengkap</td><td width="1%">:</td><td><?php echo strtoupper($RowData['NamaPerson']);?></td></tr>
										<tr><td width="35%">No Transaksi</td><td width="1%">:</td><td><?php echo $RowData['NoTransaksi'];?></td></tr>
										<tr><td>Tanggal Transaksi</td><td>:</td><td><?php echo TanggalIndo($RowData['TglTransaksi']);?></td></tr>
									  </tbody>
									</table>
									</div><hr>
									
									<h5>Detil Timbangan</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>Nama Timbangan</th>
											  <th>Alamat</th>
											  <th>Hasil Tera</th>
											  <th>Tarif Retribusi</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT a.NominalRetribusi,b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,a.NoUrutTrans,a.NoTransaksi,a.IDPerson,a.HasilAction1 FROM trtimbanganitem a join timbanganperson b on  (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(b.IDTimbangan,b.KodeLokasi,b.IDPerson)  join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) join lokasimilikperson f on (b.KodeLokasi,b.IDPerson)=(f.KodeLokasi,f.IDPerson) WHERE a.NoTransaksi='".$RowData['NoTransaksi']."' GROUP BY b.IDTimbangan,a.NoUrutTrans order by a.NoUrutTrans");
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
													<?php echo "<strong>".$res['NamaTimbangan']."</strong> ".$res['NamaKelas']." ".$res['NamaUkuran']; ?>
												</td>
												<td>
													<?php echo $res['NamaLokasi']; ?>
												</td>
												<td>
													<?php echo $res['HasilAction1']; ?>
												</td>
												<td align="right">
													<?php 
														echo "<strong>".number_format($res ['NominalRetribusi'])."</strong>"; 
														$jumlah[] = $res['NominalRetribusi'];
													?>
												</td>
											</tr>
												<?php } ?>
											<tr style="background-color:#f0f0f0;">
												<td colspan="4" align="center">
													<strong>Total Retribusi</strong>
												</td>
												<td align="right">
													<?php echo number_format(array_sum(@$jumlah)); ?>
												</td>
											</tr>	
											<?php } ?>
										  </tbody>
										</table>
									</div><hr>	
									</div>
									<div class="col-lg-12">
										<div class="text-left">
											<form method="post" action="" class="form-horizontal">
												<!--<div class="form-group row">
												  <label class="col-sm-3 form-control-label">No Referensi Pengambilan</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control" maxlength="50" placeholder="No Referensi Pengambilan" value="<?php echo @$RowData['NoRefAmbil'];?>" name="NoRefAmbil" required>
												  </div>
												</div>-->
												<div class="form-group row">
												  <label class="col-sm-3 form-control-label">Keterangan</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control"  value="<?php echo @$RowData['KeteranganAmbil'];?>" placeholder="Keterangan"  name="Keterangan">
												  </div>
												</div>
												<div class="form-group row">
												  <label class="col-sm-3 form-control-label"></label>
												  <div class="col-sm-9">
													<input type="checkbox" value="1" name="IsDiambil" <?php if(@$RowData['IsDiambil'] == '1') { echo 'checked'; }; ?> class="checkbox-template">
													<label>Sudah Di Ambil</label>
												  </div>
												</div><hr/>
												<input type="hidden" name="NoTransaksi" value="<?php echo $RowData['NoTransaksi']; ?>"> 
												<button type="submit" class="btn btn-success" name="SimpanTransaksi">Simpan</button>
												<a href="TrTerimaPembayaran.php"><span class="btn btn-danger">Kembali</span></a>
											</form>
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
	
	<?php 
		
		@$NoTransaksi	= htmlspecialchars($_POST['NoTransaksi']);
		// @$NoRefAmbil	= htmlspecialchars($_POST['NoRefAmbil']);
		@$Keterangan	= htmlspecialchars($_POST['Keterangan']);
		@$IsDiambil		= htmlspecialchars($_POST['IsDiambil']);
	
	//Simpan Transaksi Sidang Tera
	if(isset($_POST['SimpanTransaksi'])){
			// membuat id otomatis
		$sql = @mysqli_query($koneksi, "SELECT RIGHT(NoRefAmbil,8) AS kode FROM tractiontimbangan ORDER BY NoRefAmbil DESC LIMIT 1"); 
		$nums = mysqli_num_rows($sql);
		if($nums <> 0){
			 $data = mysqli_fetch_array($sql);
			 $kode = $data['kode'] + 1;
		}else{
			 $kode = 1;
		}
		//mulai bikin kode
		 $bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
		 $kode_jadi = "AML-".$Tanggal."-".$bikin_kode;
		//update 
		$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET UserAmbil='$login_id',TglAmbil='$TanggalTransaksi', NoRefAmbil='$kode_jadi', KeteranganAmbil='$Keterangan', IsDiambil='$IsDiambil', StatusTransaksi='SELESAI' WHERE NoTransaksi='$NoTransaksi'");
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Transaksi Pengembalian Timbangan User ', $login_id, $NoTransaksi, 'Transaksi Pengembalian Timbangan User');
			echo '<script language="javascript">document.location="TrPengambilan.php";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrPengambilan.php";
			  });
			  </script>';
		}
	}
	
	?>
  </body>
</html>