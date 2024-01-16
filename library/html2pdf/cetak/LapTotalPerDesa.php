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
			<div  align="center"> <font style="font-size: 21px;">LAPORAN TOTAL TIMBANGAN KECAMATAN <?php echo $_GET['nm']; ?></font></div>
			<div  align="center"> <font style="font-size: 22px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:7%;">No</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:60%;">Nama Desa</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:33%;">Jumlah Timbangan</td>
		</tr>
		
		<?php 
		$Query = mysqli_query($koneksi,"SELECT NamaDesa,KodeDesa FROM mstdesa WHERE KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' ORDER BY NamaDesa ");
			
		$no = 1;
		$tcount = mysqli_num_rows($Query);
		
		if ($tcount == 0 OR $tcount == NULL ) {
			echo '';
		}else {
		while($data = mysqli_fetch_array($Query)){
			
			$sql2 = @mysqli_query($koneksi, "SELECT a.IDTimbangan FROM timbanganperson a 
				JOIN lokasimilikperson b ON (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) 
				Join mstperson c on c.IDPerson=a.IDPerson 
				WHERE b.KodeDesa='".$data['KodeDesa']."' and b.KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' and c.UserName != 'dinas' AND  a.StatusUTTP='Aktif' 
				GROUP BY a.IDTimbangan");
			$jumlah = @mysqli_num_rows($sql2); 
			$Total[] = $jumlah;
		?>	
		<tr style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:7%;"><?php echo $no."."; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:60%;"><?php echo $data['NamaDesa']; ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:33%;"><?php echo $jumlah; ?></td>
			
		</tr>
		<?php $no++; } } ?>
		<tr style="border: solid 1px;">
			<td align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:67%;">Total</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:33%;"><?php echo number_format(array_sum($Total)); ?></td>
		</tr>
	</table>


	
		
	<page_footer>
		Halaman [[page_cu]]/[[page_nb]]
	</page_footer>
</page>


<?php  
$filename="LapTotalPerDesa.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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