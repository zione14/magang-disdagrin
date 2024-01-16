<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='Tahunan';
$fitur_id = 28;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
// $KodePasar = '';

$Tanggal   = isset($_GET['th']) ? mysqli_real_escape_string($koneksi,$_GET['th']) : date('Y');
$KodeGroup = isset($_GET['gr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['gr'])) : '';
$display   = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';

$sql_g  = "SELECT KodeGroup, NamaGroup, Keterangan, IsAktif
FROM mstgroupbarang
WHERE IsAktif = '1'";
$stmt = $koneksi->prepare($sql_g);
$data_group = array();
if($stmt->execute()){
    $result = $stmt->get_result();
    $i = 0;
    while ($row_g = $result->fetch_assoc()) {
        # code...
        if($row_g){
            array_push($data_group, $row_g);
            if($i == 0 && $KodeGroup === ""){
                $KodeGroup = $row_g['KodeGroup'];
            }
        }
        $i++;
    }
    $stmt->free_result();
    $stmt->close();
}
if($KodeGroup === "" && sizeof($data_group) > 0){
    $KodeGroup = $data_group[0]['KodeGroup'];
}

// echo json_encode($data_group);
$TanggalMulai = $Tanggal;
$TanggalSelesai = date('Y-m-d', strtotime($TanggalMulai.' 1 year'));
$minggu = array();
for ($i=0; $i < 12; $i++) { 
    # code...
    if($i == 11){
        $period = new DatePeriod(
            new DateTime($TanggalMulai),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime($TanggalSelesai)))
        );
    }else{
        $period = new DatePeriod(
            new DateTime($TanggalMulai),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime($TanggalMulai.' 1 month')))
        );
    }
    $tgl_arr = array();
    foreach ($period as $key => $value) {
        $valDate = date_format($value, 'Y-m-d');
        array_push($tgl_arr, $valDate);
    }
    array_push($minggu, $tgl_arr);
    $TanggalMulai = date('Y-m-d', strtotime($tgl_arr[sizeof($tgl_arr)-1].' 1 day'));
}
$range = array();
foreach ($minggu as $week ) {
    # code...
    $minggu_mulai = $week[0];
    $minggu_selesai = $week[sizeof($week)-1];
    array_push($range, array(
        'mulai'=>$minggu_mulai, 'selesai'=>$minggu_selesai
    ));
}

