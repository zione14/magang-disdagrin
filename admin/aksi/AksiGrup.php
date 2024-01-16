<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanGroup'){
		$KodeGroup = $_POST['KodeGroup'];
		$NamaGroup = $_POST['NamaGroup'];
		$Keterangan = $_POST['Keterangan'];
		$IsAktif = $_POST['IsAktif'];

		$result = SimpanGroup($koneksi, $KodeGroup, $NamaGroup, $Keterangan, $IsAktif);
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

function CekBarang($conn, $KodeGroup){
	$sql = "SELECT COUNT(KodeBarang) AS Jumlah FROM mstbarangpokok WHERE KodeGroup = '$KodeGroup'";
	$res = $conn->query($sql);
	if(mysqli_num_rows($res) > 0){
		$row = mysqli_fetch_assoc($res);
		return $row['Jumlah'] > 0 ? false : true;
	}else{
		return true;
	}
}

function SimpanGroup($conn, $KodeGroup, $NamaGroup, $Keterangan, $IsAktif){
	if($KodeGroup != ''){
		$sql = "UPDATE mstgroupbarang SET NamaGroup = '$NamaGroup', Keterangan = '$Keterangan', IsAktif = b'$IsAktif' WHERE KodeGroup = '$KodeGroup'";
	}else{
		$KodeGroup = AutoKodeGroup($conn);
		$sql = "INSERT INTO mstgroupbarang (KodeGroup, NamaGroup, Keterangan, IsAktif) VALUES ('$KodeGroup', '$NamaGroup', '$Keterangan', b'$IsAktif')";
	}
	return $conn->query($sql);
}

function AutoKodeGroup($conn){
	date_default_timezone_set('Asia/Jakarta');
	$tahun = date('Y');
	$sql = "SELECT RIGHT(KodeGroup,7) AS kode FROM mstgroupbarang WHERE KodeGroup LIKE '%$tahun%' ORDER BY KodeGroup DESC LIMIT 1";
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
	return 'GROUP-' . $tahun . '-' . $bikin_kode ;
}
