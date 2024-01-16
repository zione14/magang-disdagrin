	
<?php
	include "../../library/config.php";

	date_default_timezone_set('Asia/Jakarta');
	$DateTime = date('Y-m-d');

	if(isset($_POST['SimpanData'])){
		$KodePasar	  = strip_tags($_POST['KodePasar']);
		$NamaDokumen  = strip_tags($_POST['NamaDokumen']);
		$JenisDokumen = strip_tags($_POST['JenisDokumen']);
		$IDLapak	  = strip_tags($_POST['IDLapak']);
	
		$Dokumen = false;
	    if (!empty($_FILES['Dokumen']['name'])) {
	        $errors = array();
	        $file_name = $_FILES['Dokumen']['name'];
	        $file_size = $_FILES['Dokumen']['size'];
	        $file_tmp  = $_FILES['Dokumen']['tmp_name'];
	        $file_type = $_FILES['Dokumen']['type'];
	        @$file_ext  = strtolower(end(explode('.', $_FILES['Dokumen']['name'])));

	        $extensions = array("jpeg", "jpg", "png", "mp4", "docx", "doc", "pdf", "xls", "xlsx", "ppt", "pptx");

	        if (in_array($file_ext, $extensions) === false) {
	            $errors[] = "Ekstensi tidak diijikan";
	        }

	        if ($file_size > 2097152) {
	            $errors[] = 'Ukuran file harus  2 MB';
	        }

	        $newfilename = $KodePasar.'-'.date('YmdHis').'-1.'.$file_ext;
	        if (empty($errors) == true) {
	            move_uploaded_file($file_tmp, "../../images/Dokumen/Pasar/" . $newfilename);
	            $Dokumen = $newfilename;
	        }
	    }

	    $NoUrut = 1;
	    $sql = mysqli_query($koneksi, "SELECT NoUrut FROM dokumenlapak WHERE KodePasar='$KodePasar' AND IDLapak='$IDLapak' ORDER BY NoUrut DESC LIMIT 1");
	    $nums_ = mysqli_num_rows($sql);
		if($nums_ <> 0) {
	        $d_respon = mysqli_fetch_array($sql);
	        $NoUrut = $d_respon['NoUrut'] + 1;
	    }

	    $sql = "INSERT INTO dokumenlapak(NoUrut, TanggalUpload, JenisDokumen, NamaDokumen, LokasiFile, KodePasar, IDLapak) VALUES ('$NoUrut', '$DateTime', '$JenisDokumen', '$NamaDokumen', '$Dokumen', '$KodePasar', '$IDLapak')";
	    $query = mysqli_query($koneksi, $sql);
	    if($query){
			echo '<script language="javascript">alert("Input data dokumen berhasil !"); document.location="../MasterLapakPasar-dokumen.php?k='.base64_encode($KodePasar).'&l='.base64_encode($IDLapak).'"; </script>';
	    }else{
			echo '<script language="javascript">alert("Input data dokumen gagal !"); document.location="../MasterLapakPasar-dokumen.php?k='.base64_encode($KodePasar).'&l='.base64_encode($IDLapak).'"; </script>';
	    }

	}

	if(base64_decode($_GET['aksi']) == 'Hapus'){

		$KodePasar	  = strip_tags(base64_decode($_GET['k']));
		$LokasiFile	  = strip_tags(base64_decode($_GET['dk']));
		$NoUrut	  	  = strip_tags(base64_decode($_GET['u']));
		$login_id	  = strip_tags(base64_decode($_GET['l']));
		$IDLapak	  = strip_tags(base64_decode($_GET['p']));
		
		$query = mysqli_query($koneksi, "DELETE from dokumenlapak WHERE KodePasar='$KodePasar' AND IDLapak='$IDLapak' AND NoUrut='$NoUrut'");
		if($query){
			unlink("../../images/Dokumen/Pasar/".$LokasiFile."");
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Dokumen Pasar '.$KodePasar, $login_id, $NoUrut, 'Dokumen Pasar');
			echo '<script language="javascript">alert("Hapus data dokumen berhasil !"); document.location="../MasterLapakPasar-dokumen.php?k='.base64_encode($KodePasar).'&l='.base64_encode($IDLapak).'"; </script>';
		}else{
			echo '<script language="javascript">alert("Hapus data dokumen gagal !"); document.location="../MasterLapakPasar-dokumen.php?k='.base64_encode($KodePasar).'&l='.base64_encode($IDLapak).'"; </script>';
		}

	}


	





















































	// $tgltrans    = date("YmdHis");
	// @$Tahun =date("Y");
	// @$NamaPerson			= htmlspecialchars($_POST['NamaPerson']);
	// @$PJPerson				= htmlspecialchars($_POST['PJPerson']);
	// @$JenisPerson			= htmlspecialchars($_POST['JenisPerson']);
	// @$AlamatLengkapPerson	= htmlspecialchars($_POST['AlamatLengkapPerson']);
	// @$Password				= base64_encode('123456');
	// @$IsPerusahaan			= htmlspecialchars($_POST['Perusahaan']);
	// @$KodeKec				= htmlspecialchars($_POST['KodeKec']);
	// @$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
	// @$KodeKab				= htmlspecialchars($_POST['KodeKab']);
	// @$KodeDusun				= htmlspecialchars($_POST['KodeDusun']);
	// @$Lat					= htmlspecialchars($_POST['Lat']);
	// @$Lng					= htmlspecialchars($_POST['Lng']);
	// @$IDPerson				= htmlspecialchars($_POST['IDPerson']);
	// @$type					= htmlspecialchars($_POST['type']);
	// @$login_id				= htmlspecialchars($_POST['login_id']);
	// @$nourut			 	= htmlspecialchars($_POST['nourut']);
	// @$Timbangan				= htmlspecialchars($_POST['Timbangan']);
	// @$Pedagang				= htmlspecialchars($_POST['Pedagang']);
	// @$PKL					= htmlspecialchars($_POST['PKL']);
	// @$NoRekeningBank		= htmlspecialchars($_POST['NoRekeningBank']);
	// @$AnRekBank				= htmlspecialchars($_POST['AnRekBank']);
	// @$NIK					= htmlspecialchars($_POST['NIK']);
	// @$Mode					= htmlspecialchars($_POST['Mode']);
	// @$PupukSub				= htmlspecialchars($_POST['PupukSub']);
	// @$Toko					= htmlspecialchars($_POST['Toko']);
	// @$Industri				= htmlspecialchars($_POST['Industri']);
	
	// if($IsPerusahaan == '1') {
	// 	$Klasifikasi ='Perusahaan';
	// }else{
	// 	$Klasifikasi ='Perorangan';
	// }
	// @$JenisPerson			= $Timbangan."#".$Pedagang."#".$PupukSub."#".$Toko."#".$PKL."#".$Industri;

	// // @$ID_Distributor = $PupukSub == 'PupukSub' ? '1' : '';
			
	// if ($type == 'edit'){
	// 	// echo 'edit';
		
	// 	$sql 	 = mysqli_query($koneksi,"SELECT  FotoKTP,GambarPerson,IDPerson FROM mstperson where IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");  
	// 	$res	 = mysqli_fetch_array($sql);
	// 	@$Nama	 = NamaPerson($koneksi, $res['IDPerson']);
		
		
	// 	@$file = isset($_FILES['filefoto']) ? $_FILES['filefoto'] : null;
	// 	@$NewImageName1 = $file!=null ? NamaFile($file, base64_decode($_GET['id']), $nourut) : null;
			
	// 	if ($nourut == '1'){
	// 		$query = mysqli_query($koneksi,"UPDATE mstperson SET GambarPerson='$NewImageName1' WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
			
	// 		if($query){
	// 			@unlink("../../images/FotoPerson/".$res['GambarPerson']."");
				
	// 			InsertLog($koneksi, 'Edit Data', 'Mengubah Foto User atas nama '.$Nama, base64_decode($_GET['login']), base64_decode($_GET['id']), 'Master User');
	// 			echo '<script language="javascript">document.location="../MasterPersonAdd.php?id='.$_GET['id'].'&v='.$Mode.'";</script>';
	// 		}else{
	// 			echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../MasterPerson.php"; </script>';
	// 		}
	// 	}elseif($nourut == '2'){
			
	// 		$query = mysqli_query($koneksi,"UPDATE mstperson SET FotoKTP='$NewImageName1' WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
			
	// 		if($query){
	// 			@unlink("../../images/FotoPerson/".$res['FotoKTP']."");
				
	// 			InsertLog($koneksi, 'Edit Data', 'Mengubah Foto User atas nama '.$Nama, base64_decode($_GET['login']), base64_decode($_GET['id']), 'Master User');
				
				
	// 			echo '<script language="javascript">document.location="../MasterPersonAdd.php?id='.$_GET['id'].'&v='.$Mode.'";</script>';
	// 		}else{
	// 			echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="MasterPerson.php"; </script>';
	// 		}
		
	// 	}
					
	// }else{
		
	
	// 		$sql = @mysqli_query($koneksi, "SELECT MAX(RIGHT(IDPerson,7)) AS kode FROM mstperson WHERE  LEFT(IDPerson,8)='PRS-$Tahun'"); 
	// 		$num	 = mysqli_num_rows($sql);
	// 		if($num <> 0){
	// 			$data = mysqli_fetch_array($sql);
	// 			$kode = $data['kode'] + 1;
	// 		}else{
	// 			$kode = 1;
	// 		}
	 
	// 		//mulai bikin kode
	// 		 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
	// 		 $kode_jadi	 = "PRS-".$Tahun."-".$bikin_kode;
			 			
	// 		@$file1 = isset($_FILES['filefoto1']['size'])  && $_FILES['filefoto1']['size'] > 0 ? $_FILES['filefoto1'] : null;
	// 		@$file2 = isset($_FILES['filefoto2']['size'])  && $_FILES['filefoto2']['size'] > 0 ? $_FILES['filefoto2'] : null;
	// 		$NewImageName1 = $file1!=null ? NamaFile($file1, $kode_jadi, 1, $Mode) : null;
	// 		$NewImageName2 = $file2!=null ? NamaFile($file2, $kode_jadi, 2, $Mode) : null;
			
			
	// 		if ($IsPerusahaan == '0' ){
	// 			//cek apakah username ada yang sama atau tidak
	// 			$cek2 = mysqli_query($koneksi,"select Username from mstperson where Username='$NIK'");
	// 			$num2 = mysqli_num_rows($cek2);

	// 			if($num2 == 1 ){
	// 				echo '<script language="javascript">alert("NIK sudah ada, Silahkan ganti nik Anda!");document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
					
	// 			}else{
				
	// 				$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,NIK,KodeDusun,KlasifikasiUser) 
	// 				VALUES ('$kode_jadi','$NamaPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$NIK','$KodeDusun','$Klasifikasi')");
					
	// 				if ($query){
	// 					InsertLog($koneksi, 'Tambah Data', 'Menambah Master User atas nama '.$NamaPerson, $login_id, $kode_jadi, 'Master User');
	// 					echo '<script language="javascript">document.location="../MasterPerson.php?v='.$Mode.'";</script>';
	// 				}else{
	// 					echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 				}
				
	// 			}
	// 		}else{
	// 			if ($IDPerson == '' OR $IDPerson == null){
	// 				//cek apakah username ada yang sama atau tidak
	// 				$cek3 = mysqli_query($koneksi,"select Username from mstperson where Username='$NIK'");
	// 				$num3 = mysqli_num_rows($cek3);
	// 				if($num3 == 1 ){
	// 					echo '<script language="javascript">alert("NIK sudah ada, Silahkan ganti nik Anda!");document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 				}else{
										
	// 					$result = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,NIK,KodeDusun,KlasifikasiUser) 
	// 					VALUES ('$kode_jadi','$PJPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password',b'0','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$NIK','$KodeDusun','Perorangan')");
						
	// 					if ($result){
	// 						//kode jadi
	// 						$sql1 	 = mysqli_query($koneksi,'SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1');  
	// 						$num1	 = mysqli_num_rows($sql1);
	// 						if($num1 <> 0){
	// 							$data1 = mysqli_fetch_array($sql1);
	// 							$kode1 = $data1['kode1'] + 1;
	// 						}else{
	// 							$kode1 = 1;
	// 						}
	// 						//mulai bikin kode
	// 						 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
	// 						 $kode_jadi2 = "PRS-".$Tahun."-".$bikin_kode1;		
						
	// 						$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,PJPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,KodeDusun,KlasifikasiUser) 
	// 						VALUES ('$kode_jadi2','$NamaPerson','$kode_jadi','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$KodeDusun','$Klasifikasi')");
							
	// 						if ($query){
	// 							InsertLog($koneksi, 'Tambah Data', 'Menambah Master User atas nama '.$NamaPerson, $login_id, $kode_jadi, 'Master User');
	// 							echo '<script language="javascript">document.location="../MasterPerson.php?v='.$Mode.'";</script>';
	// 						}else{
	// 							echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 						}
						
	// 					}
						
	// 				}
				
	// 			}else{
	// 				$cekuser = mysqli_query($koneksi,"select JenisPerson from mstperson where IDPerson='$IDPerson'");
	// 				$jns = mysqli_fetch_array($cekuser);
	// 				$jperson = explode("#" , $jns['JenisPerson']);
					
	// 				if ($PupukSub == 'PupukSub'){
	// 					$HasilAkhir = $jperson[0]."#".$jperson[1].'#'.$PupukSub."#".$jperson[3]."#".$jperson[4]."#".$jperson[5];
	// 					mysqli_query($koneksi, "UPDATE mstperson set JenisPerson='$HasilAkhir' where IDPerson='$IDPerson' ");
	// 				}elseif($Pedagang == 'Pedagang'){
	// 					$HasilAkhir = $jperson[0]."#".$Pedagang.'#'.$jperson[2]."#".$jperson[3]."#".$jperson[4]."#".$jperson[5];
	// 					mysqli_query($koneksi, "UPDATE mstperson set JenisPerson='$HasilAkhir' where IDPerson='$IDPerson' ");
	// 				}elseif($Toko == 'Toko'){
	// 					$HasilAkhir = $jperson[0]."#".$jperson[1].'#'.$jperson[2]."#".$jperson[3]."#".$Toko."#".$jperson[5];
	// 					mysqli_query($koneksi, "UPDATE mstperson set JenisPerson='$HasilAkhir' where IDPerson='$IDPerson' ");
	// 				}
					
					
				
	// 				$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,PJPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,KodeDusun,KlasifikasiUser) 
	// 				VALUES ('$kode_jadi','$NamaPerson','$IDPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$KodeDusun','$Klasifikasi')");
					
	// 				if ($query){
	// 					InsertLog($koneksi, 'Tambah Data', 'Menambah Master User atas nama '.$NamaPerson, $login_id, $kode_jadi, 'Master User');
	// 					echo '<script language="javascript">document.location="../MasterPerson.php?v='.$Mode.'";</script>';
	// 				}else{
	// 					echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 				}
					
	// 			} 
				
				
	// 		}
			
			
		
		
	
	// }
	//  function NamaFile($file, $IDTimbangan, $no, $Mode){
	// 	$tgl    = date("YmdHis");
	// 	$BigImageMaxSize        = 500;
	// 	$DestinationDirectory   = '../../images/FotoPerson/';
	// 	$Quality                = 1000; 

	// 	$ImageName      = str_replace(' ','-',strtolower($file['name'])); 
	// 	$ImageSize      = $file['size']; 
	// 	$TempSrc        = $file['tmp_name']; 
	// 	$ImageType      = $file['type']; 
	// 	$maxsize 		= 1024000 * 2; // maksimal 2 MB (1MB = 1024000 Byte)

	// 	$valid_ext = array('jpg','jpeg','png','gif','bmp');

	// 	$tmp = explode('.', $ImageName);
	// 	$ekstensi = end($tmp);

	// 	if(isset($ImageSize) && $ImageSize <=$maxsize){
	// 		$NewImageName = $IDTimbangan.'-'.$no.'-'.$tgl.'.'.$ekstensi;
		
	// 		if(in_array($ekstensi, $valid_ext) === true){
	// 			move_uploaded_file($TempSrc, $DestinationDirectory.$NewImageName);
	// 			return $NewImageName;
	// 		}else{
	// 			return false;
	// 			// echo '<script language="javascript">alert("Maaf... file ekstensi hanya .jpg, .jpeg, .png"); document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 			// echo "Maaf... file ekstensi hanya .jpg, .jpeg, .png";
	// 		}

	// 	}else{
	// 		// echo '<script language="javascript">alert("Maaf... file yang ada pilih terlalu besar, maksimal 2 MB..!"); document.location="../MasterPerson.php?v='.$Mode.'"; </script>';
	// 		// echo('Maaf... file yang ada pilih terlalu besar, maksimal 2 MB..!'); 
	// 		return false;
	// 	}
		
		
		
	// }
	
	

		
	//  function NamaPerson($koneksi, $IDPerson){
	// 	$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
	// 	$conn = mysqli_query($koneksi, $query);
	// 	$result = mysqli_fetch_array($conn);
	// 	$NamaPerson = $result['NamaPerson'];
		
	// 	return $NamaPerson;
	//  }
	
	
?>	