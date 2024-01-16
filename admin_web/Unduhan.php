<?php 
include 'akses.php';
$Page = 'Konten';
$TanggalTransaksi	= date("Y-m-d H:i:s");

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
						<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Dokumen</span></a>&nbsp;
					</li>
					<li>
						<a href="#" class='open_modal' data-judul='' data-tahun='' data-dokumen='' data-kode='' data-jenis='Dokumen'><span class="btn btn-primary">Tambah Data</span></a>
					</li>
				</ul><br/>
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">File Unduhan</h3>
						  </div>
							<div class="card-body">
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Dokumen..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama </th>
									  <!-- <th>Tahun </th> -->
									  <th>Dokumen </th>
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
											$reload = "Unduhan.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM kontenweb WHERE 	JudulKonten LIKE '%$keyword%' and JenisKonten='Dokumen' ORDER BY TanggalKonten ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "Unduhan.php?pagination=true";
											$sql =  "SELECT * FROM kontenweb where JenisKonten='Dokumen' ORDER BY TanggalKonten ASC";
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
											<!-- <td>
												<?php echo $data ['IsiKonten']; ?>
											</td> -->
											<td>
												<a href="../images/Dokumen/Unduhan/<?=$data ['Gambar1']?>" title='Download Dokumen'><i class='btn btn-info btn-sm' style="border-radius:2px;"><span class='fa fa-download'></span> Download </i></a> 
											</td>
											<td width="150px">
												<!-- Edit Data-->
												<!-- <a href="Unduhan.php?id=<?php echo base64_encode($data['KodeKonten']);?>" title='Edit'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-edit'></span> </i></a>  -->
												<!-- Hapus Data-->
												<a href="Unduhan.php?id=<?php echo base64_encode($data['KodeKonten']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
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
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   
    <!-- JavaScript files-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="assets/vendor/chart.js/Chart.min.js"></script>
    <!-- <script src="assets/js/front.js"></script> -->
	<!-- Sweet Alerts -->
	<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
	<!-- ckeditor JS -->
	<script  src="../library/ckeditor/Fix.js"></script>
	<script type="text/javascript" src="../library/ckeditor/ckeditor.js"></script>
	<a href="#" class='open_modal' data-judul='' data-tahun='' data-dokumen='' data-kode=''><span class="btn btn-primary">Tambah Data</span></a>
	<script type="text/javascript">
		$(document).ready(function () {
	   $(".open_modal").click(function(e) {
		  var txt_judul = $(this).data("judul");
		  var txt_tahun = $(this).data("tahun");
		  var txt_dokumen  = $(this).data("dokumen");
		  var txt_kode = $(this).data("kode");
		  var txt_jenis = $(this).data("jenis");
			   $.ajax({
					   url: "UnggahDokumen.php",
					   type: "GET",
					   data : {JudulKonten: txt_judul, IsiKonten: txt_tahun, Dokumen: txt_dokumen, KodeKonten: txt_kode, JenisKonten: txt_jenis},
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
			window.location = "Unduhan.php";
			}
		else{
		alert("Terima Kasih !");	return false; 	
			}
		}
	</script>
	 <?php 
	//Hapus Data
		if(base64_decode(@$_GET['aksi'])=='Hapus'){
			$HapusGambar = mysqli_query($koneksi,"SELECT Gambar1 FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Dokumen'");
			$data=mysqli_fetch_array($HapusGambar);
				
			
			$hapus = mysqli_query($koneksi,"DELETE FROM kontenweb WHERE KodeKonten='".base64_decode($_GET['id'])."' AND JenisKonten='Dokumen'");
			if($hapus){
				@unlink("../images/Dokumen/Unduhan/".$data['Gambar1']."");
				echo '<script language="javascript">document.location="Unduhan.php"; </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "Unduhan.php";
					  });
					  </script>';
			}
		}
	
  ?>
  </body>
</html>