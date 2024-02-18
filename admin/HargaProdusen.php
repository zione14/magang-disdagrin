<?php
include '../admin/akses.php';
$Page = 'HargaProdusen';
$fitur_id = 23;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$TanggalKemarin = date('Y-m-d', strtotime('-1 days', strtotime($Tanggal)));
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
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
	<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
	<link
	rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
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
					<h2 class="no-margin-bottom">Harga Produsen</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">		
							<form action="">			
								<div class="row">
									<div class="col-md-4">
										<label class="form-control-label">Tanggal</label>
										<input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Tanggal; ?>">
									</div>
									<div class="col-md-4">
										<label class="form-control-label">Pencarian</label>
										<select class="form-control" name="psr">
											<!-- <option class="form-control" value="" selected>Semua Pasar</option> -->
											<?php 
											$sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
											$res_p = $koneksi->prepare($sql_p);
											$res_p->execute();
											$resulld = $res_p->get_result();
											while ($row_p = $resulld->fetch_assoc()) {
												if(isset($KodePasar) && $KodePasar === $row_p['KodePasar']){
													echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
												}else{
													if(!isset($KodePasar) || strlen($KodePasar) < 1 ){
														$KodePasar = $row_p['KodePasar'];
													}
													echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
												}
											}
											?>
										</select>
										
									</div>
									<div class="col-md-4">
										<label class="form-control-label">Pencarian</label>
										<div class="input-group mb-3">
											<input id="v" name="v" type="text" class="form-control" placeholder="Pencarian" value="<?php echo isset($_GET['v']) ? $_GET['v'] : '' ?>">
											<div class="input-group-append">
												<button class="btn btn-primary" type="submit">Cari</button>
											</div>
										</div>
									</div>
								</div>
							</form>
							<div class="col-lg-12">
								<div class="table-responsive">  
									<table class="table">
										<thead>
											<tr>
												<th class="text-left">No</th>
												<th class="text-left">Nama Bahan Pokok</th>
												<th class="text-center">Satuan</th>
												<th class="text-right">Harga Kemarin</th>
												<th class="text-right">Harga Sekarang</th>
												<th class="text-right">Perubahan (Rp)</th>
												<th class="text-right">Perubahan (%)</th>
												<th class="text-right">##</th>
											</tr>
										</thead>
										<tbody>
											<?php
											include '../library/pagination1.php';
											$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
											$reload = "HargaProdusen.php?psr=".base64_encode($KodePasar)."&brg=".base64_encode($KodeBarang)."&pagination=true&view=1&v=".$value;
											if(isset($_GET['v']) && $_GET['v'] != ''){
												$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
												IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, 
												IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
												IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
												IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
												IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal,
												IFNULL(hppkemarin1.HargaBarang, 0) AS HargaBarangKemarin1, 
												IFNULL(hppkemarin1.HargaProdusen, 0) AS HargaProdusenKemarin1, 
												IFNULL(hppkemarin2.HargaBarang, 0) AS HargaBarangKemarin2, 
												IFNULL(hppkemarin2.HargaProdusen, 0) AS HargaProdusenKemarin2 
												FROM mstbarangpokok b
												LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
												LEFT JOIN (
												SELECT *
												FROM reporthargaharian r
												WHERE r.KodePasar = '$KodePasar' AND DATE(r.Tanggal) = DATE_SUB(date('$Tanggal'), INTERVAL 1 DAY)
												GROUP BY r.Tanggal
												ORDER BY r.Tanggal ASC
												) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
												LEFT JOIN (
												SELECT *
												FROM reporthargaharian r
												WHERE r.KodePasar = '$KodePasar' AND DATE(r.Tanggal) = DATE_SUB(date('$Tanggal'), INTERVAL 2 DAY)
												GROUP BY r.Tanggal
												ORDER BY r.Tanggal ASC
												) hppkemarin1 ON hppkemarin1.KodeBarang = b.KodeBarang
												LEFT JOIN (
												SELECT *
												FROM reporthargaharian r
												WHERE r.KodePasar = '$KodePasar' AND DATE(r.Tanggal) = DATE_SUB(date('$Tanggal'), INTERVAL 3 DAY)
												GROUP BY r.Tanggal
												ORDER BY r.Tanggal ASC
												) hppkemarin2 ON hppkemarin2.KodeBarang = b.KodeBarang
												LEFT JOIN (
												SELECT *
												FROM reporthargaharian r
												WHERE r.KodePasar = '$KodePasar' AND DATE(r.Tanggal) = date('$Tanggal')
												GROUP BY r.Tanggal
												ORDER BY r.Tanggal ASC
												) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
												WHERE b.IsAktif = '1' AND b.NamaBarang LIKE '%$value%'
												GROUP BY b.KodeBarang
												ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
											}else{
												$reload = "tabel-hargakonsumen.php?psr=".base64_encode($KodePasar)."&tgl=".$Tanggal;
                    $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal
					FROM mstbarangpokok b
					LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
					LEFT JOIN reporthargaharian hppkemarin on hppkemarin.KodeBarang = b.KodeBarang and hppkemarin.KodePasar = '".$KodePasar."' AND DATE(hppkemarin.Tanggal) = DATE('".$TanggalKemarin."')
					LEFT JOIN reporthargaharian hppsekarang on hppsekarang.KodeBarang = b.KodeBarang and hppsekarang.KodePasar = '".$KodePasar."' AND DATE(hppsekarang.Tanggal) = DATE('".$Tanggal."') 
					WHERE b.IsAktif = '1'
					GROUP BY b.KodeBarang
					ORDER BY b.KodeGroup ASC, b.KodeBarang ASC"; 
											}
											$result = $koneksi->prepare($sql);
											$result->execute();
											$resulkk = $result->get_result();
											$rpp = 20;
											$page = intval(@$_GET["page"]);
											if($page<=0) $page = 1;  
											$tcount = mysqli_num_rows($resulkk);
											$tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
											$count = 0;
											$i = ($page-1)*$rpp;
											$no_urut = ($page-1)*$rpp;
											$kodegroup = "";
											while(($count<$rpp) && ($i<$tcount)) {
												mysqli_data_seek($resulkk,$i);
												$data = mysqli_fetch_array($resulkk);
												if($kodegroup !== $data['KodeGroup']){
													echo '<tr style="background:#f7f7f7;"><td width="50px"></td>
													<td colspan="7"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
													</tr>';
													$kodegroup = $data['KodeGroup'];
												}
												?>
												<tr class="odd gradeX">
                            <td width="50px">
                                <?php echo ++$no_urut;?> 
                            </td>
                            <td>
                                <?php echo $data ['NamaBarang'];?>
                            </td>
                            <th>
                                <?php echo $data ['Satuan'];?>
                            </th>
                            <td class="text-right">
                                <?php echo 'Rp.'.number_format($data ['HargaProdusenKemarin']);?>
                            </td>
                            <td class="text-right">
                                <?php echo 'Rp.'.number_format($data ['HargaProdusenSekarang']);?>
                            </td>
                            <?php
                            $class_naik = '';
                            $icon_ = '';
                            ?>
                            <td class="text-right">
                                <?php
                                $dataselisi = ""; 
                                $selisi =  $data ['HargaProdusenSekarang'] < 1 ? 0 : $data ['HargaProdusenSekarang'] - $data ['HargaProdusenKemarin'];
                                if($selisi < 0){
                                    $icon_ = '<i class="fa fa-fw fa-chevron-down"></i>';
                                    $tmpselisi = $selisi * -1;
                                    $class_naik = 'text-success';
                                    $dataselisi = "- Rp.".number_format($tmpselisi);
                                }elseif($selisi == 0){
                                    $icon_ = '<i class="fa fa-fw fa-minus"></i>';
                                    $class_naik = '';
                                    $dataselisi = '--';
                                }else{
                                    $icon_ = '<i class="fa fa-fw fa-chevron-up"></i>';
                                    $class_naik = 'text-danger';
                                    $dataselisi = "Rp.".number_format($selisi);
                                }
                                echo '<p class="'.$class_naik.'">'.$dataselisi.'</p>'; ?>
                            </td>
                            <td class="text-right">
                                <?php 
                                if($data['HargaProdusenSekarang'] == 0 && $data['HargaProdusenKemarin'] == 0 ){
                                    echo '<p class="'.$class_naik.'">-- '.$icon_.'</p>';
                                }else{
                                    $persenSelisi = 0;
                                    if($selisi == $data['HargaProdusenSekarang']){
                                        $persenSelisi = 100;
                                    }else if($selisi == 0){
                                        $persenSelisi = 0;
                                    }else{
                                        $persenSelisi = ($selisi / $data['HargaProdusenKemarin']) * 100;
                                    }
                                    echo '<p class="'.$class_naik.'">'.number_format($persenSelisi, 2).' % '.$icon_.'</p>';
                                }
                                ?>
                            </td>
													<td class="float-right">
														<a href="EntryHarga.php?brg=<?php echo base64_encode($data['KodeBarang']); ?>&psr=<?php echo base64_encode($KodePasar); ?>" class="btn btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
													</td>
												</tr>
												<?php
												$i++; 
												$count++;
											}

											if($tcount==0){
												echo '
												<tr>
												<td colspan="7" align="center">
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
<script type="text/javascript">
	$(document).ready(function() {
		$('#tgl').Zebra_DatePicker({format: 'Y-m-d'});
	});
</script>