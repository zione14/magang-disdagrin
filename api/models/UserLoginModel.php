<?php

class UserLoginModel {

    private $db;

    public function __construct() {
        $this->db = getcon();
    }

    public function get_login_petugas($username, $password, $jenislogin = '') {
        $sql = "SELECT IFNULL(UserName, '') AS UserName, IFNULL(NamaPegawai, '') AS NamaPegawai, IFNULL(NIP, '') AS NIP, IFNULL(Jabatan, '') AS Jabatan, 
            IFNULL(UserPsw, '') AS UserPsw, IFNULL(ActualName, '') AS ActualName, IFNULL(Address, '') AS Address, IFNULL(Phone, '') AS Phone, 
            IFNULL(HPNo, '') AS HPNo, IFNULL(Email, '') AS Email, IFNULL(UserStatus,0) AS UserStatus, IFNULL(LevelID, 0) AS LevelID, 
            IFNULL(IsAktif, 0) AS IsAktif, JenisLogin, p.KodePasar, p.NamaPasar
        FROM userlogin u
        LEFT JOIN mstpasar p on p.KodePasar = u.KodePasar
        WHERE UserName = ? AND UserPsw = ? AND IF(length('$jenislogin') > 0, JenisLogin = '$jenislogin', TRUE)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
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
    
    public function get_login_pengguna($username, $password) {
        $sql = "SELECT p.IDPerson, p.NamaPerson, p.NoRekeningBank, p.AnRekBank, p.PJPerson, p.JenisPerson, IFNULL(p.IsPerusahaan, 0) AS IsPerusahaan, 
            p.AlamatLengkapPerson, p.KodeDesa, d.NamaDesa, p.KodeKec, c.NamaKecamatan, p.KodeKab, k.NamaKabupaten, p.NamaJalan, IFNULL(p.KoorLong, 0) as KoorLong, 
            IFNULL(p.KoorLat, 0) as KoorLat, p.GambarPerson, p.Keterangan, p.UserName, p.`Password`, p.FotoKTP, IFNULL(p.IsVerified, 0) AS IsVerified
            FROM mstperson p
            LEFT JOIN mstkab k ON k.KodeKab = p.KodeKab
            LEFT JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab = p.KodeKab
            LEFT JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
            WHERE p.UserName = ? AND p.`Password` = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
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

    public function ubah_pass($username, $password, $password_lama)
    {
        # code...
        $sql = "UPDATE userlogin
        SET
            UserPsw = ?
        WHERE UserName = ? AND UserPsw = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('sss', base64_encode($password), $username, base64_encode($password_lama));
        if ($stmt->execute()) {
            return TRUE;
        }
        return FALSE;
    }
}