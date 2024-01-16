<?php
include 'akses.php';
@$fitur_id = 36;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'PengaduanMasyarakat';
// $SubPage = 'LapTeraDinas';
$TanggalTransaksi = date("Y-m-d H:i:s");

if(@$_GET['id']!=null){
	$Edit = mysqli_query($koneksi,"SELECT * FROM trpengaduan WHERE KodePengaduan='".base64_decode($_GET['id'])."'");
	$RowData = mysqli_fetch_assoc($Edit);
}

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
	<style>
	th {
		text-align: center;
	}
	label{
		font-weight: bold;
	}
	
	</style>
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "PengaduanMasyarakat.php";
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
              <h2 class="no-margin-bottom">Layanan Pengaduan Masyarakat</h2>
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
							<a href="#tambah-user" data-toggle="tab"></a>&nbsp;
						</li>
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Pengaduan Masyarakat</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-info" type="submit">Cari</button>
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
										@$keyword  = $_REQUEST['keyword'];
										$reload = "PengaduanMasyarakat.php?pagination=true&keyword=$keyword";
										$sql =  "SELECT * FROM trpengaduan WHERE 1 ";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND Email LIKE '%".$_REQUEST['keyword']."%'  ";
										}
										
										$sql .=" ORDER BY TglPengaduan DESC";
										$result = mysqli_query($koneksi,$sql);
										
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
										if($tcount == null OR $tcount === 0){
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><strong>Tidak ada data</strong></td></tr>';
										} else {
											while(($count<$rpp) && ($i<$tcount)) {
												mysqli_data_seek($result,$i);
												@$data = mysqli_fetch_array($result);
											
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php echo $data ['Email']; ?><br>
												<strong><?php echo $data ['Telepon']; ?></strong>
											</td>
											<td>
												<?php echo TanggalIndo($data['TglPengaduan']); ?>
											</td>
											<td align="center">
												<?php 
												if($data ['Status']!='Belum dibaca'){
													echo '<i class="btn btn-primary btn-sm">Terbaca</i>';
												}else{
														echo '<i class="btn btn-success btn-sm">Baru</i>';
												} ?>
											</td>
											<td width="100px" align="center">
												<?php if ($cek_fitur['ViewData'] =='1'){ ?>
													<a href="PengaduanMasyarakat.php?id=<?php echo base64_encode($data['KodePengaduan']);?>" title='Baca Pesan'><i class='btn btn-warning btn-sm' style="border-radius:2px;"><span class='fa fa-eye'></span> </i></a> 
												<?php } ?>
												<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
													<!-- Hapus Data-->
													<a href="PengaduanMasyarakat.php?tr=<?php echo base64_encode($data['KodePengaduan']);?>&aksi=<?php echo base64_encode('Hapus');?>" title='Hapus' onclick="return confirmation()"><i class='btn btn-danger btn-sm' style="border-radius:2px;"><span class='fa fa-trash'></span> </i></a> 
												<?php } ?>
											
												
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
						<div class="tab-pane fade <?php if(@$_GET['id'] != null ){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Detil Pesan Pengaduan Masyarakat</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-8 alert alert-success" >
									<?php 
										if($RowData['Status']=='Belum dibaca'){
											mysqli_query($koneksi,"UPDATE trpengaduan SET Status='Terbaca' WHERE KodePengaduan='".$RowData['KodePengaduan']."'");
									
										} 
									?>
										<label>Pengirim</label>
										<p><?php echo $RowData['Email']; ?> </p>
										
										<label>Tanggal Pengiriman</label>
										<p><?php echo TanggalIndo($RowData['TglPengaduan']); ?> | <?php echo substr($RowData['TglPengaduan'],11,19); ?></p>
										
										<label>No Telephone</label>
										<p><?php echo $RowData['Telepon']; ?></p>
										<hr/>
										
										<label>Isi Pesan</label>
										<p><?php echo $RowData['Pesan']; ?></p>
										<hr/>
										<div class="text-right">
											<a href="mailto:<?php echo $RowData['Email'];?>" target="_BLANK"><button class="btn btn-success" style="border-radius:2px;">Balas</button></a>
											<a href="PengaduanMasyarakat.php"><button class="btn btn-danger" style="border-radius:2px;">Kembali</button></a>
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
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
	

	<?php 
		
		
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$query = mysqli_query($koneksi,"delete from trpengaduan WHERE  KodePengaduan='".htmlspecialchars(base64_decode($_GET['tr']))."'");
		if($query){
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Pengaduan Masyarakat ', $login_id, base64_decode(@$_GET['tr']), 'Layanan Pengaduan Masyarakat');
			echo '<script language="javascript">document.location="PengaduanMasyarakat.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "PengaduanMasyarakat.php";
					  });
					  </script>';
		}
	}
	
	
	?>
  </body>
</html>