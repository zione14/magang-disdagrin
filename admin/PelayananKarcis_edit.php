<?php
include 'akses.php';
@$fitur_id = 53;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$JenisMenu = isset($_GET['jn']) ? mysqli_escape_string($koneksi, base64_decode($_GET['jn'])) : '';
$Page = isset($JenisMenu) && $JenisMenu != 'RiwayatPelayananKarcis' ? 'PelayananKarcis' : 'RiwayatPelayananKarcis';
$SubPage = '';
$Tanggal = date('Ymd');
if(@$_GET['id']!=null){
	$NoTrRequest = mysqli_escape_string($koneksi, base64_decode($_GET['id']));

	$sql = "SELECT t.NoTrRequest, t.TanggalRequest, t.KeteranganRequest, t.IsRealisasi, t.NoTrRealisasi, t.UserName, t.KodePasar, t.TotalNilaiKB, p.NamaPasar 
	FROM trrequestkb t
	JOIN mstpasar p ON t.KodePasar=p.KodePasar
	WHERE t.NoTrRequest = '$NoTrRequest'";
	$res = $koneksi->query($sql);
	if(mysqli_num_rows($res) > 0){
		$row = mysqli_fetch_assoc($res);
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
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Select2Master -->
	<link rel="stylesheet" href="../library/select2master/css/select2.css"/>
	<link rel="stylesheet" type="text/css" href="../library/datatables/datatables.min.css"/>
	<style>
	.table thead th {
		vertical-align: middle;
		text-align: center;
		
		border: 2px solid #dee2e6;
	}
	td {
		border: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">View Karcis Retribusi</h2>
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
							  <h3 class="h4">View Data</h3>
							</div>
							<form method="post" action="PelayananKarcis_aksi.php"  class="form-horizontal" onsubmit="return cekStok();">
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Nama Pasar </label>
												<div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" value="<?php echo @$row['NamaPasar'];?>" readonly>
												</div>
											</div>
										</div>
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Tanggal Permintaan</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" value="<?php echo @TanggalIndo($row['TanggalRequest']);?>" readonly>
												</div>
											</div>
										</div>
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Nilai Karcis Retribusi</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" value="<?php echo @number_format($row['TotalNilaiKB']);?>" readonly>
												</div>
											</div>
										</div>
									</div>
								  <div class="col-lg-12">
								  	<span class="text-danger" id="msg"></span>
									<input type="hidden" name="NoTrRequest" value="<?=$row['NoTrRequest']?>">
									<input type="hidden" name="KodePasar"   value="<?=$row['KodePasar']?>">
									<input type="hidden" name="TipeTransaksi" value="PENGIRIMAN">
									<input type="hidden" name="UserName" value="<?=$login_id?>">
									<input type="hidden" name="TanggalTransaksi" value="<?= date('Y-m-d h:i:s')?>">
                                	<br>
									<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Jenis Karcis</th>
											  <th>Jumlah Request</th>
											  <th>Total Nominal</th>
											  <?php if (isset($JenisMenu) && $JenisMenu != 'RiwayatPelayananKarcis') : ?>
											  <th>Kode Seri</th>
											  <th>Stok Karcis</th>
											  <th>Jumlah Karcis Dikirim</th>
											  <?php endif; ?>
											  
											  
											</tr>
										  </thead>
										  <tbody id="tableBody">
											<?php
												$no = 1;
												$no1 = 1;
				                                $query = mysqli_query($koneksi,"SELECT m.NamaKB, t.NoUrut, t.JumlahKB, t.TotalNominal, t.Keterangan, t.NoTrRequest, t.StokSaatRequest, t.KodeKB
				                                	FROM trrequestkbitem  t
				                                	JOIN mstkertasberharga m ON t.KodeKB = m.KodeKB
				                                	WHERE  NoTrRequest= '".$row['NoTrRequest']."'  ORDER BY NoUrut ASC");
				                                while ($data = mysqli_fetch_array($query)) {
				                            ?>
			                                    <tr>
			                                        <td><?php echo $data['NamaKB']; ?></td>
			                                        <td align="right"><?php echo number_format($data['JumlahKB']); ?></td>
													<td align="right">
														<?php echo number_format($data['TotalNominal']); ?>
														<!-- <input type="hidden" name="JumlahDebetKB[]" value="<?=$data['JumlahKB']?>"> -->
													</td>
													<?php if (isset($JenisMenu) && $JenisMenu != 'RiwayatPelayananKarcis') : ?>
													<td>
														<select id="comboKB<?=$no?>" name="NoTransArusKB[]" class="form-control"  onchange="previewData(<?=$no?>)" required>
														<option value="">Pilih Jenis Kode Seri</option>	
														<?php
															$menu = mysqli_query($koneksi,"SELECT i.NoTransArusKB, i.NoUrut, i.KodeBatch, i.JumlahDebetKB, i.TotalNominal, i.NoSeriAwal, i.NoSeriAkhir, i.KodeKB, t.KodeBatchPencetakan, IFNULL(d.TotalKeluar, 0)  TotalKeluar 
																FROM traruskbitem i 
																JOIN traruskb t ON i.NoTransArusKB = t.NoTransArusKB
																LEFT JOIN (
																	SELECT IFNULL(SUM(d.JumlahDebetKB), 0)-IFNULL(SUM(d.JumlahKreditKB), 0) as TotalKeluar,d.KodeBatch, d.KodeKB
																	FROM traruskbitem d
																	GROUP by d.KodeKB, d.KodeBatch
																) d ON d.KodeKB = i.KodeKB  AND d.KodeBatch=i.KodeBatch
																WHERE  t.TipeTransaksi='PENCETAKAN' AND i.KodeKB='".$data['KodeKB']."' AND  TotalKeluar > 0");
															while($kode = mysqli_fetch_array($menu)){
																echo '<option value="'.$kode['NoTransArusKB'].'" data-nourut="'.$kode['NoUrut'].'" data-jumlah="'.$kode['JumlahDebetKB'].'" data-total="'.$data['TotalNominal'].'" data-awal="'.$kode['NoSeriAwal'].'" data-akhir="'.$kode['NoSeriAkhir'].'" data-kodekb="'.$kode['KodeKB'].'" data-batch="'.$kode['KodeBatchPencetakan'].'" data-stok="'.$kode['TotalKeluar'].'">'.$kode['KodeBatchPencetakan'].'</option>';
															}
														?>
													    </select>
													    <input type="hidden" name="NoUrut[]" id="NoUrut<?=$no?>">
													    <!-- <input type="text" name="JumlahDebetKB[]" id="JumlahDebetKB<?=$no?>"> -->
													    <input type="hidden" name="TotalNominal[]" id="TotalNominal<?=$no?>">
													    <input type="hidden" name="NoSeriAwal[]" id="NoSeriAwal<?=$no?>">
													    <input type="hidden" name="NoSeriAkhir[]" id="NoSeriAkhir<?=$no?>">
													    <input type="hidden" name="KodeKB[]" id="KodeKB<?=$no?>">
													    <input type="hidden" name="KodeBatch[]" id="KodeBatch<?=$no?>">
													</td>
													<td>
														<!-- <input type="hidden" name="JumlahDebetKB1[]" id="JumlahDebetKB1<?=$no1?>" value="<?=$no1?>"> -->
														<span id="kerciscetak<?=$no1?>"></span>
													</td>
													<td>
														<input type="hidden" id="stoks<?=$no1?>" class="stokc">
														<input type="text" name="JumlahDebetKB[]" id="JumlahKirim" class="form-control items" required="">
													</td>
													<?php else: ?>
													
													<?php endif; ?>
			                                    </tr>
				                            <?php
				                            ++$no;
				                            ++$no1;
				                                }
				                            ?>
                                          </tbody>
										</table>
									</div><hr>	
								  </div>
									<div class="col-lg-12">
										<div class="text-left">
											<div id="btnSubmit" align="right">
												<?php if (isset($JenisMenu) && $JenisMenu != 'RiwayatPelayananKarcis') : ?>
												<button type="submit" name="SimpanData" class="btn btn-success btn-sm">Simpan</button>
												<a href="PelayananKarcis.php"><span class="btn btn-warning  btn-sm">Kembali</span></a>
												<?php else : ?>
												<a href="RiwayatPelayananKarcis.php"><span class="btn btn-warning  btn-sm">Kembali</span></a>
												<?php endif; ?>
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

    <!-- Modal Popup untuk Edit--> 
  
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
		
		var NilaiKB = 0;
		var TotalNominal = 0;
    	var NamaKB = '';
    	var NoUrut = '';
    	var JumlahDebetKB = '';
    	var NoSeriAwal = '';
    	var NoSeriAkhir = '';
    	var KodeKB = '';
    	var KodeBatch = '';

    	function previewData(num) {
    		NoUrut = $('#comboKB'+num).find('option:selected').attr('data-nourut');
		    JumlahDebetKB = $('#comboKB'+num).find('option:selected').attr('data-jumlah');
		    TotalNominal = $('#comboKB'+num).find('option:selected').attr('data-total');
		    NoSeriAwal = $('#comboKB'+num).find('option:selected').attr('data-awal');
		    NoSeriAkhir = $('#comboKB'+num).find('option:selected').attr('data-akhir');
		    KodeKB = $('#comboKB'+num).find('option:selected').attr('data-kodekb');
		    KodeBatch = $('#comboKB'+num).find('option:selected').attr('data-batch');
		    StokCetak = $('#comboKB'+num).find('option:selected').attr('data-stok');

		    $('#NoUrut'+num).val(NoUrut);
		    // $('#JumlahDebetKB'+num).val(JumlahDebetKB);
		    $('#JumlahDebetKB1'+num).val(JumlahDebetKB);
		    $('#TotalNominal'+num).val(TotalNominal);
		    $('#NoSeriAwal'+num).val(NoSeriAwal);
		    $('#NoSeriAkhir'+num).val(NoSeriAkhir);
		    $('#KodeKB'+num).val(KodeKB);
		    $('#KodeBatch'+num).val(KodeBatch);
		    $('#stoks'+num).val(StokCetak);
		    $('#kerciscetak'+num).html(StokCetak);
		     // $("p").html("Hello <b>world</b>!");

	    };


	    function cekStok(){
	   		var items = document.getElementsByClassName("items");
	   		var stokc = document.getElementsByClassName("stokc");
		    var stokCount = stokc.length;
		    var itemCount = items.length;
		    var total = 0;
		    var totalstok = 0;
		    for(var i = 0; i < itemCount; i++)
		    {
		        total = total +  parseInt(items[i].value);
		    }

		    for(var i = 0; i < stokCount; i++)
		    {
		        totalstok = totalstok +  parseInt(stokc[i].value);
		    }

		    if(total > totalstok){
		      document.getElementById('msg').innerHTML = 'Jumlah Pengiriman melebihi stok cetak';
		      return false;
		    }
    		return true;
  		}

	</script>
  </body>
</html>