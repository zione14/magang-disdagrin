<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();  
ob_start();
include_once("../../config.php"); //buat koneksi ke database  
include '../../tgl-indo.php';
include '../../terbilang.php';
@$NoTransaksi   = htmlspecialchars($_GET['id']); //kode berita yang akan dikonvert  
@$NoUrutTrans   = htmlspecialchars($_GET['itm']); //kode berita yang akan dikonvert  
$Tanggal	= date("Y-m-d");


$getData ="SELECT c.NamaTimbangan,b.Keterangan,b.UkuranRealTimbangan,b.Satuan,g.NamaPerson,g.AlamatLengkapPerson,i.NamaPegawai,i.NIP,h.JenisTransaksi,h.TglTera,c.KodeTimbangan,f.NamaLokasi,a.HasilAction2 as Metode,a.TanggalTransaksi
	FROM trtimbanganitem a 
	join timbanganperson b on  (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(b.IDTimbangan,b.KodeLokasi,b.IDPerson) 
	join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan 
	join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) 
	join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) 
	join lokasimilikperson f on (a.KodeLokasi,a.IDPerson)=(f.KodeLokasi,f.IDPerson) 
	join mstperson g on (a.IDPerson)=(g.IDPerson)
	join tractiontimbangan h on a.NoTransaksi=h.NoTransaksi
	join userlogin i on a.UserName=i.UserName
	WHERE a.NoTransaksi='$NoTransaksi' AND a.HasilAction1 = 'TERA SAH' AND a.NoUrutTrans='$NoUrutTrans'
	 ORDER BY c.NamaTimbangan ASC";

	$res =mysqli_query($koneksi, $getData);
	$dataPerson = mysqli_fetch_array($res);
	$tanggal = date_format(date_create($dataPerson['TglTera']),"d/m/Y");
	$TanggalTera = $dataPerson[9] != null ? TanggalIndo($dataPerson[9]) : '';
	
	@$ket 	  	= explode('#', $dataPerson[1]); 
	@$kapasitas	= $dataPerson[10] == 'TMB-0000010' ? 'Terlampir': $dataPerson[2]." ".$dataPerson[3];
	@$merk   	= $dataPerson[10] == 'TMB-0000010' ? 'Terlampir': ucwords($ket[0]." / ".$ket[1]." / ".$ket[2]);
	@$lokasi	= $dataPerson[8] == 'TERA DI KANTOR' ? 'Bidang Kemetrologian, Dinas Perdagangan dan Perindustrian Kab. Jombang, Jl. Brawijaya 207 Peterongan, Jombang': $dataPerson[11];
?>
<style>
	.garis{
		border : 0;
		border-top : 3px double black;
	}
	.tipis{
		border : 0;
		border-top : 1px ;
	}
	.wrap{
		border: solid 1px;  
		padding:2px;
		width: 220px; 
		height:40px; 
		align :right;
		
	}
	.nama{
		padding-top:10px;
		width: 100%; 
		height:25px; 
		text-align:center;
		
	}
	
	
