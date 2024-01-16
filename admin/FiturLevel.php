<?php
include 'akses.php';
$Page = 'Security';
$SubPage = 'AksesLevel';
$fitur_id = 6;
include '../library/lock-menu.php'; 
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(base64_decode(@$_GET['aksi'])=='NonAktif'){
	$Hapus = mysqli_query($koneksi,"DELETE FROM fiturlevel WHERE LevelID='".base64_decode($_GET['id'])."' AND FiturID='".base64_decode($_GET['data'])."'");
	if($Hapus){
		InsertLog($koneksi, 'Hapus Data', 'Menghapus Akses Level User '.base64_decode($_GET['nm']), $login_id, '', 'Akses Level');
		 echo '<script> window.location="FiturLevel.php?id='.$_GET['id'].'&nm='.$_GET['nm'].'"</script>';
	}else{
		 echo '<script>alert("Set Hak Akses Gagal"); window.location="FiturLevel.php?id='.$_GET['id'].'&nm='.$_GET['nm'].'"</script>';
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
   <style>
		 th,td {
			text-align: center;
		}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "AksesLevel.php";
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
              <h2 class="no-margin-bottom">Akses Level</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-10">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Set Fitur Akses</h3>
					</div>
					<div class="card-body">
					 <div class="col-lg-12 col-md-12" style="margin:10px 0px;">
						<ul class="nav nav-tabs">
							<!-- <li class="nav-item">
								<a href="#admin" data-toggle="tab" class="nav-link <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>"><span>Admin Pusat</span></a>&nbsp;
							</li> -->
							<li class="nav-item">
								<a href="#metrologi" data-toggle="tab" class="nav-link <?php if(!$_GET['view']==1){ echo 'in active show'; }?>"><span>Metrologi</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#hargapasar" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==2){ echo 'in active show'; }?>"><span>Harga Pasar</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#retribusi" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==2){ echo 'in active show'; }?>"><span>Retribusi Pasar</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#pupuksub" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==2){ echo 'in active show'; }?>"><span>Pupuk Subsidi</span></a>&nbsp;
							</li>
						</ul>
                     </div>
					<div class="col-lg-12 col-md-12 mb-20">
						<div class="tab-content">
							<!-- <div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>" id="admin">
								<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Menu Akses</th>
										  <th>Hak Akses</th>
										  <th>Setting</th>
										</tr>
									  </thead>
										<?php
											$sql =  "SELECT * FROM serverfitur  where Bidang ='Admin Pusat' ORDER BY FiturID ASC";
											$result = mysqli_query($koneksi,$sql);
											$no_urut = 0;
												
										?>
										<tbody>
											<form action="" method="post">
											<?php
												while($data = mysqli_fetch_array($result)) {
											?>
											<tr class="odd gradeX">
												<td class="text-left" width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td class="text-left" >
													<strong><?php echo $data ['FiturName']; ?></strong>
												</td>
												<td align="center">
													<?php
														$cek2 = mysqli_query($koneksi,"select * from fiturlevel where LevelID='".base64_decode($_GET['id'])."' AND FiturID='".$data ['FiturID']."'");
														$res = mysqli_fetch_array($cek2);
														$num2 = mysqli_num_rows($cek2);
														if($num2 == 1 ){
													?>
														<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
														<a href="FiturLevel.php?id=<?php echo $_GET['id'];?>&data=<?php echo base64_encode($data ['FiturID']);?>&aksi=<?php echo base64_encode('NonAktif');?>&nm=<?php echo $_GET['nm'];?>" title='Klik tombol untuk menonaktifkan fitur akses menu ini !'><i class='btn btn-success btn-sm'><span class='fa fa-check'></span> Akses Verified</i></a>
														<?php } ?>
													<?php }else{ ?>
														<?php if (@$cek_fitur['AddData'] =='1'){ ?>
														<input type="hidden" name="LevelID"  value="<?php echo base64_decode($_GET['id']);?>">
														<input type="hidden" 	name="LevelName"  value="<?php echo base64_decode($_GET['nm']);?>">
														<input type="checkbox" id="cekbox" name="cekbox[]" value="<?php echo $data['FiturID'] ?>"/>
														<?php } ?>
													<?php } ?>
												</td>
												<td align="center">
													<?php if($num2 == 1 ){ ?>
														<!-- Tombol Detil Data Timbangan Per user -->
														<!--<?php if (@$cek_fitur['EditData'] =='1'){ ?>
														<a href="#" class='open_modal' data-levelid='<?php echo base64_decode($_GET['id']);?>' data-fiturid='<?php echo $data['FiturID'];?>' data-levelname='<?php echo base64_decode($_GET['nm']);?>' data-login_id='<?php echo $login_id;?>'>
														<span class="btn btn-sm btn-info fa fa-cog"  title="Akses Fitur Level"></span></a>	
														<?php }?>
													<?php }?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
											<input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />
											<br/><br/>
										</form>
									</table>
								</div>
                            </div> -->
							<div class="tab-pane fade <?php if(!$_GET['view']==1){ echo 'in active show'; }?>" id="metrologi">
								<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Menu Akses</th>
										  <th>Hak Akses</th>
										  <th>Setting</th>
										</tr>
									  </thead>
										<?php
											$sql =  "SELECT * FROM serverfitur  where Bidang ='Metrologi' ORDER BY FiturID ASC";
											$result = mysqli_query($koneksi,$sql);
											$no_urut = 0;
												
										?>
										<tbody>
											<form action="" method="post">
											<?php
												while($data = mysqli_fetch_array($result)) {
											?>
											<tr class="odd gradeX">
												<td class="text-left" width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td class="text-left" >
													<strong><?php echo $data ['FiturName']; ?></strong>
												</td>
												<td align="center">
													<?php
														$cek2 = mysqli_query($koneksi,"select * from fiturlevel where LevelID='".base64_decode($_GET['id'])."' AND FiturID='".$data ['FiturID']."'");
														$res = mysqli_fetch_array($cek2);
														$num2 = mysqli_num_rows($cek2);
														if($num2 == 1 ){
													?>
														<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
														<a href="FiturLevel.php?id=<?php echo $_GET['id'];?>&data=<?php echo base64_encode($data ['FiturID']);?>&aksi=<?php echo base64_encode('NonAktif');?>&nm=<?php echo $_GET['nm'];?>" title='Klik tombol untuk menonaktifkan fitur akses menu ini !'><i class='btn btn-success btn-sm'><span class='fa fa-check'></span> Akses Verified</i></a>
														<?php } ?>
													<?php }else{ ?>
														<?php if (@$cek_fitur['AddData'] =='1'){ ?>
														<input type="hidden" name="LevelID"  value="<?php echo base64_decode($_GET['id']);?>">
														<input type="hidden" 	name="LevelName"  value="<?php echo base64_decode($_GET['nm']);?>">
														<input type="checkbox" id="cekbox" name="cekbox[]" value="<?php echo $data['FiturID'] ?>"/>
														<?php } ?>
													<?php } ?>
												</td>
												<td align="center">
													<?php if($num2 == 1 ){ ?>
														<!-- Tombol Detil Data Timbangan Per user -->
														<?php if (@$cek_fitur['EditData'] =='1'){ ?>
														<a href="#" class='open_modal' data-levelid='<?php echo base64_decode($_GET['id']);?>' data-fiturid='<?php echo $data['FiturID'];?>' data-levelname='<?php echo base64_decode($_GET['nm']);?>' data-login_id='<?php echo $login_id;?>'>
														<span class="btn btn-sm btn-info fa fa-cog"  title="Akses Fitur Level"></span></a>	
														<?php }?>
													<?php }?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
											<input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />
											<br/><br/>
										</form>
									</table>
								</div>
                            </div>
							<div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="hargapasar">
								<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Menu Akses</th>
										  <th>Hak Akses</th>
										  <th>Setting</th>
										</tr>
									  </thead>
										<?php
											$sql =  "SELECT * FROM serverfitur  where Bidang ='Harga Pasar' ORDER BY FiturID ASC";
											$result = mysqli_query($koneksi,$sql);
											$no_urut = 0;
												
										?>
										<tbody>
											<form action="" method="post">
											<?php
												while($data = mysqli_fetch_array($result)) {
											?>
											<tr class="odd gradeX">
												<td class="text-left" width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td class="text-left" >
													<strong><?php echo $data ['FiturName']; ?></strong>
												</td>
												<td align="center">
													<?php
														$cek2 = mysqli_query($koneksi,"select * from fiturlevel where LevelID='".base64_decode($_GET['id'])."' AND FiturID='".$data ['FiturID']."'");
														$res = mysqli_fetch_array($cek2);
														$num2 = mysqli_num_rows($cek2);
														if($num2 == 1 ){
													?>
														<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
														<a href="FiturLevel.php?id=<?php echo $_GET['id'];?>&data=<?php echo base64_encode($data ['FiturID']);?>&aksi=<?php echo base64_encode('NonAktif');?>&nm=<?php echo $_GET['nm'];?>" title='Klik tombol untuk menonaktifkan fitur akses menu ini !'><i class='btn btn-success btn-sm'><span class='fa fa-check'></span> Akses Verified</i></a>
														<?php } ?>
													<?php }else{ ?>
														<?php if (@$cek_fitur['AddData'] =='1'){ ?>
														<input type="hidden" name="LevelID"  value="<?php echo base64_decode($_GET['id']);?>">
														<input type="hidden" 	name="LevelName"  value="<?php echo base64_decode($_GET['nm']);?>">
														<input type="checkbox" id="cekbox" name="cekbox[]" value="<?php echo $data['FiturID'] ?>"/>
														<?php } ?>
													<?php } ?>
												</td>
												<td align="center">
													<?php if($num2 == 1 ){ ?>
														<!-- Tombol Detil Data Timbangan Per user -->
														<?php if (@$cek_fitur['EditData'] =='1'){ ?>
														<a href="#" class='open_modal' data-levelid='<?php echo base64_decode($_GET['id']);?>' data-fiturid='<?php echo $data['FiturID'];?>' data-levelname='<?php echo base64_decode($_GET['nm']);?>' data-login_id='<?php echo $login_id;?>'>
														<span class="btn btn-sm btn-info fa fa-cog"  title="Akses Fitur Level"></span></a>	
														<?php }?>
													<?php }?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
											<input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />
											<br/><br/>
										</form>
									</table>
								</div>
							</div>
								 <div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="retribusi">
									<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Menu Akses</th>
										  <th>Hak Akses</th>
										  <th>Setting</th>
										</tr>
									  </thead>
										<?php
											$sql =  "SELECT * FROM serverfitur  where Bidang ='Retribusi Pasar' ORDER BY FiturID ASC";
											$result = mysqli_query($koneksi,$sql);
											$no_urut = 0;
												
										?>
										<tbody>
											<form action="" method="post">
											<?php
												while($data = mysqli_fetch_array($result)) {
											?>
											<tr class="odd gradeX">
												<td class="text-left" width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td class="text-left" >
													<strong><?php echo $data ['FiturName']; ?></strong>
												</td>
												<td align="center">
													<?php
														$cek2 = mysqli_query($koneksi,"select * from fiturlevel where LevelID='".base64_decode($_GET['id'])."' AND FiturID='".$data ['FiturID']."'");
														$res = mysqli_fetch_array($cek2);
														$num2 = mysqli_num_rows($cek2);
														if($num2 == 1 ){
													?>
														<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
														<a href="FiturLevel.php?id=<?php echo $_GET['id'];?>&data=<?php echo base64_encode($data ['FiturID']);?>&aksi=<?php echo base64_encode('NonAktif');?>&nm=<?php echo $_GET['nm'];?>" title='Klik tombol untuk menonaktifkan fitur akses menu ini !'><i class='btn btn-success btn-sm'><span class='fa fa-check'></span> Akses Verified</i></a>
														<?php } ?>
													<?php }else{ ?>
														<?php if (@$cek_fitur['AddData'] =='1'){ ?>
														<input type="hidden" name="LevelID"  value="<?php echo base64_decode($_GET['id']);?>">
														<input type="hidden" 	name="LevelName"  value="<?php echo base64_decode($_GET['nm']);?>">
														<input type="checkbox" id="cekbox" name="cekbox[]" value="<?php echo $data['FiturID'] ?>"/>
														<?php } ?>
													<?php } ?>
												</td>
												<td align="center">
													<?php if($num2 == 1 ){ ?>
														<!-- Tombol Detil Data Timbangan Per user -->
														<?php if (@$cek_fitur['EditData'] =='1'){ ?>
														<a href="#" class='open_modal' data-levelid='<?php echo base64_decode($_GET['id']);?>' data-fiturid='<?php echo $data['FiturID'];?>' data-levelname='<?php echo base64_decode($_GET['nm']);?>' data-login_id='<?php echo $login_id;?>'>
														<span class="btn btn-sm btn-info fa fa-cog"  title="Akses Fitur Level"></span></a>	
														<?php }?>
													<?php }?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
											<input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />
											<br/><br/>
										</form>
									</table>
								</div>
								</div>
								<div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="pupuksub">
									<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Menu Akses</th>
										  <th>Hak Akses</th>
										  <th>Setting</th>
										</tr>
									  </thead>
										<?php
											$sql =  "SELECT * FROM serverfitur  where Bidang ='Pupuk Subsidi' ORDER BY FiturID ASC";
											$result = mysqli_query($koneksi,$sql);
											$no_urut = 0;
												
										?>
										<tbody>
											<form action="" method="post">
											<?php
												while($data = mysqli_fetch_array($result)) {
											?>
											<tr class="odd gradeX">
												<td class="text-left" width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td class="text-left" >
													<strong><?php echo $data ['FiturName']; ?></strong>
												</td>
												<td align="center">
													<?php
														$cek2 = mysqli_query($koneksi,"select * from fiturlevel where LevelID='".base64_decode($_GET['id'])."' AND FiturID='".$data ['FiturID']."'");
														$res = mysqli_fetch_array($cek2);
														$num2 = mysqli_num_rows($cek2);
														if($num2 == 1 ){
													?>
														<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
														<a href="FiturLevel.php?id=<?php echo $_GET['id'];?>&data=<?php echo base64_encode($data ['FiturID']);?>&aksi=<?php echo base64_encode('NonAktif');?>&nm=<?php echo $_GET['nm'];?>" title='Klik tombol untuk menonaktifkan fitur akses menu ini !'><i class='btn btn-success btn-sm'><span class='fa fa-check'></span> Akses Verified</i></a>
														<?php } ?>
													<?php }else{ ?>
														<?php if (@$cek_fitur['AddData'] =='1'){ ?>
														<input type="hidden" name="LevelID"  value="<?php echo base64_decode($_GET['id']);?>">
														<input type="hidden" 	name="LevelName"  value="<?php echo base64_decode($_GET['nm']);?>">
														<input type="checkbox" id="cekbox" name="cekbox[]" value="<?php echo $data['FiturID'] ?>"/>
														<?php } ?>
													<?php } ?>
												</td>
												<td align="center">
													<?php if($num2 == 1 ){ ?>
														<!-- Tombol Detil Data Timbangan Per user -->
														<?php if (@$cek_fitur['EditData'] =='1'){ ?>
														<a href="#" class='open_modal' data-levelid='<?php echo base64_decode($_GET['id']);?>' data-fiturid='<?php echo $data['FiturID'];?>' data-levelname='<?php echo base64_decode($_GET['nm']);?>' data-login_id='<?php echo $login_id;?>'>
														<span class="btn btn-sm btn-info fa fa-cog"  title="Akses Fitur Level"></span></a>	
														<?php }?>
													<?php }?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
											<input type="button" class="btn btn-md btn-warning" onclick="cek(this.form.cekbox)" value="Select All" />&nbsp;
											<input type="button" class="btn btn-md btn-danger" onclick="uncek(this.form.cekbox)" value="Clear All" />&nbsp;
											<input type="submit" class="btn btn-md btn-success" value="Simpan" name="submit" />
											<br/><br/>
										</form>
									</table>
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
	
	<script type="text/javascript">
	   // open modal lihat dokumen
	   $(document).ready(function () {
	   $(".open_modal").click(function(e) {
		  var id_fitur = $(this).data("fiturid");
		  var id_level = $(this).data("levelid");
		  var level_nama = $(this).data("levelname");
		  var id_login = $(this).data("login_id");
		 
		  	   $.ajax({
					   url: "SettingFitur.php",
					   type: "GET",
					   data : {FiturID: id_fitur,LevelID : id_level,LevelName :level_nama, LoginID: id_login},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
	
	<?php 

	if(isset($_POST['submit'])){
		$LevelID	= $_POST['LevelID'];
		$LevelName	= $_POST['LevelName'];
		$cekbox = @$_POST['cekbox'];
		if ($cekbox) {
			foreach ($cekbox as $value) {
				
				mysqli_query($koneksi,"REPLACE into fiturlevel (LevelID,FiturID,ViewData,AddData,EditData,DeleteData,PrintData)values('$LevelID','$value',b'1',b'1',b'1',b'1',b'1')");
			}
			InsertLog($koneksi, 'Tambah Data', 'Menambah Akses Level User '.$LevelName, $login_id, '', 'Akses Level');
			echo '<script>window.location="FiturLevel.php?id='.base64_encode($LevelID).'&nm='.base64_encode($LevelName).'"</script>';
		}else {
			echo '<script>alert("Anda belum memilih menu akses untuk di verifikasi"); window.location="FiturLevel.php?id='.base64_encode($LevelID).'&nm='.base64_encode($LevelName).'"</script>';
		} 	
	
	}
	
	
	?>
	
  </body>
</html>