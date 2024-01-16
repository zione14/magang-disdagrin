<?php
include '../pasar/akses.php';
$Page = 'Laporan';
$Tanggal = date('Ymd');
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
              <h2 class="no-margin-bottom">Laporan Stok Karcis Retribusi</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade in active show">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Laporan Stok Karcis</h3>
							</div>
							<div class="card-body">
							  <div class="col-lg-5 offset-lg-7">
	                            <!-- <form method="post" action="">
	                              <div class="form-group input-group">            
	                                <input type="text" name="keyword" id="time1" class="form-control" placeholder="Tanggal Pengeluaran..." value="<?php echo @$_REQUEST['keyword']; ?>"> 
	                                <span class="input-group-btn">
	                                  <button class="btn btn-success" type="submit">Cari</button>
	                                </span>
	                              </div>
	                            </form> -->
	                          </div>
							  <div class="table-responsive">  
									<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Jenis Karcis</th>
											  <th>Stok Karcis</th>
											  <th>Nilai Karcis</th>
											  <th>Total Karcis</th>
											  
											  <!-- <th>Pengeluaran Karcis</th> -->
											  <!-- <th>Nominal Karcis</th> -->
											</tr>
										  </thead>
										  <tbody id="tableBody">
											<?php
												$no = 1;
				                                $query = mysqli_query($koneksi,"SELECT m.NamaKB, m.NilaiKB, c.StokRequest, i.NoSeriAwal, i.NoSeriAkhir, i.KodeBatch	, i.KodeKB	 
												FROM traruskbitem  i
												JOIN traruskb t ON i.NoTransArusKB = t.NoTransArusKB
												JOIN mstkertasberharga m ON i.KodeKB = m.KodeKB
												LEFT JOIN (
														SELECT IFNULL(SUM(d.JumlahKirim), 0)-IFNULL(c.JumlahKreditKB, 0) as StokRequest, t.KodePasar, d.KodeKB
														FROM traruskbitem d
														JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
												    	LEFT JOIN (
															SELECT IFNULL(SUM(d.JumlahKreditKB), 0) as JumlahKreditKB, t.KodePasar, d.KodeKB
															FROM traruskbitem d
															JOIN traruskb t ON d.NoTransArusKB = t.NoTransArusKB
															Where t.TipeTransaksi='PENGELUARAN'
															GROUP by t.KodePasar, d.KodeKB
														) c ON c.KodePasar = t.KodePasar AND c.KodeKB = d.KodeKB
														WHERE t.TipeTransaksi='PENGIRIMAN' 
														GROUP by t.KodePasar, d.KodeKB
													) c ON c.KodePasar = t.KodePasar AND c.KodeKB = i.KodeKB
												WHERE  t.TipeTransaksi='PENGIRIMAN' AND t.KodePasar = '$KodePasar' AND c.StokRequest > 0
												GROUP by i.KodeKB
												ORDER BY NoUrut ASC");
				                                while ($data = mysqli_fetch_array($query)) {
				                            ?>
			                                    <tr>				                                    	
			                                     <td><?php echo $data['NamaKB']; ?></td>
			                                     <td align="right"><?php echo number_format($data['StokRequest']); ?></td>
			                                     <td align="right"><?php echo number_format($data['NilaiKB']); ?></td>
			                                     <td align="right"><?php echo number_format($data['StokRequest']*$data['NilaiKB']); ?></td>
			                                    </tr>
			                                <?php
			                                	$TotalNominal[] =  $data['StokRequest']*$data['NilaiKB']; 
			                                    ++$no;
				                                }
				                            ?>
				                            <tr>
				                                <td colspan="3"><h5>Total Nominal<h5></td>
				                                <td align="right"><?= number_format(array_sum($TotalNominal)); ?></td>
				                            </tr>
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
	<script src="../library/select2master/js/select2.min.js"></script>
	
	<script type="text/javascript" src="../library/datatables/datatables.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
	        $('#time1').Zebra_DatePicker({format: 'Y-m-d'});
	    });
	</script>
  </body>
</html>