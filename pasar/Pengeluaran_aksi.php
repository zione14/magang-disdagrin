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
	@$Keterangan		= htmlspecialchars($_POST['Keterangan']);
	@$UserName	  		= htmlspecialchars($_POST['UserName']);
	@$KodePasar	  		= htmlspecialchars($_POST['KodePasar']);
	@$TipeTransaksi	    = htmlspecialchars($_POST['TipeTransaksi']);
	@$TanggalTransaksi	= htmlspecialchars($_POST['TanggalTransaksi']);
	@$TotalNilaKB	 	= array_sum($_POST['TotalNominal']);
	@$Tanggal 			= date('Ymd');


	$sql = @mysqli_query($koneksi, "SELECT MAX(RIGHT(NoTransArusKB,7)) AS kode FROM traruskb WHERE  LEFT(NoTransArusKB,12)='TRK-$Tanggal' ORDER BY NoTransArusKB DESC LIMIT 1"); 
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

	 $SimpanData = @mysqli_query($koneksi, "INSERT INTO traruskb (NoTransArusKB, TanggalTransaksi, TipeTransaksi, KodePasar, Keterangan, TotalNilaKB, UserName)VALUES('$kode_jadi','$TanggalTransaksi','$TipeTransaksi', '$KodePasar','$Keterangan', '$TotalNilaKB', '$UserName')"); 

	if($SimpanData){

		 for ($i=0; $i < sizeof($_POST['KodeKB']); $i++) {

		 	$KodeKB		 		=$_POST["KodeKB"][$i];
			$JumlahKreditKB     =$_POST["JumlahKreditKB"][$i];
			$TotalNominal    	=$_POST["TotalNominal"][$i];
			$NoSeriAwal    		=$_POST["NoSeriAwal"][$i];
			$NoSeriAkhir    	=$_POST["NoSeriAkhir"][$i];
			$KodeBatch    		=$_POST["KodeBatch"][$i];
			$JumlahKirim    	=$_POST["JumlahKreditKB"][$i];
			// $StokSaatRequest 	= CekStokRequest($koneksi, $KodePasar, $_POST["KodeKB"][$i]);
			$NoUrut 			=($i+1);
				
            $DataItem = @mysqli_query($koneksi, "INSERT INTO traruskbitem (NoTransArusKB, NoUrut, KodeKB, JumlahKreditKB, TotalNominal, NoSeriAwal, NoSeriAkhir, KodeBatch, JumlahKirim)VALUES('$kode_jadi', '$NoUrut', '$KodeKB', '$JumlahKreditKB', '$TotalNominal', '$NoSeriAwal', '$NoSeriAkhir', '$KodeBatch', '$JumlahKirim')"); 
        }

        if($DataItem){
			echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="Pengeluaran.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="Pengeluaran.php"; </script>';
		}

	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "Pengeluaran.php";
			  });
			  </script>';
	}
}		

// if(base64_decode($_POST['aksi']) == 'Hapus'){
// 	$NoTrRequest = base64_decode($_POST['id']);
// 	$result = HapusKertas($koneksi, $NoTrRequest);
// 	if($result){
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Hapus Data",
// 			text: " ",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Request.php";
// 		  });
// 		  </script>';
// 	}else{
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Hapus Data Gagal!",
// 			text: "Silah coba ulang..",
// 			type: "error"
// 		  },
// 		  function () {
// 			window.location.href = "Request.php";
// 		  });
// 		  </script>';
// 	}
// }

// if(base64_decode($_POST['aksi']) == 'HapusItem'){
// 	$NoTrRequest  = base64_decode($_POST['id']);
// 	$NoUrut 	  = base64_decode($_POST['nm']);
// 	$nominal 	  = base64_decode($_POST['nominal']);
// 	$total   	  = $_POST['total'];
// 	$TotalNilaiKB = $total-$nominal;
// 	$result 	  = HapusKertasItem($koneksi, $NoTrRequest, $NoUrut);
// 	if($result){
// 		UpdateRequestItem($koneksi, $NoTrRequest, $TotalNilaiKB, 'TotalNilaiKB');
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Hapus Data",
// 			text: "",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Request_edit.php?id='.base64_encode($NoTrRequest).'";
// 		  });
// 		  </script>';
// 	}else{
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Hapus Data Gagal!",
// 			text: "Silah coba ulang..",
// 			type: "error"
// 		  },
// 		  function () {
// 			window.location.href = "Request_edit.php?id='.base64_encode($NoTrRequest).'";
// 		  });
// 		  </script>';
// 	}
			
// }

// if($_POST['aksi'] == 'EditItem'){
// 	$NoTrRequest   = mysqli_escape_string($koneksi, $_POST['NoTrRequest']);
// 	$NoUrut 	   = mysqli_escape_string($koneksi, $_POST['NoUrut']);
// 	$KodeKB 	   = mysqli_escape_string($koneksi, $_POST['KodeKB']);
// 	$JumlahKB      = mysqli_escape_string($koneksi, $_POST['JumlahKB']);
// 	$NilaiTotal    = mysqli_escape_string($koneksi, $_POST['NilaiTotal']);
// 	$TotalNominal  = $NilaiTotal * $JumlahKB;
// 	$result = EditItem($koneksi, $NoTrRequest, $NoUrut, $KodeKB, $JumlahKB, $TotalNominal);
// 	if($result){
// 		$TotalNilaiKB = HitungTotalNilaiKB($koneksi, $NoTrRequest);
// 		UpdateTransaksiRequestKB($koneksi, $NoTrRequest, $TotalNilaiKB, 'TotalNilaiKB');
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Edit Data",
// 			text: "",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Request_edit.php?id='.base64_encode($NoTrRequest).'";
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
// 			window.location.href = "Request_edit.php?id='.base64_encode($NoTrRequest).'";
// 		  });
// 		  </script>';
// 	}
	
			
// }

