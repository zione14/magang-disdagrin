<?php

    class LapakPersonModel {

	  //put your code here

	  private $KodePasar;
	  private $IDLapak;

	  public function __construct() {
		$this->db = getcon();
	  }

	  public function getlapakpasar($statuspasar = "Semua Lapak", $page = 0, $limit = 10) {
		if ($statuspasar === "Lapak Tagihan") {
		    $sql = "SELECT l.IDLapak, l.KodePasar, ps.NamaPasar, p.IDPerson, p.NamaPerson, p.PJPerson, p.AlamatLengkapPerson, l.Keterangan AS KetLapak, 
IFNULL(l.Retribusi, 0) AS RetribusiLapak, IFNULL(p.BlokLapak, l.BlokLapak) AS BlokLapak, IFNULL(p.IsAktif, 0) AS IsAktif, 
IFNULL(p.NomorLapak, l.NomorLapak) AS NomorLapak, IFNULL(p.JenisLapak, '') AS JenisLapak, 
p.NoRekBank, p.AnRekBank, IFNULL(p.Retribusi, 0) AS Retribusi, p.Keterangan, p.StatusKepemilikan, IFNULL(p.IsHariIniDibayar, 0) AS IsHariIniDibayar, 
lt.JmlHariAktif, IFNULL(r.JmlHariTerbayar, 0) AS JmlHariTerbayar, IFNULL(lt.JmlHariAktif * l.Retribusi, 0) AS Pembayaran, 
IFNULL(r.JmlRetDibayar, 0) AS JmlRetDibayar, IF(p.IsHariIniDibayar = 1, 
IFNULL((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0), 0), (((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0)) + p.Retribusi)) AS TagihanLapak, 
IFNULL(DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), p.TglAktif) AS TglMulaiTagihan, IF(r1.TglSampaiDibayar >= CURDATE(), DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), 
CURRENT_TIMESTAMP()) AS TglSelesaiTagihan
FROM lapakpasar l
INNER JOIN mstpasar ps ON ps.KodePasar = l.KodePasar
LEFT JOIN (
SELECT lp.*, pr.NamaPerson, pr.PJPerson, pr.AlamatLengkapPerson, IF(r.NoTransRet IS NULL, 0, 1) AS IsHariIniDibayar
FROM lapakperson lp
INNER JOIN mstperson pr ON pr.IDPerson = lp.IDPerson
LEFT JOIN trretribusipasar r ON r.IDPerson = lp.IDPerson AND r.IDLapak = lp.IDLapak AND r.KodePasar = lp.KodePasar AND CURDATE() >= DATE(r.TglMulaiDibayar) AND CURDATE() <= DATE(r.TglSampaiDibayar)
WHERE lp.IsAktif = '1'
GROUP BY lp.KodePasar, lp.IDLapak
) p ON p.IDLapak = l.IDLapak AND p.KodePasar = l.KodePasar
LEFT JOIN(
SELECT l.IDLapak, l.KodePasar, l.IDPerson, DATEDIFF(CURRENT_TIMESTAMP(), l.TglAktif) AS JmlHariAktif
FROM lapakperson l) AS lt ON lt.IDLapak = p.IDLapak AND lt.IDPerson = p.IDPerson AND lt.KodePasar = p.KodePasar
LEFT JOIN (
SELECT r.*, SUM(r.NominalDiterima) AS JmlRetDibayar, SUM(r.JmlHariDibayar) AS JmlHariTerbayar
FROM trretribusipasar r
GROUP BY r.IDLapak, r.IDPerson, r.KodePasar) r ON r.KodePasar = p.KodePasar AND r.IDLapak = p.IDLapak AND r.IDPerson = p.IDPerson AND DATE(r.TglMulaiDibayar) >= DATE(p.TglAktif) AND DATE(r.TglSampaiDibayar) <= CURDATE()
LEFT JOIN(
SELECT r.*
FROM trretribusipasar r
ORDER BY r.TglSampaiDibayar DESC
LIMIT 1) AS r1 ON r1.KodePasar = p.KodePasar AND r1.IDLapak = p.IDLapak AND r1.IDPerson = p.IDPerson
WHERE l.KodePasar = ? AND p.IsHariIniDibayar = '0'
LIMIT ?, ?";
		} else if ($statuspasar === "Lapak Terbayar") {
		    $sql = "SELECT l.IDLapak, l.KodePasar, ps.NamaPasar, p.IDPerson, p.NamaPerson, p.PJPerson, p.AlamatLengkapPerson, l.Keterangan AS KetLapak, 
IFNULL(l.Retribusi, 0) AS RetribusiLapak, IFNULL(p.BlokLapak, l.BlokLapak) AS BlokLapak, IFNULL(p.IsAktif, 0) AS IsAktif, 
IFNULL(p.NomorLapak, l.NomorLapak) AS NomorLapak, IFNULL(p.JenisLapak, '') AS JenisLapak, 
p.NoRekBank, p.AnRekBank, IFNULL(p.Retribusi, 0) AS Retribusi, p.Keterangan, p.StatusKepemilikan, IFNULL(p.IsHariIniDibayar, 0) AS IsHariIniDibayar, 
lt.JmlHariAktif, IFNULL(r.JmlHariTerbayar, 0) AS JmlHariTerbayar, IFNULL(lt.JmlHariAktif * l.Retribusi, 0) AS Pembayaran, 
IFNULL(r.JmlRetDibayar, 0) AS JmlRetDibayar, IF(p.IsHariIniDibayar = 1, 
IFNULL((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0), 0), (((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0)) + p.Retribusi)) AS TagihanLapak, 
IFNULL(DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), p.TglAktif) AS TglMulaiTagihan, IF(r1.TglSampaiDibayar >= CURDATE(), DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), 
CURRENT_TIMESTAMP()) AS TglSelesaiTagihan
FROM lapakpasar l
INNER JOIN mstpasar ps ON ps.KodePasar = l.KodePasar
LEFT JOIN (
SELECT lp.*, pr.NamaPerson, pr.PJPerson, pr.AlamatLengkapPerson, IF(r.NoTransRet IS NULL, 0, 1) AS IsHariIniDibayar
FROM lapakperson lp
INNER JOIN mstperson pr ON pr.IDPerson = lp.IDPerson
LEFT JOIN trretribusipasar r ON r.IDPerson = lp.IDPerson AND r.IDLapak = lp.IDLapak AND r.KodePasar = lp.KodePasar AND CURDATE() >= DATE(r.TglMulaiDibayar) AND CURDATE() <= DATE(r.TglSampaiDibayar)
WHERE lp.IsAktif = '1'
GROUP BY lp.KodePasar, lp.IDLapak
) p ON p.IDLapak = l.IDLapak AND p.KodePasar = l.KodePasar
LEFT JOIN(
SELECT l.IDLapak, l.KodePasar, l.IDPerson, DATEDIFF(CURRENT_TIMESTAMP(), l.TglAktif) AS JmlHariAktif
FROM lapakperson l) AS lt ON lt.IDLapak = p.IDLapak AND lt.IDPerson = p.IDPerson AND lt.KodePasar = p.KodePasar
LEFT JOIN (
SELECT r.*, SUM(r.NominalDiterima) AS JmlRetDibayar, SUM(r.JmlHariDibayar) AS JmlHariTerbayar
FROM trretribusipasar r
GROUP BY r.IDLapak, r.IDPerson, r.KodePasar) r ON r.KodePasar = p.KodePasar AND r.IDLapak = p.IDLapak AND r.IDPerson = p.IDPerson AND DATE(r.TglMulaiDibayar) >= DATE(p.TglAktif) AND DATE(r.TglSampaiDibayar) <= CURDATE()
LEFT JOIN(
SELECT r.*
FROM trretribusipasar r
ORDER BY r.TglSampaiDibayar DESC
LIMIT 1) AS r1 ON r1.KodePasar = p.KodePasar AND r1.IDLapak = p.IDLapak AND r1.IDPerson = p.IDPerson
WHERE l.KodePasar = ? AND p.IsHariIniDibayar = '1'
LIMIT ?, ?";
		} else {
		    $sql = "SELECT l.IDLapak, l.KodePasar, ps.NamaPasar, p.IDPerson, p.NamaPerson, p.PJPerson, p.AlamatLengkapPerson, l.Keterangan AS KetLapak, 
IFNULL(l.Retribusi, 0) AS RetribusiLapak, IFNULL(p.BlokLapak, l.BlokLapak) AS BlokLapak, IFNULL(p.IsAktif, 0) AS IsAktif, 
IFNULL(p.NomorLapak, l.NomorLapak) AS NomorLapak, IFNULL(p.JenisLapak, '') AS JenisLapak, 
p.NoRekBank, p.AnRekBank, IFNULL(p.Retribusi, 0) AS Retribusi, p.Keterangan, p.StatusKepemilikan, IFNULL(p.IsHariIniDibayar, 0) AS IsHariIniDibayar, 
lt.JmlHariAktif, IFNULL(r.JmlHariTerbayar, 0) AS JmlHariTerbayar, IFNULL(lt.JmlHariAktif * l.Retribusi, 0) AS Pembayaran, 
IFNULL(r.JmlRetDibayar, 0) AS JmlRetDibayar, IF(p.IsHariIniDibayar = 1, 
IFNULL((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0), 0), (((lt.JmlHariAktif * l.Retribusi) - IFNULL(r.JmlRetDibayar, 0)) + p.Retribusi)) AS TagihanLapak, 
IFNULL(DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), p.TglAktif) AS TglMulaiTagihan, IF(r1.TglSampaiDibayar >= CURDATE(), DATE_ADD(r1.TglSampaiDibayar, INTERVAL 1 DAY), 
CURRENT_TIMESTAMP()) AS TglSelesaiTagihan
FROM lapakpasar l
INNER JOIN mstpasar ps ON ps.KodePasar = l.KodePasar
LEFT JOIN (
SELECT lp.*, pr.NamaPerson, pr.PJPerson, pr.AlamatLengkapPerson, IF(r.NoTransRet IS NULL, 0, 1) AS IsHariIniDibayar
FROM lapakperson lp
INNER JOIN mstperson pr ON pr.IDPerson = lp.IDPerson
LEFT JOIN trretribusipasar r ON r.IDPerson = lp.IDPerson AND r.IDLapak = lp.IDLapak AND r.KodePasar = lp.KodePasar AND CURDATE() >= DATE(r.TglMulaiDibayar) AND CURDATE() <= DATE(r.TglSampaiDibayar)
WHERE lp.IsAktif = '1'
GROUP BY lp.KodePasar, lp.IDLapak
) p ON p.IDLapak = l.IDLapak AND p.KodePasar = l.KodePasar
LEFT JOIN(
SELECT l.IDLapak, l.KodePasar, l.IDPerson, DATEDIFF(CURRENT_TIMESTAMP(), l.TglAktif) AS JmlHariAktif
FROM lapakperson l) AS lt ON lt.IDLapak = p.IDLapak AND lt.IDPerson = p.IDPerson AND lt.KodePasar = p.KodePasar
LEFT JOIN (
SELECT r.*, SUM(r.NominalDiterima) AS JmlRetDibayar, SUM(r.JmlHariDibayar) AS JmlHariTerbayar
FROM trretribusipasar r
GROUP BY r.IDLapak, r.IDPerson, r.KodePasar) r ON r.KodePasar = p.KodePasar AND r.IDLapak = p.IDLapak AND r.IDPerson = p.IDPerson AND DATE(r.TglMulaiDibayar) >= DATE(p.TglAktif) AND DATE(r.TglSampaiDibayar) <= CURDATE()
LEFT JOIN(
SELECT r.*
FROM trretribusipasar r
ORDER BY r.TglSampaiDibayar DESC
LIMIT 1) AS r1 ON r1.KodePasar = p.KodePasar AND r1.IDLapak = p.IDLapak AND r1.IDPerson = p.IDPerson
WHERE l.KodePasar = ?
LIMIT ?, ?";
		}
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("sii", $this->getKodePasar(), $page, $limit);
		if ($stmt->execute()) {
		    $response = array();
		    $result = $stmt->get_result();
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

	  public function getdetaillapakpasar() {
		$sql = "SELECT l.IDLapak, p.KodePasar, ps.NamaPasar, p.IDPerson, p.NamaPerson, p.PJPerson, p.AlamatLengkapPerson, l.Keterangan AS KetLapak, 
IFNULL(l.Retribusi, 0) AS RetribusiLapak, IFNULL(p.BlokLapak, l.BlokLapak) AS BlokLapak, IFNULL(p.IsAktif, 0) AS IsAktif,
IFNULL(p.NomorLapak, l.NomorLapak) AS NomorLapak, IFNULL(p.JenisLapak, '') AS JenisLapak, 
p.NoRekBank, p.AnRekBank, IFNULL(p.Retribusi, 0) AS Retribusi, p.Keterangan, p.StatusKepemilikan
FROM lapakpasar l
INNER JOIN mstpasar ps ON ps.KodePasar = l.KodePasar
LEFT JOIN (
    SELECT lp.*, pr.NamaPerson, pr.PJPerson, pr.AlamatLengkapPerson
    FROM lapakperson lp
    INNER JOIN mstperson pr ON pr.IDPerson = lp.IDPerson
    WHERE lp.IsAktif = '1'
    GROUP BY lp.KodePasar, lp.IDLapak
) p ON p.IDLapak = l.IDLapak AND p.KodePasar = l.KodePasar
WHERE l.KodePasar = ? AND l.IDLapak = ?;";
		$stmt = $this->db->prepare($sql);
		$stmt->bind_param("ss", $this->getKodePasar(), $this->getIDLapak());
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
	  }

	  function getKodePasar() {
		return $this->KodePasar;
	  }

	  function setKodePasar($KodePasar) {
		$this->KodePasar = $KodePasar;
	  }

	  function getIDLapak() {
		return $this->IDLapak;
	  }

	  function setIDLapak($IDLapak) {
		$this->IDLapak = $IDLapak;
	  }

    }
    