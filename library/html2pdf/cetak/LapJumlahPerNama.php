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
			<div  align="center"> <font style="font-size: 20px;">LAPORAN TOTAL TIMBANGAN DESA <?php echo base64_decode(@$_GET['nmd']).' KECAMATAN '.base64_decode($_GET['nmk']); ?></font></div>
			<div  align="center"> <font style="font-size: 20px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:40%;">Nama Desa</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:35%;">Nama Timbangan</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:20%;">Jumlah</td>
		</tr>
		
		<?php 
		
		$No = 1;			
		$sql =  "SELECT a.IDTimbangan,f.NamaDesa,g.NamaKecamatan,c.NamaTimbangan,d.NamaKelas,e.NamaUkuran,a.KodeUkuran,a.KodeKelas,a.KodeTimbangan,b.KodeKec,b.KodeDesa,h.UserName FROM timbanganperson a JOIN lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) join msttimbangan c on a.KodeTimbangan=c.KodeTimbangan join kelas d on c.KodeTimbangan=d.KodeTimbangan join detilukuran e on (d.KodeTimbangan,d.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) join mstdesa f on b.KodeDesa=f.KodeDesa join mstkec g on b.KodeKec=g.KodeKec join mstperson h on h.IDPerson=a.IDPerson WHERE h.UserName != 'dinas' and b.KodeKab='".KodeKab($koneksi)."'";
								
		if(@base64_decode($_GET['kec'])!=null){
			$sql .= " AND b.KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' ";
		}
		if(@base64_decode($_GET['ds'])!=null){
			$sql .= "AND b.KodeDesa='".htmlspecialchars(base64_decode($_GET['ds']))."' ";
		}
		if(@base64_decode($_GET['id'])!=null){
			$Kode				= base64_decode($_GET['id']);
			@$bagi_uraian 		= explode("#", $Kode);
			@$Timbangan 		= $bagi_uraian[0];
			@$Kelas 			= $bagi_uraian[1];
			@$Ukuran 			= $bagi_uraian[2];
			$sql .= "AND a.KodeTimbangan='$Timbangan' AND a.KodeKelas='$Kelas' AND a.KodeUkuran='$Ukuran' ";
		}
			
		$sql .="  GROUP by a.KodeTimbangan,a.KodeKelas,a.KodeUkuran,b.KodeKec,b.KodeDesa ORDER BY f.NamaDesa,c.NamaTimbangan ASC";
		$result = mysqli_query($koneksi,$sql);
		$tcount = mysqli_num_rows($result);
		if ($tcount == 0 ){
			echo '<tr>
					<td colspan="4" align="center">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>';
		}else{
			while($data = mysqli_fetch_array($result)){
				$query = mysqli_query($koneksi, ("select a.IDTimbangan from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' and b.KodeKec='".$data['KodeKec']."' and b.KodeDesa='".$data['KodeDesa']."'"));
				$nums2 = mysqli_num_rows($query); 
				$Jumlah[] =$nums2; ?>
				
			<tr height="20" style="border: solid 1px;">
				<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $No++; ?></td>
				<td style="padding-top :5px; border: solid 1px; width:40%;"><?php echo $data['NamaDesa'].', '.$data['NamaKecamatan']; ?></td>
				<td style="padding-top :5px; border: solid 1px; width:35%;"><?php echo $data[3]." ".$data[4]." ".$data[5]; ?></td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo number_format($nums2); ?></td>
			</tr>	
		<?php } ?>
			<tr style="border: solid 1px;">
				<td align="center" colspan="3" style="padding-top :5px; border: solid 1px; width:80%;">Total</td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo number_format(array_sum(@$Jumlah)); ?></td>
			</tr>
		<?php } ?>
	</table>
</page>


<?php  
$filename="LapJumlahPerNama.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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