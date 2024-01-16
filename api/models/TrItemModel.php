<?php

class TrItemModel
{

    private $db;

    public function __construct()
    {
        $this->db = getcon();
    }

    public function get_one_item($notransaksi, $nouruttrans){
        $sql = "SELECT it.NoUrutTrans, it.NoTransaksi, it.NominalRetribusi, it.HasilAction1, it.HasilAction2, it.HasilAction3, it.HasilAction4, it.HasilAction5, it.FotoAction1, it.FotoAction2, it.FotoAction3, it.FotoAction4, it.FotoAction5, it.Keterangan, it.UserName, it.IDTimbangan, it.KodeLokasi, it.IDPerson, tr.TglAmbil, tr.TglTransaksi, tr.JenisTransaksi, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson, t.NamaTimbangan, t.AlamatTimbangan
        FROM trtimbanganitem it
        INNER JOIN tractiontimbangan tr ON tr.NoTransaksi = it.NoTransaksi
        LEFT JOIN timbanganperson t ON t.IDTimbangan = it.IDTimbangan
        LEFT JOIN lokasimilikperson l ON l.KodeLokasi = it.KodeLokasi AND l.IDPerson = it.IDPerson
        LEFT JOIN mstperson p ON p.IDPerson = it.IDPerson
        WHERE it.NoTransaksi = ? AND it.NoUrutTrans = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $notransaksi, $nouruttrans);
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

    public function gettritem($notransaksi, $idtimbangan, $search = "", $page = 0, $offset = 10)
    {
        if($search != ""){            
            $sql = "SELECT it.NoUrutTrans, it.NoTransaksi, it.NominalRetribusi, it.HasilAction1, it.HasilAction2, it.HasilAction3, it.HasilAction4, it.HasilAction5, it.FotoAction1, it.FotoAction2, it.FotoAction3, it.FotoAction4, it.FotoAction5, it.Keterangan, it.UserName, it.IDTimbangan, it.KodeLokasi, it.IDPerson, tr.TglAmbil, tr.TglTransaksi, tr.JenisTransaksi, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson, t.NamaTimbangan, t.AlamatTimbangan, tr.NoSKRD, IFNULL(tr.TotalRetribusi, 0) AS TotalRetribusi
            FROM trtimbanganitem it
            INNER JOIN tractiontimbangan tr ON tr.NoTransaksi = it.NoTransaksi
            LEFT JOIN timbanganperson t ON t.IDTimbangan = it.IDTimbangan
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = it.KodeLokasi AND l.IDPerson = it.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = it.IDPerson
            WHERE IF(length('$notransaksi') > 0, it.NoTransaksi = '$notransaksi', TRUE) AND IF(length('$idtimbangan') > 0, it.IDTimbangan = '$idtimbangan', TRUE) AND (tr.JenisTransaksi LIKE '%$search%' OR l.NamaLokasi LIKE '%$search%') 
            ORDER BY tr.TglTransaksi DESC 
            LIMIT $page, $offset";
        }else{
            $sql = "SELECT it.NoUrutTrans, it.NoTransaksi, it.NominalRetribusi, it.HasilAction1, it.HasilAction2, it.HasilAction3, it.HasilAction4, it.HasilAction5, it.FotoAction1, it.FotoAction2, it.FotoAction3, it.FotoAction4, it.FotoAction5, it.Keterangan, it.UserName, it.IDTimbangan, it.KodeLokasi, it.IDPerson, tr.TglAmbil, tr.TglTransaksi, tr.JenisTransaksi, l.NamaLokasi, l.AlamatLokasi, p.NamaPerson, p.PJPerson, t.NamaTimbangan, t.AlamatTimbangan, tr.NoSKRD, IFNULL(tr.TotalRetribusi, 0) AS TotalRetribusi
            FROM trtimbanganitem it
            INNER JOIN tractiontimbangan tr ON tr.NoTransaksi = it.NoTransaksi
            LEFT JOIN timbanganperson t ON t.IDTimbangan = it.IDTimbangan
            LEFT JOIN lokasimilikperson l ON l.KodeLokasi = it.KodeLokasi AND l.IDPerson = it.IDPerson
            LEFT JOIN mstperson p ON p.IDPerson = it.IDPerson
            WHERE IF(length('$notransaksi') > 0, it.NoTransaksi = '$notransaksi', TRUE) AND IF(length('$idtimbangan') > 0, it.IDTimbangan = '$idtimbangan', TRUE)
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
