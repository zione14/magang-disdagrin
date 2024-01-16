<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
@$id   = htmlspecialchars(base64_decode($_GET['id'])); //kode berita yang akan dikonvert  
$TanggalTransaksi	= date("Y-m-d");



@$Query = mysqli_query($koneksi,"SELECT a.NamaPerson,b.TotalRetribusi,d.NamaPegawai,d.NIP,b.NoSKRD,a.NamaPerson,a.AlamatLengkapPerson,b.TglTransaksi,b.NoTransaksi,b.TglTera,b.KeteranganTera FROM mstperson a join tractiontimbangan b on a.IDPerson=b.IDPerson left join trtimbanganitem c on b.NoTransaksi=c.NoTransaksi join userlogin d on b.UserTerima=d.UserName WHERE b.NoTransaksi='$id'");
	@$row = mysqli_fetch_array($Query);

?>

<page style="width:100%;" backtop="5mm" backbottom="10mm"  backright="5mm">
	<table>
		<tr>
		  <th rowspan="3"><img src="../../../images/Assets/logo_cetak.png" style="width:80px;height:100px"/></th>
		  <td style="width: 550px;">
			<div  align="center"> <font style="font-size: 30px;">KWITANSI PEMBAYARAN</font></div>
			<div  align="center"> <font style="font-size: 22px;">RETRIBUSI TERA/TERA ULANG</font></div><br>
			<div  align="right"> No : <?php  echo $id; ?> </div>
		   </td>
		</tr>
	</table>
  
	<table style="width:100%; margin-top: 10px; margin-right: 30px;">
		<tr>
			<td style="width:23%; vertical-align: top;">Telah terima dari</td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"> <?php echo $row[0];?></td>
		</tr>
		<tr>
			<td style="width:23%; vertical-align: top;">Uang sejumlah</td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"> <?php echo terbilang($row[1])." rupiah"; ;?></td>
		</tr>
		<tr>
			<td style="width:23%; vertical-align: top;">Uang Pembayaran</td>
			<td style="width:2%; vertical-align: top;"> : </td>
			<td style="width:75%; vertical-align: top;"> Biaya tera/tera ulang alat ukur, takar, timbang dan perlengkapannya (UTTP) sesuai dengan SKRD dengan nomor order : <p><?php  echo $id; ?></p> </td>
		</tr>
	</table>
	<table style="width:100%; margin-top: 20px;">
		<tr>
			<td style="width:70%; vertical-align: top;">
				<br>Rp.&nbsp;<div style="border: solid 1px;  padding :7 0 0 9; width: 300px; height:25px;"><?php echo number_format($row[1],2,',','.'); ?></div>
			</td>
			<td style="width:30%; vertical-align: top;">
				Jombang, <?php echo TanggalIndo($TanggalTransaksi); ?>
				<p style="text-align:center;">Unit Metrologi Legal<br> Kabupaten Jombang<br> Penerima</p><br><br><br><br>
				<div style="text-align:center;"><b><u><?php echo $row[2] ?></u></b><br>
				<b>NIP. <?php echo $row[3]; ?></b><br></div>
			</td>
		</tr>
	</table>
	<!--================================================================DATA PEMOHON==================================================================== -->
	<table style="width:100%; margin-top: 10px; margin-right: 30px; border-collapse: collapse;">
		<tr>
			<td colspan="2" align="center" style="width:100%; padding-top:5px; padding-bottom:5px; border: solid 1px;">
				<b>Surat Ketetapan Retribusi Daerah (SKRD)</b>
			</td>
		</tr>
		<tr>
			<td style="width:40%; vertical-align: top;  border: solid 1px;">No Order</td>
			<td style="width:60%; vertical-align: top;  border: solid 1px;">: <?php echo $row[4]; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="width:100%; border: solid 1px;">
				Data Pemohon
			</td>
		</tr>
		<tr>
			<td style="width:40%; vertical-align: top;  border: solid 1px;">Nama Institusi/Perusahaan</td>
			<td style="width:60%; vertical-align: top;  border: solid 1px;"><?php echo $row[5]; ?></td>
		</tr>
		<tr>
			<td style="width:40%; vertical-align: top;  border: solid 1px;">Alamat</td>
			<td style="width:60%; vertical-align: top;  border: solid 1px;"><?php echo $row[6]; ?></td>
		</tr>
		<tr>
			<td colspan="2" style="width:100%; border: solid 1px;">
				Data Setifikat
			</td>
		</tr>
		<tr>
			<td style="width:40%; vertical-align: top;  border: solid 1px;">Nama Institusi/Perusahaan</td>
			<td style="width:60%; vertical-align: top;  border: solid 1px;">:</td>
		</tr>
		<tr>
			<td style="width:40%; vertical-align: top;  border: solid 1px;">Alamat</td>
			<td style="width:60%; vertical-align: top;  border: solid 1px;">:</td>
		</tr>
	</table>
		<!--==============================================================DATA TIMBANGAN================================================================= -->
	<table style="width:100%; margin-top: 10px; margin-right: 10px; border-collapse: collapse;">
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"  rowspan="2">No</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;" rowspan="2">Nama Alat</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;" rowspan="2">Merk/Type</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:10%;" rowspan="2">Kapasitas</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:10%;" rowspan="2">Jumlah Unit</td>
			<td colspan="2" style="border: solid 1px; width:45%;">Tarif berdasar perda No 11/2016</td>
		</tr>
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="border: solid 1px; width:20%;">Tarif Peralat</td>
			<td align="center" style="border: solid 1px; width:25%;">Total</td>
		</tr>
		<?php 
		
		$hasil  = mysqli_query($koneksi,"SELECT a.NominalRetribusi,b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,a.NoUrutTrans,a.NoTransaksi,a.IDPerson,a.FotoAction1,c.Merk,b.UkuranRealTimbangan,b.KodeTimbangan,b.KodeKelas,b.KodeUkuran FROM trtimbanganitem a join timbanganperson b on  a.IDTimbangan=b.IDTimbangan join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) join lokasimilikperson f on b.KodeLokasi=f.KodeLokasi WHERE a.NoTransaksi='".$row['NoTransaksi']."' AND a.HasilAction1 = 'TERA SAH' GROUP by b.KodeTimbangan,b.KodeKelas,b.KodeUkuran ORDER BY c.NamaTimbangan ASC");  
		$no = 1;
		$tcount = mysqli_num_rows($hasil);
		
		if ($tcount == 0 OR $tcount == NULL ) {
			echo '';
		}else {
		while($res=mysqli_fetch_assoc($hasil)){ ?>	
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo $no."."; ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;"><?php echo $res['NamaTimbangan']; ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:15%;"><?php echo $res['Merk']; ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:10%;"><?php echo $res['UkuranRealTimbangan']; ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:10%;">
				<?php $query1 = mysqli_query($koneksi, ("select a.IDTimbangan from timbanganperson a  join trtimbanganitem b on a.IDTimbangan=b.IDTimbangan where a.KodeTimbangan='".$res['KodeTimbangan']."' and a.KodeKelas='".$res['KodeKelas']."' and a.KodeUkuran='".$res['KodeUkuran']."' and b.NoTransaksi='$id' AND b.HasilAction1 = 'TERA SAH'"));
				$nums2 = mysqli_num_rows($query1); 
				echo number_format($nums2);
				
				?>
			</td>
			<td align="right" style="padding-top :5px; border: solid 1px; width:20%;">
				<?php 
					if ($res['IDPerson'] == 'PRS-2019-0000000'){
						echo number_format('0')." " ;
					}else{
						echo number_format($res['NominalRetribusi'])." " ;
					}
				?>
			</td>
			<td align="right" style="padding-top :5px; border: solid 1px; width:25%;">
			<?php
				if ($res['IDPerson'] == 'PRS-2019-0000000'){
					echo "0"; 
					$Jumlah[] = 0;
					$TotalAkhir[] = 0;
				}else{
					$hasil1 =  $nums2*$res['NominalRetribusi'];
					echo number_format($hasil1)." ";
					$TotalAkhir[] = $hasil1;
					$Jumlah[] =$nums2;
				}
			?>
			</td>
		</tr>
		<?php $no++; } } ?>
		
		<tr height="20" style="border: solid 1px;">
			<td align="center" style="padding-top :5px; border: solid 1px; width:5%;"></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:40%;" colspan="3">Jumlah</td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:10%;"><?php echo number_format(array_sum(@$Jumlah)); ?></td>
			<td align="center" style="padding-top :5px; border: solid 1px; width:20%;"></td>
			<td align="right" style="padding-top :5px; border: solid 1px; width:25%;"><?php echo number_format(array_sum(@$TotalAkhir)); ?></td>
		</tr>
	</table>
		<!--==============================================================DATA PARAF================================================================= -->
	<!--================================================================DATA PEMOHON==================================================================== -->
	<table style="width:100%; margin-top: 10px; margin-right: 30px; border-collapse: collapse;">
		<tr height="20" align="center" style="padding-top :5px;">
			<td style="width:5%;   border: solid 1px;">No</td>
			<td style="width:20%;  border: solid 1px;">Tanggal Masuk</td>
			<td style="width:20%;  border: solid 1px;">Tgl Selesai</td>
			<td style="width:20%;  border: solid 1px;">Petugas Pemeriksa</td>
			<td style="width:20%;  border: solid 1px;">Pemesan</td>
			<td style="width:15%;  border: solid 1px;">Keterangan</td>
		</tr>
		<tr align="center" >
			<td style="width:5%;   border: solid 1px;"><br><br><br><br><br></td>
			<td style="width:20%;  border: solid 1px;"><?php echo TanggalIndo($row[7]); ?></td>
			<td style="width:20%;  border: solid 1px;"><?php echo TanggalIndo($row[9]); ?></td>
			<td style="width:20%;  vertical-align: bottom; border: solid 1px;"><?php echo $row[2]; ?></td>
			<td style="width:20%;  vertical-align: bottom; border: solid 1px;"><?php echo $row[0]; ?></td>
			<td style="width:15%;  vertical-align: bottom; border: solid 1px;"><?php echo $row[10]; ?></td>
		</tr>
		
	</table>
	
	
</page>
<?php  
$filename="SKRD-".$id.".pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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