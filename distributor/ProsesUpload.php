<?php
include "../library/config.php";
$UserName 		= @$_POST['UserName'];
$URLocation 	= @$_POST['URLocation'];



@$file1 = isset($_FILES['ImageFile']) ? $_FILES['ImageFile'] : null;
$NewImageName1 = $file1!=null ? NamaFile($file1, $UserName, 1, $URLocation) : null;


	$sql 	 = mysqli_query($koneksi,"SELECT  GambarPerson FROM mstperson where IDPerson='$UserName'");  
	$res	 = mysqli_fetch_array($sql);
	
	$query = mysqli_query($koneksi,"UPDATE mstperson SET GambarPerson='$NewImageName1' WHERE IDPerson='$UserName'");
	
	if($query){
		@unlink("../images/".$URLocation."/".$res['GambarPerson']."");
		echo '<script language="javascript">document.location="ProfilUser.php";</script>';
	}else{
		echo '<script language="javascript">alert("Unggah Foto Gagal!"); document.location="ProfilUser.php"; </script>';
	}

function NamaFile($file, $UserName, $no, $URLocation){
		$tgl    = date("YmdHis");
		$BigImageMaxSize        = 500;
		$DestinationDirectory   = '../images/'.$URLocation.'/';
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
		$NewImageName = $UserName.'-'.$no.'-'.$tgl.'.'.$ImageExt;

		
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