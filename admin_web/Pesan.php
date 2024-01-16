<?php 
include 'akses.php';
$Page = 'Pesan';
$TanggalTransaksi	= date("Y-m-d H:i:s");
include '../library/tgl-indo.php';

if(@$_GET['id']!=null){
	$Edit = mysqli_query($koneksi,"SELECT * FROM contact WHERE KodeContact='".base64_decode($_GET['id'])."'");
	$RowData = mysqli_fetch_assoc($Edit);
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
	<style>
		label{
			font-weight: bold;
		}
		
	</style>
  </head>
  <body>
    <!-- navbar-->
    <!-- header -->
	 <?php include 'header.php';?>
    <!-- header -->
    <div class="d-flex align-items-stretch">
	<!-- menu -->
	 <?php include 'menu.php';?>
    <!-- menu -->
      <div class="page-holder w-100 d-flex flex-wrap">
        <div class="container-fluid px-xl-5">
          <section class="py-5">
            <div class="row">
              <!-- Form Elements -->
              <div class="col-lg-12 mb-5">
				<ul class="nav nav-pills">
					<li <?php if(@$id==null){echo 'class="active"';} ?>>
						<a href="#home-pills" data-toggle="tab"></a>&nbsp;
					</li>
					<li>
						<a href="#tambah-user" data-toggle="tab"></span></a>
					</li>
				</ul>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Pesan Testimoni</h3>
						  </div>
							<div class="card-body">
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" style="border-radius:2px;" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
								<div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Pengirim</th>
									  <th>Tanggal dikirim</th>
									  <th>Status</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$kosong=null;
										if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
											// jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)pakai ini
											$keyword=$_REQUEST['keyword'];
											$reload = "Pesan.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM contact WHERE Nama LIKE '%$keyword%' OR TglTransaksi LIKE '%$keyword%' ORDER BY TglTransaksi ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "Pesan.php?pagination=true";
											$sql =  "SELECT * FROM contact ORDER BY TglTransaksi ASC";
											@$result = mysqli_query($koneksi,$sql);
										}
										
										//pagination config start
										$rpp = 15; // jumlah record per halaman
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
										if($tcount==0){
											echo '<tr><td colspan="8" align="center"><strong>Tidak ada data</strong></td></tr>';
										}else{
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo $data ['Nama']; ?></strong>
											</td>
											<td>
												<strong><?php echo TanggalIndo($data ['TglTransaksi']); ?></strong>
											</td>
											
											<td>
												<?php 
												if($data ['IsDibaca']=='1'){
													echo '<i class="btn btn-primary btn-sm">Terbaca</i>';
												}else{
														echo '<i class="btn btn-success btn-sm">Baru</i>';
												} ?>
											</td>
											<td width="150px">
												<a href="Pesan.php?id=<?php echo base64_encode($data['KodeContact']);?>" title='Baca Pesan'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fas fa-book-reader'></span> </i></a> 
												
												<!-- Hapus Data-->
												<a href="Pesan.php?id=<?php echo base64_encode($data['KodeContact']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
												
											
																						
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										}
										?>
									</tbody>
								</table>
								<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
							  </div>
							</div>
						</div>
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Detil Pesan <?php echo $RowData['Nama']; ?></h3>
						  </div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-8 alert alert-success" >
									<?php 
										if($RowData['IsDibaca']=='0'){
											mysqli_query($koneksi,"UPDATE contact SET IsDibaca=b'1' WHERE KodeContact='".$RowData['KodeContact']."'");
									
										} 
									?>
										<label>Pengirim</label>
										<p><?php echo $RowData['Nama']; ?> Pada </p>
										
										<label>Tanggal Pengiriman</label>
										<p><?php echo TanggalIndo($RowData['TglTransaksi']); ?> | <?php echo substr($RowData['TglTransaksi'],11,19); ?></p>
										
										<label>Email</label>
										<p><?php echo $RowData['Email']; ?></p>
										<hr/>
										<label>Subject</label>
										<p><?php echo $RowData['Subjek']; ?></p>
										
										<label>Isi Pesan</label>
										<p><?php echo $RowData['IsiPesan']; ?></p>
										<hr/>
										<div class="text-right">
											<a href="mailto:<?php echo $RowData['Email'];?>" target="_BLANK"><button class="btn btn-success" style="border-radius:2px;">Balas</button></a>
											<a href="Pesan.php"><button class="btn btn-danger" style="border-radius:2px;">Kembali</button></a>
										</div>
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
       <?php include 'footer.php';?>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="assets/vendor/chart.js/Chart.min.js"></script>
    <script src="assets/js/front.js"></script>
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<!-- ckeditor JS -->
	<script  src="../library/ckeditor/Fix.js"></script>
	<script type="text/javascript" src="../library/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "Pesan.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
   
  </body>
  <?php 
	//POST DATA
		@$JudulKonten		= htmlspecialchars($_POST['JudulKonten']);
		@$IsiKonten		 	= $_POST['IsiKonten'];
		@$JenisKonten		= $_POST['JenisKonten'];
		@$KodeKonten		= $_POST['KodeKonten'];
		
	//Tambah Data
		if(isset($_POST['Simpan'])){
			include '../library/kode-konten.php';
			$Kode = KodeKonten($JenisKonten, $koneksi);
			
			
			$simpan = mysqli_query($koneksi,"INSERT INTO kontenweb (KodeKonten,TanggalKonten,JenisKonten,JudulKonten,IsiKonten,username,IsAktif) VALUES 
			 ('$Kode','$TanggalTransaksi','$JenisKonten','$JudulKonten','$IsiKonten','$login_id',b'0')");
			
			if($simpan){
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="MstSlider.php?id='.base64_encode($Kode).'";</script>';
				
			}else{
				 echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "MstSlider.php";
						  });
						  </script>';
			
			}
		}
	
			
	//Edit Data	
		if(isset($_POST['SimpanEdit'])){
			//update data user login berdasarkan username yng di pilih
			$query = mysqli_query($koneksi,"UPDATE kontenweb SET IsiKonten='$IsiKonten', JudulKonten='$JudulKonten',TanggalKonten='$TanggalTransaksi' WHERE KodeKonten='$KodeKonten' AND JenisKonten='Slider'");
			
			if($query){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MstSlider.php";
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
						window.location.href = "MstSlider.php";
					  });
					  </script>';
			}
		}
	
	//hapus data
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		@$KodeContact 		= stripslashes(base64_decode(@$_GET['id'])); 
		
		$hapus = mysqli_query($koneksi,"DELETE FROM contact WHERE KodeContact='$KodeContact'");
		if($hapus){
					
			echo '<script language="javascript">document.location="Pesan.php"; </script>';
		}else{
			echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="Pesan.php"; </script>';
		}
	}
	
  ?>
</html>