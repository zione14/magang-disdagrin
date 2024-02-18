<?php
include '../admin/akses.php';
$Page = 'Grafik';
$SubPage = 'HargaHarianLaporan';
$fitur_id = 25;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');

$date = date('Y-m-d');
$date_minus_sebulan = date('Y-m-d', strtotime($date.' -1 month'));

$TanggalMulai = isset($_GET['tglmulai']) ? mysqli_real_escape_string($koneksi,$_GET['tglmulai']) : $date_minus_sebulan;
$TanggalSelesai = isset($_GET['tglselesai']) ? mysqli_real_escape_string($koneksi,$_GET['tglselesai']) : $date;

$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
//WHERE Kodepasar = $KodePasar

$sql_p = "SELECT KodePasar,NamaPasar FROM mstpasar Where KodePasar = ? ";
$res_p = $koneksi->prepare($sql_p);
$res_p->bind_param('s', $KodePasar);
$res_p->execute();
$result = $res_p->get_result();
$row_pasar = $result->fetch_assoc();

$sql_b = "SELECT KodeBarang, NamaBarang FROM mstbarangpokok WHERE KodeBarang = ? ";
$res_b = $koneksi->prepare($sql_b);
$res_b->bind_param('s', $KodeBarang);
$res_b->execute();
$resid = $res_b->get_result();
$row_barang = $resid->fetch_assoc();


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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">

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
					<h2 class="no-margin-bottom">Laporan Harga Harian</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">		
							<form action="">			
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Pencarian</label>
                                            <select class="form-control" name="psr">
                                                <!-- <option class="form-control" value="" selected>Semua Pasar</option> -->
                                                <?php 
                                                
                                                $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                                $res_p = $koneksi->prepare($sql_p);
                                                $res_p->execute();
                                                $resuldd = $res_p->get_result();
                                                while ($row_p = $resuldd->fetch_assoc()) {
                                                    if($KodePasar == $row_p['KodePasar']){
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                                    }else{
                                                        if(!isset($KodePasar) || strlen($KodePasar) < 1){
                                                            $KodePasar = $row_p['KodePasar'];
                                                        }
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                                    }
                                                }
                                                
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Nama Bahan Pokok</label>
                                            <select class="form-control" name="brg">
                                            <?php
                                                $sql_b = "SELECT KodeBarang, NamaBarang
                                                    FROM mstbarangpokok 
                                                    WHERE IsAktif = '1'";
                                                $res_b = $koneksi->prepare($sql_b);
                                                $res_b->execute();
                                                $resu = $res_b->get_result();
                                                while ($row_b = $resu->fetch_assoc()) {
                                                    if($KodeBarang == $row_b['KodeBarang']){
                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'" selected>'.$row_b['NamaBarang'].'</option>';
                                                    }else{
                                                        if(!isset($KodeBarang) || strlen($KodeBarang) < 1){
                                                            $KodeBarang = $row_b['KodeBarang'];
                                                        }
                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'">'.$row_b['NamaBarang'].'</option>';
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Tanggal Mulai</label>
                                            <input  class="form-control" id="tglmulai" name="tglmulai" type="text" value="<?php echo $TanggalMulai; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group ">
                                            <label class="form-control-label">Tanggal Selesai</label>
                                            <div class="input-group">
                                                <input  class="form-control" id="tglselesai" name="tglselesai" type="text" value="<?php echo $TanggalSelesai; ?>">
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Cari</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(isset($KodeBarang) && strlen($KodeBarang) > 0 && isset($KodePasar) && strlen($KodePasar) > 0 ): ?>
                                    <?php 
                                    $sql_lap = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin, r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar, DATE(r.Tanggal) AS FormatTanggal
                                    FROM reporthargaharian r
                                    INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
                                    INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
                                    LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                    LEFT JOIN (
                                        SELECT *
                                        FROM reporthargaharian k
                                        ORDER BY k.Tanggal DESC
                                    ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
                                    WHERE r.KodePasar = '".$KodePasar."' AND r.KodeBarang = '".$KodeBarang."' AND 
                                    (
                                        (
                                            DATE(r.Tanggal) <= DATE('".$TanggalMulai."') AND 
                                            DATE(r.Tanggal) >= DATE('".$TanggalSelesai."')
                                        ) OR 
                                        (
                                            DATE(r.Tanggal) >= DATE('".$TanggalMulai."') AND 
                                            DATE(r.Tanggal) <= DATE('".$TanggalSelesai."')
                                        )
                                    )
                                    GROUP BY r.Tanggal
                                    ORDER BY r.Tanggal DESC";
                                    $res_lap = $koneksi->prepare($sql_lap);
                                    $res_lap->execute();
                                    $resii = $res_lap->get_result();
                                    $lap_ = array();
                                    while ($row_lap = $resii->fetch_assoc()) {
                                        array_push($lap_, $row_lap);
                                    }
                                    $label_array = array();
                                    $period = new DatePeriod(
                                        new DateTime($TanggalMulai),
                                        new DateInterval('P1D'),
                                        new DateTime(date('Y-m-d', strtotime($TanggalSelesai.' 1 day')))
                                    );
                                    
                                    ?>
                                    <div class="col-lg-12 col-md-12" style="margin:10px 0px;">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item">
                                                <a href="#home-pills" data-toggle="tab" class="nav-link <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>"><span>Grafik Data</span></a>&nbsp;
                                            </li>
                                            <li class="nav-item">
                                                <a href="#home-sub" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==2){ echo 'in active show'; }?>"><span>Tabel Data</span></a>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-12 col-md-12 mb-20">
                                        <div class="tab-content" id="reportPage">
                                            <div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>" id="home-pills">
                                                <?php
                                                    $hrg_produsen = array();
                                                    $hrg_konsumen = array();
                                                    $ketersediaan = array();
                                                    foreach ($period as $key => $value) {
                                                        $valDate = date_format($value, 'Y-m-d');
                                                        $isi = 0;
                                                            foreach ($lap_ as $v ) {
                                                                if($v['FormatTanggal'] === $valDate){
                                                                    $isi = $v;
                                                                }
                                                            }
                                                            if($isi){                                            
                                                                array_push($hrg_konsumen, $isi['HargaBarang']);
                                                                array_push($hrg_produsen, $isi['HargaProdusen']);
                                                                // array_push($ketersediaan, $isi['Ketersediaan']);
                                                            }else{
                                                                array_push($hrg_produsen, 0);
                                                                array_push($hrg_konsumen, 0);
                                                                // array_push($ketersediaan, 0);
                                                            }
                                                            $value->format('Y-m-d');
                                                            array_push($label_array, $value);    
                                                    }
                                                ?>
                                                <canvas id="HargaKonsumenChart" style="width:100%;height:400px;margin:10px 0px;"></canvas>
                                                <canvas id="HargaProdusenChart" style="width:100%;height:400px;margin:10px 0px;"></canvas>
                                                <a class="float-right" href="#" id="downloadPdf">Cetak Grafik</a>
                                                <canvas id="KetersediaanChart"></canvas>

                                                
                                            </div>
                                            <div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="home-sub">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left">No.</th>
                                                                <th class="text-left">Tanggal</th>
                                                                <th class="text-left">Satuan</th>
                                                                <th class="text-right">Harga Konsumen</th>
                                                                <th class="text-right">Harga Produsen</th>
                                                                <!-- <th class="text-center">Ketersediaan</th> -->
                                                                <th class="text-left">Petugas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $nourut = 1;
                                                            $tgl_lap = "";
                                                            foreach($lap_ as $lap):
                                                                if($tgl_lap !== $lap['FormatTanggal']){
                                                                    echo '<tr style="background:#f7f7f7;">
                                                                        <td></td>
                                                                        <td colspan="6">'.date('d F Y', strtotime($lap['FormatTanggal'])).'</td>
                                                                    </tr>';
                                                                    $tgl_lap = $lap['FormatTanggal'];
                                                                }
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $nourut++; ?></td>
                                                                    <td><?php echo date('H:i:s', strtotime($lap['Tanggal']));;?></td>
                                                                    <td><?php echo $lap['Satuan']; ?></td>
                                                                    <td class="text-right"><?php echo 'Rp.'.number_format($lap['HargaBarang']); ?></td>
                                                                    <td class="text-right"><?php echo 'Rp.'.number_format($lap['HargaProdusen']); ?></td>
                                                                   <!--  <td class="text-center"><?php echo number_format($lap['Ketersediaan']); ?></td> -->
                                                                    <td class="text-left"><?php echo $lap['UserName']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    <?php //echo $sql_lap; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-lg-12">
                                        <label for="">Tidak ada data</label>
                                    </div>
                                    <?php endif; ?>
                                </div>
							</form>                            						
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBqH_ctOCgwu5RLMrH6VdhCBLorpJXaDk&libraries=places"></script> 
<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>-->

<?php include 'footer.php'; ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tglmulai').Zebra_DatePicker({format: 'Y-m-d'});
		$('#tglselesai').Zebra_DatePicker({format: 'Y-m-d'});
	});
