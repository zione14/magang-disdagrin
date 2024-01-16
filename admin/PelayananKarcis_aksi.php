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

	@$NoTrRequest 		= mysqli_escape_string($koneksi, $_POST['NoTrRequest']);
	@$KodePasar 		= mysqli_escape_string($koneksi, $_POST['KodePasar']);
	@$TipeTransaksi 	= mysqli_escape_string($koneksi, $_POST['TipeTransaksi']);
	@$UserName 			= mysqli_escape_string($koneksi, $_POST['UserName']);
	@$TanggalTransaksi  = mysqli_escape_string($koneksi, $_POST['TanggalTransaksi']);
	@$TotalNilaKB	  	= array_sum($_POST['TotalNominal']);
	$Tanggal 			= date('Ymd');

	$sql = @mysqli_query($koneksi, "SELECT RIGHT(NoTransArusKB,8) AS kode FROM traruskb ORDER BY NoTransArusKB DESC LIMIT 1"); 
	$nums = mysqli_num_rows($sql);
	if($nums <> 0){
		 $data = mysqli_fetch_array($sql);
		 $kode = $data['kode'] + 1;
	}else{
		 $kode = 1;
	}
	//mulai bikin kode
	 $bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
	 $kode_jadi = "TRK-".$Tanggal."-".$bikin_kode;

	 $SimpanData = @mysqli_query($koneksi, "INSERT INTO traruskb (NoTransArusKB,TanggalTransaksi,TipeTransaksi,TotalNilaKB,UserName,KodePasar,NoTrRequest)VALUES('$kode_jadi','$TanggalTransaksi','$TipeTransaksi','$TotalNilaKB', '$UserName', '$KodePasar', '$NoTrRequest')"); 

	if($SimpanData){

		 for ($i=0; $i < sizeof($_POST['NoTransArusKB']); $i++) {

		 	$NoUrutLama		=$_POST["NoUrut"][$i];
		 	$NoTransArusKB	=$_POST["NoTransArusKB"][$i];
		 	$JumlahKreditKB	=$_POST["JumlahDebetKB"][$i];
			$KodeKB     	=$_POST["KodeKB"][$i];
			$TotalNominal   =$_POST["TotalNominal"][$i];
			$NoSeriAwal     =$_POST["NoSeriAwal"][$i];
			$NoSeriAkhir    =$_POST["NoSeriAkhir"][$i];
			$KodeBatch   	=$_POST["KodeBatch"][$i];
			// $JumlahKirim   	=$_POST["JumlahKirim"][$i];
			$NoUrut 		=($i+1);
				
            $DataItem = @mysqli_query($koneksi, "INSERT INTO traruskbitem (NoTransArusKB,NoUrut,KodeKB,JumlahKreditKB,TotalNominal,NoSeriAwal,NoSeriAkhir,KodeBatch)VALUES('$kode_jadi','$NoUrut','$KodeKB','$JumlahKreditKB','$TotalNominal','$NoSeriAwal','$NoSeriAkhir','$KodeBatch')"); 
            UpdateTransaksiArusKBItem($koneksi, $NoTransArusKB, $NoUrutLama);
        }

        if($DataItem){
        	UpdateTransaksiRequestKB($koneksi, $NoTrRequest, $kode_jadi);
			echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="PelayananKarcis.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="PelayananKarcis.php"; </script>';
		}

	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "PelayananKarcis.php";
			  });
			  </script>';
	}
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