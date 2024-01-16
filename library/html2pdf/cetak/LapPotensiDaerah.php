<?php 
session_start();  
ob_start();
date_default_timezone_set('Asia/Jakarta');
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
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
			<div  align="center"> <font style="font-size: 20px;">LAPORAN POTENSI PENDAPATAN<br> DAERAH <?php echo 'KECAMATAN '.base64_decode($_GET['nmk']); ?></font></div>
			<div  align="center"> <font style="font-size: 20px;">DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</font></div>
			<div  align="center"> <font style="font-size: 13px;">Jl. Wahid Hasyim No.143, Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</font></div>
		   </td>
		</tr>
	</table><hr>
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:5%;">No</th>
			<th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:35%;">Jenis UTTP</th>
			<th align="center" rowspan="2" style="padding-top :5px; border: solid 1px; width:10%;">Jumlah</th>
			<th align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:25%;">Nilai Retribusi</th>
			<th align="center" colspan="2" style="padding-top :5px; border: solid 1px; width:25%;">Potensi Pendapatan</th>
		</tr>
		<tr height="15">
			<th align="center" style="padding-top :5px; border: solid 1px; width:12%;">Kantor</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:13%;">Lokasi</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:12%;">Kantor</th>
			<th align="center" style="padding-top :5px; border: solid 1px; width:13%;">Lokasi</th>
		</tr>
		
		<?php 
		
		$No = 1;			
		$sql =  "SELECT a.KodeTimbangan,a.KodeKelas,a.KodeUkuran,c.NamaTimbangan,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,b.KodeKec from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson)=(b.KodeLokasi,b.IDPerson) join msttimbangan c on a.KodeTimbangan=c.KodeTimbangan join kelas d on (a.KodeTimbangan,a.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) join mstperson h on h.IDPerson=a.IDPerson WHERE h.UserName != 'dinas' ";
								
		if(@base64_decode($_GET['kec'])!=null){
			$sql .= " AND b.KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' ";
		}
		
		$sql .="  GROUP by a.KodeTimbangan,a.KodeKelas,a.KodeUkuran order by  a.KodeTimbangan,a.KodeKelas,a.KodeUkuran";
		$result = mysqli_query($koneksi,$sql);
		$tcount = mysqli_num_rows($result);
		if ($tcount == 0 ){
			echo '<tr>
					<td colspan="7" align="center" style="padding-top :5px; border: solid 1px;">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>';
		}else{
			while($data = mysqli_fetch_array($result)){
				@$Subquery = isset($_GET['kec']) && $_GET['kec'] != null ? 'AND b.KodeKec='.htmlspecialchars($data['KodeKec']) : '';
				
				$query = mysqli_query($koneksi, ("select IDTimbangan from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' $Subquery"));
				$nums2 = mysqli_num_rows($query); 
				
				?>
				
			<tr height="20" style="border: solid 1px;">
				<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $No++; ?></td>
				<td style="padding-top :5px; border: solid 1px; width:35%;"><?php echo ucwords($data[3])." ".$data[4]." ".$data[5];?></td>
				<td style="padding-top :5px; border: solid 1px; width:10%;"><?php echo $nums2; ?></td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:12%;">
					<?php echo number_format($data[6]); ?>
				</td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:13%;">
					<?php echo number_format($data[7]); ?>
				</td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:12%;">
					<?php
						$kantor=$data[6]*$nums2;
						echo number_format($kantor);
						$JumlahKantor[] = $kantor;
					?>
				</td>
				<td align="right" style="padding-top :5px; border: solid 1px; width:13%;">
					<?php
						$lokasi=$data[7]*$nums2;
						echo number_format($lokasi);
						$JumlahLokasi[] = $lokasi;
					?>
				</td>
			</tr>	
		<?php } ?>
			<tr style="border: solid 1px;">
				<th align="center" colspan="5" style="padding-top :5px; border: solid 1px; width:75%;">Total</th>
				<th align="right" style="padding-top :5px; border: solid 1px; width:12%;"><?php echo number_format(array_sum(@$JumlahKantor)); ?></th>
				<th align="right" style="padding-top :5px; border: solid 1px; width:13%;"><?php echo number_format(array_sum(@$JumlahLokasi)); ?></th>
			</tr>
		<?php } ?>
	</table>
</page>


<?php  
$filename="LapPotensiDaerah.pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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