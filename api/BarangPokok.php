<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");
    
    require_once 'config/config.php';
    require_once 'models/MstBarang.php';

    $mstBrg = new MstBarang();
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['aksi']) && $_GET['aksi'] === "autocomplete") {
                if (isset($_GET['cari'])) {
                    $mstBrg->setCari($_GET['cari']);
                }
                $auto_complete = $mstBrg->getautocompletebrg();
                if ($auto_complete) {
                    setresponse(OK, array('status' => true, 'data' => $auto_complete));
                } else {
                    setresponse(NOT_FOUND, array('status' => false, 'message' => 'data autocomplete tidak ditemukan'));
                }
            }
            break;
        case 'POST':
            break;
    }
?>