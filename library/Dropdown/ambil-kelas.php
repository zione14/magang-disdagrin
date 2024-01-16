<?php
include('../config.php');
$KodeTimbangan = $_GET['KodeTimbangan'];


$versi = mysqli_query($koneksi,"SELECT * FROM kelas WHERE KodeTimbangan ='$KodeTimbangan' Order by NamaKelas ASC");
// echo "<option value='' >-- Nama Kelas --</option>";
while($k = mysqli_fetch_array($versi)){
	if($k['NamaKelas'] == '' OR $k['NamaKelas'] == null){
		echo "<option selected='selected' value=\"".$k['KodeKelas']."\">".$k['Keterangan']."</option>\n";
	}else{
		echo "<option selected='selected' value=\"".$k['KodeKelas']."\">".$k['NamaKelas']."</option>\n";
	}
	
    
}
?>
 