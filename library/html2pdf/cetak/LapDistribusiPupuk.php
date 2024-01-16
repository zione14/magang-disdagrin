<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
// $Tanggal = date('Y-m-d');
@$tglcari1     = htmlspecialchars($_GET['tgl1']);
@$tgl1 = isset($tglcari1) && $tglcari1 != null ? @$tglcari1 : $Tanggal; 
//operasi pengeurangan tanggal sebanyak 1 hari
@$tglkemarin = date('Y-m', strtotime('-1 month', strtotime($tgl1))); 
@$dt       = htmlspecialchars($_GET['dt']);
@$ppk      = htmlspecialchars($_GET['ppk']);

$tahun = date_create($tgl1);
$bulan = date_format($tahun,"m");

$NamaBulan = array (1 =>   'Januari',
	'Februari',
	'Maret',
	'April',
	'Mei',
	'Juni',
	'Juli',
	'Agustus',
	'September',
	'Oktober',
	'November',
	'Desember'
);

?>

<page style="width:100%;" backtop="5mm" backbottom="10mm"  backright="5mm">
	<table>
		<tr>
		  <td style="width: 550px;" >
			<div> <font style="font-size: 25px;">LAPORAN DISTRIBUSI PUPUK</font></div>
		  </td>
		</tr>
	</table><hr>
  
	<table style="width:100%; margin-top: 10px; margin-right: 30px;">
		<tr>
			<td style="width:23%; vertical-align: top;">Nama Distributor</td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"> <?php echo NamaCari($dt, 'mstperson', 'IDPerson', 'NamaPerson', $koneksi);?></td>
		</tr>
		<tr>
			<td style="width:23%; vertical-align: top;">Nama Pupuk </td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"><?php echo NamaCari($ppk,'mstpupuksubsidi', 'KodeBarang', 'NamaBarang', $koneksi);?></td>
		</tr>
		<tr>
			<td style="width:23%; vertical-align: top;">Periode Transaksi</td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"> <?php echo strtoupper($NamaBulan[(int)$bulan])." ".date_format($tahun,"Y") ;?></td>
		</tr>
	</table>
	<?php 
						
		@$TotalScore	= ResultScore($ppk,$tgl1,$tglkemarin,$dt,$koneksi);
	  ?>
	 <h4>Stok Pupuk Sebelumnya : <span id="StokBarang"> <?php echo  @$TotalScore; ?></span> </h4><br>
		
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<!--<td align="center" style="padding-top :5px; border: solid 1px; width:20%;">Tanggal Transaksi</td>-->
			<td align="center" style="padding-top :5px; border: solid 1px; width:30%;">Kios</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;">Satuan</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:25%;">Jumlah Masuk</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:25%;">Jumlah Keluar</td>
		</tr>
	
		<?php 
		
		$sql =  "SELECT b.JumlahMasuk,b.JumlahKeluar,b.TanggalTransaksi,c.NamaPerson,b.Keterangan,e.Penerimaan,e.Penjualan
		FROM sirkulasipupuk b
		JOIN mstperson c ON b.IDPerson=c.IDPerson
		LEFT JOIN (
			SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan,e.KodeBarang,e.IDPerson
			FROM sirkulasipupuk e
			JOIN mstperson d ON e.IDPerson=d.IDPerson
			WHERE e.KodeBarang = KodeBarang AND e.Keterangan= '$tglkemarin' AND d.ID_Distributor='$dt'
			GROUP BY e.KodeBarang
		) e ON e.KodeBarang = b.KodeBarang 
		WHERE b.KodeBarang = '$ppk' AND c.ID_Distributor='$dt'  AND b.Keterangan = '$tgl1'";											
		$result = mysqli_query($koneksi,$sql);
		@$tcount = mysqli_num_rows($result);
		$no = 0;
			while($data = mysqli_fetch_array($result)) {
			$Penerimaan[] = $data['JumlahMasuk'];
			$Penjualan[]  = $data['JumlahKeluar'];
			@$Sebelumnya = number_format($data['Penerimaan']-$data['Penjualan']); 	
				
		?>
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo ++$no."."; ?></td>
			<!--<td align="center" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo TanggalIndo($data['TanggalTransaksi']); ?></td>-->
			<td style="padding-top :5px; border: solid 1px; width:30%;">
				<?php echo $data['NamaPerson']?>
			</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;">TON</td>
			<td align="right" style="padding-top :5px; border: solid 1px; width:15%;"><?=number_format($data['JumlahMasuk'], 2)?></td>
			<td align="right" style="padding-top :5px; border: solid 1px; width:15%;"><?=number_format($data['JumlahKeluar'], 2)?></td>
		</tr>
		<?php } ?>
		
		
		
		<?php 
			if($tcount==0){
				echo '
				<tr  style="border: solid 1px;"  height="20">
					<td colspan="6" align="center"  style="border: solid 1px;">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>
				';
			}else{
				
				echo '
				
				<tr  style="border: solid 1px"  height="20">
					<td colspan="4" align="center" style="border: solid 1px;"><strong>Stok Sekarang</strong></td>
					<td colspan="2"  style="border: solid 1px;" align="right"><strong>'.number_format($Sebelumnya+array_sum($Penerimaan)-array_sum($Penjualan), 2).'  </strong></td>
				</tr>
				';
			}

		?>
		
	</table>
		<!--==============================================================DATA PARAF================================================================= -->
	
	
	
