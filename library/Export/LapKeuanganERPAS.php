<?php
include "../config.php";
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=LapKeuanganERPAS.xls");
$Tanggal = isset($_GET['bl']) ? mysqli_real_escape_string($koneksi,$_GET['bl']) : date('Y-m');
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
?>
<table>
	<tr><td colspan="4" align="center"><strong>LAPORAN POTENSI PENDAPATAN BIDANG RETRIBUSI PASAR BULAN <?php echo strtoupper($BulanIndo[date('m', strtotime($Tanggal)) - 1].' '.date('Y', strtotime($Tanggal))) ?></strong></td></tr>
	<tr><td colspan="4" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th align="center">No</th>
	  <th align="center">Nama Pasar</th>
	  <th align="center">Potensi Pendapatan</th>
	  <th align="center">Realisasi Pendapatan</th>
	</tr>
	

	
	<?php
		
		$sql =  "SELECT a.KodePasar,a.NamaPasar,IFNULL(pendapatan.PendapatanLapak,0) AS PendapatanLapak,IFNULL(realisasi.RealisasiRetribusi,0) AS RealisasiRetribusi
			FROM mstpasar a
			LEFT JOIN(
			    SELECT r.KodePasar, SUM(r.Retribusi) AS PendapatanLapak
				FROM lapakperson r
				WHERE r.IsAktif=b'1'
				GROUP by r.KodePasar
			) AS pendapatan ON pendapatan.KodePasar = a.KodePasar
			LEFT JOIN(
			    SELECT r.KodePasar, SUM(r.NominalDiterima) AS RealisasiRetribusi
				FROM trretribusipasar r
				WHERE LEFT(r.TanggalTrans,7) = '$Tanggal' 
				GROUP by r.KodePasar
			) AS realisasi ON realisasi.KodePasar = a.KodePasar";
		$sql .="  GROUP by a.KodePasar order by  a.NamaPasar";
		$result = mysqli_query($koneksi,$sql);
		$tcount = mysqli_num_rows($result);
			
		if ($tcount == 0 ){
			echo '
				<tr>
					<td colspan="10" align="center">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>
			
			';	
		}else{
			$no = 0; 
			
			while($data = mysqli_fetch_array($result)){
			
				$Realisasi[] = $data['RealisasiRetribusi'];
				$Pendapatan[] = $data['PendapatanLapak'];

				
				echo "<tr>";
					echo "<td align='center'>".++$no."</td>";
					echo "<td>".ucwords($data['NamaPasar'])." </td>";
					echo "<td align='right'>".$data['PendapatanLapak']."</td>";
					echo "<td align='right'>".$data['RealisasiRetribusi']."</td>";
				echo "</tr>";
		
			
			}
			echo '
				<tr>
					<td colspan="2"><strong>TOTAL</strong></td>
					<td><strong>'.array_sum($Pendapatan).'</strong></td>
					<td><strong>'.array_sum($Realisasi).'</strong></td>
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
