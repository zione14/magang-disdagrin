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

// if(base64_decode($_POST['aksi']) == 'Hapus'){
// 	$NoTransArusKB = base64_decode($_POST['id']);
// 	if(CekTransaksi($koneksi, $NoTransArusKB)){
// 		$result = HapusKertas($koneksi, $NoTransArusKB);
// 		if($result){
// 			echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Berhasil Hapus Data",
// 				text: " ",
// 				type: "success"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan.php";
// 			  });
// 			  </script>';
// 		}else{
// 			echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Hapus Data Gagal!",
// 				text: "Silah coba ulang..",
// 				type: "error"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan.php";
// 			  });
// 			  </script>';
// 		}
// 	}else{
// 		echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Hapus Data Gagal!",
// 				text: "Data Sudah Digunakan Transaksi",
// 				type: "error"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan.php";
// 			  });
// 			  </script>';
// 	}		
// }

// if(base64_decode($_POST['aksi']) == 'HapusItem'){
// 	$NoTransArusKB = base64_decode($_POST['id']);
// 	$NoUrut = base64_decode($_POST['nm']);
// 	if(CekTransaksiItem($koneksi, $NoTransArusKB, $NoUrut)){
// 		$result = HapusKertasItem($koneksi, $NoTransArusKB, $NoUrut);
// 		if($result){
// 			echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Berhasil Hapus Data",
// 				text: "",
// 				type: "success"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
// 			  });
// 			  </script>';
// 		}else{
// 			echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Hapus Data Gagal!",
// 				text: "Silah coba ulang..",
// 				type: "error"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
// 			  });
// 			  </script>';
// 		}
// 	}else{
// 		echo '<script type="text/javascript">
// 			  sweetAlert({
// 				title: "Hapus Data Gagal!",
// 				text: "Data Sudah Digunakan Transaksi",
// 				type: "error"
// 			  },
// 			  function () {
// 				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
// 			  });
// 			  </script>';
// 	}
			
// }

// if($_POST['aksi'] == 'EditItem'){
// 	$NoTransArusKB = mysqli_escape_string($koneksi, $_POST['NoTransArusKB']);
// 	$NoUrut 	   = mysqli_escape_string($koneksi, $_POST['NoUrut']);
// 	$KodeKB 	   = mysqli_escape_string($koneksi, $_POST['KodeKB']);
// 	$JumlahDebetKB = mysqli_escape_string($koneksi, $_POST['JumlahDebetKB']);
// 	$KodeBatch 	   = mysqli_escape_string($koneksi, $_POST['KodeBatch']);
// 	$NoSeriAwal    = mysqli_escape_string($koneksi, $_POST['NoSeriAwal']);
// 	$NoSeriAkhir   = $NoSeriAwal+($JumlahDebetKB-1);
// 	$NilaiTotal    = mysqli_escape_string($koneksi, $_POST['NilaiTotal']);
// 	$TotalNominal  = $NilaiTotal * $JumlahDebetKB;
// 	$result = EditItem($koneksi, $NoTransArusKB, $NoUrut, $KodeKB, $JumlahDebetKB, $KodeBatch, $NoSeriAwal, $NoSeriAkhir, $TotalNominal);
// 	if($result){
// 		$TotalNilaKB = HitungTotalNilaiKB($koneksi, $NoTransArusKB);
// 		UpdateTransaksiArusKB($koneksi, $NoTransArusKB, $TotalNilaKB, 'TotalNilaKB');
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Edit Data",
// 			text: "",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
// 		  });
// 		  </script>';
// 	}else{
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Edit Data Gagal!",
// 			text: "Silah coba ulang..",
// 			type: "error"
// 		  },
// 		  function () {
// 			window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
// 		  });
// 		  </script>';
// 	}
	
			
// }

