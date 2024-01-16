<?php
include('../config.php');
$KodePasar = $_GET['KodePasar'];
$IDLapak   = $_GET['IDLapak'];
$Tanggal   = $_GET['Tanggal'];

$report= array();
$sql = "SELECT a.KodePasar, a.IDPerson, a.IDLapak, b.BlokLapak, c.NamaPerson, IFNULL(b.Retribusi, 0) as Retribusi, b.NomorLapak, IFNULL(d.NominalDiterima, 0) NominalDiterima, d.TglMulaiDibayar 
FROM lapakperson a 
JOIN lapakpasar  b on (a.KodePasar,a.IDLapak)=(b.KodePasar,b.IDLapak)
JOIN mstperson c ON a.IDPerson = c.IDPerson
LEFT JOIN (
		SELECT d.NominalDiterima, d.IDPerson, d.KodePasar, d.IDLapak, d.TglMulaiDibayar
		FROM trretribusipasar d
		WHERE d.TglMulaiDibayar='$Tanggal'
    	GROUP by d.KodePasar, d.IDLapak, d.IDPerson
	) d ON d.IDPerson = a.IDPerson
where a.KodePasar='$KodePasar' and b.BlokLapak like '%".$IDLapak."%' AND a.IsAktif=b'1' AND IFNULL(d.NominalDiterima, 0) <= 0
GROUP BY a.KodePasar,a.IDLapak";
// $sql = "SELECT a.KodePasar, a.IDPerson, a.IDLapak, b.BlokLapak, c.NamaPerson, IFNULL(b.Retribusi, 0) as Retribusi, b.NomorLapak, IFNULL(d.NominalDiterima, 0) NominalDiterima 
// FROM lapakperson a 
// JOIN lapakpasar  b on (a.KodePasar,a.IDLapak)=(b.KodePasar,b.IDLapak)
// JOIN mstperson c ON a.IDPerson = c.IDPerson
// LEFT JOIN (
// 		SELECT d.NominalDiterima, d.IDPerson, d.KodePasar, d.IDLapak
// 		FROM trretribusipasar d
// 		WHERE d.TglMulaiDibayar='$Tanggal'
// 	) d ON d.IDLapak = a.IDLapak AND d.KodePasar = a.KodePasar
// where a.KodePasar='$KodePasar' and b.BlokLapak like '%".$IDLapak."%' AND a.IsAktif=b'1' AND IFNULL(d.NominalDiterima, 0) <= 0
// GROUP BY IDLapak";
$res = $koneksi->query($sql);
while ($row = $res->fetch_assoc()) {
    array_push($report, $row);
}
echo json_encode($report);
?>
 