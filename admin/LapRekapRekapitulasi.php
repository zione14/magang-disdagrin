<?php
include '../admin/akses.php';
$Page = 'LapPupuk';
$SubPage ='LapRekapRekapitulasi';
$fitur_id = 44;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$Bulan = date('Y-m');
@$Tgl = isset($_REQUEST['Keterangan']) && $_REQUEST['Keterangan'] != null ? @htmlspecialchars($_REQUEST['Keterangan']) : $Bulan; 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include '../admin/title.php';?>
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
    <?php include '../admin/style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
		<style>
		 th {
			text-align: center;
		}
		
		
		/* Style the form */
		#regForm {
		  background-color: #ffffff;
		  margin: 20px auto;
		  /* padding: 40px; */
		  width: 100%;
		  min-width: 300px;
		}

		/* Style the input fields */
		input {
		  padding: 10px;
		  width: 100%;
		  font-size: 17px;
		  font-family: Raleway;
		  border: 1px solid #aaaaaa;
		}

		/* Mark input boxes that gets an error on validation: */
		input.invalid {
		  background-color: #ffdddd;
		}

		/* Hide all steps by default: */
		.tab {
		  display: none;
		}

		/* Make circles that indicate the steps of the form: */
		.step {
		  height: 15px;
		  width: 15px;
		  margin: 0 2px;
		  background-color: #bbbbbb;
		  border: none; 
		  border-radius: 50%;
		  display: inline-block;
		  opacity: 0.5;
		}

		/* Mark the active step: */
		.step.active {
		  opacity: 1;
		}

		/* Mark the steps that are finished and valid: */
		.step.finish {
		  background-color: #4CAF50;
		}
	</style>
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php include 'header.php'; ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Laporan Rekapitulasi Penyaluran Pupuk</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
				<?php if(isset($_GET['s'])) {?>
					<div class="col-lg-12">
						<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span class="fa fa-window-close"></span></button> File Tidak Ditemukan</div>
					</div>
				<?php } ?>
                <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Download Rekapitulasi Penyaluran Pupuk</h3>
					</div>
					<form method="get" action="LapRekapRekapitulasi_aksi.php" >
						<div class="card-body">
							<div class="row">
								<div class="col-lg-11">
									<label>Periode Laporan </label>
									<input type="text" name="Keterangan" class="form-control" id="time7" placeholder="Bulan Tahun..." value="<?=$Tgl?>">
									<br>
									<br>
									<button type="submit" class="btn btn-primary" name="SubmitFile">Download Laporan</button>&nbsp;&nbsp;
								</div>
							</div>
						</div>
					</form>
					<hr>
					<div class="col-lg-12">
					  <h5>List Laporan Rekapitulasi Penyaluran Pupuk </h5>
					  <div class="table-responsive">  
						<table class="table">
						  <thead>
							<tr>
							  <th>No</th>
							  <th>Periode</th>
							  <th>##</th>
							</tr>
						  </thead>
							<?php
								include '../library/pagination1.php';
								// mengatur variabel reload dan sql
								$reload = "LapRekapRekapitulasi.php?pagination=true";
								$sql =  "SELECT Keterangan,TanggalTransaksi FROM `sirkulasipupuk` GROUP by Keterangan DESC";
								$oke = $koneksi->prepare($sql);
								$oke->execute();								
								$result = $oke->get_result();	
													
								//pagination config start
								$rpp = 20; // jumlah record per halaman
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
										<?php echo TanggalIndo($data['Keterangan']); ?>
									</td>
									<td align="center">
										<?php if ($cek_fitur['EditData'] =='1'){ ?>
											<a href="LapRekapRekapitulasi_aksi.php?Keterangan=<?php echo $data['Keterangan'];?>" title='Download Laporan'><i class='btn btn-info btn-sm'><span class='fa fa-print'></span></i></a>
										<?php } ?>

										<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
											<!-- Tombol Hapus Data -->												
											<a href="LapRekapRekapitulasi.php?p=<?php echo base64_encode($data['Keterangan']); ?>" title="Hapus" onclick="return confirmation()" ><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php
									$i++; 
									$count++;
								}
								if($tcount==0){
									echo '
									<tr>
										<td colspan="6" align="center">
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
    <script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>	
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
		});

		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "LapRekapRekapitulasi.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>

	<?php

		@$periode = base64_decode(mysqli_real_escape_string($koneksi,$_GET['p']));

		if(isset($_GET['p'])){
		// hapus transaksi sirkulasi pupuk
		$query = mysqli_query($koneksi,"delete from sirkulasipupuk WHERE Keterangan='$periode'");
		if($query){
			@unlink("../images/Pupuk/".$periode.".xlsx");
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Laporan Penyaluran atas periode '.$periode, $login_id, '', 'Laporan Rekapitulasi Penyaluran Pupuk');
			echo '<script language="javascript">document.location="LapRekapRekapitulasi.php";</script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: "",
						type: "error"
					  },
					  function () {
						window.location.href = "LapRekapRekapitulasi.php";
					  });
					  </script>';
		}
	}






	?>
	
  </body>
</html>