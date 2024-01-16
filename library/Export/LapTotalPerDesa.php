<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_LapTotalPerDesa.xls");
?>
<table>
	<tr><td colspan="3" align="center"><strong>LAPORAN TOTAL TIMBANGAN <?php echo $_GET['nm'];?></strong></td></tr>
	<tr><td colspan="3" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th>No</th>
	  <th>Nama Desa</th>
	  <th>Jumlah Timbangan</th>
	</tr>
	<?php
		// Load file koneksi.php
		include "../config.php";
		
		$Query = mysqli_query($koneksi,"SELECT NamaDesa,KodeDesa FROM mstdesa WHERE KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' ORDER BY NamaDesa ");
		while($data = mysqli_fetch_array($Query)){
				
				$sql2 = @mysqli_query($koneksi, "SELECT a.IDTimbangan FROM timbanganperson a 
				JOIN lokasimilikperson b ON (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) 
				Join mstperson c on c.IDPerson=a.IDPerson 
				WHERE b.KodeDesa='".$data['KodeDesa']."' and b.KodeKec='".htmlspecialchars(base64_decode($_GET['kec']))."' and c.UserName != 'dinas' AND  a.StatusUTTP='Aktif' 
				GROUP BY a.IDTimbangan"); 
				$jumlah = @mysqli_num_rows($sql2); 
				$Total[] = $jumlah;
	
			$no = 1; // Untuk penomoran tabel, di awal set dengan 1
			echo "<tr>";
				echo "<td align='center'>".$no."</td>";
				echo "<td>".$data ['NamaDesa']." </td>";
				echo "<td align='center'>".$jumlah."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		}
		echo '
			<tr>
				<td colspan="2" align="center"><strong>Jumlah Timbangan</strong></td>
				<td align="center"><strong>'.array_sum($Total).'</strong></td>
			</tr>
		';
	?>
</table>

<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
