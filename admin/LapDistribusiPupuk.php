<?php
include 'akses.php';
$fitur_id = 44;
include '../library/lock-menu.php'; 
include '../library/tgl-indo.php'; 
$Page = 'LapPupuk';
$SubPage = 'LapDistribusiPupuk';
$Tanggal = date('Y-m');
@$tglcari1     = htmlspecialchars($_REQUEST['tgl1']);
@$tgl1 = isset($tglcari1) && $tglcari1 != null ? @$tglcari1 : $Tanggal; 
//operasi pengeurangan tanggal sebanyak 1 hari
@$tglkemarin = date('Y-m', strtotime('-1 month', strtotime($tgl1))); 
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
		.hidden {
		  display: none;
		  visibility: hidden;
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
              <h2 class="no-margin-bottom">Laporan Distribusi Pupuk</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab">
							<!--<span class="btn btn-primary">Data Lapak</span>-->
							</a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"></a>
							<?php } ?>
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#detil-user" data-toggle="tab"></a>
							<?php } ?>
						</li>
						
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==''  AND $_GET['tr']==''){ echo 'in active show'; }else{ echo 'hidden'; } ?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Distributor </h3>
							  <div class="offset-lg-8">
								<a href="../library/html2pdf/cetak/LapDistribusiPupuk.php?dt=<?=@$_REQUEST['dt']?>&ppk=<?=@$_REQUEST['ppk']?>&tgl1=<?=@$tgl1?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak Laporan</span></a>
							  </div>
							</div>
							<div class="card-body">
							  <form action="">			
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Distributor</label>
                                            <select name="dt" class="form-control" required>	
											<?php
												echo "<option value='' disable>--- Pilih Distributor ---</option>";
												$menu = mysqli_query($koneksi,"SELECT IDPerson,NamaPerson FROM mstperson where JenisPerson LIKE '%PupukSub%'  AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL ORDER BY NamaPerson");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['IDPerson']==$_REQUEST['dt']){
														echo "<option value=\"".$kode['IDPerson']."\" selected>".$kode['NamaPerson']."</option>\n";
													}else{
														echo "<option value=\"".$kode['IDPerson']."\" >".$kode['NamaPerson']."</option>\n";
													}
												}
											?>
											</select>&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Nama Pupuk Subsidi</label>
                                            <select class="form-control" name="ppk" required>
                                            <?php
												$menu = mysqli_query($koneksi,"SELECT KodeBarang,NamaBarang from mstpupuksubsidi where IsAktif=b'1' ORDER by NamaBarang ASC");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeBarang'] === $_REQUEST['ppk']){
														echo "<option value=\"".$kode['KodeBarang']."\" selected='selected'>".$kode['NamaBarang']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeBarang']."\" >".$kode['NamaBarang']."</option>\n";
													}
												}
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group ">
                                            <label class="form-control-label">Periode</label>
                                            <div class="input-group">
                                                <input  class="form-control" id="time7" name="tgl1" type="text" value="<?php echo @$tgl1; ?>" placeholder="Tanggal.." required>
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Cari</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							  </form><hr>
							  <?php 
								@$dt       = htmlspecialchars($_REQUEST['dt']);
								@$ppk      = htmlspecialchars($_REQUEST['ppk']);
								@$TotalScore	= ResultScore($ppk,$tgl1,$tglkemarin,$dt,$koneksi);
							  ?>
							  <h4>Stok Pupuk Sebelumnya : <span id="StokBarang"> <?php echo  @$TotalScore; ?></span> </h4><br>
							  <div class="table-responsive">  
								<table class="table">
								  <thead>
									<tr>
									  <th>No</th>
									  <!--<th>Tanggal Transaksi</th>-->
									  <th>Kios</th>
									  <th>Satuan</th>
									  <th>Jumlah Masuk (KG)</th>
									  <th>Jumlah Keluar (KG)</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload    = "LapDistribusiPupuk.php?pagination=true&dt=$dt&ppk=$ppk&tgl1=$tgl1";
										$sql =  "SELECT b.JumlahMasuk,b.JumlahKeluar,b.TanggalTransaksi,c.NamaPerson,b.Keterangan,e.Penerimaan,e.Penjualan
												FROM sirkulasipupuk b
												JOIN mstperson c ON b.IDPerson=c.IDPerson
												LEFT JOIN (
													SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan,e.KodeBarang,e.IDPerson
													FROM sirkulasipupuk e
													JOIN mstperson d ON e.IDPerson=d.IDPerson
													WHERE e.KodeBarang = KodeBarang AND e.Keterangan= '$tglkemarin' AND d.ID_Distributor='$dt'
													GROUP BY e.KodeBarang
												) e ON e.KodeBarang = b.KodeBarang 
												WHERE b.KodeBarang = '$ppk' AND c.ID_Distributor='$dt'  AND b.Keterangan = '$tgl1'";									
												$result = mysqli_query($koneksi,$sql);
										
										// pagination config start
										// $rpp = 30; // jumlah record per halaman
										// $page = intval(@$_GET["page"]);
										// if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										// $tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										// $count = 0;
										// $i = ($page-1)*$rpp;
										// $no_urut = ($page-1)*$rpp;
										$no_urut = 0;
										//pagination config end							
									?>
									<tbody>
										<?php
										while($data = mysqli_fetch_array($result)) {
										// while(($count<$rpp) && ($i<$tcount)) {
										// 	mysqli_data_seek($result,$i);
										// 	@$data = mysqli_fetch_array($result);
											
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<!--<td>
												<strong><?php echo TanggalIndo($data['TanggalTransaksi']); ?></strong>
											</td>-->
											<td align="left">
												<?php echo $data['NamaPerson']?>
											</td>
											<td align="right">
												TON
											</td>
											<td align="right">
												<?=number_format($data['JumlahMasuk'], 2)?>
											</td>
											<td align="right">
												<?=number_format($data['JumlahKeluar'], 2)?>
												<?php 
													$Penerimaan[] = $data['JumlahMasuk'];
													$Penjualan[]  = $data['JumlahKeluar'];
												?>
											</td>
											
										</tr>
										<?php
											// $i++; 
											// $count++;
											@$Sebelumnya = number_format($data['Penerimaan']-$data['Penjualan']); 
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
											<tr>
												<td colspan="4"><strong>Stok Sekarang</strong></td>
												<td colspan="2" align="right"><strong>'.number_format($Sebelumnya+array_sum($Penerimaan)-array_sum($Penjualan), 2).'  </strong></td>
											</tr>
											';
										}
										?>
									</tbody>
								</table>
								<!-- <div><?php echo paginate_one($reload, $page, $tpages); ?></div> -->
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
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
		});
		
		function goBack() {
		  window.history.back();
		}
	</script>
	<?php

		function ResultScore($KodeBarang,$Tanggal,$TanggalKemaren,$login_id,$koneksi){
			$Query1 =  "SELECT ((IFNULL (SUM(b.JumlahMasuk), 0))-IFNULL (SUM(b.JumlahKeluar), 0)) as StokBarang 
					FROM sirkulasipupuk b
					JOIN mstperson c ON b.IDPerson=c.IDPerson
					WHERE b.KodeBarang = '$KodeBarang' AND c.ID_Distributor='$login_id'  AND b.Keterangan = '$TanggalKemaren'";
			$res1   = mysqli_query($koneksi, $Query1);
			$result1 = mysqli_fetch_array($res1);
			@$Sebelumnya = number_format($result1['StokBarang'], 2); 
			return($Sebelumnya);
			
		}
	
	?>
  </body>
</html>