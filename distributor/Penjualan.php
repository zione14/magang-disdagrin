<?php
include 'akses.php';
include 'aksihitung.php';
$Page = 'Transaksi';
$SubPage='Penjualan';
$Tahun = date("YmdHis");
$now=date('Y-m-d');
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
              <h2 class="no-margin-bottom">Transaksi Pengeluaran Pupuk Subsidi</h2>
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
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><i class="fa fa-fw fa-edit"></i> Entry Transaksi</span></a>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_REQUEST['tr']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Pengeluaran Pupuk</h3>
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
									  <th>Tanggal</th>
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
										@$keyword = $_REQUEST['KodeBarang'];
										@$tgl = $_REQUEST['tgl'];
										$reload = "Penjualan.php?pagination=true&KodeBarang=$keyword&tgl=$tgl";
										$sql =  "SELECT a.NoTransaksi, a.NilaiTransaksi, a.KodeBarang, a.JumlahKeluar, a.TanggalTransaksi, ADDDATE(a.TanggalTransaksi, INTERVAL 3 DAY) as TglEdit, (SELECT Keterangan from mstpupuksubsidi  where KodeBarang=a.KodeBarang) as Satuan,a.HargaSatuan
										FROM sirkulasipupuk a 
										WHERE a.IDPerson='$login_id' and a.JumlahMasuk is NULL ";
										
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
													echo  TanggalIndo($data['TanggalTransaksi']);
												?>
											</td>
											<td>
												<?php echo NamaPupuk($koneksi, $data['KodeBarang']); ?>
											</td>
											<td align="center">
												<?php echo number_format($data['HargaSatuan']);?>
											</td>
											<td align="center">
												<?php echo number_format($data['JumlahKeluar']).' '.$data['Satuan'];?>
											</td>
											<td align="right">
												<?php echo number_format($data['NilaiTransaksi']);?>
											</td>
											<td width="150px" align="center">	
												<?php
													if (strtotime($data['TglEdit']) > strtotime($now) ){
													
														echo '<a href="Penjualan_edit.php?id='.base64_encode($data['NoTransaksi']).'" title="Edit"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
													
														echo ' <a href="Penjualan.php?id='.base64_encode($data['NoTransaksi']).'&aksi='.base64_encode('Hapus').'&view=1" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
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
						<div class="tab-pane fade <?php if(@$_REQUEST['tr'] != null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Entry Transaksi Pengeluaran</h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									   <form method="post" action="">
									    <div class="form-group ">	
											<label>Nama Pupuk</label>
											<input type="hidden" class="form-control"  value="<?php echo @$login_id;?>" name="IDPerson">
											<input type="hidden" class="form-control"  value="<?php echo @$RowData['NoTransaksi'];?>" name="NoTransaksi">
											<select name="tr" id="comboPupuk" onchange="this.form.submit()" class="form-control" required>
												<option value="" disabled selected> Pilih Pupuk </option>';
												<?php
													$menu = mysqli_query($koneksi,"SELECT KodeBarang,NamaBarang,Keterangan from mstpupuksubsidi where IsAktif=b'1' ORDER by NamaBarang ASC");
													while($kode = mysqli_fetch_array($menu)){

														if($kode['KodeBarang'] === $_REQUEST['tr']){
															echo '<option value="'.$kode['KodeBarang'].'" data-satuan="'.$kode['Keterangan'].'" selected>'.$kode['NamaBarang'].'</option>';
														}else{
															echo '<option value="'.$kode['KodeBarang'].'" data-satuan="'.$kode['Keterangan'].'">'.$kode['NamaBarang'].'</option>';
														}
													}
												?>
											</select>
										</div>
										<div class="form-group">
											<?php
											@$masuk = stokSekarang($koneksi, $login_id, $_REQUEST['tr']);
											@$satuan = satuanBarang($koneksi, $_REQUEST['tr']);
											@$penerimaan = isset($masuk) ? $masuk : '0';
											echo isset($_REQUEST['tr']) ? 'Stok Pupuk : '.$penerimaan.' '.$satuan : '';?>
											<input type="hidden"  class="form-control"  name="JumlahMasuk" value="<?php
											echo isset($_REQUEST['tr']) ? stokSekarang($koneksi, $login_id, $_REQUEST['tr']) : '0';?>" required>
											<input type="hidden"  class="form-control"  name="HargaSatuan" value="<?php
											echo isset($_REQUEST['tr']) ? HargaSatuan($koneksi, $_REQUEST['tr']) : '0';?>" required>
											<input type="hidden"  class="form-control"  name="IDPerson" value="<?php echo $login_id;?>" required>
										</div>
										<h5>Detil Penjualan</h5>					
										<div class="table-responsive">
											<table class="table table-striped" id=datainsert>
											  <thead>
												<tr>
												  <th></th>
												  <th>Nama Toko</th>
												  <th>Jumlah Keluar</th>
												  <th>Aksi</th>
												</tr>
											  </thead>
											<tbody>
											<tr>
												<td>
													<input type="hidden" name='urut[]' class="form-control input-md" placeholder="1"value="1" readonly>
												</td>
												<td>
													<select class="form-control" name="toko[]" autocomplete="off" required>
													<option value="" disabled selected>--- Pilih Toko ---</option>
														<?php
															$menu = mysqli_query($koneksi,"SELECT * FROM mstperson where ID_Distributor='$login_id' and IsVerified=b'1' ORDER BY NamaPerson ASC");
															while($kode = mysqli_fetch_array($menu)){
																echo  '<option value="'.$kode['IDPerson'].'">'.$kode['NamaPerson'].'</option>';
															}
														?>
													</select>
												</td>
												<td>
													<input type="number" name='jumlah[]' class="form-control input-md" required>
												</td>
												
												
												<td>
													<i class="btn-secondary btn" style="border-radius: 3px" onclick=tambah()><span class='fa fa-plus'></span></i>
												</td>
											</tr>
											</tbody>
										</table>	
										</div>
											<?php
												echo '<button class="btn btn-info" type="submit" name="Simpan">Simpan</button>&nbsp;';
												echo '&nbsp;<a href="Penjualan.php"><span class="btn btn-warning">Kembali</span></a>';
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
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
		});


	
		var idrow = 2;
		function tambah(){
			var x=document.getElementById('datainsert').insertRow(idrow);
			var td1=x.insertCell(0);
			var td2=x.insertCell(1);
			var td3=x.insertCell(2);
			var td4=x.insertCell(3);

			td1.innerHTML="<input type='hidden' name='urut[]' class='form-control input-md' value='"+idrow+"' readonly>";
			td2.innerHTML='<select class="form-control" name="toko[]" autocomplete="off" required><option value="" disabled selected>--- Pilih Toko ---</option><?php
			  $menu = mysqli_query($koneksi,"SELECT * FROM mstperson where ID_Distributor='$login_id' and IsVerified=b'1' ORDER BY NamaPerson ASC");
				while($kode = mysqli_fetch_array($menu)){
					echo  '<option value="'.$kode['IDPerson'].'">'.$kode['NamaPerson'].'</option>';
				}
			?></select>';
			td3.innerHTML="<input type='number' name='jumlah[]' class='form-control input-md' required>";
			td4.innerHTML="<i class='btn-danger btn' style='border-radius: 3px;' onclick=remove()><span class='fa fa-minus'></span></i>";
			idrow++;
		}

		function remove(){
			if(idrow>2){
				var x=document.getElementById('datainsert').deleteRow(idrow-1);
				idrow--;
			}
		}
		
		
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "Penjualan.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
	<?php
		@$KodeBarang			=htmlspecialchars($_POST['tr']);
		@$JumlahMasuk			=htmlspecialchars($_POST['JumlahMasuk']);
		@$HargaSatuan			=htmlspecialchars($_POST['HargaSatuan']);
		@$IDPerson			 	=htmlspecialchars($_POST['IDPerson']);
		// @$JumlahMasuk			=htmlspecialchars($_POST['JumlahMasuk']);
		// @$NoTransaksi			=htmlspecialchars($_POST['NoTransaksi']);
		
	if(isset($_POST['Simpan'])){
		foreach($_POST['urut'] as $key => $value){
			$jumlah[]		= $_POST["jumlah"][$key];
		}
		$JumlahKeluar = array_sum($jumlah);
		
		if ($JumlahMasuk <= $JumlahKeluar){
			echo '<script type="text/javascript">
					swal("Peringatan", "Stok Barang Tidak Mencukupi","warning");
				  </script>';
		}else{
			$NilaiTransaksi = $HargaSatuan*$JumlahKeluar;
			
			// buat kode data timbangan
			$sql = @mysqli_query($koneksi, "SELECT MAX(RIGHT(NoTransaksi,5)) AS kode FROM sirkulasipupuk WHERE  LEFT(NoTransaksi,18)='TRP-$Tahun'"); 
			$nums = @mysqli_num_rows($sql); 
			while($data = @mysqli_fetch_array($sql)){
			if($nums === 0){ $kode = 1; }else{ $kode = $data['kode'] + 1; }
			}
			// membuat kode user
			$bikin_kode = str_pad($kode, 5, "0", STR_PAD_LEFT);
			$kode_jadi = "TRP-".$Tahun."-".$bikin_kode;
				
				$query = mysqli_query($koneksi,"
				INSERT into sirkulasipupuk
				(NoTransaksi,TanggalTransaksi,NilaiTransaksi,HargaSatuan,IDPerson,KodeBarang,JumlahKeluar) 
				VALUES 
				('$kode_jadi',DATE(NOW()),'$NilaiTransaksi','$HargaSatuan','$IDPerson','$KodeBarang','$JumlahKeluar')");
				
				if($query){
					
					foreach($_POST['urut'] as $key => $value){
							$idtoko			=$_POST["toko"][$key];
							$jumlahbarang	= $_POST["jumlah"][$key];
							
							if($value){
								$keluar=mysqli_query($koneksi,"INSERT INTO detilkeluar  (NoTransaksi,NoUrut,JumlahBarang,IDPerson) VALUES('$kode_jadi','$value','$jumlahbarang','$idtoko')");
							}
					}	
					echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Berhasil!",
							text: " ",
							type: "success"
						  },
						  function () {
							window.location.href = "Penjualan.php";
						  });
						  </script>';
				}
				// closing connection 
				mysqli_close($koneksi); 
				
		}
		
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
					window.location.href = "Penjualan.php";
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
					window.location.href = "Penjualan.php";
				  });
				  </script>';
		}
		// closing connection 
		mysqli_close($koneksi); 
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		mysqli_query($koneksi,"DELETE FROM detilkeluar  WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
		mysqli_query($koneksi,"DELETE FROM sirkulasipupuk  WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
		echo '<script language="javascript">document.location="Penjualan.php"; </script>';
		// closing connection 
		mysqli_close($koneksi); 	
	}
	?>
  </body>
</html>