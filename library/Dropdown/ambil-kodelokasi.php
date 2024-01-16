<?php
include('../config.php');
$IDPerson = $_GET['IDPerson'];


$versi = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson WHERE IDPerson='$IDPerson'");
echo "<option value=''>-- Lokasi Alamat --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeLokasi']."\">(".$k['NamaLokasi'].") ".$k['AlamatLokasi']."</option>\n";
}
?>
 