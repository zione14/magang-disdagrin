<?php
@$Keterangan	= htmlspecialchars($_GET['Keterangan']);

if(isset($Keterangan)){
	$back_dir = "../images/Pupuk/";
	$file 	= $back_dir.$Keterangan.'.xlsx';
	// $file 	= $Keterangan.'.xlsx';
	
	if (file_exists($file))
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: private');
		header('Pragma: private');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;

	} 
	else 
	{
		header("Location: LapRekapRekapitulasi.php?s=1");
		die();
	}
	
}