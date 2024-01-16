<?php 
// session_start();
$tip 	=$_SESSION['ip'];
$tjam	=$_SESSION['jam'];
$ttgl	=$_SESSION['tgl'];

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

$visitor = mysqli_num_rows($q);
$todays=mysqli_num_rows($today);
$hitsnow=mysqli_num_rows($hits_now);

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



$dinas    = mysqli_query($koneksi,"SELECT * FROM setting where id='1'");
$jalan    = mysqli_query($koneksi,"SELECT * FROM setting where id='4'");
$telpon   = mysqli_query($koneksi,"SELECT * FROM setting where id='2'");
$email    = mysqli_query($koneksi,"SELECT * FROM setting where id='3'");
$bidang1  = mysqli_query($koneksi,"SELECT * FROM setting where id='11'");
$bidang2  = mysqli_query($koneksi,"SELECT * FROM setting where id='12'");
$bidang3  = mysqli_query($koneksi,"SELECT * FROM setting where id='13'");
$bidang4  = mysqli_query($koneksi,"SELECT * FROM setting where id='14'");
$bidang5  = mysqli_query($koneksi,"SELECT * FROM setting where id='24'");
$bidang6  = mysqli_query($koneksi,"SELECT * FROM setting where id='25'");


$caridinas		= mysqli_fetch_assoc($dinas);
$carijalan		= mysqli_fetch_assoc($jalan);
$caritelpon		= mysqli_fetch_assoc($telpon);
$cariemail		= mysqli_fetch_assoc($email);
$res1		= mysqli_fetch_assoc($bidang1);
$res2		= mysqli_fetch_assoc($bidang2);
$res3		= mysqli_fetch_assoc($bidang3);
$res4		= mysqli_fetch_assoc($bidang4);
$res5		= mysqli_fetch_assoc($bidang5);
$res6		= mysqli_fetch_assoc($bidang6);


function UserOnline($koneksi){
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
	$to_secs = 600;
	$t_stamp = time();
	$timeout = $t_stamp - $to_secs;
	$REMOTEADDR=get_client_ip();
	$PHPSELF=$_SERVER['PHP_SELF'];
	mysqli_query($koneksi,"INSERT INTO usersonline  VALUES ('$t_stamp','$REMOTEADDR','$PHPSELF', '$tanggal')");
	mysqli_query($koneksi,"DELETE FROM usersonline WHERE timestamp<$timeout");
	$result = mysqli_query($koneksi, "SELECT DISTINCT ip FROM usersonline WHERE file='$PHPSELF'");
	$user   = mysqli_num_rows($result);
	// mysqli_close($koneksi);
	return $user." Users";

		// if ($user == 1){
		// 	return "<b>$user</b> User";
		// }else{
		// 	return "<b>$user</b> Users";
		// }
		
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

?>
