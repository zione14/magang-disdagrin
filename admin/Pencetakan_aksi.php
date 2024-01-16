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
	@$KodeBatchPencetakan = htmlspecialchars($_POST['KodeBatchPencetakan']);
	@$UserName			  = htmlspecialchars($_POST['UserName']);
	@$WaktuTransaksi	  = htmlspecialchars($_POST['WaktuTransaksi']);
	@$TanggalTransaksi	  = htmlspecialchars($_POST['TanggalTransaksi']);
	@$TipeTransaksi	  	  = htmlspecialchars($_POST['TipeTransaksi']);
	@$Keterangan	  	  = htmlspecialchars($_POST['Keterangan']);
	@$TotalNilaKB	  	  = array_sum($_POST['TotalNominal']);
	$Tanggal 			  = date('Ymd');
	@$TanggalCetak		  = $TanggalTransaksi.' '.$WaktuTransaksi;

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

	 $SimpanData = @mysqli_query($koneksi, "INSERT INTO traruskb (NoTransArusKB,TanggalTransaksi,TipeTransaksi,KodeBatchPencetakan,TotalNilaKB,UserName,Keterangan)VALUES('$kode_jadi','$TanggalCetak','$TipeTransaksi', '$KodeBatchPencetakan','$TotalNilaKB', '$UserName', '$Keterangan')"); 

	if($SimpanData){

		 for ($i=0; $i < sizeof($_POST['KodeKB']); $i++) {

		 	$JumlahDebetKB	=$_POST["JumlahDebetKB"][$i];
			$KodeKB     	=$_POST["KodeKB"][$i];
			$TotalNominal   =$_POST["TotalNominal"][$i];
			$NoSeriAwal     =$_POST["NoSeriAwal"][$i];
			$NoSeriAkhir    =$_POST["NoSeriAkhir"][$i];
			// $KodeBatch   	=$_POST["KodeBatch"][$i];
			$Keterangan   	=$_POST["Keterangan"][$i];
			$NoUrut 		=($i+1);
				
            $DataItem = @mysqli_query($koneksi, "INSERT INTO traruskbitem (NoTransArusKB,NoUrut,KodeKB,JumlahDebetKB,TotalNominal,NoSeriAwal,NoSeriAkhir,Keterangan,KodeBatch)VALUES('$kode_jadi','$NoUrut','$KodeKB','$JumlahDebetKB','$TotalNominal','$NoSeriAwal','$NoSeriAkhir','$Keterangan', '$KodeBatchPencetakan')"); 
        }

        if($DataItem){
			echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="Pencetakan.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="Pencetakan.php"; </script>';
		}

	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "Pencetakan.php";
			  });
			  </script>';
	}
}		

if(base64_decode($_POST['aksi']) == 'Hapus'){
	$NoTransArusKB = base64_decode($_POST['id']);
	if(CekTransaksi($koneksi, $NoTransArusKB)){
		$result = HapusKertas($koneksi, $NoTransArusKB);
		if($result){
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Berhasil Hapus Data",
				text: " ",
				type: "success"
			  },
			  function () {
				window.location.href = "Pencetakan.php";
			  });
			  </script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Hapus Data Gagal!",
				text: "Silah coba ulang..",
				type: "error"
			  },
			  function () {
				window.location.href = "Pencetakan.php";
			  });
			  </script>';
		}
	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Hapus Data Gagal!",
				text: "Data Sudah Digunakan Transaksi",
				type: "error"
			  },
			  function () {
				window.location.href = "Pencetakan.php";
			  });
			  </script>';
	}		
}

if(base64_decode($_POST['aksi']) == 'HapusItem'){
	$NoTransArusKB = base64_decode($_POST['id']);
	$NoUrut = base64_decode($_POST['nm']);
	if(CekTransaksiItem($koneksi, $NoTransArusKB, $NoUrut)){
		$result = HapusKertasItem($koneksi, $NoTransArusKB, $NoUrut);
		if($result){
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Berhasil Hapus Data",
				text: "",
				type: "success"
			  },
			  function () {
				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
			  });
			  </script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Hapus Data Gagal!",
				text: "Silah coba ulang..",
				type: "error"
			  },
			  function () {
				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
			  });
			  </script>';
		}
	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Hapus Data Gagal!",
				text: "Data Sudah Digunakan Transaksi",
				type: "error"
			  },
			  function () {
				window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
			  });
			  </script>';
	}
			
}

if($_POST['aksi'] == 'EditItem'){
	$NoTransArusKB = mysqli_escape_string($koneksi, $_POST['NoTransArusKB']);
	$NoUrut 	   = mysqli_escape_string($koneksi, $_POST['NoUrut']);
	$KodeKB 	   = mysqli_escape_string($koneksi, $_POST['KodeKB']);
	$JumlahDebetKB = mysqli_escape_string($koneksi, $_POST['JumlahDebetKB']);
	$KodeBatch 	   = mysqli_escape_string($koneksi, $_POST['KodeBatch']);
	$NoSeriAwal    = mysqli_escape_string($koneksi, $_POST['NoSeriAwal']);
	$NoSeriAkhir   = $NoSeriAwal+($JumlahDebetKB-1);
	$NilaiTotal    = mysqli_escape_string($koneksi, $_POST['NilaiTotal']);
	$TotalNominal  = $NilaiTotal * $JumlahDebetKB;
	$result = EditItem($koneksi, $NoTransArusKB, $NoUrut, $KodeKB, $JumlahDebetKB, $KodeBatch, $NoSeriAwal, $NoSeriAkhir, $TotalNominal);
	if($result){
		$TotalNilaKB = HitungTotalNilaiKB($koneksi, $NoTransArusKB);
		UpdateTransaksiArusKB($koneksi, $NoTransArusKB, $TotalNilaKB, 'TotalNilaKB');
		echo '<script type="text/javascript">
		  sweetAlert({
			title: "Berhasil Edit Data",
			text: "",
			type: "success"
		  },
		  function () {
			window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
		  });
		  </script>';
	}else{
		echo '<script type="text/javascript">
		  sweetAlert({
			title: "Edit Data Gagal!",
			text: "Silah coba ulang..",
			type: "error"
		  },
		  function () {
			window.location.href = "Pencetakan_edit.php?id='.base64_encode($NoTransArusKB).'";
		  });
		  </script>';
	}
	
			
}

