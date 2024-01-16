<?php
include 'akses.php';
$fitur_id = 5;
include '../library/lock-menu.php'; 
include '../library/tgl-indo.php';
$Page = 'Security';
$SubPage = 'LogServer';
@$DateTime = date('Y-m-d H:i:s');
@$JenisList =$_REQUEST['JenisListData'];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="../komponen/css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
	<?php include 'style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "LayananSurat.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
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
              <h2 class="no-margin-bottom">Log Server</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Log Server</h3>
					</div>
					<div class="card-body">							  
						<form method="post" action="">
							<div class="row">
							<div class="col-lg-3 offset-lg-3 select">
								<select name="JenisListData" class="form-control">
								   <option value="">Tampil Semua</option>
								  <option value="Tambah Data" <?php if(@$_REQUEST['JenisListData']=='Tambah Data'){echo 'selected';}?>>Tambah Data</option>
								  <option value="Hapus Data" <?php if(@$_REQUEST['JenisListData']=='Hapus Data'){echo 'selected';}?>>Hapus Data</option>
								  <option value="Edit Data" <?php if(@$_REQUEST['JenisListData']=='Edit Data'){echo 'selected';}?>>Edit Data</option>
								  <option value="Verifikasi Data" <?php if(@$_REQUEST['JenisListData']=='Verifikasi Data'){echo 'selected';}?>>Verifikasi Data</option>
								</select>
							</div>
							<div class="form-group col-lg-3">						
								<input type="text" name="Tanggal" id="date1" class="form-control" placeholder="Tanggal" value="<?php echo @$_REQUEST['Tanggal']; ?>">
							</div>
							<div class="form-group col-lg-3 input-group">						
								<input type="text" name="keyword" class="form-control" placeholder="User" value="<?php echo @$_REQUEST['keyword']; ?>">
								<span class="input-group-btn">
									<button class="btn btn-info" type="submit">Cari</button>
								</span>
							</div>
							</div>
						</form>
					  <div class="table-responsive">  
						<table class="table table-striped">
						  <thead>
							<tr>
							  <th>No</th>
							  <th>Tanggal</th>
							  <th>Aksi</th>
							  <th>Keterangan</th>
							  <th>No Transaksi</th>
							  <th>Jenis Transaksi</th>
							  <th>User</th>
							</tr>
						  </thead>
							<?php
								include '../library/pagination1.php';
								// mengatur variabel reload dan sql
								$keyword=@$_REQUEST['keyword'];
								$jenis_list=@$_REQUEST['JenisListData'];
								$tgl=@$_REQUEST['Tanggal'];
								
								$reload = "LogServer.php?pagination=true&JenisListData=$jenis_list&keyword=$keyword&Tanggal=$tgl";
								$sql =  "SELECT a.*,DATE_FORMAT(a.DateTimeLog,'%H:%i:%s') as Jam,b.ActualName FROM serverlog a JOIN userlogin b ON a.UserName=b.UserName";
								
								if(@$keyword!=null){	
									$sql.=" AND (b.ActualName LIKE '%$keyword%') ";
								}
								
								if(@$jenis_list!=null){
									$sql.=" AND a.Action ='$jenis_list' ";
								}
								
								if(@$tgl!=null){
									$sql.=" AND DATE_FORMAT(a.DateTimeLog,'%Y-%m-%d')='$tgl' ";
								}
								
								$sql.=" ORDER BY a.DateTimeLog DESC ";
								$result = mysqli_query($koneksi,$sql);
								
								//pagination config start
								$rpp = 15; // jumlah record per halaman
								$page = intval(@$_GET["page"]);
								if($page<=0) $page = 1;  
								$tcount = mysqli_num_rows($result);
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
									$data = mysqli_fetch_array($result);
								?>
								<tr class="odd gradeX">
									<td width="50px">
										<?php echo ++$no_urut;?> 
									</td>
									<td>
										<?php echo TanggalIndo($data ['DateTimeLog']); echo '<br/>';echo $data['Jam']; ?>
									</td>
									<td>
										<?php echo $data ['Action']; ?>
									</td>
									<td>
										<?php echo $data ['Description']; ?>
									</td>
									<td>
										<?php echo $data ['NoTransaksi']; ?>
									</td>
									<td>
										<?php echo $data ['JenisTransaksi']; ?>
									</td>
									<td>
										<?php echo $data ['ActualName']; ?>
									</td>
								</tr>
								<?php
									$i++; 
									$count++;
								}
								
								if($tcount==0){
									echo '
									<tr>
										<td colspan="7" align="center">
											<strong>Tidak ada data</strong>
										</td>
									</tr>
									';
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
			$('#date1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			//$('#Datetime2').Zebra_DatePicker({format: 'Y-m-d H:i', direction: 1});
		});
	</script>
	
  </body>
</html>