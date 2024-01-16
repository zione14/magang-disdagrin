<?php
include('../config.php');
$IDPerson = $_GET['IDPerson'];
$versi = "SELECT IDTimbangan,NamaTimbangan,KodeLokasi 
		from timbanganperson 
		where IDPerson='$IDPerson' and StatusUTTP='Aktif'";
$conn = mysqli_query($koneksi, $versi);
echo "<option value=''>-- Timbangan User --</option>";
while($kode = mysqli_fetch_array($conn)){
	echo '<option value="'.$kode['IDTimbangan'].'" data-nama="'.$kode['NamaTimbangan'].'" data-lokasi="'.$kode['KodeLokasi'].'">'.$kode['NamaTimbangan'].'</option>';
}
?>
 