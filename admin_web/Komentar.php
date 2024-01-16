<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");
include '../library/tgl-indo.php';

if(@$_GET['id']!=null){

	$Edit = mysqli_query($koneksi,"SELECT a.*,b.JudulKonten  
	FROM tanggapankonten a 
	JOIN kontenweb b ON (a.KodeKonten,a.JenisKonten)=(b.KodeKonten,b.JenisKonten) 
	WHERE a.IsAktif='1' AND  a.KodeTanggapan='".base64_decode($_GET['id'])."'
	ORDER BY TanggalKomentar DESC");
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
							<h3 class="h6 text-uppercase mb-0">Komentar Pengunjung</h3>
						  </div>
							<div class="card-body">
								<div class="col-lg-4 offset-lg-8">
									<!-- <form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Judul..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" style="border-radius:2px;" type="submit">Cari</button>
											</span>
										</div>
									</form> -->
								</div>
								<div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Pengunjung</th>
									  <th>Tanggal </th>
									  <th>Isi </th>
									  <th>Judul Konten </th>
									  <th>Jenis Konten </th>
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
											$reload = "Komentar.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM tanggapankonten WHERE 	JudulKonten LIKE '%$keyword%' ORDER BY TanggalKomentar DESC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "Komentar.php?pagination=true";
											$sql =  "SELECT a.*,b.JudulKonten  
											FROM tanggapankonten a 
											JOIN kontenweb b ON (a.KodeKonten,a.JenisKonten)=(b.KodeKonten,b.JenisKonten) 
											WHERE a.EmailPengirim != 'Emoticon'
											ORDER BY TanggalKomentar DESC";
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
												<strong><?php echo $data ['EmailPengirim']; ?></strong>
											</td>
											<td>
												<?php echo TanggalIndo($data ['TanggalKomentar']); ?>
											</td>
											<td>
												<?php
													$num_char = 75;
													$cut_text = substr($data['IsiKomentar'], 0, $num_char);
													$str_num = str_word_count($data['IsiKomentar']);
													if($str_num >= 10){
														if ($data['IsiKomentar']{$num_char - 1} != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
														$new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
														$cut_text = substr($data['IsiKomentar'], 0, $new_pos);
														}
														echo @$cut_text . '...';	
													}else{
														echo @$data['IsiKomentar'];
														
													}?>
											</td>
											<td>
												<?php echo $data ['JudulKonten']; ?>
											</td>
											<td>
												<?php echo $data ['JenisKonten']; ?>
											</td>
											<td>
												<?php 
													if($data['IsiTanggapan'] == null OR $data['IsiTanggapan'] == '') {
														echo '<p class="text-danger"> Belum ada tanggapan</p>';
													}else{
														echo '<p class="text-success"> Sudah Ditanggapi</p>';
													}


												?>
											</td>
											<td width="170px">
												<a href="Komentar.php?id=<?php echo base64_encode($data['KodeTanggapan']);?>" title='Beri Tanggapan'><i class='btn btn-info btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a> 
												<!-- Hapus Data-->

												<?php if ($data['IsAktif'] == 1) : ?>
													<a href="Komentar.php?kd=<?php echo base64_encode($data['KodeTanggapan']);?>&aksi=<?php echo base64_encode('NonAktif');?>&jns=<?php echo base64_encode($data['JenisKonten']);?>" title='Sembunyikan' onclick="return confirmation()"><i class='btn btn-success btn-sm' style="border-radius:2px;"><span class='fa fa-eye'></span> </i></a>
												<?php else : ?>
													<a href="Komentar.php?kd=<?php echo base64_encode($data['KodeTanggapan']);?>&aksi=<?php echo base64_encode('Aktif');?>&jns=<?php echo base64_encode($data['JenisKonten']);?>" title='Tampilkan' onclick="return confirmation1()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-eye-slash'></span> </i></a>

												<?php endif; ?>



												
												
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
							<h3 class="h6 text-uppercase mb-0">Beri Tanggapan Komentar</h3>
						  </div>
							<div class="card-body">
								<form class="form-horizontal" method="post" action="">

								  <label><strong>Judul</strong></label>
								  <p><?php echo $RowData['JudulKonten']; ?></p>

								  <label><strong>Tanggal Pengiriman</strong></label>
								  <p><?php echo TanggalIndo($RowData['TanggalKomentar']); ?> | <?php echo substr($RowData['TanggalKomentar'],11,19); ?></p>	
								  
								  <label><strong>Isi Komentar</strong></label>
								  <p><?php echo $RowData['IsiKomentar']; ?></p>	

								  <h5>Beri Tanggapan</h5>
								  <textarea name="IsiTanggapan" class="ckeditor form-control" style="border-radius:2px;" rows="4" required><?php echo @$RowData['IsiTanggapan']; ?></textarea>
								  <hr>
									<div class="text-right">
										<?php
										
											echo '<input type="hidden" name="KodeTanggapan" value="'.$RowData['KodeTanggapan'].'"> ';
											echo '<button type="submit" class="btn btn-success" style="border-radius:2px;" name="SimpanEdit">Simpan</button> &nbsp;';
										
										?>
										<a href="Komentar.php"><span class="btn btn-danger" style="border-radius:2px;">Batalkan</span></a>
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
			var answer = confirm("Apakah Anda yakin mengnonaktifkan data ini ?")
			if (answer == true){
				window.location = "Komentar.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
		function confirmation() {
			var answer = confirm("Apakah Anda yakin mengaktifkan data ini ?")
			if (answer == true){
				window.location = "Komentar.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
	
  </body>
  <?php 
	//POST DATA
		@$KodeTanggapan	= htmlspecialchars($_POST['KodeTanggapan']);
		@$IsiTanggapan	= $_POST['IsiTanggapan'];
		@$TglTransaksi 	= date('Y-m-d H:i:s');
		
			
	//Edit Data	
		if(isset($_POST['SimpanEdit'])){
			//update data user login berdasarkan username yng di pilih
			$query = mysqli_query($koneksi,"UPDATE tanggapankonten SET IsiTanggapan='$IsiTanggapan', UserName='$login_id', TanggalTanggapan='$TglTransaksi' WHERE KodeTanggapan='$KodeTanggapan'");
			
			if($query){
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "Komentar.php";
					  });
					  </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "Komentar.php";
					  });
					  </script>';
			}
		}
	
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='NonAktif'){
			@$IdKomentar	= htmlspecialchars(base64_decode(@$_GET['kd'])); 
			@$JenisKon	 	= htmlspecialchars(base64_decode(@$_GET['jns'])); 
				
			//hapus data agenda
			$hapus = mysqli_query($koneksi,"UPDATE tanggapankonten set IsAktif=b'0' WHERE KodeTanggapan='$IdKomentar' and JenisKonten='$JenisKon'");
			if($hapus){
				echo '<script language="javascript">document.location="Komentar.php"; </script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="Komentar.php"; </script>';
			}
		}

	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Aktif'){
			@$IdKomentar	= htmlspecialchars(base64_decode(@$_GET['kd'])); 
			@$JenisKon	 	= htmlspecialchars(base64_decode(@$_GET['jns'])); 
				
			//hapus data agenda
			$hapus = mysqli_query($koneksi,"UPDATE tanggapankonten set IsAktif=b'1' WHERE KodeTanggapan='$IdKomentar' and JenisKonten='$JenisKon'");
			if($hapus){
				echo '<script language="javascript">document.location="Komentar.php"; </script>';
			}else{
				echo '<script language="javascript">alert("Hapus Data Gagal !"); document.location="Komentar.php"; </script>';
			}
		}
	
  ?>
</html>