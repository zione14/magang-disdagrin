<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");

    require_once 'config/config.php';
    require_once 'models/TrRetribusiModel.php';

    $retri = new TrRetribusiModel();

    switch ($_SERVER['REQUEST_METHOD']) {
	  case 'GET':
		$page = isset($_GET['page']) ? $_GET['page'] : 0;
		if (isset($_GET['kodepasar'])) {
		    $retri->setKodePasar($_GET['kodepasar']);
		}
		if (isset($_GET['idlapak'])) {
		    $retri->setIDLapak($_GET['idlapak']);
		}
		if (isset($_GET['idperson'])) {
		    $retri->setIDPerson($_GET['idperson']);
		}
		if (isset($_GET['request'])) {
		    switch ($_GET['request']) {
			  case 'tagihan':
				$data = $retri->gettagihan();
				if ($data) {
				    setresponse(OK, array(
					  'status' => true, 'data' => $data,
				    ));
				} else {
				    setresponse(NOT_FOUND, array(
					  'status' => false, 'message' => 'data tagihan tidak ditemukan',
				    ));
				}
				break;
			  case 'histori':
				$data = $retri->gethistori($page);
				if ($data) {
				    setresponse(OK, array(
					  'status' => true, 'data' => $data,
				    ));
				} else {
				    setresponse(NOT_FOUND, array(
					  'status' => false, 'message' => 'data tagihan tidak ditemukan',
				    ));
				}
				break;
			  case 'rekapbulanan':
				$bulan = $_GET['bulan'];
				$data = $retri->getrekapbulanan($bulan);
				if ($data) {
				    setresponse(OK, array(
					  'status' => true, 'data' => $data,
				    ));
				} else {
				    setresponse(NOT_FOUND, array(
					  'status' => false, 'message' => 'data tagihan tidak ditemukan',
				    ));
				}
				break;
			  default:
				# code...
				break;
		    }
		}
		break;
	  case 'POST':
		$retri->setIDLapak($_POST['idlapak']);
		$retri->setUserName($_POST['username']);
		$retri->setKodePasar($_POST['kodepasar']);
		$retri->setIDPerson($_POST['idperson']);
		$retri->setNominalRetribusi($_POST['nominalretribusi']);
		$retri->setNominalDiterima($_POST['nominalditerima']);
		$retri->setJmlHariDibayar($_POST['jmlharidibayar']);
		$retri->setKeterangan($_POST['keterangan']);
		$retri->setTglMulaiDibayar($_POST['tglmulai']);
//		$retri->setTglSampaiDibayar($_POST['tglselesai']);
		$tglselesai = date('Y-m-d', strtotime($_POST['tglmulai'] . ' ' . ($_POST['jmlharidibayar'] - 1) . ' day'));
		$retri->setTglSampaiDibayar($tglselesai);

		$kode = $retri->createkode();
		if ($kode) {
		    $retri->setNoTransRet($kode);
		    $inserted = $retri->insertone();
		    if ($inserted) {
			  setresponse(OK, array('status' => true, 'message' => 'proses simpan pembayaran retribusi berhasil'));
		    } else {
			  setresponse(NOT_MODIFIED, array('status' => false, 'message' => 'proses simpan pembayanan retribusi gagal'));
		    }
		} else {
		    setresponse(NOT_IMPLEMENTED, array('status' => false, 'message' => 'nomor transaksi gagal generate'));
		}

		break;
    }
?>