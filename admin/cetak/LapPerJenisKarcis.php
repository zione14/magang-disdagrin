<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';
include '../../library/tgl-indo.php';


$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi,$_GET['keyword']) : '';
$KodeKB  = isset($_GET['KodeKB']) ? mysqli_real_escape_string($koneksi,$_GET['KodeKB']) : '';
$sql = "SELECT t.NoTransArusKB, t.TanggalTransaksi, t.KodeBatchPencetakan, t.TotalNilaKB, t.UserName, i.JumlahDebetKB, i.TotalNominal, i.NoSeriAwal, i.NoSeriAkhir, i.KodeBatch, i.Keterangan, m.NamaKB, m.NilaiKB, t.TipeTransaksi, p.NamaPasar, i.JumlahKirim, t.KodePasar
    FROM traruskb t
    JOIN traruskbitem i ON t.NoTransArusKB=i.NoTransArusKB
    JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
    LEFT JOIN mstpasar p ON t.KodePasar = p.KodePasar
    WHERE (t.TipeTransaksi='PENCETAKAN' OR t.TipeTransaksi='PENGELUARAN')";
    if(@$KodeKB != null){
        $sql .= " AND i.KodeKB = '$KodeKB'  ";
    }
    if($keyword != null){
        $sql .= " AND t.TanggalTransaksi LIKE '%".$keyword."%'  ";
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

$judul = "LAPORAN PER JENIS KARCIS RETRIBUSI PASAR";
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
$header = array(
    array("label"=>"NO", "length"=>9, "align"=>"L"),
    array("label"=>"TANGGAL", "length"=>34, "align"=>"C"),
    array("label"=>"URAIAN", "length"=>73, "align"=>"C"),
    array("label"=>"PASAR/DINAS", "length"=>50, "align"=>"C"),
    array("label"=>"JENIS KARCIS", "length"=>35, "align"=>"C"),
    array("label"=>"NO SERI AWAL", "length"=>32, "align"=>"C"),
    array("label"=>"NO SERI AKHIR", "length"=>33, "align"=>"C"),
    array("label"=>"MASUK (BLOK)", "length"=>32, "align"=>"C"),
    array("label"=>"KELUAR (BLOK)", "length"=>32, "align"=>"C")
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
    $uraian = isset($row['TipeTransaksi']) && $row['TipeTransaksi'] == 'PENCETAKAN' ? 'Pencetakan Karcis' : 'Penyaluran Karcis Ke '.$row['NamaPasar'];
    $pasar  = isset($row['KodePasar']) && $row['KodePasar'] != '' ? $row['NamaPasar'] : 'Disdagrin';
    $awal   = isset($row['TipeTransaksi']) && $row['TipeTransaksi'] == 'PENCETAKAN' ? $row['NoSeriAwal'] : '' ; 
    $akhir  = isset($row['TipeTransaksi']) && $row['TipeTransaksi'] == 'PENCETAKAN' ? $row['NoSeriAkhir'] : '' ;

    $pdf->Cell($header[0]['length'], 5, ++$no, 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[1]['length'], 5, TanggalIndo($row['TanggalTransaksi']), 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[2]['length'], 5, $uraian, 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[3]['length'], 5, $pasar, 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[4]['length'], 5, $row['NamaKB'], 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[5]['length'], 5, $awal, 1, '0', 'R', $fill);
    $pdf->Cell($header[6]['length'], 5, $akhir, 1, '0', 'R', $fill);
    $pdf->Cell($header[7]['length'], 5, number_format($row['JumlahDebetKB']), 1, '0', 'R', $fill);
    $pdf->Cell($header[8]['length'], 5, number_format($row['JumlahKirim']), 1, '0', 'R', $fill);
	$pdf->Ln();
}
$pdf->Output();