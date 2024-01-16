<?php
include('../config.php');
$KodeTimbangan = $_GET['KodeTimbangan'];


$versi = mysqli_query($koneksi,"SELECT * FROM detilukuran WHERE KodeTimbangan ='$KodeTimbangan' Order by NamaUkuran ASC");
// echo "<option value=''>-- Nama Ukuran --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeUkuran']."\">".$k['NamaUkuran']."</option>\n";
}
?>
 