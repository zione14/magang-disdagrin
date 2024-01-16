<?php
include "../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'Kecamatan'){
		$KodeKec = $_POST['KodeKec'];
		$KodeKab = $_POST['KodeKab'];
		$result = GetDataKecamatan($koneksi, $KodeKec, $KodeKab);
		echo $result;
	}
	if($_POST['action'] == 'Desa'){
		$KodeDesa = $_POST['KodeDesa'];
		$KodeKec = $_POST['KodeKec'];
		$result = GetDataDesa($koneksi, $KodeDesa, $KodeKec);
		echo $result;
	}
}
function GetDataKecamatan($conn, $KodeKec, $KodeKab){

	$sql = "SELECT * FROM mstkec WHERE IF(length('$KodeKab') > 0, KodeKab = '$KodeKab', TRUE) ORDER BY NamaKecamatan ASC";
	$res = $conn->query($sql);
	$Data = '<option value="" selected disabled>Pilih Kecamatan</option>';
	if($res){
		if(mysqli_num_rows($res) > 0){
			while ($row = $res->fetch_assoc()) {
				if($KodeKec==$row['KodeKec']){
					$Data .= '<option value="'.$row['KodeKec'].'" selected>'.$row['NamaKecamatan'].'</option>';
				}else{
					$Data .= '<option value="'.$row['KodeKec'].'">'.$row['NamaKecamatan'].'</option>';
				}
			}
		}
	}
	return $Data;
}

function GetDataDesa($conn, $KodeDesa, $KodeKec){

	$sql = "SELECT * FROM mstdesa WHERE KodeKec = '$KodeKec' ORDER BY NamaDesa ASC";
	$res = $conn->query($sql);
	$Data = '<option value="" selected disabled>Pilih Desa</option>';
	if($res){
		if(mysqli_num_rows($res) > 0){
			while ($row = $res->fetch_assoc()) {
				if($KodeDesa==$row['KodeDesa']){
					$Data .= '<option value="'.$row['KodeDesa'].'" selected>'.$row['NamaDesa'].'</option>';
				}else{
					$Data .= '<option value="'.$row['KodeDesa'].'">'.$row['NamaDesa'].'</option>';
				}
			}
		}
	}
	return $Data;
}
?>