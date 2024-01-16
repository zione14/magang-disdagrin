<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
$BulanTahun = isset($_GET['Bulan']) ? mysqli_real_escape_string($koneksi,$_GET['Bulan']) : date('Y-m');
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
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
			<strong><font style="font-size: 16px; line-height: 1.3;">LAPORAN PENDAPATAN RETRIBUSI PER PASAR PADA <?=strtoupper($BulanIndo[date('m', strtotime($BulanTahun)) - 1].' '.date('Y', strtotime($BulanTahun)))?></font></strong><br>
		 </td>
		</tr>
    </table>
	
	
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr>
			<th align="center" style="border : 1px; width:5%; ">No</th>
			<th align="center" style="border : 1px; width:45%;">Nama Pasar</th>
			<th align="center" style="border : 1px; width:30%;">Nama Kepala Pasar</th>
			<th align="center" style="border : 1px; width:20%;">Nilai Retribusi</th>
		</tr>
		
		<?php 
		
		$sql =  "SELECT a.NamaPasar,a.KodePasar,a.NamaKepalaPasar,b.NominalDiterima,c.Jumlah
		from mstpasar a left 
		join trretribusipasar b on a.KodePasar=b.KodePasar 
		LEFT JOIN (
			SELECT  SUM(NominalDiterima) as Jumlah,c.KodePasar
			FROM trretribusipasar c
			WHERE c.KodePasar=KodePasar
			GROUP BY c.KodePasar
		) c ON c.KodePasar = a.KodePasar
		where (date_format(b.TanggalTrans, '%Y-%m') BETWEEN '".$BulanTahun."' AND '".@$BulanTahun."') OR b.TanggalTrans is null  
		GROUP by a.KodePasar ORDER BY a.NamaPasar ASC";
		$result = mysqli_query($koneksi,$sql);		
	
		$no = 0;
		$tcount = mysqli_num_rows($result);
		
		if ($tcount == 0 OR $tcount == NULL ) {
			echo '<tr>
					<td colspan="4" style="border: solid 1px; text-align:center; height:20px>
						<strong>Tidak ada data</strong>
					</td>
				</tr>';
		}else {
			while($data=mysqli_fetch_assoc($result)){
				
				$Jumlah[] = $data['Jumlah']; 	
				
				echo '<tr height="20" >';
					
					echo '<td  style=" border: solid 1px; width:5%; text-align:center"> '.++$no.'</td>';
					echo '<td  style=" border: solid 1px; width:45%"><strong>'.$data ['NamaPasar'].' </strong></td>';
					echo '<td  style=" border: solid 1px; width:30%">'.$data ['NamaKepalaPasar'].'</td>';
					echo '<td  style=" border: solid 1px; width:20%; text-align:right">Rp '.number_format($data ['Jumlah']).'</td>';

				echo '</tr>';
			}
			
			echo '
				<tr style="background-color: #9e9999; border: solid 1px;">
					<td colspan="3" style="color:white; border: solid 1px; text-align:center; height:20px"><strong>Total</strong></td>
					<td style="color:white; border: solid 1px; text-align:right"><strong> Rp '.number_format(array_sum($Jumlah),0,',','.').'</strong></td>
				</tr>
			';
			
		}
	
		?>
	</table>
</page>


<?php  
$filename="Laporan_RekapPendapatanPasar.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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