<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='RataRata';
$fitur_id = 22;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$TanggalKemarin = date('Y-m-d', strtotime('-1 days', strtotime($Tanggal)));
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';

$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';
$tglSekarang = date('d-m-Y', strtotime($Tanggal));
$tglKemarin = date('d-m-Y', strtotime($TanggalKemarin));
$DSPLY = "";
if($display === "ketersediaan"){
	$DSPLY = "Jumlah Ketersediaan";
}elseif($display === "hprodusen"){
	$DSPLY = "Harga Produsen";
}else{
	$DSPLY = "Harga Konsumen";
}

// Perhitungan tanggal untuk stok atau persedian
// 2020-06-02
$tglpecah = date_create($Tanggal);
 $Tgl = date_format($tglpecah,"d");
 $Bln = date_format($tglpecah,"m");
 $Thn = date_format($tglpecah,"Y");

$Periode = $Thn.'-'.$Bln.'-'.$Tgl;
//Periode Sebelumnya
if($Tgl >= '01' && $Tgl <= '07'){
	$blnke = $Bln-1;
	if($Bln<=9){
		$PeriodeSebelumnya = $Thn.'-0'.$blnke.'-22';
	}else{
		$PeriodeSebelumnya = $Thn.'-'.$blnke.'-22';
	}
	$Periode = $Thn.'-'.$Bln.'-01';

}elseif($Tgl >= '22' && $Tgl <= '31'){

	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-15';
	$Periode = $Thn.'-'.$Bln.'-22';

}elseif($Tgl >= '08' && $Tgl <= '14'){

	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-01';
	$Periode = $Thn.'-'.$Bln.'-08';

}elseif($Tgl >= '15' && $Tgl <= '21'){
	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-08';
	$Periode = $Thn.'-'.$Bln.'-15';
}
// echo $Periode.'<br>';
// echo $PeriodeSebelumnya.' Stok Sebelumnya';
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
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
						<h2 class="no-margin-bottom">Laporan Rekap Harga Komoditi Pasar</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="card">
							<div class="card-header">
								<h4>Data Laporan Rekap Harga Komoditi Pasar</h4>
							</div>
							<div class="card-body">		
								<form action="">
									<div class="row">
										<div class="col-lg-4 col-md-4">
											<label class="form-control-label">Tampilkan Data</label>
											<select name="d" id="d" class="form-control">
												<option value="<?php echo base64_encode('hkonsumen'); ?>" <?php echo $display === 'hkonsumen' ? "selected":""; ?> >Harga Konsumen</option>
												<option value="<?php echo base64_encode('hprodusen'); ?>" <?php echo $display === 'hprodusen' ? "selected":""; ?> >Harga Produsen</option>
												<option value="<?php echo base64_encode('ketersediaan'); ?>" <?php echo $display === 'ketersediaan' ? "selected":""; ?> >Stok Barang</option>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<label class="form-control-label">Tanggal</label>
											<input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Tanggal; ?>">
										</div>
										<div class="col-md-4">
											<label class="form-control-label">Data Pasar</label>
											<select class="form-control" name="psr">
												<option class="form-control" value="" selected>Semua Pasar</option>
												<?php 
												$sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
												$res_p = $koneksi->query($sql_p);
												while ($row_p = $res_p->fetch_assoc()) {
													if(isset($KodePasar) && $KodePasar === $row_p['KodePasar']){
														echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
													}else{
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
								<!-- /var/www/dinasperdagangan.jombangkab.go.id/html/library/html2pdf/cetak -->
								<div class="col-lg-12 col-md-12" style="margin-bottom:10px;">
									<a target="_blank" href="../library/html2pdf/cetak/LapSemuaHargaPasar.php?tgl=<?php echo $Tanggal ?>&psr=<?php echo base64_encode($KodePasar); ?>&d=<?php echo base64_encode($display) ?>" class="btn btn-secondary"><i class="fa fa-fw fa-print"></i> Cetak</a>
									
									<a target="_blank" href="../library/Export/RekapSemuaPasar.php?tgl=<?php echo $Tanggal ?>&psr=<?php echo base64_encode($KodePasar); ?>&d=<?php echo base64_encode($display) ?>" class="btn btn-secondary"><i class="fa fa-file-excel-o"></i> Eksport</a>
								</div>
								<div class="col-lg-12">
									<div class="table-responsive">  
										<table class="table">
											<thead class="table-bordered">
												<tr>
													<th rowspan="2" class="text-left">No</th>
													<th rowspan="2" class="text-left">Nama Bahan Pokok dan Jenisnya</th>
													<th rowspan="2" class="text-center">Satuan</th>
													<th colspan="2" class="text-center"><?php echo $DSPLY; ?></th>
													<th colspan="2" class="text-center">Perubahan</th>
													<th rowspan="2" class="text-center">Ket</th>
													<th rowspan="2" class="text-center">Perubahan/Kondisi saat ini</th>
												</tr>
												<tr>
													<th class="text-center">Kemarin<br><?=isset($display) && $display != 'ketersediaan' ?  $tglKemarin : $PeriodeSebelumnya ?></th>
													<th class="text-center">Hari Ini<br><?=isset($display) && $display != 'ketersediaan' ?  $tglSekarang : $Periode ?></th>
													<th class="text-center"><?=isset($display) && $display != 'ketersediaan' ?  'Rp.' : 'Stok' ?> </th>
													<th class="text-center">%</th>
												</tr>
											</thead>
											<tbody>
												<?php
												include '../library/pagination1.php';
												$value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
												$reload = "RekapSemuaPasar.php?psr=".base64_encode($KodePasar)."&brg=".base64_encode($KodeBarang)."&pagination=true&view=1&v=".$value."&tgl=".$Tanggal."&d=".base64_encode($display);

												if($display === "ketersediaan"){

													$sql_pencarian = "";
                                                    if(isset($_GET['v']) && $_GET['v'] != ''){
                                                        $sql_pencarian = "AND b.NamaBarang like '%$value%'";
                                                    }

													$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
													IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, 
													IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang
													FROM mstbarangpokok b
													LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup

													LEFT JOIN (
													SELECT r.TglInput, r.KodeBarang,  SUM(IFNULL(r.Stok, 0)) AS Ketersediaan
													FROM stokpedagang r
													WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND r.Periode = '$PeriodeSebelumnya'
													GROUP BY r.KodeBarang ASC
													) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang

													LEFT JOIN (
													SELECT r.TglInput, r.KodeBarang, SUM(IFNULL(r.Stok, 0)) AS Ketersediaan
													FROM stokpedagang r
													WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND r.Periode = '$Periode'
													GROUP BY r.KodeBarang ASC
													) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang

													WHERE  (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009')  $sql_pencarian
													GROUP BY b.KodeBarang
													ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
													
												}else{
													$sql_pencarian = "";
                                                    if(isset($_GET['v']) && $_GET['v'] != ''){
                                                        $sql_pencarian = "AND b.NamaBarang like '%$value%'";
                                                    }
													
													$sql = "SELECT b.KodeBarang,b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang,(SELECT AVG(HargaBarang) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('') > 0, KodePasar = '', TRUE) AND DATE(Tanggal) <= DATE('2020-06-03')) AS HargaBarangKemarin,(SELECT AVG(HargaBarang) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE)									 AND DATE(Tanggal) <= DATE('$Tanggal')) AS HargaBarangSekarang,(SELECT AVG(HargaProdusen) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) AND DATE(Tanggal) <= DATE('$TanggalKemarin')) AS HargaProdusenKemarin,(SELECT AVG(HargaProdusen) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) AND DATE(Tanggal) <= DATE('$Tanggal')) AS HargaProdusenSekarang,(SELECT AVG(Ketersediaan) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) AND DATE(Tanggal) <= DATE('$TanggalKemarin')) AS KetersediaanKemarin,(SELECT AVG(Ketersediaan) FROM reporthargaharian WHERE KodeBarang = b.KodeBarang AND IF(LENGTH('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) AND DATE(Tanggal) <= DATE('$Tanggal')) AS KetersediaanSekarang,IFNULL(hppsekarang.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal
													FROM mstbarangpokok b LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup LEFT JOIN (SELECT r.* FROM reporthargaharian r) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
													WHERE b.IsAktif = '1' $sql_pencarian GROUP BY b.KodeBarang ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";


													// $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
													// IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, 
													// IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
													// IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
													// IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
													// IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal
													// FROM mstbarangpokok b
													// LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
													// LEFT JOIN (
													// SELECT r.Tanggal, r.KodeBarang, AVG(IFNULL(r.HargaBarang, 0)) AS HargaBarang, AVG(IFNULL(r.HargaProdusen, 0)) AS HargaProdusen, AVG(IFNULL(r.Ketersediaan, 0)) AS Ketersediaan, r.Keterangan 
													// FROM reporthargaharian r
													// WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND DATE(r.Tanggal) = DATE_SUB(date('$Tanggal'), INTERVAL 1 DAY)
													// ORDER BY r.Tanggal ASC
													// ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
													// LEFT JOIN (
													// SELECT r.Tanggal, r.KodeBarang, AVG(IFNULL(r.HargaBarang, 0)) AS HargaBarang, AVG(IFNULL(r.HargaProdusen, 0)) AS HargaProdusen, AVG(IFNULL(r.Ketersediaan, 0)) AS Ketersediaan, r.Keterangan 
													// FROM reporthargaharian r
													// WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND DATE(r.Tanggal) = date('$Tanggal')
													// ORDER BY r.Tanggal ASC
													// ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
													// WHERE b.IsAktif = '1' $sql_pencarian
													// GROUP BY b.KodeBarang
													// ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";

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
												$kodegroup = "";
												while(($count<$rpp) && ($i<$tcount)) {
													mysqli_data_seek($result,$i);
													$data= mysqli_fetch_array($result);
													if($kodegroup !== $data['KodeGroup']){
														echo '<tr style="background:#f7f7f7;"><td width="50px">'.(++$no_urut).'</td>
														<td colspan="8"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
														</tr>';
														$kodegroup = $data['KodeGroup'];
													}
													?>
													<tr class="odd gradeX">
														<td width="50px">
														</td>
														<td>
															<?php echo $data['NamaBarang'];?>
														</td>
														<th class="text-center">
															<?php echo $data['Satuan'];?>
														</th>
														<td class="text-right">
															<?php echo 
																($display == 'hkonsumen' ? 'Rp.'.number_format($data['HargaBarangKemarin']) : 
																	($display == 'hprodusen' ? 'Rp.'.number_format($data['HargaProdusenKemarin']) : $data['KetersediaanKemarin']))
																;?>
															</td>
															<td class="text-right">
																<?php echo 
																	($display == 'hkonsumen' ? 'Rp.'.number_format($data['HargaBarangSekarang']) : 
																		($display == 'hprodusen' ? 'Rp.'.number_format($data['HargaProdusenSekarang']) : $data['KetersediaanSekarang']))
																	;?>
																</td>
																<?php
																$class_naik = '';
																$icon_ = '';
																?>
																<td class="text-right">
																	<?php
																	$dataselisi = ""; 
																	$selisi =  ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : 
																		($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) < 1 ? 0 : ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : 
																		($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) - ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : 
																		($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin']));
																		if($selisi < 0){
																			$icon_ = '<i class="fa fa-fw fa-chevron-down"></i>';
																			$tmpselisi = $selisi * -1;
																			$class_naik = 'text-success';
																			if($display == 'ketersediaan'){
																				$dataselisi = "- ".$tmpselisi;
																			}else{
																				$dataselisi = "- Rp.".number_format($tmpselisi);
																			}
																		}elseif($selisi == 0){
																			$icon_ = '<i class="fa fa-fw fa-minus"></i>';
																			$class_naik = '';
																			$dataselisi = '--';
																		}else{
																			$icon_ = '<i class="fa fa-fw fa-chevron-up"></i>';
																			$class_naik = 'text-danger';
																			if($display == 'ketersediaan'){
																				$dataselisi = $selisi;
																			}else{
																				$dataselisi = "Rp.".number_format($selisi);
																			}
																			
																		}
																		echo '<p class="'.$class_naik.'">'.$dataselisi.'</p>'; ?>
																	</td>
																	<td class="text-right">
																		<?php 
																		if(($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : 
																			($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) == 0 && ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : 
																			($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin'])) == 0 ){
																			echo '<p class="'.$class_naik.'">-- '.$icon_.'</p>';
																	}else{
																		$persenSelisi = 0;
																		if($selisi == ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : 
																			($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang']))){
																			$persenSelisi = 100;
																	}else if($selisi == 0 || ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : 
																		($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin'])) == 0){
																		$persenSelisi = 0;
																	}else{
																		$persenSelisi = ($selisi / ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : 
																			($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin']))) * 100;
																	}
																	if(is_infinite($persenSelisi) || is_nan($persenSelisi)){	
																		echo '<p class="'.$class_naik.'">'.number_format(0, 2).' % '.$icon_.'</p>';
																	}else{
																		echo '<p class="'.$class_naik.'">'.number_format($persenSelisi, 2).' % '.$icon_.'</p>';
																	}
																}
																?>
															</td>
															<td></td>
															<td></td>
														</tr>
														<?php
														$i++; 
														$count++;
													}

													if($tcount==0){
														echo '
														<tr>
														<td colspan="9" align="center">
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
	</body>
	</html>