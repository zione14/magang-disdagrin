<?php
@session_start();
include "../library/config.php";
date_default_timezone_set('Asia/Jakarta');

$user_aktif 	= @$_SESSION['user_login'];
$user_pass 		= @$_SESSION['user_pass'];
$time_aktif		= @$_SESSION['timeout'];
	
	// Ambil data user berdasarkan username 
	$ses_sql = @mysqli_query($koneksi,  "select UserPsw,UserName,NamaPegawai,Jabatan from userlogin where UserName = '$user_aktif' AND UserPsw='$user_pass' and IsAktif=b'1'");
	$row 	 = @mysqli_fetch_array($ses_sql);
	$login_id			= $row['UserName'];
	$login_nama		 	= $row['NamaPegawai'];
	$login_password 	= $row['UserPsw'];
	$login_level		= $row['Jabatan'];

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

