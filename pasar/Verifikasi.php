<?php
include '../pasar/akses.php';
$Page = 'Verifikasi';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <!-- <meta name="description" content=""> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <!-- <meta name="robots" content="all,follow"> -->
    <!-- Bootstrap CSS-->
    <!-- <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css"> -->
    <!-- Font Awesome CSS-->
    <!-- <link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css"> -->
    <!-- Fontastic Custom icon font-->
    <!-- <link rel="stylesheet" href="../komponen/css/fontastic.css"> -->
    <!-- Google fonts - Poppins -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700"> -->
    <!-- theme stylesheet-->
	<!-- <?php include 'style.php';?> -->
    <!-- Custom stylesheet - for your changes-->
    <!-- <link rel="stylesheet" href="../komponen/css/custom.css"> -->
	<!-- Sweet Alerts -->
    <!-- <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet"> -->
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Select2Master -->
	<!-- <link rel="stylesheet" href="../library/select2master/css/select2.css"/> -->
	<!-- <link rel="stylesheet" type="text/css" href="../library/datatables/datatables.min.css"/> -->
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
              <h2 class="no-margin-bottom">Verifikasi Peneriman</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<!-- <ul class="nav nav-pills"> -->
						<!-- <li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Karcis Retribusi</span></a>&nbsp;
						</li> -->
						<!-- <li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
								<a href="Pencetakan_tambah.php"><span class="btn btn-primary">Entry Pelayanan</span></a>&nbsp;
							<?php } ?>
						</li> -->
					<!-- </ul><br/> -->
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Verifikasi Peneriman</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Tanggal..." id="time1" value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>No Permintaan Request</th>
									  <th>Tanggal Transaksi</th>
									  <th>Jumlah Nilai Karcis </th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "Pencetakan.php?pagination=true";
										$sql =  "SELECT t.NoTransArusKB, t.TanggalTransaksi, t.TipeTransaksi, t.KodePasar, t.NoTrRequest, t.Keterangan, t.KodeBatchPencetakan, t.TotalNilaKB, t.UserName	
											FROM traruskb t
											JOIN traruskbitem i ON t.NoTransArusKB=i.NoTransArusKB
											WHERE t.TipeTransaksi='PENGIRIMAN' AND t.KodePasar='$KodePasar' AND i.JumlahKirim IS NULL";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND t.TanggalTransaksi LIKE '%".$_REQUEST['keyword']."%'  ";
										}
										
										$sql .="
										GROUP BY t.NoTransArusKB
									    ORDER BY t.TanggalTransaksi ASC";
										$result = mysqli_query($koneksi,$sql);
										
										//pagination config start
										$rpp = 20; // jumlah record per halaman
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
											<td align="left">
												<?php echo $data ['NoTrRequest']; ?>
											</td>
											<td>
												<?php echo @TanggalIndo($data['TanggalTransaksi']); ?>
											</td>
											<td align="right">
												<?php echo number_format($data ['TotalNilaKB']); ?>
											</td>
											<td width="100px" align="center">
												<a href="Verifikasi_edit.php?id=<?php echo base64_encode($data['NoTransArusKB']);?>" title='Edit'><i class='btn btn-primary btn-sm'><span class='fa fa-check'></span> Cek Stok</i></a> 
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
          </section>
        </div>
      </div>
    </div>

    <div id="dialog-hapus" class="modal fade" role="dialog">
	    <div class="modal-dialog">
	        <!-- konten modal-->
	        <div class="modal-content">
				<form action="Pencetakan_aksi.php" method="post">
	            <!-- heading modal -->
	            <div class="modal-header">
	                <h4 class="modal-title">Konfirmasi Hapus</h4>
	            </div>
	            <div class="modal-body" style="padding:27px;">
						<input type="hidden" name="view" value="1">
						<input type="hidden" name="id" id="id" value="1">
						<input type="hidden" name="nm" id="nm" value="1">
						<input type="hidden" name="aksi" id="aksi" value="1">
					<label for="">Apakah anda yakin akan menghapus data ini ?</label>
				</div>
				<div class="modal-footer">
					<button type="submit" id="btn-hapus-modal" class="btn btn-sm btn-danger">Hapus</button>	
					<a href="#" data-dismiss="modal" class="btn btn-sm btn-secondary">Batal</a>		
				</div>
				</form>
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
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
	
  </body>
</html>