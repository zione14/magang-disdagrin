<?php

    require_once 'config/config.php';
    require_once 'models/UserLoginModel.php';

    $login = new UserLoginModel();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (isset($_GET['username']) && isset($_GET['password'])) {
                $username = mysqli_escape_string(getcon(), $_GET['username']);
                $password = base64_encode(mysqli_escape_string(getcon(), $_GET['password']));
                if (isset($_GET['pengguna'])) {
                    $data = $login->get_login_pengguna($username, $password);
                    if ($data) {
                        setresponse(OK, array('status' => true, 'data' => $data));
                    } else {
                        setresponse(NOT_FOUND, array('status' => false, 'message' => 'data pengguna tidak ditemukan'));
                    }
                } else {
					$jenislogin = isset($_GET['jenis']) ? $_GET['jenis'] : '';
                    $data = $login->get_login_petugas($username, $password, $jenislogin);
                    if ($data) {
                        setresponse(OK, array('status' => true, 'data' => $data));
                    } else {
                        setresponse(NOT_FOUND, array('status' => false, 'message' => 'data pegawai tidak ditemukan'));
                    }
                }
            } else {
                setresponse(BAD_REQUEST, array('status' => false, 'message' => 'request tidak dapat diproses, username atau password tidak boleh kosong'));
            }
            break;
        case 'POST':
            if(isset($_POST['aksi']) && $_POST['aksi'] === "ubah_pass"){
                $username = $_POST['username'];
                $password = $_POST['password'];
                $password_lama = $_POST['passlama'];
                $ubah_pass = $login->ubah_pass($username, $password, $password_lama);
                if($ubah_pass){
                    setresponse(OK, array(
                        'status'=>true, 'message'=>'password berhasil diubah'
                    ));
                }else{
                    setresponse(NOT_MODIFIED, array(
                        'status'=>false, 'message'=>'password gagal diubah'
                    ));
                }
            }
            break;
    }
?>