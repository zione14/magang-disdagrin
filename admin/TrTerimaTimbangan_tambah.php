<?php
include 'akses.php';
@$fitur_id = 13;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'TrMetrologi';
$SubPage = 'TrTerimaTimbangan';
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
              <h2 class="no-margin-bottom">Penerimaan Timbangan User</h2>
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
							<form method="post" action="TrTerimaTimbangan_aksi.php"  class="form-horizontal">
							<div class="card-body">
								<div class="row">
								
								  <div class="col-lg-12">
									<h5>Pilih User Pemohon</h5>
									
									<div class="form-group-material">
										<div class="input-group">
											<input type="text" class="form-control"  id="nama" placeholder="Pilih Person"  value="<?php echo  @$RowData['NamaPerson']; ?>" readonly="" />&nbsp;&nbsp;
											<div class="input-group-append">
											<button  type="button" class="btn btn-primary" id="btnCari" >Cari User</button>
											</div>
										</div>
									</div>
									<input type="hidden" name="IDPerson"  value="<?php echo @$RowData['IDPerson']; ?>" id="id">
									<input type="hidden" name="UserName" value="<?=$login_id?>">
								
									<h5>Detil Timbangan</h5>
									<div class="col-md-12 text-right">
                                  		<button type="button" class="btn btn-secondary btn-sm" id="btnAdd">Tambah</button>
                                	</div>
                                	<br>
									<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Aksi</th>
											  <th>No</th>
											  <th>Nama Timbangan</th>
											  <th>Keterangan</th>
											</tr>
										  </thead>
										  <tbody id="tableBody">
											
                                          </tbody>
										</table>
									</div><hr>	
									</div>
									<div class="col-lg-12">
										<div class="text-left">
											<div class="form-group row">
												<label class="col-sm-3 form-control-label">Keterangan</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="Keterangan" value="<?php echo @$RowData['Keterangan'];?>" name="txtKeterangan" >
												</div>
											</div>
											<div id="btnSubmit">
												<a href="TrTerimaTimbangan.php"><span class="btn btn-danger">Kembali</span></a>
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
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width:800px">
                <div class="modal-content">
                  <div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Pencarian User Pemilik UTTP</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        
                    </div>
                    <div class="modal-body">
					<div class="table-responsive">  
                        <table id="example" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
									<th>Alamat</th>
									<th>Timbangan</th>
                                </tr>
                            </thead>
                            <tbody><!-- -->
                                <?php
								
                                $query = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE  JenisPerson LIKE '%Timbangan%' AND IsVerified=b'1'  ORDER BY NamaPerson ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr id="pilih" data-id="<?php echo $data['IDPerson']; ?>" data-nama="<?php echo $data['NamaPerson']; ?>">
                                        <td><?php echo $data['NamaPerson']; ?></td>
                                        <td><?php echo $data['NIK']; ?></td>
										<td><?php echo $data['AlamatLengkapPerson']; ?></td>
										<td>
											<?php 
												$tim = mysqli_query($koneksi,"SELECT IDTimbangan FROM timbanganperson WHERE  IDPerson='".$data['IDPerson']."' AND StatusUTTP='Aktif'  ORDER BY IDTimbangan ASC");
												while ($t = mysqli_fetch_array($tim)) {
													echo $t['IDTimbangan'].'<br>';
												}
											?>
										</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>  
                    </div>
                    </div>
                </div>
            </div>
        </div>
		
	<div class="modal fade" id="ModalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="form_target">
        <div class="modal-content">
          <div class="modal-header">
		  	<h4 id="exampleModalLabel" class="modal-title">Hasil Tera Timbangan</h4>
		  	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		  </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-control-label">Nama Fitur</label>
                  <select name="IDTimbangan" id="IDTimbangan" class="form-control" required>	
				  </select>
                </div>
                <div class="form-group" align="left">
					<label>Keterangan</label>
					<textarea type="text" name="Keterangan" id="Keterangan" class="form-control" rows="4" placeholder="Keterangan"><?php echo @$row['Keterangan']; ?></textarea>
				</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary btn-sm" type="submit">Pilih</button>
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
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
		
		$(document).on('click', '#btnAdd', function () {
	      $('#ModalTambah').modal('show');
		   var IDPerson  = $("#id").val();
		   $.ajax({
			url: "../library/Dropdown/ambil-timbangantera.php",
			data: "IDPerson="+IDPerson,
			cache: false,
			success: function(msg){
				$("#IDTimbangan").html(msg);
			}
		  });
	    });
				
		 // jika dipilih, nim akan masuk ke input dan modal di tutup
            $(document).on('click', '#pilih', function (e) {
                document.getElementById("id").value = $(this).attr('data-id');
                document.getElementById("nama").value = $(this).attr('data-nama');
                $('#myModal').modal('hide');
            });
		
		  // open modal lihat progress
		
		$(document).on('click', '#btnCari', function () {
	      $('#myModal').modal('show');
		  var table = $('#example').DataTable();
		  var input = $('#example_filter input');
			setTimeout(function(){
				input.focus();
			}, 500);
	    });
		
		$(document).on('click', '#nama', function () {
	      $('#myModal').modal('show');
		  var table = $('#example').DataTable();
		  var input = $('#example_filter input');
			setTimeout(function(){
				input.focus();
			}, 500);
	    });
		
		var DataItem = [];
	    var NamaTimbangan = '';
	    var KodeLokasi = '';

	    $('#IDTimbangan').change(function () {
      		NamaTimbangan = $(this).find('option:selected').attr('data-nama');
      		KodeLokasi = $(this).find('option:selected').attr('data-lokasi');
   		});

		
		$(document).on('click', '#btnHapus', function () {
	      var NoUrut = $(this).attr("value");
	      DataItem.splice(NoUrut, 1);     
	      DrawTable();
	    });

		$("#form_target").submit(function(e) {
	      e.preventDefault();
	      var IDTimbangan      = $('#IDTimbangan').val();
	      var Keterangan       = $('#Keterangan').val();
          var dataItem = [];
	      dataItem['IDTimbangan']   = IDTimbangan;
	      dataItem['Keterangan']    = Keterangan;
	      dataItem['NamaTimbangan'] = NamaTimbangan;
	      dataItem['KodeLokasi'] = KodeLokasi;
	      
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
        +'<td class="text-center">'+(i+1)+'<input type="hidden" name="KodeLokasi[]" value="'+DataItem[i]['KodeLokasi']+'"></td>'
        +'<td>'+DataItem[i]['IDTimbangan']+' <br>'+DataItem[i]['NamaTimbangan'] +'<input type="hidden" name="IDTimbangan[]" value="'+DataItem[i]['IDTimbangan']+'"></td>'
		+'<td>'+DataItem[i]['Keterangan']+'<input type="hidden" name="Keterangan[]" value="'+DataItem[i]['Keterangan']+'"></td>'
        +'</tr>';
      }
      btnSubmit = '<button type="submit" name="SimpanData" class="btn btn-success btn-sm">Simpan</button>';
      $('#tableBody').html(strTable);
	  $('#btnSubmit').html(btnSubmit);
    }
	</script>
  </body>
</html>