<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=RekapHPP_Harian.xls");
include "../config.php";
include "../tgl-indo.php";
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

$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';
$DSPLY = "";
if($display === "ketersediaan"){
    $DSPLY = "JUMLAH KETERSEDIAAN";
}elseif($display === "hprodusen"){
    $DSPLY = "HARGA PRODUSEN";
}else{
    $DSPLY = "HARGA KONSUMEN";
}

$sql_p = "SELECT * FROM mstpasar WHERE IF(length('$KodePasar') > 0, KodePasar = '$KodePasar', TRUE) ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$data_pasar = array();
while ($row_p = $res_p->fetch_assoc()) {
    if($row_p){
        array_push($data_pasar, $row_p);
    }
}

?>
<table>
	<tr><td colspan="30" align="left"><strong><h3>DATA REKAP HARIAN <?=$DSPLY?> </h3></strong></td></tr>
	<tr><td colspan="30" align="left"><strong>DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG</strong></td></tr>
	<tr><td colspan="30" align="left"><strong>PADA <?=strtoupper(TanggalIndo($Tanggal))?></strong></td></tr>
	<tr></tr>
</table>

<table border="1" cellpadding="10">
	<tr bgcolor='#d1d1d1'>
		<th align="center" rowspan="2" style="height:40px;" >No</th>
		<th align="center" rowspan="2"  style="height:40px;">Bahan Pokok Pasar</th>
		<th align="center" colspan="<?php echo (sizeof($data_pasar)+1); ?>" style="height:20px;">Nama Pasar</th>
		
	</tr>
	<tr bgcolor='#d1d1d1'>
		<?php foreach($data_pasar as $psr): ?>
		<th align="right"><?php echo ucwords(str_replace('pasar', '', strtolower($psr['NamaPasar']))); ?></th>
		<?php endforeach; ?>
		<th align="right">Rata rata</th>
	</tr>
	<?php
		if($KodeBarang !== ""){
			$sql_b = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
			FROM mstbarangpokok b
			LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
			WHERE b.IsAktif = '1' AND b.KodeBarang = '".$KodeBarang."'
			GROUP BY b.KodeBarang
			ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
		}else{
			$sql_b = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
			FROM mstbarangpokok b
			LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
			WHERE b.IsAktif = '1'
			GROUP BY b.KodeBarang
			ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
		}
		
		$result = mysqli_query($koneksi, $sql_b);
		$tcount = mysqli_num_rows($result);
		$no_urut = 0;
		$data_barang = array();
		while($data = mysqli_fetch_assoc($result)) {
			 array_push($data_barang, $data);
			 
		}
		
		 $rekap_data = array();
			foreach ($data_barang as $brg) {                                                        
				$sql_r = "SELECT r.*, b.NamaBarang, b.Satuan, p.NamaPasar, u.ActualName
					 FROM mstbarangpokok b
					 LEFT JOIN reporthargaharian r ON b.KodeBarang = r.KodeBarang AND DATE(r.Tanggal) = DATE('".$Tanggal."')
					 LEFT JOIN mstpasar p ON p.KodePasar = r.KodePasar
					 LEFT JOIN userlogin u ON u.UserName = r.UserName
					 WHERE b.KodeBarang = '".$brg['KodeBarang']."'
					 ORDER BY b.KodeGroup";
				$stmt = $koneksi->prepare($sql_r);
				if($stmt->execute()){
					$result = $stmt->get_result();
					while ($row = $result->fetch_assoc()) {
						if($row != null){
							array_push($rekap_data, $row);
						}
					}
					$stmt->free_result();
					$stmt->close();
				}
			}
			$psrData = array();
			foreach ($data_pasar as $psr ) {
				$dtpsr = array();
				foreach ($rekap_data as $rkp) {
					if($rkp['KodePasar'] === $psr['KodePasar']){
						array_push($dtpsr, $rkp);
					}
				}
				$psrData[$psr['KodePasar']] = $dtpsr;
			}
			// echo json_encode($psrData);

			if($tcount==0){
				echo '
				<tr><td colspan="7" align="center">
					<strong>Tidak ada data</strong>
				</td></tr>';
			}else{
				$kodegroup = "";
				foreach ($data_barang as $brg):
					if($kodegroup !== $brg['KodeGroup']){
						echo '<tr style="background:#f7f7f7;"><td></td>
							<td colspan="'.(sizeof($data_pasar) + 1).'"><strong>'.ucwords($brg['NamaGroup']).'</strong></td>
						</tr>';
						$kodegroup = $brg['KodeGroup'];
					}
	?>
	
			 <tr>
				<td><?php echo ++$no_urut;?></td>
				<td><?php echo $brg['NamaBarang']; ?></td>
				<?php 
				$jml_psr = 0;
				$jml_harga = 0;
				foreach($data_pasar as $psr): ?>
				<td align="right">
					<?php 
					$data_pasar_ = $psrData[$psr['KodePasar']];
					if($display === "ketersediaan"){
						$sedia = 0;
						foreach ($data_pasar_ as $dp) {
							if($dp['KodeBarang'] === $brg['KodeBarang']){
								$sedia = $dp['Ketersediaan'];
								$jml_psr ++;
								$jml_harga += $sedia;
								break;
							}
						}
						echo number_format($sedia);
					}elseif($display === "hprodusen"){
						$hargapro = 0;
						foreach ($data_pasar_ as $dp) {
							if($dp['KodeBarang'] === $brg['KodeBarang']){
								$hargapro = $dp['HargaProdusen'];
								$jml_psr ++;
								$jml_harga += $hargapro;
								break;
							}
						}
						echo 'Rp.'.number_format($hargapro);
					}else{
						$hargabrg = 0;
						foreach ($data_pasar_ as $dp) {
							if($dp['KodeBarang'] === $brg['KodeBarang']){
								$hargabrg = $dp['HargaBarang'];
								$jml_psr ++;
								$jml_harga += $hargabrg;
								break;
							}
						}
						echo 'Rp.'.number_format($hargabrg);
					}?>
				</td>
				<?php endforeach; ?>
				<th align="right">
					<?php 
					if($jml_harga < 1 || $jml_psr < 1){
						echo '--';
					}else{
						$rata2 = $jml_harga / $jml_psr;
						if($display === "ketersediaan"){
							echo number_format($rata2);
						}else{
							echo 'Rp.'.number_format($rata2);
						}
					}
					?>
				</th>
			</tr>                            
		<?php endforeach;
			}                                                    
		?>
	
</table>		



<?php
echo '<script language="javascript">
		window.close();
	  </script>';
?>
