<?php
include 'akses.php';
$Page = 'Security';
$SubPage = 'AksesLevel';
$fitur_id = 60;
include '../library/lock-menu.php'; 
@include '../library/tgl-indo.php';
$Page = 'LapKarcis';
$SubPage = 'LapStokKarcisPasar';
$Tahun=date('Y');
$sql_p = "SELECT * FROM mstkertasberharga WHERE IsAktif=b'1' ORDER BY KodeKB ASC";
$res_p = $koneksi->query($sql_p);
$data_karcis = array();
while ($row_p = $res_p->fetch_assoc()) {
    if($row_p){
        array_push($data_karcis, $row_p);
    }
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
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
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
              <h2 class="no-margin-bottom">Laporan Stok Karcis Retribusi Pasar</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Data Stok Karcis Retribusi Pasar</h3>
					</div>
					<div class="card-body">
					  <div class="col-lg-12 col-md-12 mb-20">
						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>" id="admin">
								<div class="table-responsive">  
									<table class="table">
									  <thead>
	                                        <tr>
	                                            <th class="text-left" rowspan="2" width="50px" style="vertical-align: middle;">No.</th>
	                                            <th class="text-left" style="vertical-align: middle;" rowspan="2">Nama Pasar</th>
	                                            <th colspan="<?php echo (sizeof($data_karcis)); ?>">
	                                                <div class="text-center">Jenis Karcis</div>
	                                            </th>
	                                        </tr>
	                                        <tr>
	                                            <?php foreach($data_karcis as $krc): ?>
	                                            <th class="text-right"><?php echo ucwords(str_replace('', '', strtolower($krc['NamaKB']))); ?></th>
	                                            <?php endforeach; ?>
	                                        </tr>
	                                    </thead>
										<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										// $reload = "LapPerJenisKarcis.php?pagination=true&KodeKB=$KodeKB&keyword=$keyword";
										$sql =  "SELECT p.NamaPasar, p.KodePasar
										FROM mstpasar p
										ORDER BY p.NamaPasar ASC";
										$oke = $koneksi->prepare($sql);
										$oke->execute();
										$result = $oke->get_result();
										
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
												<?php echo $data['NamaPasar']; ?>
											</td>
											<?php foreach($data_karcis as $krc): ?>
												<?php 
												$query = "SELECT IFNULL(IFNULL(SUM(d.JumlahKirim), 0)-IFNULL(c.JumlahKreditKB, 0), 0) as StokRequest, t.KodePasar, d.KodeKB
												FROM traruskbitem d
												JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
												LEFT JOIN (
													SELECT IFNULL(SUM(d.JumlahKreditKB), 0) as JumlahKreditKB, t.KodePasar, d.KodeKB
													FROM traruskbitem d
													JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
													Where t.TipeTransaksi='PENGELUARAN'
													GROUP by t.KodePasar, d.KodeKB
												) c ON c.KodePasar = t.KodePasar AND c.KodeKB = d.KodeKB
												WHERE t.TipeTransaksi='PENGIRIMAN' AND t.KodePasar='".$data['KodePasar']."' AND d.KodeKB='".$krc['KodeKB']."'  
												GROUP by t.KodePasar, d.KodeKB";
												$oke = $koneksi->prepare($query);
												$oke->execute();
												$row = $oke->get_result();
												$res = mysqli_fetch_assoc($row); 
												?>

	                                            <td class="text-right">
	                                            	<?php echo  isset($res['StokRequest']) ? $res['StokRequest'] : 0 ; ?>
	                                            </td>
	                                        <?php endforeach; ?>

											
										</tr>
										<?php
											// $StokRequest[] = $res['StokRequest']; 
											$i++; 
											$count++;
											} 

											echo '
											<tr>
											  <td colspan="2" align="left"><strong>Total Stok Karcis</strong></td>';

											  foreach($data_karcis as $krc): 
												$query1 = "SELECT IFNULL(IFNULL(SUM(d.JumlahKirim), 0)-IFNULL(c.JumlahKreditKB, 0), 0) as StokRequest, t.KodePasar, d.KodeKB
												FROM traruskbitem d
												JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
												LEFT JOIN (
													SELECT IFNULL(SUM(d.JumlahKreditKB), 0) as JumlahKreditKB, t.KodePasar, d.KodeKB
													FROM traruskbitem d
													JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
													Where t.TipeTransaksi='PENGELUARAN'
													GROUP by t.KodePasar, d.KodeKB
												) c ON c.KodePasar = t.KodePasar AND c.KodeKB = d.KodeKB
												WHERE t.TipeTransaksi='PENGIRIMAN' AND d.KodeKB='".$krc['KodeKB']."'  
												GROUP by t.KodePasar, d.KodeKB";
												$oke1 = $koneksi->prepare($query1);
												$oke1->execute();
												$row1 = $oke1->get_result();
												$res1 = mysqli_fetch_assoc($row1); 

												echo  isset($res1['StokRequest']) ? '<td class="text-right">'.$res1['StokRequest'].'</td>' : '<td class="text-right">0</td>' ;
												endforeach;
											echo '</tr>';

											echo '
											<tr>
											  <td colspan="2" align="left"><strong>Nilai Karcis</strong></td>';
											foreach($data_karcis as $krc): 
												$query2 = "SELECT IFNULL(IFNULL(SUM(d.JumlahKirim), 0)-IFNULL(c.JumlahKreditKB, 0), 0) as StokRequest, t.KodePasar, d.KodeKB
												FROM traruskbitem d
												JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
												LEFT JOIN (
													SELECT IFNULL(SUM(d.JumlahKreditKB), 0) as JumlahKreditKB, t.KodePasar, d.KodeKB
													FROM traruskbitem d
													JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
													Where t.TipeTransaksi='PENGELUARAN'
													GROUP by t.KodePasar, d.KodeKB
												) c ON c.KodePasar = t.KodePasar AND c.KodeKB = d.KodeKB
												WHERE t.TipeTransaksi='PENGIRIMAN' AND d.KodeKB='".$krc['KodeKB']."'  
												GROUP by t.KodePasar, d.KodeKB";
												$oke2 = $koneksi->prepare($query2);
												$oke2->execute();
												$row2 = $oke2->get_result();
												$res2 = mysqli_fetch_assoc($row2); 

												echo  isset($res2['StokRequest']) ? '<td class="text-right">'.number_format($res2['StokRequest']*$krc['NilaiKB']).'</td>' : '<td class="text-right">0</td>';

												$JumlahSeluruh[] = $res2['StokRequest']*$krc['NilaiKB']; 
												endforeach;
	                                        echo '</tr>';

	                                        echo '
											<tr>
											  <td colspan="2" align="left"><strong>Total Nominal Karcis</strong></td>
											  <td colspan="'.sizeof($data_karcis).'" align="right"><strong>Rp '.number_format(array_sum($JumlahSeluruh)).'</strong></td>
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