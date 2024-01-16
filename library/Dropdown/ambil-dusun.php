<?php
include('../config.php');
$KodeDesa = $_GET['KodeDesa'];


$versi = mysqli_query($koneksi,"SELECT * FROM mstdusun WHERE KodeDesa ='$KodeDesa' Order by NamaDusun ASC");
echo "<option value=''>-- Dusun --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeDusun']."\">".$k['NamaDusun']."</option>\n";
}
?>
 