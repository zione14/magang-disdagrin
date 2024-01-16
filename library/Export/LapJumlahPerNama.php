<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_LapJumlahPerNama.xls");
?>
<table>
	<tr><td colspan="4" align="center"><strong>LAPORAN JUMLAH TIMBANGAN  DESA <?php echo base64_decode($_GET['nmd']);?> KECAMATAN <?php echo base64_decode($_GET['nmk']);?></strong></td></tr>
	<tr><td colspan="4" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th>No</th>
	  <th>Nama Desa</th>
	  <th>Nama Timbangan</th>
	  <th>Jumlah </th>
	</tr>
	<?php
		// Load file koneksi.php
		include "../config.php";
		
		$sql =  "SELECT a.IDTimbangan,f.NamaDesa,g.NamaKecamatan,c.NamaTimbangan,d.NamaKelas,e.NamaUkuran,a.KodeUkuran,a.KodeKelas,a.KodeTimbangan,b.KodeKec,b.KodeDesa,h.UserName FROM timbanganperson a JOIN lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) join msttimbangan c on a.KodeTimbangan=c.KodeTimbangan join kelas d on c.KodeTimbangan=d.KodeTimbangan join detilukuran e on (d.KodeTimbangan,d.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) join mstdesa f on b.KodeDesa=f.KodeDesa join mstkec g on b.KodeKec=g.KodeKec join mstperson h on h.IDPerson=a.IDPerson WHERE h.UserName != 'dinas' and a.StatusUTTP='Aktif' ";
									
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
				echo '
					<tr>
						<td colspan="4" align="center">
							<strong>Data Tidak Ada</strong>
						</td>
					</tr>
				
				';	
			}else{
				
				while($data = mysqli_fetch_array($result)){
					$query = mysqli_query($koneksi, ("select a.IDTimbangan from timbanganperson a  join lokasimilikperson b on (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) where a.KodeTimbangan='".$data['KodeTimbangan']."' and a.KodeKelas='".$data['KodeKelas']."' and a.KodeUkuran='".$data['KodeUkuran']."' and b.KodeKec='".$data['KodeKec']."' and b.KodeDesa='".$data['KodeDesa']."'"));
					$nums2 = mysqli_num_rows($query); 
					$Jumlah[] =$nums2;
	
					$no = 1; 
					echo "<tr>";
						echo "<td align='center'>".$no."</td>";
						echo "<td>".$data['NamaDesa'].", ".$data['NamaKecamatan']." </td>";
						echo "<td>".$data[3]." ".$data[4]." ".$data[5]."</td>";
						echo "<td align='center'>".$nums2."</td>";
					echo "</tr>";
			
					$no++; // Tambah 1 setiap kali looping
				
				}
				echo '
						<tr align="center">
							<td colspan="3"><strong>Jumlah Timbangan</strong></td>
							<td><strong>'.array_sum($Jumlah).'</strong></td>
						</tr>
				';
			}
	?>
</table>

<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
