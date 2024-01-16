<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Lap_TargetBulanan.xls");
include "../config.php";
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

@$apart		 = explode("-", htmlspecialchars(base64_decode($_GET['bln'])));
@$bulan		 = $apart[1];
@$tahun		 = $apart[0];
$QuerySetting = mysqli_query($koneksi,"SELECT Value FROM sistemsetting WHERE KodeSetting='SET-0002'");
$RowSetting = mysqli_fetch_array($QuerySetting);
$SetSurat = explode("#", $RowSetting['Value']);

?>
<table>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Laporan Realisasi Penerimaan </strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Pendapatan Asli Daerah (PAD)</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Dinas Perdagangan dan Perindustrian</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Kabupaten Jombang</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Bulan <?php echo $NamaBulan[(int)$bulan]." ".$tahun; ?></strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr>
	  <th>Kode Rekening</th>
	  <th>Target </th>
	  <th>Target PAK</th>
	  <th>Bulan Ini</th>
	  <th>Bulan Lalu</th>
	  <th>s/d Bulan ini</th>
	  <th>Prosentase</th>
	</tr>
	<?php
		// Load file koneksi.php
				
		$Query = mysqli_query($koneksi,"SELECT * FROM targettahunan WHERE (date_format(ThAnggaran, '%Y-%m')='".htmlspecialchars(base64_decode($_GET['bln']))."') and JenisTarget='PAD'");
		while($data = mysqli_fetch_array($Query)){
			
			
			
			$ini = @mysqli_query($koneksi, "SELECT SUM(TotalRetribusi) as BulanIni  FROM tractiontimbangan WHERE StatusTransaksi='SELESAI' AND IsDibayar='1' AND (date_format(TglAmbil, '%Y-%m')= '".htmlspecialchars(base64_decode($_GET['bln']))."')"); 
			$jumlahini = @mysqli_fetch_array($ini); 
			
			$lalu = @mysqli_query($koneksi, "SELECT SUM(TotalRetribusi) as BulanLalu  FROM tractiontimbangan WHERE StatusTransaksi='SELESAI' AND IsDibayar='1' AND (date_format(TglAmbil, '%Y-%m') != '".htmlspecialchars(base64_decode($_GET['bln']))."')"); 
			$jumlahlalu = @mysqli_fetch_array($lalu); 
			
			$Total = $jumlahini['BulanIni']+$jumlahlalu['BulanLalu'];
			$Persen = $Total/$data['TargetAwal']*100;
			
	
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			echo "<tr>";
				echo "<td rowspan='3' align='left'> ".$data['KodeRekening']."</td>";
				echo "<td rowspan='3' align='right'>".$data['TargetAwal']." </td>";
				echo "<td rowspan='3' align='right'>".$data['TargetPAK']."</td>";
				echo "<td rowspan='3' align='right'>".$jumlahini['BulanIni']."</td>";
				echo "<td rowspan='3' align='right'>".$jumlahlalu['BulanLalu']."</td>";
				echo "<td rowspan='3' align='right'>".$Total."</td>";
				echo "<td rowspan='3' align='right'>".number_format($Persen,2)."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		}
		
	?>
</table>

<table>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Laporan Realisasi </strong></td></tr>
	<tr><td colspan="7" align="center"><strong>UTTP</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Dinas Perdagangan dan Perindustrian</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Kabupaten Jombang</strong></td></tr>
	<tr><td colspan="7" align="center"><strong>Bulan <?php echo $NamaBulan[(int)$bulan]." ".$tahun; ?></strong></td></tr>
	<tr></tr>
</table>

<table border="1" cellpadding="10">
	<tr>
	  <th>Kode Rekening</th>
	  <th>Target </th>
	  <th>Target PAK</th>
	  <th>Bulan Ini</th>
	  <th>Bulan Lalu</th>
	  <th>s/d Bulan ini</th>
	  <th>Prosen</th>
	</tr>
	<?php
				
		$Query1 = mysqli_query($koneksi,"SELECT * FROM targettahunan WHERE (date_format(ThAnggaran, '%Y-%m')='".htmlspecialchars(base64_decode($_GET['bln']))."') and JenisTarget='UTTP'");
		while($data1 = mysqli_fetch_array($Query1)){
			
			
			
			$ini1 = @mysqli_query($koneksi, "SELECT IDTimbangan FROM timbanganperson WHERE IDPerson != 'PRS-2019-0000000' AND StatusUTTP='Aktif' AND (date_format(TglInput, '%Y-%m')= '".htmlspecialchars(base64_decode($_GET['bln']))."')"); 
			$uttpini = @mysqli_num_rows($ini1); 
			
			$lalu1 = @mysqli_query($koneksi, "SELECT IDTimbangan  FROM timbanganperson WHERE IDPerson != 'PRS-2019-0000000' AND StatusUTTP='Aktif' AND (date_format(TglInput, '%Y-%m') != '".htmlspecialchars(base64_decode($_GET['bln']))."')"); 
			$uttplalu = @mysqli_num_rows($lalu1); 
			
			$TotalUTTP = $uttpini+$uttplalu;
			$PersenUTTP = $TotalUTTP/$data1['TargetAwal']*100;
			
	
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			echo "<tr>";
				echo "<td rowspan='3' align='left'> ".$data['KodeRekening']."</td>";
				echo "<td rowspan='3' align='right'>".$data1['TargetAwal']." </td>";
				echo "<td rowspan='3' align='right'>".$data1['TargetPAK']."</td>";
				echo "<td rowspan='3' align='right'>".$uttpini."</td>";
				echo "<td rowspan='3' align='right'>".$uttplalu."</td>";
				echo "<td rowspan='3' align='right'>".$TotalUTTP."</td>";
				echo "<td rowspan='3' align='right'>".number_format($PersenUTTP,2)."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		}
		
	?>
</table>
<table>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left">Jombang, <?php echo $NamaBulan[(int)$bulan]." ".$tahun; ?></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><strong>KEPALA BIDANG KEMETROLOGIAN </strong></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN</strong></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><strong>KABUPATEN JOMBANG</strong></td>
	</tr>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr><td colspan="7" align="center"><strong></strong></td></tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><strong><u><?php echo $SetSurat[0]; ?></u></strong></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><?php echo $SetSurat[1]; ?></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><strong></strong></td>
		<td colspan="5" align="left"><?php echo $SetSurat[2]; ?></td>
	</tr>
</table>
		



<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
