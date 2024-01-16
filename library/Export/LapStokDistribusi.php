<?php
//get data
$Bulan = date('Y-m');
@$Tgl = isset($_GET['tgl']) && $_GET['tgl'] != null ? @htmlspecialchars($_GET['tgl']) : $Bulan; 
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
include '../config.php';
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Stokdistributor.xls");
include "../tgl-indo.php";
?>
<table>
	<tr><td colspan="4" align="left"><strong>LAPORAN STOK PUPUK DISTRIBUTOR PADA <?php echo strtoupper($BulanIndo[date('m', strtotime($Tgl)) - 1].' '.date('Y', strtotime($Tgl)));?></strong></td></tr>
	<tr><td colspan="4" align="left"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr></tr>
</table>
		
<table border="1" cellpadding="10">
	<?php 
		$ditri = mysqli_query($koneksi,"SELECT NamaPerson  FROM mstperson WHERE JenisPerson LIKE '%PupukSub%' AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL");
		$num = mysqli_num_rows($ditri);
	?>
	<tr style="background-color: #a6a9a9;">
	  <th align="center" rowspan="2">No</th>
	  <th align="center" rowspan="2">Nama Pupuk</th>
	  <th align="center" rowspan="2">Satuan</th>
	  <th colspan="<?=($num);?>">Stok Distributor Pupuk</th>
	  <!--<th align="center" rowspan="2">Jumlah</th>-->
	</tr>
	<?php
		echo '<tr style="background-color: #a6a9a9;">';
			while($r = mysqli_fetch_array($ditri)){ 
				echo '<th>'.$r[0].'</th>';
			}
		echo '</tr>';
	?>
	
	
	<?php
		$sql =  "SELECT a.KodeBarang,a.NamaBarang,a.Keterangan, IFNULL(b.Penerimaan, 0) AS Penerimaan,IFNULL(b.Penjualan, 0) AS Penjualan,b.KodeBarang
		FROM mstpupuksubsidi a
		LEFT JOIN (
			SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan,KodeBarang
			FROM sirkulasipupuk b
			WHERE b.KodeBarang = KodeBarang AND b.Keterangan= '$Tgl'
			GROUP BY b.KodeBarang
		) b ON b.KodeBarang = a.KodeBarang
		where a.IsAktif=b'1'";
									
		$sql .="  Order by NamaBarang ASC";
		$result = mysqli_query($koneksi,$sql);	
		
	
		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		while($data = mysqli_fetch_assoc($result)){ // Ambil semua data dari hasil eksekusi $sql
		$Total = $data['Penerimaan']-$data['Penjualan'];
			
			
			echo "<tr>";
				echo "<td align='center'>".$no."</td>";
				echo "<td>".$data ['NamaBarang']."</td>";
				echo "<td align='center'>".$data ['Keterangan']."</td>";
				
				$tb = mysqli_query($koneksi,"SELECT IDPerson FROM mstperson WHERE  JenisPerson LIKE '%PupukSub%'  AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL");
				while($res = mysqli_fetch_assoc($tb)){ 
					echo '<td align="center">'.@stokSekarang($koneksi, $res['IDPerson'], $data['KodeBarang'], $Tgl) .'</td>';
				}
				
				// echo "<td align='right'>".$Total."</td>";
			echo "</tr>";
			
			$no++; // Tambah 1 setiap kali looping
		
			
		}
	?>
</table>

<?php
	function stokSekarang($koneksi, $IDPerson, $KodeBarang, $TanggalTransaksi){
		$query = "SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan
		FROM sirkulasipupuk b
		JOIN mstperson c ON b.IDPerson=c.IDPerson
		WHERE b.KodeBarang = '$KodeBarang' AND c.ID_Distributor='$IDPerson' AND b.Keterangan = '$TanggalTransaksi'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$Penerimaan = $result['Penerimaan'];
		$Penjualan = $result['Penjualan'];
		$Total = $Penerimaan-$Penjualan;
		return number_format($Total, 2);
	}	

echo '<script language="javascript">
		window.close();
		</script>';
		?>