</script>

<script>

var labels = [];
var datea = <?php echo json_encode( $label_array); ?>;
for (let i = 0; i < datea.length; i++) {
    const label = formatDate(datea[i].date);
    labels.push(label);
}

var HargaKonsumenChart = document.getElementById("HargaKonsumenChart");
var HargaProdusenChart = document.getElementById("HargaProdusenChart");
// var KetersediaanChart = document.getElementById("KetersediaanChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 12;

var hargaKonsumenData = {
  labels: labels,
  datasets: [{
    label: "Harga Barang Konsumen",
    data: <?php echo json_encode( $hrg_konsumen); ?>,
    lineTension: 0,
    fill: true,
    borderColor: 'rgb(255, 193, 7)',
    backgroundColor: 'transparent',
    pointBorderColor: 'rgb(255, 193, 7)',
    pointBackgroundColor: 'rgb(255, 193, 7)',
    pointRadius: 5,
    pointHoverRadius: 10,
    pointHitRadius: 30,
    pointBorderWidth: 2,
    pointStyle: 'rectRounded'
  }]
};

var hargaProdusenData = {
  labels: labels,
  datasets: [{
    label: "Harga Produsen",
    data: <?php echo json_encode( $hrg_produsen); ?>,
    lineTension: 0,
    fill: true,
    borderColor: 'rgb(40, 167, 69)',
    backgroundColor: 'transparent',
    pointBorderColor: 'rgb(40, 167, 69)',
    pointBackgroundColor: 'rgb(40, 167, 69)',
    pointRadius: 5,
    pointHoverRadius: 10,
    pointHitRadius: 30,
    pointBorderWidth: 2,
    pointStyle: 'rectRounded'
  }]
};

