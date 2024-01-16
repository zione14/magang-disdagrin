<?php
date_default_timezone_set('Asia/Jakarta');
$tgl = date("YmdHis");
require_once 'config/config.php';
require_once 'models/MstPersonModel.php';
require_once 'models/ImageFunction.php';

$person = new MstPersonModel();
$imageperson = new ImageFunction();
$imagektp = new ImageFunction();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
	$page = isset($_GET['page']) ? mysqli_escape_string(getcon(), $_GET['page']) : 0;
	$offset = isset($_GET['offset']) && $_GET['offset'] > 0 ? mysqli_escape_string(getcon(), $_GET['offset']) : 10;
	$search = isset($_GET['value']) ? mysqli_escape_string(getcon(), $_GET['value']) : '';
	$isperusahaan = isset($_GET['isperusahaan']) ? (int) $_GET['isperusahaan'] : 2;

	if (isset($_GET['idperson']) && $_GET['idperson'] != '') {
		$idperson = mysqli_escape_string(getcon(), $_GET['idperson']);
		$data = $person->get_one_person($idperson);
	} else {
		$data = $person->getperson($search, $page, $offset, $isperusahaan);
	}
	if ($data) {
		setresponse(OK, array('status' => true, 'data' => $data));
	} else {
		setresponse(NOT_FOUND, array('status' => false, 'message' => 'data person tidak ditemukan'));
	}
	break;
	case 'POST':
		//print_r($_POST);die();
	if (isset($_POST['IDPerson']) && $_POST['IDPerson'] != '') {
		$idperson = $_POST['IDPerson'];
		if (isset($_FILES['photoPerson']) && $_FILES['photoPerson'] != null) {
			$upload = $imageperson->upload($_FILES['photoPerson'], '../images/FotoPerson/', $tgl . '-' . $idperson);
			if ($upload) {
				$imageNamePerson = $imageperson->getimagename();
				$image_old = $person->get_imagedelete($idperson);
				if ($image_old) {
					if (file_exists('../images/FotoPerson/' . $image_old['GambarPerson'])) {
						unlink('../images/FotoPerson/' . $image_old['GambarPerson']);
						unlink('../images/FotoPerson/thumb_' . $image_old['GambarPerson']);
					}
				}
			} else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto person', 'image_msg' => $imageperson->geterror()));
			}
		} else {
			$imageNamePerson = isset($_POST['GambarPerson']) ? $_POST['GambarPerson'] : '';
		}

		if (isset($_FILES['photoKtp']) && $_FILES['photoKtp'] != null) {
			$upload = $imagektp->upload($_FILES['photoKtp'], '../images/FotoPerson/', $tgl . '-KTP-' . $idperson);
			if ($upload) {
				$imageNameKtp = $imagektp->getimagename();
				$image_old = $person->get_imagedelete($idperson);
				if ($image_old) {
					if (file_exists('../images/FotoPerson/' . $image_old['FotoKTP'])) {
						unlink('../images/FotoPerson/' . $image_old['FotoKTP']);
						unlink('../images/FotoPerson/thumb_' . $image_old['FotoKTP']);
					}
				}
			} else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto ktp', 'image_msg' => $imagektp->geterror()));
			}
		} else {
			$imageNameKtp = isset($_POST['FotoKTP']) ? $_POST['FotoKTP'] : '';
		}
		$update = $person->update_person($idperson, $imageNamePerson, $imageNameKtp);
			//setresponse(FORBIDDEN, array('status' => false, 'message' => $update));
		if ($update) {
			$data = $person->get_one_person($idperson);
			setresponse(CREATED, array('status' => true, 'data' => $data, 'message' => 'data berhasil disimpan', 'idperson' => $idperson));
		} else {
			setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
		}
	} else {		
		$dataSimpan = $_POST;
		$IsPjBaru = isset($_POST['IsPjBaru']) ? (int) $_POST['IsPjBaru'] : 0;
		if($IsPjBaru > 0){
			$data = $_POST;
			$data['NamaPerson'] = $_POST['PJPerson'];
			$data['PJPerson'] = '';
			$data['IsPerusahaan'] = 0;
			$data['UserName'] = $_POST['NIK'];
			$idperson = $person->generate_idperson();
			if (isset($_FILES['photoPerson']) && $_FILES['photoPerson'] != null) {
				$upload = $imageperson->upload($_FILES['photoPerson'], '../images/FotoPerson/', $tgl . '-' . $idperson);
				if ($upload) {
					$imageNamePerson = $imageperson->getimagename();
					$image_old = $person->get_imagedelete($idperson);
					if ($image_old) {
						if (file_exists('../images/FotoPerson/' . $image_old['GambarPerson'])) {
							unlink('../images/FotoPerson/' . $image_old['GambarPerson']);
							unlink('../images/FotoPerson/thumb_' . $image_old['GambarPerson']);
						}
					}
				} else {
					setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto person', 'image_msg' => $imageperson->geterror()));
				}
			} else {
				$imageNamePerson = '';
			}

			if (isset($_FILES['photoKtp']) && $_FILES['photoKtp'] != null) {
				$upload = $imagektp->upload($_FILES['photoKtp'], '../images/FotoPerson/', $tgl . '-KTP-' . $idperson);
				if ($upload) {
					$imageNameKtp = $imagektp->getimagename();
					$image_old = $person->get_imagedelete($idperson);
					if ($image_old) {
						if (file_exists('../images/FotoPerson/' . $image_old['FotoKTP'])) {
							unlink('../images/FotoPerson/' . $image_old['FotoKTP']);
							unlink('../images/FotoPerson/thumb_' . $image_old['FotoKTP']);
						}
					}
				} else {
					setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto ktp', 'image_msg' => $imagektp->geterror()));
				}
			} else {
				$imageNameKtp = '';
			}			
			if($person->insert_person($idperson, $imageNamePerson, $imageNameKtp, $data)){
				$dataSimpan['PJPerson'] = $idperson;
				if((int)$dataSimpan['IsPerusahaan'] > 0){
					$dataSimpan['NIK'] = '';	
				}			
				$dataSimpan['UserName'] = $_POST['NIK'];
				$idperson = $person->generate_idperson();
				$insert = $person->insert_person($idperson, $imageNamePerson, $imageNameKtp, $dataSimpan);
				if ($insert) {
					setresponse(CREATED, array('status' => true, 'message' => 'data berhasil disimpan', 'idperson' => $idperson));
				} else {
					setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
				}
			}			
		}else{
			$dataSimpan = $_POST;
			if((int)$dataSimpan['IsPerusahaan'] > 0){
				$dataSimpan['NIK'] = '';	
			}			
			$dataSimpan['UserName'] = $_POST['NIK'];
		}

		$idperson = $person->generate_idperson();
		if (isset($_FILES['photoPerson']) && $_FILES['photoPerson'] != null) {
			$upload = $imageperson->upload($_FILES['photoPerson'], '../images/FotoPerson/', $tgl . '-' . $idperson);
			if ($upload) {
				$imageNamePerson = $imageperson->getimagename();
				$image_old = $person->get_imagedelete($idperson);
				if ($image_old) {
					if (file_exists('../images/FotoPerson/' . $image_old['GambarPerson'])) {
						unlink('../images/FotoPerson/' . $image_old['GambarPerson']);
						unlink('../images/FotoPerson/thumb_' . $image_old['GambarPerson']);
					}
				}
			} else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto person', 'image_msg' => $imageperson->geterror()));
			}
		} else {
			$imageNamePerson = '';
		}

		if (isset($_FILES['photoKtp']) && $_FILES['photoKtp'] != null) {
			$upload = $imagektp->upload($_FILES['photoKtp'], '../images/FotoPerson/', $tgl . '-KTP-' . $idperson);
			if ($upload) {
				$imageNameKtp = $imagektp->getimagename();
				$image_old = $person->get_imagedelete($idperson);
				if ($image_old) {
					if (file_exists('../images/FotoPerson/' . $image_old['FotoKTP'])) {
						unlink('../images/FotoPerson/' . $image_old['FotoKTP']);
						unlink('../images/FotoPerson/thumb_' . $image_old['FotoKTP']);
					}
				}
			} else {
				setresponse(NOT_ACCEPTABLE, array('status' => false, 'message' => 'tidak dapat menyimpan data foto ktp', 'image_msg' => $imagektp->geterror()));
			}
		} else {
			$imageNameKtp = '';
		}			
		$insert = $person->insert_person($idperson, $imageNamePerson, $imageNameKtp, $dataSimpan);
		//echo $insert;die();
		if ($insert) {
			setresponse(CREATED, array('status' => true, 'message' => 'data berhasil disimpan', 'idperson' => $idperson));
		} else {
			setresponse(FORBIDDEN, array('status' => false, 'message' => 'gagal menyimpan data'));
		}
	}
	break;
}
?>