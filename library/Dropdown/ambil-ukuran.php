<?php
include('../config.php');
$KodeKelas = $_GET['KodeKelas'];


$versi = mysqli_query($koneksi,"SELECT * FROM detilukuran WHERE KodeKelas ='$KodeKelas' Order by NamaUkuran ASC");
// echo "<option value=''>-- Nama Ukuran --</option>";
while($k = mysqli_fetch_array($versi)){
    echo "<option value=\"".$k['KodeUkuran']."\">".$k['NamaUkuran']."</option>\n";
}
?>
 