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
	$sql1 	 = mysqli_query($koneksi,"SELECT a.IDPerson, b.KodePasar, b.IDLapak FROM mstperson a LEFT JOIN lapakperson b ON a.IDPerson=b.IDPerson WHERE b.QrCode = '$Noqr'");  
	while($cek_data= mysqli_fetch_array($sql1)){
		$IDPerson = $cek_data['IDPerson'];
		$KodePasar = $cek_data['KodePasar'];
		$IDLapak = $cek_data['IDLapak'];
	}
	//cek apakah transaksi sudah ada apa belum
	$cek_tr = mysqli_query($koneksi, "SELECT NoTransRet FROM trretribusipasar WHERE IDPerson='$IDPerson' AND KodePasar='$KodePasar' AND IDLapak='$IDLapak' AND TanggalTrans='$Tanggal' AND NominalDiterima='$Nominal'");
	$jml_data = mysqli_num_rows($cek_tr);
	if($jml_data == 0){
		//kode tr ret
		$sql = "SELECT RIGHT(NoTransRet,7) AS kode FROM trretribusipasar WHERE NoTransRet LIKE '%$tahun%' ORDER BY NoTransRet DESC LIMIT 1";
		$res = mysqli_query($koneksi, $sql);
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
		$trretri = mysqli_query($koneksi,"INSERT INTO trretribusipasar (NoTransRet,TanggalTrans,NominalRetribusi,NominalDiterima,IsTransfer,Keterangan,IDPerson,KodePasar,IDLapak) VALUES ('$kode_jadi', '$Tanggal', '0','$Nominal','1','-','$IDPerson','$KodePasar','$IDLapak')");

	}		
	$i++;			
}		
echo '<script language="javascript">document.location="TrRetribusiPasarBJ.php?KodeBJ='.$kodebj.'&Tanggal1='.$tgl.'&psn='.base64_encode('Tidak Ada Data.').'"</script>';
?>