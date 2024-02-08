<?php 
	include 'akses.php';
	$Tahun=date('Y');
	//select kode pasar
	// $cek_pasar = mysqli_query($koneksi,"SELECT KodeBJ FROM mstpasar WHERE KodeBJ<>''");  
	// $hitung = mysqli_num_rows($cek_pasar);
	$sql = "SELECT KodeBJ FROM mstpasar WHERE KodeBJ <> ''";
	$stmt = $koneksi->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($kodeBJ);
	$hitung = 0;
	while ($stmt->fetch()) {
		$hitung++;
	}
	$stmt->close();
	echo "Jumlah baris yang ditemukan: " . $hitung;
	while($data_cek = mysqli_fetch_array($cek_pasar)){
		$kodepsr = $data_cek['KodeBJ'];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "http://itkp.my.id/rest/api/read/".$kodepsr,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		$result = json_decode($response);
		

		$i=0;
		foreach ($result->Detail as $key => $value) {
			$Nama = $result->Detail[$i]->Nama;
			$Norek = $result->Detail[$i]->Norek;
			$NoQr = $result->Detail[$i]->NoQr;
			$KodePasar = $result->Detail[$i]->KodePasar;
			$Tagihan = $result->Detail[$i]->Tagihan;
			$Keterangan = $result->Detail[$i]->Keterangan;
			
			$sql1 	 = "SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1";
			$stmt = $koneksi->prepare($sql1);
			$stmt->execute();
			$result = $stmt->get_result(); 
			$num1 = $result->fetch_assoc(); 
			// $num1	 = mysqli_num_rows($sql1);
			if($num1 <> 0){
				$data1 = mysqli_fetch_array($sql1);
				$kode1 = $data1['kode1'] + 1;
			}else{
				$kode1 = 1;
			}
			//mulai bikin kode
			$bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
			$kode_jadi2 = "PRS-".$Tahun."-".$bikin_kode1;
			//cek apakah sdh ada
			// $CekData = mysqli_query($koneksi,"SELECT a.NamaPerson, a.IDPerson, b.IDLapak, c.KodePasar FROM mstperson a JOIN lapakperson b ON a.IDPerson = b.IDPerson JOIN mstpasar c ON b.KodePasar=c.KodePasar WHERE a.NamaPerson='$Nama' AND c.KodeBJ='$KodePasar' AND b.QrCode='$NoQr'");
			$sql2 = "SELECT a.NamaPerson, a.IDPerson, b.IDLapak, c.KodePasar FROM mstperson a JOIN lapakperson b ON a.IDPerson = b.IDPerson JOIN mstpasar c ON b.KodePasar=c.KodePasar WHERE a.NamaPerson= ? AND c.KodeBJ= ? AND b.QrCode= ? ";
			$stmt = $koneksi->prepare($sql2);
			$stmt->bind_param("sss", $Nama, $KodePasar, $NoQr);
			$stmt->execute();
			$result = $stmt->get_result();
			$CekData = $result->fetch_assoc();
			$RowCekData = mysqli_fetch_array($CekData);
			$CountData = mysqli_num_rows($CekData);	
			
			if($CountData === 0){
				//Jika Tidak ada insert :
				//Data Person
				// $AddPerson = mysqli_query($koneksi,"INSERT INTO mstperson (IDPerson, NamaPerson, IsPerusahaan, JenisPerson, KlasifikasiUser) VALUES ('$kode_jadi2', '$Nama', '0', '#Pedagang##', 'Perseorangan')");
				$sql3 = "INSERT INTO mstperson (IDPerson, NamaPerson, IsPerusahaan, JenisPerson, KlasifikasiUser) VALUES ('?', '?', '0', '#Pedagang##', 'Perseorangan')";
				$stmt = $koneksi->prepare($sql3);
				$kodejadi = $kode_jadi2;
				$nama = $Nama;
				$stmt->bind_param("ss", $kodejadi, $nama);
				$stmt->execute();
				$result = $stmt->get_result();
				$AddPerson = $result->fetch_assoc();
				if($AddPerson){
					
					//Cek Kode Pasar
					// $KPasar = mysqli_query($koneksi, "SELECT KodePasar FROM mstpasar WHERE KodeBJ='$KodePasar'");
					$sql4 = "SELECT KodePasar FROM mstpasar WHERE KodeBJ= ? ";
					$stmt = $koneksi->prepare($sql4);
					$stmt->bind_param("s", $KodePasar);
					$stmt->execute();
					$result = $stmt->get_result();
					$KPasar = $result->fetch_assoc();
					$data_pasar = mysqli_fetch_array($KPasar);
					$Kode_Pasar= $data_pasar['KodePasar'];
					
					//insert lapak pasar
					// $sql = mysqli_query($koneksi,"SELECT RIGHT(IDLapak,10) AS kode FROM lapakpasar ORDER BY IDLapak DESC LIMIT 1");  
					$sql5 = "SELECT RIGHT(IDLapak,10) AS kode FROM lapakpasar ORDER BY IDLapak DESC LIMIT 1";
					$stmt = $koneksi->prepare($sql5);
					$stmt->execute();
					$result = $stmt->get_result();
					$sql = $result->fetch_assoc();
					$nums = mysqli_num_rows($sql);
					if($nums <> 0){
						$data = mysqli_fetch_array($sql);
						$kode = $data['kode'] + 1;
					}else{
						$kode = 1;
					}
					$bikin_kode_lapak = str_pad($kode, 10, "0", STR_PAD_LEFT);
					$kode_jadi_lapak = "LPK-".$bikin_kode_lapak;
					$Kode_Pasar;
					// $AddLapakPasar = mysqli_query($koneksi,"INSERT INTO lapakpasar (KodePasar,BlokLapak,NomorLapak,Retribusi,Keterangan,IDLapak) VALUES ('$Kode_Pasar','','','0','-','$kode_jadi_lapak')");
					$sql6 = "INSERT INTO lapakpasar (KodePasar,BlokLapak,NomorLapak,Retribusi,Keterangan,IDLapak) VALUES ('?','','','0','-','?')";
					$stmt = $koneksi->prepare($sql6);
					$okegas = $Kode_Pasar;
					$okeloh = $kode_jadi_lapak;
					$stmt-bind_param("ss",$okegas, $okeloh);
					$stmt->execute();
					$result = $stmt->get_result();
					$AddLapakPasar = $result->fetch_assoc();
					if($AddLapakPasar){
						//insert lapak person
						// $AddLapakPerson = mysqli_query($koneksi,"INSERT INTO lapakperson (KodePasar,IDLapak,BlokLapak,NomorLapak,NoRekBank,AnRekBank,Keterangan,IDPerson,Retribusi,IsAktif,TglAktif,QrCode,Tagihan) VALUES ('$Kode_Pasar','$kode_jadi_lapak','','','$Norek','-','$Keterangan','$kode_jadi2','0',b'1',NOW(),'$NoQr', '$Tagihan')");
						$sql7 = "INSERT INTO lapakperson (KodePasar,IDLapak,BlokLapak,NomorLapak,NoRekBank,AnRekBank,Keterangan,IDPerson,Retribusi,IsAktif,TglAktif,QrCode,Tagihan) VALUES ('?','?','','','?','-','?','?','0',b'1',NOW(),'?', '?')";
						$stmt = $koneksi->prepare($sql7);
						$ab = $Kode_Pasar;
						$ac = $kode_jadi_lapak;
						$ad = $Norek;
						$ae = $Keterangan;
						$af = $kode_jadi2;
						$ag = $NoQr;
						$ah = $Tagihan;
						$stmt->bind_param("ssssssd",$ab,$ac,$ad,$ae,$af, $ag, $ah);
						$stmt->execute();
						$result = $stmt->get_result();
						$AddLapakPerson = $result->fetch_assoc();
					}else{
						
					}
				}else{
					
				}
			}else{
				
				$Kode_Pasar = $RowCekData['KodePasar'];
				$Kode_Person = $RowCekData['IDPerson'];
				$Kode_Lapak = $RowCekData['IDLapak'];		
				//Update Data Tagihan
				// $UpdateLapakPerson = mysqli_query($koneksi,"UPDATE lapakperson SET Tagihan = '$Tagihan' WHERE KodePasar = '$Kode_Pasar' AND IDLapak = '$Kode_Lapak' AND IDPerson='$Kode_Person'");
				$sql8 = "UPDATE lapakperson SET Tagihan = '$Tagihan' WHERE KodePasar = ? AND IDLapak = ? AND IDPerson= ? ";
				$stmt = $koneksi->prepare($sql8);
				$stmt->bind_param("ss",$Kode_Pasar, $Kode_Lapak, $Kode_Person);
				$stmt->execute();
				$result = $stmt->get_result();
				$UpdateLapakPerson = $result->fetch_assoc();
			}		
			
			$i++;
		}
		
		echo '<script language="javascript">document.location="MasterLapakPersonBJ.php?KodePasar='.$kodepasar.'"</script>';

		curl_close($curl);
	}							

?>