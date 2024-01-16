<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
$Tanggal=date('Y-m-d');
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
			<strong><font style="font-size: 16px; line-height: 1.3;">LAPORAN TAGIHAN RETRIBUSI PASAR</font></strong><br>
		 </td>
		</tr>
    </table>
	
	
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr>
			<th align="center" style="border : 1px; width:5%;">No</th>
			<th align="center" style="border : 1px; width:55%;">Nama Lapak</th>
			<th align="center" style="border : 1px; width:20%;">Tagihan</th>
			<th align="center" style="border : 1px; width:20%;">Jumlah Hari</th>
		</tr>
		
		<?php 
		
		$sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,d.NamaPerson,a.IDLapak,c.NoRekBank,c.IDPerson,a.KodePasar,c.IsAktif FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) join mstperson d ON c.IDPerson=d.IDPerson ORDER BY b.NamaPasar,d.NamaPerson ASC";
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
				echo '<tr height="20" >';
					
					echo '<td  style=" border: solid 1px; width:5%; text-align:center"> '.++$no.'</td>';
					echo '<td  style=" border: solid 1px; width:55%"><strong>'.$data ['NamaPasar'].' </strong>'.$data ['BlokLapak'].' '.$data ['NomorLapak'].' <br>'.$data['NamaPerson'].'</td>';
					echo '<td  style="padding-top :5px; border: solid 1px; width:20%; text-align:right" >';
						$query ="SELECT TanggalTrans,NominalRetribusi from trretribusipasar where IDPerson='".$data['IDPerson']."' and KodePasar='".$data['KodePasar']."' and IDLapak='".$data['IDLapak']."' order by TanggalTrans DESC Limit 1"; 
						$res =mysqli_query($koneksi, $query);
						$result1 =mysqli_fetch_array($res);
						$datetime1 = new DateTime($result1[0]);
						$datetime2 = new DateTime($Tanggal);
						$difference = $datetime1->diff($datetime2);
						$selisih = $difference->days;
							
						if (strtotime($result1[0]) <= strtotime($Tanggal)  ){
							echo "Rp ".number_format($selisih*$result1[1]);
						}else{
							echo 'Rp 0';
						}
					echo '</td>';

					echo '<td  style="padding-top :5px; border: solid 1px; width:20%; text-align:right" >';
						if($result1[0] == '0000-00-00'){
							echo '<span>&nbsp;&nbsp;</span> 0 Hari';
						}else{
							if (strtotime($result1[0]) <= strtotime($Tanggal)){
								echo '<span>&nbsp;&nbsp;</span>'.$selisih.' Hari';
							}else{
								echo '<span>&nbsp;&nbsp;</span>'.$selisih.' Hari';
							}
						}
					echo '</td>';
					
					
				
				echo '</tr>';
			
	
			}
		}
	
		?>
	</table>
</page>


<?php  
$filename="Laporan_TagihanRetribusi.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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