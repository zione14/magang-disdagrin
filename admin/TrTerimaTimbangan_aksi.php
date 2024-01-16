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
	@$txtKeterangan	= htmlspecialchars($_POST['txtKeterangan']);
	
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

	 $SimpanData = @mysqli_query($koneksi, "INSERT INTO tractiontimbangan (NoTransaksi,IDPerson,JenisTransaksi,UserTerima,StatusTransaksi,TglTransaksi,Keterangan)VALUES('$kode_jadi','$IDPerson','TERA DI KANTOR','$UserName','PENERIMAAN',DATE(NOW()),'$txtKeterangan')"); 

	if($SimpanData){

		 for ($i=0; $i < sizeof($_POST['IDTimbangan']); $i++) {

		 	$IDTimbangan	=$_POST["IDTimbangan"][$i];
			$Keterangan     =$_POST["Keterangan"][$i];
			$KodeLokasi     =$_POST["KodeLokasi"][$i];
			$NoUrutTrans	=($i+1);
			
			$sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDikantor,b.UkuranRealTimbangan,a.NilaiBawah,a.RetPenambahanDikantor,a.NilaiTambah FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
			$res    = mysqli_fetch_array($sql);
		
			if ($res['NilaiBawah'] == '0' AND $res['RetPenambahanDikantor'] == '0' ) {
				$DataItem = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,Keterangan,NominalRetribusi,KodeLokasi)VALUES('$kode_jadi','$NoUrutTrans','$IDPerson','$IDTimbangan','$UserName','$Keterangan','".$res[0]."','$KodeLokasi')"); 
			}else{
				$Nilai = ($res['UkuranRealTimbangan']-$res['NilaiTambah'])/$res['NilaiBawah'];
				$Penambahan =($Nilai*$res['RetPenambahanDikantor'])+$res['RetribusiDikantor'];
			
				$DataItem = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,Keterangan,NominalRetribusi,KodeLokasi)VALUES('$kode_jadi','$NoUrutTrans','$IDPerson','$IDTimbangan','$UserName','$Keterangan','".$Penambahan."','$KodeLokasi')"); 
			}

		
			
            // $DataItem = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,HasilAction1,HasilAction2,TanggalTransaksi)VALUES('$kode_jadi','$NoUrutTrans','$IDPerson','$IDTimbangan','$UserName','0','$HasilAction1','$HasilAction2','$TanggalNOW')"); 
        }

        if($DataItem){
			echo '<script language="javascript">alert("Data Berhasil Disimpan!"); document.location="TrTerimaTimbangan.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Data Gagal Disimpan!"); document.location="TrTerimaTimbangan.php"; </script>';
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