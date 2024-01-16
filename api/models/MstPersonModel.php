<?php

class MstPersonModel {

    private $db;

    public function __construct() {
        $this->db = getcon();
    }

    public function get_one_person($idperson) {
        $sql = "SELECT p.IDPerson, p.NamaPerson, p.PJPerson, p.JenisPerson, IFNULL(p.IsPerusahaan, 0) AS IsPerusahaan, p.AlamatLengkapPerson, p.KodeDesa, p.KodeKec, p.KodeKab, p.NamaJalan, IF(length(p.KoorLong) > 0, p.KoorLong, 0) AS KoorLong , IF(length(p.KoorLat) > 0 , p.KoorLat, 0) AS KoorLat, IFNULL(p.GambarPerson, '') AS GambarPerson, p.Keterangan, p.UserName, p.Password, p.IsVerified, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, IFNULL(p.FotoKTP, '') AS FotoKTP, p.NIK, p.NoHP, p.NoRekeningBank, p.AnRekBank, p.KlasifikasiUser, p.KodeDusun, dus.NamaDusun, p.ID_Distributor, p.UserAdmin, p1.NamaPerson AS NamaPJ 
        FROM mstperson p
        LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
        LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
        LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
        LEFT JOIN mstdusun dus ON dus.KodeDusun = p.KodeDusun
        LEFT JOIN mstperson p1 ON p1.IDPerson = p.PJPerson
        WHERE p.IDPerson = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $idperson);
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    $row['Password'] = base64_decode($row['Password']);
					if($row['KoorLong'] == ' '){
                        $row['KoorLong'] = 0;
                    }
                    if($row['KoorLat'] == ' '){
                        $row['KoorLat'] = 0;
                    }
                    $response = $row;
                }
            }
            $stmt->free_result();
            $stmt->close();
            return $response;
        } else {
            return false;
        }
    }

    public function getperson($search = "", $page = 0, $offset = 10, $isperusahaan = 2) {
        if ($search != "") {
            $sql = "SELECT p.IDPerson, p.NamaPerson, p.PJPerson, p.JenisPerson, IFNULL(p.IsPerusahaan, 0) AS IsPerusahaan, p.AlamatLengkapPerson, p.KodeDesa, p.KodeKec, p.KodeKab, p.NamaJalan, IF(length(p.KoorLong) > 0, p.KoorLong, 0) AS KoorLong , IF(length(p.KoorLat) > 0 , p.KoorLat, 0) AS KoorLat, IFNULL(p.GambarPerson, '') AS GambarPerson, p.Keterangan, p.UserName, p.Password, p.IsVerified, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, IFNULL(p.FotoKTP, '') AS FotoKTP, p.NIK, p.NoHP, p.NoRekeningBank, p.AnRekBank, p.KlasifikasiUser, p.KodeDusun, dus.NamaDusun, p.ID_Distributor, p.UserAdmin, p1.NamaPerson AS NamaPJ 
            FROM mstperson p
            LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
            LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
            LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
			LEFT JOIN mstdusun dus ON dus.KodeDusun = p.KodeDusun
			LEFT JOIN mstperson p1 ON p1.IDPerson = p.PJPerson
            WHERE (p.NamaPerson LIKE '%$search%' OR p.PJPerson LIKE '%$search%' OR p.AlamatLengkapPerson LIKE '%$search%' OR p.UserName LIKE '%$search%' OR des.NamaDesa LIKE '%$search%' OR kec.NamaKecamatan LIKE '%$search%' OR kab.NamaKabupaten LIKE '%$search%') AND IF($isperusahaan < 2, p.IsPerusahaan = $isperusahaan, TRUE) AND p.IDPerson != 'PRS-2019-0000000'
            ORDER BY p.IDPerson ASC
            LIMIT $page, $offset";
        } else {
            $sql = "SELECT p.IDPerson, p.NamaPerson, p.PJPerson, p.JenisPerson, IFNULL(p.IsPerusahaan, 0) AS IsPerusahaan, p.AlamatLengkapPerson, p.KodeDesa, p.KodeKec, p.KodeKab, p.NamaJalan, IF(length(p.KoorLong) > 0, p.KoorLong, 0) AS KoorLong , IF(length(p.KoorLat) > 0 , p.KoorLat, 0) AS KoorLat, IFNULL(p.GambarPerson, '') AS GambarPerson, p.Keterangan, p.UserName, p.Password, p.IsVerified, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, IFNULL(p.FotoKTP, '') AS FotoKTP, p.NIK, p.NoHP, p.NoRekeningBank, p.AnRekBank, p.KlasifikasiUser, p.KodeDusun, dus.NamaDusun, p.ID_Distributor, p.UserAdmin, p1.NamaPerson AS NamaPJ 
            FROM mstperson p
            LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
            LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
            LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
			LEFT JOIN mstdusun dus ON dus.KodeDusun = p.KodeDusun
			LEFT JOIN mstperson p1 ON p1.IDPerson = p.PJPerson
			WHERE IF($isperusahaan < 2, p.IsPerusahaan = $isperusahaan, TRUE) AND p.IDPerson != 'PRS-2019-0000000'
            ORDER BY p.IDPerson ASC
            LIMIT $page, $offset";
        }
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    if ($row['KoorLong'] == ' ') {
                        $row['KoorLong'] = 0;
                    }
                    if ($row['KoorLat'] == ' ') {
                        $row['KoorLat'] = 0;
                    }
                    $row['Password'] = base64_decode($row['Password']);
                    array_push($response, $row);
                }
            }
            $stmt->free_result();
            $stmt->close();
            return $response;
        } else {
            return false;
        }
    }

    public function insert_person($idperson, $imageNamePerson, $imageNameKtp, $data = array()) {
        $NamaPerson = $data['NamaPerson'];
        $PJPerson = $data['PJPerson'];
        $JenisPerson = $data['JenisPerson'];
        $IsPerusahaan = $data['IsPerusahaan'];
        $AlamatLengkapPerson = $data['AlamatLengkapPerson'];
        $KodeDesa = $data['KodeDesa'];
        $KodeKec = $data['KodeKec'];
        $KodeKab = $data['KodeKab'];
        $NamaJalan = $data['AlamatLengkapPerson'];//$data['NamaJalan'];
        $KoorLong = $data['KoorLong'];
        $KoorLat = $data['KoorLat'];
        $Keterangan = $data['Keterangan'];
        $UserName = $data['UserName'];
        $Password = base64_encode('123456');//base64_encode($_POST['Password']);
		$NIK = $data['NIK'];
		$NoHP = $data['NoHP'];
		$UserAdmin = $data['UserAdmin'];
		
		$KodeDusun = $data['KodeDusun'];
		$NoRekeningBank = $data['NoRekeningBank'];
		$AnRekBank = $data['AnRekBank'];
		
		$KlasifikasiUser = (int) $IsPerusahaan > 0 ? 'Perusahaan' : 'Perorangan';

        $sql = "INSERT INTO mstperson (IDPerson, NamaPerson, PJPerson, JenisPerson, IsPerusahaan, AlamatLengkapPerson, KodeDesa, KodeKec, KodeKab, NamaJalan, KoorLong, KoorLat, GambarPerson, Keterangan, UserName, Password, IsVerified, FotoKTP, NIK, NoHP, UserAdmin, KodeDusun, NoRekeningBank, AnRekBank, KlasifikasiUser) VALUES ('$idperson', 
		'$NamaPerson', 
		IF(LENGTH('$PJPerson') > 0, '$PJPerson', NULL), 
		'$JenisPerson', 
		b'$IsPerusahaan', 
		'$AlamatLengkapPerson', 
		'$KodeDesa', 
		'$KodeKec', 
		'$KodeKab', 
		IF(LENGTH('$NamaJalan') > 0, '$NamaJalan', NULL), 
		'$KoorLong', 
		'$KoorLat', 
		IF(LENGTH('$imageNamePerson') > 0, '$imageNamePerson', NULL), 
		IF(LENGTH('$Keterangan') > 0, '$Keterangan', NULL), 
		IF(LENGTH('$UserName') > 0, '$UserName', NULL), 
		IF(LENGTH('$Password') > 0, '$Password', NULL),  
		b'1', 
		IF(LENGTH('$imageNameKtp') > 0, '$imageNameKtp', NULL), 
		IF(LENGTH('$NIK') > 0, '$NIK', NULL), 
		'$NoHP', 
		IF(LENGTH('$UserAdmin') > 0, '$UserAdmin', NULL), 
		'$KodeDusun', 
		'$NoRekeningBank', 
		'$AnRekBank', '$KlasifikasiUser')";
		//return $sql;
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update_person($idperson, $imageNamePerson, $imageNameKtp) {
        $NamaPerson = $_POST['NamaPerson'];
        $PJPerson = $_POST['PJPerson'];
        $JenisPerson = $_POST['JenisPerson'];
        $IsPerusahaan = $_POST['IsPerusahaan'];
        $AlamatLengkapPerson = $_POST['AlamatLengkapPerson'];
        $KodeDesa = $_POST['KodeDesa'];
        $KodeKec = $_POST['KodeKec'];
        $KodeKab = $_POST['KodeKab'];
        $NamaJalan = $_POST['AlamatLengkapPerson'];//$_POST['NamaJalan'];
        $KoorLong = $_POST['KoorLong'];
        $KoorLat = $_POST['KoorLat'];
        $Keterangan = $_POST['Keterangan'];
        $UserName = $_POST['UserName'];
        $Password = base64_encode($_POST['Password']);
		$NIK = $_POST['NIK'];
		$NoHP = $_POST['NoHP'];
		$UserAdmin = $_POST['UserAdmin'];
		
		$KodeDusun = $_POST['KodeDusun'];
		$NoRekeningBank = $_POST['NoRekeningBank'];
		$AnRekBank = $_POST['AnRekBank'];

        $sql = "UPDATE mstperson SET 
		NamaPerson = '$NamaPerson',  
		PJPerson = '$PJPerson',  
		JenisPerson = '$JenisPerson',  
		IsPerusahaan = b'$IsPerusahaan',  
		AlamatLengkapPerson = '$AlamatLengkapPerson',  
		KodeDesa = '$KodeDesa',  
		KodeKec = '$KodeKec',  
		KodeKab = '$KodeKab',  
		NamaJalan = IF(LENGTH('$NamaJalan') > 0, '$NamaJalan', NamaJalan),  
		KoorLong = '$KoorLong',  
		KoorLat = '$KoorLat',  
		GambarPerson = '$imageNamePerson', 
		FotoKTP = '$imageNameKtp', 
		Keterangan = IF(LENGTH('$Keterangan') > 0, '$Keterangan', Keterangan),  
		UserName = IF(LENGTH('$UserName') > 0, '$UserName', UserName),  
		Password = IF(LENGTH('$Password') > 0, '$Password', Password), 
		NIK = '$NIK', 
		NoHP = '$NoHP', 
		KodeDusun = IF(LENGTH('$KodeDusun') > 0, '$KodeDusun', KodeDusun),
		NoRekeningBank = IF(LENGTH('$NoRekeningBank') > 0, '$NoRekeningBank', NoRekeningBank),
		AnRekBank = IF(LENGTH('$AnRekBank') > 0, '$AnRekBank', AnRekBank) 
		WHERE IDPerson = '$idperson';";
		//return $sql;
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_imagedelete($idperson) {
        $sql = "SELECT GambarPerson, FotoKTP FROM mstperson WHERE IDPerson = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $idperson);
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    $response = $row;
                }
            }
            $stmt->free_result();
            $stmt->close();
            return $response;
        } else {
            return false;
        }
    }

    public function generate_idperson() {
        date_default_timezone_set('Asia/Jakarta');
        //PRS-2019-0000001
        $tahun = date('Y');
        $sql = "SELECT RIGHT(IDPerson,7) AS kode FROM mstperson WHERE IDPerson LIKE '%$tahun%' ORDER BY IDPerson DESC LIMIT 1";
        $res = mysqli_query($this->db, $sql);
        $result = mysqli_fetch_array($res);
        if ($result['kode'] == null) {
            $kode = 1;
        } else {
            $kode = ++$result['kode'];
        }
        $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
        return 'PRS-' . $tahun . '-' . $bikin_kode;
    }

    public function getmapperson() {
        $sql = "SELECT p.IDPerson, p.NamaPerson, p.PJPerson, p.JenisPerson, IFNULL(p.IsPerusahaan, 0) AS IsPerusahaan, p.AlamatLengkapPerson, p.KodeDesa, p.KodeKec, p.KodeKab, p.NamaJalan, IF(length(p.KoorLong) > 0, p.KoorLong, 0) AS KoorLong , IF(length(p.KoorLat) > 0 , p.KoorLat, 0) AS KoorLat, IFNULL(p.GambarPerson, '') AS GambarPerson, p.Keterangan, p.UserName, p.Password, p.IsVerified, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, IFNULL(p.FotoKTP, '') AS FotoKTP, p.NIK, p.NoHP, p.NoRekeningBank, p.AnRekBank, p.KlasifikasiUser, p.KodeDusun, dus.NamaDusun, p.ID_Distributor, p.UserAdmin
        FROM mstperson p
        LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
        LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
        LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
		LEFT JOIN mstdusun dus ON dus.KodeDusun = p.KodeDusun
		WHERE p.IDPerson != 'PRS-2019-0000000'
        ORDER BY p.NamaPerson ASC ";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    if ($row['KoorLong'] == ' ') {
                        $row['KoorLong'] = 0;
                    }
                    if ($row['KoorLat'] == ' ') {
                        $row['KoorLat'] = 0;
                    }
                    array_push($response, $row);
                }
            }
            $stmt->free_result();
            $stmt->close();
            return $response;
        } else {
            return false;
        }
    }
}
