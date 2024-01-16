<?php

    class TrRetribusiModel {

	  //put your code here

	  private $IDLapak;
	  private $IDPerson;
	  private $KodePasar;
	  private $UserName;
	  private $NoTransRet;
	  private $JmlHariDibayar;
	  private $TglMulaiDibayar;
	  private $TglSampaiDibayar;
	  private $NominalRetribusi;
	  private $NominalDiterima;
	  private $Keterangan;
	  private $db;

	  public function __construct() {
		$this->db = getcon();
	  }

	  public function createkode() {
		date_default_timezone_set('Asia/Jakarta');
		$year = date('Y-m-d');
		$stryear = '%' . $year . '%';
		$sql = "SELECT RIGHT(NoTransRet, 7) AS kode FROM trretribusipasar WHERE NoTransRet LIKE ? ORDER BY NoTransRet DESC LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("s", $stryear);
		if ($stmt->execute()) {
		    $response = array();
		    $result = $stmt->get_result();
		    $num = $result->num_rows;
		    $kode = 1;
		    if ($num > 0) {
			  $response = array();
			  while ($row = $result->fetch_assoc()) {
				if ($row != null) {
				    $response = $row;
				}
			  }
			  $kode = $response['kode'] + 1;
		    }
		    $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		    return "RET-" . $year . "-" . $bikin_kode;
		} else {
		    return FALSE;
		}
		$num = mysqli_num_rows($sql);
	  }

	  public function gettagihan() {
		$sql = "SELECT l.IDPerson, l.IDLapak, l.KodePasar, l.BlokLapak, lt.JmlHariAktif, IFNULL(r.JmlHariTerbayar, 0) AS JmlHariTerbayar, 
IFNULL(lt.JmlHariAktif * l.Retribusi, 0) AS Pembayaran, IFNULL(r.JmlRetDibayar, 0) AS JmlRetDibayar, 
IF(s.NoTransRet IS NULL, (IFNULL((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0), 0)) + l.Retribusi, IFNULL((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0), 0)) AS TagihanLapak, 
IFNULL(DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), l.TglAktif) AS TglMulaiTagihan, IF(s.NoTransRet IS NULL, 0, 1) AS IsHariIniDibayar, 
IF(r1.TglSampaiDibayar >= CURDATE(), DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), CURRENT_TIMESTAMP()) AS TglSelesaiTagihan, l.Retribusi
		FROM lapakperson l
		LEFT JOIN trretribusipasar s ON s.IDPerson = l.IDPerson AND s.IDLapak = l.IDLapak AND s.KodePasar = l.KodePasar AND CURDATE() >= DATE(s.TglMulaiDibayar) AND CURDATE() <= DATE(s.TglSampaiDibayar)
		LEFT JOIN(
			SELECT l.IDLapak, l.KodePasar, l.IDPerson, DATEDIFF(CURRENT_TIMESTAMP(), l.TglAktif) AS JmlHariAktif
			FROM lapakperson l
		) AS lt ON lt.IDLapak = l.IDLapak AND lt.IDPerson = l.IDPerson AND lt.KodePasar = l.KodePasar
		LEFT JOIN (
			SELECT r.*, SUM(r.NominalDiterima) AS JmlRetDibayar, SUM(r.JmlHariDibayar) AS JmlHariTerbayar
			FROM trretribusipasar r
			GROUP BY r.IDLapak, r.IDPerson, r.KodePasar
		) r ON r.KodePasar = l.KodePasar AND r.IDLapak = l.IDLapak AND r.IDPerson = l.IDPerson AND DATE(r.TglMulaiDibayar) >= DATE(l.TglAktif) AND DATE(r.TglSampaiDibayar) <= CURDATE()
		LEFT JOIN(
		    SELECT r.*
		    FROM trretribusipasar r
		    WHERE r.IDPerson = ? AND r.IDLapak = ? AND r.KodePasar = ?
		    ORDER BY r.TglSampaiDibayar DESC
		    LIMIT 1
		) AS r1 ON r1.KodePasar = l.KodePasar AND r1.IDLapak = l.IDLapak AND r1.IDPerson = l.IDPerson
		WHERE l.IDPerson = ? AND l.IDLapak = ? AND l.KodePasar = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ssssss", $this->getIDPerson(), $this->getIDLapak(), $this->getKodePasar(), $this->getIDPerson(),
			  $this->getIDLapak(), $this->getKodePasar());
		if ($stmt->execute()) {
		    $response = array();
		    $result = $stmt->get_result();
		    //$num_of_rows = $result->num_rows;
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

	  public function insertone() {
		$sql = "INSERT INTO trretribusipasar(NoTransRet, TanggalTrans, JmlHariDibayar, TglMulaiDibayar, TglSampaiDibayar, "
			  . "NominalRetribusi, NominalDiterima, IsTransfer, Keterangan, IDPerson, KodePasar, UserName, IDLapak) VALUES "
			  . "(?, NOW(), ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?)";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("sssssssssss", $this->getNoTransRet(), $this->getJmlHariDibayar(), $this->getTglMulaiDibayar(), $this->getTglSampaiDibayar(),
			  $this->getNominalRetribusi(), $this->getNominalDiterima(), $this->getKeterangan(), $this->getIDPerson(), $this->getKodePasar(),
			  $this->getUserName(), $this->getIDLapak());
		if ($stmt->execute()) {
		    return $stmt->affected_rows;
		} else {
		    return FALSE;
		}
	  }

	  public function gethistori($page = 0) {
		$sql = "SELECT r.*, p.NamaPasar, l.BlokLapak, l.NomorLapak, l.JenisLapak, l.NoRekBank, l.AnRekBank, l.Retribusi, l.StatusKepemilikan
FROM trretribusipasar r
INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
INNER JOIN lapakperson l ON l.KodePasar = r.KodePasar AND l.IDLapak = r.IDLapak AND l.IDPerson = r.IDPerson
WHERE r.IDPerson = ? AND r.IDLapak = ? AND r.KodePasar = ?
ORDER BY r.TanggalTrans DESC
LIMIT ?, ?";
		$stmt = $this->db->prepare($sql);
		$limit = 10;
		$stmt->bind_param("sssii", $this->getIDPerson(), $this->getIDLapak(), $this->getKodePasar(), $page, $limit);
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

	  public function getrekapbulanan($bulan) {
		if ($bulan < 10) {
		    $bulan = '0' . $bulan;
		}
		$TanggalMulai = date('Y-m-d', strtotime('2019-' . $bulan . '-01'));
		$TanggalSelesai = date('Y-m-d', strtotime($TanggalMulai . ' 1 month'));
		$period = new DatePeriod(
			  new DateTime($TanggalMulai),
			  new DateInterval('P1D'),
			  new DateTime(date('Y-m-d', strtotime($TanggalSelesai)))
		);
		$tgl_arr = array();
		foreach ($period as $key => $value) {
		    $valDate = date_format($value, 'Y-m-d');
		    array_push($tgl_arr, $valDate);
		}

		$a = 1;
		$sqlstr = "SELECT lp.IDPerson, p.NamaPerson, lp.IDLapak, lp.KodePasar, lp.BlokLapak, lp.NomorLapak, lp.JenisLapak, lp.Retribusi, lp.Keterangan, ";
		foreach ($tgl_arr as $tgl) {
		    if ($a == sizeof($tgl_arr)) {
			  $sqlstr .= "DATE('" . $tgl . "') >= DATE(r.TglMulaiDibayar) AND DATE('" . $tgl . "') <= DATE(r.TglSampaiDibayar) AS t" . $a;
		    } else {
			  $sqlstr .= "DATE('" . $tgl . "') >= DATE(r.TglMulaiDibayar) AND DATE('" . $tgl . "') <= DATE(r.TglSampaiDibayar) AS t" . $a . ", ";
		    }
		    $a++;
		}
		$sqlstr .= " FROM trretribusipasar r
		    INNER JOIN lapakperson lp on lp.IDPerson = r.IDPerson and lp.IDLapak = r.IDLapak and lp.KodePasar = r.KodePasar
		    INNER JOIN mstperson p on p.IDPerson = r.IDPerson
		    WHERE r.KodePasar = ?
		    GROUP BY r.IDPerson, r.IDLapak
		    ORDER BY r.TglSampaiDibayar DESC";
		$stmt = $this->db->prepare($sqlstr);
		$limit = 10;
		$stmt->bind_param("s", $this->getKodePasar());
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

	  public function getIDLapak() {
		return $this->IDLapak;
	  }

	  public function getIDPerson() {
		return $this->IDPerson;
	  }

	  public function getKodePasar() {
		return $this->KodePasar;
	  }

	  public function getUserName() {
		return $this->UserName;
	  }

	  public function getNoTransRet() {
		return $this->NoTransRet;
	  }

	  public function setIDLapak($IDLapak) {
		$this->IDLapak = $IDLapak;
	  }

	  public function setIDPerson($IDPerson) {
		$this->IDPerson = $IDPerson;
	  }

	  public function setKodePasar($KodePasar) {
		$this->KodePasar = $KodePasar;
	  }

	  public function setUserName($UserName) {
		$this->UserName = $UserName;
	  }

	  public function setNoTransRet($NoTransRet) {
		$this->NoTransRet = $NoTransRet;
	  }

	  function getJmlHariDibayar() {
		return $this->JmlHariDibayar;
	  }

	  function getTglMulaiDibayar() {
		return $this->TglMulaiDibayar;
	  }

	  function getTglSampaiDibayar() {
		return $this->TglSampaiDibayar;
	  }

	  function getNominalRetribusi() {
		return $this->NominalRetribusi;
	  }

	  function getNominalDiterima() {
		return $this->NominalDiterima;
	  }

	  function getKeterangan() {
		return $this->Keterangan;
	  }

	  function setJmlHariDibayar($JmlHariDibayar) {
		$this->JmlHariDibayar = $JmlHariDibayar;
	  }

	  function setTglMulaiDibayar($TglMulaiDibayar) {
		$this->TglMulaiDibayar = $TglMulaiDibayar;
	  }

	  function setTglSampaiDibayar($TglSampaiDibayar) {
		$this->TglSampaiDibayar = $TglSampaiDibayar;
	  }

	  function setNominalRetribusi($NominalRetribusi) {
		$this->NominalRetribusi = $NominalRetribusi;
	  }

	  function setNominalDiterima($NominalDiterima) {
		$this->NominalDiterima = $NominalDiterima;
	  }

	  function setKeterangan($Keterangan) {
		$this->Keterangan = $Keterangan;
	  }

    }
    