</style>
<page  backimg="../../../images/Assets/cover.png" backimgx="60%" backimgy="85%" backimgw="80%" backleft="5mm" backright="5mm" backbottom="5mm" backtop="5mm">
	<table>
		<tr>
		  <th rowspan="3"><img src="../../../images/Assets/logo_cetak.png" style="width:75px;height:100px"/></th>
		  <td align="center" style="width: 550px; padding-top:10px;">
			<font style="font-size: 18px; line-height: 1.3;">PEMERINTAH KABUPATEN JOMBANG</font><br>
			<font style="font-size: 23px; line-height: 1.3;">DINAS PERDAGANGAN DAN PERINDUSTRIAN</font><br>
			<font style="font-size: 16px; line-height: 1.3;">Jl. KH. Wahid Hasyim No.143 Jombang 61419</font><br>
			<font style="font-size: 14px; line-height: 1.3;">Telp. ( 0321 ) 874549 Fax (0321) 850494 Email: disperdagperin@jombangkab.go.id<br>
			Website: jombangkab.go.id</font>
		 </td>
		</tr>
    </table><hr class="garis">
	
	<!-- ===============  No Transaksi  ================== -->
	<table style="width:100%; margin-top: 10px;">
		<tr>
			<td style="width:65%; vertical-align: top;"></td>
			<td style="width:35%;">
				<div class="wrap">
					<table style="width:100%;">
						<tr>
							<td width="40%" height="10"><b>No. Order :</b></td>
							<td width="60%" height="10" align="center"><b><?php echo $NoTransaksi; ?></b><hr class="tipis">
								<b><?=$tanggal?></b>
							</td>
						</tr>
						
					</table>
				</div>
			</td>
		</tr>
	</table>
	
	<!-- ===============  No Surat  ================== -->
	<div class="nama">
			<font style="font-size: 18px; line-height: 1.3;"><b><u>SURAT KETERANGAN  HASIL PENGUJIAN</u></b></font><br>
			<font style="font-size: 16px; line-height: 1.3;">No : 510.3/..../415.32/2019   </font><br>
	</div>
	
	<!-- ===============  Detil Timbangan  ================== -->
	<table style="width:100%; margin-top: 20px; margin-right: 30px; font-size: 14px; line-height: 1.3;">
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Jenis Alat UTTP</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><strong><?=$dataPerson[0]?></strong></td>
		</tr>
		
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Merek / Type / Nomor Seri</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><strong><?=$merk?></strong></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Kapasitas / Medium</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><strong><?=$kapasitas?></strong></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Pemilik / Pemakai</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><strong><?=$dataPerson[4]?></strong></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Alamat</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><strong><?=$dataPerson[5]?></strong></td>
		</tr>
		<tr>
			<td height="10" style="width:38%; vertical-align: top;"></td>
			<td height="10" style="width:2%; vertical-align: top;"></td>
			<td height="10" style="width:60%; vertical-align: top;" colspan="2"></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Diuji  oleh</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:30%; vertical-align: top;"><b><?=$dataPerson[6]?></b></td>
			<td height="15" style="width:30%; vertical-align: top;"><b>NIP. <?=$dataPerson[7]?></b></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Tanggal Pengujian</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2"><b><?=$dataPerson[13]?></b></td>
		</tr>
		<tr>
			<td height="15" style="width:38%; vertical-align: top;">Lokasi Pengujian</td>
			<td height="15" style="width:2%; vertical-align: top;">:</td>
			<td height="15" style="width:60%; vertical-align: top; text-align: justify;" colspan="2"><?=$lokasi?></td>
		</tr>
		<tr>
			<td height="10" style="width:38%; vertical-align: top;"></td>
			<td height="10" style="width:2%; vertical-align: top;"></td>
			<td height="10" style="width:40%; vertical-align: top;" colspan="2"></td>
		</tr>
		<tr>
			<td rowspan="2" style="width:38%; vertical-align: top;">Metode</td>
			<td rowspan="2" style="width:2%; vertical-align: top;">:</td>
			<td style="width:60%; vertical-align: top;" colspan="2"><?=$dataPerson[12]?></td>
		</tr>
		<tr>
			<td height="15" style="width:60%; vertical-align: top;" colspan="2">(SK Dirjen SPK No. 131/SPK/KEP/10/2015)</td>
		</tr>
		<tr>
			<td height="10" style="width:38%; vertical-align: top;"></td>
			<td height="10" style="width:2%; vertical-align: top;"></td>
			<td height="10" style="width:60%; vertical-align: top;" colspan="2"></td>
		</tr>
		<tr>
			<td height="10" style="width:38%; vertical-align: top;">Hasil  Pengujian</td>
			<td height="10" style="width:2%; vertical-align: top;">:</td>
			<td height="10" style="width:60%; vertical-align: top; text-align: justify;" colspan="2">Disahkan dengan pembubuhan Tanda Tera Sah 2019 berdasarkan Undang-Undang Republik Indonesia Nomor 2 Tahun 1981 tentang Metrologi Legal.</td>
		</tr>
	</table><br><hr class="garis">
	<!-- ===============  TTD  ================== -->
	<table style="width:100%; margin-top: 10px;">
		<tr>
			<td style="width:50%; vertical-align: top;"></td>
			<td style="width:50%; vertical-align: top;" align="center" >Jombang, <?php echo TanggalIndo($Tanggal); ?><br><br>
				<font style="font-size: 15px; line-height: 1.3;">
					<strong>KEPALA DINAS PERDAGANGAN </strong><br>
					<strong>DAN PERINDUSTRIAN </strong><br>
					<strong>KABUPATEN JOMBANG </strong>
					<br>
					<br>
					<br>
					<br>
					<br>
					<strong><u>Drs. BAMBANG NURWIJANTO, M.Si</u></strong><br>
					<strong>Pembina Utama Muda </strong><br>
					<strong>NIP. 19651229 199103 1 005</strong>
				</font>
			</td>
		</tr>
	</table>
	
	<!-- ===============  CATATAN  ================== -->
	<table style="width:100%; margin-top: 20px;">
		<tr>
			<td colspan="2" style="width:3%; vertical-align: top;"><b><u>CATATAN:</u></b></td>
		</tr>
		<tr>
			<td style="width:3%; vertical-align: top;"></td>
			<td style="width:3%; vertical-align: top;">1.</td>
			<td style="width:94%; vertical-align: top;">Keterangan Pengujian ini berlaku sampai tanggal <?=$dataPerson[13]?>. </td>
		</tr>
		<tr>
			<td style="width:3%; vertical-align: top;"></td>
			<td style="width:3%; vertical-align: top;">2.</td>
			<td style="width:94%; vertical-align: top;">Surat ini tidak berlaku apabila tanda tera rusak.</td>
		</tr>
		<tr>
			<td style="width:3%; vertical-align: top;"></td>
			<td style="width:3%; vertical-align: top;">3.</td>
			<td style="width:94%; vertical-align: top;">Salinan Keterangan Pengujian ini tidak berlaku tanpa pengesahan dari Kepala Dinas Perdagangan dan Perindustrian Kabupaten Jombang. </td>
		</tr>
	</table>
	<?php if ($dataPerson[10] == 'TMB-0000010') { ?> 
	<!-- ===============  LAMPIRAN  ================== -->
	<table style="width:100%; margin-top: 50px;">
		<tr>
			<td style="width:40%; vertical-align: top;"></td>
			<td style="width:60%;">
				<table style="width:100%; font-size: 15px; line-height: 1.5;">
					<tr>
						<td width="100%" height="10" colspan="3">Lampiran Surat Keterangan Pengujian</td>
					</tr>
					<tr>
						<td width="38%" height="10">Nomor</td>
						<td width="2%" 	height="10"> : </td>
						<td width="60%" height="10">510.3/..../415.32/2019 </td>
					</tr>
					<tr>
						<td width="38%" height="10">Tanggal</td>
						<td width="2%" 	height="10"> : </td>
						<td width="60%" height="10"><?php echo TanggalIndo($Tanggal); ?></td>
					</tr>
					<tr>
						<td width="38%" height="10">Pemilik</td>
						<td width="2%" 	height="10"> : </td>
						<td width="60%" height="10"><?=$dataPerson[4]?></td>
					</tr>
					<tr>
						<td width="38%" height="10">Alamat</td>
						<td width="2%" 	height="10"> : </td>
						<td width="60%" height="10"><?=$dataPerson[5]?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- ===============  DATA  ================== -->
	
	<table style="width:100%; margin-top: 50px; margin-right: 10px; border-collapse: collapse;">
		<tr style="border: solid 1px;">
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:5%;">No</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:15%;">Merk</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:10%;">Type</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:15%;">No Seri</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:20%;">Kapasitas</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:10%;">Buatan</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:10%;">Medium</td>
			<td height="30" align="center" style="padding-top :5px; border: solid 1px; width:15%;">Jumlah</td>
		</tr>
		<?php 
			$no = 0;
			$getTim ="SELECT b.Keterangan,b.UkuranRealTimbangan,b.Satuan
			FROM trtimbanganitem a 
			join timbanganperson b on  a.IDTimbangan=b.IDTimbangan 
			WHERE a.NoTransaksi='$NoTransaksi' AND a.HasilAction1 = 'TERA SAH' 
			GROUP by a.IDTimbangan ORDER BY b.NamaTimbangan ASC";
			$result =mysqli_query($koneksi, $getTim);
			while($dataTim=mysqli_fetch_array($result)){ 
			@$Tim 	  	= explode('#', $dataTim[0]); 
			
		?>	
		<tr height="15" style="border: solid 1px;">
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:5%;"><?php echo ++$no; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:15%;"><?php echo $Tim[0]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:10%;"><?php echo $Tim[1]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:15%;"><?php echo $Tim[2]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:20%;"><?php echo $dataTim[1]." ".$dataTim[2]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:10%;"><?php echo $Tim[3]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:10%;"><?php echo $Tim[4]; ?></td>
			<td height="20" align="center" style="padding-top :5px; border: solid 1px; width:15%;"><?php echo $Tim[5]; ?></td>
			
		</tr>
		<?php } ?>
	</table>
	<table style="width: 100%; margin-top: 30px; font-size: 15px; line-height: 1.3;">
		<tr style="text-align: center;">
			<td style="text-align: center; width: 50%;">
				<table style="text-align: center; width: 100%;">
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							Mengetahui,
						</td>
					</tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							Kepala Bidang Kemetrologian
						</td>
					</tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							
						</td>
					</tr>
					<tr style="height: 100px;width: 100%;"><td style="height: 50px;width: 100%;"></td></tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							<b><u>Mita Arina, S.H., M.Si.</u></b>
						</td>
					</tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							NIP. 198210102005012016
						</td>
					</tr>
				</table>			
			</td>
			<td style="text-align: center; center; width: 50%;">
				<table style="text-align: center; width: 100%;">
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							Penanggung Jawab Teknik,
						</td>
					</tr>
					<tr style="height: 100px;width: 100%;"><td style="height: 70px;width: 100%;"></td></tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							<b><u>Monicha Sari, A.Md</u></b>
						</td>
					</tr>
					<tr style="text-align: center; width: 100%;">
						<td style="text-align: center; width: 100%;">
							NIP. 19911020 201505 2 001
							
						</td>
					</tr>
				</table>			
			</td>
		</tr>
	</table>
	<?php } ?>
</page>


<?php  
$filename="SKHP-".$NoTransaksi.".pdf"; //ubah untuk menentukan nama file pdf yang dihasilkan nantinya  
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