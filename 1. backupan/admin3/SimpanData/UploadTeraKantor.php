<?php
	include "../../library/config.php";
	
	//Post Data
	@$NoTransaksi 	= htmlspecialchars($_POST['NoTransaksi']);
	@$KodeLokasi 	= htmlspecialchars($_POST['KodeLokasi']);
	@$IDPerson	 	= htmlspecialchars($_POST['IDPerson']);
	@$IDTimbangan 	= htmlspecialchars($_POST['IDTimbangan']);
	@$UserName		= htmlspecialchars($_POST['UserName']);
	@$NoUrutTrans	= htmlspecialchars($_POST['NoUrutTrans']);
	@$HasilAction1	= htmlspecialchars($_POST['HasilAction1']);
	@$HasilAction2	= htmlspecialchars($_POST['HasilAction2']);
	@$HasilAction3	= htmlspecialchars($_POST['HasilAction3']);
	@$type			= htmlspecialchars($_POST['type']);
	@$id			= htmlspecialchars(base64_decode($_GET['id']));
	@$urt			= htmlspecialchars(base64_decode($_GET['urt']));
	@$nourut		= htmlspecialchars($_POST['nourut']);
	@$Aksi			= htmlspecialchars($_POST['Aksi']);

	
	
	if ($type == 'edit'){
		
		$sql 	 = mysqli_query($koneksi,"SELECT  FotoAction1,FotoAction2,FotoAction3 FROM trtimbanganitem where NoTransaksi='$id' and NoUrutTrans='$urt'");  
		$res	 = mysqli_fetch_array($sql);
		@$file = isset($_FILES['filefoto']) ? $_FILES['filefoto'] : null;
		@$NewImageName1 = $file!=null ? NamaFile($file, base64_decode($_GET['id']), $nourut) : null;
			
		if ($nourut == '1'){
			$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET FotoAction1='$NewImageName1' WHERE NoTransaksi='$id' and NoUrutTrans='$urt'");
			
			if($query){
				
				@unlink("../../images/TeraTimbangan/".$res['FotoAction1']."");
				@unlink("../../images/TeraTimbangan/thumb_".$res['FotoAction1']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Foto Hasil Sidang Tera', $UserName, $id, 'Transaksi Proses Sidang Tera');
				if ($Aksi == '1'){
					echo '<script language="javascript">document.location="TrSidangTera.php?NoTransaksi='.$id.'";</script>';
				}elseif ($Aksi == 'Edit Tera Dilokasi'){
					echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.$_GET['id'].'";</script>';
				}else{
					echo '<script language="javascript">document.location="TrSidangTera.php?id='.$_GET['id'].'";</script>';
				}
				
			}else{
				echo '<script language="javascript">alert("Edit Data Gagal !"); document.location="TrSidangTera.php"; </script>';
			}
		}elseif($nourut == '2'){
			$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET FotoAction2='$NewImageName1' WHERE NoTransaksi='$id' and NoUrutTrans='$urt'");
			
			if($query){
				
				@unlink("../../images/TeraTimbangan/".$res['FotoAction2']."");
				@unlink("../../images/TeraTimbangan/thumb_".$res['FotoAction2']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Foto Hasil Sidang Tera', $UserName, $id, 'Transaksi Proses Sidang Tera');
				if ($Aksi == '1'){
					echo '<script language="javascript">document.location="../TrSidangTera.php?NoTransaksi='.$id.'";</script>';
				}elseif ($Aksi == 'Edit Tera Dilokasi'){
					echo '<script language="javascript">document.location="../TrTeraDilokasi.php?id='.$_GET['id'].'";</script>';
				}else{
					echo '<script language="javascript">document.location="../TrSidangTera.php?id='.$_GET['id'].'";</script>';
				}
				
			}else{
				echo '<script language="javascript">alert("Edit Data Gagal !"); document.location="../TrSidangTera.php"; </script>';
			}
		
		}elseif($nourut == '3') {
			$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET FotoAction3='$NewImageName1' WHERE NoTransaksi='$id' and NoUrutTrans='$urt'");
			
			if($query){
				
				@unlink("../../images/TeraTimbangan/".$res['FotoAction3']."");
				@unlink("../../images/TeraTimbangan/thumb_".$res['FotoAction3']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Foto Hasil Sidang Tera', $UserName, $id, 'Transaksi Proses Sidang Tera');
				if ($Aksi == '1'){
					echo '<script language="javascript">document.location="../TrSidangTera.php?NoTransaksi='.$id.'";</script>';
				}elseif ($Aksi == 'Edit Tera Dilokasi'){
					echo '<script language="javascript">document.location="../TrTeraDilokasi.php?id='.$_GET['id'].'";</script>';
				}else{
					echo '<script language="javascript">document.location="../TrSidangTera.php?id='.$_GET['id'].'";</script>';
				}
				
			}else{
				echo '<script language="javascript">alert("Edit Data Gagal !"); document.location="../TrSidangTera.php"; </script>';
			}
			
		}		
	}else{
		
		// echo $NoTransaksi." ".$NoUrutTrans;
			 
		@$file1 = isset($_FILES['filefoto1']) ? $_FILES['filefoto1'] : null;
		@$file2 = isset($_FILES['filefoto2']) ? $_FILES['filefoto2'] : null;
		@$file3 = isset($_FILES['filefoto3']) ? $_FILES['filefoto3'] : null;
		
		$NewImageName1 = $file1!=null ? NamaFile($file1, $NoTransaksi, 1) : null;
		$NewImageName2 = $file2!=null ? NamaFile($file2, $NoTransaksi, 2) : null;
		$NewImageName3 = $file3!=null ? NamaFile($file3, $NoTransaksi, 3) : null;
		
		if ($Aksi =='Tera Dilokasi'){
			$AmbilNoUrut=mysqli_query($koneksi,"SELECT MAX(NoUrutTrans) as NoSaatIni FROM trtimbanganitem WHERE NoTransaksi='$NoTransaksi'");
			$Data=mysqli_fetch_assoc($AmbilNoUrut);
			$NoSekarang = $Data['NoSaatIni'];
			$Urutan = $NoSekarang+1;
		
			// $sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDiLokasi FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
			// $res    = mysqli_fetch_array($sql);
			
			$sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDiLokasi,b.UkuranRealTimbangan,a.NilaiBawah,a.RetPenambahanDilokasi,a.NilaiTambah FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
			$res    = mysqli_fetch_array($sql);
			
			if ($res['NilaiBawah'] == '0' AND $res['RetPenambahanDilokasi'] == '0' ) {
				$SimpanData = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,KodeLokasi,HasilAction1,HasilAction2,HasilAction3,FotoAction1,FotoAction2,FotoAction3)VALUES('$NoTransaksi','$Urutan','$IDPerson','$IDTimbangan','$UserName','".$res[0]."','$KodeLokasi','$HasilAction1','$HasilAction2','$HasilAction3','$NewImageName1','$NewImageName2','$NewImageName3')"); 
			}else{
				$Nilai = ($res['UkuranRealTimbangan']-$res['NilaiTambah'])/$res['NilaiBawah'];
				$Penambahan =($Nilai*$res['RetPenambahanDilokasi'])+$res['RetribusiDiLokasi'];
				
				$SimpanData = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,KodeLokasi,HasilAction1,HasilAction2,HasilAction3,FotoAction1,FotoAction2,FotoAction3)VALUES('$NoTransaksi','$Urutan','$IDPerson','$IDTimbangan','$UserName','".$Penambahan."','$KodeLokasi','$HasilAction1','$HasilAction2','$HasilAction3','$NewImageName1','$NewImageName2','$NewImageName3')"); 
			}
			
			if($SimpanData){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Data Item Timbangan ', $UserName, $NoTransaksi, 'Transaksi Tera Di Lokasi');
				echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($NoTransaksi).'";</script>';
			}else{
				echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="TrTeraDilokasi.php"; </script>';
			}
		}else{
				$query = mysqli_query($koneksi,"update trtimbanganitem set HasilAction1='$HasilAction1', HasilAction2='$HasilAction2',HasilAction3='$HasilAction3', FotoAction1='$NewImageName1', FotoAction2='$NewImageName2', FotoAction3='$NewImageName3' where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
							
			if ($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Hasil Sidang Tera Timbangan', $UserName, $NoTransaksi, 'Transaksi Proses Sidang Tera');
				if($Aksi == 'tambah'){
					echo '<script language="javascript">document.location="TrSidangTera.php?id='.base64_encode($NoTransaksi).'"; </script>';
				}else{
					echo '<script language="javascript">document.location="TrSidangTera.php?NoTransaksi='.$NoTransaksi.'"; </script>';
				}	
			}else{
				echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="TrSidangTera.php"; </script>';
			}
		}
			
	}
	 function NamaFile($file, $NoTransaksi, $no){
		$tgl    = date("YmdHis");
		$ThumbSquareSize        = 200; 
		$BigImageMaxSize        = 500;
		$ThumbPrefix            = "thumb_"; 
		$DestinationDirectory   = '../../images/TeraTimbangan/';
		$Quality                = 1000; 

		$ImageName      = str_replace(' ','-',strtolower($file['name'])); 
		$ImageSize      = $file['size']; 
		$TempSrc        = $file['tmp_name']; 
		$ImageType      = $file['type']; 

		switch(strtolower($ImageType)){
			case 'image/png':
			$CreatedImage =  imagecreatefrompng($file['tmp_name']);
			break;
			case 'image/gif':
			$CreatedImage =  imagecreatefromgif($file['tmp_name']);
			break;          
			case 'image/jpeg':
			case 'image/pjpeg':
			$CreatedImage = imagecreatefromjpeg($file['tmp_name']);
			break;
			default:
			die('Unsupported File!'); 
		}
		list($CurWidth,$CurHeight)=getimagesize($TempSrc);
		$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
		$ImageExt = str_replace('.','',$ImageExt);
		$ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);
		$NewImageName = $NoTransaksi.'-'.$no.'-'.$tgl.'.'.$ImageExt;

		$thumb_DestRandImageName    = $DestinationDirectory.$ThumbPrefix.$NewImageName; 
		$DestRandImageName          = $DestinationDirectory.$NewImageName; 
		if(resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType)){
			if(cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$ImageType))
			{
				return $NewImageName;
				
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	 function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType){
		if($CurWidth <= 0 || $CurHeight <= 0) 
		{
			return false;
		}

		$ImageScale         = min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
		$NewWidth           = ceil($ImageScale*$CurWidth);
		$NewHeight          = ceil($ImageScale*$CurHeight);
		$NewCanves          = imagecreatetruecolor($NewWidth, $NewHeight);
		if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
		{
			switch(strtolower($ImageType))
			{
				case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
				case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;          
				case 'image/jpeg':
				case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
				default:
				return false;
			}
			if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
			return true;
		}
	}

	 function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType){
		if($CurWidth <= 0 || $CurHeight <= 0) 
		{
			return false;
		}
		if($CurWidth>$CurHeight)
		{
			$y_offset = 0;
			$x_offset = ($CurWidth - $CurHeight) / 2;
			$square_size    = $CurWidth - ($x_offset * 2);
		}else{
			$x_offset = 0;
			$y_offset = ($CurHeight - $CurWidth) / 2;
			$square_size = $CurHeight - ($y_offset * 2);
		}

		$NewCanves  = imagecreatetruecolor($iSize, $iSize); 
		if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
		{
			switch(strtolower($ImageType))
			{
				case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
				case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;          
				case 'image/jpeg':
				case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
				default:
				return false;
			}
			if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
			return true;
		}
	}
	

	
	