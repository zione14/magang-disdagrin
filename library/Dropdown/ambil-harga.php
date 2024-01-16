<?php
include('../config.php');

$IDLapak = $_POST["Lapak"];
$KodePasar = $_POST['Pasar'];
$output = GetHargaLapak($koneksi, $KodePasar, $IDLapak);
echo $output;
    
function GetHargaLapak($koneksi, $KodePasar, $IDLapak) {
	$sql = "SELECT u.TglAktif,u.KodePasar,u.Retribusi,u.IDLapak,c.TanggalTrans
	FROM lapakperson u 
	LEFT JOIN (
		SELECT c.IDLapak,c.IDPerson,c.KodePasar,Max(c.TglSampaiDibayar) as TanggalTrans
		FROM trretribusipasar c
	  
		WHERE c.IDPerson = IDPerson and c.KodePasar = KodePasar and c.IDLapak= IDLapak
		GROUP BY c.IDPerson,c.KodePasar,c.IDLapak
	) c ON (c.IDPerson,c.KodePasar,c.IDLapak)=  (u.IDPerson,u.KodePasar,u.IDLapak)
	WHERE u.KodePasar = '$KodePasar' AND u.IDLapak = '$IDLapak'";

	// $res = $koneksi->query($sql);
	$res = mysqli_query($koneksi, $sql);
	if ($res) {
		$row = mysqli_fetch_assoc($res);		
		return $row['TglAktif']."#". $row['KodePasar']."#".$row['Retribusi']."#".$row['IDLapak']."#".$row['TanggalTrans'];
	} else {
		return 'gAGAL';
	}
 

}