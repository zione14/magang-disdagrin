<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
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
		  <td style="width: 900px;">
			<div  align="center"> <font style="font-size: 30px;">TABEL DATA TIMBANGAN</font></div>
			<div  align="center"> <font style="font-size: 22px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<td align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:40%;">Nama Timbangan </td>
			<td align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:33%;">Kode Pelaporan</td>
			<td align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:22%;">Nilai Retribusi</td>
		</tr>
		<tr height="20" style="border: solid 1px;">
			
			<td align="center" style="padding-top :5px; border: solid 1px; width:11%;">Kantor</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:11%;">Lokasi</td>
		</tr>
		<?php 
		
		$No = 1;
		$sql = "SELECT a.JenisTimbangan,a.NamaTimbangan,b.KodeKelas,a.KodeTimbangan,b.NamaKelas,c.RetribusiDikantor,c.RetribusiDiLokasi,c.KodeUkuran,c.NamaUkuran FROM msttimbangan a join kelas b on a.KodeTimbangan=b.KodeTimbangan join detilukuran c on (b.KodeKelas,b.KodeTimbangan)=(c.KodeKelas,c.KodeTimbangan)";
		
		
		$sql.=" ORDER BY a.NamaTimbangan ASC";
		$Query = mysqli_query($koneksi,$sql);
		while($data = mysqli_fetch_array($Query)){ ?>	
		
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $No++; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:40%;"><?php echo $data ['NamaTimbangan'].'<br>'.$data ['NamaKelas'].' '.$data ['NamaUkuran']; ?></td>
			<td style="padding-top :5px; border: solid 1px; width:35%;"><?php echo $data ['JenisTimbangan']; ?></td>
			<td align='right' style="padding-top :5px; border: solid 1px; width:11%;"><?php echo number_format($data['RetribusiDikantor']); ?></td>
			<td align='right' style="padding-top :5px; border: solid 1px; width:11%;"><?php echo number_format($data['RetribusiDiLokasi']); ?></td>
			
		</tr>
		<?php } ?>
	</table>
</page>


<?php  
$filename="MasterTimbangan.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
//==========================================================================================================  
//Copy dan paste langsung script dibawah ini,untuk mengetahui lebih jelas tentang fungsinya silahkan baca-baca tutorial tentang HTML2PDF  
//==========================================================================================================  
$content = ob_get_clean();  
// $content = '<page style="font-family: freeserif">'.nl2br($content).'</page>';  
 require_once('../../html2pdf/html2pdf.class.php');
 try  
 {  
  $html2pdf = new HTML2PDF('L','F4','en', false, 'ISO-8859-15',array(18, 5, 15, 3));  
  $html2pdf->setDefaultFont('Arial');  
  $html2pdf->writeHTML($content, isset($_GET['vuehtml']));  
  $html2pdf->Output($filename);  
  
 }  
 catch(HTML2PDF_exception $e) { echo $e; }  
?>  