// var ketersediaanData = {
//   labels: labels,
//   datasets: [{
//     label: "Jumlah Ketersediaan",
//     data: <?php echo json_encode( $ketersediaan); ?>,
//     lineTension: 0,
//     fill: true,
//     borderColor: 'rgb(0, 123, 255)',
//     backgroundColor: 'transparent',
//     pointBorderColor: 'rgb(0, 123, 255)',
//     pointBackgroundColor: 'rgb(0, 123, 255)',
//     pointRadius: 5,
//     pointHoverRadius: 10,
//     pointHitRadius: 30,
//     pointBorderWidth: 2,
//     pointStyle: 'rectRounded'
//   }]
// };

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

var lineChart1 = new Chart(HargaKonsumenChart, {
  type: 'line',
  data: hargaKonsumenData,
  options: chartOptions
});

var lineChart2 = new Chart(HargaProdusenChart, {
  type: 'line',
  data: hargaProdusenData,
  options: chartOptions
});

// var lineChart3 = new Chart(KetersediaanChart, {
//   type: 'line',
//   data: ketersediaanData,
//   options: chartOptions
// });

$('#downloadPdf').click(function(event) {
  // get size of report page
  var reportPageHeight = $('#reportPage').innerHeight();
  var reportPageWidth = $('#reportPage').innerWidth();
  
  // create a new canvas object that we will populate with all other canvas objects
  var pdfCanvas = $('<canvas />').attr({
    id: "canvaspdf",
    width: reportPageWidth,
    height: reportPageHeight
  });
  
  // keep track canvas position
  var pdfctx = $(pdfCanvas)[0].getContext("2d");
  pdfctx.fillStyle = "#ffffff";
  pdfctx.fillRect(0,0,1000,1300);
  var pdfctxX = 0;
  var pdfctxY = 0;
  var buffer = 50;
  
  // for each chart.js chart
  $("canvas").each(function(index) {
    // get the chart height/width
    var canvasHeight = $(this).innerHeight();
    var canvasWidth = $(this).innerWidth();
    
    // draw the chart into the new canvas
    pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
    pdfctxX += canvasWidth + buffer;
    // pdfctxY += canvasHeight + buffer;
    // our report page is in a grid pattern so replicate that in the new canvas
     if (index % 1 === 0) {
       pdfctxX =0;
       pdfctxY += canvasHeight + buffer;
     }
  });
  
  // create new pdf and add our new canvas as an image
  var pdf = new jsPDF("portrait", 'pt', [reportPageHeight, reportPageWidth]);
  pdf.addImage($(pdfCanvas)[0], 'PNG', 100, 100);
  
  pdf.setFont("helvetica");
pdf.setFontStyle("bold");
var judul = "GRAFIK DATA HARGA HARIAN <?= $row_barang['NamaBarang'];?> DI <?= $row_pasar['NamaPasar'];?>\nPERIODE <?= $TanggalMulai;?> S/D <?= $TanggalSelesai;?>";
pdf.text(judul,75,60);
  pdf.save(judul+'.pdf');
});

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun", "Jul",
        "Aug", "Sept", "Okt",
        "Nov", "Des"
    ];

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [day, monthNames[month-1], year].join('/');
}

</script>