<html>
<head>
  <?php include 'title.php';?>
  <!-- Sweet Alerts -->
  <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
</head>

<body>
<script src="js/jquery.min.js"></script>
<!-- Sweet Alerts -->
<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
</body>
<?php
include "../library/config.php";
if(isset($_POST['SimpanData'])){

	@$NoTransArusKB 		= mysqli_escape_string($koneksi, $_POST['NoTransArusKB']);
	for ($i=0; $i < sizeof($_POST['NoUrut']); $i++) {
	 	$NoUrut			= $_POST["NoUrut"][$i];
		$JumlahKirim   	= $_POST["JumlahKirim"][$i];
        $DataItem =  @mysqli_query($koneksi, "UPDATE traruskbitem SET JumlahKirim='$JumlahKirim' WHERE NoTransArusKB='$NoTransArusKB' AND NoUrut='$NoUrut'"); 
    }

 //    if($DataItem){
		
	// }else{
	// 	echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="Verifikasi.php"; </script>';
	// }
	echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="Verifikasi.php"; </script>';
}		


function UpdateTransaksiArusKBItem($conn, $NoTransArusKB, $NoUrut){
	$sqlitem = "UPDATE traruskbitem SET Keterangan='Sudah Dipakai' WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut='$NoUrut' ";
	return $conn->query($sqlitem);
}

function UpdateTransaksiRequestKB($conn, $NoTrRequest, $NoTrRealisasi){
	$sqlitem = "UPDATE trrequestkb SET IsRealisasi=b'1', NoTrRealisasi = '$NoTrRealisasi' WHERE  NoTrRequest='$NoTrRequest' ";
	return $conn->query($sqlitem);
}

?>
</html>