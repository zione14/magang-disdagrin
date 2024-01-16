<?php

    class MstPasar {

        private $db;
        private $KodePasar;
        private $NamaPasar;
        private $NamaKepalaPasar;
        private $NoTelpPasar;
        private $KodeDesa;
        private $KodeKec;
        private $KodeKab;
        private $KoorLong;
        private $KoorLat;

        public function __construct() {
            $this->db = getcon();
        }

        public function getpasarsaya($username) {
            # code...
            $sql = "SELECT p.KodePasar, p.NamaPasar, p.NamaKepalaPasar, p.NoTelpPasar, p.KodeDesa, d.NamaDesa, p.KodeKec, c.NamaKecamatan, p.KodeKab, 
 b.NamaKabupaten, 112.221 AS KoorLong, -7.556032627191996 AS KoorLat
FROM mstpasar p
INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab = p.KodeKab
INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
LEFT JOIN userlogin u ON u.KodePasar = p.KodePasar
LEFT JOIN lapakperson lp ON lp.KodePasar = p.KodePasar
LEFT JOIN mstperson mp ON mp.IDPerson = lp.IDPerson
WHERE u.UserName = ? OR mp.IDPerson = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ss", $username, $username);
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
                return FALSE;
            }
        }

        public function getKodePasar() {
            return $this->KodePasar;
        }

        public function setKodePasar($KodePasar) {
            $this->KodePasar = $KodePasar;
        }

        public function getNamaPasar() {
            return $this->NamaPasar;
        }

        public function setNamaPasar($NamaPasar) {
            $this->NamaPasar = $NamaPasar;
        }

        public function getNamaKepalaPasar() {
            return $this->NamaKepalaPasar;
        }

        public function setNamaKepalaPasar($NamaKepalaPasar) {
            $this->NamaKepalaPasar = $NamaKepalaPasar;
        }

        public function getNoTelpPasar() {
            return $this->NoTelpPasar;
        }

        public function setNoTelpPasar($NoTelpPasar) {
            $this->NoTelpPasar = $NoTelpPasar;
        }

        public function getKodeDesa() {
            return $this->KodeDesa;
        }

        public function setKodeDesa($KodeDesa) {
            $this->KodeDesa = $KodeDesa;
        }

        public function getKodeKec() {
            return $this->KodeKec;
        }

        public function setKodeKec($KodeKec) {
            $this->KodeKec = $KodeKec;
        }

        public function getKodeKab() {
            return $this->KodeKab;
        }

        public function setKodeKab($KodeKab) {
            $this->KodeKab = $KodeKab;
        }

        public function getKoorLong() {
            return $this->KoorLong;
        }

        public function setKoorLong($KoorLong) {
            $this->KoorLong = $KoorLong;
        }

        public function getKoorLat() {
            return $this->KoorLat;
        }

        public function setKoorLat($KoorLat) {
            $this->KoorLat = $KoorLat;
        }

    }

?>