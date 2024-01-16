<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of MstBarang
     *
     * @author ASM-PC
     */
    class MstBarang {

        private $db;
        private $KodeBarang;
        private $NamaBarang;
        private $Merk;
        private $Satuan;
        private $Keterangan;
        private $KodeGroup;
        private $IsAktif;
        //put your code here
        private $cari;

        public function __construct() {
            $this->db = getcon();
        }

        public function getautocompletebrg() {
            $stmt = false;
            if ($this->getCari()) {
                $sql = "SELECT KodeBarang AS Kode, NamaBarang AS Nama
                        FROM mstbarangpokok 
                        WHERE IsAktif = '1' AND NamaBarang LIKE ?";
                $stmt = $this->db->prepare($sql);
                $pencarian = '%'.$this->getCari().'%';
                $stmt->bind_param('s', $pencarian);
            } else {
                $sql = "SELECT KodeBarang, NamaBarang
                        FROM mstbarangpokok 
                        WHERE IsAktif = '1'";
                $stmt = $this->db->prepare($sql);
            }
            if ($stmt) {
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
            return FALSE;
        }

        function getCari() {
            return $this->cari;
        }

        function setCari($cari) {
            $this->cari = $cari;
        }

        function getKodeBarang() {
            return $this->KodeBarang;
        }

        function getNamaBarang() {
            return $this->NamaBarang;
        }

        function getMerk() {
            return $this->Merk;
        }

        function getSatuan() {
            return $this->Satuan;
        }

        function getKeterangan() {
            return $this->Keterangan;
        }

        function getKodeGroup() {
            return $this->KodeGroup;
        }

        function getIsAktif() {
            return $this->IsAktif;
        }

        function setKodeBarang($KodeBarang) {
            $this->KodeBarang = $KodeBarang;
        }

        function setNamaBarang($NamaBarang) {
            $this->NamaBarang = $NamaBarang;
        }

        function setMerk($Merk) {
            $this->Merk = $Merk;
        }

        function setSatuan($Satuan) {
            $this->Satuan = $Satuan;
        }

        function setKeterangan($Keterangan) {
            $this->Keterangan = $Keterangan;
        }

        function setKodeGroup($KodeGroup) {
            $this->KodeGroup = $KodeGroup;
        }

        function setIsAktif($IsAktif) {
            $this->IsAktif = $IsAktif;
        }

    }
    