</page>
<?php  

	// function ResultScore($KodeBarang,$Tanggal,$Tanggal2,$TanggalKemaren,$login_id,$koneksi){
	// 	$Query1 =  "SELECT a.TanggalTransaksi,((IFNULL (b.Pembelian, 0))-IFNULL (b.Penjualan, 0)) as StokBarang 
	// 	FROM sirkulasipupuk a
	// 	LEFT JOIN (
	// 		SELECT IFNULL(SUM(JumlahMasuk), 0) as Pembelian, IFNULL(SUM(JumlahKeluar), 0) as Penjualan,b.KodeBarang,b.IDPerson
	// 		FROM sirkulasipupuk b
	// 		WHERE b.KodeBarang = KodeBarang AND (date_format(b.TanggalTransaksi, '%Y-%m-%d') BETWEEN '1991-01-01' AND '$TanggalKemaren') AND b.IDPerson=IDPerson
	// 		GROUP BY b.KodeBarang
	// 	) b ON (b.KodeBarang, b.IDPerson) = (a.KodeBarang,a.IDPerson) 
	// 	WHERE a.IDPerson='$login_id' and a.KodeBarang='$KodeBarang'
	// 	ORDER BY a.NoTransaksi DESC";
	// 	$res1   = mysqli_query($koneksi, $Query1);
	// 	$result1 = mysqli_fetch_array($res1);
		
	// 	@$Sebelumnya = number_format($result1['StokBarang']); 
		
	// 	return($Sebelumnya);
		
	// }
	function ResultScore($KodeBarang,$Tanggal,$TanggalKemaren,$login_id,$koneksi){
		$Query1 =  "SELECT ((IFNULL (SUM(b.JumlahMasuk), 0))-IFNULL (SUM(b.JumlahKeluar), 0)) as StokBarang 
				FROM sirkulasipupuk b
				JOIN mstperson c ON b.IDPerson=c.IDPerson
				WHERE b.KodeBarang = '$KodeBarang' AND c.ID_Distributor='$login_id'  AND b.Keterangan = '$TanggalKemaren'";
		$res1   = mysqli_query($koneksi, $Query1);
		$result1 = mysqli_fetch_array($res1);
		@$Sebelumnya = number_format($result1['StokBarang'], 2); 
		return($Sebelumnya);
		
	}
	
	function NamaCari($kode, $table, $where, $Dicari, $koneksi){
		$Query =  "SELECT $Dicari
		FROM $table
		WHERE $where='$kode'";
		$res   = mysqli_query($koneksi, $Query);
		$result = mysqli_fetch_array($res);
		return($result[0]);
		
	}


$filename="Laporan_Distribusipupuk.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
//==========================================================================================================  
//Copy dan paste langsung script dibawah ini,untuk mengetahui lebih jelas tentang fungsinya silahkan baca-baca tutorial tentang HTML2PDF  
//==========================================================================================================  
$content = ob_get_clean();  
// $content = '<page style="font-family: freeserif">'.nl2br($content).'</page>';  
 require_once('../../html2pdf/html2pdf.class.php');
 try  
 {  
  $html2pdf = new HTML2PDF('P','F4','en', false, 'ISO-8859-15',array(18, 5, 15, 3));  
  $html2pdf->setDefaultFont('Arial');  
  $html2pdf->writeHTML($content, isset($_GET['vuehtml']));  
  $html2pdf->Output($filename);  
  $html2pdf->Output('../doc/'.$filename, 'F');
  
 }  
 catch(HTML2PDF_exception $e) { echo $e; }  
?>  