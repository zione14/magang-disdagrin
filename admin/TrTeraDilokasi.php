<?php
include 'akses.php';
@$fitur_id = 17;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'TrMetrologi';
$SubPage = 'TrTeraDilokasi';
$Tanggal = date('Ymd');
$TanggalNOW = date("Y-m-d");

if(@$_GET['id']!=null){
	$Sebutan = 'Data Penerimaan Timbangan';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT a.NamaPerson,b.IDPerson,b.NoTransaksi,c.IDTimbangan,b.TglTransaksi,b.NoSKRD,b.KodeLokasi,b.KeteranganTera,b.NoRefTera,b.NoRefAmbil,b.Keterangan,b.TotalRetribusi 
		FROM mstperson a 
		join tractiontimbangan b on a.IDPerson=b.IDPerson 
		left join trtimbanganitem c on b.NoTransaksi=c.NoTransaksi
		 WHERE b.NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
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
				window.location = "TrTeraDilokasi.php";
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
              <h2 class="no-margin-bottom">Tera Timbangan Di Lokasi</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Transaksi</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
								<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Entry Transaksi</span></a>&nbsp;
							<?php } ?>
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Data Tera Timbangan Dilokasi</h3>
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
									  <th>Jumlah Retribusi</th>
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										$reload = "TrTerimaTimbangan.php?pagination=true";
										$sql =  "SELECT a.NoTransaksi,b.NamaPerson,b.AlamatLengkapPerson,b.IDPerson,a.TotalRetribusi,a.TglTransaksi,a.IsDitera FROM tractiontimbangan a join mstperson b on a.IDPerson=b.IDPerson Where b.JenisPerson LIKE '%Timbangan%' AND (a.StatusTransaksi='PROSES SIDANG' OR a.StatusTransaksi='SELESAI' OR a.StatusTransaksi='PROSES SIDANG'OR a.StatusTransaksi ='PRAWAITING') AND (a.IsDibayar=b'0' OR a.IsDibayar is null) AND a.JenisTransaksi='TERA DI LOKASI' ";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND b.NamaPerson LIKE '%".$_REQUEST['keyword']."%'  ";
										}
										
										$sql .=" ORDER BY a.TglTransaksi ASC";
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
											<td>
												<strong><?php echo $data ['NamaPerson']; ?></strong><br>
												<?php echo $data['NoTransaksi']."<br>"; ?>
												<?php echo 'Pada : '.@TanggalIndo($data['TglTransaksi']); ?>
											</td>
											<td>
												<?php echo $data ['AlamatLengkapPerson']; ?>
												
											</td>
											<td align="center">
												<?php echo "Rp ".number_format($data['TotalRetribusi']); ?>
											</td>
											<td width="150px" align="center">
												<?php if ($cek_fitur['EditData'] =='1'){ ?>
													<a href="TrTeraDilokasi.php?id=<?php echo base64_encode($data['NoTransaksi']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a> 
												<?php } ?>
											
												<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
												<!-- Tombol Detil Data Timbangan Per user-->		
													<a href="TrTeraDilokasi.php?tr=<?php echo base64_encode($data['NoTransaksi']);?>&aksi=<?php echo base64_encode('HapusTransaksi');?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>
												<?php } ?>
												<?php if ($cek_fitur['PrintData'] =='1' AND $data['IsDitera'] =='1'){ ?>
													<!-- Tombol Print Data Timbangan Per user-->		
													<!-- <a href="../library/html2pdf/cetak/PrintSKRD.php?id=<?php echo base64_encode($data['NoTransaksi']); ?>"  target="_BLANK" title="Cetak SKRD" ><i class="btn btn-secondary btn-sm"><span class="fa fa-print"></span></i></a> -->
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
									<h5>Pilih User Pemohon </h5>
									<form method="post" action="">
									<div class="form-group-material">
										<input type="text" class="form-control" id="nama" placeholder="Pilih Person"  value="<?php echo  @$RowData['NamaPerson']; ?>" readonly="" />
										<input type="hidden" name="IDPerson"  value="<?php echo @$RowData['IDPerson']; ?>" id="id">
									</div>
									<div class="form-group-material">
										<div class="input-group">
											<select class="form-control" name="KodeLokasi" id="KodeLokasi" autocomplete="off" required>
											<option value=''>--- Lokasi Alamat---</option>
											<?php
												$menu = mysqli_query($koneksi,"SELECT * FROM lokasimilikperson WHERE IDPerson='".$RowData['IDPerson']."' ORDER BY NamaLokasi");
												while($kode = mysqli_fetch_array($menu)){
													if($kode['KodeLokasi']==$RowData['KodeLokasi']){
														echo "<option value=\"".$kode['KodeLokasi']."\" selected>(".$kode['NamaLokasi'].") ".$kode['AlamatLokasi']."</option>\n";
													}else{
														echo "<option value=\"".$kode['KodeLokasi']."\" >(".$kode['NamaLokasi'].") ".$kode['AlamatLokasi']."</option>\n";
													}
												}
											?>
											</select>
											<div class="input-group-append">
											<?php 
												if(@$_GET['id'] != 0 OR @$_GET['id'] != null){ 		
													echo ' <input type="hidden" name="NoTransaksi" value="'.$RowData['NoTransaksi'].'">';
													echo '<button type="submit" class="btn btn-primary" name="GantiTrans">Pilih</button>';
												}else{
													echo '<button type="submit" class="btn btn-primary" name="SimpanTrans">Pilih</button>';
												}
											?>
											</div>
										</div>
									</div>
									</form><hr>
									<?php if (@$_GET['id']!=null) { ?>
									<h5>Detil Timbangan <?php echo $RowData['NamaPerson']; ?></h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Aksi</th>
											  <th>No</th>
											  <th>Nama Timbangan</th>
											  <th>Alamat</th>
											  <th>Hasil Tera</th>
											  <th>Tarif Retribusi</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT a.NominalRetribusi,b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,a.NoUrutTrans,a.NoTransaksi,a.IDPerson,a.HasilAction1,b.KodeLokasi,a.IDTimbangan 
													FROM trtimbanganitem a 
													join timbanganperson b on  (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(b.IDTimbangan,b.KodeLokasi,b.IDPerson)  
													join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan 
													join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) 
													join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) 
													join lokasimilikperson f on (b.KodeLokasi,b.IDPerson)=(f.KodeLokasi,f.IDPerson) 
													WHERE a.NoTransaksi='".$RowData['NoTransaksi']."' 
													GROUP BY b.IDTimbangan,a.NoUrutTrans order by a.NoUrutTrans");
												$no_urut = 0;
												$count = mysqli_num_rows($sql);
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($res = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												<td width="90px" align="center">
													<!-- Tombol Hapus Data Timbangan Per user-->
													<?php $totRet = $RowData['TotalRetribusi']-$res['NominalRetribusi'];?>	

													<a href="TrTeraDilokasi.php?id=<?php echo base64_encode($res['NoTransaksi']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($res['NoUrutTrans']);  ?>&ut=<?php echo base64_encode($res['IDTimbangan']);?>&rp=<?php echo base64_encode($totRet);?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>

													<?php if ($res['HasilAction1'] != 'TERA BATAL') { ?>	
														<a href="../library/html2pdf/cetak/SKHP.php?id=<?php echo $res['NoTransaksi']; ?>&itm=<?php echo $res['NoUrutTrans']?>"  target="_BLANK" title="Cetak SKHP" ><i class="btn btn-secondary btn-sm"><span class="fa fa-print"></span></i></a>	
													<?php } ?>
													
																										
												</td>
												<td width="50px">
													<?php echo ++$no_urut;?> 
												</td>
												<td>
													<?php echo "<strong>".$res['NamaTimbangan']."</strong> ".$res['NamaKelas']." ".$res['NamaUkuran']; ?>
												</td>
												<td>
													<?php echo $res['NamaLokasi']; ?>
												</td>
												<td>
													<?php echo $res['HasilAction1']; ?>
												</td>
												<td align="right">
													<?php 
														if ($res['IDPerson'] == 'PRS-2019-0000000'){
															echo "<strong>0</strong>"; 
															$jumlah[] = 0;
														}else{
															echo "<strong>".number_format($res ['NominalRetribusi'])."</strong>"; 
															$jumlah[] = $res['NominalRetribusi'];
														}
													?>
												</td>
											</tr>
												<?php } ?>
											<tr>
												<td colspan="5" align="center">
													<p>Total Retribusi</p>
												</td>
												<td align="right">
													<?php echo number_format(array_sum(@$jumlah)); ?>
												</td>
											</tr>	
											<?php } ?>
											<tr style="background-color:white;">
												<td colspan="9" align="right">
												<a href="#" class='open_modal_item' data-notransaksi='<?php echo $RowData['NoTransaksi'];?>' data-idperson='<?php echo $RowData['IDPerson'];?>' data-user='<?php echo $login_id; ?>' data-lokasi='<?php echo $RowData['KodeLokasi']; ?>' data-aksi='<?php echo 'Tera Dilokasi'; ?>' data-total='<?php echo $RowData['TotalRetribusi'];; ?>'><span class="btn btn-secondary " title="Tambah Item Timbangan">Tambah</span></a>
												</td>
											</tr>
										  </tbody>
										</table>
									</div><hr>	
									</div>
									<div class="col-lg-12">
										<div class="text-left">
											<form action="" method="post"  class="form-horizontal">
												<div class="form-group row" style="display:none">
												  <label class="col-sm-3 form-control-label">No SKRD</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="No SKRD" value="<?php echo @$RowData['NoSKRD'];?>" name="NoSKRD">
												  </div>
												</div>
												<div class="form-group row">
												  <label class="col-sm-3 form-control-label">Keterangan</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="Keterangan" value="<?php echo @$RowData['KeteranganTera'];?>" name="KeteranganTera" required>
												  </div>
												</div>
												<hr/>
												<input type="hidden" name="NoTransaksi" value="<?php echo $RowData['NoTransaksi']; ?>"> 
												<input type="hidden" name="IDPerson" value="<?php echo $RowData['IDPerson']; ?>"> 
												<input type="hidden" name="TotalRetribusi" value="<?php echo array_sum(@$jumlah); ?>"> 
												<button type="submit" class="btn btn-success" name="SimpanTransaksi">Simpan</button>
												<a href="TrTeraDilokasi.php"><span class="btn btn-danger">Kembali</span></a>			
											</form>
										</div>
									  </form>
									</div>
									<?php } ?>
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
	<div id="ModalEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width:800px">
            <div class="modal-content">
              <div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">Pencarian User Pemilik UTTP</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                </div>
                <div class="modal-body">
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
							
                            $query = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE  JenisPerson LIKE '%Timbangan%' AND IsVerified=b'1' ORDER BY NamaPerson ASC");
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
	<script type="text/javascript" src="../library/datatables/datatables.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});

		
        // jika dipilih, nim akan masuk ke input dan modal di tutup
        $(document).on('click', '#pilih', function (e) {
            document.getElementById("id").value = $(this).attr('data-id');
            document.getElementById("nama").value = $(this).attr('data-nama');
            $('#myModal').modal('hide');
            var IDPerson = $(this).attr('data-id');
            $.ajax({
			url: "../library/Dropdown/ambil-kodelokasi.php",
			data: "IDPerson="+IDPerson,
			cache: false,
			success: function(msg){
				$("#KodeLokasi").html(msg);
			}
		});


        });

        $(document).on('click', '#nama', function () {
        	$('#myModal').modal('show');
			var table = $('#example').DataTable();
			var input = $('#example_filter input');
			setTimeout(function(){
				input.focus();
			}, 500);
        });
	</script>
	<script type="text/javascript">
		// open modal menambah item timbangan
	   $(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var no_trans = $(this).data("notransaksi");
		  var id_person = $(this).data("idperson");
		  var user = $(this).data("user");
		  var kode_lokasi = $(this).data("lokasi");
		  var aksi = $(this).data("aksi");
		  var total = $(this).data("total");
		  	   $.ajax({
					   url: "Modal/AddTeraDilokasi.php",
					   type: "GET",
					   data : {NoTransaksi: no_trans,IDPerson: id_person,login_id: user,KodeLokasi: kode_lokasi,Aksi: aksi,TotalRetribusi:total},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		
	</script>	
	<?php 
		@$IDPerson		= htmlspecialchars($_POST['IDPerson']);
		@$KodeLokasi	= htmlspecialchars($_POST['KodeLokasi']);
		@$NoTransaksi	= htmlspecialchars($_POST['NoTransaksi']);
		@$UserName		= htmlspecialchars($_POST['UserName']);
		@$IDTimbangan	= htmlspecialchars($_POST['IDTimbangan']);
		@$Keterangan	= htmlspecialchars($_POST['Keterangan']);
		@$NoUrutTrans	= htmlspecialchars($_POST['NoUrutTrans']);
		@$NoSKRD		= htmlspecialchars($_POST['NoSKRD']);
		@$TotalRetribusi= htmlspecialchars($_POST['TotalRetribusi']);
		@$NoRefTera 	= htmlspecialchars($_POST['NoRefTera']);
		@$NoRefAmbil	= htmlspecialchars($_POST['NoRefAmbil']);
		@$KeteranganTera= htmlspecialchars($_POST['KeteranganTera']);
		@$HasilAction1  = htmlspecialchars($_POST['HasilAction1']);
		@$HasilAction2  = htmlspecialchars($_POST['HasilAction2']);
		@$Retribusi     = htmlspecialchars($_POST['Retribusi']);
		$TanggalTransaksi = date("Y-m-d H:i:s");
		
		if(isset($_POST['SimpanTrans'])){
			// cek apakah sudah ada permohonan
			$cek = @mysqli_query($koneksi, "SELECT * from tractiontimbangan where IDPerson='$IDPerson' AND (StatusTransaksi='PRSOSS SIDANG'  OR StatusTransaksi ='PRAWAITING')");
			$num = @mysqli_num_rows($cek);
			
			if($num > 0){
				echo '<script type="text/javascript">swal( "Transaksi Sudah Ada!", " Silahkan Mengubah Transaksi Yang Sudah Ada ", "error" ); </script>';
			}else{ 
				// membuat id otomatis
				$sql = @mysqli_query($koneksi, "SELECT RIGHT(NoTransaksi,8) AS kode FROM tractiontimbangan ORDER BY NoTransaksi DESC LIMIT 1"); 
				$nums = mysqli_num_rows($sql);
				if($nums <> 0){
					 $data = mysqli_fetch_array($sql);
					 $kode = $data['kode'] + 1;
				}else{
					 $kode = 1;
				}
				//mulai bikin kode
				 $bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
				 $kode_jadi = "TR-".$Tanggal."-".$bikin_kode;
				
				//simpan 
				$SimpanData = @mysqli_query($koneksi, "INSERT INTO tractiontimbangan (NoTransaksi,IDPerson,JenisTransaksi,UserTerima,UserTera,UserAmbil,KodeLokasi,TglTransaksi,StatusTransaksi)VALUES('$kode_jadi','$IDPerson','TERA DI LOKASI','$login_id','$login_id','$login_id','$KodeLokasi', DATE(NOW()), 'PRAWAITING')"); 
				
				if ($SimpanData){
						InsertLog($koneksi, 'Tambah Data', 'Menambah Transaksi Tera Timbangan Di Lokasi', $login_id, $kode_jadi, 'Transaksi Tera Di Lokasi');
						echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($kode_jadi).'";</script>';
					}else{
						echo '<script type="text/javascript">
					  sweetAlert({
						title: "Simpan Data Gagal!",
						text: " ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrTeraDilokasi.php";
					  });
					  </script>';
					}
				
				
			}
	}
	
	
	
	//Simpan Edit Item Timbangan
	if(isset($_POST['SimpanEdit'])){
				
		$sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDiLokasi,b.UkuranRealTimbangan,a.NilaiBawah,a.RetPenambahanDilokasi,a.NilaiTambah FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
		$res    = mysqli_fetch_array($sql);
		
		if ($res['NilaiBawah'] == '0' AND $res['RetPenambahanDilokasi'] == '0' ) {
			$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET IDTimbangan='$IDTimbangan',HasilAction1='$HasilAction1', HasilAction2='$HasilAction2', HasilAction3='$HasilAction3',NominalRetribusi='".$res[0]."' WHERE NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
		}else{
			$Nilai = ($res['UkuranRealTimbangan']-$res['NilaiTambah'])/$res['NilaiBawah'];
			$Penambahan =($Nilai*$res['RetPenambahanDilokasi'])+$res['RetribusiDiLokasi'];
			
			$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET IDTimbangan='$IDTimbangan',HasilAction1='$HasilAction1', HasilAction2='$HasilAction2', HasilAction3='$HasilAction3',NominalRetribusi='".$Penambahan."' WHERE NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
		}
				
		if ($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Item Timbangan Transaksi Tera  Di Lokasi ', $login_id, $NoTransaksi, 'Transaksi Tera Di Lokasi');
			echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($NoTransaksi).'";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrTeraDilokasi.php";
			  });
			  </script>';
		}
	}
	
	
		
	//Hapus Data Item Timbangan
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
		$HapusGambar = mysqli_query($koneksi,"SELECT FotoAction1,FotoAction2,FotoAction3,IDTimbangan FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."' and NoUrutTrans='".htmlspecialchars(base64_decode($_GET['nm']))."'");
		$data=mysqli_fetch_array($HapusGambar);
		
		mysqli_query($koneksi,"update timbanganperson set StatusUTTP='Aktif' where IDTimbangan='".$data['IDTimbangan']."'");
		$query = mysqli_query($koneksi,"delete from trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."' and NoUrutTrans='".htmlspecialchars(base64_decode($_GET['nm']))."'");
		if($query){
			
			mysqli_query($koneksi,"update tractiontimbangan set TotalRetribusi='".htmlspecialchars(base64_decode($_GET['rp']))."' where NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Item Timbangan', $login_id, base64_decode(@$_GET['id']), 'Transaksi Tera Di Lokasi');
			echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.$_GET['id'].'"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrTeraDilokasi.php";
					  });
					  </script>';
		}
	}
	
	//Ganti User Pemohon
	if(isset($_POST['GantiTrans'])){ 
		// cek apakah sudah ada permohonan
		$cek = @mysqli_query($koneksi, "SELECT * from tractiontimbangan where IDPerson='$IDPerson' AND KodeLokasi='$KodeLokasi' AND ( StatusTransaksi='PROSES SIDANG' OR StatusTransaksi='PRAWAITING')");
		$num = @mysqli_num_rows($cek);
		
		if($num > 0){
			echo '<script type="text/javascript">swal( "Transaksi Sudah Ada!", "Silahkan Mengubah Transaksi Yang Sudah Ada ", "error" ); </script>';
		}else{ 
			mysqli_query($koneksi,"DELETE FROM trtimbanganitem WHERE NoTransaksi='$NoTransaksi'");
			//update 
			$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET IDPerson='$IDPerson', KodeLokasi='$KodeLokasi' WHERE NoTransaksi='$NoTransaksi'");
			if ($query){
				InsertLog($koneksi, 'Edit Data', 'Mengubah User Pemohon Transaksi Penerimaan Timbangan User', $login_id, $NoTransaksi, 'Transaksi Tera Di Lokasi');
				echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($NoTransaksi).'";</script>';
			}else{
				echo '<script type="text/javascript">
				  sweetAlert({
					title: "Simpan Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "TrTeraDilokasi.php";
				  });
				  </script>';
			}
		}
	}
	
	//Simpan Transaksi Penerimaan
	if(isset($_POST['SimpanTransaksi'])){
		// cek apakah sudah ada permohonan
		$cek = @mysqli_query($koneksi, "SELECT * from trtimbanganitem where NoTransaksi='$NoTransaksi'");
		$num = @mysqli_num_rows($cek);
		// echo $num;
		
		if($num <= 0){
			echo '<script type="text/javascript">swal( "Item Timbangan Belum Ada!", " Silahkan inputkan item timbangan user ", "error" ); </script>';
		}else{ 
			$res = @mysqli_query($koneksi, "SELECT NoRefAmbil from tractiontimbangan where NoTransaksi='$NoTransaksi'");
			$check = @mysqli_fetch_array($res);
			
			
			if ($check[0] == null OR $check[0] == ''){
					// membuat id no ref tera
					$sql = @mysqli_query($koneksi, "SELECT RIGHT(NoRefTera,8) AS kode FROM tractiontimbangan ORDER BY NoRefTera DESC LIMIT 1"); 
					$nums = mysqli_num_rows($sql);
					if($nums <> 0){
						 $data = mysqli_fetch_array($sql);
						 $kode = $data['kode'] + 1;
					}else{
						 $kode = 1;
					}
					//mulai bikin kode
					 $bikin_kode = str_pad($kode, 8, "0", STR_PAD_LEFT);
					 $kode_jadi = "TB-".$Tanggal."-".$bikin_kode;
					
					//Noref Ambil
					$sql1 = @mysqli_query($koneksi, "SELECT RIGHT(NoRefAmbil,8) AS kodeambil FROM tractiontimbangan ORDER BY NoRefAmbil DESC LIMIT 1"); 
					$nums1 = mysqli_num_rows($sql1);
					if($nums1 <> 0){
						 $data1 = mysqli_fetch_array($sql1);
						 $kode1 = $data1['kodeambil'] + 1;
					}else{
						 $kode1 = 1;
					}
					//mulai bikin kode
					 $bikin_kode1 = str_pad($kode1, 8, "0", STR_PAD_LEFT);
					 $kode_ambil = "AML-".$Tanggal."-".$bikin_kode1;
				
				$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET NoSKRD='$NoSKRD', TotalRetribusi='$TotalRetribusi',KeteranganTera='$KeteranganTera',NoRefTera='$kode_jadi',NoRefAmbil='$kode_ambil',IsDiambil=b'1',TglAmbil='$TanggalTransaksi',IsDitera=b'1',UserTera='$login_id',StatusTransaksi='SELESAI',TglTera='$TanggalTransaksi' WHERE NoTransaksi='$NoTransaksi' and IDPerson='$IDPerson'");
			}else{
				$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET NoSKRD='$NoSKRD', TotalRetribusi='$TotalRetribusi',KeteranganTera='$KeteranganTera',IsDiambil=b'1',TglAmbil='$TanggalTransaksi',IsDitera=b'1',UserTera='$login_id',StatusTransaksi='SELESAI',TglTera='$TanggalTransaksi' WHERE NoTransaksi='$NoTransaksi' and IDPerson='$IDPerson'");
			}
		
			
			if ($query){
				InsertLog($koneksi, 'Edit Data', 'Transaksi Tera DiLokasi', $login_id, $NoTransaksi, 'Transaksi Tera Di Lokasi');
				echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($NoTransaksi).'";</script>';
			}else{
				echo '<script type="text/javascript">
				  sweetAlert({
					title: "Simpan Data Gagal!",
					text: " ",
					type: "error"
				  },
				  function () {
					window.location.href = "TrTeraDilokasi.php";
				  });
				  </script>';
			}
		}
	}
	
	//Hapus Transaksi Penerimaan
	if(base64_decode(@$_GET['aksi'])=='HapusTransaksi'){
		
		$HapusGambar = mysqli_query($koneksi,"SELECT IDTimbangan FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."'");
		$data=mysqli_fetch_array($HapusGambar);

		mysqli_query($koneksi,"DELETE FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."'");
		$query = mysqli_query($koneksi,"delete from tractiontimbangan WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."' ");
		if($query){
			mysqli_query($koneksi,"update timbanganperson set StatusUTTP='Aktif' where IDTimbangan='".$data['IDTimbangan']."'");
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Transaksi Tera Timbangan Di Lokasi', $login_id, base64_decode(@$_GET['tr']), 'Transaksi Tera Di Lokasi');
			echo '<script language="javascript">document.location="TrTeraDilokasi.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrTeraDilokasi.php";
					  });
					  </script>';
		}
	}
	
	if(isset($_POST['TeraUTTP'])){
		$AmbilNoUrut=mysqli_query($koneksi,"SELECT MAX(NoUrutTrans) as NoSaatIni FROM trtimbanganitem WHERE NoTransaksi='$NoTransaksi'");
		$Data=mysqli_fetch_assoc($AmbilNoUrut);
		$NoSekarang = $Data['NoSaatIni'];
		$Urutan = $NoSekarang+1;
				
		@$Status = isset($HasilAction1) && $HasilAction1 === "TERA BATAL" ? "Non Aktif" : "Aktif";
		mysqli_query($koneksi,"update timbanganperson set StatusUTTP='$Status' where IDTimbangan='$IDTimbangan'");
		
		$sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDiLokasi,b.UkuranRealTimbangan,a.NilaiBawah,a.RetPenambahanDilokasi,a.NilaiTambah FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
		$res    = mysqli_fetch_array($sql);
		
		if ($HasilAction1 == 'TERA BATAL'){
			$SimpanData = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,KodeLokasi,HasilAction1,HasilAction2,TanggalTransaksi)VALUES('$NoTransaksi','$Urutan','$IDPerson','$IDTimbangan','$login_id','0','$KodeLokasi','$HasilAction1','$HasilAction2','$TanggalNOW')"); 
			$Total = $Retribusi;
			mysqli_query($koneksi,"update tractiontimbangan set TotalRetribusi='$Total' where NoTransaksi='$NoTransaksi'");
			
		}else{
			if ($res['NilaiBawah'] == '0' AND $res['RetPenambahanDilokasi'] == '0' ) {
				$SimpanData = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,KodeLokasi,HasilAction1,HasilAction2,TanggalTransaksi)VALUES('$NoTransaksi','$Urutan','$IDPerson','$IDTimbangan','$login_id','".$res[0]."','$KodeLokasi','$HasilAction1','$HasilAction2','$TanggalNOW')"); 
				$Total = $Retribusi+$res[0];
				mysqli_query($koneksi,"update tractiontimbangan set TotalRetribusi='$Total' where NoTransaksi='$NoTransaksi'");
			}else{
				$Nilai = ($res['UkuranRealTimbangan']-$res['NilaiTambah'])/$res['NilaiBawah'];
				$Penambahan =($Nilai*$res['RetPenambahanDilokasi'])+$res['RetribusiDiLokasi'];
				
				$SimpanData = @mysqli_query($koneksi, "INSERT INTO trtimbanganitem (NoTransaksi,NoUrutTrans,IDPerson,IDTimbangan,UserName,NominalRetribusi,KodeLokasi,HasilAction1,HasilAction2,TanggalTransaksi)VALUES('$NoTransaksi','$Urutan','$IDPerson','$IDTimbangan','$UserName','".$Penambahan."','$KodeLokasi','$HasilAction1','$HasilAction2','$TanggalNOW')"); 
				$Total = $Retribusi+$Penambahan;
				mysqli_query($koneksi,"update tractiontimbangan set TotalRetribusi='$Total' where NoTransaksi='$NoTransaksi'");
			}
			
		}
		
		if($SimpanData){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Data Item Timbangan ', $login_id, $NoTransaksi, 'Transaksi Tera Di Lokasi');
			echo '<script language="javascript">document.location="TrTeraDilokasi.php?id='.base64_encode($NoTransaksi).'";</script>';
		}else{
			echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="TrTeraDilokasi.php"; </script>';
		}
		
	}
	
	
	?>
  </body>
</html>