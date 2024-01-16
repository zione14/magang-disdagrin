<?php
include 'akses.php';
@$fitur_id = 4;
include '../library/lock-menu.php';

if (base64_decode(@$_GET['user']) != 'PRS-2019-0000000'){
	$Page = 'MasterPerson';
}else{
	$Page = 'TimbanganDinas';
}

// Tanggal dan Tahun
$Tahun=date('Y');
$DateTime=date('Y-m-d H:i:s');
//getdata
@$IDPerson = base64_decode($_GET['user']);
@$NamaPerson  = NamaPerson($koneksi, $IDPerson);
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
	<script src="http://maps.google.com/maps/api/js?key=AIzaSyCaX_RkNdrKRsaMYOp9PCOj11JgrZNgR6w&sensor=false"></script>
	<!-- Datatables -->
	<link rel="stylesheet" href="../library/datatables/dataTables.bootstrap.css"/>
<script>
		
    var marker;
	 var markers = [];
      function initialize() {
		  
		  $("#KodeLokasi").change(function(){
			clearMarkers();
			var IDPerson	= $("#IDPerson").val();
			var KodeLokasi = $("#KodeLokasi").val();
			$.ajax({
				url: "../library/Dropdown/ambil-lokasi.php",
				data: { KodeLokasi:KodeLokasi, IDPerson:IDPerson },
				cache: false,
				dataType : 'json',
				success: function(msg){
				addMarker(msg.lat, msg.long, '<b>Lokasi Milik User</b>');
				//$("#Coba").html(msg);
			}
		});
	  });
		  
		// Variabel untuk menyimpan informasi (desc)
		var infoWindow = new google.maps.InfoWindow;
		
		//  Variabel untuk menyimpan peta Roadmap
		var mapOptions = {
          mapTypeId: google.maps.MapTypeId.ROADMAP
        } 
		
		// Pembuatan petanya
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
              
        // Variabel untuk menyimpan batas kordinat
		var bounds = new google.maps.LatLngBounds();
		
		// Pengambilan data dari database
		
		 addMarker(-7.566119828718862, 112.22208269364012, '');
		

		// Proses membuat marker 
		function addMarker(lat, lng, info) {
			var lokasi = new google.maps.LatLng(lat, lng);
			bounds.extend(lokasi);
			 var gambar = {
				url: "../web/images/map-marker.png", // url
				scaledSize: new google.maps.Size(50, 50), // scaled size
				origin: new google.maps.Point(0,0), // origin
				anchor: new google.maps.Point(0, 0) // anchor
				
			};
			var marker = new google.maps.Marker({
				map: map,
				position: lokasi,
				icon: gambar
			}); 
			markers.push(marker);
			map.fitBounds(bounds);
			bindInfoWindow(marker, map, infoWindow, info);
		 }
		
		// Menampilkan informasi pada masing-masing marker yang diklik
        function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
        }
		
		// Sets the map on all markers in the array.
      function setMapOnAll(map) {
        for (var i = 0; i < markers.length; i++) {
          markers[i].setMap(map);
        }
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }
 
        }
      google.maps.event.addDomListener(window, 'load', initialize);
    
	
	</script>
	<style>
	th {
		text-align: center;
	}
	</style>
	
	<script type="text/javascript">
		function confirmation() {
			var answer = confirm("Apakah Anda yakin menghapus data ini ?")
			if (answer == true){
				window.location = "TimbanganUserDetil.php";
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
              <h2 class="no-margin-bottom">Detil UTTP User <?php echo $NamaPerson; ?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data UTTP</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
							<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>&nbsp;
							<?php } ?>
						</li>
						<li>
							<?php 
								if (base64_decode(@$_GET['user']) != 'PRS-2019-0000000'){ 
									echo '<a href="MasterPerson.php?v=Timbangan"><span class="btn btn-primary">Kembali</span></a>';
								}else{
									if ($cek_fitur['AddData'] =='1'){ 
										echo '<a href="LokasiUserDetil.php?user='.$_GET['user'].'" title="Lokasi Milik Dinas" ><span class="btn btn-primary">Lokasi Dinas</span></a>';
									} 
								}
								?>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Detil UTTP User</h3>
							</div>
							<div class="card-body">		
								<?php if (base64_decode(@$_GET['user']) != 'PRS-2019-0000000') { ?>
								<div class="col-lg-4 offset-lg-8">
									<form method="post" action="">
										<div class="form-group input-group">						
											<input type="text" name="keyword" class="form-control" placeholder="Nama Timbangan..." value="<?php echo @$_REQUEST['keyword']; ?>">
											<span class="input-group-btn">
												<button class="btn btn-success" type="submit">Cari</button>
											</span>
										</div>
									</form>
								</div>
								<?php } ?>
							  <div class="table-responsive">  
								<table class="table table-striped">
								  <thead>
									<tr>
									  <th>No</th>
									  <th>Nama UTTP</th>
									  <th>Jenis UTTP</th>
									  <?php 
										if (base64_decode($_GET['user']) != 'PRS-2019-0000000'){
										  echo '<th>Tarif Retribusi</th>';
										}
									  ?>									  
									  <th>Alamat</th>
									  <th>Status</th>
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
											$reload = "TimbanganUserDetil.php?pagination=true&keyword=$keyword";
											$sql =  "SELECT b.IDTimbangan,c.JenisTimbangan,c.NamaTimbangan,b.NamaTimbangan as RealName,a.NamaPerson,a.IDPerson,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,f.NamaLokasi,f.AlamatLokasi,b.StatusUTTP FROM mstperson a join timbanganperson b on a.IDPerson=b.IDPerson join msttimbangan c on b.KodeTimbangan=c.KodeTimbangan join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) join lokasimilikperson f on (f.KodeLokasi,f.IDPerson)=(b.KodeLokasi,b.IDPerson) WHERE c.NamaTimbangan LIKE '%$keyword%' and a.IsVerified=b'1' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='$IDPerson' GROUP BY b.IDTimbangan ASC";
											$result = mysqli_query($koneksi,$sql);
										}else{
										//jika tidak ada pencarian pakai ini
											$reload = "TimbanganUserDetil.php?pagination=true";
											$sql =  "SELECT b.IDTimbangan,c.JenisTimbangan,c.NamaTimbangan,b.NamaTimbangan as RealName,a.NamaPerson,a.IDPerson,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,f.NamaLokasi,f.AlamatLokasi,b.StatusUTTP FROM mstperson a join timbanganperson b on a.IDPerson=b.IDPerson join msttimbangan c on b.KodeTimbangan=c.KodeTimbangan join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) join lokasimilikperson f on (f.KodeLokasi,f.IDPerson)=(b.KodeLokasi,b.IDPerson) WHERE  a.IsVerified=b'1' and a.JenisPerson LIKE '%Timbangan%' and a.IDPerson='$IDPerson'  GROUP BY b.IDTimbangan ASC";
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
											<td width="50px"><?php echo ++$no_urut;?></td>
											<td><strong><?php echo $data ['RealName']; ?></strong></td>
											<td><?php echo $data['NamaTimbangan']." ".$data['NamaKelas']." ".$data['NamaUkuran']; ?></td>
												<?php if (base64_decode($_GET['user']) != 'PRS-2019-0000000'){ ?>
											<td><?php echo "Dikantor : Rp ".number_format($data ['RetribusiDikantor'])."<br>"; ?>
												<?php echo "Dilokasi : Rp ".number_format($data ['RetribusiDiLokasi']).""; ?></td>
												<?php } ?>
											<td><?php echo $data['NamaLokasi']."<br> ".$data['AlamatLokasi']; ?></td>
											<td><?php echo $data['StatusUTTP'] == 'Aktif' ? '<span class="text-success">AKTIF</span>' : '<span class="text-danger">NONAKTIF</span>'; ?></td>
											<td width="100px" align="center">
												<?php if ($cek_fitur['EditData'] =='1' and $data['StatusUTTP'] == 'Aktif'){ ?>
												<!-- Tombol Edit Data -->
													<a href="TimbanganUserEdit.php?id=<?php echo base64_encode($data['IDTimbangan']);?>" title='Edit'><i class='btn btn-warning btn-sm' disabled><span class='fa fa-edit'></span></i></a>
												<?php } ?>
												<?php if ($cek_fitur['DeleteData'] =='1' and $data['StatusUTTP'] == 'Aktif'){ ?>
												<!-- Tombol Edit Data -->												
													<a href="TimbanganUserDetil.php?tr=<?php echo base64_encode($data['IDTimbangan']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($data['NamaPerson']);?>&tm=<?php echo base64_encode($data['NamaTimbangan']);?>&prs=<?php echo base64_encode($data['IDPerson']);?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm" ><span class="fa fa-trash"></span></i></a>
												<?php } ?>

												<?php if ($cek_fitur['EditData'] =='1' and base64_decode($_GET['user']) == 'PRS-2019-0000000'){ ?>
												<!-- Tombol Edit Data -->
													<a href="TimbanganUserView.php?id=<?php echo base64_encode($data['IDTimbangan']);?>" title='View Riwayat Tera Timbangan'><i class='btn btn-info btn-sm' disabled><span class="fa fa-refresh"></span></i></a>
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
							  <h3 class="h4">Tambah Data UTTP</h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-6">
									   <form action="SimpanData/UploadFotoTimbangan.php"  method="post" enctype="multipart/form-data">
										<div class="form-group-material">
										  <label>Nama UTTP</label>
										  <input type="text" name="NamaTimbangan" class="form-control" placeholder="Nama Timbangan" value="<?php echo @$RowData['NamaTimbangan'];?>" maxlength="150">
										</div>
										<div class="form-group-material">
										  <label>Jenis UTTP</label>
										  <select id="KodeTimbangan" name="KodeTimbangan" class="form-control" >	
												<?php
													echo "<option value=''>--- Timbangan ---</option>";
													$menu = mysqli_query($koneksi,"SELECT KodeTimbangan,NamaTimbangan from msttimbangan ORDER by NamaTimbangan ASC");
													while($kode = mysqli_fetch_array($menu)){
														if($kode['KodeTimbangan'] === $RowData['KodeTimbangan']){
															echo "<option value=\"".$kode['KodeTimbangan']."\" selected='selected'>".$kode['NamaTimbangan']."</option>\n";
														}else{
															echo "<option value=\"".$kode['KodeTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
														}
													}
												?>
											</select>
											
											
										</div>
										<div class="form-group">
											<label>Nama Kelas </label>
											<select id="KodeKelas" name="KodeKelas" class="form-control" required>
											<option value=''>--- Nama Kelas ---</option>
											</select>
										</div>
										<div class="form-group">
											<label>Nama Ukuran</label>
											<select id="KodeUkuran" class="form-control" name="KodeUkuran" required>
											<option value=''>--- Nama Ukuran ---</option>
											</select>
										</div>
										<div class="form-group-material">
										  <label>Nama Lokasi</label>
										   <select id="KodeLokasi" name="KodeLokasi" class="form-control" required>	
												<?php
													echo "<option value=''>--- Pilih Lokasi ---</option>";
													$lokasi = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson where IDPerson='$IDPerson' ORDER BY NamaLokasi");
													while($res = mysqli_fetch_array($lokasi)){
														if($res['KodeLokasi'] === @$RowData['KodeLokasi']){
															echo "<option value=\"".$res['KodeLokasi']."\" selected='selected'>".$res['NamaLokasi']."</option>\n";
														}else{
															$Lat =  $res['KoorLat'];
															echo "<option value=\"".$res['KodeLokasi']."\" >".$res['NamaLokasi']."</option>\n";
														}
													}
												?>
											</select>
										</div>
										
										<div class="form-group-material">
										  <label>Ukuran Real UTTP </label>
										  <input type="number" name="UkuranRealTimbangan" class="form-control"  placeholder="Kapasitas Timbangan" value="<?php echo @$RowData['UkuranRealTimbangan'];?>" maxlength="150">
										</div>
										<div class="form-group">
										  <label>Satuan</label>
										  <input type="text" name="Satuan" class="form-control" placeholder="Satuan" value="<?php echo @$RowData['Satuan'];?>">
										</div>
										<div class="form-group">
										  <label>Merk</label>
										  <input type="text" name="Merk" class="form-control" placeholder="Merk">
										</div>
										<div class="form-group">
										  <label>Type</label>
										  <input type="text" name="Type" class="form-control" placeholder="Type">
										</div>
										<div class="form-group">
										  <label>Nomor Seri</label>
										  <input type="text" name="Seri" class="form-control" placeholder="Seri">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
										  <label>Keterangan</label>
										  <input type="text" name="Jumlah" class="form-control" placeholder="Keterangan">
										</div>
										<div class="form-group">
										  <label>Timbangan Buatan</label>
										  <input type="text" name="Buatan" class="form-control" placeholder="Buatan">
										</div>
										<div class="form-group">
										  <label>Medium</label>
										  <input type="text" name="Medium" class="form-control" placeholder="Medium">
										</div>
										<div class="form-group-material">
											<div class="row">
												<div class="col-md-11">
													<div class="panel panel-default">
														<div class="panel-heading"></div>
														<div class="panel-body">
															<div id="map-canvas" style="width: 450px; height: 350px;"></div>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div id="Coba"></div>
										<div class="form-group-material">
											<label>Upload Gambar Timbangan type .jpg/.png 2MB (Minimal 1)</label>
											<div class="form-group">
												<br>
												<input type="file" name="filefoto1" id="filefoto1" style="width: 100%;" >
											</div>
											<div class="form-group">
												<input type="file" name="filefoto2" id="filefoto2" style="width: 100%;" >
											</div>
											<div class="form-group">
												<input type="file" name="filefoto3" id="filefoto3" style="width: 100%;" >
											</div>
											<div class="form-group">
												<input type="file" name="filefoto4" id="filefoto4" style="width: 100%;" >
											</div>
											<img src="../web/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
												<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
											<div class="text-center">
												<div align="center" id="output"></div>
											</div>
										</div>
										<div class="text-right">
										<?php
											echo ' <input type="hidden" name="KodeKab" value="'.KodeKab($koneksi).'">';
											echo ' <input type="hidden" id="IDPerson" name="IDPerson" value="'.$IDPerson.'">';
											echo ' <input type="hidden" name="login_id" value="'.$login_id.'">';
											echo '<button type="submit"  id="submit-btn"  class="btn btn-primary" name="Simpan">Simpan</button>';
										?>
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

	

	var htmlobjek;
	$(document).ready(function(){
	  //apabila terjadi event onchange terhadap object <select id=nama_produk>
	 $("#KodeTimbangan").change(function(){
		var KodeTimbangan = $("#KodeTimbangan").val();
		$.ajax({
			url: "../library/Dropdown/ambil-kelas.php",
			data: "KodeTimbangan="+KodeTimbangan,
			cache: false,
			success: function(msg){
				$("#KodeKelas").html(msg);
				
			}
		});
		
		$.ajax({
			url: "../library/Dropdown/ambil-ukurantim.php",
			data: "KodeTimbangan="+KodeTimbangan,
			cache: false,
			success: function(msg){
				$("#KodeUkuran").html(msg);
				
			}
		});
		
	  });
	  
	  $("#KodeKelas").change(function(){
		  
		var KodeKelas = $("#KodeKelas").val();
		console.log(KodeKelas);
		$.ajax({
			url: "../library/Dropdown/ambil-ukuran.php",
			data: "KodeKelas="+KodeKelas,
			cache: false,
			success: function(msg){
				$("#KodeUkuran").html(msg);
			}
		});
	  });
	  
	});
	</script>
		
	<script>
	  function goBack() {
		  window.history.back();
	  }
	</script>
	
	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript" src="../komponen/js/jquery.form.min.js"></script>
	
		
	<?php
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$QueryCekData = @mysqli_query($koneksi, "SELECT IDTimbangan FROM trtimbanganitem where IDTimbangan='".base64_decode($_GET['tr'])."'"); 
		$numCek = @mysqli_num_rows($QueryCekData); 
		// echo $numCek;
		if($numCek > 0){
			echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "TimbanganUserDetil.php?user='.$_GET['prs'].'";
						  });
						  </script>';
		}else{
			//hapus transaksi timbangan user
			$HapusGambar = mysqli_query($koneksi,"SELECT FotoTimbangan1,FotoTimbangan2,FotoTimbangan3,FotoTimbangan4,QRCode FROM timbanganperson WHERE IDTimbangan='".base64_decode($_GET['tr'])."'");
			$data=mysqli_fetch_array($HapusGambar);
			
			$query = mysqli_query($koneksi,"delete from timbanganperson WHERE IDTimbangan='".base64_decode($_GET['tr'])."'");
			if($query){
				//hapus gambar terlebih dahulu
				unlink("../images/TimbanganQR/".$data['QRCode']."");
				unlink("../images/Timbangan/".$data['FotoTimbangan1']."");
				unlink("../images/Timbangan/thumb_".$data['FotoTimbangan1']."");
				unlink("../images/Timbangan/".$data['FotoTimbangan2']."");
				unlink("../images/Timbangan/thumb_".$data['FotoTimbangan2']."");
				unlink("../images/Timbangan/".$data['FotoTimbangan3']."");
				unlink("../images/Timbangan/thumb_".$data['FotoTimbangan3']."");
				unlink("../images/Timbangan/".$data['FotoTimbangan4']."");
				unlink("../images/Timbangan/thumb_".$data['FotoTimbangan4']."");
				
				InsertLog($koneksi, 'Hapus Data', 'Menghapus Timbangan User atas nama '.base64_decode(@$_GET['nm']).' dengan nama timbangan '.base64_decode(@$_GET['tm']), $login_id, base64_decode(@$_GET['tr']), 'Timbangan User');
					
				echo '<script language="javascript">document.location="TimbanganUserDetil.php?user='.$_GET['prs'].'"; </script>';
			}else{
				echo '<script type="text/javascript">
						  sweetAlert({
							title: "Hapus Data Gagal!",
							text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
							type: "error"
						  },
						  function () {
							window.location.href = "TimbanganUserDetil.php?user='.$_GET['prs'].'";
						  });
						  </script>';
			}
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