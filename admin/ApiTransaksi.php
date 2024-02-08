<?php 
include 'akses.php';
$tahun=date('Y');
@$tgl = htmlspecialchars($_GET['Tanggal']);
@$kodebj = htmlspecialchars($_GET['KodeBJ']);
@$post_data['Tanggal'] = $tgl;
@$post_data['Kodepasar'] = $kodebj;
$Url = "http://itkp.my.id/rest/api/update";
foreach ( $post_data as $key => $value) {
	$post_items[] = $key . '=' . $value;
}
$post_string = implode ('&', $post_items);
$curl_connection = curl_init($Url);
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
$response = curl_exec($curl_connection);
curl_close($curl_connection);
$result = json_decode($response);

$i=0;
foreach ($result->Detail as $key => $value) {
	$KodeTransaksi = $result->Detail[$i]->KodeTransaksi;
	$Noqr = $result->Detail[$i]->Noqr;
	$Nominal = $result->Detail[$i]->Nominal;
	$Tanggal = $result->Detail[$i]->Tanggal;
	//Ambil Kode IDPerson
	// $sql1 	 = mysqli_query($koneksi,"SELECT a.IDPerson, b.KodePasar, b.IDLapak FROM mstperson a LEFT JOIN lapakperson b ON a.IDPerson=b.IDPerson WHERE b.QrCode = '$Noqr'");  
	$sql1 = "SELECT a.IDPerson, b.KodePasar, b.IDLapak FROM mstperson a LEFT JOIN lapakperson b ON a.IDPerson=b.IDPerson WHERE b.QrCode = ? ";
	$stmt = $koneksi->prepare($sql1);
	$stmt->bind_param("s",$Noqr);
	$stmt->execute();
	$result = $stmt->get_result();
	$akubir = $result->fetch_assoc();
	while($cek_data= mysqli_fetch_array($akubir)){
		$IDPerson = $cek_data['IDPerson'];
		$KodePasar = $cek_data['KodePasar'];
		$IDLapak = $cek_data['IDLapak'];
	}
	//cek apakah transaksi sudah ada apa belum
	// $cek_tr = mysqli_query($koneksi, "SELECT NoTransRet FROM trretribusipasar WHERE IDPerson='$IDPerson' AND KodePasar='$KodePasar' AND IDLapak='$IDLapak' AND TanggalTrans='$Tanggal' AND NominalDiterima='$Nominal'");
	$sql2 = "SELECT NoTransRet FROM trretribusipasar WHERE IDPerson= ? AND KodePasar= ? AND IDLapak= ? AND TanggalTrans= ? AND NominalDiterima= ?";
	$stmt = $koneksi->prepare($sql2);
	$stmt->bind_param("ssssd", $IDPerson, $KodePasar, $IDLapak, $Tanggal, $Nominal);
	$stmt->execute();
	$result = $stmt->get_result();
	$cek_tr = $result->fetch_assoc();
	$jml_data = mysqli_num_rows($cek_tr);
	if($jml_data == 0){
		//kode tr ret
		$sql = "SELECT RIGHT(NoTransRet,7) AS kode FROM trretribusipasar WHERE NoTransRet LIKE CONCAT('%', ?, '%') ORDER BY NoTransRet DESC LIMIT 1";
		$stmt = $koneksi->prepare($sql);
		$stmt->bind_param("s", $tahun);
		$stmt->execute();
		$result = $stmt->get_result();
		$res = $result->fetch_assoc();
		// $res = mysqli_query($koneksi, $sql);
		if(mysqli_num_rows($res) > 0){
			$resultkode = mysqli_fetch_array($res);
			if ($resultkode['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$resultkode['kode'];
			}	
		}else{
			$kode = 1;
		}

		$bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		$kode_jadi  = 'TRP-' . $tahun . '-' . $bikin_kode ;
		//insert ke trretribusi
		// $trretri = mysqli_query($koneksi,"INSERT INTO trretribusipasar (NoTransRet,TanggalTrans,NominalRetribusi,NominalDiterima,IsTransfer,Keterangan,IDPerson,KodePasar,IDLapak) VALUES ('$kode_jadi', '$Tanggal', '0','$Nominal','1','-','$IDPerson','$KodePasar','$IDLapak')");
		$sql5 = "INSERT INTO trretribusipasar (NoTransRet,TanggalTrans,NominalRetribusi,NominalDiterima,IsTransfer,Keterangan,IDPerson,KodePasar,IDLapak) VALUES ('?', '?', '0','?','1','-','?','?','?')";
		$stmt = $koneksi->prepare($sql5);
		$ab = $kode_jadi;
		$ac = $Tanggal;
		$ad = $Nominal;
		$ae = $IDPerson;
		$af = $KodePasar;
		$ag = $IDLapak;
		$stmt->bind_param("ssssss",$ab, $ac, $ad, $ae, $af, $ag);
		$stmt->execute();
		$result = $stmt->get_result();
		$trretri = $result->fetch_assoc();
	}		
	$i++;			
}		
echo '<script language="javascript">document.location="TrRetribusiPasarBJ.php?KodeBJ='.$kodebj.'&Tanggal1='.$tgl.'&psn='.base64_encode('Tidak Ada Data.').'"</script>';
?>