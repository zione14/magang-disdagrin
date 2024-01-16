<?php

    class MstTimbanganModel {

	  private $db;

	  public function __construct() {
		$this->db = getcon();
	  }

	  public function get_onetimbangan($kodetimbangan) {
		$sql = "SELECT KodeTimbangan, NamaTimbangan, JenisTimbangan, Merk, Ukuran, Kapasitas, TahunPembuatan, NamaPabrik, RetribusiDiKantor, RetribusiDiLokasi, IsPunyaKelas
        FROM msttimbangan 
        WHERE KodeTimbangan = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("s", $kodetimbangan);
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

	  public function gettimbangan($search = "", $page = 0, $offset = 10) {
		if ($search != "") {
		    if ($offset == -1) {
			  $sql = "SELECT KodeTimbangan, NamaTimbangan, JenisTimbangan, Merk, Ukuran, Kapasitas, TahunPembuatan, NamaPabrik, RetribusiDiKantor, RetribusiDiLokasi, IsPunyaKelas
            FROM msttimbangan 
            WHERE (NamaTimbangan LIKE '%$search%' OR JenisTimbangan LIKE '%$search%' OR Merk LIKE '%$search%' OR Ukuran LIKE '%$search%' OR Kapasitas LIKE '%$search%' OR TahunPembuatan LIKE '%$search%' OR NamaPabrik LIKE '%$search%') 
            ORDER BY NamaTimbangan ASC";
		    } else {
			  $sql = "SELECT KodeTimbangan, NamaTimbangan, JenisTimbangan, Merk, Ukuran, Kapasitas, TahunPembuatan, NamaPabrik, RetribusiDiKantor, RetribusiDiLokasi, IsPunyaKelas
            FROM msttimbangan 
            WHERE (NamaTimbangan LIKE '%$search%' OR JenisTimbangan LIKE '%$search%' OR Merk LIKE '%$search%' OR Ukuran LIKE '%$search%' OR Kapasitas LIKE '%$search%' OR TahunPembuatan LIKE '%$search%' OR NamaPabrik LIKE '%$search%') 
            ORDER BY NamaTimbangan ASC 
            LIMIT $page, $offset";
		    }
		} else {
		    if ($offset == -1) {
			  $sql = "SELECT KodeTimbangan, NamaTimbangan, JenisTimbangan, Merk, Ukuran, Kapasitas, TahunPembuatan, NamaPabrik, RetribusiDiKantor, RetribusiDiLokasi, IsPunyaKelas
            FROM msttimbangan 
            ORDER BY NamaTimbangan ASC";
		    } else {
			  $sql = "SELECT KodeTimbangan, NamaTimbangan, JenisTimbangan, Merk, Ukuran, Kapasitas, TahunPembuatan, NamaPabrik, RetribusiDiKantor, RetribusiDiLokasi, IsPunyaKelas
            FROM msttimbangan 
            ORDER BY NamaTimbangan ASC 
            LIMIT $page, $offset";
		    }
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

	  public function getkelas($kodetimbangan) {
		$sql = "SELECT KodeKelas, Keterangan, KodeTimbangan, NamaKelas
        FROM kelas 
        WHERE IF(length('$kodetimbangan') > 0, KodeTimbangan = '$kodetimbangan', TRUE)
        ORDER BY KodeKelas ASC";
//echo $sql;exit;
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

	  public function getukuran($kodekelas) {
		$sql = "SELECT KodeUkuran, NamaUkuran, Keterangan, RetribusiDikantor, RetribusiDiLokasi, KodeKelas, NilaiBawah, NilaiAtas, KodeTimbangan, NilaiTambah, RetPenambahanDikantor, RetPenambahanDiLokasi
        FROM detilukuran 
        WHERE IF(length('$kodekelas') > 0, KodeKelas = '$kodekelas', TRUE)
        ORDER BY KodeUkuran ASC";
//echo $sql;exit;
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
    