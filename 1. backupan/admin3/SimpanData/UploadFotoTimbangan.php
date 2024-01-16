<?php
	include "../../library/config.php";
	$Tahun=date('Y');
	$tgltrans    = date("YmdHis");
	//Post Data
	@$IDPerson 			 = htmlspecialchars($_POST['IDPerson']);
	@$NamaTimbangan		 = htmlspecialchars($_POST['NamaTimbangan']);
	// @$KodeTimbangan		 = htmlspecialchars($_POST['KodeTimbangan']);
	@$KodeLokasi		 = htmlspecialchars($_POST['KodeLokasi']);
	// @$AlamatTimbangan  	 = htmlspecialchars($_POST['AlamatTimbangan']);
	@$UkuranRealTimbangan= htmlspecialchars($_POST['UkuranRealTimbangan']);
	
	@$KodeKec			 = htmlspecialchars($_POST['KodeKec']);
	@$KodeDesa			 = htmlspecialchars($_POST['KodeDesa']);
	@$KodeKab			 = htmlspecialchars($_POST['KodeKab']);
	@$Lat				 = htmlspecialchars($_POST['Lat']);
	@$LngAsli			 = htmlspecialchars($_POST['Lng']);
	@$type				 = htmlspecialchars($_POST['type']);
	@$login_id			 = htmlspecialchars($_POST['login_id']);
	@$nourut			 = htmlspecialchars($_POST['nourut']);
	@$NamaPerson 		 = NamaPerson($koneksi, $IDPerson);
	// @$bagi_uraian 		 = explode("#", $KodeTimbangan);
	@$Timbangan 		 = htmlspecialchars($_POST['KodeTimbangan']);
	@$Kelas 			 = htmlspecialchars($_POST['KodeKelas']);
	@$Ukuran 			 = htmlspecialchars($_POST['KodeUkuran']);
	@$Satuan 			 = htmlspecialchars($_POST['Satuan']);
	@$Merk 				 = htmlspecialchars($_POST['Merk']);
	@$Type 			 	 = htmlspecialchars($_POST['Type']);
	@$Seri 			 	 = htmlspecialchars($_POST['Seri']);
	@$Buatan		 	 = htmlspecialchars($_POST['Buatan']);
	@$Medium		 	 = htmlspecialchars($_POST['Medium']);
	@$Jumlah		 	 = htmlspecialchars($_POST['Jumlah']);
	@$Keterangan		 = $Merk."#".$Type."#".$Seri."#".$Buatan."#".$Medium."#".$Jumlah;
	
	if ($type == 'edit'){
		
		$sql 	 = mysqli_query($koneksi,"SELECT  FotoTimbangan1,FotoTimbangan2,FotoTimbangan3,FotoTimbangan4,NamaTimbangan,IDPerson FROM timbanganperson where IDTimbangan='".base64_decode($_GET['id'])."'");  
		$res	 = mysqli_fetch_array($sql);
		@$Nama	 = NamaPerson($koneksi, $res['IDPerson']);
		
		
		@$file = isset($_FILES['filefoto']) ? $_FILES['filefoto'] : null;
		@$NewImageName1 = $file!=null ? NamaFile($file, base64_decode($_GET['id']), $nourut) : null;
			
		if ($nourut == '1'){
			$query = mysqli_query($koneksi,"UPDATE timbanganperson SET FotoTimbangan1='$NewImageName1' WHERE IDTimbangan='".base64_decode($_GET['id'])."'");
			
			if($query){
				
				@unlink("../../images/Timbangan/".$res['FotoTimbangan1']."");
				@unlink("../../images/Timbangan/thumb_".$res['FotoTimbangan1']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Timbangan User atas nama '.$Nama.' dengan nama timbangan '.$res['NamaTimbangan'], base64_decode($_GET['login']), base64_decode($_GET['id']), 'Timbangan User');
				echo '<script language="javascript">document.location="TimbanganUserEdit.php?id='.$_GET['id'].'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
			}
		}elseif($nourut == '2'){
			
			$query = mysqli_query($koneksi,"UPDATE timbanganperson SET FotoTimbangan2='$NewImageName1' WHERE IDTimbangan='".base64_decode($_GET['id'])."'");
			
			if($query){
				
				@unlink("../../images/Timbangan/".$res['FotoTimbangan2']."");
				@unlink("../../images/Timbangan/thumb_".$res['FotoTimbangan2']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Timbangan User atas nama '.$Nama.' dengan nama timbangan '.$res['NamaTimbangan'], base64_decode($_GET['login']), base64_decode($_GET['id']), 'Timbangan User');
				
				
				echo '<script language="javascript">document.location="../TimbanganUserEdit.php?id='.$_GET['id'].'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
			}
		
		}elseif($nourut == '3') {
			$query = mysqli_query($koneksi,"UPDATE timbanganperson SET FotoTimbangan3='$NewImageName1' WHERE IDTimbangan='".base64_decode($_GET['id'])."'");
			
			if($query){
				
				@unlink("../../images/Timbangan/".$res['FotoTimbangan3']."");
				@unlink("../../images/Timbangan/thumb_".$res['FotoTimbangan3']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Timbangan User atas nama '.$Nama.' dengan nama timbangan '.$res['NamaTimbangan'], base64_decode($_GET['login']), base64_decode($_GET['id']), 'Timbangan User');
				
				
				echo '<script language="javascript">document.location="../TimbanganUserEdit.php?id='.$_GET['id'].'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
			}
			
		}elseif($nourut == '4') {
			$query = mysqli_query($koneksi,"UPDATE timbanganperson SET FotoTimbangan4='$NewImageName1' WHERE IDTimbangan='".base64_decode($_GET['id'])."'");
			
			if($query){
				
				@unlink("../../images/Timbangan/".$res['FotoTimbangan4']."");
				@unlink("../../images/Timbangan/thumb_".$res['FotoTimbangan4']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Timbangan User atas nama '.$Nama.' dengan nama timbangan '.$res['NamaTimbangan'], base64_decode($_GET['login']), base64_decode($_GET['id']), 'Timbangan User');
				
				
				echo '<script language="javascript">document.location="../TimbanganUserEdit.php?id='.$_GET['id'].'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
			}
		}
					
	}else{
		
		//kode jadi
		$sql 	 = mysqli_query($koneksi,'SELECT RIGHT(IDTimbangan,7) AS kode FROM timbanganperson ORDER BY IDTimbangan DESC LIMIT 1');  
		$num	 = mysqli_num_rows($sql);
		if($num <> 0){
			$data = mysqli_fetch_array($sql);
			$kode = $data['kode'] + 1;
		}else{
			$kode = 1;
		}

		//mulai bikin kode
		 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		 $kode_jadi	 = $Tahun."-".$bikin_kode;
	 
		@$file1 = isset($_FILES['filefoto1']) ? $_FILES['filefoto1'] : null;
		@$file2 = isset($_FILES['filefoto2']) ? $_FILES['filefoto2'] : null;
		@$file3 = isset($_FILES['filefoto3']) ? $_FILES['filefoto3'] : null;
		@$file4 = isset($_FILES['filefoto4']) ? $_FILES['filefoto4'] : null;
		
		@$file1 = isset($_FILES['filefoto1']['size'])  && $_FILES['filefoto1']['size'] > 0 ? $_FILES['filefoto1'] : null;
		@$file2 = isset($_FILES['filefoto2']['size'])  && $_FILES['filefoto2']['size'] > 0 ? $_FILES['filefoto2'] : null;
		@$file3 = isset($_FILES['filefoto3']['size'])  && $_FILES['filefoto3']['size'] > 0 ? $_FILES['filefoto3'] : null;
		@$file4 = isset($_FILES['filefoto4']['size'])  && $_FILES['filefoto4']['size'] > 0 ? $_FILES['filefoto4'] : null;
		
		$NewImageName1 = $file1!=null ? NamaFile($file1, $kode_jadi, 1) : null;
		$NewImageName2 = $file2!=null ? NamaFile($file2, $kode_jadi, 2) : null;
		$NewImageName3 = $file3!=null ? NamaFile($file3, $kode_jadi, 3) : null;
		$NewImageName4 = $file4!=null ? NamaFile($file4, $kode_jadi, 4) : null;
	
	
		// // create QR Code
		// //set it to writable location, a place for temp generated PNG files
		$PNG_TEMP_DIR = '../../images/TimbanganQR/';

		// //html PNG location prefix
		// $PNG_WEB_DIR = '../../images/TimbanganQR/';

		// include "../../library/phpqrcode/qrlib.php";    
		// $errorCorrectionLevel = 'H';
		// $matrixPointSize = 6;
		// $matrixData = $kode_jadi;
		
		// //generated
		// $lokasine = $PNG_TEMP_DIR.$kode_jadi.'-'.$tgltrans.'.png';
		$filename = $kode_jadi.'-'.$tgltrans.'.png';
		// QRcode::png($matrixData, $lokasine, $errorCorrectionLevel, $matrixPointSize, 2);
		
		include('../../library/barcode/src/BarcodeGenerator.php');
		include('../../library/barcode/src/BarcodeGeneratorPNG.php');
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();
		file_put_contents('../../images/TimbanganQR/'.$filename, $generatorSVG->getBarcode($Tahun.$bikin_kode, $generatorSVG::TYPE_CODE_128));
		
		
		$latlong = mysqli_query($koneksi,"select KoorLong,KoorLat from lokasimilikperson where KodeLokasi='$KodeLokasi' and IDPerson='$IDPerson'");
		$res = mysqli_fetch_array($latlong);
		
		$cek2 = mysqli_query($koneksi,"select KoorLong from timbanganperson where KoorLong='".$res[0]."'");
		$num2 = mysqli_num_rows($cek2);
		if($num2 > 0 ){
			@$Lng = $res[0]+0.0001;
		}else{
			@$Lng = $res[0];
		}
	
		$query = mysqli_query($koneksi,"INSERT into timbanganperson (IDTimbangan,FotoTimbangan1,FotoTimbangan2,FotoTimbangan3,FotoTimbangan4,KodeLokasi,Keterangan,KodeTimbangan,IDPerson,QRCode,KodeKelas,KodeUkuran,UkuranRealTimbangan,NamaTimbangan,KoorLat,KoorLong,StatusUTTP,TglInput,Satuan) VALUES ('$kode_jadi','$NewImageName1','$NewImageName2','$NewImageName3','$NewImageName4','$KodeLokasi','$Keterangan','$Timbangan','$IDPerson','$filename','$Kelas','$Ukuran','$UkuranRealTimbangan','$NamaTimbangan','".$res[1]."','$Lng','Aktif', NOW(), '$Satuan')");
				
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Timbangan User atas nama '.$NamaPerson.' dengan nama timbangan '.$NamaTimbangan, $login_id, $kode_jadi, 'Timbangan User');
					
			echo '<script language="javascript">document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
		}else{
			echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="../TimbanganUserDetil.php?user='.base64_encode($IDPerson).'"; </script>';
		}
	
	
	}
	 function NamaFile($file, $IDTimbangan, $no){
		$tgl    = date("YmdHis");
		$ThumbSquareSize        = 200; 
		$BigImageMaxSize        = 500;
		$ThumbPrefix            = "thumb_"; 
		$DestinationDirectory   = '../../images/Timbangan/';
		$Quality                = 1000; 		
		
		$ImageName      = str_replace(' ','-',strtolower($file['name'])); 
		$ImageSize      = $file['size']; 
		$TempSrc        = $file['tmp_name']; 
		$ImageType      = $file['type']; 
		$maxsize 		= 1024000 * 2; // maksimal 2 MB (1MB = 1024000 Byte)

		$valid_ext = array('jpg','jpeg','png','gif','bmp');

		$tmp = explode('.', $ImageName);
		$ekstensi = end($tmp);

		if(isset($ImageSize) && $ImageSize <=$maxsize){
			$NewImageName = $IDTimbangan.'-'.$no.'-'.$tgl.'.'.$ekstensi;
		
			if(in_array($ekstensi, $valid_ext) === true){
				move_uploaded_file($TempSrc, $DestinationDirectory.$NewImageName);
				return $NewImageName;
			}else{
				return false;
			}

		}else{
			return false;
		}
	}
	
	
	 function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	