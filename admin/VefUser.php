<?php
include 'akses.php';
@$fitur_id = 7;
include '../library/lock-menu.php';
$Page = 'VerifikasiUser';

if(@$_GET['id']!=null){
	$Sebutan = 'Detil Data Timbangan';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
	@$RowData = mysqli_fetch_assoc($Edit);
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
	<!-- akhir dari Bagian js -->
	<?php include '../library/ViewMaps.php';?>
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
              <h2 class="no-margin-bottom">Verifikasi User</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab">
							<!--<span class="btn btn-primary">Verifikasi</span>--></a>&nbsp;
						</li>
						<li>
							<a href="#tambah-user" data-toggle="tab"></a>&nbsp;
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Verifikasi User</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
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
									  <th>Nama</th>
									  <th>Alamat</th>
									  <th>Jenis Person</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$kosong=null;
										if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
											// jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)pakai ini
											$keyword=$_REQUEST['keyword'];
											$reload = "TimbanganUser.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT NamaPerson,AlamatLengkapPerson,JenisPerson,IDPerson FROM mstperson where  NamaPerson LIKE '%$keyword%' and IsVerified=b'1' and JenisPerson LIKE '%Timbangan%' ORDER BY NamaPerson ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "TimbanganUser.php?pagination=true";
											$sql =  "SELECT NamaPerson,AlamatLengkapPerson,JenisPerson,IDPerson FROM mstperson where IsVerified=b'0' and JenisPerson LIKE '%Timbangan%' ORDER BY NamaPerson ASC";
											@$result = mysqli_query($koneksi,$sql);
										}
										
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
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><br><h5>Tidak Ada Data</h5><br></td></tr>';
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
												<strong><?php echo $data ['NamaPerson']; ?></strong>
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
											</td>
											<td align="center">
												<?php echo $data ['JenisPerson']; ?>
											</td>
											<td width="100px" align="center">
												<?php if ($cek_fitur['ViewData'] =='1'){ ?>
												<!-- Tombol Detil Data Timbangan Per user-->		
													<a href="VefUser.php?id=<?php echo base64_encode($data['IDPerson']);?>" title='Lihat Detil Timbangan'><i class='btn btn-info btn-sm'><span class='fa fa-info-circle'></span></i></a>
												<?php } ?>
											
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
						<div class="tab-pane fade <?php if(@$_GET['id']!=null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									<h5>Informasi Pemohon</h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <tbody>
											<tr><td width="20%">Nama User/ PJ</td><td width="1%">:</td><td><?php echo strtoupper($RowData['NamaPerson'])." / ".strtoupper($RowData['PJPerson']);?></td></tr>
											<tr><td>Jenis User</td><td>:</td><td><?php echo $RowData['JenisPerson'];?></td></tr>
											<tr><td>Alamat</td><td>:</td><td><?php echo $RowData['AlamatLengkapPerson'];?></td></tr>
											<tr><td>Foto Pemohon</td><td>:</td><td><a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['GambarPerson'];?>' data-url='<?php echo 'FotoPerson';?>'><i  class="btn btn-success ">Preview</i></a></td></tr>
											<tr><td>Foto KTP Pemohon</td><td>:</td><td><a href="#" class='open_modal_view' data-dokumen='<?php echo $RowData['FotoKTP'];?>' data-url='<?php echo 'FotoPerson';?>'><i  class="btn btn-success ">Preview</i></a></td></tr>
											
										  </tbody>
										</table>
										</div><hr>
										<br><h5>Detil Lokasi <?php echo $RowData['NamaPerson']; ?></h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>No</th>
											  <th>Nama Lokasi</th>
											  <th>Alamat</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$query =mysqli_query($koneksi, "SELECT b.NamaLokasi,b.AlamatLokasi,b.KodeLokasi,a.NamaPerson,a.IDPerson FROM mstperson a join lokasimilikperson b on a.IDPerson=b.IDPerson WHERE  a.IsVerified=b'0' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='".$RowData['IDPerson']."' ORDER BY b.KodeLokasi ASC");
												$no_urut = 0;
												$count1 = mysqli_num_rows($query);
												if($count1 == null OR $count1 === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($hasil = mysqli_fetch_array($query)){
											?>
											<tr class="odd gradeX">
												
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<strong><?php echo $hasil['NamaLokasi']; ?></strong>
												</td>
												<td>
													<?php echo $hasil['AlamatLokasi']; ?>
												</td>
												
											</tr>
												<?php } }?>
										  </tbody>
										</table>
									</div>
									<hr>	
									<br><h5>Detil Timbangan <?php echo $RowData['NamaPerson']; ?></h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Aksi</th>
											  <th>No</th>
											  <th>Nama Timbangan</th>
											  <th>Jenis Timbangan</th>
											  <th>Alamat</th>
											  <th>Tarif Retribusi</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT b.IDTimbangan,c.JenisTimbangan,c.NamaTimbangan,b.NamaTimbangan as RealName,a.NamaPerson,a.IDPerson,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,f.NamaLokasi,f.AlamatLokasi FROM mstperson a join timbanganperson b on a.IDPerson=b.IDPerson join msttimbangan c on b.KodeTimbangan=c.KodeTimbangan join kelas d on c.KodeTimbangan=d.KodeTimbangan join detilukuran e on (d.KodeTimbangan,d.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) join lokasimilikperson f on (f.KodeLokasi,f.IDPerson)=(b.KodeLokasi,b.IDPerson) WHERE  a.IsVerified=b'0' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='".$RowData['IDPerson']."' GROUP BY b.IDTimbangan ASC");
												$no_urut = 0;
												$count = mysqli_num_rows($sql);
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($res = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												<td width="50px">
													<!-- Tombol Detil Data Timbangan Per user-->											
													<a href="#" class='open_modal' data-idtimbangan='<?php echo $res['IDTimbangan'];?>'>
													<span class="btn btn-sm btn-warning fa fa-eye"  title="Lihat Detil Foto Timbangan"></span></a>
												</td>
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<strong><?php echo $res['RealName']; ?></strong>
												</td>
												<td>
													<?php echo $res['NamaTimbangan']." ".$res['NamaKelas']." ".$res['NamaUkuran'].'<br>'.$res['JenisTimbangan']; ?>
												</td>
												<td>
													<?php echo $res['NamaLokasi']."<br> ".$res['AlamatLokasi']; ?>
												</td>
												<td>
													<?php echo "Dikantor : Rp ".number_format($res ['RetribusiDikantor'])."<br>"; ?>
												<?php echo "Dilokasi : Rp ".number_format($res ['RetribusiDiLokasi']).""; ?>
												</td>
											</tr>
												<?php } }?>
										  </tbody>
										</table>
									</div>
									<hr>
									<h5>Semua Lokasi</h5>
										 <div class="col-lg-12">
											<div class="row">
												<div class="col-md-11">
													<div class="panel panel-default">
														<div class="panel-heading"></div>
														<div class="panel-body">
															<div id="map-canvas" style="width: 900px; height: 350px;"></div>
														</div>
													</div>
												</div>	
											</div>
										</div>
									<hr>
									
									
									</div>
									<div class="col-lg-6">
										<div class="text-left">
											<form action="" method="post">
											<input type="hidden" name="IDPerson" value="<?php echo $RowData['IDPerson']; ?>"> 
											<input type="hidden" name="NamaPerson" value="<?php echo $RowData['NamaPerson']; ?>"> 
											<?php if ($cek_fitur['EditData'] =='1'){ ?>
											<button type="submit" class="btn btn-primary" name="Verifikasi">Verifikasi</button> &nbsp;
											<?php } ?>
											<a href="VefUser.php"><span class="btn btn-success">Kembali</span></a>
											</form>
										</div>
									  </form>
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
	<div id="ModalView" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	<div id="ModalDokumen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	
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
	<script type="text/javascript">
			// open modal lihat dokumen
	   $(document).ready(function () {
	   $(".open_modal").click(function(e) {
		  var id_timbangan = $(this).data("idtimbangan");
		 
		  	   $.ajax({
					   url: "LihatDokumen.php",
					   type: "GET",
					   data : {IDTimbangan: id_timbangan},
					   success: function (ajaxData){
					   $("#ModalView").html(ajaxData);
					   $("#ModalView").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		//open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_view").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
		  var url_foto  = $(this).data("url");
			   $.ajax({
					   url: "ViewFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen, URLocation: url_foto},
					   success: function (ajaxData){
					   $("#ModalDokumen").html(ajaxData);
					   $("#ModalDokumen").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		
		
	
	</script>
	

	
	
	<?php 
		
		if(isset($_POST['Verifikasi'])){
		@$IDPerson 		= htmlspecialchars($_POST['IDPerson']);
		@$NamaPerson 	= htmlspecialchars($_POST['NamaPerson']);
		
		// query update
		$query = mysqli_query($koneksi,"UPDATE mstperson SET IsVerified=b'1' WHERE IDPerson='$IDPerson'");
		if($query){
			InsertLog($koneksi, 'Verifikasi Data', 'Verifikasi Data User atas nama '.$NamaPerson, $login_id, $IDPerson, 'Verifikasi User');
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Verifikasi Data Berhasil!",
					text: "",
					type: "success"
				  },
				  function () {
					window.location.href = "VefUser.php";
				  });
				  </script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Verifikasi Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "VefUser.php";
				  });
				  </script>';
		}
	}
	
	
	?>
	
	
  </body>
</html>