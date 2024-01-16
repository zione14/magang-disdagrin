<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=TimbanganTera.xls");
?>
<style> 
.str{ 
	mso-number-format:\@; 
} 
</style>
<!--<table>
	
	
	<tr><td colspan="4" align="center"><strong>LAPORAN TIMBANGAN TERA</strong></td></tr>
	<tr><td colspan="4" align="center"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>-->
		
<table border="1" cellpadding="10">
	<tr style="background-color: #a6a9a9;">
	  <th>No</th>
	  <th>Nama Timbangan</th>
	  <th>ID Timbangan</th>
	  <th>Bulan/Tahun Tera</th>
	</tr>

	<?php
		// Load file koneksi.php
		include "../config.php";
		$Query = mysqli_query($koneksi,"SELECT a.NamaTimbangan,a.IDTimbangan,MAX(g.TglTera) as Tanggal,i.MasaTera
		FROM timbanganperson a 
		join trtimbanganitem f on (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(f.IDTimbangan,f.KodeLokasi,f.IDPerson) 
		join tractiontimbangan g on f.NoTransaksi=g.NoTransaksi 
		join msttimbangan i on a.KodeTimbangan=i.KodeTimbangan 
		WHERE a.IDPerson !='PRS-2019-0000000' AND  a.StatusUTTP='Aktif'
		GROUP by a.IDTimbangan DESC");
		
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		while($data = mysqli_fetch_array($Query)){ // Ambil semua data dari hasil eksekusi $sql
		$tgl1 = $data['Tanggal'];
		$period = $data['MasaTera'];
		$TeraUlang = date('Y-m-d', strtotime('+'.$period.' year', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 1 tahun		
		
		$date = date_create($TeraUlang);
		$tera = date_format($date,"my");
		
		$num_char = 20;
		@$jmhhuruf = strlen($data['NamaTimbangan']);
		@$namatimbangan = $data['NamaTimbangan'];
		
		@$char     = $namatimbangan{$num_char - 1};
			while($char != ' ') {
				@$char = $namatimbangan{--$num_char}; // Cari spasi pada posisi
			}
		@$text = substr($namatimbangan, 0, $num_char) . '...';
		@$textnama = isset($jmhhuruf) && $jmhhuruf <= '20' ? @$data['NamaTimbangan'] : $text; 	
		
			echo "<tr>";
				echo "<td align='center'>".$no."</td>";
				echo "<td>".$textnama." </td>";
				echo "<td>".$data ['IDTimbangan']."</td>";
				echo "<td class='str'>".$tera."</td>";
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
