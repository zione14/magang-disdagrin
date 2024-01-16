<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
$KodePasar = isset($_GET['psr'])  ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$Tanggal1  = isset($_GET['tgl1']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['tgl1'])) : '';
$Tanggal2  = isset($_GET['tgl2']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['tgl2'])) : '';



$Tanggal=date('Y-m-d');

$sql = "SELECT NamaPasar FROM mstpasar WHERE KodePasar = '$KodePasar'";
$res_select = $koneksi->query($sql);
$RowData = mysqli_fetch_assoc($res_select);


if($KodePasar != ''){
	$namapasar = strtoupper($RowData['NamaPasar']);
}else{
	$namapasar = "SEMUA PASAR";
}
?>
<style>
table th {
  padding: 10px;
  border-left:1px solid #e0e0e0;
  border-bottom: 1px solid #e0e0e0;
  background: #5c54cc;
  color :white;
}
</style>
<page style="width:100%;" backtop="5mm" backbottom="10mm"  backright="5mm">
	<table>
		<tr>
		  <td rowspan="3"><img src="../../../images/Assets/logo_cetak.png" style="width:75px;height:100px"/></td>
		  <td align="center" style="width: 550px; padding-top:10px;">
			<font style="font-size: 18px; line-height: 1.3;">PEMERINTAH KABUPATEN JOMBANG</font><br>
			<font style="font-size: 23px; line-height: 1.3;">DINAS PERDAGANGAN DAN PERINDUSTRIAN</font><br>
			<font style="font-size: 16px; line-height: 1.3;">Jl. KH. Wahid Hasyim No.143 Jombang 61419</font><br>
			<font style="font-size: 14px; line-height: 1.3;">Telp. ( 0321 ) 874549 Fax (0321) 850494 Email: disperdagperin@jombangkab.go.id<br>
			Website: jombangkab.go.id</font>
		 </td>
		</tr>
    </table><hr class="garis">
	
	
	<table>
		<tr>
		  <td align="center" style="width: 650px; padding-top:10px;">
			<strong><font style="font-size: 16px; line-height: 1.3;">LAPORAN PENDAPATAN RETRIBUSI</font></strong><br>
			<strong><font style="font-size: 16px; line-height: 1.3;"><?=$namapasar?></font></strong><br>
		 </td>
		</tr>
    </table>
	
	
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr>
			<th align="center" style="border : 1px; width:5%; ">No</th>
			<th align="center" style="border : 1px; width:35%;">Nama </th>
			<!--<th align="center" style="border : 1px; width:15%;">Uraian</th>-->
			<th align="center" style="border : 1px; width:25%;">Alamat</th>
			<th align="center" style="border : 1px; width:20%;">Lapak</th>
			<th align="center" style="border : 1px; width:15%;">Retribusi</th>
		</tr>
		
		<?php 
		
		$sql =  "SELECT a.*, b.NamaPerson,b.AlamatLengkapPerson,d.BlokLapak,d.NomorLapak,c.NamaPasar,f.Keterangan as KetLapak   
		FROM trretribusipasar a 
		JOIN mstperson b ON a.IDPerson=b.IDPerson 
		join mstpasar c on a.KodePasar=c.KodePasar 
		join lapakpasar d on (d.KodePasar,d.IDLapak)=(a.KodePasar,a.IDLapak) 
		join lapakperson f on (f.KodePasar,f.IDLapak,f.IDPerson)=(a.KodePasar,a.IDLapak,a.IDPerson) 
		WHERE  1 ";
	
		if(@$KodePasar != null OR @$KodePasar != ''){
			$sql .= " AND a.KodePasar='$KodePasar'  ";
		}
		
		if((isset($Tanggal1) && $Tanggal2<>"")){
			$sql .= " AND (date_format(a.TanggalTrans, '%Y-%m-%d') BETWEEN '$Tanggal1' AND '$Tanggal2') ";
		}
		
		$sql .="  ORDER BY a.TanggalTrans DESC";
		$result = mysqli_query($koneksi,$sql);	
		$no = 0;
		$tcount = mysqli_num_rows($result);
		
		if ($tcount == 0 OR $tcount == NULL ) {
			echo '<tr>
					<td colspan="6" style="border: solid 1px; text-align:center; height:20px">
						<strong>Tidak ada data</strong>
					</td>
				</tr>';
		}else {
			while($data=mysqli_fetch_assoc($result)){
				
				$Jumlah[] = $data['NominalDiterima'];
				
				echo '<tr height="20" >';
					
					echo '<td  style=" border: solid 1px; width:5%; text-align:center"> '.++$no.'</td>';
					echo '<td  style=" border: solid 1px; width:35%"><strong>'.$data ['NamaPerson'].' </strong><br>'.TanggalIndo($data ['TanggalTrans']).' <br>No Transaksi : <br>'.$data ['NoTransRet'].'</td>';
					echo '<td  style=" border: solid 1px; width:25%; text-align:left"> '.$data ['KetLapak'].'<br>'.$data['NamaPasar'].'</td>';
					echo '<td  style=" border: solid 1px; width:20%"><strong>'.$data ['NamaPasar'].' </strong><br>'.$data ['IDLapak'].'</td>';
					echo '<td  style=" border: solid 1px; width:15%; text-align:right"><strong> Rp '.number_format($data ['NominalDiterima']).'</strong></td>'; 	
				
				echo '</tr>';
			}
			
			echo '
				<tr style="background-color: #9e9999; border: solid 1px;">
					<td colspan="5" style="color:white; border: solid 1px; text-align:center; height:20px"><strong>Total Retribusi</strong></td>
					<td style="color:white; border: solid 1px; text-align:right"><strong> Rp '.number_format(array_sum($Jumlah),0,',','.').'</strong></td>
				</tr>
			';
			
			
			
		}
	
		?>
	</table>
</page>


<?php  
$filename="Laporan_PendapatanRetribusi.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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