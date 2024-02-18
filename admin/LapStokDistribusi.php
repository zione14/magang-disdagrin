<?php
include 'akses.php';
$fitur_id = 42;
include '../library/lock-menu.php'; 
include '../library/tgl-indo.php'; 
$Page = 'LapPupuk';
$SubPage = 'LapStokDistribusi';
$Bulan = date('Y-m');
@$Tgl = isset($_REQUEST['keyword']) && $_REQUEST['keyword'] != null ? @htmlspecialchars($_REQUEST['keyword']) : $Bulan; 
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
              <h2 class="no-margin-bottom">Laporan Stok Distributor</h2>
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
					</ul>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==''  AND $_GET['tr']==''){ echo 'in active show'; }else{ echo 'hidden'; } ?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Stok Distributor </h3>
							  <div class="offset-lg-7">
								<a href="../library/Export/LapStokDistribusi.php?tgl=<?=@$Tgl?>"><span class="btn btn-secondary">Export Laporan</span></a>
							  </div>
							</div>
							<div class="card-body">							  
								<div class="col-lg-5 offset-lg-7">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" id="time7" placeholder="Bulan Tahun..." value="<?=$Tgl?>">
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<?php 
										$ditri = mysqli_query($koneksi,"SELECT NamaPerson  FROM mstperson WHERE JenisPerson LIKE '%PupukSub%' AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL");
										$num = mysqli_num_rows($ditri);
									?>
									<tr>
									  <th align="center" rowspan="2">No</th>
									  <th align="center" rowspan="2">Nama Pupuk</th>
									  <th align="center" rowspan="2">Satuan</th>
									  <th colspan="<?=($num);?>">Stok Distributor Pupuk</th>
									</tr>
									<?php
										echo '<tr>';
											while($r = mysqli_fetch_array($ditri)){ 
												echo '<th>'.$r[0].'</th>';
											}
										echo '</tr>';
									?>
									
									
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "LapStokDistribusi.php?pagination=true&keyword=$Tgl";
										$sql =  "SELECT a.KodeBarang,a.NamaBarang,a.Keterangan,b.Penerimaan,b.Penjualan,b.KodeBarang
										FROM mstpupuksubsidi a
										LEFT JOIN (
											SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan,KodeBarang
											FROM sirkulasipupuk b
											WHERE b.KodeBarang = KodeBarang AND b.Keterangan= '$Tgl'
											GROUP BY b.KodeBarang
										) b ON b.KodeBarang = a.KodeBarang
										where a.IsAktif=b'1'";
																	
										$sql .="  Order by NamaBarang ASC";
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
												<?php echo $data ['NamaBarang']; ?>
												
											</td>
											<td align="center">
												<?php echo $data ['Keterangan']; ?>
											</td>
												<?php 
													$tb = mysqli_query($koneksi,"SELECT IDPerson FROM mstperson WHERE  JenisPerson LIKE '%PupukSub%'  AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL");
													while($res = mysqli_fetch_array($tb)){ 
														echo '<td align="center">'.stokSekarang($koneksi, $res[0], $data['KodeBarang'], $Tgl) .'</td>';
													}
												?>
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
		});
	</script>
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>

	  $("#KodePasar").change(function(){
		var KodePasar = $("#KodePasar").val();
		$.ajax({
			url: "../library/Dropdown/ambil-lapakpasar.php",
			data: "KodePasar="+KodePasar,
			cache: false,
			success: function(msg){
				$("#IDLapak").html(msg);
			}
		});
	  });
	  
	});
	</script>
	
	<script>
	  function goBack() {
		  window.history.back();
	  }
	</script>
	
	<?php
	
	function stokSekarang($koneksi, $IDPerson, $KodeBarang, $TanggalTransaksi){
		$query = "SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan
		FROM sirkulasipupuk b
		JOIN mstperson c ON b.IDPerson=c.IDPerson
		WHERE b.KodeBarang = '$KodeBarang' AND c.ID_Distributor='$IDPerson'  AND b.Keterangan = '$TanggalTransaksi'";
		$oke1 = $koneksi->prepare($query);
		$oke1->execute();
		$conn = $oke1->get_result();
		$result = mysqli_fetch_array($conn);
		$Penerimaan = $result['Penerimaan'];
		$Penjualan = $result['Penjualan'];
		$Total = $Penerimaan-$Penjualan;
		return number_format($Total, 2);
	}	
	?>
  </body>
</html>