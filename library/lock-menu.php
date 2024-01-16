<?php

$cek_query = mysqli_query($koneksi, "SELECT a.* , b.* FROM fiturlevel a JOIN accesslevel b ON a.LevelID=b.LevelID WHERE a.LevelID='$login_level' AND a.FiturID='$fitur_id' and b.IsAktif=b'1' ");

	$cek_fitur = mysqli_fetch_array($cek_query);
	$num_fitur = mysqli_num_rows($cek_query);
	
		if($num_fitur == 0 ){
			echo '<script type="text/javascript">document.location="error-access.php";</script>';
			// break;
		}
	
	
?>