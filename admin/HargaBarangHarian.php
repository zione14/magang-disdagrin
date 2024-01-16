<?php
include '../admin/akses.php';
$Page = 'MasterData';
$SubPage='MasterBarang';
// $fitur_id = 3;
// include '../library/lock-menu.php';
include '../library/tgl-indo.php';

$KodeBarang = '';
$KodePasar = isset($_GET['psr']) ? mysqli_escape_string($koneksi, base64_decode($_GET['psr'])) : "";

if(isset($_GET['k']) && $_GET['k'] != ''){
	$KodeBarang = mysqli_escape_string($koneksi, base64_decode($_GET['k']));
	$sql = "SELECT b.*, g.NamaGroup FROM mstbarangpokok b LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup WHERE KodeBarang = '$KodeBarang'";
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
					location.href="HargaBarangHarian.php";
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
				location.href="HargaBarangHarian.php";
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
	<!-- Maps -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBqH_ctOCgwu5RLMrH6VdhCBLorpJXaDk&libraries=places"></script>
	<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
	
	<style>
		th {
			text-align: center;
		}

		.hidden {
			display: none;
			visibility: hidden;
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
					<h2 class="no-margin-bottom">Harga Barang Harian</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="col-lg-12">
						<ul class="nav nav-pills">
							<?php if ($cek_fitur['ViewData'] =='1'){ ?>
								<li>
									<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Harga <?php echo $RowData['NamaBarang']; ?></span></a>&nbsp;
								</li>
								<li>
									<a href="#home-sub" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>&nbsp;
								</li>
							<?php } ?>
							<li>
								<?php if ($cek_fitur['PrintData'] =='1'){ ?>
									<li>
										<a href="../library/html2pdf/cetak/MasterTimbangan.php" title='Print Data' target="_BLANK"><span class="btn btn-primary">Cetak Data Barang</span></a>

									</li>
								<?php } ?>
							</ul>
						</div>
						<br>
						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>" id="home-pills">
								<div class="card">
									<div class="card-header d-flex align-items-center">
										<h3 class="h4">Data Harga <?php echo $RowData['NamaBarang']; ?></h3>
									</div>
									<div class="card-body">
										<form action="">
											<div class="row">
												<div class="col-md-12">
													<input type="hidden" name="k" value="<?php echo base64_encode($RowData['KodeBarang']); ?>">
													<label class="form-control-label">Pencarian</label>
												</div>
												<div class="col-md-4">
													<select class="form-control" name="psr">
														<option class="form-control" value="" selected>Semua Pasar</option>
														<?php 
														$sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
														$res_p = $koneksi->query($sql_p);
														while ($row_p = $res_p->fetch_assoc()) {
															if($KodePasar === $row_p['KodePasar']){
																echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
															}else{
																// if(isset($KodePasar) && strlen($KodePasar) < 1){
																// 	$KodePasar = $row_p['KodePasar'];
																// }
																echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
															}
														}
														?>
													</select>
												</div>
												<div class="col-md-4 text-right">
													<div class="input-group mb-3">
														<input name="v" type="text" class="form-control" placeholder="Cari" value="<?php echo isset($_GET['v']) ? $_GET['v'] : '' ?>">
														<div class="input-group-append">
															<button class="btn btn-primary" type="submit">Cari</button>
														</div>
													</div>
												</div>
												<div class="col-md-4 float-right text-right">
													<a href="EntryHarga.php?brg=<?php echo base64_encode($RowData['KodeBarang']); ?>&psr=<?php echo isset($KodePasar) ? base64_encode($KodePasar) : ""; ?>" class="btn btn-info"><i class="fa fa-fw fa-edit"></i> Tambah Laporan Harga</a>
												</div>
												<div class="col-lg-12">
													<div class="table-responsive">  
														<table class="table table-striped">
															<thead>
																<tr>
																	<th class="text-left">No</th>
																	<th class="text-left">Tanggal</th>
																	<th class="text-right">Harga Barang</th>
																	<th class="text-right">Harga Produsen</th>
																	<th class="text-center">Ketersediaan</th>
																	<th class="text-left">Nama Pasar</th>
																</tr>
															</thead>
															<tbody>
																<?php
																include '../library/pagination1.php';
																$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
																$reload = "HargaBarangHarian.php?pagination=true&view=1&v=".$value;
																if(isset($_GET['v']) && $_GET['v'] != ''){
																	if(strlen($KodePasar) > 0){
																		$sql =  "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
																	r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
																   FROM reporthargaharian r
																   INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
																   INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
																   LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
																   LEFT JOIN (
																	   SELECT *
																	   FROM reporthargaharian k
																	   ORDER BY k.Tanggal DESC
																   ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
																   WHERE r.KodePasar = '".$KodePasar."' AND r.KodeBarang = '".$RowData['KodeBarang']."' AND (b.NamaBarang LIKE '%$value%' OR b.Merk LIKE '%$value%' OR g.NamaGroup LIKE '%$value%')
																	GROUP BY r.KodeBarang, r.KodePasar, r.Tanggal
																   ORDER BY r.Tanggal DESC";
																	}else{
																		$sql =  "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin, r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
																   FROM reporthargaharian r
																   INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
																   INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
																   LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
																   LEFT JOIN (
																	   SELECT *
																	   FROM reporthargaharian k
																	   ORDER BY k.Tanggal DESC
																   ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
																   WHERE r.KodeBarang = '".$RowData['KodeBarang']."' AND (b.NamaBarang LIKE '%$value%' OR b.Merk LIKE '%$value%' OR g.NamaGroup LIKE '%$value%')
																	GROUP BY r.KodeBarang, r.KodePasar, r.Tanggal
																   ORDER BY r.Tanggal DESC";
																	}
																}else{
																	if(strlen($KodePasar) > 0){
																		$sql =  "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
																		r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
																	FROM reporthargaharian r
																	INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
																	INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
																	LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
																	LEFT JOIN (
																		SELECT *
																		FROM reporthargaharian k
																		ORDER BY k.Tanggal DESC
																	) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
																	WHERE r.KodePasar = '".$KodePasar."' AND r.KodeBarang = '".$RowData['KodeBarang']."'
																	GROUP BY r.KodeBarang, r.KodePasar, r.Tanggal
																	ORDER BY r.Tanggal DESC";
																	}else{
																		$sql =  "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
																		r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
																	FROM reporthargaharian r
																	INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
																	INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
																	LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
																	LEFT JOIN (
																		SELECT *
																		FROM reporthargaharian k
																		ORDER BY k.Tanggal DESC
																	) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
																	WHERE  r.KodeBarang = '".$RowData['KodeBarang']."'
																	GROUP BY r.KodeBarang, r.KodePasar, r.Tanggal
																	ORDER BY r.Tanggal DESC";
																	}
																}
																// echo $sql;
																$result = mysqli_query($koneksi,$sql);
																$rpp = 20;
																$page = intval(@$_GET["page"]);
																if($page<=0) $page = 1;  
																$tcount = mysqli_num_rows($result);
																$tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
																$count = 0;
																$i = ($page-1)*$rpp;
																$no_urut = ($page-1)*$rpp;
																while(($count<$rpp) && ($i<$tcount)) {
																	mysqli_data_seek($result,$i);
																	$data = mysqli_fetch_array($result);
																	?>
																	<tr class="odd gradeX">
																		<td width="50px">
																			<?php echo ++$no_urut;?> 
																		</td>
																		<td>
																			<?php echo date('d/F/Y H:i:s', strtotime($data ['Tanggal']));?>
																		</td>
																		<td class="text-right">
																			<?php echo 'Rp.'.number_format($data ['HargaBarang']);?>
																		</td>
																		<td class="text-right">
																			<?php echo 'Rp.'.number_format($data ['HargaProdusen']);?>
																		</td>
																		<td class="text-center">
																			<?php echo number_format($data ['Ketersediaan']).' '.$data ['Satuan'];?>
																		</td>
																		<td>
																			<?php echo $data ['NamaPasar'];?>
																		</td>
																	</tr>
																	<?php
																	$i++; 
																	$count++;
																}

																if($tcount==0){
																	echo '
																	<tr>
																	<td colspan="4" align="center">
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
										</form>
									</div>
								</div>
							</div>
							<div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="home-sub">
								<div class="card col-lg-12">
									<div class="card-header d-flex align-items-center">
										<h3 class="h4">Tambah Data Barang</h3>
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
														<label class="form-control-label">Merk</label>
														<input type="text" placeholder="Merk" class="form-control" name="txtMerk" value="<?php echo isset($RowData['Merk']) ? $RowData['Merk'] : ''; ?>" required>
													</div>
													<div class="form-group">
														<label class="form-control-label">Satuan</label>
														<input type="text" placeholder="Satuan" class="form-control" name="txtSatuan" value="<?php echo isset($RowData['Satuan']) ? $RowData['Satuan'] : ''; ?>" required>
													</div>		
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-control-label">Grup</label>
														<select class="form-control" name="cbGrup" id="cbGrup" required>
															<option value="" selected disabled>Pilih Grup</option>
															<?php 
															$sql_g = "SELECT * FROM mstgroupbarang ORDER BY NamaGroup ASC";
															$res_g = $koneksi->query($sql_g);
															while ($row = mysqli_fetch_assoc($res_g)) {
																if(isset($RowData['KodeGroup']) && $row['KodeGroup'] == $RowData['KodeGroup']){
																	echo '<option value="'.$row['KodeGroup'].'" selected>'.$row['NamaGroup'].'</option>';
																}else{
																	echo '<option value="'.$row['KodeGroup'].'">'.$row['NamaGroup'].'</option>';
																}
															}
															?>
														</select>
													</div>
													<div class="form-group">
														<label class="form-control-label">Keterangan</label>
														<input type="text" placeholder="Keterangan" class="form-control" name="txtKeterangan" value="<?php echo isset($RowData['Keterangan']) ? $RowData['Keterangan'] : ''; ?>" required>
													</div>
													<div class="form-group">
														<label class="form-control-label">Status</label>
														<div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" class="custom-control-input" id="defaultInline1" name="rbIsAktif" value="1" <?php echo isset($RowData['IsAktif']) && $RowData['IsAktif'] == '1' ? 'checked' : '' ?> required>
																<label class="custom-control-label" for="defaultInline1">Aktif</label>
															</div>
															<div class="custom-control custom-radio custom-control-inline">
																<input type="radio" class="custom-control-input" id="defaultInline2" name="rbIsAktif" value="0" <?php echo isset($RowData['IsAktif']) && $RowData['IsAktif'] == '0' ? 'checked' : '' ?> required>
																<label class="custom-control-label" for="defaultInline2">Tidak Aktif</label>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<button type="submit" class="btn btn-primary">Simpan</button>
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
		var Satuan = $("[name='txtSatuan']").val();
		var Keterangan = $("[name='txtKeterangan']").val();
		var KodeGroup = $("[name='cbGrup']").val();
		var IsAktif = $("input[name='rbIsAktif']:checked").val();

		var formData = new FormData();
		formData.append("KodeBarang", KodeBarang);
		formData.append("NamaBarang", NamaBarang);
		formData.append("Merk", Merk);
		formData.append("Satuan", Satuan);
		formData.append("Keterangan", Keterangan);
		formData.append("KodeGroup", KodeGroup);
		formData.append("IsAktif", IsAktif);
		formData.append("action", 'SimpanBarang');
		$.ajax({
			url: "aksi/AksiBarang.php",
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
						window.location.href = "HargaBarangHarian.php";
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
			url: "aksi/AksiBarang.php",
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