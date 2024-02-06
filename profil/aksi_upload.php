<?php
include '../library/config.php';
	@$telepon 		= htmlspecialchars($_POST['telepon']);
	@$NIK 			= htmlspecialchars($_POST['NIK']);

	//membuat id user
	$year	 = date('Y');
	$sql 	 = mysqli_query($koneksi,'SELECT RIGHT(KodeTransaksi,7) AS kode FROM trpermohonan WHERE KodeTransaksi LIKE "%'.$year.'%" ORDER BY KodeTransaksi DESC LIMIT 1');  
	$num	 = mysqli_num_rows($sql);
	if($num <> 0) {
		$data = mysqli_fetch_array($sql);
		$kode = $data['kode'] + 1;
	}else{
		$kode = 1;
	}
	 
	//mulai bikin kode
	 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
	 $kode_jadi_kontak	 = "MOHON-".$year."-".$bikin_kode;
	
	$tanggal	 = date('Ymd');
		 
	function random_word($ide = 7){
		$pool = '1234567890abcdefghijkmnpqrstuvwxyz';
		
		$word = '';
		for ($i = 0; $i < $ide; $i++){
			$word .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $word; 
	}
	$random = random_word(7);

if(isset($_POST)){
	############ Edit settings ##############
	$ThumbSquareSize 		= 200; //Thumbnail will be 200x200
	$BigImageMaxSize 		= 500; //Image Maximum height or width
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	$DestinationDirectory	= '../images/Permohonan/'; //specify upload directory ends with / (slash)
	$Quality 				= 100; //jpeg quality
	##########################################
	
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		die();
	}
	
	// check $_FILES['ImageFile'] not empty
	if(!isset($_FILES['ImageFile']) || !is_uploaded_file($_FILES['ImageFile']['tmp_name']))
	{
		die('Upload bermasalah,cek ulang extensi gambar yang di upload!'); // output error when above checks fail.
	}
	
	// Random number will be added after image name
	@$RandomNumber 	= rand(0, 9999999999); 

	$ImageName 		= str_replace(' ','-',strtolower($_FILES['ImageFile']['name'])); //get image name
	$ImageSize 		= $_FILES['ImageFile']['size']; // get original image size
	$TempSrc	 	= $_FILES['ImageFile']['tmp_name']; // Temp name of image file stored in PHP tmp folder
	$ImageType	 	= $_FILES['ImageFile']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.
	
	/* $fp = fopen($TempSrc, 'r'); // open file (read-only, binary)
    $file_content = fread($fp, $ImageSize) or die("Tidak dapat membaca source file"); // read file
    $file_content = mysqli_real_escape_string($file_content) or die("Tidak dapat membaca source file"); // parse image ke string
    fclose($fp); // tuptup file */
	
	//Let's check allowed $ImageType, we use PHP SWITCH statement here
	switch(strtolower($ImageType))
	{
		case 'image/png':
			//Create a new image from file 
			$CreatedImage = @imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
			break;
		case 'application/pdf':
			//Create a new image from file 
			$CreatedImage = @imagecreatefrompng($_FILES['ImageFile']['tmp_name']);
			// break;
		case 'image/gif':
			$CreatedImage = @imagecreatefromgif($_FILES['ImageFile']['tmp_name']);
			break;			
		case 'image/jpeg':
		case 'image/pjpeg':
			$CreatedImage = @imagecreatefromjpeg($_FILES['ImageFile']['tmp_name']);
			break;
		default:
			die('Unsupported File!'); //output error and exit
	}
	
	//PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
	//Get first two values from image, width and height. 
	//list assign svalues to $CurWidth,$CurHeight
	list($CurWidth,$CurHeight)=getimagesize($TempSrc);
	
	//Get file extension from Image name, this will be added after random name
	$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
  	$ImageExt = str_replace('.','',$ImageExt);
	
	//remove extension from filename
	$ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName); 
	
	//Construct a new name with random number and extension.
	//$NewImageName = $ImageName.'-'.$RandomNumber.'.'.$ImageExt;
	$NewImageName = $kode_jadi_kontak.'-'.$tanggal.'.'.$ImageExt;
	
	//set the Destination Image
	$thumb_DestRandImageName 	= $DestinationDirectory.$ThumbPrefix.$NewImageName; //Thumbnail name with destination directory
	$DestRandImageName 			= $DestinationDirectory.$NewImageName; // Image with destination directory
	
	
		$targetfolder = "../images/Permohonan/".basename($NewImageName);
		if(move_uploaded_file($_FILES['ImageFile']['tmp_name'], $targetfolder)) {
		
			$stmt = mysqli_query($koneksi,"INSERT INTO trpermohonan (KodeTransaksi,TglTransaksi,NIK,NoTelp,Dokumen,Status) VALUES ('$kode_jadi_kontak', NOW(),'$NIK','$telepon','$NewImageName','Belum Dibaca')");
				if($stmt){
					
					foreach($_POST['nourut'] as $key => $value){
					$IDTimbangan	= htmlspecialchars($_POST["IDTimbangan"][$key]);

						if($value){
							$query1=mysqli_query($koneksi,"INSERT INTO detiluttp  (KodeTransaksi,NoUrut,IDTimbangan) VALUES('$kode_jadi_kontak','$value','$IDTimbangan')");
						}
							
					} 
					echo '<script language="javascript">alert("Permohonan Tera berhasil di kirim"); document.location="PermohonanTera.php"; </script>';	
				}else{
					echo '<script language="javascript">alert("Data Gagal disimpan"); document.location="PermohonanTera.php"; </script>';	
				}
		}else {
			echo '<script language="javascript">alert("Data Gagal disimpan !"); document.location="PermohonanTera.php"; </script>';
			  // echo "File Gagal di Upload";
			  unlink("../images/Permohonan/$NewImageName");
		}
			
	
	
	
	
	
}





// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//Construct a proportional size of new image
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	
	// Resize Image
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
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}

//This function corps image to create exact square images, no matter what its original size!
function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{	 
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
	if($CurWidth>$CurHeight)
	{
		$y_offset = 0;
		$x_offset = ($CurWidth - $CurHeight) / 2;
		$square_size 	= $CurWidth - ($x_offset * 2);
	}else{
		$x_offset = 0;
		$y_offset = ($CurHeight - $CurWidth) / 2;
		$square_size = $CurHeight - ($y_offset * 2);
	}
	
	$NewCanves 	= imagecreatetruecolor($iSize, $iSize);	
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
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;

	}
	  
}