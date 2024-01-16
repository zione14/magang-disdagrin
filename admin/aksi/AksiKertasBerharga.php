<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanData'){
		$KodeKB     = mysqli_escape_string($koneksi, $_POST['KodeKB']);
		$NamaKB 	= mysqli_escape_string($koneksi, $_POST['NamaKB']);
		$NilaiKB 	= mysqli_escape_string($koneksi, $_POST['NilaiKB']);
		$Keterangan = mysqli_escape_string($koneksi, $_POST['Keterangan']);
		$IsAktif 	= $_POST['IsAktif'];
		$result = SimpanData($koneksi, $KodeKB, $NamaKB, $NilaiKB, $Keterangan, $IsAktif);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}

	if($_POST['action'] == 'HapusGroup'){
		$KodeGroup = $_POST['KodeGroup'];
		if(CekBarang($conn, $KodeGroup)){
			$result = HapusGroup($koneksi, $KodeGroup);
			if($result){
				echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
			}else{
				echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
			}
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menghapus data\nHapus barang terlebih dahulu.'));
		}		
	}
}

function SimpanData($conn,  $KodeKB, $NamaKB, $NilaiKB, $Keterangan, $IsAktif){
	if($KodeKB != ''){
		$sql = "UPDATE mstkertasberharga SET NamaKB = '$NamaKB', NilaiKB = '$NilaiKB', Keterangan = '$Keterangan', IsAktif = b'$IsAktif' WHERE KodeKB = '$KodeKB'";
	}else{
		$KodeKB = getKode($conn);
		$sql = "INSERT INTO mstkertasberharga (KodeKB, NamaKB, NilaiKB, Keterangan, IsAktif) VALUES ('$KodeKB', '$NamaKB', '$NilaiKB', '$Keterangan', b'$IsAktif')";
	}
	return $conn->query($sql);
}

function getKode($conn){
	date_default_timezone_set('Asia/Jakarta');
	$tahun = date('Y');
	$sql = "SELECT RIGHT(KodeKB,7) AS kode FROM mstkertasberharga WHERE KodeKB LIKE '%$tahun%' ORDER BY KodeKB DESC LIMIT 1";
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
	return 'KTS-' . $tahun . '-' . $bikin_kode ;
}
