<?php
include('../config.php');
$d = base64_decode($_GET['d']);

if($d == 'ketersediaan') {

	$versi = mysqli_query($koneksi,"SELECT KodeGroup,NamaGroup FROM mstgroupbarang WHERE IsAktif = 1 AND (KodeGroup ='GROUP-2019-0000001' OR KodeGroup ='GROUP-2019-0000004' OR KodeGroup ='GROUP-2019-0000005' OR KodeGroup ='GROUP-2019-0000012' OR KodeGroup ='GROUP-2019-0000013') ORDER BY KodeGroup ASC");
	// echo "<option value=''>-- Pilih --</option>";
	while($k = mysqli_fetch_array($versi)){
	    echo "<option value=\"".base64_encode($k['KodeGroup'])."\">".$k['NamaGroup']."</option>\n";
	}

}else{
	$versi = mysqli_query($koneksi,"SELECT KodeGroup,NamaGroup FROM mstgroupbarang WHERE IsAktif = 1 ORDER BY KodeGroup ASC");
	// echo "<option value=''>-- Pilih --</option>";
	while($k = mysqli_fetch_array($versi)){
	    echo "<option value=\"".base64_encode($k['KodeGroup'])."\">".$k['NamaGroup']."</option>\n";
	}

}




?>
 