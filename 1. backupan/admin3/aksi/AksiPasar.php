<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanPasar'){
		$KodePasar = $_POST['KodePasar'];
		$NamaPasar = $_POST['NamaPasar'];
		$NamaKepalaPasar = $_POST['NamaKepalaPasar'];
		$NoTelpPasar = $_POST['NoTelpPasar'];
		$KodeKab = $_POST['KodeKab'];
		$KodeKec = $_POST['KodeKec'];
		$KodeDesa = $_POST['KodeDesa'];
		$KoorLong = $_POST['KoorLong'];
		$KoorLat = $_POST['KoorLat'];

		$result = SimpanPasar($koneksi, $KodePasar, $NamaPasar, $NamaKepalaPasar, $NoTelpPasar, $KodeKab, $KodeKec, $KodeDesa, $KoorLong, $KoorLat);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}

	if($_POST['action'] == 'HapusPasar'){
		$KodePasar = $_POST['KodePasar'];
		$result = HapusPasar($koneksi, $KodePasar);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menghapus data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menghapus data.'));
		}
	}
}

function SimpanPasar($conn, $KodePasar, $NamaPasar, $NamaKepalaPasar, $NoTelpPasar, $KodeKab, $KodeKec, $KodeDesa, $KoorLong, $KoorLat){
	if($KodePasar != ''){
		$sql = "UPDATE mstpasar SET NamaPasar = '$NamaPasar', NamaKepalaPasar = '$NamaKepalaPasar', NoTelpPasar = '$NoTelpPasar', KodeDesa = '$KodeDesa', KodeKec = '$KodeKec', KodeKab = '$KodeKab', KoorLong = IF(length('$KoorLong') > 0, '$KoorLong', 0), KoorLat = IF(length('$KoorLat') > 0, '$KoorLat', 0) WHERE KodePasar = '$KodePasar'";
	}else{
		$KodePasar = AutoKodePasar($conn);
		$sql = "INSERT INTO mstpasar (KodePasar, NamaPasar, NamaKepalaPasar, NoTelpPasar, KodeDesa, KodeKec, KodeKab, KoorLong, KoorLat) VALUES ('$KodePasar', '$NamaPasar', '$NamaKepalaPasar', '$NoTelpPasar', '$KodeDesa', '$KodeKec', '$KodeKab', '$KoorLong', '$KoorLat')";
	}
	return $conn->query($sql);
}

function AutoKodePasar($conn){
	date_default_timezone_set('Asia/Jakarta');
	$tahun = date('Y');
	$sql = "SELECT RIGHT(KodePasar,7) AS kode FROM mstpasar WHERE KodePasar LIKE '%$tahun%' ORDER BY KodePasar DESC LIMIT 1";
	$res = mysqli_query($conn, $sql);
	if(mysqli_num_rows($res) > 0){
		$result = mysqli_fetch_array($res);
		if ($result['kode'] == null) {
			$kode = 1;
		} else {
			$kode = ++$result['kode'];
		}	
	}else{
		$kode = 1;
	}
	
	$bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
	return 'PSR-' . $tahun . '-' . $bikin_kode ;
}

function HapusPasar($conn, $KodePasar){
	$sql = "DELETE FROM mstpasar WHERE KodePasar = '$KodePasar'";
	return $conn->query($sql);
}