<?php

class LaporanHarianhpp
{

    private $db;
    private $KodeBarang;
    private $Tanggal;
    private $HargaBarang;
    private $Keterangan;
    private $UserName;
    private $KodePasar;
    private $IsSincToProv;
    private $UserSinc;
    private $NamaBarang;
    private $HargaProdusen;
    private $Ketersediaan;

    public function __construct()
    {
        $this->db = getcon();
    }

    public function getlaporan($page = 0)
    {
        $stmt = false;
        if ($this->getNamaBarang()) {
            if ($this->getTanggal()) {
                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
                IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, hppsekarang.UserName as TanggalKemarin,
                IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
                IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
                IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
                IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal, now() as Sekarang, IFNULL(hppkemarin.Tanggal, '') AS TanggalWingi
                FROM mstbarangpokok b
                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = DATE_SUB(date(?), INTERVAL 1 DAY)
                ORDER BY r.Tanggal ASC
                ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = date(?)
                ORDER BY r.Tanggal ASC
                ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
                WHERE b.IsAktif = '1' AND b.NamaBarang LIKE ?
                GROUP BY b.KodeBarang
                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC
                ";
                $pencarian = '%' . $this->getNamaBarang() . '%';
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param('sssss', $this->getKodePasar(), $this->getTanggal(), $this->getKodePasar(), $this->getTanggal(), $pencarian);
            } else {
                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
                IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, hppsekarang.UserName as TanggalKemarin,
                IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
                IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
                IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
                IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal, now() as Sekarang, IFNULL(hppkemarin.Tanggal, '') AS TanggalWingi
                FROM mstbarangpokok b
                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? DATE(r.Tanggal) = DATE_SUB(date(?), INTERVAL 1 DAY)
                ORDER BY r.Tanggal ASC
                ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = curdate()
                ORDER BY r.Tanggal ASC
                ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
                WHERE b.IsAktif = '1' AND b.NamaBarang LIKE ?
                GROUP BY b.KodeBarang
                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC
                ";
                $pencarian = '%' . $this->getNamaBarang() . '%';
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param('sss', $this->getKodePasar(), $this->getKodePasar(), $pencarian);
            }
        } else {
            if ($this->getTanggal()) {
                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
                IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, hppsekarang.UserName as TanggalKemarin,
                IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
                IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
                IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
                IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal, now() as Sekarang, IFNULL(hppkemarin.Tanggal, '') AS TanggalWingi
                FROM mstbarangpokok b
                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = DATE_SUB(date(?), INTERVAL 1 DAY)
                ORDER BY r.Tanggal ASC
                ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = date(?)
                ORDER BY r.Tanggal ASC
                ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
                WHERE b.IsAktif = '1'
                GROUP BY b.KodeBarang
                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC
                ";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param('ssss', $this->getKodePasar(), $this->getTanggal(), $this->getKodePasar(), $this->getTanggal());
            } else {
                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, 
                IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, hppsekarang.UserName as TanggalKemarin,
                IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, 
                IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, 
                IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, 
                IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal, now() as Sekarang, IFNULL(hppkemarin.Tanggal, '') AS TanggalWingi
                FROM mstbarangpokok b
                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = DATE_SUB(curdate(), INTERVAL 1 DAY)
                ORDER BY r.Tanggal ASC
                ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
                LEFT JOIN (
                SELECT *
                FROM reporthargaharian r
                WHERE r.KodePasar = ? AND DATE(r.Tanggal) = curdate()
                ORDER BY r.Tanggal ASC
                ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
                WHERE b.IsAktif = '1'
                GROUP BY b.KodeBarang
                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC
                ";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param('ss', $this->getKodePasar(), $this->getKodePasar());
            }
        }
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            //$num_of_rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    array_push($response, $row);
                }
            }
            $stmt->free_result();
            $stmt->close();
            return $response;
        } else {
            return FALSE;
        }
    }

    public function gethistorireport($page = 0)
    {
        if ($this->getKodeBarang()) {
            $sql = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, 
            IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin, IFNULL(hppsekarang.HargaBarang, IFNULL(r.HargaBarang, 0)) AS HargaBarang, ifnull(hppsekarang.Ketersediaan, IFNULL(r.Ketersediaan, 0)) as Ketersediaan, ifnull(hppsekarang.HargaProdusen, IFNULL(r.HargaProdusen, 0)) as HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
            FROM reporthargaharian r
            INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
            INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
            LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
            LEFT JOIN (
            SELECT *
            FROM reporthargaharian k
            ORDER BY k.Tanggal DESC
            ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND 
            DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
            LEFT JOIN (
            SELECT *
            FROM reporthargaharian r1
            WHERE r1.KodePasar = ? AND DATE(r1.Tanggal) = curdate()
            ORDER BY r1.Tanggal ASC
            ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
            WHERE r.KodePasar = ? AND r.KodeBarang = ? AND DATE(r.Tanggal) = curdate()
            GROUP BY hppkemarin.Tanggal
            ORDER BY r.Tanggal DESC
            LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $this->getKodePasar(), $this->getKodePasar(), $this->getKodeBarang());
            if ($stmt->execute()) {
                $response = array();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if ($row != null) {
                        $response = $row;
                    }
                }
                $stmt->free_result();
                $stmt->close();
                return $response;
            } else {
                return FALSE;
            }
        } else {
            $sql = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, 
            IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
            r.HargaBarang, ifnull(r.Ketersediaan, 0) as Ketersediaan, ifnull(r.HargaProdusen, 0) as HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
            FROM reporthargaharian r
            INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
            INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
            LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
            LEFT JOIN (
            SELECT *
            FROM reporthargaharian k
            ORDER BY k.Tanggal DESC
            ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND 
            DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
            WHERE r.UserName = ?
            GROUP BY hppkemarin.Tanggal
            ORDER BY r.Tanggal DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $this->getUserName());
            if ($stmt->execute()) {
                $response = array();
                $result = $stmt->get_result();
                //$num_of_rows = $result->num_rows;
                while ($row = $result->fetch_assoc()) {
                    if ($row != null) {
                        array_push($response, $row);
                    }
                }
                $stmt->free_result();
                $stmt->close();
                return $response;
            } else {
                return FALSE;
            }
        }
    }

    public function cek_sudah_insert()
    {
        $sql = "SELECT r.Tanggal
        FROM reporthargaharian r
        WHERE DATE(r.Tanggal) = CURDATE() AND r.KodeBarang = ? AND r.KodePasar = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $this->getKodeBarang(), $this->getKodePasar());
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            if ($num_of_rows < 1) {
                return FALSE;
            }
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    $response = $row;
                }
            }
            return $response['Tanggal'];
        }
        return FALSE;
    }

    public function cek_jml_report()
    {
        # code...
        $sql = "SELECT IFNULL(COUNT(b.KodeBarang), 0) AS jmlbrg, IFNULL(COUNT(r.KodeBarang), 0) AS jmlreport
        FROM mstbarangpokok b
        LEFT JOIN(
        SELECT *
        FROM `reporthargaharian`
        WHERE DATE(Tanggal) = CURRENT_DATE() AND KodePasar = ?
        GROUP BY KodeBarang) r ON r.KodeBarang = b.KodeBarang";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $this->getKodePasar());
        if ($stmt->execute()) {
            $response = array();
            $result = $stmt->get_result();
            $num_of_rows = $result->num_rows;
            if ($num_of_rows < 1) {
                return FALSE;
            }
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    $response = $row;
                }
            }
            return $response;
        }
        return FALSE;
    }

    public function cek_yg_belum_update()
    {
        /*$sql = "INSERT INTO reporthargaharian(KodeBarang, Tanggal, HargaBarang, Keterangan, UserName, HargaProdusen, Ketersediaan, KodePasar)
        SELECT * FROM (SELECT b.KodeBarang, CURDATE() AS Tanggal,IFNULL(r.HargaBarang,0) As hargabarang,r.Keterangan,IFNULL(r.UserName, ?) AS UserName, IFNULL(r.HargaProdusen,0) As hargaProdusen,IFNULL(r.Ketersediaan,0) as Ketersediaan,IFNULL(r.KodePasar, ?) AS KodePasar 
        FROM mstbarangpokok b 
        LEFT JOIN (SELECT * FROM reporthargaharian WHERE DATE(Tanggal) = DATE(SUBDATE(CURRENT_TIMESTAMP , INTERVAL 1 DAY))) AS r ON b.KodeBarang = r.KodeBarang AND r.UserName = ? AND r.KodePasar = ?) AS tmp
        ON DUPLICATE KEY UPDATE
        HargaBarang = tmp.HargaBarang,
        Keterangan = tmp.Keterangan,
        UserName = tmp.UserName,
        HargaProdusen = tmp.HargaProdusen,
        Ketersediaan = tmp.Ketersediaan";*/
        $sql = "INSERT INTO reporthargaharian(KodeBarang, Tanggal, HargaBarang, Keterangan, UserName, HargaProdusen, Ketersediaan, KodePasar)
        SELECT * FROM (SELECT b.KodeBarang, CURDATE() AS Tanggal, IFNULL(rn.HargaBarang, IFNULL(r.HargaBarang, 0)) AS hargabarang, IFNULL(rn.Keterangan, IFNULL(r.Keterangan, '-')) AS Keterangan, IFNULL(r.UserName, ?) AS UserName, IFNULL(rn.HargaProdusen, IFNULL(r.HargaProdusen, 0)) AS hargaProdusen, IFNULL(rn.Ketersediaan, IFNULL(r.Ketersediaan,0)) as Ketersediaan, IFNULL(r.KodePasar, ?) AS KodePasar 
        FROM mstbarangpokok b 
        LEFT JOIN (SELECT * FROM reporthargaharian WHERE DATE(Tanggal) = CURDATE()) AS rn ON b.KodeBarang = rn.KodeBarang AND rn.UserName = ? AND rn.KodePasar = ?
        LEFT JOIN (SELECT * FROM reporthargaharian WHERE DATE(Tanggal) = DATE(SUBDATE(CURRENT_TIMESTAMP , INTERVAL 1 DAY))) AS r ON b.KodeBarang = r.KodeBarang AND r.UserName = ? AND r.KodePasar = ? GROUP BY b.KodeBarang) AS tmp
        ON DUPLICATE KEY UPDATE
        HargaBarang = tmp.HargaBarang,
        Keterangan = tmp.Keterangan,
        UserName = tmp.UserName,
        HargaProdusen = tmp.HargaProdusen,
        Ketersediaan = tmp.Ketersediaan";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssss', $this->getUserName(), $this->getKodePasar(), $this->getUserName(),  $this->getKodePasar(), $this->getUserName(),  $this->getKodePasar());
        if ($stmt->execute()) {
            return TRUE;
        }
        return FALSE;
    }

    public function update()
    {
        $sql = "UPDATE reporthargaharian SET HargaBarang = ?, Ketersediaan = ?, HargaProdusen = ?
        WHERE KodeBarang = ? AND KodePasar = ? AND Tanggal = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssss', $this->getHargaBarang(), $this->getKetersediaan(), $this->getHargaProdusen(), $this->getKodeBarang(), $this->getKodePasar(), $this->getTanggal());
        if ($stmt->execute()) {
            return TRUE;
        }
        return FALSE;
    }

    public function insert()
    {
        $sql = "INSERT INTO reporthargaharian(KodeBarang, Tanggal, HargaBarang, Keterangan, UserName, KodePasar, Ketersediaan, HargaProdusen) VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'sssssss',
            $this->getKodeBarang(),
            $this->getHargaBarang(),
            $this->getKeterangan(),
            $this->getUserName(),
            $this->getKodePasar(),
            $this->getKetersediaan(),
            $this->getHargaProdusen()
        );
        if ($stmt->execute()) {
            return TRUE;
        }
        return FALSE;
    }

    public function getNamaBarang()
    {
        return $this->NamaBarang;
    }

    public function setNamaBarang($NamaBarang)
    {
        $this->NamaBarang = $NamaBarang;
    }

    public function getKodeBarang()
    {
        return $this->KodeBarang;
    }

    public function setKodeBarang($KodeBarang)
    {
        $this->KodeBarang = $KodeBarang;
    }

    public function getTanggal()
    {
        return $this->Tanggal;
    }

    public function setTanggal($Tanggal)
    {
        $this->Tanggal = $Tanggal;
    }

    public function getHargaBarang()
    {
        return $this->HargaBarang;
    }

    public function setHargaBarang($HargaBarang)
    {
        $this->HargaBarang = $HargaBarang;
    }

    public function getKeterangan()
    {
        return $this->Keterangan;
    }

    public function setKeterangan($Keterangan)
    {
        $this->Keterangan = $Keterangan;
    }

    public function getUserName()
    {
        return $this->UserName;
    }

    public function setUserName($UserName)
    {
        $this->UserName = $UserName;
    }

    public function getKodePasar()
    {
        return $this->KodePasar;
    }

    public function setKodePasar($KodePasar)
    {
        $this->KodePasar = $KodePasar;
    }

    public function getIsSincToProv()
    {
        return $this->IsSincToProv;
    }

    public function setIsSincToProv($IsSincToProv)
    {
        $this->IsSincToProv = $IsSincToProv;
    }

    public function getUserSinc()
    {
        return $this->UserSinc;
    }

    public function setUserSinc($UserSinc)
    {
        $this->UserSinc = $UserSinc;
    }

    public function getHargaProdusen()
    {
        return $this->HargaProdusen;
    }

    public function getKetersediaan()
    {
        return $this->Ketersediaan;
    }

    public function setHargaProdusen($HargaProdusen)
    {
        $this->HargaProdusen = $HargaProdusen;
    }

    public function setKetersediaan($Ketersediaan)
    {
        $this->Ketersediaan = $Ketersediaan;
    }
}