if($_POST['aksi'] == 'Edit'){
	$NoTransArusKB 		 = mysqli_escape_string($koneksi, $_POST['NoTransArusKB']);
	$KodeBatchPencetakan = mysqli_escape_string($koneksi, $_POST['KodeBatchPencetakan']);
	$Keterangan			 = mysqli_escape_string($koneksi, $_POST['Keterangan']);
	$WaktuTransaksi		 = mysqli_escape_string($koneksi, $_POST['WaktuTransaksi']);
	$TanggalTransaksi	 = mysqli_escape_string($koneksi, $_POST['TanggalTransaksi']);
	$TanggalCetak		 = $TanggalTransaksi.' '.$WaktuTransaksi;
	$result = UpdateTransaksiArus($koneksi, $NoTransArusKB, $TanggalCetak, $Keterangan, $KodeBatchPencetakan);
	if($result){
		echo '<script type="text/javascript">
		  sweetAlert({
			title: "Berhasil Edit Data",
			text: "",
			type: "success"
		  },
		  function () {
			window.location.href = "Pencetakan.php";
		  });
		  </script>';
	}else{
		echo '<script type="text/javascript">
		  sweetAlert({
			title: "Edit Data Gagal!",
			text: "Silah coba ulang..",
			type: "error"
		  },
		  function () {
			window.location.href = "Pencetakan.php";
		  });
		  </script>';
	}
	
			
}

function CekTransaksi($conn, $NoTransArusKB){
	$sql = "SELECT COUNT(NoTransArusKB) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND Keterangan = 'Sudah Dipakai'";
	$res = $conn->query($sql);
	if(mysqli_num_rows($res) > 0){
		$row = mysqli_fetch_assoc($res);
		return $row['Jumlah'] > 0 ? false : true;
	}else{
		return true;
	}
}

function HapusKertas($conn, $NoTransArusKB){
	$sqlitem = "DELETE FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB'";
	$item = $conn->query($sqlitem);
	if($item){
		$sql = "DELETE FROM traruskb WHERE NoTransArusKB = '$NoTransArusKB'";
		return $conn->query($sql);
	}
}

function CekTransaksiItem($conn, $NoTransArusKB, $NoUrut){
	$sql = "SELECT COUNT(NoTransArusKB) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut AND '$NoUrut'  AND Keterangan != 'Sudah Dipakai'";
	$res = $conn->query($sql);
	if(mysqli_num_rows($res) > 0){
		$row = mysqli_fetch_assoc($res);
		return $row['Jumlah'] > 0 ? true : false;
	}else{
		return false;
	}
}

function HapusKertasItem($conn, $NoTransArusKB, $NoUrut){
	$sqlitem = "DELETE FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut ='$NoUrut'  AND Keterangan != 'Sudah Dipakai' ";
	return $conn->query($sqlitem);
}

function EditItem($conn, $NoTransArusKB, $NoUrut, $KodeKB, $JumlahDebetKB, $KodeBatch, $NoSeriAwal, $NoSeriAkhir, $TotalNominal){
	$sqlitem = "UPDATE traruskbitem SET KodeKB='$KodeKB', JumlahDebetKB='$JumlahDebetKB', KodeBatch='$KodeBatch', NoSeriAwal='$NoSeriAwal', NoSeriAkhir='$NoSeriAkhir', TotalNominal='$TotalNominal' WHERE NoTransArusKB = '$NoTransArusKB' AND NoUrut='$NoUrut'";
	return $conn->query($sqlitem);
}

function HitungTotalNilaiKB($conn, $NoTransArusKB){
	$sql = "SELECT IFNULL(SUM(TotalNominal), 0) AS Jumlah FROM traruskbitem WHERE NoTransArusKB = '$NoTransArusKB'";
	$res = $conn->query($sql);
	if(mysqli_num_rows($res) > 0){
		$row = mysqli_fetch_assoc($res);
		return $row['Jumlah'];
	}else{
		return 0;
	}
}

function UpdateTransaksiArusKB($conn, $NoTransArusKB, $Value, $Table){
	$sqlitem = "UPDATE traruskb SET $Table='$Value' WHERE NoTransArusKB = '$NoTransArusKB'";
	return $conn->query($sqlitem);
}

function UpdateTransaksiArus($conn, $NoTransArusKB, $TanggalTransaksi, $Keterangan, $KodeBatchPencetakan){
	$sqlitem = "UPDATE traruskb SET TanggalTransaksi='$TanggalTransaksi', Keterangan='$Keterangan', KodeBatchPencetakan='$KodeBatchPencetakan' WHERE NoTransArusKB = '$NoTransArusKB'";
	return $conn->query($sqlitem);
}


?>
</html>