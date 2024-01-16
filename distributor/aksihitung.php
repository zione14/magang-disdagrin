<?php
	//membuat id konten
	function stokSekarang($koneksi, $IDPerson, $KodeBarang){
		$query = "SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan FROM sirkulasipupuk where IDPerson='$IDPerson' and KodeBarang='$KodeBarang'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$Penerimaan = $result['Penerimaan'];
		$Penjualan = $result['Penjualan'];
		$Total = $Penerimaan-$Penjualan;
		return $Total;
	}
	
	function NamaPupuk($koneksi, $KodeBarang){
		$query = "SELECT NamaBarang FROM mstpupuksubsidi where KodeBarang='$KodeBarang'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPupuk = $result['NamaBarang'];
		
		return $NamaPupuk;
	}
	
	function HargaSatuan($koneksi, $KodeBarang){
		$query = "SELECT Harga FROM mstpupuksubsidi where KodeBarang='$KodeBarang'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$Harga = $result['Harga'];
		
		return $Harga;
	}
	
	function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	function stokPenjualan($koneksi, $IDPerson, $KodeBarang, $NoTransaksi){
		$query = "SELECT SUM(JumlahKeluar) as Penjualan FROM sirkulasipupuk where IDPerson='$IDPerson' and KodeBarang='$KodeBarang' and NoTransaksi='$NoTransaksi'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$Penjualan = $result['Penjualan'];
		
		return $Penjualan;
	 }
	 
	 function satuanBarang($koneksi, $KodeBarang){
		$query = "SELECT Keterangan FROM mstpupuksubsidi where KodeBarang='$KodeBarang'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$Satuan = $result['Keterangan'];
		
		return $Satuan;
	 }
?>

