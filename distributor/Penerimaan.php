<?php
include 'akses.php';
include 'aksihitung.php';
$Page = 'Transaksi';
$SubPage='Penerimaan';
$Tahun = date("YmdHis");
$now=date('Y-m-d');
if(@$_GET['id']==null){
	$Sebutan = 'Entry Transaksi';
}else{
	$Sebutan = 'Edit Transaksi';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT b.AsalMasuk,b.AlamatAsal,b.JumlahMasuk,b.KodeBarang,b.NoTransaksi,(SELECT a.Keterangan from mstpupuksubsidi a where a.KodeBarang=b.KodeBarang) as Satuan FROM sirkulasipupuk b WHERE b.NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<style>
	th {
		text-align: center;
	}
	</style>
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php include 'header.php';?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Transaksi Penerimaan Pupuk Subsidi</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"></a>&nbsp;
						</li>
						<li>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><i class="fa fa-fw fa-edit"></i> <?php echo $Sebutan; ?></span></a>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Penerimaan Pupuk</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-8 offset-lg-4">
									<form method="post" action="">
										<div class="form-group input-group">		
											<input type="text" name="tgl" class="form-control" id="time7" value="<?php echo @$_REQUEST['tgl']; ?>" placeholder="Tanggal Transaksi" required>&nbsp;&nbsp;
											<select id="KodeBarang" name="KodeBarang" class="form-control">	
											<?php
												echo "<option value=''>--- Pilih Pupuk ---</option>";
												$menu = mysqli_query($koneksi,"SELECT KodeBarang,NamaBarang from mstpupuksubsidi where IsAktif=b'1' ORDER by NamaBarang ASC");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeBarang'] === $_REQUEST['KodeBarang']){
														echo "<option value=\"".$kode['KodeBarang']."\" selected='selected'>".$kode['NamaBarang']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeBarang']."\" >".$kode['NamaBarang']."</option>\n";
													}
												}
											?>
											</select>&nbsp;&nbsp;
											<span class="input-group-btn">
												<button class="btn btn-info" type="submit">Cari</button>
												<!--<a href="../library/html2pdf/cetak/MasterTimbangan.php" title='Print Data' target="_BLANK"><span class="btn btn-secondary">Cetak Data UTTP</span></a>-->
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Suplier</th>
									  <th>Nama Barang</th>
									  <th>Harga Satuan</th>
									  <th>Jumlah Barang</th>
									  <th>Nilai Barang</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$tgl = $_REQUEST['tgl'];
										@$keyword = $_REQUEST['KodeBarang'];
										$reload = "Penerimaan.php?pagination=true&KodeBarang=$keyword&tgl=$tgl";
										$sql =  "SELECT a.NoTransaksi, a.AsalMasuk, a.AlamatAsal, a.NilaiTransaksi, a.KodeBarang, a.JumlahMasuk, a.TanggalTransaksi, ADDDATE(a.TanggalTransaksi, INTERVAL 3 DAY) as TglEdit,(SELECT Keterangan from mstpupuksubsidi  where KodeBarang=a.KodeBarang) as Satuan,a.HargaSatuan
										FROM sirkulasipupuk a 
										WHERE a.IDPerson='$login_id' and a.JumlahKeluar is NULL ";
										
										if(@$_REQUEST['KodeBarang']!=null){
												$sql .= " AND ( a.KodeBarang LIKE '%".$_REQUEST['KodeBarang']."%' ) ";
										}
										if(@$_REQUEST['tgl']!=null){
												$sql .= " AND  a.TanggalTransaksi='$tgl'  ";
										}
										
										$sql .=" ORDER BY a.TanggalTransaksi DESC";
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										$rpp = 20; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody>
										<?php
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php 
													echo $data['AsalMasuk']."<br>".
														 $data['AlamatAsal']."<br>".
														 TanggalIndo($data['TanggalTransaksi']);
												?>
											</td>
											<td>
												<?php echo NamaPupuk($koneksi, $data['KodeBarang']); ?>
											</td>
											<td align="center">
												<?php echo number_format($data['HargaSatuan']);?>
											</td>
											<td align="center">
												<?php echo number_format($data['JumlahMasuk']).' '.$data['Satuan'];?>
											</td>
											<td align="right">
												<?php echo number_format($data['NilaiTransaksi']);?>
											</td>
											<td width="150px" align="center">	
												<?php
													if (strtotime($data['TglEdit']) > strtotime($now) ){
													
														echo '<a href="Penerimaan.php?id='.base64_encode($data['NoTransaksi']).'" title="Edit"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
													
														echo ' <a href="Penerimaan.php?id='.base64_encode($data['NoTransaksi']).'&aksi='.base64_encode('Hapus').'&view=1" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													}
												?>
												
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										?>
									</tbody>
								</table>
								<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
							  </div>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									   <form method="post" action="">
									    <div class="form-group">
											<label>Nama Suplier</label>
											<input type="text" name="AsalMasuk" maxlength="100" class="form-control" placeholder="Nama Suplier" value="<?php echo @$RowData['AsalMasuk']; ?>">
										</div>
									    <div class="form-group">
											<label>Alamat Suplier</label>
											<input type="text" name="AlamatAsal" maxlength="255"  class="form-control" placeholder="Alamat Suplier" value="<?php echo @$RowData['AlamatAsal']; ?>">
										</div>
										<div class="form-group ">	
											<label>Nama Pupuk</label>
											<input type="hidden" class="form-control"  value="<?php echo @$login_id;?>" name="IDPerson">
											<input type="hidden" class="form-control"  value="<?php echo @$RowData['NoTransaksi'];?>" name="NoTransaksi">
											<select id="comboPupuk" name="KodeBarang" class="form-control"  required>
												<option value="" disabled selected> Pilih Pupuk </option>';
												<?php
													$menu = mysqli_query($koneksi,"SELECT KodeBarang,NamaBarang,Keterangan from mstpupuksubsidi where IsAktif=b'1' ORDER by NamaBarang ASC");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeBarang'] === $RowData['KodeBarang']){

															echo '<option value="'.$kode['KodeBarang'].'" data-satuan="'.$kode['Keterangan'].'" selected>'.$kode['NamaBarang'].'</option>';
														}else{
															echo '<option value="'.$kode['KodeBarang'].'" data-satuan="'.$kode['Keterangan'].'">'.$kode['NamaBarang'].'</option>';
														}
													}
												?>
											</select>
										</div>
									    <div class="form-group">
										  <label>Jumlah Penerimaan</label>
										  <div class="input-group">
											<input type="number" min="0" class="form-control"  placeholder="Jumlah Masuk" name="JumlahMasuk" value="<?php
											echo isset($RowData['JumlahMasuk']) ? $RowData['JumlahMasuk'] : '0';?>" required>
											<div class="input-group-append"><span class="input-group-text"><p id="satuan"><?=@$RowData['Satuan']?></p></span></div>
										  </div>
										</div>
									   
											<?php
												if(@$RowData['NoTransaksi'] != null) {
													echo '<button class="btn btn-success" type="submit" name="SimpanEdit">Simpan</button>';
												}else{
													echo '<button class="btn btn-info" type="submit" name="Simpan">Simpan</button>&nbsp;';
												}
												echo '&nbsp;<a href="Penerimaan.php"><span class="btn btn-warning">Kembali</span></a>';
											?>
										</div>
									</form>
									</div>
								</div>
							</div>
						</div>
					</div>
                  </div>
                </div>
            </div>
          </section>
        </div>
      </div>
    </div>
	
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>	
	<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script>

		// var Satuan = '';

		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			alert(hai);
		});

		$('#comboPupuk').change(function () {
      		Satuan = $(this).find('option:selected').attr('data-satuan');
      		// alert(Satuan);
      		document.getElementById("satuan").innerHTML = Satuan;
     	
    	});
	</script>
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "Penerimaan.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
	<?php
		@$AsalMasuk 			=htmlspecialchars($_POST['AsalMasuk']);
		@$AlamatAsal		 	=htmlspecialchars($_POST['AlamatAsal']);
		@$KodeBarang			=htmlspecialchars($_POST['KodeBarang']);
		@$IDPerson			 	=htmlspecialchars($_POST['IDPerson']);
		@$JumlahMasuk			=htmlspecialchars($_POST['JumlahMasuk']);
		@$NoTransaksi			=htmlspecialchars($_POST['NoTransaksi']);
		
	if(isset($_POST['Simpan'])){	
		// buat kode data timbangan
		$sql = @mysqli_query($koneksi, "SELECT MAX(RIGHT(NoTransaksi,5)) AS kode FROM sirkulasipupuk WHERE  LEFT(NoTransaksi,18)='TRP-$Tahun'"); 
			$nums = @mysqli_num_rows($sql); 
			while($data = @mysqli_fetch_array($sql)){
			if($nums === 0){ $kode = 1; }else{ $kode = $data['kode'] + 1; }
			}
			// membuat kode user
			$bikin_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
			$kode_jadi = "TRP-".$Tahun."-".$bikin_kode;
			
					
		$sql1 	= mysqli_query($koneksi, ("SELECT Harga FROM mstpupuksubsidi WHERE KodeBarang='$KodeBarang'"));
		$res    = mysqli_fetch_array($sql1);
		$NilaiTransaksi = $res[0]*$JumlahMasuk;
						
		$query = mysqli_query($koneksi,"INSERT into sirkulasipupuk(NoTransaksi,TanggalTransaksi,AsalMasuk,AlamatAsal,NilaiTransaksi,HargaSatuan,IDPerson,KodeBarang,JumlahMasuk) 
			VALUES ('$kode_jadi',DATE(NOW()),'$AsalMasuk','$AlamatAsal','$NilaiTransaksi','".$res[0]."','$IDPerson','$KodeBarang','$JumlahMasuk')");
			if($query){
				echo '<script language="javascript">document.location="Penerimaan.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "Penerimaan.php";
					  });
					  </script>';
			}
		// closing connection 
		mysqli_close($koneksi); 
	}
	
	if(isset($_POST['SimpanEdit'])){
		$sql1 	= mysqli_query($koneksi, ("SELECT Harga FROM mstpupuksubsidi WHERE KodeBarang='$KodeBarang'"));
		$res    = mysqli_fetch_array($sql1);
		$NilaiTransaksi = $res[0]*$JumlahMasuk;
		
		//query update
		$query = mysqli_query($koneksi,"UPDATE sirkulasipupuk SET AsalMasuk='$AsalMasuk',AlamatAsal='$AlamatAsal',NilaiTransaksi='$NilaiTransaksi',HargaSatuan='".$res[0]."',KodeBarang='$KodeBarang',JumlahMasuk='$JumlahMasuk' WHERE NoTransaksi='$NoTransaksi'");
		if($query){
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "Penerimaan.php";
				  });
				  </script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "Penerimaan.php";
				  });
				  </script>';
		}
		// closing connection 
		mysqli_close($koneksi); 
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		mysqli_query($koneksi,"DELETE FROM sirkulasipupuk  WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
		echo '<script language="javascript">document.location="Penerimaan.php"; </script>';
		// closing connection 
		mysqli_close($koneksi); 	
	}
	?>
  </body>
</html>