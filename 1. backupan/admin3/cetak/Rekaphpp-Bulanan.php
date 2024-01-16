<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';
// include '../aksi_hitung.php';

$Tanggal = isset($_GET['month']) ? mysqli_real_escape_string($koneksi,$_GET['month'].'-01') : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';
$month = isset($_GET['month']) ? mysqli_real_escape_string($koneksi,$_GET['month']) : date('Y-m');

$Thn = substr($month, 0, 4);
$Bln = substr($month, 5);
$periode1 = $Thn.'-'.$Bln.'-01';
$periode2 = $Thn.'-'.$Bln.'-08';
$periode3 = $Thn.'-'.$Bln.'-15';
$periode4 = $Thn.'-'.$Bln.'-22';


function pembulatan($uang){
    
    if($uang < 0){
        $akhir = 0;
    }else{
        $ratusan = substr(number_format($uang), -2);
        
        if($ratusan<50) {
         $akhir = $uang - $ratusan;
        }else{
         $akhir = $uang + (100-$ratusan);
         
        }
    }
    
    return 'Rp '.number_format($akhir);
}
 

$sql = "SELECT NamaPasar FROM mstpasar WHERE KodePasar = '$KodePasar'";
    $res_select = $koneksi->query($sql);
    $RowData = mysqli_fetch_assoc($res_select);

$TanggalMulai = $Tanggal;
$TanggalSelesai = date('Y-m-d', strtotime($TanggalMulai.' 1 month'));
$minggu = array();
for ($i=0; $i < 4; $i++) { 
    # code...
    if($i == 3){
        $period = new DatePeriod(
            new DateTime($TanggalMulai),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime($TanggalSelesai)))
        );
    }else{
        $period = new DatePeriod(
            new DateTime($TanggalMulai),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime($TanggalMulai.' 7 day')))
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

$sql_pasar = "";
if($KodePasar !== ""){
    $sql_pasar = "AND r.KodePasar = '".$KodePasar."'";
}

if ($display === "ketersediaan" ) :

$sql = "SELECT b.KodeBarang, b.NamaBarang, g.KodeGroup, g.NamaGroup, b.Satuan, IFNULL(week1.RtKetersediaan,0) AS RtKetersediaan1, IFNULL(week2.RtKetersediaan,0) AS RtKetersediaan2, IFNULL(week3.RtKetersediaan,0) AS RtKetersediaan3, IFNULL(week4.RtKetersediaan,0) AS RtKetersediaan4
FROM mstbarangpokok b
INNER JOIN mstgroupbarang g on g.KodeGroup = b.KodeGroup
LEFT JOIN(
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE r.Periode ='$periode1' $sql_pasar 
    GROUP by r.KodeBarang
) AS week1 ON week1.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE r.Periode ='$periode2' $sql_pasar 
    GROUP by r.KodeBarang
) AS week2 ON week2.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE r.Periode ='$periode3' $sql_pasar 
    GROUP by r.KodeBarang
) AS week3 ON week3.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE r.Periode ='$periode4' $sql_pasar 
    GROUP by r.KodeBarang
) AS week4 ON week4.KodeBarang = b.KodeBarang
WHERE (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009') 
GROUP BY b.KodeBarang
ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";

else:

$sql = "SELECT b.KodeBarang, b.NamaBarang, g.KodeGroup, g.NamaGroup, b.Satuan, IFNULL(week1.RtHargabarang,0) AS RtHargabarang1, IFNULL(week2.RtHargabarang,0) AS RtHargabarang2, IFNULL(week3.RtHargabarang,0) AS RtHargabarang3, IFNULL(week4.RtHargabarang,0) AS RtHargabarang4, IFNULL(week1.RtHargaProdusen,0) AS RtHargaProdusen1, IFNULL(week2.RtHargaProdusen,0) AS RtHargaProdusen2, IFNULL(week3.RtHargaProdusen,0) AS RtHargaProdusen3, IFNULL(week4.RtHargaProdusen,0) AS RtHargaProdusen4, IFNULL(week1.RtKetersediaan,0) AS RtKetersediaan1, IFNULL(week2.RtKetersediaan,0) AS RtKetersediaan2, IFNULL(week3.RtKetersediaan,0) AS RtKetersediaan3, IFNULL(week4.RtKetersediaan,0) AS RtKetersediaan4, IFNULL(week1.JmlData,0) AS JmlData1, IFNULL(week2.JmlData,0) AS JmlData2, IFNULL(week3.JmlData,0) AS JmlData3, IFNULL(week4.JmlData,0) AS JmlData4
FROM mstbarangpokok b
INNER JOIN mstgroupbarang g on g.KodeGroup = b.KodeGroup
LEFT JOIN(
    SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
    FROM reporthargaharian r
    WHERE DATE(r.Tanggal) >= DATE('".$range[0]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[0]['selesai']."') ".$sql_pasar." 
    GROUP by r.KodeBarang
) AS week1 ON week1.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
    FROM reporthargaharian r
    WHERE DATE(r.Tanggal) >= DATE('".$range[1]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[1]['selesai']."') ".$sql_pasar." 
    GROUP by r.KodeBarang
) AS week2 ON week2.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
    FROM reporthargaharian r
    WHERE DATE(r.Tanggal) >= DATE('".$range[2]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[2]['selesai']."') ".$sql_pasar." 
    GROUP by r.KodeBarang
) AS week3 ON week3.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
    FROM reporthargaharian r
    WHERE DATE(r.Tanggal) >= DATE('".$range[3]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[3]['selesai']."') ".$sql_pasar." 
    GROUP by r.KodeBarang
) AS week4 ON week4.KodeBarang = b.KodeBarang";

