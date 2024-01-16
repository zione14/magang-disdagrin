<?php
include 'akses.php';
$fitur_id = 9;
include '../library/lock-menu.php'; 
include '../library/tgl-indo.php';
$Page = 'Laporan';
$SubPage = 'LapSebaranTimbangan';

if(@$_REQUEST['kode']!=null){
	@$bagi_uraian 		= explode("#", $_REQUEST['kode']);
	@$Timbangan 		= $bagi_uraian[0];
	@$Kelas 			= $bagi_uraian[1];
	@$Ukuran 			= $bagi_uraian[2];
}

$gl = '[';

$sql =  "SELECT b.AlamatLokasi,a.KoorLat,a.KoorLong,a.NamaTimbangan,a.IDTimbangan,e.NamaKabupaten,c.NamaKecamatan,d.NamaDesa,g.NoTransaksi,h.NamaPerson,i.MasaTera,MAX(g.TglTransaksi) as Tanggal FROM timbanganperson a JOIN lokasimilikperson b ON (a.KodeLokasi,a.IDPerson) = (b.KodeLokasi,b.IDPerson) JOIN mstkab e ON b.KodeKab=e.KodeKab JOIN mstkec c ON b.KodeKec = c.KodeKec JOIN mstdesa d ON b.KodeDesa=d.KodeDesa left join trtimbanganitem f on (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(f.IDTimbangan,f.KodeLokasi,f.IDPerson)  left join tractiontimbangan g on f.NoTransaksi=g.NoTransaksi join mstperson h on h.IDPerson=a.IDPerson join msttimbangan i on a.KodeTimbangan=i.KodeTimbangan WHERE b.KodeKab='".KodeKab($koneksi)."' AND  h.UserName !='dinas' AND  a.StatusUTTP='Aktif'";

if(@$_REQUEST['keyword']!=null){
	$sql .= " AND ( h.NamaPerson LIKE '%".$_REQUEST['keyword']."%' || h.PJPerson LIKE '%".$_REQUEST['keyword']."%' || a.IDTimbangan LIKE '%".$_REQUEST['keyword']."%' ) ";
}

if(@$_REQUEST['kode']!=null){
	$sql .= "AND a.KodeTimbangan='$Timbangan' AND a.KodeKelas='$Kelas' AND a.KodeUkuran='$Ukuran' ";
}

$sql .="  GROUP by a.IDTimbangan DESC";
$result = mysqli_query($koneksi,$sql);	

