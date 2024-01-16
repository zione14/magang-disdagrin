<?php
include('../config.php');
$KodeLokasi = $_GET['KodeLokasi'];
$IDPerson = $_GET['IDPerson'];

$query = mysqli_query($koneksi,"select * from lokasimilikperson where KodeLokasi='$KodeLokasi' and IDPerson='$IDPerson'");
			while ($data = mysqli_fetch_array($query))
			{
				$lat = $data['KoorLat'];
				$lon = $data['KoorLong'];
				echo json_encode(array('lat' => $lat, 'long' => $lon));
				//echo ("addMarker($lat, $lon, '<b>Lokasi Milik User</b>');\n");                        
			}
?>
 