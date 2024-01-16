<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
$html='
<style>
h3 {
	color: black;
	font-family: times;
	font-size: 16pt;
	text-align: center;
}
h4 {
	color: black;
	font-family: times;
	font-size: 14pt;
	text-align: center;
}

.border-isi {
	border-top: 0;
	border-bottom: 0;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	vertical-align: middle;
	padding: 4px;
}

.border {
	border-collapse: collapse;
	border: 1px solid #000;
	vertical-align: middle;
	text-align: center;
	padding: 4px;
}

.border-left {
	border-collapse: collapse;
	border: 1px solid #000;
	text-align: left;
	padding: 4px;
}

.border-right {
	border-collapse: collapse;
	border: 1px solid #000;
	text-align: right;
	padding: 4px;
}

.border-lr-r {
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	text-align: right;
	padding: 4px;
}

.border-lr-l {
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	text-align: left;
	padding: 4px;
}

.border-lr-c {
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	text-align: center;
	padding: 4px;
}

table,td {
	font-family: times;
	font-size: 12pt;
}
</style>
<table style="width: 100%" align="center">
<tr>
<td align="center">
<h4>DAFTAR ISIAN HARGA RATA-RATA BEBERAPA BAHAN POKOK PANGAN DI KABUPATEN JOMBANG</h4>
</td>
</tr>
<tr><td></td></tr>
<tr><td></td></tr>
</table>';
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$TanggalKemarin = date('Y-m-d', strtotime('-1 days', strtotime($Tanggal)));
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$tglSekarang = date('d-m-Y', strtotime($Tanggal));
$tglKemarin = date('d-m-Y', strtotime($TanggalKemarin));
$NamaPasar = 'Semua Pasar';
if($KodePasar!=''){
	$sql_pasar = "SELECT NamaPasar FROM mstpasar WHERE KodePasar = '$KodePasar'";
	$res_pasar = mysqli_query($koneksi, $sql_pasar);
	if($res_pasar){
		$row_pasar = mysqli_fetch_assoc($res_pasar);
		$NamaPasar = $row_pasar['NamaPasar'];
	}
}

// Perhitungan tanggal untuk stok atau persedian
// 2020-06-02
$tglpecah = date_create($Tanggal);
 $Tgl = date_format($tglpecah,"d");
 $Bln = date_format($tglpecah,"m");
 $Thn = date_format($tglpecah,"Y");

$Periode = $Thn.'-'.$Bln.'-'.$Tgl;
//Periode Sebelumnya
if($Tgl >= '01' && $Tgl <= '07'){
	$blnke = $Bln-1;
	if($Bln<=9){
		$PeriodeSebelumnya = $Thn.'-0'.$blnke.'-22';
	}else{
		$PeriodeSebelumnya = $Thn.'-'.$blnke.'-22';
	}
	$Periode = $Thn.'-'.$Bln.'-01';

}elseif($Tgl >= '22' && $Tgl <= '31'){

	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-15';
	$Periode = $Thn.'-'.$Bln.'-22';

}elseif($Tgl >= '08' && $Tgl <= '14'){

	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-01';
	$Periode = $Thn.'-'.$Bln.'-08';

}elseif($Tgl >= '15' && $Tgl <= '21'){
	$PeriodeSebelumnya = $Thn.'-'.$Bln.'-08';
	$Periode = $Thn.'-'.$Bln.'-15';
}

$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';
$DSPLY = "";
if($display === "ketersediaan"){
    $DSPLY = "Jumlah Ketersediaan";
}elseif($display === "hprodusen"){
    $DSPLY = "Harga Produsen";
}else{
    $DSPLY = "Harga Konsumen";
}

