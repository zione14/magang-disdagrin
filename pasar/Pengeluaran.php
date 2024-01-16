<?php
include '../pasar/akses.php';
$Page = 'Pengeluaran';
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
	.Zebra_DatePicker_Icon_Wrapper {
			width:100% !important;
	}
		
	.Zebra_DatePicker_Icon {
		top: 11px !important;
		right: 12px;
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
              <h2 class="no-margin-bottom">Pengeluaran Karcis</h2>
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
							  <h3 class="h4">Pengeluaran Karcis</h3>
							</div>
							<form method="post" action="Pengeluaran_aksi.php"  class="form-horizontal">
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Keterangan</label>
												<div class="col-sm-9">
													<textarea class="form-control" rows="2" name="Keterangan"></textarea>
												</div>
											</div>
										</div>
									</div>
								  <div class="col-lg-12">
									<input type="hidden" name="UserName" value="<?php echo isset($login_id) ? $login_id : ''; ?>">
									<input type="hidden" name="KodePasar"   value="<?=$KodePasar?>">
									<input type="hidden" name="TipeTransaksi" value="PENGELUARAN">
									<input type="hidden" name="SimpanData" value="0">
									<input type="hidden" name="TanggalTransaksi" value="<?= date('Y-m-d h:i:s')?>">
                                	<br>
									<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Jenis Karcis</th>
											  <th>Nilai Karcis</th>
											  <th>Stok Karcis</th>
											  <th>Pengeluaran Karcis</th>
											  <th>Nominal Karcis</th>
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
				                                     <td align="right"><?php echo number_format($data['StokRequest']*$data['NilaiKB']); ?></td>
				                                     <td align="right"><?php echo number_format($data['StokRequest']); ?></td>
				                                     <td>
				                                     	<input type="hidden" name="NoSeriAwal[]" id="NoSeriAwal<?=$no?>" value="<?=$data['NoSeriAwal']?>" class="form-control" required="">
				                                     	<input type="hidden" name="NoSeriAkhir[]" id="NoSeriAkhir<?=$no?>" value="<?=$data['NoSeriAkhir']?>" class="form-control" required="">
				                                     	<input type="hidden" name="KodeBatch[]" id="KodeBatch<?=$no?>" value="<?=$data['KodeBatch']?>" class="form-control" required="">
				                                     	<input type="hidden" name="KodeKB[]" id="KodeKB<?=$no?>" value="<?=$data['KodeKB']?>" class="form-control" required="">



				                                     	<input type="hidden" name="NilaiKB[]" id="NilaiKB<?=$no?>" value="<?=$data['NilaiKB']?>" class="form-control" required="">
				                                     	<input type="number" name="JumlahKreditKB[]" id="JumlahKreditKB<?=$no?>" class="form-control" onkeyup="cekHitung(<?=$no?>)" required="">
				                                     </td>
				                                     <td>
				                                     	<input type="number" name="TotalNominal[]" style="text-align: right" id="hasil<?=$no?>" class="form-control items" value="0" readonly>
				                                     </td>
														
														
				                                    </tr>
				                                    <?php
				                                    

				                                    ++$no;

				                                }
				                            ?>
				                            <tr>
				                                <td colspan="4"><h5>Total Nominal Penerimaan<h5></td>
				                                <td align="right"><input type="number" style="text-align: right" id="tot" class="form-control" value="0" readonly=""></td>
				                            </tr>
                                          </tbody>
										</table>
									</div><hr>	
								  </div>
									<div class="col-lg-12">
										<div class="text-left">
											<div id="btnSubmit" align="right">
												<button type="submit"class="btn btn-success btn-sm">Simpan Data</button>
												<!-- <a href="Request.php"><span class="btn btn-warning  btn-sm">Kembali</span></a> -->
											</div>
										</div>
									</div>
								</div>
							</div>
							</form>
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
		function cekHitung(num) {
		    var sum = 0 ;
		    var nilai = $('#NilaiKB'+num).val();
		    var jumlah = $('#JumlahKreditKB'+num).val();
		    var hasil = (parseFloat(nilai)*parseFloat(jumlah));

		  
		    document.getElementById('hasil'+num).value = hasil;
		    var items = document.getElementsByClassName("items");
		    var itemCount = items.length;
		    var total = 0;
		    for(var i = 0; i < itemCount; i++)
		    {
		        total = total +  parseFloat(items[i].value);
		    }
		    document.getElementById('tot').value = total;
		}
		
		

	</script>
  </body>
</html>