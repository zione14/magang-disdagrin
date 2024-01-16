<?php
date_default_timezone_set('Asia/Jakarta');
$tgl = date("YmdHis");
require_once 'config/config.php';
require_once 'models/TrActionModel.php';

$tr = new TrActionModel();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
		$offset = isset($_GET['offset']) && $_GET['offset'] > 0 ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
		$search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';
		if (isset($_GET['idperson']) && $_GET['idperson'] != '') {
			$idperson = mysqli_escape_string(getcon(), $_GET['idperson']);
			$kodelokasi = mysqli_escape_string(getcon(), $_GET['kodelokasi']);
			$data = $tr->gettraction($idperson, $kodelokasi, $search, $page, $offset);
			if ($data) {
				setresponse(OK, array('status' => true, 'data' => $data));
			} else {
				setresponse(NOT_FOUND, array('status' => false, 'message' => 'data transaksi tidak ditemukan'));
			}
		} else if (isset($_GET['notransaksi']) && $_GET['notransaksi'] != '') {
			$data = $tr->get_one_tr($notransaksi);
			if ($data) {
				setresponse(OK, array('status' => true, 'data' => $data));
			} else {
				setresponse(NOT_FOUND, array('status' => false, 'message' => 'data transaksi tidak ditemukan'));
			}
		} else {
			setresponse(BAD_REQUEST, array('status' => false, 'message' => 'id person atau notransaksi tidak boleh kosong'));
		}
		break;
}
?>