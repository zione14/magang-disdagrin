<?php
include 'akses.php';
@$fitur_id = 52;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'Pencetakan';
$SubPage = '';
$Tanggal = date('Ymd');

if(@$_GET['id']!=null){
	$Sebutan = 'Data Penerimaan Timbangan';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT a.NamaPerson,b.IDPerson,b.NoTransaksi,c.IDTimbangan,b.TglTransaksi,b.Keterangan FROM mstperson a join tractiontimbangan b on a.IDPerson=b.IDPerson left join trtimbanganitem c on b.NoTransaksi=c.NoTransaksi WHERE b.NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
}else{
	$Sebutan = 'Tambah Data';	
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
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "TrTerimaTimbangan.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
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
              <h2 class="no-margin-bottom">Tambah Karcis Retribusi</h2>
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
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<form method="post" action="Pencetakan_aksi.php"  class="form-horizontal">
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Kode Pencetakan</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="Kode Pencetakan" value="<?php echo @$RowData['KodeBatchPencetakan'];?>" name="KodeBatchPencetakan" required>
												</div>
											</div>
										</div>
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Tanggal Cetak</label>
												<div class="col-sm-9">
													<input type="text" id="time1" class="form-control" placeholder="Tanggal Cetak" value="<?php echo @substr($RowData['TanggalTransaksi'], 0, 10);?>" name="TanggalTransaksi" required>
												</div>
											</div>
										</div>
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Keterangan</label>
												<div class="col-sm-9">
													<textarea class="form-control" rows="2" name="Keterangan"><?php echo @$RowData['Keterangan'];?></textarea>
												</div>
											</div>
										</div>
									</div>
								  <div class="col-lg-12">
									<input type="hidden" name="TipeTransaksi" value="PENCETAKAN">
									<input type="hidden" name="UserName" value="<?=$login_id?>">
									<input type="hidden" name="WaktuTransaksi" value="<?= date('h:i:s')?>">
								
									<!-- <h5>Cetak Karcis Retribusi</h5> -->
									<div class="col-md-12 text-right">
                                  		<button type="button" class="btn btn-secondary btn-sm" id="btnAdd">Tambah</button>
                                	</div>
                                	<br>
									<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>##</th>
											  <th>Jenis Karcis</th>
											  <th>Jumlah</th>
											  <th>Total Nominal</th>
											  <th>No Seri Awal</th>
											  <th>No Seri Akhir</th>
											</tr>
										  </thead>
										  <tbody id="tableBody">
											
                                          </tbody>
										</table>
									</div><hr>	
								  </div>
									<div class="col-lg-12">
										<div class="text-left">
											<div id="btnSubmit" align="right">
												<!-- <a href="Pencetakan.php"><span class="btn btn-danger">Kembali</span></a> -->
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
	
		
	<div class="modal fade" id="ModalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	    <div class="modal-dialog" role="document">
	      <form id="form_target">
	        <div class="modal-content">
	          <div class="modal-header">
			  	<h4 id="exampleModalLabel" class="modal-title">Karcis Retribusi</h4>
			  	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			  </div>
	          <div class="modal-body">
	            <div class="row">
	              <div class="col-md-12">
	                <div class="form-group">
	                  <label class="form-control-label">Jenis Karcis Retribusi</label>
	                  <select name="KodeKB" id="comboKB" class="form-control" required>	
	                  	<option value="">Pilih Jenis</option>
						<?php
							$menu = mysqli_query($koneksi,"SELECT KodeKB, NamaKB, NilaiKB  FROM mstkertasberharga WHERE IsAktif='1'");
							while($kode = mysqli_fetch_array($menu)){
								echo '<option value="'.$kode['KodeKB'].'" data-nilai="'.$kode['NilaiKB'].'" data-nama="'.$kode['NamaKB'].'">'.$kode['NamaKB'].'</option>';
							}
						?>
					  </select>
	                </div>
	                <div class="form-group" align="left">
						<label class="form-control-label">Jumlah Karcis Retribusi</label>
						<input type="number" placeholder="Jumlah Karcis" class="form-control" id="JumlahDebetKB" required>
					</div>
					<div class="form-group" align="left">
						<label class="form-control-label">No Seri Awal</label>
						<input type="number" placeholder="No Seri Awal" class="form-control" id="NoSeriAwal" required>
					</div>
					<!-- <div class="form-group" align="left">
						<label class="form-control-label">Kode Seri</label>
						<input type="text" placeholder="Kode Seri Karcis" class="form-control" id="KodeBatch" required>
					</div> -->
	              </div>
	              <!-- <div class="col-md-6">
	                <div class="form-group" align="left">
						<label class="form-control-label">No Seri Awal</label>
						<input type="number" placeholder="No Seri Awal" class="form-control" id="NoSeriAwal" required>
					</div>
					<div class="form-group" align="left">
						<label class="form-control-label">No Seri Terakhir</label>
						<input type="number" placeholder="No Seri Terakhir" class="form-control" id="NoSeriAkhir" required>
					</div>
	              </div> -->
	            </div>
	          </div>
	          <div class="modal-footer">
	            <button class="btn btn-secondary btn-sm" type="submit">Simpan</button>
	            <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal">Batal</button>
	          </div>
	        </div>
	      </form>
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
		var NilaiKB = 0;
		var TotalNominal = 0;
    	var NamaKB = '';
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
		
		$(document).on('click', '#btnAdd', function () {
	      $('#ModalTambah').modal('show');
	    });

	    $('#comboKB').change(function () {
	      NamaKB = $(this).find('option:selected').attr('data-nama');
	      NilaiKB = $(this).find('option:selected').attr('data-nilai');
	    });
				
		var DataItem = [];
		
		$(document).on('click', '#btnHapus', function () {
	      var NoUrut = $(this).attr("value");
	      DataItem.splice(NoUrut, 1);     
	      DrawTable();
	    });

		$("#form_target").submit(function(e) {
	      e.preventDefault();
	      var KodeKB      	 = $('#comboKB').val();
	      var JumlahDebetKB  = $('#JumlahDebetKB').val();
	      var NoSeriAwal  	 = $('#NoSeriAwal').val();
	      var NoSeriAkhir  	 = (parseFloat(NoSeriAwal)+parseFloat(JumlahDebetKB))-1;
	      // var KodeBatch 	 = $('#KodeBatch').val();
	      var TotalNominal	 = JumlahDebetKB * NilaiKB;
          var dataItem = [];
	      dataItem['KodeKB']   		= KodeKB;
	      dataItem['JumlahDebetKB'] = JumlahDebetKB;
	      dataItem['NoSeriAwal'] 	= NoSeriAwal;
	      dataItem['NoSeriAkhir']   = NoSeriAkhir;
	      // dataItem['KodeBatch'] 	= KodeBatch;
	      dataItem['NamaKB'] 		= NamaKB;
	      dataItem['TotalNominal'] 	= TotalNominal;
	      
	      DataItem.push(dataItem);
	      DrawTable();
	      $('#form_target')[0].reset();
	      $('#ModalTambah').modal('toggle');
	    });

    function DrawTable(){
      var strTable = '';
      var btnSubmit = '';
      for(i=0; i < DataItem.length; i++){
        strTable += '<tr>'
		+'<td class="text-center">'
        +'<button id="btnHapus" value="'+i+'" class="btn btn-danger btn-sm" title="Hapus"><span>x</span></button>'
        +'</td>'
        +'<td class="text-center"><input type="hidden" name="KodeKB[]" value="'+DataItem[i]['KodeKB']+'"><input type="hidden" name="Keterangan[]" value="Belum Dipakai">'+DataItem[i]['NamaKB']+'</td>'
        +'<td class="text-center"><input type="hidden" name="JumlahDebetKB[]" value="'+DataItem[i]['JumlahDebetKB']+'">'+DataItem[i]['JumlahDebetKB']+'</td>'
        +'<td class="text-center"><input type="hidden" name="TotalNominal[]" value="'+DataItem[i]['TotalNominal']+'">'+formatNumber(DataItem[i]['TotalNominal'])+'</td>'
        +'<td class="text-center"><input type="hidden" name="NoSeriAwal[]" value="'+DataItem[i]['NoSeriAwal']+'">'+DataItem[i]['NoSeriAwal']+'</td>'
        +'<td class="text-center"><input type="hidden" name="NoSeriAkhir[]" value="'+DataItem[i]['NoSeriAkhir']+'">'+DataItem[i]['NoSeriAkhir']+'</td>'
        +'</tr>';
      }
      btnSubmit = '<button type="submit" name="SimpanData" class="btn btn-success btn-sm">Simpan</button>';
      $('#tableBody').html(strTable);
	  $('#btnSubmit').html(btnSubmit);
    }

    function formatNumber(num) {
	  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
	}

	</script>
  </body>
</html>