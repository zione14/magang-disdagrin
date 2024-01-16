<?php
include '../library/config.php';

$KodeKonten  	= $_GET['KodeKonten'];
$JenisData   	= $_GET['JenisData'];
$IsiKomentar 	= $_GET['IsiKomentar'];
$IP			 	= $_GET['IP'];
$TglTransaksi 	= date('Y-m-d H:i:s');
$Sekarang 		= date('Y-m-d');

function jumlahKomentar($koneksi, $KodeKonten, $JenisKonten, $Emoticon, $JenisEmoticon){

  if(isset($JenisEmoticon) AND $JenisEmoticon != ''){
  	$komentar = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisKonten' and  	EmailPengirim='$Emoticon' AND IsiKomentar='$JenisEmoticon' AND IsAktif=b'1'");
  }else{
  	$komentar = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisKonten' and  	EmailPengirim='$Emoticon' AND IsAktif=b'1' ");
  }
  $result	= mysqli_num_rows($komentar);
  return  $result;

}

//membuat id user
$year	 = date('Y');
$sql 	 = mysqli_query($koneksi,'SELECT RIGHT(KodeTanggapan,7) AS kode FROM tanggapankonten WHERE KodeTanggapan LIKE "%'.$year.'%" ORDER BY KodeTanggapan DESC LIMIT 1');  
$num	 = mysqli_num_rows($sql);
 
if($num <> 0) {
	$data = mysqli_fetch_array($sql);
	$kode = $data['kode'] + 1;
}else{
	$kode = 1;
}
 
//mulai bikin kode
 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
 $kode_jadi	 = "KMTR-".$year."-".$bikin_kode;

//Transaksi dengan ip yang sama 
 $cekip = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisData' and  EmailPengirim='Emoticon' AND IsAktif=b'1' AND IP='$IP' AND DATE_FORMAT(TanggalKomentar,'%Y-%m-%d') = '$Sekarang'");
 $resultIP	= mysqli_num_rows($cekip);

 if($resultIP <> 0){
 	$Simpan = mysqli_query($koneksi,"UPDATE tanggapankonten set IsiKomentar='$IsiKomentar' WHERE KodeKonten='$KodeKonten' and JenisKonten='$JenisData' and  EmailPengirim='Emoticon' AND IsAktif=b'1' AND IP='$IP' AND DATE_FORMAT(TanggalKomentar,'%Y-%m-%d') = '$Sekarang'");
 }else{
 	$Simpan = mysqli_query($koneksi,"INSERT INTO tanggapankonten (KodeTanggapan,KodeKonten,JenisKonten,EmailPengirim,TanggalKomentar,IsiKomentar,IsAktif,IP)VALUES('$kode_jadi','$KodeKonten','$JenisData','Emoticon','$TglTransaksi', '$IsiKomentar',b'1','$IP')");
 }
 
	$JumlahUpvote	 = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Upvote');
	$JumlahFunny 	 = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Funny');
	$JumlahLove 	 = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Love');
	$JumlahSurprised = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Surprised');
	$JumlahAngry 	 = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Angry');
	$JumlahSad 		 = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Sad');
	$JumlahKomentar  = jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', '');

	if($Simpan){
		$response = array($JumlahUpvote, $JumlahFunny, $JumlahLove, $JumlahSurprised, $JumlahAngry, $JumlahSad, $JumlahKomentar, $IP);
		echo json_encode($response);
	}

