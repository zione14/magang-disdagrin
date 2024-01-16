<?php
include '../admin/akses.php';
$Page = 'MasterData';
$SubPage='MasterPasar';
$fitur_id = 19;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php include '../admin/title.php';?>
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
	<?php include '../admin/style.php';?>
	<!-- Custom stylesheet - for your changes-->
	<link rel="stylesheet" href="../komponen/css/custom.css">
	
</head>
<body>
	<div class="page">
		<!-- Main Navbar-->
		<?php include 'header.php'; ?>
		<div class="page-content d-flex align-items-stretch"> 
			<!-- Side Navbar -->
			<?php include 'menu.php';?>
			<div class="content-inner">
				<!-- Page Header-->
				<header class="page-header">
					<div class="container-fluid">
						<h2 class="no-margin-bottom">Master Pasar</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
								<div class="card-header">
									<h4>Data Pasar</h4>
								</div>
								<div class="card-body">
									<a href="MasterPasar-tambah.php" class="btn btn-sm btn-primary">Tambah Pasar</a>
									<div class="table-responsive" style="margin-top:10px">
										<table class="table table-stripped">
											<thead>
												<tr>
													<th>No.</th>
													<th>Nama Pasar</th>
													<th>Nama Kep. Pasar</th>
													<th>No. Telp</th>
													<th>Alamat Lengkap</th>
													<th class="text-right">Aksi</th>
												</tr>
											</thead>
											<?php
												include '../library/pagination1.php';
												$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
												$reload = "MasterPasar.php?pagination=true&view=1&v=".$value;
												if(isset($_GET['v']) && $_GET['v'] != ''){
													$sql =  "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa, COUNT(DISTINCT u.UserName) AS JumlahPetugas
													FROM mstpasar p
													INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
													INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
													INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
													WHERE (p.NamaPasar LIKE '%$value%' OR p.NamaKepalaPasar LIKE '%$value%')
													LEFT JOIN userlogin u ON u.KodePasar = p.KodePasar
													GROUP BY p.KodePasar
													ORDER BY p.NamaPasar ASC";
												}else{
													$sql =  "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa, COUNT(DISTINCT u.UserName) AS JumlahPetugas
													FROM mstpasar p
													INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
													INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
													INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
													LEFT JOIN userlogin u ON u.KodePasar = p.KodePasar
													GROUP BY p.KodePasar
													ORDER BY p.NamaPasar ASC";	
												}

												$result = mysqli_query($koneksi,$sql);
												$rpp = 20;
												$page = intval(@$_GET["page"]);
												if($page<=0) $page = 1;  
												$tcount = mysqli_num_rows($result);
												$tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
												$count = 0;
												$i = ($page-1)*$rpp;
												$no_urut = ($page-1)*$rpp;			
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
															<?php echo $data ['NamaPasar'];?>
														</td>
														<td>
															<?php echo $data ['NamaKepalaPasar'];?>
														</td>
														<td>
															<?php echo $data ['NoTelpPasar'];?>
														</td>
														<td>
															<?php echo ucwords(strtolower($data['NamaDesa'].', '.$data['NamaKecamatan'].', '.$data['NamaKabupaten'])); ?>
														</td>
														<td class="text-right">										
															<?php
															if ($cek_fitur['EditData'] =='1'){ 
																echo '<a href="MasterPasar-dokumen.php?k='.base64_encode($data['KodePasar']).'&view=2" title="Dokumen Pasar"><i class="btn btn-success btn-sm"><span class="fa fa-file"></span></i></a> ';
															}
															if ($cek_fitur['EditData'] =='1'){ 
																echo '<a href="MasterPasar-tambah.php?k='.base64_encode($data['KodePasar']).'&view=2" title="Ubah"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a> ';
															}
															if ($cek_fitur['DeleteData'] =='1'){
																echo '<button id="btnHapus" value="'.$data['KodePasar'].'" class="btn btn-danger btn-sm" title="Hapus" ><span class="fa fa-trash"></span></button>';
															}
															?>
															<a href="PetugasPasar.php?id=<?php echo base64_encode($data['KodePasar']); ?>" class="btn btn-sm btn-info"><i class="fa fa-fw fa-users"></i> petugas (<?php echo $data['JumlahPetugas']; ?>)</a>
														</td>
													</tr>
													<?php
													$i++; 
													$count++;
												}

												if($tcount==0){
													echo '
													<tr>
														<td colspan="5" align="center">
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
				</section>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>
	<script>
	
	$(document).ready(function () {
		$(document).on('click', '#btnHapus', function () {
			var kodePasar = $(this).val();
			swal({
				title: "Peringatan",
				text: "Apakah Anda yakin menghapus data tersebut.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					HapusData(kodePasar);              
				}
			});
		});
	});

	function HapusData(kodePasar){
		console.log(kodePasar);
		var action = "HapusPasar";
		$.ajax({
			url: "aksi/AksiPasar.php",
			method: "POST",
			data: {action: action, KodePasar: kodePasar},
			dataType: 'json',
			success: function (data) {
				if(data.response == 200){
					swal({
						title: "Berhasil", 
						text: "Berhasil menghapus data."+data.response, 
						icon: "success", 
						allowOutsideClick: false
					}).then(function() {
						location.reload();
					});
				}else{
					swal('Gagal menghapus data, Karena Sudah digunakan transaksi.');
				}               
			}
		});
	}
	</script>
</body>