endif;

// echo $sql;exit;
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$DSPLY = "";
if($display === "ketersediaan"){
    $DSPLY = "JUMLAH KETERSEDIAAN";
}elseif($display === "hprodusen"){
    $DSPLY = "HARGA PRODUSEN";
}else{
    $DSPLY = "HARGA KONSUMEN";
}
$judul = "DATA REKAP ".$DSPLY." BAHAN POKOK PADA ".strtoupper($BulanIndo[date('m', strtotime($Tanggal)) - 1].' '.date('Y', strtotime($Tanggal)));
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
if($KodePasar != ''){
    $namapasar = strtoupper($RowData['NamaPasar']);
}else{
    $namapasar = "SEMUA PASAR";
}
$header = array(
    array("label"=>"No.", "length"=>8, "align"=>"L"),
    array("label"=>"Nama Bahan Pokok", "length"=>88, "align"=>"L"),
    array("label"=>"Satuan", "length"=>22, "align"=>"C"),
    array("label"=>"Minggu I", "length"=>40, "align"=>"R"),
    array("label"=>"Minggu II", "length"=>40, "align"=>"R"),
    array("label"=>"Minggu III", "length"=>40, "align"=>"R"),
    array("label"=>"Minggu IV", "length"=>40, "align"=>"R")
);

$pdf = new FPDF( 'L', 'mm', 'A4' );
$pdf->AddPage();

#tampilkan judul laporan
$pdf->SetFont('Arial','B','16');
$pdf->Cell(0, 5, $judul, '0', 1, 'C');

#tampilkan sub-judul laporan
$pdf->SetFont('Arial','','10');
$pdf->Cell(0, 8, $subjudul, '0', 1, 'C');

#tampilkan namapasar laporan
$pdf->SetFont('Arial','','10');
$pdf->Cell(0, 4, $namapasar, '0', 1, 'C');

#buat header tabel
$pdf->SetFont('Arial','','10');
$pdf->SetFillColor(39, 55, 64);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(0,0,0);
$pdf->Ln();
foreach ($header as $kolom) {
    $pdf->Cell($kolom['length'], 8, $kolom['label'], 1, '0', $kolom['align'], true);
}
$pdf->Ln();

#tampilkan data tabelnya
$pdf->SetFillColor(255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
$fill=false;


$result = mysqli_query($koneksi, $sql);
$no_urut = 1;
$kodegroup = "";
while($data = mysqli_fetch_array($result)) {
    if($kodegroup !== $data['KodeGroup']){
        $fill = true;
        $pdf->Cell($header[0]['length'], 5, '', 1, $header[0]['align'], $fill);
        $pdf->Cell($header[1]['length']+$header[2]['length']+$header[3]['length']+$header[4]['length']+$header[5]['length']+$header[6]['length'], 5, strtoupper($data['NamaGroup']), 1, '0', 'L', $fill);
        $pdf->Ln();
        $kodegroup = $data['KodeGroup'];
    }
    $fill = false;
    
    $pdf->Cell($header[0]['length'], 5, $no_urut++, 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[1]['length'], 5, $data['NamaBarang'], 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[2]['length'], 5, $data['Satuan'], 1, '0', $header[2]['align'], $fill);
    if($display === "ketersediaan"){
        $pdf->Cell($header[3]['length'], 5, ($data ['RtKetersediaan1']), 1, '0', $header[3]['align'], $fill);
        $pdf->Cell($header[4]['length'], 5, ($data ['RtKetersediaan2']), 1, '0', $header[4]['align'], $fill);
        $pdf->Cell($header[5]['length'], 5, ($data ['RtKetersediaan3']), 1, '0', $header[5]['align'], $fill);
        $pdf->Cell($header[6]['length'], 5, ($data ['RtKetersediaan4']), 1, '0', $header[6]['align'], $fill);
    }elseif($display === "hprodusen"){
        $pdf->Cell($header[3]['length'], 5, pembulatan($data ['RtHargaProdusen1']), 1, '0', $header[3]['align'], $fill);
        $pdf->Cell($header[4]['length'], 5, pembulatan($data ['RtHargaProdusen2']), 1, '0', $header[4]['align'], $fill);
        $pdf->Cell($header[5]['length'], 5, pembulatan($data ['RtHargaProdusen3']), 1, '0', $header[5]['align'], $fill);
        $pdf->Cell($header[6]['length'], 5, pembulatan($data ['RtHargaProdusen4']), 1, '0', $header[6]['align'], $fill);
    }else{
        $pdf->Cell($header[3]['length'], 5, pembulatan($data ['RtHargabarang1']), 1, '0', $header[3]['align'], $fill);
        $pdf->Cell($header[4]['length'], 5, pembulatan($data ['RtHargabarang2']), 1, '0', $header[4]['align'], $fill);
        $pdf->Cell($header[5]['length'], 5, pembulatan($data ['RtHargabarang3']), 1, '0', $header[5]['align'], $fill);
        $pdf->Cell($header[6]['length'], 5, pembulatan($data ['RtHargabarang4']), 1, '0', $header[6]['align'], $fill);
    }
    $pdf->Ln();
}

$pdf->Output();