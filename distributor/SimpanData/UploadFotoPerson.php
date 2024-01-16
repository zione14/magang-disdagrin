	
<?php
	include "../../library/config.php";
	$tgltrans    = date("YmdHis");
	@$Tahun =date("Y");
	@$NamaPerson			= htmlspecialchars($_POST['NamaPerson']);
	@$PJPerson				= htmlspecialchars($_POST['PJPerson']);
	@$JenisPerson			= htmlspecialchars($_POST['JenisPerson']);
	@$AlamatLengkapPerson	= htmlspecialchars($_POST['AlamatLengkapPerson']);
	@$Password				= base64_encode('123456');
	@$IsPerusahaan			= htmlspecialchars($_POST['Perusahaan']);
	@$KodeKec				= htmlspecialchars($_POST['KodeKec']);
	@$KodeDesa				= htmlspecialchars($_POST['KodeDesa']);
	@$KodeKab				= htmlspecialchars($_POST['KodeKab']);
	@$KodeDusun				= htmlspecialchars($_POST['KodeDusun']);
	@$Lat					= htmlspecialchars($_POST['Lat']);
	@$Lng					= htmlspecialchars($_POST['Lng']);
	@$IDPerson				= htmlspecialchars($_POST['IDPerson']);
	@$type					= htmlspecialchars($_POST['type']);
	@$login_id				= htmlspecialchars($_POST['login_id']);
	@$nourut			 	= htmlspecialchars($_POST['nourut']);
	@$Timbangan				= htmlspecialchars($_POST['Timbangan']);
	@$Pedagang				= htmlspecialchars($_POST['Pedagang']);
	@$PKL					= htmlspecialchars($_POST['PKL']);
	@$Industri				= htmlspecialchars($_POST['Industri']);
	@$NoRekeningBank		= htmlspecialchars($_POST['NoRekeningBank']);
	@$AnRekBank				= htmlspecialchars($_POST['AnRekBank']);
	@$NIK					= htmlspecialchars($_POST['NIK']);
	@$Mode					= htmlspecialchars($_POST['Mode']);
	@$PupukSub				= htmlspecialchars($_POST['PupukSub']);
	@$Toko					= htmlspecialchars($_POST['Toko']);
	@$ID_Distributor		= htmlspecialchars($_POST['ID_Distributor']);
	
	if($IsPerusahaan == '1') {
		$Klasifikasi ='Perusahaan';
	}else{
		$Klasifikasi ='Perorangan';
	}
	@$JenisPerson			= $Timbangan."#".$Pedagang."#".$PupukSub."#".$Toko;
	
	if ($type == 'edit'){
		// echo 'edit';
		
		$sql 	 = mysqli_query($koneksi,"SELECT  FotoKTP,GambarPerson,IDPerson FROM mstperson where IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");  
		$res	 = mysqli_fetch_array($sql);
		@$Nama	 = NamaPerson($koneksi, $res['IDPerson']);
		
		
		@$file = isset($_FILES['filefoto']) ? $_FILES['filefoto'] : null;
		@$NewImageName1 = $file!=null ? NamaFile($file, base64_decode($_GET['id']), $nourut) : null;
			
		if ($nourut == '1'){
			$query = mysqli_query($koneksi,"UPDATE mstperson SET GambarPerson='$NewImageName1' WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
			
			if($query){
				@unlink("../../images/FotoPerson/".$res['GambarPerson']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Foto User atas nama '.$Nama, base64_decode($_GET['login']), base64_decode($_GET['id']), 'Master User');
				echo '<script language="javascript">document.location="../MasterPersonAdd.php?id='.$_GET['id'].'&v='.$Mode.'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="../MasterPerson.php"; </script>';
			}
		}elseif($nourut == '2'){
			
			$query = mysqli_query($koneksi,"UPDATE mstperson SET FotoKTP='$NewImageName1' WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
			
			if($query){
				@unlink("../../images/FotoPerson/".$res['FotoKTP']."");
				
				InsertLog($koneksi, 'Edit Data', 'Mengubah Foto User atas nama '.$Nama, base64_decode($_GET['login']), base64_decode($_GET['id']), 'Master User');
				
				
				echo '<script language="javascript">document.location="../MasterPersonAdd.php?id='.$_GET['id'].'&v='.$Mode.'";</script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="MasterPerson.php"; </script>';
			}
		
		}
					
	}else{
		
			//kode jadi
			
			$sql = @mysqli_query($koneksi, "SELECT MAX(RIGHT(IDPerson,7)) AS kode FROM mstperson WHERE  LEFT(IDPerson,8)='PRS-$Tahun'"); 
			$num	 = mysqli_num_rows($sql);
			if($num <> 0){
				$data = mysqli_fetch_array($sql);
				$kode = $data['kode'] + 1;
			}else{
				$kode = 1;
			}
	 
			//mulai bikin kode
			 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
			 $kode_jadi	 = "PRS-".$Tahun."-".$bikin_kode;
			 			
			@$file1 = isset($_FILES['filefoto1']) ? $_FILES['filefoto1'] : null;
			@$file2 = isset($_FILES['filefoto2']) ? $_FILES['filefoto2'] : null;
			
			$NewImageName1 = $file1!=null ? NamaFile($file1, $kode_jadi, 1) : null;
			$NewImageName2 = $file2!=null ? NamaFile($file2, $kode_jadi, 2) : null;
			
			
			if ($IsPerusahaan == '0' ){
				//cek apakah username ada yang sama atau tidak
				$cek2 = mysqli_query($koneksi,"select Username from mstperson where Username='$NIK'");
				$num2 = mysqli_num_rows($cek2);
				if($num2 == 1 ){
					echo '<script type="text/javascript">
							  sweetAlert({
								title: "Simpan Data Gagal!",
								text: " Username sudah ada, Silahkan ganti username Anda! ",
								type: "error"
							  },
							  function () {
								window.location.href = "MasterPerson.php";
							  });
							  </script>';
				}else{
				
					$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,NIK,KodeDusun,KlasifikasiUser,ID_Distributor) 
					VALUES ('$kode_jadi','$NamaPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$NIK','$KodeDusun','$Klasifikasi','$ID_Distributor')");
				
				}
			}else{
				if ($IDPerson == '' OR $IDPerson == null){
					//cek apakah username ada yang sama atau tidak
					$cek3 = mysqli_query($koneksi,"select Username from mstperson where Username='$NIK'");
					$num3 = mysqli_num_rows($cek3);
					if($num3 == 1 ){
						echo '<script type="text/javascript">
								  sweetAlert({
									title: "Simpan Data Gagal!",
									text: " Username sudah ada, Silahkan ganti username Anda! ",
									type: "error"
								  },
								  function () {
									window.location.href = "MasterPerson.php";
								  });
								  </script>';
					}else{
										
						$result = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,NIK,KodeDusun,KlasifikasiUser,ID_Distributor) 
						VALUES ('$kode_jadi','$PJPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password',b'0','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$NIK','$KodeDusun','Perorangan','$ID_Distributor')");
						
						if ($result){
							//kode jadi
							$sql1 	 = mysqli_query($koneksi,'SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1');  
							$num1	 = mysqli_num_rows($sql1);
							if($num1 <> 0){
								$data1 = mysqli_fetch_array($sql1);
								$kode1 = $data1['kode1'] + 1;
							}else{
								$kode1 = 1;
							}
							//mulai bikin kode
							 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
							 $kode_jadi2 = "PRS-".$Tahun."-".$bikin_kode1;		
						
							$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,PJPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,KodeDusun,KlasifikasiUser,ID_Distributor) 
							VALUES ('$kode_jadi2','$NamaPerson','$kode_jadi','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$KodeDusun','$Klasifikasi','$ID_Distributor')");
						
						}
						
					}
				
				}else{
				
					$query = mysqli_query($koneksi,"INSERT into mstperson (IDPerson,NamaPerson,PJPerson,JenisPerson,AlamatLengkapPerson,UserName,Password,IsPerusahaan,KodeKec,KodeDesa,KodeKab,NamaJalan,KoorLat,KoorLong,IsVerified,GambarPerson,FotoKTP,NoRekeningBank,AnRekBank,KodeDusun,KlasifikasiUser,ID_Distributor) 
					VALUES ('$kode_jadi','$NamaPerson','$IDPerson','$JenisPerson','$AlamatLengkapPerson','$NIK','$Password','$IsPerusahaan','$KodeKec','$KodeDesa','$KodeKab','$AlamatLengkapPerson','$Lat','$Lng',b'1','$NewImageName1','$NewImageName2','$NoRekeningBank','$AnRekBank','$KodeDusun','$Klasifikasi','$ID_Distributor')");
					
				} 
				
				
			}
			
			if ($query){
				InsertLog($koneksi, 'Tambah Data', 'Menambah Master User atas nama '.$NamaPerson, $login_id, $kode_jadi, 'Master User');
				echo '<script language="javascript">document.location="MasterPerson.php?v='.$Mode.'";</script>';
			}else{
				echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="MasterPerson.php?v='.$Mode.'"; </script>';
			}
		
		
	
	}
	 function NamaFile($file, $IDTimbangan, $no){
		$tgl    = date("YmdHis");
		$BigImageMaxSize        = 500;
		$DestinationDirectory   = '../../images/FotoPerson/';
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
		$NewImageName = $IDTimbangan.'-'.$no.'-'.$tgl.'.'.$ImageExt;

		
		$DestRandImageName          = $DestinationDirectory.$NewImageName; 
		
		
		if(resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType)){
			return $NewImageName;
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

		
	 function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	
?>	