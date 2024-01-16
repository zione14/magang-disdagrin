<?php
@session_start();
include "../library/config.php";
include '../library/tgl-indo.php';
date_default_timezone_set('Asia/Jakarta');

$user_aktif 	= @$_SESSION['user_login'];
$user_pass 		= @$_SESSION['user_pass'];
$time_aktif		= @$_SESSION['timeout'];
	
	// Ambil data user berdasarkan username 
	$ses_sql = @mysqli_query($koneksi,  "select GambarPerson,IDPerson,NamaPerson,Password,AlamatLengkapPerson,JenisPerson from mstperson where UserName = '$user_aktif' AND Password='$user_pass' and IsVerified=b'1'");
	$row 	 = @mysqli_fetch_array($ses_sql);
	$login_id			= $row['IDPerson'];
	$login_nama		 	= $row['NamaPerson'];
	$login_password 	= $row['Password'];
	$login_alamat		= $row['AlamatLengkapPerson'];
	$jenis_person		= $row['JenisPerson'];
	$FotoProfil			= $row['GambarPerson'];

	// echo $user_aktif;
	$waktu    = time()+25200; //(GMT+7)
	$expired  = 30000;

	if(isset($_SESSION['user_login']) AND @$login_id!=null){
		//jika waktu sekarang kurang dari sesi timeout
		if($waktu < $time_aktif){
			//hapus sesi timeout yang lama ,buat sesi timeout yang baru
			unset($_SESSION['timeout']);
			@$_SESSION['timeout'] = $waktu + $expired;
			//disini konten untuk user atau admin yang berhasil login
		}else{
			session_destroy();
			echo '<script language="javascript">document.location="../library/logout.php?id='.base64_encode("timeout").'"; </script>';
		}
	}else{
		echo '<script language="javascript">document.location="../library/logout.php?id='.base64_encode("error").'"; </script>';
	}

?>

