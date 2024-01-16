<?php
include '../admin/akses.php';
$Page = 'MasterPupuk';
$fitur_id = 40;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';

$KodeBarang = '';

if(isset($_GET['k']) && $_GET['k'] != ''){
	$KodeBarang = mysqli_escape_string($koneksi, base64_decode($_GET['k']));
	$sql = "SELECT NamaBarang,Harga,Keterangan,KodeBarang,IsAktif FROM mstpupuksubsidi WHERE KodeBarang = '$KodeBarang'";
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
					location.href="MasterPupuk.php";
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
				location.href="MasterPupuk.php";
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
					<h2 class="no-margin-bottom">Master Pupuk Subsidi</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="col-lg-12">
						<ul class="nav nav-pills">
							<?php if ($cek_fitur['ViewData'] =='1'){ ?>
								<li>
									<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Barang Pupuk</span></a>&nbsp;
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
										<h3 class="h4">Data Barang Pupuk</h3>
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
																<th class="text-left">Nama Barang</th>
																<!-- <th class="text-left">Harga</th> -->
																<th class="text-left">Satuan</th>
																<th class="text-right">Aksi</th>
															</tr>
														</thead>
														<?php
														include '../library/pagination1.php';
														$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
														$reload = "MasterPupuk.php?pagination=true&view=1&v=".$value;
														if(isset($_GET['v']) && $_GET['v'] != ''){
															$sql =  "SELECT NamaBarang,Harga,KodeBarang,Keterangan FROM mstpupuksubsidi
															WHERE (NamaBarang LIKE '%$value%' OR Harga LIKE '%$value%') and IsAktif=b'1'
															ORDER BY NamaBarang ASC";
														}else{
															$sql =  "SELECT NamaBarang,Harga,KodeBarang,Keterangan  FROM mstpupuksubsidi where  IsAktif=b'1'  ORDER BY NamaBarang ASC";
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
																		<?php echo $data ['NamaBarang'];?>
																	</td>
																	<!-- <td>
																		<?php echo "Rp.".number_format($data ['Harga']);?>
																	</td> -->
																	<td>
																		<?php echo $data ['Keterangan'];?>
																	</td>
																	<td class="text-right">
																		<?php
																		
																		if ($cek_fitur['EditData'] =='1'){ 
																			echo '
																			<a href="MasterPupuk.php?k='.base64_encode($data['KodeBarang']).'&view=2" title="Hapus"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
																		}
																		if ($cek_fitur['DeleteData'] =='1'){ 
																			echo '
																			<button id="btnHapus" value="'.$data['KodeBarang'].'" class="btn btn-danger btn-sm" title="Hapus" ><span class="fa fa-trash"></span></button>';
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
										<h3 class="h4">Tambah Data Barang Pupuk</h3>
									</div>
									<div class="card-body">
										<form id="form_Barang" method="post" action="">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-control-label">Nama Barang</label>
														<input type="text" placeholder="Nama Barang" class="form-control" name="txtNamaBarang" value="<?php echo isset($RowData['NamaBarang']) ? $RowData['NamaBarang'] : ''; ?>" required>
													</div>
													<div class="form-group">
														<!-- <label class="form-control-label">Harga</label> -->
														<input type="hidden" placeholder="Harga" class="form-control" name="txtMerk" value="0" required>
													</div>
													<div class="form-group">
														<label class="form-control-label">Satuan</label>
														<input type="text" placeholder="Satuan" class="form-control" name="txtKeterangan" value="TON" required>
													</div>
												</div>
												<div class="col-md-12">
													<button type="submit" class="btn btn-primary">Simpan</button>
													<a href="MasterPupuk.php" class="btn btn-warning">Kembali</a>
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

	var KodeBarang;

	$(document).ready(function () {
		KodeBarang = "<?php echo $KodeBarang; ?>";
	});

	$("#form_Barang").submit(function(e) {
		e.preventDefault();
		var NamaBarang = $("[name='txtNamaBarang']").val();
		var Merk = $("[name='txtMerk']").val();
		var Keterangan = $("[name='txtKeterangan']").val();
		var IsAktif = $("input[name='rbIsAktif']:checked").val();

		var formData = new FormData();
		formData.append("KodeBarang", KodeBarang);
		formData.append("NamaBarang", NamaBarang);
		formData.append("Merk", Merk);
		formData.append("Keterangan", Keterangan);
		// formData.append("IsAktif", IsAktif);
		formData.append("action", 'SimpanBarang');
		$.ajax({
			url: "aksi/AksiPupuk.php",
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
						window.location.href = "MasterPupuk.php";
					});
				} else {
					swal('Error', data.msg,'warning');
				}
			}
		});
	});

	$(document).on('click', '#btnHapus', function () {
		var kodeBarang = $(this).val();
		swal({
			title: "Peringatan",
			text: "Apakah Anda yakin menghapus data tersebut.",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				HapusData(kodeBarang);              
			}
		});
	});

	function HapusData(kodeBarang){
		var action = "HapusBarang";
		$.ajax({
			url: "aksi/AksiPupuk.php",
			method: "POST",
			data: {action: action, KodeBarang: kodeBarang},
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