// if($_POST['aksi'] == 'Edit'){
// 	$NoTransArusKB 		 = mysqli_escape_string($koneksi, $_POST['NoTransArusKB']);
// 	$KodeBatchPencetakan = mysqli_escape_string($koneksi, $_POST['KodeBatchPencetakan']);
// 	$Keterangan			 = mysqli_escape_string($koneksi, $_POST['Keterangan']);
// 	$WaktuTransaksi		 = mysqli_escape_string($koneksi, $_POST['WaktuTransaksi']);
// 	$TanggalTransaksi	 = mysqli_escape_string($koneksi, $_POST['TanggalTransaksi']);
// 	$TanggalCetak		 = $TanggalTransaksi.' '.$WaktuTransaksi;
// 	$result = UpdateTransaksiArus($koneksi, $NoTransArusKB, $TanggalCetak, $Keterangan, $KodeBatchPencetakan);
// 	if($result){
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Edit Data",
// 			text: "",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Pencetakan.php";
// 		  });
// 		  </script>';
// 	}else{
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Edit Data Gagal!",
// 			text: "Silah coba ulang..",
// 			type: "error"
// 		  },
// 		  function () {
// 			window.location.href = "Pencetakan.php";
// 		  });
// 		  </script>';
// 	}
	
			
// }

// function CekTransaksi($conn, $NoTransArusKB){
// 	$sql = "SELECT COUNT(NoTransArusKB) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND Keterangan = 'Sudah Dipakai'";
// 	$res = $conn->query($sql);
// 	if(mysqli_num_rows($res) > 0){
// 		$row = mysqli_fetch_assoc($res);
// 		return $row['Jumlah'] > 0 ? false : true;
// 	}else{
// 		return true;
// 	}
// }

// function HapusKertas($conn, $NoTransArusKB){
// 	$sqlitem = "DELETE FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB'";
// 	$item = $conn->query($sqlitem);
// 	if($item){
// 		$sql = "DELETE FROM traruskb WHERE NoTransArusKB = '$NoTransArusKB'";
// 		return $conn->query($sql);
// 	}
// }

// function CekTransaksiItem($conn, $NoTransArusKB, $NoUrut){
// 	$sql = "SELECT COUNT(NoTransArusKB) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut AND '$NoUrut'  AND Keterangan != 'Sudah Dipakai'";
// 	$res = $conn->query($sql);
// 	if(mysqli_num_rows($res) > 0){
// 		$row = mysqli_fetch_assoc($res);
// 		return $row['Jumlah'] > 0 ? true : false;
// 	}else{
// 		return false;
// 	}
// }

// function HapusKertasItem($conn, $NoTransArusKB, $NoUrut){
// 	$sqlitem = "DELETE FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut ='$NoUrut'  AND Keterangan != 'Sudah Dipakai' ";
// 	return $conn->query($sqlitem);
// }

// function EditItem($conn, $NoTransArusKB, $NoUrut, $KodeKB, $JumlahDebetKB, $KodeBatch, $NoSeriAwal, $NoSeriAkhir, $TotalNominal){
// 	$sqlitem = "UPDATE traruskbitem SET KodeKB='$KodeKB', JumlahDebetKB='$JumlahDebetKB', KodeBatch='$KodeBatch', NoSeriAwal='$NoSeriAwal', NoSeriAkhir='$NoSeriAkhir', TotalNominal='$TotalNominal' WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut='$NoUrut'";
// 	return $conn->query($sqlitem);
// }

// function HitungTotalNilaiKB($conn, $NoTransArusKB){
// 	$sql = "SELECT IFNULL(SUM(TotalNominal), 0) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB'";
// 	$res = $conn->query($sql);
// 	if(mysqli_num_rows($res) > 0){
// 		$row = mysqli_fetch_assoc($res);
// 		return $row['Jumlah'];
// 	}else{
// 		return 0;
// 	}
// }

// function UpdateTransaksiArusKB($conn, $NoTransArusKB, $Value, $Table){
// 	$sqlitem = "UPDATE traruskb SET $Table='$Value' WHERE NoTransArusKB = '$NoTransArusKB'";
// 	return $conn->query($sqlitem);
// }

// function UpdateTransaksiArus($conn, $NoTransArusKB, $TanggalTransaksi, $Keterangan, $KodeBatchPencetakan){
// 	$sqlitem = "UPDATE traruskb SET TanggalTransaksi='$TanggalTransaksi', Keterangan='$Keterangan', KodeBatchPencetakan='$KodeBatchPencetakan' WHERE NoTransArusKB = '$NoTransArusKB'";
// 	return $conn->query($sqlitem);
// }




?>
</html>