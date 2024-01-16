<?php 
include 'akses.php';
$Page = 'Setting';

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	$Edit = mysqli_query($koneksi,"SELECT * FROM setting WHERE username='".base64_decode($_GET['id'])."'");
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
                <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
						  <div class="card-header">
							<h3 class="h6 text-uppercase mb-0">Sistem Setting</h3>
						  </div>
							<div class="card-body">
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Setting..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama</th>
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
											$reload = "SistemSetting.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT * FROM setting WHERE nama LIKE '%$keyword%' AND nama != 'SOSMED' ORDER BY nama ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "SistemSetting.php?pagination=true";
											$sql =  "SELECT * FROM setting where nama != 'SOSMED' ORDER BY nama ASC";
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
												<strong><?php echo strtoupper($data ['nama']); ?></strong>
											</td>
											
											<td width="150px">
												<a href="#" class='open_modal' data-idset='<?php echo $data['id'];?>' data-namaset='<?php echo $data['nama'];?>' data-valueset='<?php echo $data['value'];?>' data-valuefile='<?php echo $data['file'];?>'><span class="btn btn-warning btn-sm fa fa-edit" title="Edit Setting"></span></a></td></a>
											
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
		  var id_set = $(this).data("idset");
		  var nama_set = $(this).data("namaset");
		  var value_set = $(this).data("valueset");
		  var file_set = $(this).data("valuefile");
		 
			   $.ajax({
					   url: "SettingEdit.php",
					   type: "GET",
					   data : {IdSet: id_set, NamaSet: nama_set, ValueSet: value_set, FileSet: file_set},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
  </body>
</html>