<?php 

// koneksi database
$koneksi = mysqli_connect("localhost","root","","disnag_jo");
 
// Check koneksi database
if (mysqli_connect_errno()){
	echo "Koneksi Database Gagal : " . mysqli_connect_error();
}



function InsertLog($koneksi, $Action, $Description, $UserName, $NoTransaksi, $JenisTransaksi){
	$LogID = AutoKodeLog($koneksi);
	$sql = "INSERT INTO serverlog (LogID, Action, DateTimeLog, 	Description, UserName, 	NoTransaksi, JenisTransaksi) VALUES ('$LogID', '$Action', NOW(), '$Description', '$UserName', '$NoTransaksi', '$JenisTransaksi')";
	$res = $koneksi->query($sql);	
}

function AutoKodeLog($koneksi){
	$sql = "SELECT RIGHT(LogID,37) AS kode FROM serverlog ORDER BY LogID DESC LIMIT 1";
	$res = mysqli_query($koneksi, $sql);
	$result = mysqli_fetch_array($res);
	if ($result['kode'] == null) {
		$kode = 1;
	} else {
		$kode = ++$result['kode'];
	}
	$bikin_kode = str_pad($kode, 37, "0", STR_PAD_LEFT);
	return 'LOG-' . $bikin_kode;
}

function KodeKab($koneksi){
	$query = "SELECT value FROM sistemsetting where KodeSetting='SET-0001'";
	$conn = mysqli_query($koneksi, $query);
	$result = mysqli_fetch_array($conn);
	$KodeKab = $result['value'];
	
	return $KodeKab;
}

?>