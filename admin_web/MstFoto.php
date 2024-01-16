<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");
include '../library/tgl-indo.php';

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Foto'");
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
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<style>
				
		.Zebra_DatePicker_Icon_Wrapper{
			width:100% !important;
		}
		
		.Zebra_DatePicker_Icon {
			top: 11px !important;
			right: 12px;
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
						<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Foto</span></a>&nbsp;
					</li>
					<li>
						<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo @$Sebutan; ?></span></a>
					</li>
				</ul><br/>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Galeri Foto</h3>
						  </div>
							<div class="card-body">
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Judul..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Album</th>
									  <th>Tanggal </th>
									  <th>Keterangan </th>
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
											$reload = "MstFoto.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM kontenweb WHERE 	JudulKonten LIKE '%$keyword%' and JenisKonten='Foto' ORDER BY TanggalKonten ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MstFoto.php?pagination=true";
											$sql =  "SELECT * FROM kontenweb where JenisKonten='Foto' ORDER BY TanggalKonten ASC";
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
												<strong><?php echo $data ['JudulKonten']; ?></strong>
											</td>
											<td>
												<?php echo TanggalIndo($data ['TanggalKonten']); ?>
											</td>
											<td>
												<?php
													$num_char = 75;
													$cut_text = substr($data['IsiKonten'], 0, $num_char);
													$str_num = str_word_count($data['IsiKonten']);
													if($str_num >= 10){
														if ($data['IsiKonten'][$num_char - 1] != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
														$new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
														$cut_text = substr($data['IsiKonten'], 0, $new_pos);
														}
														echo @$cut_text . '...';	
													}else{
														echo @$data['IsiKonten'];
														
													}?>
											</td>
											<td width="170px">
												<a href="MstFoto.php?id=<?php echo base64_encode($data['KodeKonten']);?>" title='Edit'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a> 
												<!-- Hapus Data-->
												<a href="MstFoto.php?id=<?php echo base64_encode($data['KodeKonten']);?>&aksi=<?php echo base64_encode('Hapus');?>&jns=<?php echo base64_encode($data['JenisKonten']);?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a>
												<!-- Foto Data-->
												<a href="UploadFoto.php?id=<?php echo base64_encode($data['KodeKonten']);?>&jns=<?php echo base64_encode($data['JenisKonten']);?>" title='Edit'><i class='btn btn-success btn-sm' style="border-radius:2px;"><span class='fa fa-images'></span> </i></a> 
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
							<h3 class="h6 text-uppercase mb-0"><?php echo $Sebutan; ?></h3>
						  </div>
							<div class="card-body">
								<form class="form-horizontal" method="post" action="">
								  <div class="form-group row">
									<label class="col-md-2 form-control-label">Album Foto*</label>
									<div class="col-md-10">
									  <input type="text" class="form-control" style="border-radius:2px;" maxlength="255" placeholder="Album Foto" value="<?php echo @$RowData['JudulKonten'];?>" name="JudulKonten" required>
									</div>
								  </div>
								  <div class="form-group row">
									<label class="col-md-2 form-control-label">Keterangan</label>
									<div class="col-md-10">
									  <textarea name="IsiKonten" class="form-control" style="border-radius:2px;" rows="4" required><?php echo @$RowData['IsiKonten']; ?></textarea>
									</div>
								  </div>
								  <hr>
									<div class="text-right">
										<?php
										if(@$_GET['id']==null){
											echo '<input type="hidden" name="JenisKonten" value="Foto"> ';
											echo '<button type="submit" class="btn btn-success" style="border-radius:2px;" name="Simpan">Simpan</button>';
										}else{
											echo '<input type="hidden" name="KodeKonten" value="'.$RowData['KodeKonten'].'"> ';
											echo '<button type="submit" class="btn btn-success" style="border-radius:2px;" name="SimpanEdit">Simpan</button> &nbsp;';
										}
										?>
										<a href="MstFoto.php"><span class="btn btn-danger" style="border-radius:2px;">Batalkan</span></a>
									</div>
								</form>
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
	<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			//$('#Datetime2').Zebra_DatePicker({format: 'Y-m-d H:i', direction: 1});
		});
		  
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MstFoto.php";
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
		@$IsiKonten		 	= htmlspecialchars($_POST['IsiKonten']);
		@$JenisKonten		= $_POST['JenisKonten'];
		@$KodeKonten		= $_POST['KodeKonten'];
		
	//Tambah Data
		if(isset($_POST['Simpan'])){
			include '../library/kode-konten.php';
			$Kode = KodeKonten($JenisKonten, $koneksi);
			
			
			$simpan = mysqli_query($koneksi,"INSERT INTO kontenweb (KodeKonten,TanggalKonten,JenisKonten,JudulKonten,IsiKonten,username,IsAktif) VALUES 
			 ('$Kode','$TanggalTransaksi','$JenisKonten','$JudulKonten','$IsiKonten','$login_id',b'1')");
			
			if($simpan){
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="UploadFoto.php?id='.base64_encode($Kode).'&jns='.base64_encode($JenisKonten).'";</script>';
				
			}else{
				 echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "MstFoto.php";
						  });
						  </script>';
			
			}
		}
	
			
	//Edit Data	
		if(isset($_POST['SimpanEdit'])){
			
			$query = mysqli_query($koneksi,"UPDATE kontenweb SET IsiKonten='$IsiKonten', JudulKonten='$JudulKonten',TanggalKonten='$TanggalTransaksi' WHERE KodeKonten='$KodeKonten' AND JenisKonten='Foto'");
			
			if($query){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MstFoto.php";
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
						window.location.href = "MstFoto.php";
					  });
					  </script>';
			}
		}
	
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Hapus'){
			@$IdKonten 		= htmlspecialchars(base64_decode(@$_GET['id'])); 
			@$JenisKon	 	= htmlspecialchars(base64_decode(@$_GET['jns'])); 
			
			//hapus gambar terlebih dahulu
			$HapusGambar = mysqli_query($koneksi,"SELECT Dokumen FROM detailkonten WHERE KodeKonten='$IdKonten' and JenisKonten='$JenisKon' ");
			$data=mysqli_fetch_array($HapusGambar);
				
			//hapus data agenda
			$hapus = mysqli_query($koneksi,"DELETE FROM detailkonten WHERE KodeKonten='$IdKonten' and JenisKonten='$JenisKon'");
			if($hapus){
				unlink("../assets/galeri/".$data['Dokumen']."");
				unlink("../assets/galeri/thumb_".$data['Dokumen']."");
			
				$hapus2 = mysqli_query($koneksi,"DELETE FROM kontenweb WHERE KodeKonten='$IdKonten' and JenisKonten='$JenisKon'");
				
				echo '<script language="javascript">document.location="MstFoto.php"; </script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="MstFoto.php"; </script>';
			}
		}
	
  ?>
</html>