$jenis = isset($display) && $display != 'ketersediaan' ?  'Rp.' : 'Stok';
$kemarin = isset($display) && $display != 'ketersediaan' ?  $tglKemarin : $PeriodeSebelumnya;
$sekarang = isset($display) && $display != 'ketersediaan' ?  $tglSekarang : $Periode; 
$html.='
<table>
<tr>
<td style="width:20%;">Tanggal Pengamatan</td>
<td style="width:3%;">:</td>
<td>'.$tglSekarang.'</td>
</tr>
<tr>
<td style="width:20%;">Pasar</td>
<td style="width:3%;">:</td>
<td>'.$NamaPasar.'</td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
<tr>
<td></td>
<td></td>
<td></td>
</tr>
</table>
<table style="width:100%; border-collapse: collapse;" border="1">
<tr bgcolor="#e6e5e3">
<td rowspan="2" class="border" style="width:4%;">No</td>
<td rowspan="2" class="border" style="width:10%;">Nama Bahan Pokok dan Jenisnya</td>
<td rowspan="2" class="border" style="width:10%;">Satuan</td>
<td colspan="2" class="border" style="width:14%;">'.$DSPLY.'</td>
<td colspan="2" class="border" style="width:14%;">Perubahan</td>
<td rowspan="2" class="border" style="width:10%;">Ket</td>
<td rowspan="2" class="border" style="width:15%;">Perubahan/Kondisi saat ini</td>
</tr>
<tr bgcolor="#e6e5e3">
<td class="border">Kemarin<br>'.$kemarin.'</td>
<td class="border">Hari Ini<br>'.$sekarang.'</td>
<td class="border">'.$jenis.'</td>
<td class="border">%</td>
</tr>
';
if($display === "ketersediaan"){

$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, 
IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang
FROM mstbarangpokok b
LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup

LEFT JOIN (
SELECT r.TglInput, r.KodeBarang,  SUM(IFNULL(r.Stok, 0)) AS Ketersediaan
FROM stokpedagang r
WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND r.Periode = '$PeriodeSebelumnya'
GROUP BY r.KodeBarang ASC
) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang

LEFT JOIN (
SELECT r.TglInput, r.KodeBarang, SUM(IFNULL(r.Stok, 0)) AS Ketersediaan
FROM stokpedagang r
WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND r.Periode = '$Periode'
GROUP BY r.KodeBarang ASC
) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang

WHERE  (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009')  $sql_pencarian
GROUP BY b.KodeBarang
ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";

}else{
$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, 
IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal
FROM mstbarangpokok b
LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
LEFT JOIN (
SELECT r.Tanggal, r.KodeBarang, AVG(IFNULL(r.HargaBarang, 0)) AS HargaBarang, AVG(IFNULL(r.HargaProdusen, 0)) AS HargaProdusen, AVG(IFNULL(r.Ketersediaan, 0)) AS Ketersediaan, r.Keterangan 
FROM reporthargaharian r
WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND DATE(r.Tanggal) = DATE_SUB(date('$Tanggal'), INTERVAL 1 DAY)
ORDER BY r.Tanggal ASC
) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
LEFT JOIN (
SELECT r.Tanggal, r.KodeBarang, AVG(IFNULL(r.HargaBarang, 0)) AS HargaBarang, AVG(IFNULL(r.HargaProdusen, 0)) AS HargaProdusen, AVG(IFNULL(r.Ketersediaan, 0)) AS Ketersediaan, r.Keterangan 
FROM reporthargaharian r
WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND DATE(r.Tanggal) = date('$Tanggal')
ORDER BY r.Tanggal ASC
) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
WHERE b.IsAktif = '1'
GROUP BY b.KodeBarang
ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
}
$a = 0;
//die($sql);
$result = mysqli_query($koneksi,$sql);

if($result){
	$kodegroup = "";
	while($data = mysqli_fetch_assoc($result)) 
	{
		if($kodegroup !== $data['KodeGroup']){
			$html .= '<tr bgcolor="#f7f7f7">
			<td class="border" width="50px">'.(++$a).'</td>
			<td class="border-left" colspan="8"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
			</tr>';
			$kodegroup = $data['KodeGroup'];
		}
		$html .= '<tr>
		<td class="border" width="50px"></td>
		<td class="border-left">'.$data['NamaBarang'].'</td>
		<td class="border">'.$data['Satuan'].'</td>
		<td class="border-right">'.
		($display == 'hkonsumen' ? 'Rp.'.number_format($data['HargaBarangKemarin']) : ($display == 'hprodusen' ? 'Rp.'.number_format($data['HargaProdusenKemarin']) : $data['KetersediaanKemarin']))
		.'</td>
		<td class="border-right">'.
		($display == 'hkonsumen' ? 'Rp.'.number_format($data['HargaBarangSekarang']) : ($display == 'hprodusen' ? 'Rp.'.number_format($data['HargaProdusenSekarang']) : $data['KetersediaanSekarang']))
		.'</td>';

		$class_naik = '';
		$icon_ = '';
		$html .= '<td class="border-right">';
		$dataselisi = ""; 
		$selisi =  ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : ($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) < 1 ? 0 : ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : ($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) - ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : ($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin']));
		if($selisi < 0){
			$icon_ = '';
			$tmpselisi = $selisi * -1;
			$class_naik = "color='#28c904'";
			if($display == 'ketersediaan'){
				$dataselisi = "- ".$tmpselisi;
			}else{
				$dataselisi = "- Rp.".number_format($tmpselisi);
			}
		}elseif($selisi == 0){
			$icon_ = '-';
			$class_naik = '';
			$dataselisi = '--';
		}else{
			$icon_ = '';
			$class_naik = "color='#fc0f03'";
			if($display == 'ketersediaan'){
				$dataselisi = $selisi;
			}else{
				$dataselisi = "Rp.".number_format($selisi);
			}
		}
		$html .= '<font style="'.$class_naik.'">'.$dataselisi.'</font>';
		$html .= '</td>
		<td class="border-right">';
		if(($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : ($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang'])) == 0 && ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : ($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin'])) == 0 ){
			$html .= '<font style="'.$class_naik.'">-- '.$icon_.'</font>';
		}else{
			$persenSelisi = 0;
			if($selisi == ($display == 'hkonsumen' ? $data['HargaBarangSekarang'] : ($display == 'hprodusen' ? $data['HargaProdusenSekarang'] : $data['KetersediaanSekarang']))){
				$persenSelisi = 100;
			}else if($selisi == 0 || ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : ($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin'])) == 0){
				$persenSelisi = 0;
			}else{
				$persenSelisi = ($selisi / ($display == 'hkonsumen' ? $data['HargaBarangKemarin'] : ($display == 'hprodusen' ? $data['HargaProdusenKemarin'] : $data['KetersediaanKemarin']))) * 100;
			}
			if(is_infinite($persenSelisi) || is_nan($persenSelisi)){    
				$html .= '<font style="'.$class_naik.'">'.number_format(0, 2).' % '.$icon_.'</font>';
			}else{
				$html .= '<font style="'.$class_naik.'">'.number_format($persenSelisi, 2).' % '.$icon_.'</font>';
			}
		}
		$html .= '</td>
		<td class="border"></td>
		<td class="border"></td>
		</tr>';
	}
}else{
	print_r(mysqli_error($koneksi));
}

$html.='</table>';
//die($html);
ob_get_clean();

$filename="Laporan_RatarataHarga.pdf";
//$content = ob_get_clean();  
require_once('../../html2pdf/html2pdf.class.php');
try  
{  
	$html2pdf = new HTML2PDF('L','A4','en', false, 'ISO-8859-15',array(18, 5, 15, 3));  
	$html2pdf->setDefaultFont('Arial');  
	//$html2pdf->writeHTML($content, true, false, true, false, '');  
	$html2pdf->writeHTML($html, isset($_GET['vuehtml']));
	$html2pdf->Output($filename);  
	//$html2pdf->Output('../doc/'.$filename, 'F');

}  
catch(HTML2PDF_exception $e) { echo $e;  }
?>  