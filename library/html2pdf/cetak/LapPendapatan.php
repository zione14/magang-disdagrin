<?php 

session_start();  
ob_start();
date_default_timezone_set('Asia/Jakarta');
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
@$id   = htmlspecialchars(base64_decode($_GET['id'])); //kode berita yang akan dikonvert  
$TanggalTransaksi	= date("Y-m-d");
  
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
		  <th rowspan="3"><img src="../../../images/Assets/logo_cetak.png" style="width:80px;height:100px"/></th>
		  <td style="width: 550px;">
			<div  align="center"> <font style="font-size: 27px;">LAPORAN PENDAPATAN RETRIBUSI</font></div>
			<div  align="center"> <font style="font-size: 22px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:40%;">Nama Lengkap</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:35%;">Alamat</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:20%;">Nilai Retribusi</td>
		</tr>
		
		<?php 
		
		$No = 1;
			
		if (base64_decode($_GET['tgl1']) == '' AND  base64_decode($_GET['tgl2']) == '') {
			$Day = date('Y-m-d');
			$Query = mysqli_query($koneksi,"SELECT a.*, b.NamaPerson, b.AlamatLengkapPerson FROM tractiontimbangan a JOIN mstperson b ON a.IDPerson=b.IDPerson WHERE a.StatusTransaksi='SELESAI' AND b.UserName !='dinas' AND a.IsDibayar='1' AND (date_format(a.TglAmbil, '%Y-%m-%d') BETWEEN '".$Day."' AND '".@$Day."') ORDER BY a.TglTransaksi DESC");
		}else{
			$Query = mysqli_query($koneksi,"SELECT a.*, b.NamaPerson, b.AlamatLengkapPerson FROM tractiontimbangan a JOIN mstperson b ON a.IDPerson=b.IDPerson WHERE a.StatusTransaksi='SELESAI' AND b.UserName !='dinas' AND a.IsDibayar='1' AND (date_format(a.TglAmbil, '%Y-%m-%d') BETWEEN '".base64_decode($_GET['tgl1'])."' AND '".@base64_decode($_GET['tgl2'])."') ORDER BY a.TglTransaksi DESC");
		}
		
			while($data = mysqli_fetch_array($Query)){
				@$Jumlah[] = $data['TotalRetribusi']; ?>
				
			<tr height="20" style="border: solid 1px;">
				<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $No++; ?></td>
				<td style="padding-top :5px; border: solid 1px; width:40%;"><?php echo $data ['NamaPerson'].'<br>No Transaksi :'.
						$data['NoTransaksi'].'<br>'.TanggalIndo($data['TglTransaksi']); ?></td>
				<td style="padding-top :5px; border: solid 1px; width:35%;"><?php echo $data['AlamatLengkapPerson']; ?></td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo number_format($data ['TotalRetribusi']); ?></td>
			</tr>	
		<?php } 
		$tcount = mysqli_num_rows($Query);
		if ($tcount == 0 ){
			echo'<tr>
					<td colspan="4" align="center" style="padding-top :5px; border: solid 1px;">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>';
		}else{
		?>
			<tr style="border: solid 1px;">
				<td align="center" colspan="3" style="padding-top :5px; border: solid 1px; width:80%;">Total</td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo number_format(array_sum(@$Jumlah)); ?></td>
			</tr>
		<?php } ?>
	</table>
</page>


<?php  
$filename="LapPendapatan.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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
  
 }  
 catch(HTML2PDF_exception $e) { echo $e; }  
?>  