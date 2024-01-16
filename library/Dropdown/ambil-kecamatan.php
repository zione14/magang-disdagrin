<?php
include('config.php');
$KodeLokasi = $_GET['KodeLokasi'];
$versi = mysqli_query($koneksi,"SELECT * FROM mstdusun WHERE KodeLokasi ='$KodeLokasi'");
echo "<option value=''>-- Dusun --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeDusun']."\">".$k['NamaDusun']."</option>\n";
}
?>
 