<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';
include '../../library/tgl-indo.php';


$KodePasar = isset($_GET['KodePasar']) ? mysqli_real_escape_string($koneksi,$_GET['KodePasar']) : '';
$sql = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  e.NamaPerson, e.AlamatLengkapPerson, e.TglAktif, p.NamaPasar
    FROM lapakpasar l 
    JOIN mstpasar p ON p.KodePasar=l.KodePasar
    LEFT JOIN (
        SELECT p.NamaPerson, lp.IDPerson, lp.KodePasar, lp.IDLapak, p.AlamatLengkapPerson, lp.TglAktif
        FROM lapakperson lp 
        JOIN mstperson p ON lp.IDPerson=p.IDPerson
        WHERE lp.IsAktif=b'1'
    ) e ON e.KodePasar = l.KodePasar AND e.IDLapak = l.IDLapak ";
    
    if($KodePasar != null){
        $sql .= " WHERE l.KodePasar='$KodePasar' ";
    }
$sql .=" ORDER BY IDLapak ASC";


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

$LapakTerpakai  = ResultData('' ,'Lapak Terpakai', $koneksi, $KodePasar);
$LapakKosong    = ResultData('', 'Lapak Kosong', $koneksi, $KodePasar);

$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$NamaPasar = isset($_GET['KodePasar']) && $_GET['KodePasar'] != '' ? strtoupper($data[0]['NamaPasar']) : 'SEMUA PASAR';
$judul = "LAPORAN KETERSEDIAAN LAPAK ".$NamaPasar." ";
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
$header = array(
    array("label"=>"NO", "length"=>10, "align"=>"L"),
    array("label"=>"NAMA LAPAK", "length"=>70, "align"=>"C"),
    array("label"=>"NAMA PEMILIK", "length"=>60, "align"=>"C"),
    array("label"=>"ALAMAT PEMILIK", "length"=>90, "align"=>"C"),
    array("label"=>"TANGGAL SEWA", "length"=>50, "align"=>"C"),
    array("label"=>"STATUS", "length"=>45, "align"=>"C")
);

// $pdf = new FPDF( 'L', 'mm', 'F4' );
$pdf = new FPDF( 'L', 'mm', array(215,350) );
$pdf->AddPage();

#tampilkan judul laporan
$pdf->SetFont('Arial','B','16');
$pdf->Cell(0, 5, $judul, '0', 1, 'C');

#tampilkan sub-judul laporan
$pdf->SetFont('Arial','','13');
$pdf->Cell(0, 8, $subjudul, '0', 1, 'C');

#tampilkan judul laporan
$pdf->SetFont('Arial','B','11');
$pdf->Cell(0, 5, "Lapak Terpakai            : ".$LapakTerpakai, '0', 1, 'L');
$pdf->SetFont('Arial','B','11');
$pdf->Cell(0, 3, "Lapak Tidak Terpakai  : ".$LapakKosong, '0', 1, 'L');

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
    $TglAktif = isset($row['TglAktif']) && $row['TglAktif'] != '' ? TanggalIndo($row['TglAktif']) : '';
    $Status   = isset($row['TglAktif']) && $row['TglAktif'] != '' ? 'Terpakai' : 'Tidak Terpakai';

    $pdf->Cell($header[0]['length'], 5, ++$no, 1, '0', $header[1]['align'], $fill);
    $pdf->Cell($header[1]['length'], 5, $row['BlokLapak'].' Nomor '.$row['NomorLapak'], 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[2]['length'], 5, $row['NamaPerson'], 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[3]['length'], 5, $row['AlamatLengkapPerson'], 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[4]['length'], 5, $TglAktif, 1, '0', $header[0]['align'], $fill);
    $pdf->Cell($header[5]['length'], 5, $Status, 1, '0', $header[0]['align'], $fill);
	$pdf->Ln();
}
$pdf->Output();

function ResultData($Jenis,$Tabel,$koneksi, $KodePasar){

    $where  = isset($KodePasar) && $KodePasar != '' ? "WHERE KodePasar = '".$KodePasar."'" :  '';
    if($Tabel == 'Lapak Terpakai'){
        $Query = mysqli_query($koneksi,"SELECT IDLapak FROM lapakperson $where");
        $RowData = mysqli_num_rows($Query);
        $Data = $RowData;   
        
    }elseif($Tabel == 'Lapak Kosong'){
        $lapak = mysqli_query($koneksi,"SELECT IDLapak FROM lapakpasar $where");
        $ada = mysqli_num_rows($lapak);
        $tersedia = $ada;   
        
        $Query = mysqli_query($koneksi,"SELECT IDLapak FROM lapakperson $where");
        $RowData = mysqli_num_rows($Query);
        $terpakai = $RowData;   
        
        $Data = $tersedia-$terpakai;
    } 
    return($Data);
}   