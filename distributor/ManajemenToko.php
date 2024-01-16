<?php
include 'akses.php';
$Page = 'ManajemenToko';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Data Toko';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT NamaPerson,AlamatLengkapPerson,NoHP,IsVerified,IDPerson FROM mstperson WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
	@$RowData = mysqli_fetch_assoc($Edit);
	
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
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
              <h2 class="no-margin-bottom">Manajemen Toko</h2>
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
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Toko <?=$login_nama?></h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-warning" type="submit">Cari</button>
												<!--<a href="../library/html2pdf/cetak/MasterTimbangan.php" title='Print Data' target="_BLANK"><span class="btn btn-secondary">Cetak Data UTTP</span></a>-->
											</span>
										</div>
									</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama Toko</th>
									  <th>Alamat Lengkap</th>
									  <th>No Telepon</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$keyword = $_REQUEST['keyword'];
										$reload = "ManajemenToko.php?pagination=true&keyword=$keyword";
										$sql =  "SELECT NamaPerson,AlamatLengkapPerson,NoHP,IDPerson FROM mstperson  WHERE 	ID_Distributor='$login_id' and IsVerified=b'1'  ";
										
										if(@$_REQUEST['keyword']!=null){
												$sql .= " AND ( NamaPerson LIKE '%".$_REQUEST['keyword']."%' ) ";
											}
										$sql .=" ORDER BY NamaPerson ASC";
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
												<?php echo $data ['NamaPerson'];?>
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson'];?>
											</td>
											<td>
												<?php echo $data ['NoHP'];?>
											</td>
											<td width="150px" align="center">	
												<?php
													
													echo '<a href="ManajemenToko.php?id='.base64_encode($data['IDPerson']).'" title="Edit"><i class="btn btn-warning btn-sm"><span class="fa fa-edit"></span></i></a>';
												
													echo ' <a href="ManajemenToko.php?id='.base64_encode($data['IDPerson']).'&aksi='.base64_encode('Hapus').'&view=1" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>';
													
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
								  <label>Nama Toko</label>
								  <div>
									<input type="hidden" class="form-control"  value="<?php echo @$login_id;?>" name="ID_Distributor">
									<input type="hidden" class="form-control"  value="<?php echo @$RowData['IDPerson'];?>" name="IDPerson">
									<input type="text" class="form-control" maxlength="255" placeholder="Nama Toko" value="<?php echo @$RowData['NamaPerson'];?>" name="NamaPerson" required>
								  </div>
								</div>
								<div class="form-group">
								  <label>Alamat Lengkap</label>
								  <div>
									<input type="text" class="form-control" maxlength="255" placeholder="Alamat Lengkap Toko" value="<?php echo @$RowData['AlamatLengkapPerson'];?>" name="AlamatLengkapPerson">
								  </div>
								</div>
								<div class="form-group">
								  <label>No Telepon</label>
								  <div>
									<input type="number" class="form-control" maxlength="16" placeholder="No Telepon" value="<?php echo @$RowData['NoHP'];?>" name="NoHP">
								  </div>
								</div>
								<?php
									if(@$_GET['id']==null){
										echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
									}else{
										echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
									}
								?>
								<a href="ManajemenToko.php"><span class="btn btn-warning">Kembali</span></a>
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
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "ManajemenToko.php";
				}
			else{
			alert("Terima Kasih !");	return false; 	
				}
			}
	</script>
	<?php
		@$NamaPerson 			=htmlspecialchars($_POST['NamaPerson']);
		@$AlamatLengkapPerson 	=htmlspecialchars($_POST['AlamatLengkapPerson']);
		@$NoHP	 				=htmlspecialchars($_POST['NoHP']);
		@$IDPerson			 	=htmlspecialchars($_POST['IDPerson']);
		@$ID_Distributor		=htmlspecialchars($_POST['ID_Distributor']);
		
	if(isset($_POST['Simpan'])){	
		// buat kode data timbangan
		$sql1 	 = mysqli_query($koneksi,'SELECT RIGHT(IDPerson,7) AS kode1 FROM mstperson ORDER BY IDPerson DESC LIMIT 1');  
		$num1	 = mysqli_num_rows($sql1);
		if($num1 <> 0){
			$data1 = mysqli_fetch_array($sql1);
			$kode1 = $data1['kode1'] + 1;
		}else{
			$kode1 = 1;
		}
		//mulai bikin kode
		 $bikin_kode1 = str_pad($kode1, 7, "0", STR_PAD_LEFT);
		 $kode_jadi2 = "PRS-".$Tahun."-".$bikin_kode1;	
			
			
			$query = mysqli_query($koneksi,"INSERT into mstperson(IDPerson,NamaPerson,AlamatLengkapPerson,NoHP,IsVerified,JenisPerson,ID_Distributor) 
			VALUES ('$kode_jadi2','$NamaPerson','$AlamatLengkapPerson','$NoHP',b'1','Pengecer Pupuk','$ID_Distributor')");
			if($query){
				echo '<script language="javascript">document.location="ManajemenToko.php";</script>';
			}else{
				echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "ManajemenToko.php";
					  });
					  </script>';
			}
	}
	if(isset($_POST['SimpanEdit'])){
		
		//query update
		$query = mysqli_query($koneksi,"UPDATE mstperson SET NamaPerson='$NamaPerson',AlamatLengkapPerson='$AlamatLengkapPerson',NoHP='$NoHP' WHERE IDPerson='$IDPerson'");
		if($query){
			echo '<script type="text/javascript">
				  sweetAlert({
					title: "Edit Data Berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
					window.location.href = "ManajemenToko.php";
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
					window.location.href = "ManajemenToko.php";
				  });
				  </script>';
		}
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		mysqli_query($koneksi,"UPDATE mstperson set IsVerified=b'0' WHERE IDPerson='".htmlspecialchars(base64_decode($_GET['id']))."'");
		echo '<script language="javascript">document.location="ManajemenToko.php"; </script>';
			
	}
	?>
  </body>
</html>