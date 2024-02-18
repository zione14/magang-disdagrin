<?php
include '../admin/akses.php';
$Page = 'KetersediaanStok';
$fitur_id = 24;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d'); $blnow = date("m");
$TanggalKemarin = date('Y-m-d', strtotime('-1 days', strtotime($Tanggal)));




$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$Tgl = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : '01';
$Bln = isset($_GET['bln']) ? mysqli_real_escape_string($koneksi,$_GET['bln']) : $blnow;
$Thn = isset($_GET['thn']) ? mysqli_real_escape_string($koneksi,$_GET['thn']) : date("Y");

$Periode = $Thn.'-'.$Bln.'-'.$Tgl;
//Periode Sebelumnya
if($Tgl == '01'){
	$blnke = $Bln-1;
	if($Bln<=9){
		$PeriodeSebelumnya = $Thn.'-0'.$blnke.'-22';
	}else{
		$PeriodeSebelumnya = $Thn.'-'.$blnke.'-22';
	}
}elseif($Tgl == '22'){
	$PeriodeSebelumnya =  $Thn.'-'.$Bln.'-15';
}else{
	$tglke = $Tgl-7;
	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-0'.$tglke;

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
					<h2 class="no-margin-bottom">Data Stok</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">		
							<form action="">			
								<div class="row">
									<div class="col-md-2">
                                        <label class="form-control-label">Tanggal</label>
                                        <select class="form-control" name="tgl">
                                            <option value="01" <?php echo isset($Tgl) && $Tgl === "01" ?"selected" : ""; ?>>01</option>
                                            <option value="08" <?php echo isset($Tgl) && $Tgl === "08" ?"selected" : ""; ?>>08</option>
                                            <option value="15" <?php echo isset($Tgl) && $Tgl === "15" ?"selected" : ""; ?>>15</option>
                                            <option value="22" <?php echo isset($Tgl) && $Tgl === "22" ?"selected" : ""; ?>>22</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-control-label">&nbsp;</label>
                                        <select class="form-control" name="bln">
				                            <?php
	                                            $bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	                                            for($bulan=1; $bulan<=12; $bulan++){
	                                            	if($bulan<=9){
	                                            		if (isset($Bln) && $Bln !=''){
	                                            			$select = isset($Bln) && $Bln == '0'.$bulan ? 'selected' : '';
	                                            			echo"<option value=0".$bulan." ".$select."> $bln[$bulan] </option>";
	                                            		}else{
	                                            			$select = isset($blnow)  && $blnow == '0'.$bulan ? 'selected' : '';
	                                            			echo"<option value=0".$bulan." ".$select."> $bln[$bulan] </option>";
	                                            		}
	                                            		
	                                            		
	                                            	}else{
	                                            		if (isset($Bln) && $Bln !=''){
	                                            			$select = isset($Bln) && $Bln == $bulan ? 'selected' : '';
	                                            			echo"<option value=".$bulan." ".$select."> $bln[$bulan] </option>";
	                                            		}else{
	                                            			$select = isset($blnow)  && $blnow == $bulan ? 'selected' : '';
	                                            			echo"<option value=".$bulan." ".$select."> $bln[$bulan] </option>";
	                                            			
	                                            		}
	                                            		
	                                            	}
	                                            }
                                           ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-control-label">&nbsp;</label>
                                        <select class="form-control" name="thn">
                                           <?php
                                            for($i=date("Y"); $i>=date("Y")-5; $i-=1){
                                            	$selectthn = isset($Thn) && $Thn == $i ? 'selected' : '';
                                                echo "<option value=$i ".$selectthn."> $i </option>";
                                            }
                                           ?>
                                        </select>
                                    </div>
									<div class="col-md-5">
										<label class="form-control-label">Pencarian</label>
										<div class="input-group mb-3">
										<select class="form-control" name="psr">
											<?php 
											$sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
											$oke = $koneksi->prepare($sql_p);
											$oke->execute();											
											$res_p = $oke->get_result();
											while ($row_p = $res_p->fetch_assoc()) {
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
												<th class="text-right">Jumlah Kemarin</th>
												<th class="text-right">Jumlah Sekarang</th>
												<th class="text-right">Perubahan</th>
												<th class="text-right">Perubahan (%)</th>
												<th class="text-right">##</th>
											</tr>
										</thead>
										<tbody>
											<?php
											include '../library/pagination1.php';
											// $value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
											$reload = "KetersediaanStok.php?psr=".base64_encode($KodePasar)."&brg=".base64_encode($KodeBarang)."&pagination=true&view=1";
											
											$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang,  IFNULL(hppkemarin.StokKemarin, 0) AS KetersediaanKemarin, 
													IFNULL(hppsekarang.StokSekarang, 0) AS KetersediaanSekarang
													
												FROM mstbarangpokok b
												LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
												LEFT JOIN (
													SELECT SUM(r.Stok) as StokKemarin,r.KodeBarang
													FROM stokpedagang r
													WHERE r.KodePasar = '$KodePasar' AND r.Periode = '$PeriodeSebelumnya'
													GROUP by r.KodeBarang
												) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
												LEFT JOIN (
													SELECT SUM(r.Stok) as StokSekarang,r.KodeBarang
													FROM stokpedagang r
													WHERE r.KodePasar = '$KodePasar' AND r.Periode = '$Periode'
													GROUP by r.KodeBarang
												) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
												WHERE (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009') 
												GROUP BY b.KodeBarang
												ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
											$oke1 = $koneksi->prepare($sql);
											$oke1->execute();
											$result = $oke1->get_result();
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
												$data = mysqli_fetch_array($result);
												// if($kodegroup !== $data['KodeGroup']){
												// 	echo '<tr style="background:#f7f7f7;"><td width="50px"></td>
												// 		<td colspan="7"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
												// 	</tr>';
												// 	$kodegroup = $data['KodeGroup'];
												// }
												?>
												<tr class="odd gradeX">
													<td width="50px">
														<?php echo ++$no_urut;?> 
													</td>
													<td>
														<?php echo $data ['NamaBarang'];?>
													</td>
													<th class="text-center">
														<?php echo $data ['Satuan'];?>
													</th>
													<td class="text-right">
														<?php echo number_format($data ['KetersediaanKemarin']);?>
													</td>
													<td class="text-right">
														<?php echo number_format($data ['KetersediaanSekarang']);?>
													</td>
													<?php
													$class_naik = '';
													?>
													<td class="text-right">
														<?php
														$dataselisi = ""; 
														$selisi =  $data ['KetersediaanSekarang'] < 1 ? 0 : $data ['KetersediaanSekarang'] - $data ['KetersediaanKemarin'];
														if($selisi < 0){
															$class_naik = 'text-danger';
															$tmpselisi = $selisi * -1;
															$dataselisi = "- ".number_format($tmpselisi);
														}elseif($selisi == 0){
															$class_naik = '';
															$dataselisi = '--';
														}else{
															$class_naik = 'text-success';
															$dataselisi = number_format($selisi);
														}
														echo '<p class="'.$class_naik.'">'.$dataselisi.'</p>'; ?>
													</td>
													<td class="text-right">
														<?php 
														$persenSelisi = $selisi == 0 || $data ['KetersediaanKemarin'] == 0 ? 0 : ($selisi / $data ['KetersediaanKemarin'])* 100;
														if(is_infinite($persenSelisi) || is_nan($persenSelisi)){			
															echo '<p class="'.$class_naik.'">'.number_format(0, 2).' %</p>';
														}else{
															echo '<p class="'.$class_naik.'">'.number_format($persenSelisi, 2).' %</p>';
														}?>
													</td>
													<td class="float-right">
														<a href="EntryStok.php?brg=<?php echo base64_encode($data['KodeBarang']); ?>&psr=<?php echo base64_encode($KodePasar); ?>&bln=<?php echo $Bln; ?>&tgl=<?php echo $Tgl; ?>&thn=<?php echo $Thn; ?>" class="btn btn-sm btn-warning"><i class="fa fa-fw fa-edit"></i></a>
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