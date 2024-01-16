<?php

    date_default_timezone_set('Asia/Jakarta');
    $tgl = date("YmdHis");
    require_once 'config/config.php';
    require_once 'models/TimbanganPersonModel.php';
    require_once 'models/ImageFunction.php';

    $tp = new TimbanganPersonModel();
    $image = new ImageFunction();


    switch ($_SERVER['REQUEST_METHOD']) {
	  case 'GET':
		$page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
		$offset = isset($_GET['offset']) && $_GET['offset'] > 0 ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
		$search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';

		if (isset($_GET['idtimbangan']) && $_GET['idtimbangan'] != '') {
		    $idtimbangan = mysqli_escape_string(getcon(), $_GET['idtimbangan']);
		    $data = $tp->get_one_timbanganperson($idtimbangan);
		    if ($data) {
				$data['KoorLong'] = str_replace(',', '.', $data['KoorLong']);
				$data['KoorLat'] = str_replace(',', '.', $data['KoorLat']);
			  setresponse(OK, array('status' => true, 'data' => $data));
		    } else {
			  setresponse(NOT_FOUND, array('status' => false, 'message' => 'data timbangan tidak ditemukan'));
		    }
		} else {
		    $idperson = mysqli_escape_string(getcon(), $_GET['idperson']);
		    $kodelokasi = mysqli_escape_string(getcon(), $_GET['kodelokasi']);
			$kodekec = @$_GET['kodekec'];
			$kodedesa = @$_GET['kodedesa'];
		    $data = $tp->gettimbanganperson($idperson, $kodelokasi, $search, $page, $offset, $kodekec, $kodedesa);
		    if ($data) {
			  setresponse(OK, array('status' => true, 'data' => $data));
		    } else {
			  setresponse(NOT_FOUND, array('status' => false, 'message' => 'data timbangan tidak ditemukan'));
		    }
		}
		break;
	  case 'POST':

		$imageName1 = '';
		$imageName2 = '';
		$imageName3 = '';
		$imageName4 = '';

		if (isset($_POST['IDTimbangan']) && $_POST['IDTimbangan'] != '') {
		    $idtimbangan = $_POST['IDTimbangan'];
		    if (isset($_FILES['photo1']) && $_FILES['photo1'] != null) {
			  $upload = $image->upload($_FILES['photo1'], '../images/Timbangan/', $idtimbangan. '-1' . '-' . $tgl );
			  if ($upload) {
				$imageName1 = $image->getimagename();
				if ($_POST['FotoTimbangan1'] != '') {
				    if (file_exists('../images/Timbangan/' . $_POST['FotoTimbangan1'])) {
					  unlink('../images/Timbangan/' . $_POST['FotoTimbangan1']);
					  unlink('../images/Timbangan/thumb_' . $_POST['FotoTimbangan1']);
				    }
				}
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto', 'image_msg' => $image->geterror()));
			  }
		    } else {
			  $imageName1 = isset($_POST['FotoTimbangan1']) ? $_POST['FotoTimbangan1'] : '';
		    }

		    if (isset($_FILES['photo2']) && $_FILES['photo2'] != null) {
			  $upload = $image->upload($_FILES['photo2'], '../images/Timbangan/', $idtimbangan. '-2' . '-' . $tgl);
			  if ($upload) {
				$imageName2 = $image->getimagename();
				if ($_POST['FotoTimbangan2'] != '') {
				    if (file_exists('../images/Timbangan/' . $_POST['FotoTimbangan2'])) {
					  unlink('../images/Timbangan/' . $_POST['FotoTimbangan2']);
					  unlink('../images/Timbangan/thumb_' . $_POST['FotoTimbangan2']);
				    }
				}
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 2', 'image_msg' => $image->geterror()));
			  }
		    } else {
			  $imageName2 = isset($_POST['FotoTimbangan2']) ? $_POST['FotoTimbangan2'] : '';
		    }

		    if (isset($_FILES['photo3']) && $_FILES['photo3'] != null) {
			  $upload = $image->upload($_FILES['photo3'], '../images/Timbangan/', $idtimbangan. '-3' . '-' . $tgl);
			  if ($upload) {
				$imageName3 = $image->getimagename();
				if ($_POST['FotoTimbangan3'] != '') {
				    if (file_exists('../images/Timbangan/' . $_POST['FotoTimbangan3'])) {
					  unlink('../images/Timbangan/' . $_POST['FotoTimbangan3']);
					  unlink('../images/Timbangan/thumb_' . $_POST['FotoTimbangan3']);
				    }
				}
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 3', 'image_msg' => $image->geterror()));
			  }
		    } else {
			  $imageName3 = isset($_POST['FotoTimbangan3']) ? $_POST['FotoTimbangan3'] : '';
		    }

		    if (isset($_FILES['photo4']) && $_FILES['photo4'] != null) {
			  $upload = $image->upload($_FILES['photo4'], '../images/Timbangan/', $idtimbangan. '-4' . '-' . $tgl);
			  if ($upload) {
				$imageName4 = $image->getimagename();
				if ($_POST['FotoTimbangan4'] != '') {
				    if (file_exists('../images/Timbangan/' . $_POST['FotoTimbangan4'])) {
					  unlink('../images/Timbangan/' . $_POST['FotoTimbangan4']);
					  unlink('../images/Timbangan/thumb_' . $_POST['FotoTimbangan4']);
				    }
				}
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 4', 'image_msg' => $image->geterror()));
			  }
		    } else {
			  $imageName4 = isset($_POST['FotoTimbangan4']) ? $_POST['FotoTimbangan4'] : '';
		    }

		    $update = $tp->update_timbanganperson($idtimbangan, $imageName1, $imageName2, $imageName3, $imageName4);
			//setresponse(FORBIDDEN, array('status' => false, 'message' => $update));
		    if ($update) {
			  setresponse(CREATED, array('status' => true, 'message' => 'data berhasil disimpan'));
		    } else {
			  setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
		    }
		} else {
		    $idtimbangan = $tp->generate_idtimbangan();
	          if (!empty($_FILES['photo1']['tmp_name'])) {
			  $upload = $image->upload($_FILES['photo1'], '../images/Timbangan/', $idtimbangan  . '-1'. '-' . $tgl);
			  if ($upload) {
				$imageName1 = $image->getimagename();
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto', 'image_msg' => $image->geterror()));
			  }
		    }

	          if (!empty($_FILES['photo2']['tmp_name'])) {
			  $upload = $image->upload($_FILES['photo2'], '../images/Timbangan/', $idtimbangan  . '-2'. '-' . $tgl);
			  if ($upload) {
				$imageName2 = $image->getimagename();
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 2', 'image_msg' => $image->geterror()));
			  }
		    }

	          if (!empty($_FILES['photo3']['tmp_name'])) {
			  $upload = $image->upload($_FILES['photo3'], '../images/Timbangan/', $idtimbangan  . '-3'. '-' . $tgl);
			  if ($upload) {
				$imageName3 = $image->getimagename();
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 3', 'image_msg' => $image->geterror()));
			  }
		    }

	          if (!empty($_FILES['photo4']['tmp_name'])) {
			  $upload = $image->upload($_FILES['photo4'], '../images/Timbangan/', $idtimbangan  . '-4'. '-' . $tgl);
			  if ($upload) {
				$imageName4 = $image->getimagename();
			  } else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto 4', 'image_msg' => $image->geterror()));
			  }
		    }
			
			//buat qr code timbangan			
			require_once '../library/barcode/src/BarcodeGenerator.php';
			require_once '../library/barcode/src/BarcodeGeneratorPNG.php';
			$fileqrcode = $idtimbangan.'-'.$tgl.'.png';
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();
			$Tahun = date('Y');
			file_put_contents('../images/TimbanganQR/'.$fileqrcode, $generatorSVG->getBarcode($idtimbangan, $generatorSVG::TYPE_CODE_128));

		    $insert = $tp->insert_timbanganperson($idtimbangan, $imageName1, $imageName2, $imageName3, $imageName4, $fileqrcode);
		    //setresponse(FORBIDDEN, array('status'=>false, 'message'=>$insert));
		    //die();
		    if ($insert) {
			  setresponse(CREATED, array('status' => true, 'message' => 'data berhasil disimpan'));
		    } else {
			  setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
		    }
		}
		break;
	  case 'DELETE':
		parse_str(file_get_contents('php://input'), $req);
		if (isset($req['IDTimbangan']) && $req['IDTimbangan'] != '') {
		    $idtimbangan = $req['IDTimbangan'];
		    $datafoto = $tp->get_fototimbangan($idtimbangan);
		    $res = $tp->delete_timbanganperson($idtimbangan);
		    if ($res) {
			  if ($datafoto['FotoTimbangan1'] != '') {
				if (file_exists('../images/Timbangan/' . $datafoto['FotoTimbangan1'])) {
				    unlink('../images/Timbangan/' . $datafoto['FotoTimbangan1']);
				    unlink('../images/Timbangan/thumb_' . $datafoto['FotoTimbangan1']);
				}
			  }

			  if ($datafoto['FotoTimbangan2'] != '') {
				if (file_exists('../images/Timbangan/' . $datafoto['FotoTimbangan2'])) {
				    unlink('../images/Timbangan/' . $datafoto['FotoTimbangan2']);
				    unlink('../images/Timbangan/thumb_' . $datafoto['FotoTimbangan2']);
				}
			  }

			  if ($datafoto['FotoTimbangan3'] != '') {
				if (file_exists('../images/Timbangan/' . $datafoto['FotoTimbangan3'])) {
				    unlink('../images/Timbangan/' . $datafoto['FotoTimbangan3']);
				    unlink('../images/Timbangan/thumb_' . $datafoto['FotoTimbangan3']);
				}
			  }

			  if ($datafoto['FotoTimbangan4'] != '') {
				if (file_exists('../images/Timbangan/' . $datafoto['FotoTimbangan4'])) {
				    unlink('../images/Timbangan/' . $datafoto['FotoTimbangan4']);
				    unlink('../images/Timbangan/thumb_' . $datafoto['FotoTimbangan4']);
				}
			  }

			  setresponse(OK, array('status' => true, 'message' => 'data berhasil dihapus'));
		    }
		} else {
		    setresponse(BAD_REQUEST, array('status' => false, 'message' => 'permintaan tidak dapat diproses, Id Timbangan is null'));
		}
		break;
    }
?>