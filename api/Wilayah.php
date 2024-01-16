<?php
require_once 'config/config.php';
require_once 'models/WilayahModel.php';

$wilayah = new WilayahModel();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		if (isset($_GET['kodekab'])) {
			$kodekab = mysqli_escape_string(getcon(), $_GET['kodekab']);
			$data = $wilayah->getkecamatan($kodekab);
			if ($data) {
				setresponse(OK, array('status' => true, 'data' => $data));
			} else {
				setresponse(NOT_FOUND, array('status' => false, 'message' => 'data kecamatan kosong'));
			}
		} else if ($_GET['kodekec']) {
			$kodekec = mysqli_escape_string(getcon(), $_GET['kodekec']);
			$data = $wilayah->getdesa($kodekec);
			if ($data) {
				setresponse(OK, array('status' => true, 'data' => $data));
			} else {
				setresponse(NOT_FOUND, array('status' => false, 'message' => 'data desa kosong'));
			}
		} else if ($_GET['kodedesa']) {
			$kodedesa = mysqli_escape_string(getcon(), $_GET['kodedesa']);
			$data = $wilayah->getdusun($kodedesa);
			if ($data) {
				setresponse(OK, array('status' => true, 'data' => $data));
			} else {
				setresponse(NOT_FOUND, array('status' => false, 'message' => 'data dusun kosong'));
			}
		}
		break;
	case 'POST':
		break;
}
?>