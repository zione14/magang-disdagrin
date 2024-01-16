<?php
include '../admin/akses.php';
$Page = 'ImportLap';
$fitur_id = 44;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
$Bulan = date('Y-m');
@$Tgl = isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != null ? @htmlspecialchars($_REQUEST['keyword']) : $Bulan; 
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
              <h2 class="no-margin-bottom">Import Laporan Pupuk</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
				<?php if(isset($_GET['s'])) {?>
					<div class="col-lg-12">
						<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert"><span class="fa fa-window-close"></span></button> Berhasil Import Data</div>
					</div>
				<?php } ?>
				<?php if(isset($_GET['d'])) {?>
					<div class="col-lg-12">
						<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span class="fa fa-window-close"></span></button> Periode Laporan sudah diupload, silahkan hapus terlebih dahulu.</div>
					</div>
				<?php } ?>
                <div class="col-lg-12">
				  <div class="card">
						<div class="card-header d-flex align-items-center">
						  <h3 class="h4">Import Laporan Pupuk</h3>
						</div>
						
						
						<form method="post" action="ImportLapPupukAksi.php" enctype="multipart/form-data" onSubmit="return validateForm()" >
							<div class="card-body">
								<div class="row">
									<div class="col-lg-11">
										<label>Periode Laporan </label>
										<input type="text" name="Keterangan" class="form-control" id="time7" placeholder="Bulan Tahun..." value="<?=$Tgl?>">
										</br><div class="form-group-material">
											<label>Import File : </label>
											<div class="input-group">
											<input type="file" name="fileexcel" id="NamaFile" class="form-control" placeholder="Nama File" value="" readonly>&nbsp;
											<span class="input-group-btn">
												
											</span>
											</div>
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-primary" name="SubmitFile">Import Data</button>&nbsp;&nbsp;
							</div>
						</form>
						
					
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
	</script>
	<script type="text/javascript">
	//    validasi form (hanya file .xls yang diijinkan)
    function validateForm(){
        function hasExtension(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }

        if(!hasExtension('NamaFile', ['.xlsx'])){
            alert("Hanya file XLSX (Excel 2007) yang diijinkan.");
            return false;
        }
    }
	</script>
  </body>
</html>