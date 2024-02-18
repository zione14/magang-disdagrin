<?php
include 'akses.php';
@$fitur_id = 32;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Page = 'LaporanRetribusi';
$SubPage = 'LapPendapatanRetPasar';
?>
<!DOCTYPE html>
<html>
  <head>
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
	.table thead th {
		vertical-align: middle;
		text-align: center;
		border-bottom: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">Laporan Pendapatan Retribusi Pasar</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Pendapatan Retribusi Pasar</h3>
							  <div class="offset-lg-7">
								<!--<a data-toggle="modal" data-target="#myModal"><span class="btn btn-primary">Cetak & Export</span></a>-->
							  </div>
							</div>
							<div class="card-body">
								<form method="post" action="LapPendapatanRetPasar.php">
									<div class="col-lg-12">
										<div class="col-lg-12 form-group input-group">
											<select name="KodePasar" class="form-control">	
												<option value="">Semua Pasar</option>
												<?php
													$menu = mysqli_query($koneksi,"SELECT * FROM mstpasar");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodePasar']==@$_REQUEST['KodePasar']){
															echo "<option value=\"".$kode['KodePasar']."\" selected >".$kode['NamaPasar']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodePasar']."\" >".$kode['NamaPasar']."</option>\n";
														}
													}
												?>
											</select>&nbsp;&nbsp;
											<input type="text" name="Tanggal1" class="form-control" id="time1" value="<?php echo @$_REQUEST['Tanggal1']; ?>" placeholder="Tanggal Awal" required>&nbsp;&nbsp;
											<input type="text" name="Tanggal2" class="form-control" id="time2" value="<?php echo @$_REQUEST['Tanggal2']; ?>" placeholder="Tanggal Akhir" required>
										</div>
									
									</div>
									<div class="col-lg-8 offset-lg-4">
										<div class="col-lg-12 form-group input-group">
											<input type="text" name="keyword" class="form-control"  value="<?php echo @$_REQUEST['keyword']; ?>" placeholder="Nama User...">&nbsp;&nbsp;
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>&nbsp;
												
												<a href="../library/html2pdf/cetak/LapPendapatanRetPasar.php?psr=<?=base64_encode(@$_REQUEST['KodePasar'])?>&tgl1=<?=base64_encode(@$_REQUEST['Tanggal1'])?>&tgl2=<?=base64_encode(@$_REQUEST['Tanggal2'])?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak</span></a>
												
											</span>
										</div>
									</div>
								</form>
							  <div class="table-responsive">  
								<table class="table table-hover">
								  <thead>
									<tr>
									  <th>No </th>
									  <th>Nama Person</th>
									  <!--<th>Uraian</th>-->
									  <th>Alamat </th>
									  <th>Lapak Person </th>
									  <th>Nilai Dibayar</th>
									</tr>
									
								  </thead>
									<?php
										include '../library/pagination1.php';
										$Day = date('Y-m');
										$psr  = @$_REQUEST['KodePasar'];
										$tgl1 = @$_REQUEST['Tanggal1'];
										$tgl2 = @$_REQUEST['Tanggal2'];
										$key  = @$_REQUEST['keyword'];
										$reload = "LapPendapatanRetPasar.php?pagination=true&Tanggal2=$tgl2&Tanggal1=$tgl1&KodePasar=$psr&keyword=$key";
										$sql =  "SELECT a.*, b.NamaPerson, 
											b.AlamatLengkapPerson,d.BlokLapak,d.NomorLapak,c.NamaPasar,f.Keterangan as KetLapak 
											FROM trretribusipasar a 
											JOIN mstperson b ON a.IDPerson=b.IDPerson 
											join mstpasar c on a.KodePasar=c.KodePasar join 
											lapakpasar d on (d.KodePasar,d.IDLapak)=(a.KodePasar,a.IDLapak) 
											join lapakperson f on (f.KodePasar,f.IDLapak,f.IDPerson)=(a.KodePasar,a.IDLapak,a.IDPerson) 
											WHERE  1 ";
										
										if(@$_REQUEST['KodePasar']!= null OR @$_REQUEST['KodePasar']!=''){
											$sql .= " AND a.KodePasar='".@$_REQUEST['KodePasar']."'  ";
										}
										
										if((isset($_REQUEST['Tanggal1']) && $_REQUEST['Tanggal2']<>"")){
											$sql .= " AND (date_format(a.TanggalTrans, '%Y-%m-%d') BETWEEN '".@$_REQUEST['Tanggal1']."' AND '".@$_REQUEST['Tanggal2']."') ";
										}

										if((isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"")){
											$sql .= " AND b.NamaPerson LIKE '%".htmlspecialchars($_REQUEST['keyword'])."%' ";
										}
										
										$sql .="  ORDER BY a.TanggalTrans DESC";
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
												<strong><?php echo @$data ['NamaPerson']; ?></strong><br>
												<?php echo TanggalIndo(@$data ['TanggalTrans']); ?>
												<p>No Transaksi : <?php echo @$data ['NoTransRet']; ?></p>
											</td>
											<!--<td>
												<strong><?php echo @$data ['JmlHariDibayar']." Hari x ".$data['NominalRetribusi']; ?></strong>
											</td>-->
											<td>
												<?php echo $data['KetLapak']."<br>".$data['NamaPasar']; ?>
											</td>
											<td>
												<strong><?php echo @$data ['NamaPasar']."</strong> ".$data['BlokLapak']." ".$data['NomorLapak']."<br>".$data['IDLapak']; ?>
											</td>
											<td align="right">
												<?php echo "<strong> Rp ".number_format($data ['NominalDiterima'])."</strong>"; ?>
												<?php  $Jumlah[] = $data['NominalDiterima']; ?>
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
										}
										
										if($tcount==0){
											echo '
											<tr>
												<td colspan="8" align="center">
													<strong>Data Tidak Ada</strong>
												</td>
											</tr>
											';
										}else{
											echo '
											<tr style="background-color: #9e9999;">
												<td colspan="4" align="center" style="color:white"><strong>Total Retribusi</strong></td>
												<td align="right" style="color:white"><strong> Rp '.number_format(array_sum($Jumlah),0,',','.').'</strong></td>
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
			  <h4 id="exampleModalLabel" class="modal-title">Cetak Laporan Pendapatan Retribusi</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<form method="post" target="_BLANK">
			<div class="modal-body">
				<input type="hidden" name="tanggal1" value="<?php echo @$_REQUEST['Tanggal1']; ?>" class="form-control">
				<input type="hidden" name="tanggal2" value="<?php echo @$_REQUEST['Tanggal2']; ?>" class="form-control">
				<div class="form-group">  
				</div>
			</div>
			<div class="modal-footer">
			  <input type="submit" name="Cetak" class="btn btn-primary" value="Cetak">
			  <input type="submit" name="Export" class="btn btn-danger" value="Export Excel">
			  <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
			</div>
			</form>
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
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			//$('#Datetime2').Zebra_DatePicker({format: 'Y-m-d H:i', direction: 1});
		});
	</script>
	<?php 
		if(isset($_POST['Cetak'])){
			$Tanggl1 = htmlspecialchars($_POST['tanggal1']);
			$Tanggl2 = htmlspecialchars($_POST['tanggal2']);
			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapPendapatan.php?tgl1='.base64_encode($Tanggl1).'&tgl2='.base64_encode($Tanggl2).'";</script>';
		}

		if(isset($_POST['Export'])){
			$Tanggl1 = htmlspecialchars($_POST['tanggal1']);
			$Tanggl2 = htmlspecialchars($_POST['tanggal2']);
			echo '<script language="javascript">document.location="../library/Export/LapPendapatan.php?tgl1='.base64_encode($Tanggl1).'&tgl2='.base64_encode($Tanggl2).'";</script>';
		}
	?>
	
  </body>
</html>