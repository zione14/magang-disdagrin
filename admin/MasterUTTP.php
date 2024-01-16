<?php
include 'akses.php';
@$fitur_id = 2;
include '../library/lock-menu.php';
$Page = 'MasterUTTP';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Data UTTP';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM msttimbangan WHERE KodeTimbangan='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	@$res = explode("#", $RowData['JenisPerson']);
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
	th {
		text-align: center;
	}
	
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MasterUTTP.php";
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
              <h2 class="no-margin-bottom">Master Data UTTP </h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>
							<?php } ?>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data UTTP</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-6 offset-lg-6">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-warning" type="submit">Cari</button>
												<?php if ($cek_fitur['PrintData'] =='1'){ ?>
												<a href="../library/html2pdf/cetak/MasterTimbangan.php" title='Print Data' target="_BLANK"><span class="btn btn-secondary">Cetak Data UTTP</span></a>
												<?php } ?>
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
									  <th>Kode Pelaporan</th>
									  <th>Masa Tera</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$keyword = $_REQUEST['keyword'];
										$reload = "MasterUTTP.php?pagination=true&keyword=$keyword";
										$sql =  "SELECT * FROM msttimbangan  WHERE 1  ";
										
										if(@$_REQUEST['keyword']!=null){
												$sql .= " AND ( NamaTimbangan LIKE '%".$_REQUEST['keyword']."%' ) ";
											}
										$sql .=" ORDER BY NamaTimbangan ASC";
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
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<?php echo $data ['NamaTimbangan'];?>
											</td>
											<td>
												<?php echo $data ['JenisTimbangan'];?>
											</td>
											<td>
												<?php echo $data ['MasaTera']." Tahun";?>
											</td>
											<td width="150px" align="center">	
													<a href="MasterUkuran.php?kd=<?php echo base64_encode($data['KodeTimbangan']);?>&nm=<?php echo base64_encode($data['NamaTimbangan']);?>" title='Setting Ukuran Timbangan'><i class='btn btn-success btn-sm'><span class="fa fa-gavel"></span></i></a> 
												<?php
													if ($cek_fitur['EditData'] =='1'){ 
														echo '<a href="MasterUTTP.php?id='.base64_encode($data['KodeTimbangan']).'" title="Edit"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
													}
													if ($cek_fitur['DeleteData'] =='1'){ 
														echo ' <a href="MasterUTTP.php?id='.base64_encode($data['KodeTimbangan']).'&nm='.base64_encode($data['NamaTimbangan']).'&aksi='.base64_encode('Hapus').'&view=1" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													}
												?>
												
											</td>
										</tr>
										<?php
											$i++; 
											$count++;
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
								<form  method="post" action="">
								<div class="form-group ">
								  <label>Nama UTTP</label>
								  <div>
									<input type="hidden" class="form-control"  value="<?php echo @$RowData['KodeTimbangan'];?>" name="KodeTimbangan">
									<input type="text" class="form-control" maxlength="150" placeholder="Nama UTTP" value="<?php echo @$RowData['NamaTimbangan'];?>" name="NamaTimbangan" required>
								  </div>
								</div>
								<div class="form-group">
								  <label>Kode Pelaporan</label>
								  <div>
									<input type="text" class="form-control" maxlength="100" placeholder="Kode Pelaporan" value="<?php echo @$RowData['JenisTimbangan'];?>" name="JenisTimbangan">
								  </div>
								</div>
								<div class="form-group">
								  <label>Masa Tera UTTP (Tahun) </label>
								  <div>
									<input type="number" class="form-control"  placeholder="Masa Tera UTTP" value="<?php echo @$RowData['MasaTera'];?>" name="MasaTera">
								  </div>
								</div>
								<div class="form-group">
									<input type="checkbox"  id="chkPassport" value="1" name="IsPunyaKelas" <?php if(@$RowData['IsPunyaKelas'] == '1') { echo 'checked'; }; ?>  class="checkbox-template">
									<label>Kelas</label>
								</div>
								<?php if (@$_GET['id']!= null AND $RowData['IsPunyaKelas'] == 1) { ?>
								
								  <div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>Aksi</th>
										  <th>No</th>
										  <th>Nama UTTP</th>
										  <th>Nama Kelas</th>
										  
										</tr>
									  </thead>
									<tbody>
									<?php 
									$no = 1;
									$QuerySyarat = mysqli_query($koneksi,"SELECT a.NamaKelas,a.KodeKelas,b.NamaTimbangan,b.KodeTimbangan FROM kelas a join msttimbangan b on a.KodeTimbangan=b.KodeTimbangan WHERE a.KodeTimbangan='".$RowData['KodeTimbangan']."' ");
									while($RowSyarat=mysqli_fetch_array($QuerySyarat)){
									?>
										<tr>
											<td align="left" width="100">
											<?php 
												if ($cek_fitur['DeleteData'] =='1'){ 
													echo ' <a href="MasterUTTP.php?id='.base64_encode($RowSyarat['KodeKelas']).'&cd='.base64_encode($RowSyarat['KodeTimbangan']).'&nm='.$RowSyarat['NamaKelas'].'&aksi='.base64_encode('Hapus').'&view=2" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
												}
											
											
											?>
											<!-- Tombol Edit Data Timbangan Per user-->
											<a href="#" class='open_modal_item' data-kodetim='<?php echo $RowData['KodeTimbangan'];?>' data-namatim='<?php echo $RowData['NamaTimbangan'];?>' data-user='<?php echo $login_id; ?>' data-nourut='<?php echo $RowSyarat['KodeKelas']; ?>' data-aksi='<?php echo 'Edit'; ?>'><span class="btn btn-warning btn-sm fa fa-edit" title="Edit Kelas Timbangan" ></span></a>
											
											
											</td>
											<td><?php echo $no++; ?></td>
											<td><?php echo $RowSyarat['NamaKelas']; ?></td>
											<td><?php echo $RowSyarat['NamaTimbangan']; ?></td>
											
											
										</tr>
									<?php } ?>
										<tr style="background-color:white;">
											<td colspan="9" align="right">
											<a href="#" class='open_modal_item' data-kodetim='<?php echo $RowData['KodeTimbangan'];?>' data-namatim='<?php echo $RowData['NamaTimbangan'];?>' data-user='<?php echo $login_id; ?>'><span class="btn btn-secondary " title="Tambah Kelas Timbangan">Tambah</span></a>
											</td>
										</tr>
									</tbody>
									</table>
								  </div>
								
								<?php } ?>
								<?php
									if(@$_GET['id']==null){
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
									}else{
										echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
									}
								?>
								<a href="MasterUTTP.php"><span class="btn btn-warning">Kembali</span></a>
							  </form>
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
	<script type="text/javascript">
		// open modal lihat progress
	   $(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var kd_tim = $(this).data("kodetim");
		  var nm_tim = $(this).data("namatim");
		  var user = $(this).data("user");
		  var no_urut = $(this).data("nourut");
		  var aksi = $(this).data("aksi");
		  	   $.ajax({
					   url: "Modal/AddKelas.php",
					   type: "GET",
					   data : {KodeTimbangan: kd_tim,NamaTimbangan: nm_tim,login_id: user,NoUrutTrans: no_urut,Aksi: aksi},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
	
	
	
	<?php
		@$NamaTimbangan 	=htmlspecialchars($_POST['NamaTimbangan']);
		@$JenisTimbangan 	=htmlspecialchars($_POST['JenisTimbangan']);
		@$MasaTera	 		=htmlspecialchars($_POST['MasaTera']);
		@$KodeTimbangan 	=htmlspecialchars($_POST['KodeTimbangan']);
		@$KodeKelas		 	=htmlspecialchars($_POST['KodeKelas']);
		@$IsPunyaKelas	 	=htmlspecialchars($_POST['IsPunyaKelas']);
		@$NamaKelas		 	=htmlspecialchars($_POST['NamaKelas']);
		@$NilaiBawah	 	=htmlspecialchars($_POST['NilaiBawah']);
		@$NilaiAtas		 	=htmlspecialchars($_POST['NilaiAtas']);
		@$NilaiTambah	 	=htmlspecialchars($_POST['NilaiTambah']);
		@$NamaUkuran	 	=htmlspecialchars($_POST['NamaUkuran']);
		@$KodeUkuran	 	=htmlspecialchars($_POST['KodeUkuran']);
		@$RetribusiDikantor	=htmlspecialchars($_POST['RetribusiDikantor']);
		@$RetribusiDiLokasi	=htmlspecialchars($_POST['RetribusiDiLokasi']);
		@$RetPenambahanDikantor	 	=htmlspecialchars($_POST['RetPenambahanDikantor']);
		@$RetPenambahanDiLokasi	 	=htmlspecialchars($_POST['RetPenambahanDiLokasi']);
		
	if(isset($_POST['Simpan'])){	
		// buat kode data timbangan
			$sql = mysqli_query($koneksi,'SELECT RIGHT(KodeTimbangan,7) AS kode FROM msttimbangan ORDER BY KodeTimbangan DESC LIMIT 1');  
			$nums = mysqli_num_rows($sql);
			if($nums <> 0){
				 $data = mysqli_fetch_array($sql);
				 $kode = $data['kode'] + 1;
			}else{
				 $kode = 1;
			}
			//mulai bikin kode
			 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
			 $kode_jadi = "TMB-".$bikin_kode;
			
			
			$query = mysqli_query($koneksi,"INSERT into msttimbangan(KodeTimbangan,NamaTimbangan,JenisTimbangan,MasaTera,IsPunyaKelas) 
			VALUES ('$kode_jadi','$NamaTimbangan','$JenisTimbangan','$MasaTera','$IsPunyaKelas')");
			if($query){
				if ($IsPunyaKelas != '1') {
						$sql1 = mysqli_query($koneksi,"SELECT RIGHT(KodeKelas,7) AS kode1 FROM kelas ORDER BY KodeKelas DESC LIMIT 1");  
						$nums1 = mysqli_num_rows($sql1);
						if($nums1 <> 0){
							 $data1 = mysqli_fetch_array($sql1);
							 $kode1 = $data1['kode1'] + 1;
						}else{
							 $kode1 = 1;
						}
						//mulai bikin kode
						 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
						 $kodekelas = "KLS-".$bikin_kode1;
						 echo $kodekelas;
						
						$query1 = mysqli_query($koneksi,"INSERT into kelas(KodeTimbangan,KodeKelas,Keterangan) VALUES ('$kode_jadi','$kodekelas','$NamaTimbangan')");
						
					if ($query1){
						InsertLog($koneksi, 'Tambah Data', 'Menambah Master Data Timbangan dengan nama '.$NamaTimbangan, $login_id, $kode_jadi, 'Master Data');
						echo '<script language="javascript">document.location="MasterUTTP.php";</script>';
					}else{
						echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterUTTP.php";
					  });
					  </script>';
					}
				}else{
					InsertLog($koneksi, 'Tambah Data', 'Menambah Master Data Timbangan dengan nama '.$NamaTimbangan, $login_id, $kode_jadi, 'Master Data');
					echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MasterUTTP.php?id='.base64_encode($kode_jadi).'";
					  });
					  </script>';
					
				}
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterUTTP.php";
					  });
					  </script>';
			}
	}
	if(isset($_POST['SimpanEdit'])){
		
		//query update
		$query = mysqli_query($koneksi,"UPDATE msttimbangan SET NamaTimbangan='$NamaTimbangan',JenisTimbangan='$JenisTimbangan',MasaTera='$MasaTera',IsPunyaKelas='$IsPunyaKelas' WHERE KodeTimbangan='$KodeTimbangan'");
		if($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Master Data Timbangan dengan nama '.$NamaTimbangan, $login_id, $KodeTimbangan, 'Master Data');
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "MasterUTTP.php";
				  });
				  </script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterUTTP.php";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		if(@$_GET['view']=='1'){
			mysqli_query($koneksi,"DELETE FROM kelas WHERE KodeTimbangan='".htmlspecialchars(base64_decode($_GET['id']))."'");
			$query = mysqli_query($koneksi,"DELETE FROM msttimbangan WHERE KodeTimbangan='".htmlspecialchars(base64_decode($_GET['id']))."'");
			if($query){
				 
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Master Data Timbangan dengan nama '.base64_decode(@$_GET['nm']), $login_id, base64_decode(@$_GET['id']), 'Master Data');
				echo '<script language="javascript">document.location="MasterUTTP.php"; </script>';
			}else{
				echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data telah digunakan di berbagai transaksi! ",
							type: "error"
						  },
						  function () {
							window.location.href = "MasterUTTP.php";
						  });
						  </script>';
			}
		}elseif(@$_GET['view']=='2'){
			$query = mysqli_query($koneksi,"DELETE FROM kelas WHERE KodeKelas='".htmlspecialchars(base64_decode($_GET['id']))."' AND KodeTimbangan='".htmlspecialchars(base64_decode($_GET['cd']))."'");
			if($query){
				
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Master Data Kelas Timbangan dengan nama '.@$_GET['nm'], $login_id, base64_decode(@$_GET['id']), 'Master Data');
				echo '<script language="javascript">document.location="MasterUTTP.php?id='.$_GET['cd'].'"; </script>';
			}else{
				echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data telah digunakan di berbagai transaksi! ",
							type: "error"
						  },
						  function () {
							window.location.href = "MasterUTTP.php?view='.$_GET['cd'].'";
						  });
						  </script>';
			}
		}
	
	}
	
		
	//Simpan Edit Item Timbangan
	if(isset($_POST['SimpanItem'])){
		$sql1 = mysqli_query($koneksi,"SELECT RIGHT(KodeKelas,7) AS kode1 FROM kelas  ORDER BY KodeKelas DESC LIMIT 1");  
		$nums1 = mysqli_num_rows($sql1);
		if($nums1 <> 0){
			 $data1 = mysqli_fetch_array($sql1);
			 $kode1 = $data1['kode1'] + 1;
		}else{
			 $kode1 = 1;
		}
		//mulai bikin kode
		 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
		 $kodekelas = "KLS-".$bikin_kode1;
		
		$query1 = mysqli_query($koneksi,"INSERT into kelas(KodeTimbangan,KodeKelas,NamaKelas) VALUES ('$KodeTimbangan','$kodekelas','$NamaKelas')");
				
				
		if ($query1){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Master Data Kelas Timbangan dengan nama '.$NamaKelas, $login_id, $kodekelas, 'Master Data');
			echo '<script language="javascript">document.location="MasterUTTP.php?id='.base64_encode($KodeTimbangan).'";</script>';
		}else{
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Simpan Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "MasterUTTP.php?id='.base64_encode($KodeTimbangan).'";
				  });
				  </script>';
		}
	}
	
	if(isset($_POST['EditItem'])){
		
		$query = mysqli_query($koneksi,"UPDATE kelas SET NamaKelas='$NamaKelas' WHERE KodeTimbangan='$KodeTimbangan' and KodeKelas='$KodeKelas' ");
		
			if($query){
				InsertLog($koneksi, 'Edit Data', 'Mengubah Master Data Kelas Timbangan dengan nama '.$NamaKelas, $login_id, $KodeKelas, 'Master Data');
				
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Berhasil!",
						text: " ",
						type: "success"
					  },
					  function () {
						window.location.href = "MasterUTTP.php?id='.base64_encode($KodeTimbangan).'";
					  });
					  </script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Edit Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterUTTP.php?id='.base64_encode($KodeTimbangan).'";
					  });
					  </script>';
			}
	}
	
	
	?>
  </body>
</html>