<?php

class WilayahModel
{

    private $db;

    public function __construct()
    {
        $this->db = getcon();
    }

    public function getkecamatan($kodekab)
    {

        $sql = "SELECT * FROM mstkec 
        WHERE KodeKab = '$kodekab'
        ORDER BY NamaKecamatan ASC";
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

    public function getdesa($kodekec)
    {

        $sql = "SELECT * FROM mstdesa 
        WHERE KodeKec = '$kodekec'
        ORDER BY NamaDesa ASC";
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
	
	public function getdusun($kodedesa)
    {

        $sql = "SELECT * FROM mstdusun 
        WHERE KodeDesa = '$kodedesa'
        ORDER BY NamaDusun ASC";
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
