<?php
include('../config.php');
$IDPerson = $_GET['IDPerson'];


$versi = mysqli_query($koneksi,"SELECT * FROM lapakperson WHERE IDPerson ='$IDPerson'");
echo "<option value=''>-- Lapak Pasar --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['IDLapak']."#".$k['KodePasar']."\">".$k['BlokLapak']." ".$k['NomorLapak']."</option>\n";
}
?>
 