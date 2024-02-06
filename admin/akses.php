<?php
@session_start();
include "../library/config.php";
date_default_timezone_set('Asia/Jakarta');

$user_aktif 	= @$_SESSION['user_login'];
$user_pass 		= @$_SESSION['user_pass'];
$time_aktif		= @$_SESSION['timeout'];
	
	// Ambil data user berdasarkan username 
	// $ses_sql = @mysqli_query($koneksi,  "select a.FotoProfil,a.JenisLogin,a.UserName,a.NamaPegawai,a.UserPsw,a.LevelID,b.LevelName from userlogin a left join accesslevel b on  a.LevelID=b.LevelID where a.UserName = '$user_aktif' AND a.UserPsw='$user_pass' and a.IsAktif=b'1'");
	// $user 	 = @mysqli_fetch_array($ses_sql);
	$sql = "SELECT a.FotoProfil,a.JenisLogin,a.UserName,a.NamaPegawai,a.UserPsw,a.LevelID,b.LevelName FROM userlogin A LEFT JOIN accesslevel b on  a.LevelID=b.LevelID WHERE a.UserName = ? AND a.UserPsw= ? AND a.IsAktif=b'1'";
	$stmt = $koneksi->prepare($sql);
	$stmt->bind_param("ss", $user_aktif, $user_pass);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	$login_id			= $user['UserName'];
	$login_nama		 	= $user['NamaPegawai'];
	$login_password 	= $user['UserPsw'];
	$login_level		= $user['LevelID'];
	$login_levelname	= $user['LevelName'];
	$JenisLogin			= $user['JenisLogin'];
	$FotoProfil			= $user['FotoProfil'];

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

