<?php 
include 'head.php';
include '../library/pagination1.php';
date_default_timezone_set('Asia/Jakarta');

$date = date('Y-m-d');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : $date;
$date_minus_sebulan = date('Y-m-d', strtotime($Tanggal.' -1 month'));

$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';

$sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$datapasar = array();
while ($row_p = $res_p->fetch_assoc()) {
    array_push($datapasar, $row_p);
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
<style>
    .pagination {
        display: inline-block !important;
    }

    .pagination li {
        color: black;
        float: left;
        padding: 8px 2px;
        text-decoration: none;
        list-style-type: none;
    }
    
    select{
        display: block;
        width: 100%;
    }
</style>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="span12">
                <h4>Grafik Data Harga Produsen</h4>
            </div>
            <form action="" method="get">
            <div class="span3">            
                <div class="form-group">
                    <label class="form-control-label">Nama Bahan Pokok</label>
                    <select class="form-control" name="brg">
                    <?php
                        $sql_b = "SELECT KodeBarang, NamaBarang
                            FROM mstbarangpokok 
                            WHERE IsAktif = '1'";
                        $res_b = $koneksi->query($sql_b);
                        while ($row_b = $res_b->fetch_assoc()) {
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
            <div class="span4">
                <div class="form-search input-group">
                    <label for="" style="display:block;">Tanggal</label>
                    <div class="input-group">
                        <input type="text" name="tgl" id="tgl" value="<?php echo $Tanggal; ?>" class="form-control">
                        <button type="submit" class="btn btn-sm btn-primary input-group-btn input-group-append">Tampilkan</button>
                    </div>
                </div>
            </div>
            </form>
            <div class="span12" style="margin-top:12px;">
                <?php if(isset($KodeBarang) && strlen($KodeBarang) > 0) :?>
                <?php 
                $sql_br = "SELECT *
                FROM mstbarangpokok b 
                WHERE b.KodeBarang = ?";
                $stmt = $koneksi->prepare($sql_br);
                $stmt->bind_param("s", $KodeBarang);
                $detail_brg = array();
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    $num_of_rows = $result->num_rows;
                    while ($row = $result->fetch_assoc()) {
                        if($row != null){
                            $detail_brg = $row;
                        }
                    }
                    $stmt->free_result();
                    $stmt->close();
                }
                ?>
                <h6>Grafik Harga "<?php echo $detail_brg['NamaBarang']; ?>" Pada Tingkat Produsen per 30 Hari dari Tanggal <?php $strTanggal = date_create($Tanggal); echo date_format($strTanggal, "d/F/Y"); ?></h6>
                <p><i>harga ditampilkan dalam satuan rupiah</i></p>
                <?php
                    $TanggalMulai = $date_minus_sebulan;
                    $TanggalSelesai = $date;
                    $sql_lap =  "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin, r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar, DATE(r.Tanggal) AS FormatTanggal
                    FROM reporthargaharian r
                    INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
                    INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
                    LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                    LEFT JOIN (
                        SELECT *
                        FROM reporthargaharian k
                        ORDER BY k.Tanggal DESC
                    ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
                    WHERE r.KodeBarang = '".$KodeBarang."' AND 
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
                    $res_lap = $koneksi->query($sql_lap);
                    $lap_ = array();
                    while ($row_lap = $res_lap->fetch_assoc()) {
                        array_push($lap_, $row_lap);
                    }

                    // var_dump($sql_lap);
                    // die();
                    $label_array = array();
                    $period = new DatePeriod(
                        new DateTime($TanggalMulai),
                        new DateInterval('P1D'),
                        new DateTime($TanggalSelesai)
                    );
                    foreach ($period as $key => $value) {
                        $value->format('Y-m-d');
                        array_push($label_array, $value);    
                    }
                    $hrg_produsen_pasar = array();
                    $i = 0;
                    foreach ($datapasar as $pasar) {
                        $hrg_produsen = array();
                        foreach ($period as $key => $value) {
                            $valDate = date_format($value, 'Y-m-d');
                            $isi = 0;
                            foreach ($lap_ as $v ) {
                                if($v['FormatTanggal'] === $valDate && $v['KodePasar'] === $pasar['KodePasar']){
                                    $isi = $v;
                                }
                            }
                            if($isi){                                            
                                array_push($hrg_produsen, $isi['HargaProdusen']);
                            }else{
                                array_push($hrg_produsen, 0);
                            }  
                        }
                        array_push($hrg_produsen_pasar, array(
                            'KodePasar'=>$pasar['KodePasar'],
                            'Data'=>$hrg_produsen,
                            'NamaPasar'=>$pasar['NamaPasar']
                        ));
                        $i++;
                    }
                ?>
                <canvas id="HargaProdusenChart" style="width:100%;height:400px;margin:10px 0px;"></canvas>
                <?php else: ?>
                <label for="">Tidak ada data</label>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php include 'foot.php';?>

<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tgl').Zebra_DatePicker({format: 'Y-m-d'});
	});
</script>

<script>

var labels = [];
var datea = <?php echo json_encode( $label_array); ?>;
for (let i = 0; i < datea.length; i++) {
    const label = formatDate(datea[i].date);
    labels.push(label);
}

var HargaProdusenChart = document.getElementById("HargaProdusenChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 12;

var dataset_hrg = <?php echo json_encode($hrg_produsen_pasar); ?>;
var dataset = [];
for (let i = 0; i < dataset_hrg.length; i++) {
    const dataset_h = dataset_hrg[i];
    var color = getRandomColor();
    dataset.push(
        {
            label: dataset_h.NamaPasar,
            data: dataset_h.Data,
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
        }
    );
}

var hargaProdusenData = {
  labels: labels,
  datasets: dataset
};

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

var lineChart1 = new Chart(HargaProdusenChart, {
  type: 'line',
  data: hargaProdusenData,
  options: chartOptions
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