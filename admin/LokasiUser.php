<?php
include 'akses.php';
@$fitur_id = 8;
include '../library/lock-menu.php';
$Page = 'DataPemilik';
$SubPage = 'Lokasi';
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
              <h2 class="no-margin-bottom">Lokasi User</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Lokasi User</span></a>&nbsp;
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Lokasi User</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" type="submit">Cari</button>
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
									  <th>Alamat</th>
									  <!--<th>Jenis User</th>-->
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
											$reload = "LokasiUser.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT NamaPerson,AlamatLengkapPerson,JenisPerson,IDPerson FROM mstperson where  NamaPerson LIKE '%$keyword%' and IsVerified=b'1' and JenisPerson LIKE '%Timbangan%' and UserName != 'dinas' ORDER BY NamaPerson ASC";
											$oke = $koneksi->prepare($sql);
											$oke->execute();
											$result = $oke->get_result();
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "LokasiUser.php?pagination=true";
											$sql =  "SELECT NamaPerson,AlamatLengkapPerson,JenisPerson,IDPerson,PJPerson FROM mstperson where IsVerified=b'1' and JenisPerson LIKE '%Timbangan%' and UserName != 'dinas' ORDER BY NamaPerson ASC";
											$oke1 = $koneksi->prepare($sql);
											$oke1->execute();
											@$result = $oke1->get_result();
										}
										
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
												<strong><?php echo $data ['NamaPerson']; ?></strong>
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
											</td>
											<!--<td align="center">
												<?php echo $data ['JenisPerson']; ?>
											</td>-->
											<td width="100px" align="center">
												<!-- Tombol Detil Data Timbangan Per user-->												
												<a href="LokasiUserDetil.php?user=<?php echo base64_encode($data['IDPerson']);?>" title='Lihat Detil Timbangan'><i class='btn btn-warning btn-sm'><span class="fa fa-map-marker"></span></i></a> 
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
  </body>
</html>