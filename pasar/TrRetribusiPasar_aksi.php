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

	@$TanggalTransaksi	  = htmlspecialchars($_POST['TanggalTransaksi']);
	@$WaktuTransaksi	  = date('H:i:s');
	@$UserName			  = htmlspecialchars($_POST['UserName']);
	@$TanggalTrans		  = $TanggalTransaksi.' '.$WaktuTransaksi;

	for ($i=0; $i < sizeof($_POST['cekbox']); $i++) {

		$IDPerson     		= $_POST["cekbox"][$i];
		$NoTransRet			= GetIDTransaksi($koneksi);
		$TanggalTrans   	= $TanggalTrans;
		$JmlHariDibayar 	= '1';
		$TglMulaiDibayar 	= $TanggalTrans;
		$TglSampaiDibayar 	= $TanggalTrans;
		$NominalRetribusi	= $_POST["Retribusi"][$i];
		$KodePasar	 		= $_POST["KodePasar"][$i];
		$IDLapak	 		= $_POST["IDLapak"][$i];
		// $NoUrut 			=($i+1);

        $DataItem = @mysqli_query($koneksi, "INSERT INTO trretribusipasar (NoTransRet,IDPerson,KodePasar,UserName,IDLapak,TanggalTrans,JmlHariDibayar,TglMulaiDibayar,TglSampaiDibayar,NominalRetribusi,NominalDiterima,IsTransfer)VALUES('$NoTransRet','$IDPerson','$KodePasar','$UserName','$IDLapak','$TanggalTrans','$JmlHariDibayar','$TglMulaiDibayar','$TglSampaiDibayar','$NominalRetribusi','$NominalRetribusi','0')"); 

    }

    if($DataItem){
		echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="TrRetribusiPasar.php"; </script>';
	}else{
		echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="TrRetribusiPasar.php"; </script>';
	}
}

function GetIDTransaksi($koneksi) {
	$Tanggal = date("Ymd");
	$sql = "SELECT RIGHT(NoTransRet,7) AS kode FROM trretribusipasar WHERE NoTransRet LIKE '%$Tanggal%' ORDER BY NoTransRet DESC LIMIT 1";
	$res = mysqli_query($koneksi, $sql);
	if(mysqli_num_rows($res) > 0){
		$result = mysqli_fetch_array($res);
		if ($result['kode'] == null) {
			$kode = 1;
		} else {
			$kode = ++$result['kode'];
		}	
	}else{
		$kode = 1;
	}
	
	$bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
	return 'TRP-' . $Tanggal . '-' . $bikin_kode ;
}		

?>
</html>