// if($_POST['aksi'] == 'Edit'){
// 	$NoTrRequest 		 = mysqli_escape_string($koneksi, $_POST['NoTrRequest']);
// 	$KeteranganRequest   = mysqli_escape_string($koneksi, $_POST['KeteranganRequest']);
// 	$result = UpdateTransaksiRequestKB($koneksi, $NoTrRequest, $KeteranganRequest, 'KeteranganRequest');
// 	if($result){
// 		echo '<script type="text/javascript">
// 		  sweetAlert({
// 			title: "Berhasil Edit Data",
// 			text: "",
// 			type: "success"
// 		  },
// 		  function () {
// 			window.location.href = "Request.php";
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
// 			window.location.href = "Request.php";
// 		  });
// 		  </script>';
// 	}
	
			
// }

// // function CekTransaksi($conn, $NoTrRequest){
// // 	$sql = "SELECT COUNT(NoTrRequest) AS Jumlah FROM trrequestkbitem WHERE NoTrRequest = '$NoTrRequest' AND Keterangan = 'Sudah Dipakai'";
// // 	$res = $conn->query($sql);
// // 	if(mysqli_num_rows($res) > 0){
// // 		$row = mysqli_fetch_assoc($res);
// // 		return $row['Jumlah'] > 0 ? false : true;
// // 	}else{
// // 		return true;
// // 	}
// // }

// function HapusKertas($conn, $NoTrRequest){
// 	$sqlitem = "DELETE FROM trrequestkbitem WHERE NoTrRequest = '$NoTrRequest'";
// 	$item = $conn->query($sqlitem);
// 	if($item){
// 		$sql = "DELETE FROM trrequestkb WHERE NoTrRequest = '$NoTrRequest'";
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

// function HapusKertasItem($conn, $NoTrRequest, $NoUrut){
// 	$sqlitem = "DELETE FROM trrequestkbitem WHERE NoTrRequest = '$NoTrRequest' AND NoUrut = '$NoUrut'";
// 	return $conn->query($sqlitem);
// }

// function EditItem($conn, $NoTrRequest, $NoUrut, $KodeKB, $JumlahKB, $TotalNominal){
// 	$sqlitem = "UPDATE trrequestkbitem SET KodeKB='$KodeKB', JumlahKB='$JumlahKB', TotalNominal='$TotalNominal' WHERE NoTrRequest = '$NoTrRequest' AND NoUrut='$NoUrut'";
// 	return $conn->query($sqlitem);
// }

// function HitungTotalNilaiKB($conn, $NoTrRequest){
// 	$sql = "SELECT IFNULL(SUM(TotalNominal), 0) AS Jumlah FROM trrequestkbitem WHERE NoTrRequest = '$NoTrRequest'";
// 	$res = $conn->query($sql);
// 	if(mysqli_num_rows($res) > 0){
// 		$row = mysqli_fetch_assoc($res);
// 		return $row['Jumlah'];
// 	}else{
// 		return 0;
// 	}
// }

// function UpdateRequestItem($conn, $NoTrRequest, $Value, $Table){
// 	$sqlitem = "UPDATE trrequestkb SET $Table='$Value' WHERE NoTrRequest = '$NoTrRequest'";
// 	return $conn->query($sqlitem);
// }

// function UpdateTransaksiRequestKB($conn, $NoTrRequest, $Value, $Table){
// 	$sqlitem = "UPDATE trrequestkb SET $Table='$Value' WHERE NoTrRequest = '$NoTrRequest'";
// 	return $conn->query($sqlitem);
// }

// function UpdateTransaksiArus($conn, $NoTransArusKB, $TanggalTransaksi, $Keterangan, $KodeBatchPencetakan){
// 	$sqlitem = "UPDATE traruskb SET TanggalTransaksi='$TanggalTransaksi', Keterangan='$Keterangan', KodeBatchPencetakan='$KodeBatchPencetakan' WHERE NoTransArusKB = '$NoTransArusKB'";
// 	return $conn->query($sqlitem);
// }

// function CekStokRequest($conn, $KodePasar, $KodeKB){
// 	$sql = "SELECT IFNULL(SUM(d.JumlahKirim), 0)-IFNULL(c.JumlahKreditKB, 0) as StokSaatRequest
// 	FROM traruskbitem d
// 	JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
// 	LEFT JOIN (
// 		SELECT IFNULL(SUM(d.JumlahKreditKB), 0) as JumlahKreditKB, t.KodePasar, d.KodeKB
// 		FROM traruskbitem d
// 		JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
// 		Where t.TipeTransaksi='PENGELUARAN'
// 		GROUP by t.KodePasar, d.KodeKB
// 	) c ON c.KodePasar = t.KodePasar AND c.KodeKB = d.KodeKB
// 	WHERE t.KodePasar = '$KodePasar' AND d.KodeKB = '$KodeKB' AND t.TipeTransaksi='PENGIRIMAN'";
// 	$res = $conn->query($sql);
// 	if(mysqli_num_rows($res) > 0){
// 		$row = mysqli_fetch_assoc($res);
// 		return $row['StokSaatRequest'];
// 	}else{
// 		return 0;
// 	}
// }


?>
</html>