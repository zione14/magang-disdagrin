<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='Harian';
$fitur_id = 26;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';

$sql_p = "SELECT * FROM mstpasar WHERE IF(length('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$data_pasar = array();
while ($row_p = $res_p->fetch_assoc()) {
    if($row_p){
        array_push($data_pasar, $row_p);
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
	<link
	rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
	
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
						<h2 class="no-margin-bottom">Laporan Rekap Harian</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
                                <div class="card-header">
                                    <h4>Data Laporan Rekap Harian Bahan Pokok</h4>
                                </div>
								<div class="card-body">
                                    <div class="row" style="padding:0px !important;">
                                        <div class="col-lg-12">
                                            <form action="" method="get">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="form-control-label">Pencarian</label>
                                                        <select class="form-control" name="psr">
                                                            <option class="form-control" value="" selected>Semua Pasar</option>
                                                            <?php 
                                                            $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                                            $res_p = $koneksi->query($sql_p);
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
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Tampilkan Data</label>
                                                        <select name="d" id="d" class="form-control">
                                                            <option value="<?php echo base64_encode('hkonsumen'); ?>" <?php echo $display === 'hkonsumen' ? "selected":""; ?> >Harga Konsumen</option>
                                                            <option value="<?php echo base64_encode('hprodusen'); ?>" <?php echo $display === 'hprodusen' ? "selected":""; ?> >Harga Produsen</option>
                                                            <!-- <option value="<?php echo base64_encode('ketersediaan'); ?>" <?php echo $display === 'ketersediaan' ? "selected":""; ?> >Ketersediaan</option> -->
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Nama Bahan Pokok</label>
                                                        <select class="form-control" name="brg">
                                                            <option class="form-control" value="" selected>Semua Bahan Pokok</option>
                                                            <?php
                                                                $sql_b = "SELECT KodeBarang, NamaBarang
                                                                    FROM mstbarangpokok 
                                                                    WHERE IsAktif = '1'";
                                                                $res_b = $koneksi->query($sql_b);
                                                                while ($row_b = $res_b->fetch_assoc()) {
                                                                    if($KodeBarang == $row_b['KodeBarang']){
                                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'" selected>'.$row_b['NamaBarang'].'</option>';
                                                                    }else{
                                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'">'.$row_b['NamaBarang'].'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Tanggal</label>
                                                        <div class="input-group mb-3">
                                                            <input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Tanggal; ?>">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="submit">Cari</button>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </form>
                                        </div>
										 <div class="col-lg-12 col-md-12" style="margin-bottom:10px;">
                                            <a  href="../library/Export/Rekaphpp-Harian.php?tgl=<?php echo $Tanggal ?>&brg=<?php echo base64_encode($row_b['KodeBarang']); ?>&d=<?php echo base64_encode($display); ?>&psr=<?php echo base64_encode($KodePasar); ?>" class="btn btn-secondary"><i class="fa fa-download"></i> Export Excel</a>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left" rowspan="2" width="50px" style="vertical-align: middle;">No.</th>
                                                            <th class="text-left" style="vertical-align: middle;" rowspan="2">Bahan Pokok Pasar</th>
                                                            <th colspan="<?php echo (sizeof($data_pasar)+1); ?>">
                                                                <div class="text-center">Nama Pasar</div>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <?php foreach($data_pasar as $psr): ?>
                                                            <th class="text-right"><?php echo ucwords(str_replace('pasar', '', strtolower($psr['NamaPasar']))); ?></th>
                                                            <?php endforeach; ?>
                                                            <th class="text-right">Rata rata</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    include '../library/pagination1.php';
                                                    $value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
                                                    $reload = "Rekaphpp-Harian.php?psr=".base64_encode($KodePasar)."&tgl=".$Tanggal."&pagination=true&view=1&v=".$value;

                                                    $sql_b = "";
                                                    if($KodeBarang !== ""){
                                                        $sql_b = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
                                                        FROM mstbarangpokok b
                                                        LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                                        WHERE b.IsAktif = '1' AND b.KodeBarang = '".$KodeBarang."'
                                                        GROUP BY b.KodeBarang
                                                        ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
                                                    }else{
                                                        $sql_b = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
                                                        FROM mstbarangpokok b
                                                        LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                                        WHERE b.IsAktif = '1'
                                                        GROUP BY b.KodeBarang
                                                        ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
                                                    }
                                                    
                                                    $result = mysqli_query($koneksi, $sql_b);
                                                    $rpp = 10;
                                                    $page = intval(@$_GET["page"]);
                                                    if($page<=0) $page = 1;  
                                                    $tcount = mysqli_num_rows($result);
                                                    $tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
                                                    $count = 0;
                                                    $i = ($page-1)*$rpp;
                                                    $no_urut = ($page-1)*$rpp;
                                                    $data_barang = array();
                                                    while(($count<$rpp) && ($i<$tcount)) {
                                                        mysqli_data_seek($result,$i);
                                                        $data = mysqli_fetch_assoc($result);
                                                        array_push($data_barang, $data);
                                                        $i++; 
                                                        $count++;
                                                    }
													
													

                                                    $rekap_data = array();
                                                    foreach ($data_barang as $brg) {                                                        
                                                        $sql_r = "SELECT r.*, b.NamaBarang, b.Satuan, p.NamaPasar, u.ActualName
                                                             FROM mstbarangpokok b
                                                             LEFT JOIN reporthargaharian r ON b.KodeBarang = r.KodeBarang AND DATE(r.Tanggal) = DATE('".$Tanggal."')
                                                             LEFT JOIN mstpasar p ON p.KodePasar = r.KodePasar
                                                             LEFT JOIN userlogin u ON u.UserName = r.UserName
                                                             WHERE b.KodeBarang = '".$brg['KodeBarang']."'
                                                             ORDER BY b.KodeGroup";
                                                        $stmt = $koneksi->prepare($sql_r);
                                                        if($stmt->execute()){
                                                            $result = $stmt->get_result();
                                                            while ($row = $result->fetch_assoc()) {
                                                                if($row != null){
                                                                    array_push($rekap_data, $row);
                                                                }
                                                            }
                                                            $stmt->free_result();
                                                            $stmt->close();
                                                        }
                                                    }
                                                    $psrData = array();
                                                    foreach ($data_pasar as $psr ) {
                                                        $dtpsr = array();
                                                        foreach ($rekap_data as $rkp) {
                                                            if($rkp['KodePasar'] === $psr['KodePasar']){
                                                                array_push($dtpsr, $rkp);
                                                            }
                                                        }
                                                        $psrData[$psr['KodePasar']] = $dtpsr;
                                                    }
                                                    // echo json_encode($psrData);

                                                    if($tcount==0){
                                                        echo '
                                                        <tr><td colspan="7" align="center">
                                                            <strong>Tidak ada data</strong>
                                                        </td></tr>';
                                                    }else{
                                                        $kodegroup = "";
                                                        foreach ($data_barang as $brg):
                                                            if($kodegroup !== $brg['KodeGroup']){
                                                                echo '<tr style="background:#f7f7f7;"><td></td>
                                                                    <td colspan="'.(sizeof($data_pasar) + 2).'"><strong>'.ucwords($brg['NamaGroup']).'</strong></td>
                                                                </tr>';
                                                                $kodegroup = $brg['KodeGroup'];
                                                            }
                                                         ?>
                                                        <tr>
                                                            <td><?php echo ++$no_urut;?></td>
                                                            <td><?php echo $brg['NamaBarang']; ?></td>
                                                            <?php 
                                                            $jml_psr = 0;
                                                            $jml_harga = 0;
                                                            foreach($data_pasar as $psr): ?>
                                                            <td class="text-right">
                                                                <?php 
                                                                $data_pasar_ = $psrData[$psr['KodePasar']];
                                                                if($display === "ketersediaan"){
                                                                    $sedia = 0;
                                                                    foreach ($data_pasar_ as $dp) {
                                                                        if($dp['KodeBarang'] === $brg['KodeBarang']){
                                                                            $sedia = $dp['Ketersediaan'];
                                                                            $jml_psr ++;
                                                                            $jml_harga += $sedia;
                                                                            break;
                                                                        }
                                                                    }
                                                                    echo number_format($sedia);
                                                                }elseif($display === "hprodusen"){
                                                                    $hargapro = 0;
                                                                    foreach ($data_pasar_ as $dp) {
                                                                        if($dp['KodeBarang'] === $brg['KodeBarang']){
                                                                            $hargapro = $dp['HargaProdusen'];
                                                                            $jml_psr ++;
                                                                            $jml_harga += $hargapro;
                                                                            break;
                                                                        }
                                                                    }
                                                                    echo 'Rp.'.number_format($hargapro);
                                                                }else{
                                                                    $hargabrg = 0;
                                                                    foreach ($data_pasar_ as $dp) {
                                                                        if($dp['KodeBarang'] === $brg['KodeBarang']){
                                                                            $hargabrg = $dp['HargaBarang'];
                                                                            $jml_psr ++;
                                                                            $jml_harga += $hargabrg;
                                                                            break;
                                                                        }
                                                                    }
                                                                    echo 'Rp.'.number_format($hargabrg);
                                                                }?>
                                                            </td>
                                                            <?php endforeach; ?>
                                                            <th class="text-right">
                                                                <?php 
                                                                if($jml_harga < 1 || $jml_psr < 1){
                                                                    echo '--';
                                                                }else{
                                                                    $rata2 = $jml_harga / $jml_psr;
                                                                    if($display === "ketersediaan"){
                                                                        echo number_format($rata2);
                                                                    }else{
                                                                        echo 'Rp.'.number_format($rata2);
                                                                    }
                                                                }
                                                                ?>
                                                            </th>
                                                        </tr>                            
                                                    <?php endforeach;
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
</body>
<?php include 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tgl').Zebra_DatePicker({format: 'Y-m-d'});
	});
</script>