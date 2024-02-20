<?php
include 'akses.php';
@$fitur_id = 38;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
$Page = 'Laporan';
$SubPage = 'LapUTTPUser';

?>
<!DOCTYPE html>
<html>
  <head>
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
	<style>
	.table thead th {
		
		text-align: center;
		
		border: 2px solid #dee2e6;
	}
	td {
		border: 2px solid #dee2e6;
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
              <h2 class="no-margin-bottom">Laporan Alat UTTP User</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="col-lg-12">
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Laporan UTTP User </h3>
							  <div class="offset-lg-8">
							  </div>
							</div>
							<div class="card-body">
								<div class="col-lg-12">
									<form method="post" action="LapUTTPUser.php?KodePasar=<?php echo @$_REQUEST['KodePasar']; ?>&KodeKec=<?php @$_REQUEST['KodeKec'];?>">
									<div class="col-lg-12 input-group">
										<select id="KodeKec" name="KodeKec" class="form-control">	
											<?php
												echo "<option value=''>--- Semua Kecamatan ---</option>";
												$menu = mysqli_query($koneksi,"SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeKec']==$_REQUEST['KodeKec']){
														echo "<option value=\"".$kode['KodeKec']."\" selected>".$kode['NamaKecamatan']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
													}
												}
											?>
										</select>&nbsp;&nbsp;

										<select id="KodePasar" name="KodePasar" class="form-control">	
											<?php
												echo "<option value=''>--- Semua Pasar ---</option>";
												$menu = mysqli_query($koneksi,"SELECT KodePasar,NamaPasar FROM mstpasar ORDER BY NamaPasar");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodePasar']==$_REQUEST['KodePasar']){
														echo "<option value=\"".$kode['KodePasar']."\" selected>".$kode['NamaPasar']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodePasar']."\" >".$kode['NamaPasar']."</option>\n";
													}
												}
											?>
										</select>
										
										
									</div>
									<br><div class="col-lg-10 offset-lg-2 form-group input-group">
										<input type="text" name="keyword" class="form-control" placeholder="Nama User atau IDTimbangan..." value="<?php echo @$_REQUEST['keyword']; ?>">&nbsp;&nbsp;
										
										<span class="input-group-btn">
											<button class="btn btn-large btn-warning" type="submit">Cari</button>
											
											<a href="../library/Export/LapRekapUTTP.php?kec=<?php echo @$_REQUEST['KodeKec']; ?>&psr=<?php echo @$_REQUEST['KodePasar']; ?>"><span class="btn btn-secondary">Export Laporan</span></a>
										</span>
									</div>
								</form>
								</div>
							  <div class="table-responsive">  
								<table class="table table">
								  <thead>
									<tr>
									  <!-- <th align="center" rowspan="2">No</th> -->
									  <th align="center" rowspan="2">Nama Pemilik</th>
									  <th align="center" rowspan="2">Alamat UTTP</th>
									  <th align="center" colspan="2">Jenis UTTP</th>
									  <th align="center" rowspan="2">Tahun Masa Tera</th>
									  <th align="center" colspan="2">Nilai Retribusi</th>
									</tr>
									<tr height="15">
										<th align="center" >ID UTTP</th>
										<th align="center" >Nama</th>
										<th align="center" >Lokasi</th>
										<th align="center" >Kantor</th>
									</tr>
									
								  </thead>
								  		<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$keyword  = $_REQUEST['keyword'];
										@$kec 	= $_REQUEST['KodeKec'];
										@$pasar  = @$_REQUEST['KodePasar'];
										$reload = "LapUTTPUser.php?pagination=true&keyword=$keyword&KodeKec=$kec&KodePasar=$pasar";
										$q = "SELECT a.NamaPerson,a.AlamatLengkapPerson,a.NIK,b.KodeLokasi,b.NamaLokasi,b.AlamatLokasi,d.NamaTimbangan,e.NamaKelas,f.NamaUkuran,f.RetribusiDikantor,f.RetribusiDiLokasi,c.IDTimbangan, ADDDATE(h.TglTera, INTERVAL d.MasaTera YEAR) as masa,b.IDPerson FROM mstperson a join lokasimilikperson b on (a.IDPerson=b.IDPerson) join timbanganperson c on (b.IDPerson,b.KodeLokasi)=(c.IDPerson,c.KodeLokasi)
										join msttimbangan d on c.KodeTimbangan=d.KodeTimbangan 
										join kelas e on (c.KodeTimbangan,c.KodeKelas)=(e.KodeTimbangan,e.KodeKelas) 
										join detilukuran f on (c.KodeTimbangan,c.KodeKelas,c.KodeUkuran)=(f.KodeTimbangan,f.KodeKelas,f.KodeUkuran) 
										left join trtimbanganitem g on c.IDTimbangan=g.IDTimbangan 
										left join tractiontimbangan h on h.NoTransaksi=g.NoTransaksi  
										where a.UserName !='dinas' and c.StatusUTTP='Aktif'";

										if(@$_REQUEST['KodeKec'] !=null){
											$q .= " AND b.KodeKec='".$_REQUEST['KodeKec']."' ";
										}
										if(@$_REQUEST['KodePasar'] !=null){
											$q .= " AND b.KodePasar='".$_REQUEST['KodePasar']."' ";
										}

										if(@$_REQUEST['keyword']!=null){
											$q .= " AND (a.NamaPerson LIKE '%".htmlspecialchars($_REQUEST['keyword'])."%' OR c.IDTimbangan LIKE '%".htmlspecialchars($_REQUEST['keyword'])."%') ";
										}

										$q .=" GROUP by c.IDTimbangan order by a.IDPerson ASC";
										$oke = $koneksi->prepare($q);
										$oke->execute();
										$result = $oke->get_result($koneksi,$q);
										
										$no_urut=0;
										//pagination config start
										$rpp = 50; // jumlah record per halaman
										$page = intval(@$_GET["page"]);
										if($page<=0) $page = 1;  
										@$tcount = mysqli_num_rows($result);
										$tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
										$count = 0;
										$i = ($page-1)*$rpp;
										$no_urut = ($page-1)*$rpp;
										//pagination config end				
									?>
									<tbody id="table_body">
										<?php
										if($tcount == null OR $tcount === 0){
											echo '<tr class="odd gradeX"><td colspan="9" align="center"><strong>Tidak ada data</strong></td></tr>';
										} else {
											$idperson = '';
											$kodelokasi = '';
											$idtimangan = '';
											while(($count<$rpp) && ($i<$tcount)) {
												mysqli_data_seek($result,$i);
												@$data = mysqli_fetch_array($result);
												echo '<tr class="odd gradeX">';

												if(@$data['IDPerson'] !== $idperson){ 
													$no_urut = $no_urut + 1 ;
													// echo '<td  width="50px">'.$no_urut.'</td>';
													echo '<td>'.$data['NamaPerson'].'</td>';
													echo '<td>'.$data['NamaLokasi'].'</td>';
													echo '<td>'.$data['IDTimbangan'].'</td>';
													echo '<td>'.$data['NamaTimbangan'].'</td>';
													echo '<td>'.@TanggalIndo($data['masa']).'</td>';
													echo '<td>'.$data['RetribusiDikantor'].'</td>';
													echo '<td>'.$data['RetribusiDiLokasi'].'</td>';
												}else{
													if(@$data['KodeLokasi'] !== $kodelokasi){
														// echo '<td width="50px"></td>';
														echo '<td></td>';
														echo '<td>'.$data['NamaLokasi'].'</td>';
														echo '<td>'.$data['IDTimbangan'].'</td>';
														echo '<td>'.$data['NamaTimbangan'].'</td>';
														echo '<td>'.@TanggalIndo($data['masa']).'</td>';
														echo '<td>'.$data['RetribusiDikantor'].'</td>';
														echo '<td>'.$data['RetribusiDiLokasi'].'</td>';
													}else{
														// echo '<td width="50px"></td>';
														echo '<td></td>';
														echo '<td></td>';
														echo '<td>'.$data['IDTimbangan'].'</td>';
														echo '<td>'.$data['NamaTimbangan'].'</td>';
														echo '<td>'.@TanggalIndo($data['masa']).'</td>';
														echo '<td>'.$data['RetribusiDikantor'].'</td>';
														echo '<td>'.$data['RetribusiDiLokasi'].'</td>';

													}
												}

												$idperson = @$data['IDPerson'];
												$kodelokasi = @$data['KodeLokasi'];
												$idtimangan = @$data['IDTimbangan'];
												$i++; 
												$count++;
												echo '</tr>';					
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
	<!-- Modal-->
	  <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
		<div role="document" class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 id="exampleModalLabel" class="modal-title">Cetak Laporan Potensi Pendapatan</h4>
			  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
			  <form method="post" target="_BLANK">
				<div class="form-group">
					<label>Pilih Kecamatan</label>
					<select id="Kode" name="KodeKec" class="form-control" >
						<?php
							echo "<option value=''>--- Semua Kecamatan ---</option>";
							$menu = mysqli_query($koneksi,"SELECT KodeKec,NamaKecamatan FROM mstkec where KodeKab='".KodeKab($koneksi)."'  ORDER BY NamaKecamatan");
							while($kode = mysqli_fetch_array($menu)){
								if($kode['KodeKec']==$_REQUEST['KodeKec']){
									echo "<option value=\"".$kode['KodeKec']."\" selected>".$kode['NamaKecamatan']."</option>\n";
								}else{
									echo "<option value=\"".$kode['KodeKec']."\" >".$kode['NamaKecamatan']."</option>\n";
								}
							}
						?>
					</select>
				</div>
				<div class="form-group">       
				  <input type="submit" name="Cetak" class="btn btn-primary" value="Cetak">
				  <input type="submit" name="Export" class="btn btn-danger" value="Export Excel">
				</div>
			  </form>
			</div>
			<div class="modal-footer">
			  <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
			</div>
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
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time2').Zebra_DatePicker({format: 'Y-m-d'});
			$('#time7').Zebra_DatePicker({format: 'Y-m-d'});
			//$('#Datetime2').Zebra_DatePicker({format: 'Y-m-d H:i', direction: 1});
		});
	</script>
	<?php 
		if(isset($_POST['Cetak'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			
			
			echo '<script language="javascript">document.location="../library/html2pdf/cetak/LapPotensiDaerah.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'";</script>';
		}

		if(isset($_POST['Export'])){
			$KodeKec  			= htmlspecialchars($_POST['KodeKec']);
			$sql 				= mysqli_query($koneksi, ("select NamaKecamatan from mstkec where KodeKec='$KodeKec'"));
			$res 				= mysqli_fetch_array($sql);
			
			
			echo '<script language="javascript">document.location="../library/Export/LapPotensiDaerah.php?kec='.base64_encode($KodeKec).'&nmk='.base64_encode($res[0]).'";</script>';
			
			
		}
		
			
	?>
	
  </body>
</html>