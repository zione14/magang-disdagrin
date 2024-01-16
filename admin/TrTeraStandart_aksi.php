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
if(isset($_POST['SimpanData'])){
	include "../library/config.php";

	@$IDPerson		= htmlspecialchars($_POST['IDPerson']);
	@$UserName		= htmlspecialchars($_POST['UserName']);
	$Tanggal = date('Ymd');
	$TanggalNOW = date("Y-m-d");

	$sql = @mysqli_query($koneksi, "SELECT RIGHT(NoTransaksi,8) AS kode FROM tractiontimbangan ORDER BY NoTransaksi DESC LIMIT 1"); 
	$nums = mysqli_num_rows($sql);
	if($nums <> 0){
		 $data = mysqli_fetch_array($sql);
		 $kode = $data['kode'] + 1;
	}else{
		 $kode = 1;
	}
	//mulai bikin kode
	 $bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
	 $kode_jadi = "TR-".$Tanggal."-".$bikin_kode;

	 $SimpanData = @mysqli_query($koneksi, "INSERT INTO tractiontimbangan (NoTransaksi,IDPerson,JenisTransaksi,UserTerima,UserTera,UserAmbil,TglTransaksi,StatusTransaksi,TotalRetribusi,IsDibayar,IsDiambil,TglAmbil,TglTera)VALUES('$kode_jadi','$IDPerson','TERA DI LOKASI','$UserName','$UserName','$UserName', DATE(NOW()), 'SELESAI','0',b'1',b'1',NOW(),NOW())"); 

	if($SimpanData){

		 for ($i=0; $i < sizeof($_POST['IDTimbangan']); $i++) {

		 	$IDTimbangan	=$_POST["IDTimbangan"][$i];
			$HasilAction1   =$_POST["HasilAction1"][$i];
			$HasilAction2	=$_POST["HasilAction2"][$i];
			$NoUrutTrans	=($i+1);

			@$Status = isset($HasilAction1) && $HasilAction1 === "TERA BATAL" ? "Non Aktif" : "Aktif";
			mysqli_query($koneksi,"update timbanganperson set StatusUTTP='$Status' where IDTimbangan='$IDTimbangan'");
			
            $DataItem = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,HasilAction1,HasilAction2,TanggalTransaksi)VALUES('$kode_jadi','$NoUrutTrans','$IDPerson','$IDTimbangan','$UserName','0','$HasilAction1','$HasilAction2','$TanggalNOW')"); 
        }

        if($DataItem){
			echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="TrTeraStandart.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="TrTeraStandart.php"; </script>';
		}

	}else{
		echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrTeraStandart.php";
			  });
			  </script>';
	}
}		

?>
</html>