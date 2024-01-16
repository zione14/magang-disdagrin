<?php
date_default_timezone_set('Asia/Jakarta');
$tgl = date("YmdHis");
require_once 'config/config.php';
require_once 'models/MstPersonModel.php';

$person = new MstPersonModel();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		$data = $person->getmapperson();
		if ($data) {
			setresponse(OK, array('status' => true, 'data' => $data));
		} else {
			setresponse(NOT_FOUND, array('status' => false, 'message' => 'data person tidak ditemukan'));
		}
		break;
}
?>