<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");

    require_once 'config/config.php';
    require_once 'models/MstPasar.php';

    $pasar = new MstPasar();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $username = htmlspecialchars($_GET['username']);
            $page = isset($_GET['page']) ? $_GET['page'] : 0;
            $pasarsaya = $pasar->getpasarsaya($username);
            if ($pasarsaya) {
                setresponse(OK, array('status' => true, 'data' => $pasarsaya));
            } else {
                setresponse(NOT_FOUND, array('status' => false, 'message' => 'data pasar tidak ditemukan'));
            }
            break;
        case 'POST':
            break;
    }
?>

