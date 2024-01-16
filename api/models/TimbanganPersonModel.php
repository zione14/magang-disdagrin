<?php

class TimbanganPersonModel
{

    private $db;

    public function __construct()
    {
        $this->db = getcon();
    }

    public function get_one_timbanganperson($idtimbangan){
        $sql = "SELECT tp.Satuan,tp.IDTimbangan, tp.KodeLokasi, l.NamaLokasi, tp.NamaTimbangan, tp.AlamatTimbangan, IF(LENGTH(tp.KoorLong) > 0 ,tp.KoorLong , 0) AS KoorLong, IF(LENGTH(tp.KoorLat) > 0, tp.KoorLat, 0) AS KoorLat, IF(tp.Keterangan != '#####', tp.Keterangan, '-') AS Keterangan, IFNULL(tp.FotoTimbangan1, '') AS FotoTimbangan1, IFNULL(tp.FotoTimbangan2, '') AS FotoTimbangan2, IFNULL(tp.FotoTimbangan3, '') AS FotoTimbangan3, IFNULL(tp.FotoTimbangan4, '') AS FotoTimbangan4, l.KodeDesa, l.KodeKec, l.KodeKab, tp.KodeTimbangan, tp.IDPerson, tp.QRCode, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson, t.NamaTimbangan AS NamaTimbanganMst, t.JenisTimbangan, t.Merk, t.Ukuran, t.Kapasitas, t.TahunPembuatan, t.NamaPabrik, 0 as NilaiRetribusi, tp.KodeUkuran, tp.KodeKelas, tp.KodeLokasi, tp.UkuranRealTimbangan, k.Keterangan AS KeteranganKelas, k.NamaKelas, u.NamaUkuran, u.Keterangan AS KeteranganUkuran, u.RetribusiDikantor, u.RetribusiDiLokasi, u.NilaiBawah, u.NilaiAtas, u.NilaiTambah, u.RetPenambahanDikantor, u.RetPenambahanDiLokasi  
        FROM timbanganperson tp 
        LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tp.KodeLokasi AND l.IDPerson = tp.IDPerson
        LEFT JOIN mstperson p ON p.IDPerson = tp.IDPerson
        LEFT JOIN msttimbangan t ON t.KodeTimbangan = tp.KodeTimbangan
        LEFT JOIN mstkab kab ON kab.KodeKab = l.KodeKab 
        LEFT JOIN mstkec kec ON kec.KodeKec = l.KodeKec 
        LEFT JOIN mstdesa des ON des.KodeDesa = l.KodeDesa 
        LEFT JOIN kelas k ON tp.KodeKelas = k.KodeKelas
        LEFT JOIN detilukuran u ON tp.KodeUkuran = u.KodeUkuran
        WHERE tp.IDTimbangan = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $idtimbangan);
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

