<?php
include 'akses.php';
include 'aksihitung.php';
$Page = 'Transaksi';
$SubPage='Penjualan';
$Tahun=date('Y');
$now=date('Y-m-d');
@$NoTransaksi= htmlspecialchars(base64_decode($_GET['id']));

	
@$Edit = mysqli_query($koneksi,"SELECT KodeBarang,NoTransaksi FROM sirkulasipupuk WHERE NoTransaksi='$NoTransaksi'");
@$RowData = mysqli_fetch_assoc($Edit);
	

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
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
              <h2 class="no-margin-bottom">Transaksi Penjualan Pupuk Subsidi</h2>
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
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Edit Penjualan Pupuk</h3>
							</div>
							<div class="card-body">							  
								<div class="row">
								  <div class="col-lg-12">
									   <form method="post" action="">
									    <div class="form-group ">	
											<label>Nama Pupuk</label>
											<select name="KodeBarang" onchange="this.form.submit()" class="form-control" disabled>
												<option value="" disabled selected> Pilih Pupuk </option>';
												<?php
													$menu = mysqli_query($koneksi,"SELECT KodeBarang,NamaBarang from mstpupuksubsidi where IsAktif=b'1' ORDER by NamaBarang ASC");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeBarang'] === $RowData['KodeBarang']){
															echo "<option value=\"".$kode['KodeBarang']."\" selected='selected'>".$kode['NamaBarang']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodeBarang']."\" >".$kode['NamaBarang']."</option>\n";
														}
													}
												?>
											</select>
										</div>
										<div class="form-group">
											<?php
											@$masuk = stokSekarang($koneksi, $login_id, $RowData['KodeBarang']);
											@$penerimaan = isset($masuk) ? $masuk : '0';
											@$satuan = satuanBarang($koneksi, $RowData['KodeBarang']);
											echo isset($RowData['KodeBarang']) ? 'Jumlah Penerimaan Pupuk : '.$penerimaan.' '.$satuan : '';?>
											
										</div>
										<hr><h5>Detil Penjualan</h5>					
										<div class="table-responsive">
											<table class="table table-striped" id=datainsert>
											  <thead>
												<tr>
												  <th>Nama Toko</th>
												  <th>Jumlah Keluar</th>
												  <th>Aksi</th>
												</tr>
											  </thead>
											  <tbody>
												<?php 
												$no = 1;
												$sql =mysqli_query($koneksi, "SELECT IDPerson,JumlahBarang,NoUrut From detilkeluar where NoTransaksi='$NoTransaksi'");
												while($res = mysqli_fetch_array($sql)){
												?>
												<tr>
													<td><?php echo NamaPerson($koneksi, $res['IDPerson']); ?></td>
													<td align="center"><?php echo number_format($res['JumlahBarang']); ?></td>
													<td align="center"><a href="Penjualan_edit.php?tr=<?php echo base64_encode($NoTransaksi);?>&no=<?php echo base64_encode($res['NoUrut']);?>&bg=<?php echo base64_encode($RowData['KodeBarang']);?>&jm=<?php echo base64_encode($res['JumlahBarang']);?>&aksi=Hapus" title='Hapus'><i class='btn btn-danger btn-sm'><span class='fa fa-trash'></span></i></a> 
													</td>
												</tr>
												<?php } ?>
												<form method="post" action="">
												<tr>
													<td>
														<div class="form-group-material">
														  <select name="Toko" class="form-control" required>
															<option value="" disabled selected> Pilih Toko </option>';
															<?php
																$menu = mysqli_query($koneksi,"SELECT * FROM mstperson where ID_Distributor='$login_id' and IsVerified=b'1' ORDER BY NamaPerson ASC");
																	while($kode = mysqli_fetch_array($menu)){
																		echo  '<option value="'.$kode['IDPerson'].'">'.$kode['NamaPerson'].'</option>';
																	}
															?>
														  </select>
														</div>
													</td>
													<td>
														<div class="form-group-material">
														  <input type="number" name="JumlahBarang" class="form-control" placeholder="Jumlah Barang (KG)" required>
														  <input type="hidden"  class="form-control"  name="JumlahMasuk" value="<?php
														echo isset($RowData['KodeBarang']) ? stokSekarang($koneksi, $login_id, $RowData['KodeBarang']) : '0';?>" required>
														<input type="hidden"  class="form-control"  name="HargaSatuan" value="<?php
														echo isset($RowData['KodeBarang']) ? HargaSatuan($koneksi, $RowData['KodeBarang']) : '0';?>" required>
														<input type="hidden" class="form-control"  value="<?php echo $RowData['KodeBarang'];?>" name="KodeBarang">
														<input type="hidden" class="form-control"  value="<?php echo $login_id;?>" name="IDPerson">
														<input type="hidden" class="form-control"  value="<?php echo $RowData['NoTransaksi'];?>" name="NoTransaksi">
														</div>
													</td>
													<td align="center">
														<button type="submit" class="btn btn-success btn-sm" name="Simpan"><span class='fa fa-plus'></span></i></button>
													</td>
												</tr>
												</form>
											   </tbody>
											</table>	
										</div>
										<?php
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
	<script type="text/javascript">
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
		@$KodeBarang			=htmlspecialchars($_POST['KodeBarang']);
		@$JumlahMasuk			=htmlspecialchars($_POST['JumlahMasuk']);
		@$HargaSatuan			=htmlspecialchars($_POST['HargaSatuan']);
		@$IDPerson			 	=htmlspecialchars($_POST['IDPerson']);
		@$Toko					=htmlspecialchars($_POST['Toko']);
		@$NoTrans				=htmlspecialchars($_POST['NoTransaksi']);
		@$JumlahBarang			=htmlspecialchars($_POST['JumlahBarang']);
		
	if(isset($_POST['Simpan'])){
		
		if ($JumlahMasuk <= $JumlahBarang){
			echo '<script language="javascript">alert("Jumlah Tidak Sesuai");document.location="Penjualan_edit.php?id='.base64_encode($NoTrans).'"; </script>';
		}else{
			$stokPenjualan = stokPenjualan($koneksi, $login_id, $KodeBarang, $NoTrans);
			$JumlahKeluar  = $stokPenjualan+$JumlahBarang;
			$NilaiTransaksi = $HargaSatuan*$JumlahKeluar;
			
				$query = mysqli_query($koneksi,"
				UPDATE sirkulasipupuk set NilaiTransaksi='$NilaiTransaksi',HargaSatuan='$HargaSatuan',JumlahKeluar='$JumlahKeluar' WHERE NoTransaksi='$NoTrans'");
				
				if($query){
					$AmbilNoUrut=mysqli_query($koneksi,"SELECT MAX(NoUrut) as NoSaatIni FROM detilkeluar WHERE NoTransaksi='$NoTrans'");
					$Data=mysqli_fetch_assoc($AmbilNoUrut);
					$NoSekarang = $Data['NoSaatIni'];
					$Urutan = $NoSekarang+1;
					
					$keluar=mysqli_query($koneksi,"INSERT INTO detilkeluar  (NoTransaksi,NoUrut,JumlahBarang,IDPerson) VALUES('$NoTrans','$Urutan','$JumlahBarang','$Toko')");
					
					echo '<script language="javascript">document.location="Penjualan_edit.php?id='.base64_encode($NoTrans).'";</script>';
				}
				
				// closing connection 
				mysqli_close($koneksi); 
				
		}
		
	}
	
	if(@$_GET['aksi']=='Hapus'){

		$delete = mysqli_query($koneksi,"DELETE FROM detilkeluar  WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."' and NoUrut='".htmlspecialchars(base64_decode($_GET['no']))."'");
		
		if ($delete) {
			$HargaSatuan = HargaSatuan($koneksi, htmlspecialchars(base64_decode($_GET['bg'])));
			$stokPenjualan = stokPenjualan($koneksi, $login_id, htmlspecialchars(base64_decode($_GET['bg'])), htmlspecialchars(base64_decode($_GET['tr'])));
			$JumlahKeluar	= $stokPenjualan-htmlspecialchars(base64_decode($_GET['jm']));
			$NilaiTransaksi = $HargaSatuan*$JumlahKeluar;
			
			mysqli_query($koneksi,"update sirkulasipupuk set NilaiTransaksi='$NilaiTransaksi',HargaSatuan='$HargaSatuan',JumlahKeluar='$JumlahKeluar' WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."'");
			echo '<script language="javascript">document.location="Penjualan.php"; </script>';
			// closing connection 
			mysqli_close($koneksi); 	
		}
		
		
		
	}
	?>
  </body>
</html>