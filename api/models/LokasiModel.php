<?php

class LokasiModel {

    private $db;

    public function __construct() {
        $this->db = getcon();
    }

    public function get_one_lokasi($kodelokasi, $idperson) {
        $sql = "SELECT l.KodeLokasi, l.NamaLokasi, l.JenisLokasi, l.AlamatLokasi, l.KoorLong, l.KoorLat, l.Keterangan, l.IDPerson, l.KodeDesa, l.KodeKec, l.KodeKab, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson
        FROM lokasimilikperson l
        LEFT JOIN mstperson p ON p.IDPerson = l.IDPerson
        LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
        LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
        LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
        WHERE l.KodeLokasi = ? AND l.IDPerson = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $kodelokasi, $idperson);
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

    public function getlokasiperson($idperson, $search = "", $page = 0, $offset = 10) {
        if ($search != "") {
            $sql = "SELECT l.KodeLokasi, l.NamaLokasi, l.JenisLokasi, l.AlamatLokasi, l.KoorLong, l.KoorLat, l.Keterangan, l.IDPerson, l.KodeDesa, l.KodeKec, l.KodeKab, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson
            FROM lokasimilikperson l
            LEFT JOIN mstperson p ON p.IDPerson = l.IDPerson
            LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
            LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
            LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
            WHERE (p.NamaPerson LIKE '%$search%' OR p.PJPerson LIKE '%$search%' OR l.NamaLokasi LIKE '%$search%' OR l.AlamatLokasi LIKE '%$search%' OR des.NamaDesa LIKE '%$search%' OR kec.NamaKecamatan LIKE '%$search%' OR kab.NamaKabupaten LIKE '%$search%') AND l.IDPerson = '$idperson' AND l.IDPerson != 'PRS-2019-0000000'
            ORDER BY l.NamaLokasi ASC
            LIMIT $page, $offset";
        } else {
            $sql = "SELECT l.KodeLokasi, l.NamaLokasi, l.JenisLokasi, l.AlamatLokasi, l.KoorLong, l.KoorLat, l.Keterangan, l.IDPerson, l.KodeDesa, l.KodeKec, l.KodeKab, des.NamaDesa, kec.NamaKecamatan, kab.NamaKabupaten, p.NamaPerson, p.PJPerson
            FROM lokasimilikperson l
            LEFT JOIN mstperson p ON p.IDPerson = l.IDPerson
            LEFT JOIN mstkab kab ON kab.KodeKab = p.KodeKab
            LEFT JOIN mstkec kec ON kec.KodeKec = p.KodeKec
            LEFT JOIN mstdesa des ON des.KodeDesa = p.KodeDesa
            WHERE l.IDPerson = '$idperson' AND l.IDPerson != 'PRS-2019-0000000'
            ORDER BY l.NamaLokasi ASC
            LIMIT $page, $offset";
        }
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

    public function insert_lokasi($kodelokasi, $idperson) {
        $NamaLokasi = $_POST['NamaLokasi'];
        $JenisLokasi = $_POST['JenisLokasi'];
        $AlamatLokasi = $_POST['AlamatLokasi'];
        $KoorLong = $_POST['KoorLong'];
        $KoorLat = $_POST['KoorLat'];
        $Keterangan = $_POST['Keterangan'];
        $KodeDesa = $_POST['KodeDesa'];
        $KodeKec = $_POST['KodeKec'];
        $KodeKab = $_POST['KodeKab'];

        $sql = "INSERT INTO lokasimilikperson (KodeLokasi, NamaLokasi, JenisLokasi, AlamatLokasi, KoorLong, KoorLat, Keterangan, IDPerson, KodeDesa, KodeKec, KodeKab) VALUES ('$kodelokasi', '$NamaLokasi', '$JenisLokasi', '$AlamatLokasi', '$KoorLong', '$KoorLat', '$Keterangan', '$idperson', '$KodeDesa', '$KodeKec', '$KodeKab')";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update_lokasi($kodelokasi, $idperson) {
        $NamaLokasi = $_POST['NamaLokasi'];
        $JenisLokasi = $_POST['JenisLokasi'];
        $AlamatLokasi = $_POST['AlamatLokasi'];
        $KoorLong = $_POST['KoorLong'];
        $KoorLat = $_POST['KoorLat'];
        $Keterangan = $_POST['Keterangan'];
        $KodeDesa = $_POST['KodeDesa'];
        $KodeKec = $_POST['KodeKec'];
        $KodeKab = $_POST['KodeKab'];

        $sql = "UPDATE lokasimilikperson SET
        NamaLokasi = '$NamaLokasi',
        JenisLokasi = '$JenisLokasi',
        AlamatLokasi = '$AlamatLokasi',
        KoorLong = '$KoorLong',
        KoorLat = '$KoorLat',
        Keterangan = '$Keterangan',
        KodeDesa = '$KodeDesa',
        KodeKec = '$KodeKec',
        KodeKab = '$KodeKab'
        WHERE KodeLokasi = '$kodelokasi' AND IDPerson = '$idperson'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_lokasi($kodelokasi, $idperson) {
        $sql = "DELETE FROM lokasimilikperson WHERE KodeLokasi = '$kodelokasi' AND IDPerson = '$idperson'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function generate_kodelokasi($idperson) {
        date_default_timezone_set('Asia/Jakarta');
        $tahun = date('Y');
        $sql = "SELECT RIGHT(KodeLokasi,7) AS kode FROM lokasimilikperson WHERE KodeLokasi LIKE '%$tahun%' AND IDPerson = '$idperson' ORDER BY KodeLokasi DESC LIMIT 1";
        $res = mysqli_query($this->db, $sql);
        $result = mysqli_fetch_array($res);
        if ($result['kode'] == null) {
            $kode = 1;
        } else {
            $kode = ++$result['kode'];
        }
        $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
        return 'LKS-' . $tahun . '-' . $bikin_kode;
    }
}
