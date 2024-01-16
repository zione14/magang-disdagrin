<?php
include '../admin/akses.php';
$Page = 'MasterDistribusi';
$fitur_id = 41;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';

$IDPerson = '';

if(isset($_GET['k']) && $_GET['k'] != ''){
	$IDPerson = mysqli_escape_string($koneksi, base64_decode($_GET['k']));
	$sql = "SELECT NamaPerson,IDPerson,JenisPerson,AlamatLengkapPerson,NoHP,UserName,Password,IsVerified FROM mstperson WHERE IDPerson = '$IDPerson'";
	$res_select = $koneksi->query($sql);
	$RowData = array();
	if($res_select){
		if(mysqli_num_rows($res_select) < 1){
			?>
			<script type="text/javascript">
				swal({
					title: "Error", 
					text: "Data tidak ditemukan", 
					icon: "error", 
					allowOutsideClick: false
				}).then(function() {
					location.href="MasterDistribusi.php";
				});
			</script>
			<?php
		}else{
			$RowData = mysqli_fetch_assoc($res_select);
		}
	}else{
		?>
		<script type="text/javascript">
			swal({
				title: "Error", 
				text: "Terjadi kesalahan", 
				icon: "error", 
				allowOutsideClick: false
			}).then(function() {
				location.href="MasterDistribusi.php";
			});
		</script>
		<?php
	}
}
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
	
	<style>
		th {
			text-align: center;
		}

		.hidden {
			display: none;
			visibility: hidden;
		}
		
	</style>
