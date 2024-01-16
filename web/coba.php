<?php
	
	include "../library/config.php";
	
	$tgltrans    = date("YmdHis");

	
	$timbangan = mysqli_query($koneksi, "Select IDTimbangan from timbanganperson WHERE IDTimbangan BETWEEN '2020-0000001' AND '2020-0000800'");
	while($data = mysqli_fetch_assoc($timbangan)){
		
		
		$idtimbangan = $data['IDTimbangan'];
		include('../library/barcode/src/BarcodeGenerator.php');
		include('../library/barcode/src/BarcodeGeneratorPNG.php');
		$fileqrcode = $idtimbangan.'-'.$tgltrans.'.png';
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();
		$Tahun = date('Y');
		file_put_contents('../images/TimbanganQR/'.$fileqrcode, $generatorSVG->getBarcode($idtimbangan, $generatorSVG::TYPE_CODE_128));
		
		
		$update=mysqli_query($koneksi, "Update timbanganperson set QRCode='$fileqrcode' where IDTimbangan='".$idtimbangan."'");

		if($update){
				echo 'bener gaes';
		}else{
				echo 'gagal gaes';
		}
		
		
	}