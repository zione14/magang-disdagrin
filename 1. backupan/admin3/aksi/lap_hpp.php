<?php
include "../../library/config.php";


$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';

$sql = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
    r.HargaBarang, ifnull(r.Ketersediaan, 0) as Ketersediaan, ifnull(r.HargaProdusen, 0) as HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
    FROM reporthargaharian r
    INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
    INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
    LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
    LEFT JOIN (
            SELECT *
            FROM reporthargaharian k
            ORDER BY k.Tanggal DESC
    ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND 
    DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
    WHERE r.KodePasar = ? AND DATE(r.Tanggal) <= ? AND r.KodeBarang = ?
    GROUP BY hppkemarin.Tanggal
    ORDER BY r.Tanggal DESC
    LIMIT 1";
$reportharian = array();
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('sss', $KodePasar, $Tanggal, $KodeBarang);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row != null) {
            $reportharian = $row;
        }
    }
    $stmt->free_result();
    $stmt->close();
}

echo json_encode($reportharian);
?>