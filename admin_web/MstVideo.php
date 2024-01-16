<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Video'");
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
						<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Video</span></a>&nbsp;
					</li>
					<li>
						<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo @$Sebutan; ?></span></a>
					</li>
				</ul><br/>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Master Video</h3>
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
									  <th>Judul Video</th>
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
											$reload = "MstSlider.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM kontenweb WHERE 	JudulKonten LIKE '%$keyword%' and JenisKonten='Video' ORDER BY TanggalKonten ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MstSlider.php?pagination=true";
											$sql =  "SELECT * FROM kontenweb where JenisKonten='Video' ORDER BY TanggalKonten ASC";
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
											<!--<iframe width="300" height="150" src="<?php echo $data['Gambar1']; ?>" frameborder="0" allowfullscreen></iframe>-->
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
														
													}
												?>
											</td>
											<td width="170px">
												<a href="MstVideo.php?id=<?php echo base64_encode($data['KodeKonten']);?>" title='Edit'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a> 
												
												<!-- Hapus Data-->
												<a href="MstVideo.php?id=<?php echo base64_encode($data['KodeKonten']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
												
												<a href="#" class='open_modal' data-video='<?php echo $data['Gambar1'];?>' data-judulkonten='<?php echo $data['JudulKonten'];?>'><span class="btn btn-success btn-sm  o-clock-1" style="border-radius:2px;" title="View Video"></span></a></td></a>
											
																						
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
									<label class="col-md-2 form-control-label">Judul Video*</label>
									<div class="col-md-10">
									  <input type="text" class="form-control" style="border-radius:2px;" maxlength="255" placeholder="Judul Video" value="<?php echo @$RowData['JudulKonten'];?>" name="JudulKonten" required>
									</div>
								  </div>
								   <div class="form-group row">
									<label class="col-md-2 form-control-label">URL Video Youtube*</label>
									<div class="col-md-10">
									  <input type="text" class="form-control" style="border-radius:2px;" maxlength="255" placeholder="https://www.youtube.com/embed/O5x-cXkk3EA" value="<?php echo @$RowData['Gambar1'];?>" name="Gambar1" required>
									</div>
								  </div> <div class="form-group row">
									<label class="col-md-2 form-control-label">Keterangan</label>
									<div class="col-md-10">
									  <textarea name="IsiKonten" class="form-control" style="border-radius:2px;" rows="4" required><?php echo @$RowData['IsiKonten']; ?></textarea>
									</div>
								  </div>
								  <hr>
									<div class="text-right">
										<?php
										if(@$_GET['id']==null){
											echo '<input type="hidden" name="JenisKonten" value="Video"> ';
											echo '<button type="submit" class="btn btn-success" style="border-radius:2px;" name="Simpan">Simpan</button>';
										}else{
											echo '<input type="hidden" name="KodeKonten" value="'.$RowData['KodeKonten'].'"> ';
											echo '<button type="submit" class="btn btn-success" style="border-radius:2px;" name="SimpanEdit">Simpan</button> &nbsp;';
										}
										?>
										<a href="MstVideo.php"><span class="btn btn-danger" style="border-radius:2px;">Batalkan</span></a>
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
	
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
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
	 $(document).ready(function () {
	   $(".open_modal").click(function(e) {
		  var id_video = $(this).data("video");
		  var judul_konten = $(this).data("judulkonten");
		 
		 
			   $.ajax({
					   url: "Modal/ViewVideo.php",
					   type: "GET",
					   data : {Gambar1: id_video, JudulKonten: judul_konten},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MstVideo.php";
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
		@$Gambar1			= $_POST['Gambar1'];
		@$KodeKonten		= $_POST['KodeKonten'];
		
	//Tambah Data
		if(isset($_POST['Simpan'])){
			include '../library/kode-konten.php';
			$Kode = KodeKonten($JenisKonten, $koneksi);
			
			
			$simpan = mysqli_query($koneksi,"INSERT INTO kontenweb (KodeKonten,Gambar1,TanggalKonten,JenisKonten,JudulKonten,IsiKonten,username,IsAktif) VALUES 
			 ('$Kode','$Gambar1','$TanggalTransaksi','$JenisKonten','$JudulKonten','$IsiKonten','$login_id',b'1')");
			
			if($simpan){
				echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="MstVideo.php";</script>';
				
			}else{
				 echo '<script type="text/javascript">
						  sweetAlert({
							title: "Simpan Data Gagal!",
							text: " ",
							type: "error"
						  },
						  function () {
							window.location.href = "MstVideo.php";
						  });
						  </script>';
			
			}
		}
	
			
	//Edit Data	
		if(isset($_POST['SimpanEdit'])){
			//update data user login berdasarkan username yng di pilih
			$query = mysqli_query($koneksi,"UPDATE kontenweb SET IsiKonten='$IsiKonten', JudulKonten='$JudulKonten',TanggalKonten='$TanggalTransaksi', Gambar1='$Gambar1' WHERE KodeKonten='$KodeKonten' AND JenisKonten='Video'");
			
			if($query){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MstVideo.php";
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
						window.location.href = "MstVideo.php";
					  });
					  </script>';
			}
		}
	
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Hapus'){
			
			
			$hapus = mysqli_query($koneksi,"DELETE FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Video'");
			if($hapus){
			
				echo '<script language="javascript">document.location="MstVideo.php"; </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MstVideo.php";
					  });
					  </script>';
			}
		}
	
  ?>
</html>