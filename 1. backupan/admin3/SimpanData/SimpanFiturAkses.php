<?php  
include ('../../library/config.php');

	//POST Data 
	@$ViewData   = $_POST['ViewData'];
	@$AddData    = $_POST['AddData'];
	@$EditData 	 = $_POST['EditData'];
	@$DeleteData = $_POST['DeleteData'];
	@$PrintData  = $_POST['PrintData'];
	@$LevelID    = $_POST['LevelID'];
	@$FiturID    = $_POST['FiturID'];
	@$LevelName  = $_POST['LevelName'];
	@$LoginID    = $_POST['LoginID'];
	
	
	$Edit = mysqli_query($koneksi,"UPDATE fiturlevel SET ViewData='$ViewData', AddData='$AddData', EditData='$EditData', DeleteData='$DeleteData', PrintData='$PrintData' WHERE  FiturID='$FiturID' and LevelID='$LevelID'");
	
	if ($Edit){
		InsertLog($koneksi, 'Edit Data', 'Mengubah Akses Level User '.$LevelName, $LoginID, '', 'Akses Level');
		echo '<script>alert("Data Berhasil Disimpan");window.location="../FiturLevel.php?id='.base64_encode($LevelID).'&nm='.base64_encode($LevelName).'"</script>';
		
	}else{
		echo '<script>alert("Data Gagal Disimpan"); window.location="../FiturLevel.php?id='.base64_encode($LevelID).'&nm='.base64_encode($LevelName).'"</script>';
	}

?>  