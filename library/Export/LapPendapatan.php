<?php
//get data
$tgl1 = htmlspecialchars(base64_decode($_GET['tgl1']));
$tgl2 = htmlspecialchars(base64_decode($_GET['tgl2']));
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pendapatan_Retribusi.xls");
include "../tgl-indo.php";
?>
<table>
	<tr><td colspan="4" align="center"><strong>LAPORAN PENDAPATAN RETRIBUSI</strong></td></tr>
	<tr><td colspan="4" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<?php if ($tgl1 != '' AND $tgl2 != '' ) { ?>
		<tr><td colspan="4" align="center"><strong><?php echo TanggalIndo($tgl1).' - '.TanggalIndo($tgl2) ; ?></strong></td></tr>
	<?php } ?>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th>No</th>
	  <th>Nama Lengkap</th>
	  <th>Alamat</th>
	  <th>Nilai Retribusi</th>
	</tr>
	<tr style="background-color: #a6a9a9;">
		<td align="center"><i>1</i></td>
		<td align="center"><i>2</i></td>
		<td align="center"><i>3</i></td>
		<td align="center"><i>4</i></td>
	</tr>
	<?php
		// Load file koneksi.php
		include "../config.php";
		
		// Buat query untuk menampilkan semua data
		if ($tgl1 == '' AND  $tgl2 == '') {
			$Day = date('Y-m-d');
			$Query = mysqli_query($koneksi,"SELECT a.*, b.NamaPerson, b.AlamatLengkapPerson FROM tractiontimbangan a JOIN mstperson b ON a.IDPerson=b.IDPerson WHERE a.StatusTransaksi='SELESAI' AND a.IsDibayar='1' AND (date_format(a.TglAmbil, '%Y-%m-%d') BETWEEN '".$Day."' AND '".@$Day."') AND b.UserName !='dinas' ORDER BY a.TglTransaksi DESC");
		}else{
			$Query = mysqli_query($koneksi,"SELECT a.*, b.NamaPerson, b.AlamatLengkapPerson FROM tractiontimbangan a JOIN mstperson b ON a.IDPerson=b.IDPerson WHERE a.StatusTransaksi='SELESAI' AND a.IsDibayar='1' AND (date_format(a.TglAmbil, '%Y-%m-%d') BETWEEN '".$tgl1."' AND '".$tgl2."') AND b.UserName !='dinas' ORDER BY a.TglTransaksi DESC");
		}
		$tcount = mysqli_num_rows($Query);
			if ($tcount == 0 ){
			echo '
				<tr>
					<td colspan="4" align="center">
						<strong>Data Tidak Ada</strong>
					</td>
				</tr>
			
			';	
		}else{
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		while($data = mysqli_fetch_array($Query)){ // Ambil semua data dari hasil eksekusi $sql
			@$Jumlah[] = $data['TotalRetribusi'];
			
			echo "<tr>";
				echo "<td align='center'>".$no."</td>";
				echo "<td>".$data ['NamaPerson'].",<br> No Transaksi :".$data['NoTransaksi']."<br>" .TanggalIndo($data['TglTransaksi'])."</td>";
				echo "<td align='center'>".$data ['AlamatLengkapPerson']."</td>";
				echo "<td align='center'> Rp ".$data ['TotalRetribusi']."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		}
			echo '
			<tr style="background-color: #9e9999;">
				<td colspan="3" align="center" style="color:white"><strong>Total</strong></td>
				<td align="center" style="color:white"><strong> Rp '.array_sum($Jumlah).'</strong></td>
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
