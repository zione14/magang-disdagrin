<?php

date_default_timezone_set('Asia/Jakarta');
$tgl = date("YmdHis");
require_once 'config/config.php';
require_once 'models/ImageFunction.php';
require_once 'models/MstPasar.php';
require_once 'models/LaporanHarianhpp.php';

$pasar = new MstPasar();
$imageFunc = new ImageFunction();
$laphpp = new LaporanHarianhpp();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $username = htmlspecialchars($_GET['username']);
        $laphpp->setUserName($username);
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $limit = 20;
        if (isset($_GET['aksi']) && $_GET['aksi'] === "histori") {
            if (isset($_GET['kodebarang'])) {
                $pasarsaya = $pasar->getpasarsaya($username);
                if ($pasarsaya) {
                    $kodebarang = $_GET['kodebarang'];
                    $kodepasar = $pasarsaya['KodePasar'];
                    $laphpp->setKodeBarang($kodebarang);
                    $laphpp->setKodePasar($kodepasar);
                    $datahistori = $laphpp->gethistorireport($page);
                    if ($datahistori) {
                        setresponse(OK, array('status' => true, 'data' => $datahistori));
                    } else {
                        setresponse(NOT_FOUND, array('status' => false, 'message' => 'data histori tidak ditemukan'));
                    }
                } else {
                    setresponse(NOT_FOUND, array('status' => false, 'message' => 'data histori tidak ditemukan'));
                }
            } else {
                $datahistori = $laphpp->gethistorireport($page);
                if ($datahistori) {
                    setresponse(OK, array('status' => true, 'data' => $datahistori));
                } else {
                    setresponse(NOT_FOUND, array('status' => false, 'message' => 'data histori tidak ditemukan'));
                }
            }
        } else if (isset($_GET['aksi']) && $_GET['aksi'] === "updatelap") {
            $kodepasar = $_GET['kodepasar'];
            $laphpp->setKodePasar($kodepasar);
            $brg_not_update = $laphpp->cek_yg_belum_update();
            if ($brg_not_update) {
                setresponse(OK, array('status' => true, 'data' => 'perbarui berhasil'));
            } else {
                setresponse(NOT_FOUND, array('status' => false, 'data' => 'perbarui gagal'));
            }
        } else {
            $pasarsaya = $pasar->getpasarsaya($username);
            if ($pasarsaya) {
                $dataResponse = array('detailpasar' => $pasarsaya);
                $laphpp->setKodePasar($pasarsaya['KodePasar']);
                if (isset($_GET['namabarang'])) {
                    $laphpp->setNamaBarang($_GET['namabarang']);
                }
                if (isset($_GET['tanggal'])) {
                    $laphpp->setTanggal($_GET['tanggal']);
                }
                $datalaphpp = $laphpp->getlaporan($page);
                if ($datalaphpp) {
                    $dataResponse['laporanhpp'] = $datalaphpp;
                }
                $cek_jml_report = $laphpp->cek_jml_report();
                if ($cek_jml_report) {
                    $dataResponse['detailpasar']['jmlbrg'] = $cek_jml_report['jmlbrg'];
                    $dataResponse['detailpasar']['jmlreport'] = $cek_jml_report['jmlreport'];
                }
                setresponse(OK, array('status' => true, 'data' => $dataResponse));
            } else {
                setresponse(NOT_FOUND, array('status' => false, 'message' => 'data pasar tidak ditemukan'));
            }
        }
        break;
    case 'POST':
        $username = htmlspecialchars($_POST['username']);
        $kodebarang = htmlspecialchars($_POST['kodebarang']);
        $kodepasar = htmlspecialchars($_POST['kodepasar']);
        $hargabarang = htmlspecialchars($_POST['hargabarang']);
        $ketersediaan = htmlspecialchars($_POST['ketersediaan']);
        $hargaprodusen = htmlspecialchars($_POST['hargaprodusen']);
        $keterangan = htmlspecialchars($_POST['keterangan']);

        $laphpp->setUserName($username);
        $laphpp->setKodeBarang($kodebarang);
        $laphpp->setKodePasar($kodepasar);
        $laphpp->setHargaBarang($hargabarang);
        $laphpp->setKeterangan($keterangan);
        $laphpp->setKetersediaan($ketersediaan);
        $laphpp->setHargaProdusen($hargaprodusen);
        $laphpp->setTanggal(date('Y-m-d H:i:s'));

        $cek_sudah_update = $laphpp->cek_sudah_insert();
        if ($cek_sudah_update) {
            $laphpp->setTanggal($cek_sudah_update);
            $updated = $laphpp->update();
            if ($updated) {
                setresponse(ACCEPTED, array(
                    'status' => true, 'message' => 'data laporan hpp berhasil disimpan'
                ));
            } else {
                setresponse(NOT_MODIFIED, array(
                    'status' => false, 'message' => 'data laporan hpp gagal diperbarui'
                ));
            }
        } else {
            $inserted = $laphpp->insert();
            if ($inserted) {
                setresponse(ACCEPTED, array(
                    'status' => true, 'message' => 'data laporan hpp berhasil disimpan'
                ));
            } else {
                setresponse(NOT_ACCEPTABLE, array(
                    'status' => false, 'message' => 'data laporan hpp gagal diperbarui'
                ));
            }
            //            }
            break;
        }
}
