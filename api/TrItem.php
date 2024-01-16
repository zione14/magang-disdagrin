<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");
    require_once 'config/config.php';
    require_once 'models/TrItemModel.php';

    $tritem = new TrItemModel();

    switch ($_SERVER['REQUEST_METHOD']) {
	  case 'GET':
		$page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
		$offset = isset($_GET['offset']) && $_GET['offset'] > 0 ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
		$search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';

		$notransaksi = mysqli_escape_string(getcon(), $_GET['notransaksi']);
		$idtimbangan = mysqli_escape_string(getcon(), $_GET['idtimbangan']);
		if (isset($_GET['notransaksi']) && $_GET['notransaksi'] != '' && isset($_GET['nouruttrans']) && $_GET['nouruttrans'] != '') {
		    $nouruttrans = $_GET['nouruttrans'];
		    $data = $tritem->get_one_item($notransaksi, $nouruttrans);
		} else {
		    $data = $tritem->gettritem($notransaksi, $idtimbangan, $search, $page, $offset);
		}
		if ($data) {
		    setresponse(OK, array('status' => true, 'data' => $data));
		} else {
		    setresponse(NOT_FOUND, array('status' => false, 'message' => 'data transaksi tidak ditemukan'));
		}
		break;
    }
?>