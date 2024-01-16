<?php
include 'akses.php';
$Mode = htmlspecialchars($_GET['v']);

$Page = 'ManajemenToko';
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');

if(@$_GET['id']==null){
	$Sebutan = 'Tambah Data';
}else{
	$Sebutan = 'Edit Data';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
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
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">

	<style>
	th {
		text-align: center;
	}
	
	#map #infowindow-content {
		display: inline;
	}

	.pac-card {
	  margin: 10px 10px 0 0;
	  border-radius: 2px 0 0 2px;
	  box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  outline: none;
	  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	  background-color: #fff;
	  font-family: Roboto;
	}

	#pac-container {
	  padding-bottom: 12px;
	  margin-right: 12px;
	}

	.pac-controls {
	  display: inline-block;
	  padding: 5px 11px;
	}

	.pac-controls label {
	  font-family: Roboto;
	  font-size: 13px;
	  font-weight: 300;
	}

	#pacinput,#pacinputpengambilan {
	  background-color: #fff;
	  font-family: Roboto;
	  font-size: 15px;
	  font-weight: 300;
	  margin: 10px 12px;
	  padding: 5px;
	  text-overflow: ellipsis;
	  width: 250px;
	}

	#pacinput:focus {
	  border-color: #4d90fe;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "MasterUser.php";
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
              <h2 class="no-margin-bottom">Manajemen Toko </h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data User</span></a>&nbsp;
						</li>
						<li>
							
							<a href="MasterPersonAdd.php?v=<?=$Mode;?>"><span class="btn btn-primary"><?php echo $Sebutan; ?></span></a>
							
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data User</h3>
							</div>
							<div class="card-body">							  
								<div class="col-lg-6 offset-lg-6">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-warning" type="submit">Cari</button>
												
												
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
									  <th>Klasifikasi User</th>
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
											$reload = "MasterPerson.php?pagination=true&keyword=$keyword&v=$Mode";
											$sql =  "SELECT * FROM mstperson WHERE NamaPerson LIKE '%$keyword%' and IsVerified=b'1' AND UserName != 'dinas' and JenisPerson LIKE '%$Mode%' and ID_Distributor='$login_id' ORDER BY NamaPerson ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "MasterPerson.php?pagination=true&v=$Mode";
											$sql =  "SELECT * FROM mstperson where IsVerified=b'1' AND UserName != 'dinas' and JenisPerson LIKE '%$Mode%' and ID_Distributor='$login_id' ORDER BY NamaPerson ASC";
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
										while(($count<$rpp) && ($i<$tcount)) {
											mysqli_data_seek($result,$i);
											@$data = mysqli_fetch_array($result);
										?>
										<tr class="odd gradeX">
											<td width="50px">
												<?php echo ++$no_urut;?> 
											</td>
											<td>
												<strong><?php echo $data ['NamaPerson']; ?></strong><br>
												<?php echo NamaPerson($koneksi, $data['PJPerson']); ?>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
											</td>
											<td align="center">
												<?php 
													@$row1 = explode("#", $data['KlasifikasiUser']);
													@$row = explode("#", $data['JenisPerson']);
													echo @$row1[0]."<br>".@$row1[1]."<br>".@$row1[2]."<br>".@$row[13]; 
												?>
											</td>
											<td width="200px" align="center">
												<!-- Tombol Edit Data -->
											
												<a href="MasterPersonAdd.php?id=<?php echo base64_encode($data['IDPerson']);?>&v=<?=$Mode?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a>
											
												<!-- Tombol Hapus Data -->												
												<a href="MasterPerson.php?id=<?php echo base64_encode($data['IDPerson']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($data['NamaPerson']);  ?>&v=<?php echo $Mode;  ?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>
												
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
	
	<?php
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		//hapus transaksi timbangan user
		$HapusGambar = mysqli_query($koneksi,"SELECT FotoKTP,GambarPerson FROM mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
		$data=mysqli_fetch_array($HapusGambar);
		
		$query = mysqli_query($koneksi,"delete from mstperson WHERE IDPerson='".base64_decode($_GET['id'])."'");
		if($query){
			unlink("../images/FotoPerson/".$data['GambarPerson']."");
			unlink("../images/FotoPerson/".$data['FotoKTP']."");
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Master User atas nama '.base64_decode(@$_GET['nm']), $login_id, base64_decode(@$_GET['id']), 'Master User');
			echo '<script language="javascript">document.location="MasterPerson.php?v='.$_GET['v'].'"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "MasterPerson.php?v='.$_GET['v'].'";
					  });
					  </script>';
		}
	}
	
	
	function NamaPerson($koneksi, $IDPerson){
		$query = "SELECT NamaPerson FROM mstperson where IDPerson='$IDPerson'";
		$conn = mysqli_query($koneksi, $query);
		$result = mysqli_fetch_array($conn);
		$NamaPerson = $result['NamaPerson'];
		
		return $NamaPerson;
	 }
	
	?>
  </body>
</html>