</head>
<body>
</body>
<div class="page">
	<!-- Main Navbar-->
	<?php 
	include 'header.php'; ?>
	<div class="page-content d-flex align-items-stretch"> 
		<!-- Side Navbar -->
		<?php include 'menu.php';?>
		<div class="content-inner">
			<!-- Page Header-->
			<header class="page-header">
				<div class="container-fluid">
					<h2 class="no-margin-bottom">Master Data Distributor Pupuk</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="col-lg-12">
						<ul class="nav nav-pills">
							<?php if ($cek_fitur['ViewData'] =='1'){ ?>
								<li>
									<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Distributor</span></a>&nbsp;
								</li>
								<li>
									<a href="#home-sub" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>&nbsp;
								</li>
							<?php } ?>
							<li>
								<?php if ($cek_fitur['PrintData'] =='1'){ ?>
									<!--<li>
										<a href="../library/html2pdf/cetak/MasterTimbangan.php" title='Print Data' target="_BLANK"><span class="btn btn-primary">Cetak Data Barang</span></a>

									</li>-->
								<?php } ?>
							</ul>
						</div>
						<br>
						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>" id="home-pills">
								<div class="card">
									<div class="card-header d-flex align-items-center">
										<h3 class="h4">Data Distributor Pupuk</h3>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6 text-right">
												<form action="">
													<div class="input-group mb-3">
														<input name="v" type="text" class="form-control" placeholder="Cari" value="<?php echo isset($_GET['v']) ? $_GET['v'] : '' ?>">
														<div class="input-group-append">
															<button class="btn btn-primary" type="submit">Cari</button>
														</div>
													</div>
												</form>
											</div>
											<div class="col-md-12">
												<div class="table-responsive">  
													<table class="table table-striped">
														<thead>
															<tr>
																<th class="text-left">No</th>
																<th class="text-left">Nama Distributor</th>
																<th class="text-left">Alamat</th>
																<th class="text-left">NoHP</th>
																<th class="text-left">Status</th>
																<th class="text-right">Aksi</th>
															</tr>
														</thead>
														<?php
														include '../library/pagination1.php';
														$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
														$reload = "MasterDistribusi.php?pagination=true&view=1&v=".$value;
														if(isset($_GET['v']) && $_GET['v'] != ''){
															$sql =  "SELECT NamaPerson,IDPerson,JenisPerson,AlamatLengkapPerson,NoHP,IsVerified  FROM mstperson
															WHERE (NamaPerson LIKE '%$value%' OR AlamatLengkapPerson LIKE '%$value%' OR JenisPerson LIKE '%$value%') AND JenisPerson='Distributor' AND KlasifikasiUser='Distributor Pupuk' 
															ORDER BY NamaPerson ASC";
														}else{
															$sql =  "SELECT NamaPerson,IDPerson,JenisPerson,AlamatLengkapPerson,NoHP,IsVerified  FROM mstperson WHERE JenisPerson='Distributor' AND KlasifikasiUser='Distributor Pupuk' ORDER BY NamaPerson ASC";
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
																		<?php echo $data ['NamaPerson'];?>
																	</td>
																	<td>
																		<?php echo $data ['AlamatLengkapPerson'];?>
																	</td>
																	<td>
																		<?php echo $data ['NoHP'];?>
																	</td>
																	<td>
																		<?php echo $data['IsVerified'] == '1' ? '<span class="text-success">AKTIF</span>' : '<span class="text-danger">NONAKTIF</span>'; ?>
																	</td>
																	<td class="text-right">
																		<?php
																		
																		if ($cek_fitur['EditData'] =='1'){ 
																			echo '
																			<a href="MasterDistribusi.php?k='.base64_encode($data['IDPerson']).'&view=2" title="Hapus"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
																		}
																		if ($cek_fitur['DeleteData'] =='1'){ 
																			echo '
																			<button id="btnHapus" value="'.$data['IDPerson'].'" class="btn btn-danger btn-sm" title="Hapus" ><span class="fa fa-trash"></span></button>';
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
							<div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="home-sub">
								<div class="card col-lg-12">
									<div class="card-header d-flex align-items-center">
										<h3 class="h4">Tambah Data Distributor Pupuk</h3>
									</div>
									<div class="card-body">
										<form id="form_Barang" method="post" action="">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-control-label">Nama Distributor</label>
														<input type="text" placeholder="Nama Distributor" class="form-control" name="txtNamaPerson" value="<?php echo isset($RowData['NamaPerson']) ? $RowData['NamaPerson'] : ''; ?>" required>
													</div>
													<div class="form-group">
														<label class="form-control-label">Alamat Lengkap</label>
														<input type="text" placeholder="Alamat Lengkap" class="form-control" name="txtAlamat" value="<?php echo isset($RowData['AlamatLengkapPerson']) ? $RowData['AlamatLengkapPerson'] : ''; ?>" required>
													</div>
													<div class="form-group">
														<label class="form-control-label">No HandPhone</label>
														<input type="text" placeholder="No HandPhone" class="form-control" name="txtNoHP" value="<?php echo isset($RowData['NoHP']) ? $RowData['NoHP'] : ''; ?>" required>
													</div>		
												</div>
												<div class="col-md-6">
													
													<!--<div class="form-group">
														<label class="form-control-label">Keterangan</label>
														<input type="text" placeholder="Keterangan" class="form-control" name="txtKeterangan" value="<?php echo isset($RowData['Keterangan']) ? $RowData['Keterangan'] : ''; ?>" required>
													</div>-->
													<div class="form-group">
														<label class="form-control-label">Username</label>
														<input type="text" placeholder="Username" class="form-control" name="txtUsername" value="<?php echo isset($RowData['UserName']) ? $RowData['UserName'] : ''; ?>" <?php echo isset($RowData['UserName']) ? 'readonly' : 'required'; ?>>
														<p><i>default password di set menjadi "123456"</i></p>
													</div>
													<div class="form-group">
														<label class="form-control-label">Status</label>
														<div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" class="custom-control-input" id="defaultInline1" name="rbIsAktif" value="1" <?php echo isset($RowData['IsVerified']) && $RowData['IsVerified'] == '1' ? 'checked' : '' ?> required>
																<label class="custom-control-label" for="defaultInline1">Aktif</label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" class="custom-control-input" id="defaultInline2" name="rbIsAktif" value="0" <?php echo isset($RowData['IsVerified']) && $RowData['IsVerified'] == '0' ? 'checked' : '' ?> required>
																<label class="custom-control-label" for="defaultInline2">Tidak Aktif</label>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<button type="submit" class="btn btn-primary">Simpan</button>
													<a href="MasterDistribusi.php" class="btn btn-warning">Kembali</a>
												</div>
											</div>
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
<?php include 'footer.php'; ?>
<script type="text/javascript">

	var IDPerson;

	$(document).ready(function () {
		IDPerson = "<?php echo $IDPerson; ?>";
	});

	$("#form_Barang").submit(function(e) {
		e.preventDefault();
		var NamaPerson = $("[name='txtNamaPerson']").val();
		var Alamat = $("[name='txtAlamat']").val();
		// var Keterangan = $("[name='txtKeterangan']").val();
		var NoHP = $("[name='txtNoHP']").val();
		var UserName = $("[name='txtUsername']").val();
		var IsAktif = $("input[name='rbIsAktif']:checked").val();

		var formData = new FormData();
		formData.append("IDPerson", IDPerson);
		formData.append("NamaPerson", NamaPerson);
		formData.append("Alamat", Alamat);
		formData.append("NoHP", NoHP);
		// formData.append("Keterangan", Keterangan);
		formData.append("UserName", UserName);
		formData.append("IsAktif", IsAktif);
		formData.append("action", 'SimpanDistributor');
		$.ajax({
			url: "aksi/AksiDistributor.php",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData:false,
			dataType: 'json',
			success: function (data) {
				if (data.response == 200) {
					$('#form_Barang')[0].reset();
					swal({
						title: "Berhasil", 
						text: "Berhasil menyimpan data.", 
						icon: "success", 
						allowOutsideClick: false
					}).then(function() {
						window.location.href = "MasterDistribusi.php";
					});
				} else if (data.response == 501){
                    swal('Perinngatan','Username tersebut telah digunakan','warning');
				}else {
					swal('Error', data.msg,'warning');
				}
			}
		});
	});

	$(document).on('click', '#btnHapus', function () {
		var idperson = $(this).val();
		swal({
			title: "Peringatan",
			text: "Apakah Anda yakin menghapus data tersebut.",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				HapusData(idperson);              
			}
		});
	});

	function HapusData(idperson){
		var action = "HapusBarang";
		$.ajax({
			url: "aksi/AksiDistributor.php",
			method: "POST",
			data: {action: action, IDPerson: idperson},
			dataType: 'json',
			success: function (data) {
				if(data.response = 200){
					swal({
						title: "Berhasil", 
						text: "Berhasil menghapus data.", 
						icon: "success", 
						allowOutsideClick: false
					}).then(function() {
						location.reload();
					});
				}else{
					swal('Gagal menghapus data\nCoba lagi.','error');
				}               
			}
		});
	}
</script>