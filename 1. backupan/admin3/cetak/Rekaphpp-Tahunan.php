<?php 
require_once ("../../assets/fpdf/fpdf.php");
include '../../library/config.php';


$Tanggal = isset($_GET['th']) ? mysqli_real_escape_string($koneksi,$_GET['th']) : date('Y').'-01-01';
$KodeGroup = isset($_GET['gr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['gr'])) : '';
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';

$sql_gr = "SELECT NamaGroup
FROM mstgroupbarang
WHERE KodeGroup = '$KodeGroup'";

$stmt1 = mysqli_query($koneksi, $sql_gr);
$rowdata = mysqli_fetch_assoc($stmt1);

if($display === "ketersediaan"){
$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Satuan, 
week1.RtKetersediaan AS RtKetersediaan1, week1.JmlData AS JmlData1,
week2.RtKetersediaan AS RtKetersediaan2, week2.JmlData AS JmlData2, 
week3.RtKetersediaan AS RtKetersediaan3, week3.JmlData AS JmlData3, 
week4.RtKetersediaan AS RtKetersediaan4, week4.JmlData AS JmlData4,
week5.RtKetersediaan AS RtKetersediaan5, week5.JmlData AS JmlData5, 
week6.RtKetersediaan AS RtKetersediaan6, week6.JmlData AS JmlData6,
week7.RtKetersediaan AS RtKetersediaan7, week7.JmlData AS JmlData7,
week8.RtKetersediaan AS RtKetersediaan8, week8.JmlData AS JmlData8,
week9.RtKetersediaan AS RtKetersediaan9, week9.JmlData AS JmlData9,
week10.RtKetersediaan AS RtKetersediaan10, week10.JmlData AS JmlData10,
week11.RtKetersediaan AS RtKetersediaan11, week11.JmlData AS JmlData11,
week12.RtKetersediaan AS RtKetersediaan12, week12.JmlData AS JmlData12
FROM mstbarangpokok b
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='01'  
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week1 ON week1.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='02'  
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week2 ON week2.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='03' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week3 ON week3.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='04'  
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week4 ON week4.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='05'  
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week5 ON week5.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='06' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week6 ON week6.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='07' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week7 ON week7.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='08' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week8 ON week8.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='09' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week9 ON week9.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='10' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week10 ON week10.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='11' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week11 ON week11.KodeBarang = b.KodeBarang
LEFT JOIN(
    SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
    FROM mstbarangpokok b
    LEFT JOIN (
    SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
    FROM stokpedagang r
    WHERE LEFT(r.Periode,4) = '$Tanggal' AND SUBSTRING(r.Periode,6,2)='12' 
    GROUP BY r.KodeBarang) AS rt ON rt.KodeBarang = b.KodeBarang
    GROUP BY b.KodeBarang) AS week12 ON week12.KodeBarang = b.KodeBarang

WHERE b.KodeGroup = '$KodeGroup' AND (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009')";

}else{
$sql = "SELECT b.KodeBarang, b.NamaBarang, b.Satuan, 
week1.RtHargabarang AS RtHargabarang1, week1.RtHargaProdusen AS RtHargaProdusen1, week1.RtKetersediaan AS RtKetersediaan1, week1.JmlData AS JmlData1, 
week2.RtHargabarang AS RtHargabarang2, week2.RtHargaProdusen AS RtHargaProdusen2, week2.RtKetersediaan AS RtKetersediaan2, week2.JmlData AS JmlData2, 
week3.RtHargabarang AS RtHargabarang3, week3.RtHargaProdusen AS RtHargaProdusen3, week3.RtKetersediaan AS RtKetersediaan3, week3.JmlData AS JmlData3, 
week4.RtHargabarang AS RtHargabarang4, week4.RtHargaProdusen AS RtHargaProdusen4, week4.RtKetersediaan AS RtKetersediaan4, week4.JmlData AS JmlData4,
week5.RtHargabarang AS RtHargabarang5, week5.RtHargaProdusen AS RtHargaProdusen5, week5.RtKetersediaan AS RtKetersediaan5, week5.JmlData AS JmlData5, 
week6.RtHargabarang AS RtHargabarang6, week6.RtHargaProdusen AS RtHargaProdusen6, week6.RtKetersediaan AS RtKetersediaan6, week6.JmlData AS JmlData6, 
week7.RtHargabarang AS RtHargabarang7, week7.RtHargaProdusen AS RtHargaProdusen7, week7.RtKetersediaan AS RtKetersediaan7, week7.JmlData AS JmlData7, 
week8.RtHargabarang AS RtHargabarang8, week8.RtHargaProdusen AS RtHargaProdusen8, week8.RtKetersediaan AS RtKetersediaan8, week8.JmlData AS JmlData8,
week9.RtHargabarang AS RtHargabarang9, week9.RtHargaProdusen AS RtHargaProdusen9, week9.RtKetersediaan AS RtKetersediaan9, week9.JmlData AS JmlData9, 
week10.RtHargabarang AS RtHargabarang10, week10.RtHargaProdusen AS RtHargaProdusen10, week10.RtKetersediaan AS RtKetersediaan10, week10.JmlData AS JmlData10, 
week11.RtHargabarang AS RtHargabarang11, week11.RtHargaProdusen AS RtHargaProdusen11, week11.RtKetersediaan AS RtKetersediaan11, week11.JmlData AS JmlData11, 
week12.RtHargabarang AS RtHargabarang12, week12.RtHargaProdusen AS RtHargaProdusen12, week12.RtKetersediaan AS RtKetersediaan12, week12.JmlData AS JmlData12
FROM mstbarangpokok b
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 1 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week1 ON week1.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 2 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week2 ON week2.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 3 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week3 ON week3.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 4 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week4 ON week4.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 5 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week5 ON week5.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 6 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week6 ON week6.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 7 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week7 ON week7.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 8 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week8 ON week8.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 9 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week9 ON week9.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 10 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week10 ON week10.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 11 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."') AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week11 ON week11.KodeBarang = b.KodeBarang
LEFT JOIN(
	SELECT b.KodeBarang, b.Satuan, IFNULL(rt.RtHargabarang, 0) AS RtHargabarang, IFNULL(rt.RtHargaProdusen, 0) AS RtHargaProdusen, IFNULL(rt.RtKetersediaan, 0) AS RtKetersediaan, COUNT(rt.KodeBarang) AS JmlData
	FROM mstbarangpokok b
	LEFT JOIN (
	SELECT r.KodeBarang, FLOOR(AVG(r.HargaBarang)) AS RtHargabarang, FLOOR(AVG(r.HargaProdusen)) AS RtHargaProdusen, FLOOR(AVG(r.Ketersediaan)) AS RtKetersediaan
	FROM reporthargaharian r
	WHERE MONTH(r.Tanggal) = 12 AND YEAR(r.Tanggal) = YEAR('".$Tanggal."')  AND r.HargaBarang > 0 
	GROUP BY r.Tanggal) AS rt ON rt.KodeBarang = b.KodeBarang
	GROUP BY b.KodeBarang) AS week12 ON week12.KodeBarang = b.KodeBarang
WHERE b.KodeGroup = '".$KodeGroup."'";
}

$rekap = array();
$stmt = $koneksi->prepare($sql);
if($stmt->execute()){
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
		if($row != null){
			array_push($rekap, $row);
		}
	}
	$stmt->free_result();
	$stmt->close();
}


$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$DSPLY = "";
if($display === "ketersediaan"){
    $DSPLY = "JUMLAH KETERSEDIAAN";
}elseif($display === "hprodusen"){
    $DSPLY = "HARGA PRODUSEN";
}else{
    $DSPLY = "HARGA KONSUMEN";
}
$judul = "DATA REKAP ".$DSPLY." BAHAN POKOK ".$rowdata['NamaGroup']." TAHUN ".date('Y', strtotime($Tanggal));
$subjudul = "DINAS PERDAGANGAN DAN PERINDUSTRIAN KABUPATEN JOMBANG";
$header = array(
    array("label"=>"Nama Bahan Pokok", "length"=>78, "align"=>"L"),
    array("label"=>"Jan", "length"=>21, "align"=>"R"),
    array("label"=>"Feb", "length"=>21, "align"=>"R"),
    array("label"=>"Mar", "length"=>21, "align"=>"R"),
    array("label"=>"Apr", "length"=>21, "align"=>"R"),
    array("label"=>"Mei", "length"=>21, "align"=>"R"),
    array("label"=>"Jun", "length"=>21, "align"=>"R"),
    array("label"=>"Jul", "length"=>21, "align"=>"R"),
    array("label"=>"Agust", "length"=>21, "align"=>"R"),
    array("label"=>"Sept", "length"=>21, "align"=>"R"),
    array("label"=>"Okt", "length"=>21, "align"=>"R"),
    array("label"=>"Nov", "length"=>21, "align"=>"R"),
    array("label"=>"Des", "length"=>21, "align"=>"R")
);

// $pdf = new FPDF( 'L', 'mm', 'F4' );
$pdf = new FPDF( 'L', 'mm', array(215,350) );
$pdf->AddPage();

#tampilkan judul laporan
$pdf->SetFont('Arial','B','16');
$pdf->Cell(0, 5, $judul, '0', 1, 'C');

#tampilkan sub-judul laporan
$pdf->SetFont('Arial','','10');
$pdf->Cell(0, 8, $subjudul, '0', 1, 'C');

#buat header tabel
$pdf->SetFont('Arial','','10');
$pdf->SetFillColor(39, 55, 64);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(0,0,0);
$pdf->Ln();
foreach ($header as $kolom) {
	$pdf->Cell($kolom['length'], 8, $kolom['label'], 1, '0', $kolom['align'], true);
}
$pdf->Ln();

#tampilkan data tabelnya
$pdf->SetFillColor(255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
$fill=false;

foreach($rekap as $rkp){
	  $pdf->Cell($header[0]['length'], 5, $rkp['NamaBarang'], 1, '0', $header[0]['align'], $fill);
	  
	  for ($i=1; $i <= 12; $i++){
		  
			if($display === "ketersediaan"){
				$pdf->Cell($header[1]['length'], 5, $rkp['RtKetersediaan'.$i], 1, '0', $header[1]['align'], $fill);
			}elseif($display === "hprodusen"){
				$pdf->Cell($header[1]['length'], 5, 'Rp.'.number_format($rkp['RtHargaProdusen'.$i]), 1, '0', $header[1]['align'], $fill);
			}else{
				$pdf->Cell($header[1]['length'], 5, 'Rp.'.number_format($rkp['RtHargabarang'.$i]), 1, '0', $header[1]['align'], $fill);
			}
		  
	  }
	
	 $pdf->Ln();
}

$pdf->Output();