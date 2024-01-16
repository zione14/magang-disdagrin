<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
@$id   = htmlspecialchars(base64_decode($_GET['id'])); //kode berita yang akan dikonvert  
@$Mode   = htmlspecialchars($_GET['v']); //kode berita yang akan dikonvert  
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
			<div  align="center"> <font style="font-size: 30px;">LAPORAN USER</font></div>
			<div  align="center"> <font style="font-size: 22px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:30%;">Nama Lengkap</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:30%;">Nama Penanggung Jawab</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:35%;">Alamat</td>
			<!--<td align="center" style="padding-top :5px; border: solid 1px; width:20%;">Jenis User</td>-->
		</tr>
		
		<?php 
		$subquery = "";
		
		if ($Mode != NULL OR $Mode != ''){
			$subquery = "AND JenisPerson Like '%$Mode%'";
		}else{
			$subquery = "";
		}
		// if (base64_decode($_GET['id']) == 'SEMUA') {
			$Query = mysqli_query($koneksi,"SELECT * FROM mstperson where IsVerified=b'1' ".$subquery."  ORDER BY NamaPerson ASC");
		// }else{
			// $Query = mysqli_query($koneksi,"SELECT * FROM mstperson where IsVerified=b'1' and JenisPerson='".base64_decode($_GET['id'])."' ORDER BY NamaPerson ASC");
		// }
			
		$no = 1;
		$tcount = mysqli_num_rows($Query);
		
		if ($tcount == 0 OR $tcount == NULL ) {
			echo '';
		}else {
		while($res=mysqli_fetch_assoc($Query)){ ?>	
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $no."."; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:30%;"><?php echo $res['NamaPerson']; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:30%;"><?php echo $res['PJPerson']; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:35%;"><?php echo $res['AlamatLengkapPerson']; ?></td>
			<!--<td style="padding-top :5px; border: solid 1px; width:20%;"><?php echo $res['JenisPerson']; ?></td>-->
			
		</tr>
		<?php $no++; } } ?>
	</table>


	
		
	<page_footer>
		Halaman [[page_cu]]/[[page_nb]]
	</page_footer>
</page>


<?php  
$filename="LaporanUser.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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