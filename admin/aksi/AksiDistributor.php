<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanDistributor'){
		$IDPerson 	= $_POST['IDPerson'];
		$NamaPerson = $_POST['NamaPerson'];
		$Alamat		= $_POST['Alamat'];
		$NoHP 		= $_POST['NoHP'];
		// $Keterangan = $_POST['Keterangan'];
		$UserName 	= $_POST['UserName'];
		$IsAktif 	= $_POST['IsAktif'];

		$result = SimpanDistributor($koneksi, $IDPerson, $NamaPerson, $Alamat, $NoHP, $UserName, $IsAktif);
		echo json_encode($result);
		// if($result){
			// echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		// }else{
			// echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		// }
	}

	if($_POST['action'] == 'HapusBarang'){
		$IDPerson = $_POST['IDPerson'];
		$result = HapusBarang($koneksi, $IDPerson);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}
}

function SimpanDistributor($conn, $IDPerson, $NamaPerson, $Alamat, $NoHP, $UserName, $IsAktif){
	if($IDPerson != ''){
		$sql = "UPDATE mstperson SET NamaPerson = '$NamaPerson', AlamatLengkapPerson = '$Alamat', NoHP = '$NoHP', UserName = '$UserName', IsVerified = b'$IsAktif' WHERE IDPerson = '$IDPerson'";
		
	
		 $res = $conn->query($sql);
            if ($res) {  
                return array('response' => 200);
            } else {
                return array('response' => 500, 'msg' => 'Gagal menyimpan data.');
            }
		
	}else{
		$sql_cek = "SELECT * FROM mstperson WHERE UserName = '$UserName'";
		$res_cek = $conn->query($sql_cek);
		if($res_cek){
			$row_cek = mysqli_num_rows($res_cek);
			if($row_cek > 0){
				return array('response' => 501);
			}else{
				$IDPerson = AutoKodeBarang($conn);
				$sql = "INSERT INTO mstperson (IDPerson, NamaPerson, AlamatLengkapPerson, NoHP, UserName, IsVerified, JenisPerson, KlasifikasiUser, Password) VALUES ('$IDPerson', '$NamaPerson', '$Alamat', '$NoHP', '$UserName', b'$IsAktif', 'Distributor' ,'Distributor Pupuk','".base64_encode('123456')."')";
			}
			$res = $conn->query($sql);
            if ($res) {  
                return array('response' => 200);
            } else {
                return array('response' => 500, 'msg' => 'Gagal menyimpan data.');
            }
		}
	}
	
}

function AutoKodeBarang($conn){
	date_default_timezone_set('Asia/Jakarta');
	$tahun = date('Y');
	$sql = "SELECT RIGHT(IDPerson,7) AS kode FROM mstperson WHERE IDPerson LIKE '%$tahun%' ORDER BY IDPerson DESC LIMIT 1";
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
	return 'PRS-' . $tahun . '-' . $bikin_kode ;
}

function HapusBarang($conn, $IDPerson){
	$sql = "DELETE FROM mstperson WHERE IDPerson = '$IDPerson'";
	return $conn->query($sql);
}