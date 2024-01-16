<?php
include 'akses.php';
include 'aksihitung.php';
$Page = 'LaporanStok';
$Tanggal = date('Y-m-d');
@$tglcari1     = htmlspecialchars($_REQUEST['tgl1']);
@$tglcari2     = htmlspecialchars($_REQUEST['tgl2']);
@$tgl1 = isset($tglcari1) && $tglcari1 != null ? @$tglcari1 : $Tanggal; 
@$tgl2 = isset($tglcari2) && $tglcari2 != null ? @$tglcari2 : $Tanggal;  
//operasi pengeurangan tanggal sebanyak 1 hari
@$tglkemarin = date('Y-m-d', strtotime('-1 days', strtotime($tgl1))); 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
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
              <h2 class="no-margin-bottom">Laporan Distribusi Pupuk</h2>
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
						<li>
							<a href="../library/html2pdf/cetak/LapDistribusiPupuk.php?dt=<?=@$login_id?>&ppk=<?=@$_REQUEST['ppk']?>&tgl1=<?=@$tgl1?>&tgl2=<?=@$tgl2?>" target="_blank"><span class="btn btn-secondary"><i class="fa fa-print"></i> Cetak Laporan</span></a>
						</li>
					</ul>
					<br>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Distribusi Stok Pupuk <?=$login_nama?></h3>
							</div>
							<div class="card-body">							  
							  <form action="">			
                                <div class="row">
                                    
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
                                        <div class="form-group">
                                            <label class="form-control-label">Tanggal Mulai</label>
                                            <input  class="form-control" id="time1" name="tgl1" type="text" value="<?php echo @$tgl1; ?>" placeholder="Tanggal.." required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group ">
                                            <label class="form-control-label">Tanggal Selesai</label>
                                            <div class="input-group">
                                                <input  class="form-control" id="time2" name="tgl2" type="text" value="<?php echo @$tgl2; ?>" placeholder="Tanggal.." required>
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Cari</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							  </form><hr>
							  <?php 
								@$ppk      = htmlspecialchars($_REQUEST['ppk']);
								@$TotalScore	= ResultScore($ppk,$tgl1,$tgl2,$tglkemarin,$login_id,$koneksi);
							  ?>
							  <h4>Stok Pupuk Sebelumnya : <span id="StokBarang"> <?php echo  @$TotalScore; ?></span> </h4><br>
							  <div class="table-responsive">  
								<table class="table">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Tanggal Transaksi</th>
									  <th>Uraian</th>
									  <th>Harga Satuan</th>
									  <th>Jumlah Masuk (KG)</th>
									  <th>Jumlah Keluar (KG)</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										
										
										$reload    = "LapDistribusiPupuk.php?pagination=true&ppk=$ppk&tgl1=$tgl1&tgl2=$tgl2";
										$sql =  "SELECT a.TanggalTransaksi,a.JumlahMasuk,a.JumlahKeluar,a.HargaSatuan,b.Penerimaan,b.Penjualan,a.AsalMasuk,a.NoTransaksi
										FROM sirkulasipupuk a
										LEFT JOIN (
											SELECT SUM(JumlahMasuk) as Penerimaan,SUM(JumlahKeluar) as Penjualan,b.KodeBarang,b.IDPerson
											FROM sirkulasipupuk b
											WHERE b.KodeBarang = KodeBarang AND (date_format(b.TanggalTransaksi, '%Y-%m-%d') BETWEEN '1991-01-01' AND '$tglkemarin') AND b.IDPerson=IDPerson
											GROUP BY b.KodeBarang
										) b ON (b.KodeBarang, b.IDPerson) = (a.KodeBarang,a.IDPerson) 
										WHERE a.IDPerson='$login_id' and a.KodeBarang='$ppk' and  (date_format(a.TanggalTransaksi, '%Y-%m-%d') BETWEEN '$tgl1' AND '$tgl2')
										ORDER BY a.NoTransaksi ASC";										
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										// $rpp = 1; // jumlah record per halaman
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
											// mysqli_data_seek($result,$i);
											// @$data = mysqli_fetch_array($result);
											
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo TanggalIndo($data['TanggalTransaksi']); ?></strong>
											</td>
											<td align="left">
												<?php 
													if ($data['AsalMasuk'] != NULL OR $data['AsalMasuk'] != '' ){
														echo 'Penerimaaan Pupuk dari '.$data['AsalMasuk'];
													}else{
														
														echo 'Pegeluaran Pupuk ke :<br>';
														
														$org = mysqli_query($koneksi, "SELECT IDPerson,(SELECT NamaPerson From mstperson WHERE IDPerson= a.IDPerson) as NamaPerson FROM detilkeluar a WHERE a.NoTransaksi='".$data['NoTransaksi']."'");
														while($r = mysqli_fetch_array($org)){
															echo $r['NamaPerson'].'<br>';
														}
														
													}
												?>
											</td>
											<td align="center">
												<?="Rp".number_format($data['HargaSatuan'])?>
											</td>
											<td align="center">
												<?=number_format($data['JumlahMasuk'])?>
											</td>
											<td align="right">
												<?=number_format($data['JumlahKeluar'])?>
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
											// <tr>
												// <td colspan="3"><strong>Stok Sebelumnya</strong></td>
												// <td colspan="2" align="right"><strong>'.$Sebelumnya.'  </strong></td>
											// </tr>
											echo '

											<tr>
												<td colspan="4"><strong>Stok Sekarang</strong></td>
												<td colspan="2" align="right"><strong>'.number_format($Sebelumnya+array_sum($Penerimaan)-array_sum($Penjualan)).'  </strong></td>
											</tr>
											';
										}
										?>
									</tbody>
								</table>
								<!--<div><?php echo paginate_one($reload, $page, $tpages); ?></div>-->
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
		});
		
	</script>
	<?php 
		function ResultScore($KodeBarang,$Tanggal,$Tanggal2,$TanggalKemaren,$login_id,$koneksi){
			$Query1 =  "SELECT a.TanggalTransaksi,((IFNULL (b.Pembelian, 0))-IFNULL (b.Penjualan, 0)) as StokBarang 
			FROM sirkulasipupuk a
			LEFT JOIN (
				SELECT IFNULL(SUM(JumlahMasuk), 0) as Pembelian, IFNULL(SUM(JumlahKeluar), 0) as Penjualan,b.KodeBarang,b.IDPerson
				FROM sirkulasipupuk b
				WHERE b.KodeBarang = KodeBarang AND (date_format(b.TanggalTransaksi, '%Y-%m-%d') BETWEEN '1991-01-01' AND '$TanggalKemaren') AND b.IDPerson=IDPerson
				GROUP BY b.KodeBarang
			) b ON (b.KodeBarang, b.IDPerson) = (a.KodeBarang,a.IDPerson) 
			WHERE a.IDPerson='$login_id' and a.KodeBarang='$KodeBarang'
			ORDER BY a.NoTransaksi DESC";
			$res1   = mysqli_query($koneksi, $Query1);
			$result1 = mysqli_fetch_array($res1);
			
			@$Sebelumnya = number_format($result1['StokBarang']); 
			
			return($Sebelumnya);
			
		}
	?>
  </body>
</html>