while ($daftar = mysqli_fetch_array($result)) {
	$HariIni = date("Y-m-d");
	$tgl1 = $daftar['Tanggal'];
	if ($tgl1 === null OR $tgl1 === '' ){
		$tanggal = 'Belum Tera Timbangan';
		$icon = '../images/Assets/hitam.png';
		
		
	}else{
		
		$period = $daftar['MasaTera'];
		$TeraUlang = date('Y-m-d', strtotime('+'.$period.' year', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 1 tahun
		$tanggal = TanggalIndo($TeraUlang);
		//satu bulan sebelum jatuh tempo
		$Tgl1Bulan = date('Y-m-d', strtotime('-1 month', strtotime($TeraUlang)));
		
		//icon timbangan
		if(strtotime($HariIni) >= strtotime($Tgl1Bulan) && strtotime($HariIni) < strtotime($TeraUlang)){ 	
			$icon = '../images/Assets/kuning.png';
		}elseif(strtotime($HariIni) > strtotime($TeraUlang)){
			$icon = '../images/Assets/merah.png';
		}elseif(strtotime($HariIni) < strtotime($Tgl1Bulan)){
			$icon = '../images/Assets/hijau.png';
	
		}
	}
	
	
	$gl .= "['{$daftar['AlamatLokasi']}, {$daftar['NamaDesa']},<br>, {$daftar['NamaKecamatan']},  {$daftar['NamaKabupaten']}', '{$daftar['KoorLat']}', '{$daftar['KoorLong']}', '{$tanggal}', '{$daftar['NamaTimbangan']}', '$icon', '{$daftar['NamaPerson']}' ],";
}
$gl .= ']';





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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBqH_ctOCgwu5RLMrH6VdhCBLorpJXaDk&libraries=places"></script>
	
	<script type="text/javascript" src="../komponen/js/markerclusterer_compiled.js"></script>
	<script type="text/javascript">
 window.onload = function() {
	 var lokasi = <?php echo $gl;?>;
	 
  // set info window
  var infowindow = new google.maps.InfoWindow();

  // set position
  var pos = new google.maps.LatLng(-7.533028,112.239711);

  // set option untuk peta
  var option = {
   center: pos,
   zoom: 14,
   mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
 												

  // set peta
  var map = new google.maps.Map(document.getElementById('maps'), option);
  infowindow = new google.maps.InfoWindow({
   content: 'loading...'
  });

  // set bounds
  var bounds = new google.maps.LatLngBounds();
  var markers = [];
  var content = $('#infoWindowTemplate').clone();

  // loop lokasi and push to markers
  for (var i = 0; i < lokasi.length; i++) {
   var pos = new google.maps.LatLng(lokasi[i][1], lokasi[i][2]);
   var marker = new google.maps.Marker({
    position: pos,
    map: map,
	icon: lokasi[i][5],
    title: lokasi[i][4],
    zIndex: lokasi[i][4],
    html: lokasi[i][0],
    text: lokasi[i][6],
    tgl: lokasi[i][3]
   });
   
   
   
   bounds.extend(pos);
   
   google.maps.event.addListener(marker, 'click', function () {
    infowindow.setContent('<b>'+ this.title +'</b><br/>'+ this.html+'<br><b>'+this.text+'</b>&nbsp;&nbsp;(Tera Ulang : &nbsp;&nbsp;<i><u>'+this.tgl+'</u></i>&nbsp;)');
    infowindow.open(map, this);
   });
   markers.push(marker);
  }
  map.fitBounds(bounds);

  // set marker cluster
  var markerCluster = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

 }
</script>
	
		<style>
	th {
		text-align: center;
	}
	
	.hidden {
	  display: none;
	  visibility: hidden;
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
	/* basic positioning */
	.legend { list-style: none; }
	.legend li { float: left; margin-right: 10px; }
	.legend span { border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px; }
	/* your colors */
	.legend .superawesome { background-color: green; }
	.legend .awesome { background-color: #ebc634; }
	.legend .kindaawesome { background-color: red; }
	.legend .blacky { background-color: black; }
	
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
              <h2 class="no-margin-bottom">Loporan Sebaran UTTP</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Lokasi Sebaran UTTP</h3>
					</div>
					<div class="card-body">	
						<div class="col-lg-12">
							<form method="post" action="">
							<div class="col-lg-12 form-group input-group">
								
								<input type="text" name="keyword" class="form-control" placeholder="Nama atau ID Timbangan..." value="<?php echo @$_REQUEST['keyword']; ?>">&nbsp;&nbsp;
								<select  name="kode" class="form-control">
									<?php
										echo "<option value=''>--- Timbangan ---</option>";
										$menu = mysqli_query($koneksi,"SELECT a.KodeTimbangan,b.KodeKelas,c.KodeUkuran,a.NamaTimbangan,b.NamaKelas,c.NamaUkuran FROM msttimbangan a LEFT JOIN kelas b on a.KodeTimbangan=b.KodeTimbangan Join detilukuran c on (b.KodeTimbangan,b.KodeKelas)=(c.KodeTimbangan,c.KodeKelas) ORDER by a.NamaTimbangan ASC");
										while($kode = mysqli_fetch_array($menu)){
												if($kode['KodeTimbangan'] === $Timbangan){
													echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" selected='selected'>".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
												}else{
													echo "<option value=\"".$kode['KodeTimbangan']."#".$kode['KodeKelas']."#".$kode['KodeUkuran']."\" >".$kode['NamaTimbangan']." ".$kode['NamaKelas']." ".$kode['NamaUkuran']."</option>\n";
												}
										}
									?>
								</select>
								<span class="input-group-btn">
									<button class="btn btn-large btn-info" type="submit">Cari</button>
								 </span>
							</div>
						</form>
						</div>
						<div class="col-lg-12">
							<div id="maps" style="width: 930px; height: 500px; background-color: #EEE"></div><hr>
							
							<ul class="legend">
								<li>Keterangan : </li><br><br>
								<li><span class="superawesome"></span>Warna Hijau Waktu Tera Masih Lama </li><br>
								<li><span class="awesome"></span> Warna Kuning Waktu Tera Bulan Ini </li><br>
								<li><span class="kindaawesome"></span> Warna Merah Waktu Tera Timbangan Sudah Terlewat</li><br>
								<li><span class="blacky"></span> Warna Hitam Belum Melakukan Tera </li>
							</ul>
							<br>
							<div id="infoWindowTemplate" class="hidden">
								<div class="panel panel-primary" style="box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; margin: 0 0 0 10px;border-radius:5px !important; border: none;background: #fff; ">
									<div class="panel-heading" style="background:#4287f5;    border-top-left-radius: 5px;border-top-right-radius: 5px;">
										<h4 class="panel-title" style="color: #fff;font-size: 1.2rem;padding: 10px;margin-bottom: 0;"></h4>
									</div>
									<div class="panel-body" style="padding:5px;border-radius: 4px;">
										<table class="table" style="margin: 0px;">
											<tbody>
												<tr>
													<td style="border:none;">
														<div style="border-radius: 50%;background:#4287f5;padding: 9px;color: #fff;width: 30px;height: 30px;">
														<i class="fa fa-map-marker" style="font-size:1.9em;color:#fff;"></i>
														</div>
													</td>
													<td style="border:none;">
														<span class="address" style="font-size: 14px;line-height: 25px;"></span>
													</td>
												</tr>
												<tr>
													<td style="border:none;">
														<div style="border-radius: 50%;background: #4287f5;padding-top: 6px; padding-left: 2px;color: #fff;width: 30px;height: 30px;">
														&nbsp;<i class="fa fa-balance-scale" style="font-size:1.7em;color:#fff;"></i>
														</div>
													</td>
													<td style="border:none;">
														Tera Ulang
														<span class="phone" style="font-size: 14px;line-height: 25px;"></span>
													</td>
												</tr>
											</tbody>
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
	<script type="text/javascript">
		$(document).ready(function() {
			$('#date1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			
		});
	</script>
	<script>
	  $(document).ready(function(){
		 initAutocomplete(); 
	  });
  </script>
  </body>
</html>