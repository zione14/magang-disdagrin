<?php 
@session_start();
@$tip 	=$_SESSION['ip'];
@$tjam	=$_SESSION['jam'];
@$ttgl	=$_SESSION['tgl'];

if($tip=='' && $tjam=='' && $ttgl==''){
	$ip		=$_SERVER['REMOTE_ADDR'];
	$jam	=date("h:i:s");
	$tgl 	=date("d-m-Y");
	$_SESSION ["ip"] = $ip;
	$_SESSION ["jam"] = $jam;
	$_SESSION ["tgl"] = $tgl;
}
$sip	=$_SESSION['ip'];
$sjam	=$_SESSION['jam'];
$stgl	=$_SESSION['tgl'];



$ip=$_SERVER['REMOTE_ADDR'];
$tanggal=date("d-m-Y");
$tgl=date("d");
$bln=date("m");
$thn=date("Y");
$tglk=$tgl-1;

$baca  =mysqli_query($koneksi,"SELECT * FROM konter WHERE ip='$sip' AND tanggal='$stgl' AND waktu='$sjam'");
$baca1 =mysqli_num_rows($baca);
if($baca1==0){
	$tkonter=mysqli_query($koneksi,"INSERT INTO konter VALUES ('$sip','$stgl','$sjam')");
}

$q =mysqli_query($koneksi,"SELECT * FROM konter");
$today=mysqli_query($koneksi,"SELECT * FROM konter WHERE tanggal='$tanggal'");
$hits_now  =mysqli_query($koneksi,"SELECT * FROM usersonline WHERE tanggal='$stgl'");

$visitor 	=mysqli_num_rows($q);
$todays		=mysqli_num_rows($today);
$hitsnow 	=mysqli_num_rows($hits_now);

function sistemSetting($koneksi, $id, $jenis){
  $setting=mysqli_query($koneksi, "SELECT nama,value,file FROM setting where id='$id'");
  $result=mysqli_fetch_array($setting);

	if($jenis=='nama'){
	  	return  $result['nama'];
	}elseif($jenis=='value'){
	  	return  $result['value'];
	}else{
	  	return  $result['file'];
	}
}


function UserOnline($koneksi){
	// Function to get the client IP address
	function get_client_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

	$tanggal=date("d-m-Y");
	$to_secs = 500;
	$t_stamp = time();
	$timeout = $t_stamp - $to_secs;
	// $REMOTEADDR=$_SERVER['REMOTE_ADDR'];
	$REMOTEADDR=get_client_ip();
	$PHPSELF=$_SERVER['PHP_SELF'];
	mysqli_query($koneksi,"INSERT INTO usersonline  VALUES ('$t_stamp','$REMOTEADDR','$PHPSELF', '$tanggal')");
	mysqli_query($koneksi,"DELETE FROM usersonline WHERE timestamp < '$timeout'");
	$result = mysqli_query($koneksi, "SELECT * FROM usersonline WHERE file='$PHPSELF' GROUP BY ip");
	$user   = mysqli_num_rows($result);

	return $user." Users";
}

function counter($koneksi){
	$countertabel=mysqli_query($koneksi, "SELECT * FROM hitscounter");
	$totalyangada=mysqli_fetch_array($countertabel);
	$totalyangada1=$totalyangada['hits']+1;

	$updatecounter=mysqli_query($koneksi, "UPDATE hitscounter SET hits = '$totalyangada1'");
	$tampilkansekarang=mysqli_query($koneksi, "SELECT * FROM hitscounter");
	$tampilkansekarang1=mysqli_fetch_array($tampilkansekarang);
	return  $tampilkansekarang1['hits'];

}

function jumlahKomentar($koneksi, $KodeKonten, $JenisKonten, $Emoticon, $JenisEmoticon){

  if(isset($JenisEmoticon) AND $JenisEmoticon != ''){
  	$komentar = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisKonten' and  	EmailPengirim='$Emoticon' AND IsiKomentar='$JenisEmoticon' AND IsAktif=b'1'");
  }else{
  	$komentar = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisKonten' and  	EmailPengirim='$Emoticon' AND IsAktif=b'1' ");
  }
  $result	= mysqli_num_rows($komentar);
  return  $result;

}

?>
