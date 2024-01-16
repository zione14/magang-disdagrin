<?php
	//membuat id konten
	function KodeKonten($JenisKonten, $koneksi){
		$year	 = date('Y');
		$sql = "SELECT RIGHT(KodeKonten,8) AS kode FROM kontenweb WHERE KodeKonten LIKE '%".$year."%' AND JenisKonten='$JenisKonten' ORDER BY KodeKonten DESC LIMIT 1";
		$res = mysqli_query($koneksi, $sql);
		$result = mysqli_fetch_array($res);
		if ($result['kode'] == null) {
			$kode = 1;
		} else {
			$kode = ++$result['kode'];
		}
		$bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
		
		return "KONTEN-".$year."-".$bikin_kode;;
	}

 ?>