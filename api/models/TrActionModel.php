<?php

class TrActionModel
{

    private $db;

    public function __construct()
    {
        $this->db = getcon();
    }

    public function get_one_tr($notransaksi){
        $sql = "SELECT tr.NoTransaksi, tr.TglTransaksi, tr.JenisTransaksi, tr.Keterangan, tr.TotalRetribusi, tr.NoSKRD, tr.IsDiambil, tr.TglAmbil, tr.IsDibayar, tr.NoRefAmbil, tr.KeteranganAmbil, tr.TglDibayar, tr.NoRefDibayar, tr.KeteranganBayar, tr.IsDitera, tr.TglTera, tr.NoRefTera, tr.KeteranganTera, tr.StatusTransaksi, tr.UserTerima, tr.KodeLokasi, tr.IDPerson, tr.UserBayar, tr.UserTera, tr.UserAmbil, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson
        FROM tractiontimbangan tr
        LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tr.KodeLokasi AND l.IDPerson = tr.IDPerson
        LEFT JOIN mstperson p ON p.IDPerson = tr.IDPerson
        WHERE tr.NoTransaksi = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $notransaksi);
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

    public function gettraction($idperson, $kodelokasi, $search = "", $page = 0, $offset = 10)
    {
        if($search != ""){            
            $sql = "SELECT tr.NoTransaksi, tr.TglTransaksi, tr.JenisTransaksi, tr.Keterangan, tr.TotalRetribusi, tr.NoSKRD, tr.IsDiambil, tr.TglAmbil, tr.IsDibayar, tr.NoRefAmbil, tr.KeteranganAmbil, tr.TglDibayar, tr.NoRefDibayar, tr.KeteranganBayar, tr.IsDitera, tr.TglTera, tr.NoRefTera, tr.KeteranganTera, tr.StatusTransaksi, tr.UserTerima, tr.KodeLokasi, tr.IDPerson, tr.UserBayar, tr.UserTera, tr.UserAmbil, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson
            FROM tractiontimbangan tr
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tr.KodeLokasi AND l.IDPerson = tr.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = tr.IDPerson
            WHERE tr.IDPerson = '$idperson' AND IF(length('$kodelokasi') > 0, tr.KodeLokasi = '$kodelokasi', TRUE) AND (tr.JenisTransaksi LIKE '%$search%' OR l.NamaLokasi LIKE '%$search%') 
            ORDER BY tr.TglTransaksi DESC 
            LIMIT $page, $offset";
        }else{
            $sql = "SELECT tr.NoTransaksi, tr.TglTransaksi, tr.JenisTransaksi, tr.Keterangan, tr.TotalRetribusi, tr.NoSKRD, tr.IsDiambil, tr.TglAmbil, tr.IsDibayar, tr.NoRefAmbil, tr.KeteranganAmbil, tr.TglDibayar, tr.NoRefDibayar, tr.KeteranganBayar, tr.IsDitera, tr.TglTera, tr.NoRefTera, tr.KeteranganTera, tr.StatusTransaksi, tr.UserTerima, tr.KodeLokasi, tr.IDPerson, tr.UserBayar, tr.UserTera, tr.UserAmbil, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson
            FROM tractiontimbangan tr
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = tr.KodeLokasi AND l.IDPerson = tr.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = tr.IDPerson
            WHERE tr.IDPerson = '$idperson' AND IF(length('$kodelokasi') > 0, tr.KodeLokasi = '$kodelokasi', TRUE)
            ORDER BY tr.TglTransaksi DESC 
            LIMIT $page, $offset";
        }
//	  echo $sql;exit;
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
}