    public function gettimbanganperson($idperson, $kodelokasi, $search = "", $page = 0, $offset = 10, $kodekec = '', $kodedesa = '')
    {
        $stmt = false;
        if($search != ""){            
            $sql = "SELECT tp.IDTimbangan, tp.KodeLokasi, l.NamaLokasi, tp.NamaTimbangan, tp.AlamatTimbangan, IF(LENGTH(tp.KoorLong) > 0 ,tp.KoorLong , 0) AS KoorLong, IF(LENGTH(tp.KoorLat) > 0, tp.KoorLat, 0) AS KoorLat, IF(tp.Keterangan != '#####', tp.Keterangan, '-') AS Keterangan, IFNULL(tp.FotoTimbangan1, '') AS FotoTimbangan1, IFNULL(tp.FotoTimbangan2, '') AS FotoTimbangan2, IFNULL(tp.FotoTimbangan3, '') AS FotoTimbangan3, IFNULL(tp.FotoTimbangan4, '') AS FotoTimbangan4, l.KodeDesa, l.KodeKec, l.KodeKab, tp.KodeTimbangan, tp.IDPerson, tp.QRCode, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson, t.NamaTimbangan AS NamaTimbanganMst, t.JenisTimbangan, t.Merk, t.Ukuran, t.Kapasitas, t.TahunPembuatan, t.NamaPabrik, 0 as NilaiRetribusi, tp.KodeUkuran, tp.KodeKelas, tp.KodeLokasi, tp.UkuranRealTimbangan, k.Keterangan AS KeteranganKelas, k.NamaKelas, u.NamaUkuran, u.Keterangan AS KeteranganUkuran, u.RetribusiDikantor, u.RetribusiDiLokasi, u.NilaiBawah, u.NilaiAtas, u.NilaiTambah, u.RetPenambahanDikantor, u.RetPenambahanDiLokasi   
            FROM timbanganperson tp 
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tp.KodeLokasi AND l.IDPerson = tp.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = tp.IDPerson
            LEFT JOIN msttimbangan t ON t.KodeTimbangan = tp.KodeTimbangan
            LEFT JOIN mstkab kab ON kab.KodeKab = l.KodeKab 
            LEFT JOIN mstkec kec ON kec.KodeKec = l.KodeKec 
            LEFT JOIN mstdesa des ON des.KodeDesa = l.KodeDesa 
            LEFT JOIN kelas k ON tp.KodeKelas = k.KodeKelas
            LEFT JOIN detilukuran u ON tp.KodeUkuran = u.KodeUkuran 
            WHERE tp.IDPerson != 'PRS-2019-0000000' AND (p.NamaPerson LIKE '%$search%' OR p.PJPerson LIKE '%$search%' OR l.NamaLokasi LIKE '%$search%' OR tp.NamaTimbangan LIKE '%$search%' OR tp.AlamatTimbangan LIKE '%$search%' OR des.NamaDesa LIKE '%$search%' OR kec.NamaKecamatan LIKE '%$search%' OR kab.NamaKabupaten LIKE '%$search%') AND IF(LENGTH('$idperson') > 0, tp.IDPerson = '$idperson', TRUE) AND IF(LENGTH('$kodelokasi') > 0, tp.KodeLokasi = '$kodelokasi', TRUE) AND IF(LENGTH('$kodekec') > 0, l.KodeKec = '$kodekec', TRUE) AND IF(LENGTH('$kodedesa') > 0, l.KodeDesa = '$kodedesa', TRUE)
            ORDER BY tp.NamaTimbangan ASC 
            LIMIT $page, $offset";
        }else{
            $sql = "SELECT tp.IDTimbangan, tp.KodeLokasi, l.NamaLokasi, tp.NamaTimbangan, tp.AlamatTimbangan, IF(LENGTH(tp.KoorLong) > 0 ,tp.KoorLong , 0) AS KoorLong, IF(LENGTH(tp.KoorLat) > 0, tp.KoorLat, 0) AS KoorLat, IF(tp.Keterangan != '#####', tp.Keterangan, '-') AS Keterangan, IFNULL(tp.FotoTimbangan1, '') AS FotoTimbangan1, IFNULL(tp.FotoTimbangan2, '') AS FotoTimbangan2, IFNULL(tp.FotoTimbangan3, '') AS FotoTimbangan3, IFNULL(tp.FotoTimbangan4, '') AS FotoTimbangan4, l.KodeDesa, l.KodeKec, l.KodeKab, tp.KodeTimbangan, tp.IDPerson, tp.QRCode, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson, t.NamaTimbangan AS NamaTimbanganMst, t.JenisTimbangan, t.Merk, t.Ukuran, t.Kapasitas, t.TahunPembuatan, t.NamaPabrik, 0 as NilaiRetribusi, tp.KodeUkuran, tp.KodeKelas, tp.KodeLokasi, tp.UkuranRealTimbangan, k.Keterangan AS KeteranganKelas, k.NamaKelas, u.NamaUkuran, u.Keterangan AS KeteranganUkuran, u.RetribusiDikantor, u.RetribusiDiLokasi, u.NilaiBawah, u.NilaiAtas, u.NilaiTambah, u.RetPenambahanDikantor, u.RetPenambahanDiLokasi  
            FROM timbanganperson tp 
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tp.KodeLokasi AND l.IDPerson = tp.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = tp.IDPerson
            LEFT JOIN msttimbangan t ON t.KodeTimbangan = tp.KodeTimbangan
            LEFT JOIN mstkab kab ON kab.KodeKab = l.KodeKab 
            LEFT JOIN mstkec kec ON kec.KodeKec = l.KodeKec 
            LEFT JOIN mstdesa des ON des.KodeDesa = l.KodeDesa 
            LEFT JOIN kelas k ON tp.KodeKelas = k.KodeKelas
            LEFT JOIN detilukuran u ON tp.KodeUkuran = u.KodeUkuran
            WHERE tp.IDPerson != 'PRS-2019-0000000' AND IF(LENGTH('$idperson') > 0, tp.IDPerson = '$idperson', TRUE) AND IF(LENGTH('$kodelokasi') > 0, tp.KodeLokasi = '$kodelokasi', TRUE) AND IF(LENGTH('$kodekec') > 0, l.KodeKec = '$kodekec', TRUE) AND IF(LENGTH('$kodedesa') > 0, l.KodeDesa = '$kodedesa', TRUE)
            ORDER BY tp.NamaTimbangan ASC 
            LIMIT $page, $offset";
        }
//         echo $sql;exit;
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
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

    public function insert_timbanganperson($idtimbangan, $FotoTimbangan1, $FotoTimbangan2, $FotoTimbangan3, $FotoTimbangan4, $QRCode = ''){
        $NamaTimbangan = $_POST['NamaTimbangan'];
        $AlamatTimbangan = $_POST['AlamatTimbangan'];
        $KoorLong = $_POST['KoorLong'];
        $KoorLat = $_POST['KoorLat'];
        $Keterangan = $_POST['Keterangan'];
        $KodeTimbangan = $_POST['KodeTimbangan'];
        $IDPerson = $_POST['IDPerson'];
		$KodeUkuran = $_POST['KodeUkuran'];
        $KodeKelas = $_POST['KodeKelas'];
        $KodeLokasi = $_POST['KodeLokasi'];
        $UkuranRealTimbangan = $_POST['UkuranRealTimbangan'];
        $Satuan = $_POST['Satuan'];

        $sql = "INSERT INTO timbanganperson (IDTimbangan, NamaTimbangan, AlamatTimbangan, KoorLong, KoorLat, Keterangan, FotoTimbangan1, FotoTimbangan2, FotoTimbangan3, FotoTimbangan4, KodeTimbangan, IDPerson, KodeUkuran, KodeKelas, KodeLokasi, UkuranRealTimbangan, StatusUTTP, TglInput, Satuan, QRCode) VALUES ('$idtimbangan', '$NamaTimbangan', '$AlamatTimbangan', '$KoorLong', '$KoorLat', '$Keterangan', IF(LENGTH('$FotoTimbangan1') > 0 , '$FotoTimbangan1', NULL), IF(LENGTH('$FotoTimbangan2') > 0 , '$FotoTimbangan2', NULL), IF(LENGTH('$FotoTimbangan3') > 0 , '$FotoTimbangan3', NULL), IF(LENGTH('$FotoTimbangan4') > 0 , '$FotoTimbangan4', NULL), '$KodeTimbangan', '$IDPerson', '$KodeUkuran', '$KodeKelas', '$KodeLokasi', '$UkuranRealTimbangan', 'Aktif', CURRENT_TIMESTAMP, '$Satuan', IF(LENGTH('$QRCode') > 0 , '$QRCode', NULL))";
        //return $sql;
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update_timbanganperson($idtimbangan, $FotoTimbangan1, $FotoTimbangan2, $FotoTimbangan3, $FotoTimbangan4){
        //$NamaLokasi = $_POST['NamaLokasi'];
        $NamaTimbangan = $_POST['NamaTimbangan'];
        $AlamatTimbangan = $_POST['AlamatTimbangan'];
        $KoorLong = $_POST['KoorLong'];
        $KoorLat = $_POST['KoorLat'];
        $Keterangan = $_POST['Keterangan'];
        /* $KodeDesa = $_POST['KodeDesa'];
        $KodeKec = $_POST['KodeKec'];
        $KodeKab = $_POST['KodeKab']; */
        $KodeTimbangan = $_POST['KodeTimbangan'];
		
        $Satuan = $_POST['Satuan'];
		$KodeUkuran = $_POST['KodeUkuran'];
        $KodeKelas = $_POST['KodeKelas'];
        $UkuranRealTimbangan = $_POST['UkuranRealTimbangan'];

        $sql = "UPDATE timbanganperson SET 
        NamaTimbangan = '$NamaTimbangan', 
        AlamatTimbangan = IF(LENGTH('$AlamatTimbangan') > 0 , '$AlamatTimbangan', AlamatTimbangan), 
        KoorLong = '$KoorLong', 
        KoorLat = '$KoorLat', 
        Keterangan = '$Keterangan', 
        FotoTimbangan1 = IF(LENGTH('$FotoTimbangan1') > 0 , '$FotoTimbangan1', NULL), 
        FotoTimbangan2 = IF(LENGTH('$FotoTimbangan2') > 0 , '$FotoTimbangan2', NULL), 
        FotoTimbangan3 = IF(LENGTH('$FotoTimbangan3') > 0 , '$FotoTimbangan3', NULL), 
        FotoTimbangan4 = IF(LENGTH('$FotoTimbangan4') > 0 , '$FotoTimbangan4', NULL), 
        KodeTimbangan = '$KodeTimbangan',
		KodeUkuran = '$KodeUkuran', 
		KodeKelas = '$KodeKelas',  
		UkuranRealTimbangan = '$UkuranRealTimbangan',
		Satuan = '$Satuan'
        WHERE IDTimbangan = '$idtimbangan'";
		//return $sql;
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_timbanganperson($idtimbangan){
        $sql = "DELETE FROM timbanganperson WHERE IDTimbangan = '$idtimbangan'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_fototimbangan($idtimbangan){
        $sql = "SELECT IFNULL(tp.FotoTimbangan1, '') AS FotoTimbangan1, IFNULL(tp.FotoTimbangan2, '') AS FotoTimbangan2, IFNULL(tp.FotoTimbangan3, '') AS FotoTimbangan3, IFNULL(tp.FotoTimbangan4, '') AS FotoTimbangan4 FROM timbanganperson tp WHERE IDTimbangan = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $idtimbangan);
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

    public function generate_idtimbangan(){
        date_default_timezone_set('Asia/Jakarta');
        $tahun = date('Y');
        $sql = "SELECT RIGHT(IDTimbangan,7) AS kode FROM timbanganperson WHERE IDTimbangan LIKE '$tahun%' ORDER BY IDTimbangan DESC LIMIT 1";
        $res = mysqli_query($this->db, $sql);
        $result = mysqli_fetch_array($res);
        if ($result['kode'] == null) {
            $kode = 1;
        } else {
            $kode = ++$result['kode'];
        }
        $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
        return $tahun . '-' . $bikin_kode ;
    }
}
