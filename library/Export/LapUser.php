<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_User.xls");
?>
<table>
	
	
	<tr><td colspan="4" align="center"><strong>LAPORAN USER</strong></td></tr>
	<tr><td colspan="4" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th>No</th>
	  <th>Nama Lengkap</th>
	  <th colspan="2">Alamat</th>
	</tr>
	<tr style="background-color: #a6a9a9;">
		<td align="center"><i>1</i></td>
		<td align="center"><i>2</i></td>
		<td colspan="2" align="center"><i>3</i></td>
	</tr>
	<?php
		// Load file koneksi.php
		include "../config.php";
		@$Mode   = htmlspecialchars($_GET['v']); //kode berita yang akan dikonvert  
		$subquery = "";
		
		if ($Mode != NULL OR $Mode != ''){
			$subquery = "AND JenisPerson Like '%$Mode%'";
		}else{
			$subquery = "";
		}
			
		$Query = mysqli_query($koneksi,"SELECT * FROM mstperson where IsVerified=b'1' ".$subquery."  ORDER BY NamaPerson ASC");
				
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		while($data = mysqli_fetch_array($Query)){ // Ambil semua data dari hasil eksekusi $sql
			echo "<tr>";
				echo "<td align='center'>".$no."</td>";
				echo "<td>".$data ['NamaPerson']." </td>";
				echo "<td colspan='2'>".$data ['AlamatLengkapPerson']."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		}
		
	?>
</table>

<?php
echo '<script language="javascript">
		window.close();
		</script>';
?>
