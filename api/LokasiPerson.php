<?php
date_default_timezone_set('Asia/Jakarta');
$tgl = date("YmdHis");
require_once 'config/config.php';
require_once 'models/LokasiModel.php';

$lokasi = new LokasiModel();

switch ($_SERVER['REQUEST_METHOD']) {
case 'GET':
    $page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
    $offset = isset($_GET['offset']) && $_GET['offset'] > 0 ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
    $search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';
    if (isset($_GET['idperson']) && $_GET['idperson'] != '') {
        $idperson = mysqli_escape_string(getcon(), $_GET['idperson']);
        if (isset($_GET['kodelokasi']) && $_GET['kodelokasi'] != '') {
            $kodelokasi = mysqli_escape_string(getcon(), $_GET['kodelokasi']);
            $data = $lokasi->get_one_lokasi($kodelokasi, $idperson);
        } else {
            $data = $lokasi->getlokasiperson($idperson, $search, $page, $offset);
        }
        if ($data) {
            setresponse(OK, array('status' => true, 'data' => $data));
        } else {
            setresponse(NOT_FOUND, array('status' => false, 'message' => 'data lokasi person tidak ditemukan'));
        }
    } else {
        setresponse(BAD_REQUEST, array('status' => false, 'message' => 'id person tidak boleh kosong'));
    }
    break;
case 'POST':
    if (isset($_POST['IDPerson']) && $_POST['IDPerson'] != '') {
        $idperson = mysqli_escape_string(getcon(), $_POST['IDPerson']);
        if (isset($_POST['KodeLokasi']) && $_POST['KodeLokasi'] != '') {
            $kodelokasi = mysqli_escape_string(getcon(), $_POST['KodeLokasi']);
            $update = $lokasi->update_lokasi($kodelokasi, $idperson);
            if ($update) {
                $data = $lokasi->get_one_lokasi($kodelokasi, $idperson);
                setresponse(CREATED, array('status' => true, 'data' => $data, 'message' => 'data berhasil disimpan'));
            } else {
                setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
            }
        } else {
            $kodelokasi = $lokasi->generate_kodelokasi($idperson);
            $insert = $lokasi->insert_lokasi($kodelokasi, $idperson);
            if ($insert) {
                $data = $lokasi->get_one_lokasi($kodelokasi, $idperson);
                setresponse(CREATED, array('status' => true, 'data' => $data, 'message' => 'data berhasil disimpan'));
            } else {
                setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
            }
        }
    } else {
        setresponse(BAD_REQUEST, array('status' => false, 'message' => 'id person tidak boleh kosong'));
    }
    break;
}
?>