<?php
include 'akses.php';
@$fitur_id = 39;
include '../library/lock-menu.php';
$Page = 'TargetTahunan';
date_default_timezone_set('Asia/Jakarta'); $DateTime=date('Y-m-d H:i:s'); $Bulan=date('Y-m');  $Tanggal=date('Y-m-d'); 
$NamaBulan = array (1 =>   'Januari',
	'Februari',
	'Maret',
	'April',
	'Mei',
	'Juni',
	'Juli',
	'Agustus',
	'September',
	'Oktober',
	'November',
	'Desember'
);

$QuerySetting = mysqli_query($koneksi,"SELECT Value FROM sistemsetting WHERE KodeSetting='SET-0002'");
$RowSetting = mysqli_fetch_array($QuerySetting);
$SetSurat = explode("#", $RowSetting['Value']);
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
	.Zebra_DatePicker_Icon_Wrapper {
		width:100% !important;
	}
	
	.Zebra_DatePicker_Icon {
		top: 11px !important;
		right: 12px;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "TargetTahunan.php";
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
              <h2 class="no-margin-bottom">Laporan Target Realisasi PAD </h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<!---<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Dusun</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>
							<?php } ?>
						</li>
					</ul><br/>-->
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Laporan Realisasi Penerimaan </h3>
							  <div class="offset-lg-4">
								<a href="#" class='open_modal_item' data-tahun='<?php echo $IDPerson;?>'><span class="btn btn-secondary " title="Tambah Target">Tambah Data</span></a>&nbsp;
								<a data-toggle="modal" data-target="#myModal"><span class="btn btn-primary">Export Laporan</span></a>
							  </div>
							</div>
							<div class="card-body">							  
								<!--<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="Bulan" class="form-control" id="time7" value="<?php echo @$_REQUEST['Bulan']; ?>" placeholder="Periode Bulan" required>
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>-->
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Bulan</th>
									  <th>Jenis Target</th>
									  <th>Kode Rekening</th>
									  <th>Target PAD</th>
									  <th>Target PAK</th>
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
											$reload = "TargetTahunan.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT a.NamaDusun,a.KodeDusun,b.NamaDesa,c.NamaKecamatan FROM mstdusun a join mstdesa b on (a.KodeDesa,a.KodeKec)=(b.KodeDesa,b.KodeKec) join mstkec c on a.KodeKec=c.KodeKec WHERE a.NamaDusun LIKE '%$keyword%' ORDER BY a.NamaDusun,b.NamaDesa,c.NamaKecamatan ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "TargetTahunan.php?pagination=true";
											$sql =  "SELECT * FROM targettahunan ORDER BY ThAnggaran DESC";
											@$result = mysqli_query($koneksi,$sql);
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
												<strong>
													<?php 
														@$apart		 = explode("-", $data ['ThAnggaran']);
														@$bulan		 = $apart[1];
														@$tahun		 = $apart[0];
														echo $NamaBulan[(int)$bulan]." ".$tahun; 
													?>
												</strong>												
											<td>
												<?php echo $data ['JenisTarget']; ?>
											</td>
											<td align="center">
												<?php echo $data ['KodeRekening']; ?>
											</td>
												<td align="center">
												<?php echo $data ['TargetAwal']; ?>
											</td>
												<td align="center">
												<?php echo $data ['TargetPAK']; ?>
											</td>
											<td width="120px" align="center">
													<!-- Tombol Edit Data -->
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="#" class='open_modal_item' data-tahun='<?php echo $data['ThAnggaran'];?>' data-notrans='<?php echo $data['NoTrans'];?>' data-aksi='<?php echo 'edit';?>'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a>
												<?php } ?>
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
		<!-- Modal-->
	  <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
		<div role="document" class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 id="exampleModalLabel" class="modal-title">Export Laporan Realisasi Penerimaan</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			  <form method="post" target="_blank" action="TargetTahunan.php">
				<div class="form-group">
					<label>Periode</label>
					<input type="text" name="Bulan" class="form-control" id="time1" value="<?php echo @$Bulan; ?>" placeholder="Periode Bulan" required>
				</div>
				<div class="form-group">
					<label>Nama TTD</label>
					<input type="text" name="Nama" value="<?php echo $SetSurat[0];?>" class="form-control">
				</div>
				<div class="form-group">
					<label>Pangkat dan Golongan</label>
					<input type="text" name="Pangkat" value="<?php echo $SetSurat[1];?>" class="form-control">
				</div>
				<div class="form-group">
					<label>NIP</label>
					<input type="text" name="NIP" value="<?php echo $SetSurat[2];?>" class="form-control">
				</div>
				<div class="form-group"> 
				  <input type="submit" name="Export" class="btn btn-danger" value="Export Excel">
				</div>
			  </form>
			</div>
			<div class="modal-footer">
			  <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
			</div>
		  </div>
		</div>
	  </div>
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	

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
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
			$('#time1').Zebra_DatePicker({format: 'Y-m'});
		});
	</script>
	<script type="text/javascript">
	$(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var thangga = $(this).data("tahun");
		  var aksi = $(this).data("aksi");
		  var notrans = $(this).data("notrans");
		  	   $.ajax({
					   url: "Modal/AddTargetTahunan.php",
					   type: "GET",
					   data : {ThAnggaran: thangga, Aksi:aksi, NoTrans:notrans},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>

	
	<?php
	
	//Post data user simpan data baru
	@$JenisTarget		= htmlspecialchars($_POST['JenisTarget']);	@$JenisTarget1		= htmlspecialchars($_POST['JenisTarget1']);
	@$KodeRekening		= htmlspecialchars($_POST['KodeRekening']);	@$KodeRekeningUTTP	= htmlspecialchars($_POST['KodeRekeningUTTP']);
	@$TargetAwal		= htmlspecialchars($_POST['TargetAwal']);	@$TargetAwalUTTP	= htmlspecialchars($_POST['TargetAwalUTTP']);
	@$TargetPAK			= htmlspecialchars($_POST['TargetPAK']);	@$TargetPAKUTTP		= htmlspecialchars($_POST['TargetPAKUTTP']);
	@$ThAnggaran		= htmlspecialchars($_POST['ThAnggaran']);	$TangalJam			= date('d');
	@$ThAnggaran1		= htmlspecialchars($_POST['ThAnggaran1']);	@$NoTrans			= htmlspecialchars($_POST['NoTrans']);
		
	if(isset($_POST['Simpan'])){
		
		// cek apakah sudah ada target tahunan di bulan dan tahun yang sama
		$cek = @mysqli_query($koneksi, "SELECT * from targettahunan where (date_format(ThAnggaran, '%Y-%m')= '$ThAnggaran')");
		$num = @mysqli_num_rows($cek);
		
		if($num > 0){
			echo '<script type="text/javascript">swal( "Laporan Realisasi Target sudah ada", "Silahkan Pilih Periode Bulan yang berbeda ", "error" ); </script>';
		}else{
		
			$Tahun=date('Ym');
			$sql = "SELECT RIGHT(NoTrans,6) AS kode FROM targettahunan ORDER BY NoTrans DESC LIMIT 1";
			$res = mysqli_query($koneksi, $sql);
			$result = mysqli_fetch_array($res);
			if ($result['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$result['kode'];
			}
			$bikin_kode = str_pad($kode, 6, "0", STR_PAD_LEFT);
			$kode_jadi	 = $Tahun."-".$bikin_kode;
			
			
			$query = mysqli_query($koneksi,"INSERT into targettahunan (NoTrans,JenisTarget,ThAnggaran,KodeRekening,TargetAwal,TargetPAK) 
			VALUES ('$kode_jadi','$JenisTarget','".$ThAnggaran."-".$TangalJam."','$KodeRekening','$TargetAwal','$TargetPAK')");
			if($query){
				$Tahun=date('Ym');
				$sql1 = "SELECT RIGHT(NoTrans,6) AS kode1 FROM targettahunan ORDER BY NoTrans DESC LIMIT 1";
				$res1 = mysqli_query($koneksi, $sql1);
				$result1 = mysqli_fetch_array($res1);
				if ($result1['kode1'] == null) {
					$kode1 = 1;
				} else {
					$kode1 = ++$result1['kode1'];
				}
				$bikin_kode1 = str_pad($kode1, 6, "0", STR_PAD_LEFT);
				$kode_jadi1	 = $Tahun."-".$bikin_kode1;
				
				$query1 = mysqli_query($koneksi,"INSERT into targettahunan (NoTrans,JenisTarget,ThAnggaran,KodeRekening,TargetAwal,TargetPAK) 
				VALUES ('$kode_jadi1','$JenisTarget1','".$ThAnggaran."-".$TangalJam."','$KodeRekeningUTTP','$TargetAwalUTTP','$TargetPAKUTTP')");
				
				if ($query1){
					InsertLog($koneksi, 'Tambah Data', 'Menambah Target Laporan Realisasi PAD dan UTTP dengan ID '.$kode_jadi.' dan '.$kode_jadi1, $login_id, $kode_jadi, 'Laporan Target Realisasi');
				echo '<script language="javascript">document.location="TargetTahunan.php";</script>';
				}else{
					echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "TargetTahunan.php";
					  });
					  </script>';
				}
			}
		}	
	}
	
	if(isset($_POST['EditSimpan'])){
		
		// query update
		mysqli_query($koneksi,"UPDATE targettahunan SET KodeRekening='$KodeRekening',TargetAwal='$TargetAwal',TargetPAK='$TargetPAK' where (date_format(ThAnggaran, '%Y-%m-%d')= '$ThAnggaran1') and JenisTarget='PAD'");
		
		$query = mysqli_query($koneksi,"UPDATE targettahunan SET KodeRekening='$KodeRekeningUTTP',TargetAwal='$TargetAwalUTTP',TargetPAK='$TargetPAKUTTP' where (date_format(ThAnggaran, '%Y-%m-%d')= '$ThAnggaran1') and JenisTarget='UTTP'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Target Laporan Realisasi PAD dan UTTP', $login_id, $NoTrans, 'Laporan Target Realisasi');
			echo '<script language="javascript">document.location="TargetTahunan.php";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "TargetTahunan.php";
				  });
				  </script>';
		}
	}
	
	if(isset($_POST['Export'])){
		//update setting
		mysqli_query($koneksi,"UPDATE sistemsetting SET Value='".$_POST['Nama']."#".$_POST['Pangkat']."#".$_POST['NIP']."' WHERE KodeSetting='SET-0002' ");
		
		echo '<script language="javascript">document.location="../library/Export/LapTargetBulanan.php?bln='.base64_encode($_POST['Bulan']).'&nm='.base64_encode($_POST['Nama']).'&pkt='.base64_encode($_POST['Pangkat']).'&np='.base64_encode($_POST['NIP']).'";</script>';
	}
	
	?>
  </body>
</html>