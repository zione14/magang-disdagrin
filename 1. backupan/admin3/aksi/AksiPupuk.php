<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanBarang'){
		$KodeBarang = $_POST['KodeBarang'];
		$NamaBarang = $_POST['NamaBarang'];
		$Merk = $_POST['Merk'];
		$Keterangan = $_POST['Keterangan'];
		// $IsAktif = $_POST['IsAktif'];

		$result = SimpanBarang($koneksi, $KodeBarang, $NamaBarang, $Merk, $Keterangan);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}

	if($_POST['action'] == 'HapusBarang'){
		$KodeBarang = $_POST['KodeBarang'];
		$result = HapusBarang($koneksi, $KodeBarang);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}
}

function SimpanBarang($conn, $KodeBarang, $NamaBarang, $Merk, $Keterangan){
	if($KodeBarang != ''){
		$sql = "UPDATE mstpupuksubsidi SET NamaBarang = '$NamaBarang', Harga = '$Merk', Keterangan = '$Keterangan', IsAktif = b'1' WHERE KodeBarang = '$KodeBarang'";
	}else{
		$KodeBarang = AutoKodeBarang($conn);
		$sql = "INSERT INTO mstpupuksubsidi (KodeBarang, NamaBarang, Harga, Keterangan, IsAktif) VALUES ('$KodeBarang', '$NamaBarang', '$Merk', '$Keterangan', b'1')";
	}
	return $conn->query($sql);
}

function AutoKodeBarang($conn){
	date_default_timezone_set('Asia/Jakarta');
	$tahun = date('Y');
	$sql = "SELECT RIGHT(KodeBarang,7) AS kode FROM mstpupuksubsidi WHERE KodeBarang LIKE '%$tahun%' ORDER BY KodeBarang DESC LIMIT 1";
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
	return 'PPK-' . $tahun . '-' . $bikin_kode ;
}

function HapusBarang($conn, $KodeBarang){
	$sql = "Update mstpupuksubsidi set IsAktif=b'0' WHERE KodeBarang = '$KodeBarang'";
	return $conn->query($sql);
}