<?php
include('../config.php');
$KodeKec = $_GET['KodeKec'];


$versi = mysqli_query($koneksi,"SELECT * FROM mstdesa WHERE KodeKec ='$KodeKec' Order by NamaDesa ASC");
echo "<option value=''>-- Desa --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeDesa']."\">".$k['NamaDesa']."</option>\n";
}
?>
 