<?php
include "../library/config.php";
include '../library/PHPExcel.php';

@$Keterangan	= htmlspecialchars($_POST['Keterangan']);

$periode = "SELECT 	NoTransaksi FROM sirkulasipupuk WHERE Keterangan = ? ";
$count  = $koneksi->prepare($periode);
$count->bind_param('s', $Keterangan);
$count->execute();
$result = $count->get_result();



if(mysqli_num_rows($result) > 1){
	header("Location: ImportLapPupuk.php?d=1");
	die();

}else{

	$random = "file_upload_".rand(11111,99999);
	$namaFile= $_FILES["fileexcel"]["name"];
	$file_basename = substr($namaFile, 0, strrpos($namaFile, '.')); // strip extention
	$file_ext = substr($namaFile, strrpos($namaFile, '.')); // strip name
	$newfilename = $Keterangan.$file_ext;


	$target_file = $newfilename;
	$uploadOk = 1;

	if (move_uploaded_file($_FILES["fileexcel"]["tmp_name"], "../images/Pupuk/$target_file")) {
		ini_set('memory_limit', '-1');
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$inputFileType = 'Excel2007';
		$sheetIndex = 0;
		$inputFileName = "../images/Pupuk/$target_file";
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$sheetnames = $objReader->listWorksheetNames($inputFileName);
		$objReader->setLoadSheetsOnly($sheetnames[$sheetIndex]);
		try {
			$objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
			die('Error loading file :' . $e->getMessage());
		}

		$worksheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$numRows = count($worksheet);
		$hasil = true;
		$KodeKec = '';
		$kodeKec = '';
		$PJPerson = '';
		$pJPerson = '';
		$IDPerson = '';
	    //baca untuk setiap baris excel
		for ($i=10; $i <= $numRows ; $i++) {
			//echo 'A : '.$worksheet[$i]['A'].', B: '.$worksheet[$i]['B'].', C: '.$worksheet[$i]['C'].', D: '.$worksheet[$i]['D'].'<br>';

			if($worksheet[$i]['A']!=null && $worksheet[$i]['A'] != ''){
				$NamaKecamatan = $worksheet[$i]['A'];
				$NamaKecamatan = str_replace('JUMLAH ', '', $NamaKecamatan);
				$NamaKecamatan = str_replace('KEC. ', '', $NamaKecamatan);
				$kodeKec = CekKecamatan($koneksi, $NamaKecamatan);
				if($kodeKec){$KodeKec = $kodeKec;}
				if(!$kodeKec){
					$pJPerson = CekDistributor($koneksi, $worksheet[$i]['A'], $KodeKec, 1, '');
					if($pJPerson){$PJPerson = $pJPerson;}
				}
			}else{
				if($worksheet[$i]['B']!=null && $worksheet[$i]['B'] != ''){
					$IDPerson = CekDistributor($koneksi, $worksheet[$i]['B'], $KodeKec, 0, $PJPerson);

					//STOK AWAL
					if($worksheet[$i]['E']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['E']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000001', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['F']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['F']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000001', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['G']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['G']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000003', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['H']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['H']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000002', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['I']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['I']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000002', $Nominal, '', $Keterangan);	
					}

					//PENEBUSAN
					if($worksheet[$i]['J']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['J']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000001', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['K']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['K']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000001', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['L']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['L']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000003', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['M']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['M']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000002', $Nominal, '', $Keterangan);	
					}
					if($worksheet[$i]['N']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['N']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000002', $Nominal, '', $Keterangan);	
					}

					
					//PENYALURAN
					if($worksheet[$i]['O']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['O']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000001', '', $Nominal, $Keterangan);	
					}
					if($worksheet[$i]['P']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['P']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000001', '', $Nominal, $Keterangan);	
					}
					if($worksheet[$i]['Q']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['Q']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000003', '', $Nominal, $Keterangan);	
					}
					if($worksheet[$i]['R']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['R']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2019-0000002', '', $Nominal, $Keterangan);	
					}
					if($worksheet[$i]['S']!=''){
						$Nominal = str_replace(",",".",$worksheet[$i]['S']);
						InsertSirkulasi($koneksi, $IDPerson, 'PPK-2020-0000002', '', $Nominal, $Keterangan);	
					}
				}
			}
		}
		// unlink($target_file);
	}

	header("Location: ImportLapPupuk.php?s=1");
	die();
}

