<?php
include 'akses.php';
$Page = 'Security';
$SubPage = 'AksesLevel';
$fitur_id = 59;
include '../library/lock-menu.php'; 
@include '../library/tgl-indo.php';
$Page = 'LapKarcis';
$SubPage = 'LapPerJenisKarcis';
$Tahun=date('Y');
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
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
   <style>
		 th,td {
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
              <h2 class="no-margin-bottom">Laporan Per Jenis Karcis Retribusi</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Data Per Jenis Karcis Retribusi</h3>
					</div>
					<div class="card-body">
					<div class="col-lg-10 offset-lg-2">
						<form method="post" action="">
							<div class="form-group input-group">
								<select name="KodeKB" class="form-control">	
									<option value="">Semua Jenis Karcis</option>
									<?php
										$sql1 = "SELECT * FROM mstkertasberharga Where IsAktif=b'1'";
										$oke = $koneksi->prepare($sql1);
										$oke->execute();
										$menu = $oke->get_result();
										while($kode = mysqli_fetch_array($menu)){
											if($kode['KodeKB']==@$_REQUEST['KodeKB']){
												echo "<option value=\"".$kode['KodeKB']."\" selected >".$kode['NamaKB']."</option>\n";
											}else{
												echo "<option value=\"".$kode['KodeKB']."\" >".$kode['NamaKB']."</option>\n";
											}
										}
									?>
								</select>&nbsp;						
								<input type="text" name="keyword" class="form-control" placeholder="Tanggal..." id="time1" value="<?php echo @$_REQUEST['keyword']; ?>">&nbsp;	
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit">Cari</button>
									 <a target="_blank" href="cetak/LapPerJenisKarcis.php?keyword=<?php echo @$_REQUEST['keyword']; ?>&KodeKB=<?php echo @$_REQUEST['KodeKB']; ?>" class="btn btn-secondary"><i class="fa fa-fw fa-print"></i> Cetak</a>
								</span>
							</div>
						</form>
					</div>
					<div class="col-lg-12 col-md-12 mb-20">

						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>" id="admin">
								<div class="table-responsive">  
									<table class="table">
									  <thead>
										<tr>
										  <th>NO</th>
										  <th>TANGGAL</th>
										  <th>URAIAN</th>
										  <th>PASAR/DINAS</th>
										  <th>JENIS KARCIS</th>
										  <th>NO SERI AWAL</th>
										  <th>NO SERI AKHIR</th>
										  <th>MASUK (BLOK)</th>
										  <th>KELUAR (BLOK)</th>
										</tr>
									  </thead>
										<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$KodeKB    = mysqli_escape_string($koneksi, $_REQUEST['KodeKB']);
										@$keyword   = mysqli_escape_string($koneksi, $_REQUEST['keyword']);
										$reload = "LapPerJenisKarcis.php?pagination=true&KodeKB=$KodeKB&keyword=$keyword";
										$sql =  "SELECT t.NoTransArusKB, t.TanggalTransaksi, t.KodeBatchPencetakan, t.TotalNilaKB, t.UserName, i.JumlahDebetKB, i.TotalNominal, i.NoSeriAwal, i.NoSeriAkhir, i.KodeBatch, i.Keterangan, m.NamaKB, m.NilaiKB, t.TipeTransaksi, p.NamaPasar, i.JumlahKirim
											FROM traruskb t
											JOIN traruskbitem i ON t.NoTransArusKB=i.NoTransArusKB
											JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
											LEFT JOIN mstpasar p ON t.KodePasar = p.KodePasar
											WHERE (t.TipeTransaksi='PENCETAKAN' OR t.TipeTransaksi='PENGELUARAN')";

										if(@$_REQUEST['KodeKB']!=null){
											$sql .= " AND i.KodeKB = '$KodeKB'  ";
										}
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND t.TanggalTransaksi LIKE '%".$_REQUEST['keyword']."%'  ";
										}
										
										$sql .=" ORDER BY t.TanggalTransaksi ASC";
										$oke1 = $koneksi->prepare($sql);
										$oke1->execute();
										$result = $oke1->get_result();
										
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
												<?php echo @TanggalIndo($data['TanggalTransaksi']); ?>
											</td>
											<td>
												<?php echo isset($data['TipeTransaksi']) && $data['TipeTransaksi'] == 'PENCETAKAN' ? 'Pencetakan Karcis' : 'Penyaluran Karcis ke '.$data['NamaPasar'] ?>
											</td>
											<td>
												<?php echo isset($data['NamaPasar']) && $data['NamaPasar'] != '' ? $data['NamaPasar'] : 'Disdagrin' ?>
											</td>
											<td>
												<?php echo $data['NamaKB']?>
											</td>
											<td align="center">
												<?php echo isset($data['TipeTransaksi']) && $data['TipeTransaksi'] == 'PENCETAKAN' ? $data['NoSeriAwal'] : '' ?>
											</td>
											<td align="center">
												<?php echo isset($data['TipeTransaksi']) && $data['TipeTransaksi'] == 'PENCETAKAN' ? $data['NoSeriAkhir'] : '' ?>
											</td>
											<td align="center">
												<?php echo number_format($data['JumlahDebetKB']); ?>
											</td>
											<td align="center">
												<?php echo number_format($data['JumlahKirim']); ?>
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
            </div>
          </section> 
        </div>
      </div>
    </div>
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
  </body>
</html>