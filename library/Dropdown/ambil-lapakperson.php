<?php
include('../config.php');
$KodePasar = $_GET['KodePasar'];


$versi = mysqli_query($koneksi,"SELECT a.* FROM lapakpasar a 
LEFT JOIN lapakperson  b on (a.KodePasar,a.IDLapak)=(b.KodePasar,b.IDLapak)
where a.KodePasar='$KodePasar' and b.IDPerson is null ORDER BY BlokLapak");
echo "<option value=''>-- Lapak Pasar --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['IDLapak']."\">".$k['BlokLapak']." ".$k['NomorLapak']."</option>\n";
}
?>
 