$sql_gr = "SELECT KodeGroup, NamaGroup, Keterangan, IsAktif
FROM mstgroupbarang
WHERE KodeGroup = ?";
$group = array();
$stmt = $koneksi->prepare($sql_gr);
$stmt->bind_param('s', $KodeGroup);
if($stmt->execute()){
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if($row != null){
            $group = $row;
        }
    }
    $stmt->free_result();
    $stmt->close();
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
	
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
						<h2 class="no-margin-bottom">Laporan Rekap Tahunan</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
                                <div class="card-header">
                                    <h4>Perkembangan Harga Rata-Rata Bahan pokok "<?php echo isset($group['NamaGroup']) ? $group['NamaGroup'] : ""; ?>" dalam setahun</h4>
                                </div>
								<div class="card-body">
                                    <div class="row" style="padding:0px !important;">
                                        <div class="col-lg-12">
                                            <form action="" method="get">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Tampilkan Data</label>
                                                        <select name="d" id="d" class="form-control">
                                                            <option value="<?php echo base64_encode('hkonsumen'); ?>" <?php echo $display === 'hkonsumen' ? "selected":""; ?> >Harga Konsumen</option>
                                                            <option value="<?php echo base64_encode('hprodusen'); ?>" <?php echo $display === 'hprodusen' ? "selected":""; ?> >Harga Produsen</option>
                                                            <option value="<?php echo base64_encode('ketersediaan'); ?>" <?php echo $display === 'ketersediaan' ? "selected":""; ?> >Stok Barang</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label for="" class="form-control-label">Nama Group Barang</label>
                                                        <select name="gr" id="gr" class="form-control">
                                                        <?php 

                                                            if($display === "ketersediaan"){
                                                            $tambah = "AND (KodeGroup ='GROUP-2019-0000001' OR KodeGroup ='GROUP-2019-0000004' OR KodeGroup ='GROUP-2019-0000005' OR KodeGroup ='GROUP-2019-0000012' OR KodeGroup ='GROUP-2019-0000013')";
                                                        }
                                                            $sql_g = "SELECT * FROM mstgroupbarang WHERE IsAktif = 1 $tambah ORDER BY KodeGroup ASC";
                                                            $res_g = $koneksi->query($sql_g);
                                                            while ($row = mysqli_fetch_assoc($res_g)) {
                                                                if(isset($KodeGroup) && $row['KodeGroup'] == $KodeGroup){
                                                                    echo '<option value="'.base64_encode($row['KodeGroup']).'" selected>'.$row['NamaGroup'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.base64_encode($row['KodeGroup']).'">'.$row['NamaGroup'].'</option>';
                                                                }
                                                            }
                                                        ?>
                                                        
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Tahun</label>
                                                        <div class="input-group mb-3">
                                                            <select name="th" id="th" class="form-control">
                                                            <?php 
                                                            $i = date('Y');
                                                            while ( $i > 2010) { 
                                                                # code...
                                                                if($i == $Tanggal){
                                                                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                                                }else{
                                                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                                                }
                                                                $i--;
                                                            } ?>
                                                            </select>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="submit">Cari</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg-12">
                                        <?php 
                                       // echo  $KodeGroup;
                                       
                                        if($display === "ketersediaan"){

                                            $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Satuan, 
                                        week1.RtKetersediaan AS RtKetersediaan1, week1.JmlData AS JmlData1,
                                        week2.RtKetersediaan AS RtKetersediaan2, week2.JmlData AS JmlData2, 
                                        week3.RtKetersediaan AS RtKetersediaan3, week3.JmlData AS JmlData3, 
                                        week4.RtKetersediaan AS RtKetersediaan4, week4.JmlData AS JmlData4,
                                        week5.RtKetersediaan AS RtKetersediaan5, week5.JmlData AS JmlData5, 
                                        week6.RtKetersediaan AS RtKetersediaan6, week6.JmlData AS JmlData6,
                                        week7.RtKetersediaan AS RtKetersediaan7, week7.JmlData AS JmlData7,
                                        week8.RtKetersediaan AS RtKetersediaan8, week8.JmlData AS JmlData8,
                                        week9.RtKetersediaan AS RtKetersediaan9, week9.JmlData AS JmlData9,
                                        week10.RtKetersediaan AS RtKetersediaan10, week10.JmlData AS JmlData10,
                                        week11.RtKetersediaan AS RtKetersediaan11, week11.JmlData AS JmlData11,
                                        week12.RtKetersediaan AS RtKetersediaan12, week12.JmlData AS JmlData12
                                        FROM mstbarangpokok b
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='01'  
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week1 ON week1.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='02'  
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week2 ON week2.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='03' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week3 ON week3.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='04'  
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week4 ON week4.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='05'  
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week5 ON week5.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='06' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week6 ON week6.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='07' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week7 ON week7.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='08' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week8 ON week8.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='09' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week9 ON week9.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='10' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week10 ON week10.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='11' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week11 ON week11.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                            FROM stokpedagang r
                                            WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='12' 
                                            GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week12 ON week12.KodeBarang = b.KodeBarang

                                        WHERE b.KodeGroup = '$KodeGroup' AND (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009')";


                                        }else{

                                        $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Satuan, 
                                        week1.RtHargabarang AS RtHargabarang1, week1.RtHargaProdusen AS RtHargaProdusen1, week1.RtKetersediaan AS RtKetersediaan1, week1.JmlData AS JmlData1, 
                                        week2.RtHargabarang AS RtHargabarang2, week2.RtHargaProdusen AS RtHargaProdusen2, week2.RtKetersediaan AS RtKetersediaan2, week2.JmlData AS JmlData2, 
                                        week3.RtHargabarang AS RtHargabarang3, week3.RtHargaProdusen AS RtHargaProdusen3, week3.RtKetersediaan AS RtKetersediaan3, week3.JmlData AS JmlData3, 
                                        week4.RtHargabarang AS RtHargabarang4, week4.RtHargaProdusen AS RtHargaProdusen4, week4.RtKetersediaan AS RtKetersediaan4, week4.JmlData AS JmlData4,
                                        week5.RtHargabarang AS RtHargabarang5, week5.RtHargaProdusen AS RtHargaProdusen5, week5.RtKetersediaan AS RtKetersediaan5, week5.JmlData AS JmlData5, 
                                        week6.RtHargabarang AS RtHargabarang6, week6.RtHargaProdusen AS RtHargaProdusen6, week6.RtKetersediaan AS RtKetersediaan6, week6.JmlData AS JmlData6, 
                                        week7.RtHargabarang AS RtHargabarang7, week7.RtHargaProdusen AS RtHargaProdusen7, week7.RtKetersediaan AS RtKetersediaan7, week7.JmlData AS JmlData7, 
                                        week8.RtHargabarang AS RtHargabarang8, week8.RtHargaProdusen AS RtHargaProdusen8, week8.RtKetersediaan AS RtKetersediaan8, week8.JmlData AS JmlData8,
                                        week9.RtHargabarang AS RtHargabarang9, week9.RtHargaProdusen AS RtHargaProdusen9, week9.RtKetersediaan AS RtKetersediaan9, week9.JmlData AS JmlData9, 
                                        week10.RtHargabarang AS RtHargabarang10, week10.RtHargaProdusen AS RtHargaProdusen10, week10.RtKetersediaan AS RtKetersediaan10, week10.JmlData AS JmlData10, 
                                        week11.RtHargabarang AS RtHargabarang11, week11.RtHargaProdusen AS RtHargaProdusen11, week11.RtKetersediaan AS RtKetersediaan11, week11.JmlData AS JmlData11, 
                                        week12.RtHargabarang AS RtHargabarang12, week12.RtHargaProdusen AS RtHargaProdusen12, week12.RtKetersediaan AS RtKetersediaan12, week12.JmlData AS JmlData12
                                        FROM mstbarangpokok b
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 1 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week1 ON week1.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 2 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week2 ON week2.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 3 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week3 ON week3.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 4 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week4 ON week4.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 5 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week5 ON week5.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 6 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week6 ON week6.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 7 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week7 ON week7.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 8 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week8 ON week8.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 9 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week9 ON week9.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 10 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week10 ON week10.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 11 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week11 ON week11.KodeBarang = b.KodeBarang
                                        LEFT JOIN(
                                            SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
                                            FROM mstbarangpokok b
                                            LEFT JOIN (
                                            SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
                                            FROM reporthargaharian r
                                            WHERE MONTH(r.Tanggal) = 12 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
                                            GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
                                            GROUP BY b.KodeBarang) AS week12 ON week12.KodeBarang = b.KodeBarang
                                        WHERE b.KodeGroup = '".$KodeGroup."' AND b.IsAktif='1'";

                                        }

                                        $rekap = array();
                                        $stmt = $koneksi->prepare($sql);
                                        if($stmt->execute()){
                                            $result = $stmt->get_result();
                                            while ($row = $result->fetch_assoc()) {
                                                if($row != null){
                                                    array_push($rekap, $row);
                                                }
                                            }
                                            $stmt->free_result();
                                            $stmt->close();
                                        }
                                        ?>
                                        <script>var datarekap = <?php echo json_encode($rekap); ?>;</script>
                                        <canvas id="rekaptahunancart" style="width:100%;height:400px;margin:10px 0px;"></canvas>
                                        </div>
										
										<div class="col-lg-12 col-md-12" style="margin-bottom:10px;">
                                            <a target="_blank" href="cetak/Rekaphpp-Tahunan.php?th=<?php echo $Tanggal ?>&gr=<?php echo base64_encode($KodeGroup); ?>&d=<?php echo base64_encode($display); ?>" class="btn btn-secondary"><i class="fa fa-fw fa-print"></i> Cetak</a>
                                        </div>
										
                                        <div class="col-lg-12 col-md-12 table-responsive" style="margin-top:10px;">
                                            <table class="table table-stripped">
                                                <thead>                                            
                                                    <tr style="background:#f7f7f7;">
                                                        <th class="text-left">Nama Barang</th>
                                                        <th class="text-right">Jan</th>
                                                        <th class="text-right">Feb</th>
                                                        <th class="text-right">Mar</th>
                                                        <th class="text-right">Apr</th>
                                                        <th class="text-right">Mei</th>
                                                        <th class="text-right">Jun</th>
                                                        <th class="text-right">Jul</th>
                                                        <th class="text-right">Agust</th>
                                                        <th class="text-right">Sept</th>
                                                        <th class="text-right">Okt</th>
                                                        <th class="text-right">Nov</th>
                                                        <th class="text-right">Des</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($rekap as $rkp): ?>
                                                        <tr>
                                                            <td class="text-left"><?php echo $rkp['NamaBarang']; ?></td>
                                                            <?php for ($i=1; $i <= 12; $i++):?>
                                                            <td class="text-right">
                                                            <?php 
                                                                if($display === "ketersediaan"){
                                                                    echo number_format($rkp['RtKetersediaan'.$i]);
                                                                }elseif($display === "hprodusen"){
                                                                    echo 'Rp.'.number_format($rkp['RtHargaProdusen'.$i]);
                                                                }else{
                                                                    echo 'Rp.'.number_format($rkp['RtHargabarang'.$i]);
                                                                }
                                                            ?></td>
                                                            <?php endfor; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<script src="../komponen/js/mycolor.js"></script>
<script>
var htmlobjek;
$(document).ready(function(){
  //apabila terjadi event onchange terhadap object <select id=nama_produk>

  $("#d").change(function(){
    var d = $("#d").val();
    $.ajax({
        url: "../library/Dropdown/ambil-grop.php",
        data: "d="+d,
        cache: false,
        success: function(msg){
            $("#gr").html(msg);
        }
    });
  });
});


var rekaptahunancart = document.getElementById("rekaptahunancart");
var display = '<?php echo $display; ?>';

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 12;

var datasets = [];
for (let i = 0; i < datarekap.length; i++) {
    const rekap = datarekap[i];
    var dataharga = [];
    for (let x = 1; x <= 12; x++) {
        if(display == "ketersediaan"){
            dataharga.push(rekap["RtKetersediaan"+x]);
        }else if(display == "hprodusen"){
            dataharga.push(rekap["RtHargaProdusen"+x]);
        }else{
            dataharga.push(rekap["RtHargabarang"+x]);
        }
    }
    var color = getRandomColor();
    datasets.push({
        label: rekap['NamaBarang'],
        data: dataharga,
        lineTension: 0,
        fill: true,
        borderColor: color,
        backgroundColor: 'transparent',
        pointBorderColor: color,
        pointBackgroundColor: color,
        pointRadius: 5,
        pointHoverRadius: 10,
        pointHitRadius: 30,
        pointBorderWidth: 2,
        pointStyle: 'rectRounded'
    });
}

var chartOptions = {
    legend: {
        display: true,
        position: 'bottom'
    },
    scales: {
        yAxes: [{
            ticks: {
                suggestedMin: 0
            }
        }]
    }
};

var cart = new Chart(rekaptahunancart, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agust", "Sept", "Okt", "Nov", "Des"],
        datasets: datasets
    },
    options: chartOptions
});
</script>