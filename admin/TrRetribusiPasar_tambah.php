<?php
include 'akses.php';
@$fitur_id = 45;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'PembayaranRetribusi';
$SubPage = 'TrRetribusiPasar';
$Tanggal = date('Y-m-d');
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
              <h2 class="no-margin-bottom">Entry Pembayaran Retribusi</h2>
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
							  <h3 class="h4">Entry Pembayaran</h3>
							</div>
							<form method="post" action="TrRetribusiPasar_aksi.php"  class="form-horizontal">
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
								  		<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Tanggal Pembayaran</label>
												<div class="col-sm-9">
													<input type="text" id="time1" class="form-control" placeholder="Tanggal Pembayaran" value="<?php echo $Tanggal;?>" name="TanggalTransaksi" required>
												</div>
											</div>
										</div>
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Nama Pasar</label>
												<div class="col-sm-9">
													<select  id="KodePasar"  class="form-control">	
														<option value="">Pilih Pasar</option>
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
													</select>
												</div>
											</div>
										</div>
										
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Blok Lapak</label>
												<div class="col-sm-9">
													<select id="IDLapak" class="form-control" name="IDLapak" required>
														<?php
															echo "<option value=''>--- Lapak Pasar ---</option>";
															$menu = mysqli_query($koneksi,"SELECT a.* FROM lapakpasar a 
															LEFT JOIN lapakperson  b on (a.KodePasar,a.IDLapak)=(b.KodePasar,b.IDLapak)
															where a.KodePasar='".$RowData['KodePasar']."' and b.IDPerson is null ORDER BY BlokLapak");
															while($kode1 = mysqli_fetch_array($menu)){
																if($RowData['IDLapak'] !== NULL){
																	if($kode1['IDLapak'] === $RowData['IDLapak']){
																		echo "<option value=\"".$kode1['IDLapak']."\" selected='selected'>".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
																	}else{
																		echo "<option value=\"".$kode1['IDLapak']."\" >".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
																	}
																}else{
																	echo "<option value=\"".$kode1['IDLapak']."\" >".$kode1['BlokLapak']." ".$kode1['NomorLapak']."</option>\n";
																}
																
																
															}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
								  <div class="col-lg-12">
									<input type="hidden" name="SimpanData">
									<!-- <input type="hidden" name="WaktuTransaksi" value="<?= date('h:i:s')?>"> -->
									<!-- <input type="hidden" name="aksi" value="Edit"> -->
									<input type="hidden" name="UserName" value="<?php echo isset($row['UserName']) ? $row['UserName'] : ''; ?>">
                                	<br>
									<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>Nomor Lapak</th>
											  <th>Nama Pemilik</th>
											  <th>Nominal Retribusi</th>
											  <th>Bayar</th>
											</tr>
										  </thead>
										  <tbody id="lookup">
											
                                          </tbody>
                                          <input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<!-- <input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />-->
											<br/><br/> 
										</table>
									</div><hr>	
								  </div>
									<div class="col-lg-12">
										<div class="text-left">
											<div id="btnSubmit" align="right">
												<button type="submit" class="btn btn-success btn-sm">Simpan</button>
												<a href="TrRetribusiPasar.php"><span class="btn btn-warning  btn-sm">Kembali</span></a>
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
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
	<script>
	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>

	  $("#KodePasar").change(function(){
		var KodePasar = $("#KodePasar").val();
		$.ajax({
			url: "../library/Dropdown/ambil-lapakblok.php",
			data: "KodePasar="+KodePasar,
			cache: false,
			success: function(msg){
				$("#IDLapak").html(msg);
			}
		});
	  });
	  
	});

	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>

	  $("#IDLapak").change(function(){
	  	var KodePasar = $('#KodePasar option:selected').val(); 
		var IDLapak =  $('#IDLapak option:selected').text(); 
		var Tanggal = $("#time1").val();

		// alert(Tanggal);
		$.ajax({
			url: "../library/Dropdown/ambil-retribusi.php",
			data: {KodePasar: KodePasar, IDLapak: IDLapak, Tanggal: Tanggal},
			dataType: 'json',
			success: function (data) {
				// alert(data);
		      var print = '<tr style="display :none;"><td></td><td></td><td></td></tr>';
		      for (var i = 0 ; i < data.length; i++) {
		        var number = i +1;
		        print += '<tr><td>'+number+'</td><td><input type="hidden" name="KodePasar[]" value="'+data[i].KodePasar+'"><input type="hidden" name="IDLapak[]" value="'+data[i].IDLapak+'"><input hidden="text" name="Retribusi[]" value="'+data[i].Retribusi+'">Nomon '+data[i].NomorLapak+'</td><td>'+data[i].NamaPerson+'</td><td>'+data[i].Retribusi+'</td><td><input type="checkbox" id="cekbox" name="cekbox[]" value="'+data[i].IDPerson+'"/></td></tr>';
		      }
		      // print += '<tr><td>Jumlah Total</td><td align="right"><input type="number" id="jmlLaki" class="form-control" value="'+data[0].KodePasar+'" readonly></td></tr>';
		      $('#lookup').html(print);
		    }
		});
	  });
	  
	});
	</script>
	<script>
		function cek(cekbox){
			for(i=0; i < cekbox.length; i++){
				cekbox[i].checked = true;
			}
		}
		function uncek(cekbox){
			for(i=0; i < cekbox.length; i++){
				cekbox[i].checked = false;
			}
		}
	
	</script>
  </body>
</html>