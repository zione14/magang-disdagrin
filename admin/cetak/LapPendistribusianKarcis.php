<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';
include '../../library/tgl-indo.php';


$Tanggal = isset($_GET['th']) ? mysqli_real_escape_string($koneksi,$_GET['th']) : '';
$sql = "SELECT t.NoTransArusKB, t.TanggalTransaksi, t.KodeBatchPencetakan, t.TotalNilaKB, t.UserName, i.JumlahKreditKB, i.TotalNominal, i.NoSeriAwal, i.NoSeriAkhir, i.KodeBatch, i.Keterangan, m.NamaKB, m.NilaiKB, p.NamaPasar
    FROM traruskb t
    JOIN traruskbitem i ON t.NoTransArusKB=i.NoTransArusKB
    JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
    JOIN mstpasar p ON t.KodePasar=p.KodePasar
    WHERE t.TipeTransaksi='PENGIRIMAN'";
    
    if($Tanggal != null){
        $sql .= " AND t.TanggalTransaksi LIKE '%".$Tanggal."%'  ";
    }
$sql .=" ORDER BY t.TanggalTransaksi ASC";


$data = array();
$stmt = $koneksi->prepare($sql);
if($stmt->execute()){
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		if($row != null){
			array_push($data, $row);
		}
	}
	$stmt->free_result();
	$stmt->close();
}


$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$judul = "LAPORAN PENDISTRIBUSIAN KARCIS RETRIBUSI";
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
$header = array(
    array("label"=>"No", "length"=>10, "align"=>"C"),
    array("label"=>"Jenis Karcis Retribusi", "length"=>40, "align"=>"C"),
    array("label"=>"Nama Pasar", "length"=>45, "align"=>"C"),
    array("label"=>"No Seri", "length"=>35, "align"=>"C"),
    array("label"=>"Kode Seri", "length"=>40, "align"=>"C"),
    array("label"=>"Tanggal Pendistribusian", "length"=>43, "align"=>"C"),
    array("label"=>"Nilai Karcis", "length"=>34, "align"=>"C"),
    array("label"=>"Jumlah Pendistribusian", "length"=>40, "align"=>"C"),
    array("label"=>"Total Nominal", "length"=>40, "align"=>"C")
);

// $pdf = new FPDF( 'L', 'mm', 'F4' );
$pdf = new FPDF( 'L', 'mm', array(215,350) );
$pdf->AddPage();

#tampilkan judul laporan
$pdf->SetFont('Arial','B','16');
$pdf->Cell(0, 5, $judul, '0', 1, 'C');

#tampilkan sub-judul laporan
$pdf->SetFont('Arial','','10');
$pdf->Cell(0, 8, $subjudul, '0', 1, 'C');

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

$no = 0;
foreach($data as $row){
    $pdf->Cell($header[0]['length'], 5, ++$no, 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[1]['length'], 5, $row['NamaKB'], 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[2]['length'], 5, $row['NamaPasar'], 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[3]['length'], 5, $row['NoSeriAwal']."-".$row['NoSeriAkhir'], 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[4]['length'], 5, $row['KodeBatch'], 1, '0', $header[3]['align'], $fill);
    $pdf->Cell($header[5]['length'], 5, TanggalIndo($row['TanggalTransaksi']), 1, '0', $header[4]['align'], $fill);
    $pdf->Cell($header[6]['length'], 5, number_format($row['NilaiKB']), 1, '0', 'R', $fill);
    $pdf->Cell($header[7]['length'], 5, number_format($row['JumlahKreditKB']), 1, '0', 'R', $fill);
    $pdf->Cell($header[8]['length'], 5, number_format($row['TotalNominal']), 1, '0', 'R', $fill);
	$pdf->Ln();
}
$pdf->Output();