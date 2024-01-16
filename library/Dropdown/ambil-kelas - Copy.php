<?php
include('../config.php');
$KodeTimbangan = $_GET['KodeTimbangan'];


$versi = mysqli_query($koneksi,"SELECT * FROM kelas WHERE KodeTimbangan ='$KodeTimbangan' Order by NamaKelas ASC");
echo "<option value=''>-- Nama Kelas --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeKelas']."\">".$k['NamaKelas']."</option>\n";
}
?>
 