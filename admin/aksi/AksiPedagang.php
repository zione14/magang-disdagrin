<?php
include "../../library/config.php";

if(isset($_POST)){
	if($_POST['action'] == 'SimpanPedagang'){
		$JumlahPedagang = $_POST['JumlahPedagang'];
		$KodeBarang = $_POST['KodeBarang'];
		$KodePasar = $_POST['KodePasar'];
		$Edit = $_POST['Edit'];
		

		$result = SimpanPedagang($koneksi, $KodeBarang, $JumlahPedagang, $KodePasar, $Edit);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}

	if($_POST['action'] == 'HapusPedagang'){
		$KodeBarang = $_POST['KodeBarang'];
		$KodePasar = $_POST['KodePasar'];
		$result = HapusPedagang($koneksi, $KodeBarang, $KodePasar);
		if($result){
			echo json_encode(array('response' => 200, 'msg' => 'Berhasil menyimpan data.'));
		}else{
			echo json_encode(array('response' => 500, 'msg' => 'Gagal menyimpan data.'));
		}
	}
}

function SimpanPedagang($conn, $KodeBarang, $JumlahPedagang, $KodePasar, $Edit){
	if($Edit != ''){
		$sql = "UPDATE masterpedagang SET JumlahPedagang = '$JumlahPedagang' WHERE KodeBarang = '$KodeBarang' AND KodePasar = '$KodePasar' ";
	}else{
		$sql = "INSERT INTO masterpedagang (KodeBarang, JumlahPedagang, KodePasar) VALUES ('$KodeBarang', '$JumlahPedagang', '$KodePasar')";
	}
	return $conn->query($sql);
}



function HapusPedagang($conn, $KodeBarang, $KodePasar){
	$sql = "DELETE FROM masterpedagang WHERE KodeBarang = '$KodeBarang' AND KodePasar='$KodePasar'";
	return $conn->query($sql);
}