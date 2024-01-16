<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");

    require_once 'config/config.php';
    require_once 'models/LapakPersonModel.php';

    $lapak = new LapakPersonModel();

    switch ($_SERVER['REQUEST_METHOD']) {
	  case 'GET':
		$datalapak = false;
		$page = isset($_GET['page']) ? $_GET['page'] : 0;
		if (isset($_GET['kodepasar'])) {
		    $lapak->setKodePasar($_GET['kodepasar']);
		    if (isset($_GET['idlapak'])) {
			  $lapak->setIDLapak($_GET['idlapak']);
			  $data = $lapak->getdetaillapakpasar();
		    } elseif(isset($_GET['statuspasar'])) {
			  $statuspasar = $_GET['statuspasar'];
			  $data = $lapak->getlapakpasar($statuspasar, $page);
		    }else{
			  $data = $lapak->getlapakpasar($page);
		    }
		    if ($data) {
			  setresponse(OK, array('status' => true, 'data' => $data));
		    } else {
			  setresponse(NOT_FOUND, array(
				'status' => false, 'message' => 'data lapak tidak ditemukan'
			  ));
		    }
		} else {
		    setresponse(NOT_FOUND, array(
			  'status' => false, 'message' => 'kode pasar tidak ditemukan'
		    ));
		}
		break;
	  case 'POST':
		break;
    }
?>