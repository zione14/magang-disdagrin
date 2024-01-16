<?php
include('../config.php');
$KodePasar = $_GET['KodePasar'];


$versi = mysqli_query($koneksi,"SELECT * FROM lapakpasar WHERE KodePasar ='$KodePasar'");
echo "<option value=''>-- Lapak Pasar --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['IDLapak']."\">".$k['BlokLapak']." ".$k['NomorLapak']."</option>\n";
}
?>
 