function CekKecamatan($conn, $nama){
		if (strpos($nama, 'KECAMATAN ') !== false){
			$nama = str_replace("KECAMATAN","","$nama");
		}

		$sql = "SELECT * FROM mstkec WHERE REPLACE(NamaKecamatan, ' ', '') LIKE REPLACE('%$nama%', ' ', '')  AND KodeKab = '3517'";
		$kook = $conn->prepare($sql);
		$kook->execute();
		$res = $kook->get_result();
		if($res){
			if(mysqli_num_rows($res) > 0){
				$row = mysqli_fetch_assoc($res);
				return $row['KodeKec'];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function CekDistributor($conn, $nama, $kodekecamatan, $isperusahaan, $pjperson){
		if (strpos($nama, 'JUMLAH KEC.') !== false){
			
		}else{
			$sql = "SELECT * FROM mstperson WHERE NamaPerson = ? and KodeKec= ? ";
			// $sql = "SELECT * FROM mstperson WHERE NamaPerson = '$nama' and ID_Distributor=$pjperson and KodeKec='$kodekecamatan' ";
			$kita = $conn->prepare($sql);
			$kita->bind_param('ss', $nama, $kodekecamatan);
			$kita->execute();
			$res = $kita->get_result();
			if($res){
				if(mysqli_num_rows($res) < 1){
					$IDPerson = GetIdPerson($conn);
					$NamaPerson = $nama;
					$JenisPerson = '##PupukSub###';
					$IsPerusahaan = $isperusahaan;
					$KodeKec = $kodekecamatan;
					$KodeKab = '3517';
					$UserName = $nama;
					$Password = base64_encode('12345');
					$IsVerified = 1;
					$KlasifikasiUser = $isperusahaan > 0 ? 'Perusahaan' : 'Perorangan';
					$PJPerson = $pjperson;
					$sql_in = "INSERT INTO mstperson (IDPerson, NamaPerson, JenisPerson, IsPerusahaan, KodeKec, KodeKab, UserName, Password, IsVerified, KlasifikasiUser, ID_Distributor) VALUES ('$IDPerson','$NamaPerson','$JenisPerson','$IsPerusahaan','$KodeKec','$KodeKab','$UserName','$Password','$IsVerified','$KlasifikasiUser', IF(length('$PJPerson')>0, '$PJPerson', NULL))";
					$ilam = $conn->prepare($sql_in);
					$ilam->execute();
					$res1 = $ilam->get_result();
					if($res1){
						return $IDPerson;
					}else{
						return false;
					}
				}else{
					$row = mysqli_fetch_assoc($res);
					return $row['IDPerson'];
				}
			}else{
				$IDPerson = GetIdPerson($conn);
				$NamaPerson = $nama;
				$JenisPerson = '##PupukSub###';
				$IsPerusahaan = $isperusahaan;
				$KodeKec = $kodekecamatan;
				$KodeKab = '3517';
				$UserName = $nama;
				$Password = base64_encode('12345');
				$IsVerified = 1;
				$KlasifikasiUser = $isperusahaan > 0 ? 'Perusahaan' : 'Perorangan';
				$PJPerson = $pjperson;
				$sql_in = "INSERT INTO mstperson (IDPerson, NamaPerson, JenisPerson, IsPerusahaan, KodeKec, KodeKab, UserName, Password, IsVerified, KlasifikasiUser, ID_Distributor) VALUES ('$IDPerson','$NamaPerson','$JenisPerson','$IsPerusahaan','$KodeKec','$KodeKab','$UserName','$Password','$IsVerified','$KlasifikasiUser', IF(length('$PJPerson')>0, '$PJPerson', NULL))";
				$lila = $conn->prepare($sql_in);
				$lila->execute();
				$res1 = $lila->get_result();
				if($res1){
					return $IDPerson;
				}else{
					return false;
				}
			}

		}
	}

	function GetIdPerson($conn){
		$Tahun = date('Y');
		$sql1 = 'SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1'; 
		$jola = $conn->prepare($sql1);
		$jola->execute(); 
		$num1 = $jola->get_result();
		if($num1 <> 0){
			$data1 = mysqli_fetch_array($jola);
			$kode1 = $data1['kode1'] + 1;
		}else{
			$kode1 = 1;
		}
		$bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
		return "PRS-".$Tahun."-".$bikin_kode1;
	}

	function InsertSirkulasi($conn, $IDPerson, $KodeBarang, $JumlahMasuk, $JumlahKeluar, $Keterangan){	
		$NoTransaksi = NoTransaksi($conn);
		$sql = "SELECT Harga FROM mstpupuksubsidi WHERE KodeBarang = ? ";
		$okegas = $conn->prepare($sql);
		$okegas->bind_param('s', $KodeBarang);
		$okegas->execute();
		$resultkk = $okegas->get_result();
		$res = mysqli_fetch_array($resultkk);
		$HargaSatuan = $res[0];
		if($JumlahMasuk != ''){
			// $NilaiTransaksi = $HargaSatuan * $JumlahMasuk;
			$NilaiTransaksi = 0;
			$query = mysqli_query($conn, "INSERT INTO sirkulasipupuk (NoTransaksi, TanggalTransaksi, NilaiTransaksi, HargaSatuan, IDPerson, KodeBarang, JumlahMasuk, Keterangan) VALUES ('$NoTransaksi', CURDATE(), '$NilaiTransaksi', '$HargaSatuan', '$IDPerson', '$KodeBarang', '$JumlahMasuk', '$Keterangan')");
		}else{
			// $NilaiTransaksi = $HargaSatuan * $JumlahKeluar;
			$NilaiTransaksi = 0;
			$query = mysqli_query($conn, "INSERT INTO sirkulasipupuk (NoTransaksi, TanggalTransaksi, NilaiTransaksi, HargaSatuan, IDPerson, KodeBarang, JumlahKeluar, Keterangan) VALUES ('$NoTransaksi', CURDATE(), '$NilaiTransaksi', '$HargaSatuan', '$IDPerson', '$KodeBarang', '$JumlahKeluar', '$Keterangan')");
		}
	}

	function NoTransaksi($conn){
		$Tahun = date("YmdHis");
		$sql = @mysqli_query($conn, "SELECT MAX(RIGHT(NoTransaksi,5)) AS kode FROM sirkulasipupuk WHERE  LEFT(NoTransaksi,18)='TRP-$Tahun'"); 
		$nums = @mysqli_num_rows($sql); 
		while($data = @mysqli_fetch_array($sql)){
			if($nums === 0){ $kode = 1; }else{ $kode = $data['kode'] + 1; }
		}
		$bikin_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
		return "TRP-".$Tahun."-".$bikin_kode;
	}
?>