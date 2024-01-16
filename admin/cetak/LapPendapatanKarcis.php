<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';
include '../../library/tgl-indo.php';


$BulanTahun = isset($_GET['Bulan']) ? mysqli_real_escape_string($koneksi,$_GET['Bulan']) : date('Y-m');
$sql =  "SELECT p.NamaPasar, k.NamaKB, t.NoTransArusKB, IFNULL(c.JumlahPengiriman, 0) as JumlahPengiriman,  (IFNULL(c.JumlahPengiriman, 0) * k.NilaiKB) as NominalPengiriman, IFNULL(d.JumlahPengeluaran, 0) as JumlahPengeluaran, (IFNULL(d.JumlahPengeluaran, 0) * k.NilaiKB) as NominalPengeluaran 
FROM traruskbitem i
JOIN mstkertasberharga k ON i.KodeKB=k.KodeKB
JOIN traruskb t ON i.NoTransArusKB=t.NoTransArusKB
JOIN mstpasar p ON t.KodePasar=p.KodePasar
LEFT JOIN (
    SELECT  SUM(c.JumlahKirim) as JumlahPengiriman,  SUM(c.TotalNominal) as NominalPengiriman,c.KodeKB, d.KodePasar
    FROM traruskbitem c
    JOIN traruskb d ON c.NoTransArusKB=d.NoTransArusKB
    where (date_format(d.TanggalTransaksi, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun') AND d.TipeTransaksi ='PENGIRIMAN'
    GROUP BY c.KodeKB,d.KodePasar
) c ON c.KodePasar = t.KodePasar AND c.KodeKB=i.KodeKB
LEFT JOIN (
    SELECT  SUM(c.JumlahKreditKB) as JumlahPengeluaran,  SUM(c.TotalNominal) as NominalPengeluaran,c.KodeKB, d.KodePasar
    FROM traruskbitem c
    JOIN traruskb d ON c.NoTransArusKB=d.NoTransArusKB
    where (date_format(d.TanggalTransaksi, '%Y-%m') BETWEEN '$BulanTahun' AND '$BulanTahun') AND d.TipeTransaksi ='PENGELUARAN'
    GROUP BY c.KodeKB,d.KodePasar
) d ON d.KodePasar = t.KodePasar AND d.KodeKB=i.KodeKB
GROUP by t.KodePasar, i.KodeKB
ORDER by t.KodePasar, i.KodeKB";
        


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

$judul = "LAPORAN PENDAPATAN KARCIS RETRIBUSI PASAR PADA ".strtoupper($BulanIndo[date('m', strtotime($BulanTahun)) - 1].' '.date('Y', strtotime($BulanTahun)))."";
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
$header = array(
    array("label"=>"No", "length"=>10, "align"=>"C"),
    array("label"=>"Jenis Karcis", "length"=>70, "align"=>"C"),
    array("label"=>"Jumlah Diterima", "length"=>60, "align"=>"C"),
    array("label"=>"Nominal Penggunaan Karcis", "length"=>65, "align"=>"C"),
    array("label"=>"Jumlah Dikeluar", "length"=>60, "align"=>"C"),
    array("label"=>"Nominal Pendapatan Diterima", "length"=>65, "align"=>"C")
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
$NamaPasar = "";
$no = 0;
if($data){
    foreach($data as $row){
        if($NamaPasar !== $row['NamaPasar']){
            $fill = true;
            $pdf->Cell($header[0]['length'], 5, '', 1, $header[0]['align'], $fill);
            $pdf->Cell($header[1]['length']+$header[2]['length']+$header[3]['length']+$header[4]['length']+$header[5]['length'], 5, strtoupper($row['NamaPasar']), 1, '0', 'L', $fill);
            $pdf->Ln();
            $NamaPasar = $row['NamaPasar'];

        }
        $fill = false;
        

        $pdf->Cell($header[0]['length'], 5, ++$no, 1, '0', $header[1]['align'], $fill);
        $pdf->Cell($header[1]['length'], 5, $row['NamaKB'], 1, '0', 'L', $fill);
        $pdf->Cell($header[2]['length'], 5, number_format($row['JumlahPengiriman']), 1, '0', 'R', $fill);
        $pdf->Cell($header[3]['length'], 5, number_format($row['NominalPengiriman']), 1, '0', 'R', $fill);
        $pdf->Cell($header[4]['length'], 5, number_format($row['JumlahPengeluaran']), 1, '0', 'R', $fill);
        $pdf->Cell($header[5]['length'], 5, number_format($row['NominalPengeluaran']), 1, '0', 'R', $fill);
    	$pdf->Ln();
        // ++$no;
       
    }
}else{
    $pdf->Cell('329', 5, 'TIDAK ADA DATA', 1, '0', $header[1]['align'], $fill);
}
$pdf->Output();