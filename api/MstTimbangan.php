<?php

    require_once 'config/config.php';
    require_once 'models/MstTimbanganModel.php';

    $timbangan = new MstTimbanganModel();

    switch ($_SERVER['REQUEST_METHOD']) {
	  case 'GET':
		$page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
		$offset = isset($_GET['offset']) ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
		$search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';

		if (isset($_GET['kodetimbangan']) && $_GET['kodetimbangan'] != '') {
		    $kodetimbangan = mysqli_escape_string(getcon(), $_GET['kodetimbangan']);
		    // $data = $timbangan->get_onetimbangan($kodetimbangan);
		    $data = $timbangan->getkelas($kodetimbangan);
		} else if (isset($_GET['kodekelas']) && $_GET['kodekelas'] != '') {
		    $kodekelas = mysqli_escape_string(getcon(), $_GET['kodekelas']);
		    $data = $timbangan->getukuran($kodekelas);
		} else {
		    $data = $timbangan->gettimbangan($search, $page, $offset);
		}
		if ($data) {
		    setresponse(OK, array('status' => true, 'data' => $data));
		} else {
		    setresponse(NOT_FOUND, array('status' => false, 'message' => 'data tidak ditemukan'));
		}
		break;
    }
?>