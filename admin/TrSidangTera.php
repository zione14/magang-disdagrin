<?php
include 'akses.php';
@$fitur_id = 14;
include '../library/lock-menu.php';
@include '../library/tgl-indo.php';
$Page = 'TrMetrologi';
$SubPage = 'TrSidangTera';
$TanggalTransaksi = date("Y-m-d H:i:s");
$TanggalNOW = date("Y-m-d");
$Tanggal = date('Ymd');

if(@$_GET['id']!=null){
	$Sebutan = 'Edit Proses Tera';	
	$Readonly = 'readonly';
	
	@$Edit = mysqli_query($koneksi,"SELECT a.NamaPerson,b.IDPerson,b.NoTransaksi,c.IDTimbangan,b.TglTransaksi,b.KeteranganTera,b.Keterangan FROM mstperson a join tractiontimbangan b on a.IDPerson=b.IDPerson left join trtimbanganitem c on b.NoTransaksi=c.NoTransaksi WHERE b.NoTransaksi='".base64_decode($_GET['id'])."'");
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
				window.location = "TrTerimaTimbangan.php";
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
              <h2 class="no-margin-bottom">Proses Sidang Tera</h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
					<ul class="nav nav-pills">
						<li <?php if(@$id==null){echo 'class="active"';} ?>>
							<a href="#home-pills" data-toggle="tab"><span class="btn btn-primary">Data Timbangan</span></a>&nbsp;
						</li>
						<li>
							<?php if ($cek_fitur['AddData'] =='1'){ ?>
								<a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Entry Transaksi</span></a>&nbsp;
							<?php } ?>
						</li>
						<li>
							<a href="#edit-user" data-toggle="tab"></a>&nbsp;
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
					<?php if (@$_GET['id'] != null ){ ?>
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
					<?php }else{ ?>
						<div class="tab-pane fade <?php if(@$_REQUEST['cd']==null){ echo 'in active show'; }?>" id="home-pills">
					<?php } ?>
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Sidang Tera</h3>
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
										$sql =  "SELECT a.NoTransaksi,b.NamaPerson,b.AlamatLengkapPerson,b.IDPerson,a.TotalRetribusi,a.TglTransaksi,a.IsDitera FROM tractiontimbangan a join mstperson b on a.IDPerson=b.IDPerson Where b.JenisPerson LIKE '%Timbangan%' AND StatusTransaksi='PROSES SIDANG' ";
										
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
													<a href="TrSidangTera.php?id=<?php echo base64_encode($data['NoTransaksi']);?>" title='Edit'><i class='btn btn-warning btn-sm'><span class='fa fa-edit'></span></i></a> 
												<?php } ?>
											
												<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
												<!-- Tombol Detil Data Timbangan Per user-->		
													<a href="TrSidangTera.php?tr=<?php echo base64_encode($data['NoTransaksi']);?>&aksi=<?php echo base64_encode('HapusTransaksi');?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>
												<?php } ?>
												<?php if ($cek_fitur['PrintData'] =='1' AND $data['IsDitera'] =='1' AND $data['TotalRetribusi'] != '0'){ ?>
													<!-- Tombol Print Data Timbangan Per user-->		
													<a href="../library/html2pdf/cetak/PrintSKRD.php?id=<?php echo base64_encode($data['NoTransaksi']); ?>"  target="_BLANK" title="Cetak SKRD" ><i class="btn btn-secondary btn-sm"><span class="fa fa-print"></span></i></a>
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
						<div class="tab-pane fade <?php if(@$_REQUEST['cd'] != null){ echo 'in active show'; }?>" id="tambah-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									<h5>Pilih Transaksi Penerimaan</h5>
									<form method="post" action="" class="form-horizontal">
									<div class="form-group-material">
										<div class="input-group">
											<select class="form-control" name="cd" autocomplete="off" onchange="this.form.submit()">
											<?php echo '<option value="" disabled selected>-- Pilih Transaksi --</option>';
												$list = @mysqli_query($koneksi, "SELECT a.NoTransaksi,b.NamaPerson FROM tractiontimbangan a join mstperson b on a.IDPerson=b.IDPerson WHERE a.JenisTransaksi='TERA DI KANTOR' AND (a.StatusTransaksi='PENERIMAAN') ORDER by a.TglTransaksi"); 
												while($daftar = @mysqli_fetch_array($list)){
													if($daftar['NoTransaksi'] === @base64_decode($_REQUEST['cd'])){
														echo "<option value=\"".base64_encode($daftar['NoTransaksi'])."\" selected>".$daftar['NoTransaksi']." ".ucwords($daftar['NamaPerson'])."</option>\n";
													}else{
														echo "<option value=\"".base64_encode($daftar['NoTransaksi'])."\">".$daftar['NoTransaksi']." ".ucwords($daftar['NamaPerson'])."</option>\n";
													}
												}
											?>
											</select>
										</div>
									</div>
									<hr>
									</form>
									<h5>Detil Timbangan</h5>
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
												$sql =mysqli_query($koneksi, "SELECT a.NominalRetribusi,b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,a.NoUrutTrans,a.NoTransaksi,a.IDPerson,a.HasilAction1,a.IDTimbangan 
												FROM trtimbanganitem a 
												join timbanganperson b on  (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(b.IDTimbangan,b.KodeLokasi,b.IDPerson) 
												join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan 
												join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) 
												join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) 
												join lokasimilikperson f on (b.KodeLokasi,b.IDPerson)=(f.KodeLokasi,f.IDPerson)
												WHERE a.NoTransaksi='".@base64_decode($_REQUEST['cd'])."' 
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
													<?php if ($res['HasilAction1'] != null OR $res['HasilAction1'] != '') { ?>
														<!-- Tombol Hapus Data Timbangan Per user-->											
														<a href="TrSidangTera.php?id=<?php echo base64_encode($res['NoTransaksi']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($res['NoUrutTrans']);  ?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>
														<!-- Tombol Edit Data Timbangan Per user-->
															<a href="#" class='open_modal_item' data-notransaksi='<?php echo $res['NoTransaksi'];?>'  data-user='<?php echo $res['IDTimbangan']; ?>' data-nourut='<?php echo $res['NoUrutTrans']; ?>' data-aksi='<?php echo 'cd'; ?>' data-trans='<?php echo 'edit'; ?>'><span class="btn btn-warning btn-sm fa fa-edit" title="Edit Item Timbangan" ></span></a>
															
														<?php if ($res['HasilAction1'] != 'TERA BATAL') { ?>	
															<a href="../library/html2pdf/cetak/SKHP.php?id=<?php echo $res['NoTransaksi']; ?>&itm=<?php echo $res['NoUrutTrans']?>"  target="_BLANK" title="Cetak SKHP" ><i class="btn btn-secondary btn-sm"><span class="fa fa-print"></span></i></a>	
														<?php } ?>
													<?php }else{ ?>
														<a href="#" class='open_modal_item' data-notransaksi='<?php echo $res['NoTransaksi'];?>' data-nourut='<?php echo $res['NoUrutTrans'];?>' data-user='<?php echo $res['IDTimbangan']; ?>' data-aksi='<?php echo 'cd'; ?>'><span class="btn btn-secondary btn-sm fa fa-eye" title="Hasil Sidang Tera"></span></a>
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
														echo "<strong>".number_format($res ['NominalRetribusi'])."</strong>"; 
														$jumlah[] = $res['NominalRetribusi'];
														$person = $res['IDPerson'];
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
										  </tbody>
										</table>
									</div><hr>	
									</div>
									<div class="col-lg-12">
										<div class="text-left">
											<form method="post" action="" class="form-horizontal">
												<!--<div class="form-group row">
												  <label class="col-sm-3 form-control-label">Metode</label>
												  <div class="col-sm-9">
													<select name="Metode" id="Metode" class="form-control">
														<option value="">Pilih Opsi</option>
														<option value="Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji" <?php echo isset($RowData['Keterangan']) && $RowData['Keterangan'] === "Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji" ?"selected" : ""; ?>>Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji</option>
														<option value="Syarat Teknis Timbangan Bukan Otomatis" <?php echo isset($RowData['Keterangan']) && $RowData['Keterangan'] === "Syarat Teknis Timbangan Bukan Otomatis" ?"selected" : ""; ?>>Syarat Teknis Timbangan Bukan Otomatis</option>
														
													</select>
												  </div>
												</div>-->
												<div class="form-group row">
												  <label class="col-sm-3 form-control-label">Keterangan</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="Keterangan"  name="Keterangan">
													<input type="hidden" class="form-control" name="TotalRetribusi" value="<?php echo @array_sum($jumlah); ?>">
												  </div>
												</div><hr/>
												<input type="hidden" name="NoTransaksi" value="<?php echo @base64_decode($_REQUEST['cd']); ?>"> 
												<input type="hidden" name="IDPerson" value="<?php echo $person; ?>"> 
												<button type="submit" class="btn btn-success" name="SimpanTransaksi">Simpan</button>
												<a href="TrSidangTera.php"><span class="btn btn-danger">Kembali</span></a>
											</form>
										</div>
									 
									</div>
								</div>
							</div>
						</div>
						<!--=========================================================== Edit Data ====================================================-->
						<div class="tab-pane fade <?php if(@$_GET['id'] != null){ echo 'in active show'; }?>" id="edit-user">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4"><?php echo $Sebutan; ?></h3>
							</div>
							<div class="card-body">
								<div class="row">
								  <div class="col-lg-12">
									<h5>Pilih Transaksi Penerimaan</h5>
									<form method="post" action="" class="form-horizontal">
									<div class="form-group-material">
										<div class="input-group">
											<select class="form-control" name="id" autocomplete="off">
											<?php echo '<option value="" disabled selected>-- Pilih Transaksi --</option>';
												$list = @mysqli_query($koneksi, "SELECT a.NoTransaksi,b.NamaPerson FROM tractiontimbangan a join mstperson b on a.IDPerson=b.IDPerson WHERE a.JenisTransaksi='TERA DI KANTOR' AND a.StatusTransaksi='PROSES SIDANG' ORDER by a.TglTransaksi"); 
												while($daftar = @mysqli_fetch_array($list)){
													if($daftar['NoTransaksi'] === @$RowData['NoTransaksi']){
														echo "<option value=\"".$daftar['NoTransaksi']."\" selected>".$daftar['NoTransaksi']." ".ucwords($daftar['NamaPerson'])."</option>\n";
													}else{
														echo "<option value=\"".$daftar['NoTransaksi']."\">".$daftar['NoTransaksi']." ".ucwords($daftar['NamaPerson'])."</option>\n";
													}
												}
											?>
											</select>
										</div>
									</div>
									<hr>
									</form>
									<h5>Detil Timbangan </h5>
										<div class="table-responsive">  
										<table class="table table-striped">
										  <thead>
											<tr>
											  <th>Aksi </th>
											  <th>No</th>
											  <th>Nama Timbangan</th>
											  <th>Alamat</th>
											  <th>Hasil Tera</th>
											  <th>Tarif Retribusi</th>
											</tr>
										  </thead>
										  <tbody>
											<?php
												$sql =mysqli_query($koneksi, "SELECT a.NominalRetribusi,b.NamaTimbangan,d.NamaKelas,e.NamaUkuran,c.JenisTimbangan,f.NamaLokasi,a.NoUrutTrans,a.NoTransaksi,a.IDPerson,a.FotoAction1,a.HasilAction1,a.IDTimbangan 
												FROM trtimbanganitem a 
												join timbanganperson b on (a.IDTimbangan,a.KodeLokasi,a.IDPerson)=(b.IDTimbangan,b.KodeLokasi,b.IDPerson) 
												join msttimbangan c on c.KodeTimbangan=b.KodeTimbangan 
												join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) join detilukuran e on (e.KodeTimbangan,e.KodeKelas,e.KodeUkuran)=(b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) 
												join lokasimilikperson f on(b.KodeLokasi,b.IDPerson)=(f.KodeLokasi,f.IDPerson) WHERE a.NoTransaksi='".@$RowData['NoTransaksi']."' GROUP BY b.IDTimbangan,a.NoUrutTrans order by a.NoUrutTrans");
												$no_urut = 0;
												$count = mysqli_num_rows($sql);
												if($count == null OR $count === 0){
													echo '<tr class="odd gradeX"><td colspan="9" align="center"><b>Tidak Ada Data</b></td></tr>';
												} else {
													while($res = mysqli_fetch_array($sql)){
											?>
											<tr class="odd gradeX">
												<td width="90px" align="center">
													<?php if ($res['HasilAction1'] != null OR $res['HasilAction1'] != '') { ?>
														<!-- Tombol Hapus Data Timbangan Per user-->											
														<a href="TrSidangTera.php?id=<?php echo base64_encode($res['NoTransaksi']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&nm=<?php echo base64_encode($res['NoUrutTrans']);  ?>&dc=<?php echo '1';  ?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-ban"></span></i></a>
														<!-- Tombol Edit Data Timbangan Per user-->
															<a href="#" class='open_modal_item' data-notransaksi='<?php echo $res['NoTransaksi'];?>'  data-user='<?php echo $res['IDTimbangan']; ?>' data-nourut='<?php echo $res['NoUrutTrans']; ?>' data-aksi='<?php echo 'id'; ?>' data-trans='<?php echo 'edit'; ?>'><span class="btn btn-warning btn-sm fa fa-edit" title="Edit Item Timbangan" ></span></a>
															
															<?php if ($res['HasilAction1'] != 'TERA BATAL') { ?>	
															<a href="../library/html2pdf/cetak/SKHP.php?id=<?php echo $res['NoTransaksi']; ?>&itm=<?php echo $res['NoUrutTrans']?>"  target="_BLANK" title="Cetak SKHP" ><i class="btn btn-secondary btn-sm"><span class="fa fa-print"></span></i></a>	
														<?php } ?>
													<?php }else{ ?>
														<a href="#" class='open_modal_item' data-notransaksi='<?php echo $res['NoTransaksi'];?>' data-nourut='<?php echo $res['NoUrutTrans'];?>' data-user='<?php echo $res['IDTimbangan']; ?>' data-aksi='<?php echo 'id'; ?>'><span class="btn btn-secondary btn-sm fa fa-eye" title="Hasil Sidang Tera"></span></a>
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
														echo "<strong>".number_format($res ['NominalRetribusi'])."</strong>"; 
														$jumlah[] = $res['NominalRetribusi'];
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
										  </tbody>
										</table>
									</div><hr>	
									</div>
									<div class="col-lg-12">
										<div class="text-left">
											<form method="post" action="" class="form-horizontal">
												<div class="form-group row">
												  <label class="col-sm-3 form-control-label">Keterangan</label>
												  <div class="col-sm-9">
													<input type="text" class="form-control" maxlength="150" placeholder="Keterangan" value="<?php echo @$RowData['KeteranganTera'];?>" name="Keterangan">
													<input type="hidden" class="form-control" name="TotalRetribusi" value="<?php echo @array_sum($jumlah); ?>">
												  </div>
												</div><hr/>
												<input type="hidden" name="NoTransaksi" value="<?php echo @$RowData['NoTransaksi']; ?>"> 
												<button type="submit" class="btn btn-success" name="EditTransaksi">Simpan</button>
												<a href="TrSidangTera.php"><span class="btn btn-danger">Kembali</span></a>
												
											</form>
										</div>
									 
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
		});
	</script>
	<script type="text/javascript">
		// open modal lihat progress
	   $(document).ready(function () {
	   $(".open_modal_item").click(function(e) {
		  var no_trans = $(this).data("notransaksi");
		  var user = $(this).data("user");
		  var no_urut = $(this).data("nourut");
		  var aksi = $(this).data("aksi");
		  var trans = $(this).data("trans");
		  	   $.ajax({
					   url: "Modal/AddTeraTimbangan.php",
					   type: "GET",
					   data : {NoTransaksi: no_trans,login_id: user,NoUrutTrans: no_urut,Aksi: aksi, Transaksi: trans},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
		// open modal lihat progress
	   $(document).ready(function () {
	   $(".open_modal_edit").click(function(e) {
		  var no_trans = $(this).data("notransaksi");
		  var user = $(this).data("user");
		  var no_urut = $(this).data("nourut");
		  var aksi = $(this).data("aksi");
		  	   $.ajax({
					   url: "Modal/EditTeraTimbangan.php",
					   type: "GET",
					   data : {NoTransaksi: no_trans,login_id: user,NoUrutTrans: no_urut,Aksi: aksi},
					   success: function (ajaxData){
					   $("#ModalEdit").html(ajaxData);
					   $("#ModalEdit").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>

	<?php 
		
		@$NoTransaksi		= htmlspecialchars($_POST['NoTransaksi']);
		@$IDTimbangan		= htmlspecialchars($_POST['IDTimbangan']);
		@$Keterangan		= htmlspecialchars($_POST['Keterangan']);
		@$HasilAction1		= htmlspecialchars($_POST['HasilAction1']);
		@$HasilAction2		= htmlspecialchars($_POST['HasilAction2']);
		@$Aksi				= htmlspecialchars($_POST['Aksi']);
		@$HasilAction3		= htmlspecialchars($_POST['HasilAction3']);
		@$NoUrutTrans		= htmlspecialchars($_POST['NoUrutTrans']);
		@$TotalRetribusi	= htmlspecialchars($_POST['TotalRetribusi']);
		@$IDPerson			= htmlspecialchars($_POST['IDPerson']);
		
	
	//Simpan Transaksi Sidang Tera
	if(isset($_POST['SimpanTransaksi'])){

		// membuat id otomatis
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
		
		// echo $kode_jadi;
		
		// update 
		if ($IDPerson == 'PRS-2019-0000000'){
		
			$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglTera='$TanggalTransaksi', UserTera='$login_id', NoRefTera='$kode_jadi', KeteranganTera='$Keterangan', IsDitera=b'1', StatusTransaksi='SELESAI',TotalRetribusi='$TotalRetribusi',TglAmbil='$TanggalTransaksi',IsDibayar=b'1',UserBayar='$login_id',UserAmbil='$login_id',TglDibayar='$TanggalTransaksi' WHERE NoTransaksi='$NoTransaksi'");
			
			
			
		}else{
			$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglTera='$TanggalTransaksi', UserTera='$login_id', NoRefTera='$kode_jadi', KeteranganTera='$Keterangan', IsDitera=b'1', StatusTransaksi='PROSES SIDANG',TotalRetribusi='$TotalRetribusi' WHERE NoTransaksi='$NoTransaksi'");
		}
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Transaksi Sidang Tera', $login_id, $NoTransaksi, 'Transaksi Proses Sidang Tera');
			// echo '<script language="javascript">document.location="TrSidangTera.php?id='.base64_encode($NoTransaksi).'";</script>';
			echo '<script language="javascript">document.location="TrSidangTera.php";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrSidangTera.php";
			  });
			  </script>';
		}
	}
	
	//Hapus Transaksi Penerimaan
	if(base64_decode(@$_GET['aksi'])=='HapusTransaksi'){
		mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglTera=NULL, NoRefTera=NULL, KeteranganTera=NULL, IsDitera=NULL, StatusTransaksi='PENERIMAAN',Keterangan=NULL WHERE NoTransaksi='".htmlspecialchars(base64_decode(@$_GET['tr']))."'");
		
		$statustim = mysqli_query($koneksi,"SELECT IDTimbangan FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['tr']))."'");
		$data=mysqli_fetch_array($statustim);	
		
		mysqli_query($koneksi,"update timbanganperson set StatusUTTP='Aktif' where IDTimbangan='".$data['IDTimbangan']."'");
		
		//update 
		$edit = mysqli_query($koneksi,"UPDATE trtimbanganitem SET  FotoAction1=NULL, FotoAction2=NULL, FotoAction3=NULL, HasilAction1=NULL, HasilAction2=NULL, HasilAction3=NULL,NominalRetribusi=NULL,TanggalTransaksi=NULL  WHERE NoTransaksi='".htmlspecialchars(base64_decode(@$_GET['tr']))."'");
		if($edit){
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Transaksi Penerimaan Timbangan', $login_id, base64_decode(@$_GET['tr']), 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrSidangTera.php";
					  });
					  </script>';
		}
		
	}
	
	if(base64_decode(@$_GET['aksi'])=='Hapus'){
				
		$statustim = mysqli_query($koneksi,"SELECT IDTimbangan FROM trtimbanganitem WHERE NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."'");
		$data=mysqli_fetch_array($statustim);	
		
		mysqli_query($koneksi,"update timbanganperson set StatusUTTP='Aktif' where IDTimbangan='".$data['IDTimbangan']."'");
		
		$query = mysqli_query($koneksi,"update trtimbanganitem set FotoAction1=NULL, FotoAction2=NULL, FotoAction3=NULL, HasilAction1=NULL, HasilAction2=NULL, HasilAction3=NULL,NominalRetribusi=NULL  WHERE  NoTransaksi='".htmlspecialchars(base64_decode($_GET['id']))."' and NoUrutTrans='".htmlspecialchars(base64_decode($_GET['nm']))."'");
		if($query){
			
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Hasil Sidang Tera Timbangan ', $login_id, base64_decode(@$_GET['id']), 'Transaksi Proses Sidang Tera');
			if ($_GET['dc'] == '1') {
				echo '<script language="javascript">document.location="TrSidangTera.php?id='.$_GET['id'].'"; </script>';
			}else{
				echo '<script language="javascript">document.location="TrSidangTera.php?cd='.$_GET['id'].'"; </script>';
			}
			
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrSidangTera.php";
					  });
					  </script>';
		}
	}
	
	//Simpan Edit Item Timbangan
	if(isset($_POST['SimpanEdit'])){
		//update 
		$query = mysqli_query($koneksi,"UPDATE trtimbanganitem SET HasilAction1='$HasilAction1', HasilAction2='$HasilAction2', HasilAction3='$HasilAction3' WHERE NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
		if ($query){
			InsertLog($koneksi, 'Edit Data', 'Mengubah Transaksi Hasil Sidang Tera ', $login_id, $NoTransaksi, 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php?NoTransaksi='.$NoTransaksi.'";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrSidangTera.php";
			  });
			  </script>';
		}
	}
	
	//Simpan Transaksi Sidang Tera
	if(isset($_POST['EditTransaksi'])){
		
		// update 
		$query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET TglTera='$TanggalTransaksi', UserTera='$login_id', KeteranganTera='$Keterangan', IsDitera=b'1', StatusTransaksi='PROSES SIDANG',TotalRetribusi='$TotalRetribusi' WHERE NoTransaksi='$NoTransaksi'");
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Transaksi Sidang Tera', $login_id, $NoTransaksi, 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrSidangTera.php";
			  });
			  </script>';
		}
	}
	
	
	if(isset($_POST['TeraUTTP'])){
		@$Status = isset($HasilAction1) && $HasilAction1 === "TERA BATAL" ? "Non Aktif" : "Aktif";
		mysqli_query($koneksi,"update timbanganperson set StatusUTTP='$Status' where IDTimbangan='$IDTimbangan'");
		
		if ($HasilAction1 == 'TERA BATAL'){
			$query = mysqli_query($koneksi,"update trtimbanganitem set HasilAction1='$HasilAction1', NominalRetribusi='0', TanggalTransaksi='$TanggalNOW' where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
		}else{
			$sql 	= mysqli_query($koneksi, ("SELECT a.RetribusiDikantor,b.UkuranRealTimbangan,a.NilaiBawah,a.RetPenambahanDikantor,a.NilaiTambah FROM detilukuran a join timbanganperson b on (a.KodeTimbangan,a.KodeKelas,a.KodeUkuran) = (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran) WHERE b.IDTimbangan='$IDTimbangan'"));
			$res    = mysqli_fetch_array($sql);
		
			if ($res['NilaiBawah'] == '0' AND $res['RetPenambahanDikantor'] == '0' ) {
				$query = mysqli_query($koneksi,"update trtimbanganitem set HasilAction1='$HasilAction1',NominalRetribusi='".$res[0]."',HasilAction2='$HasilAction2', TanggalTransaksi='$TanggalNOW'  where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
								
			}else{
				$Nilai = ($res['UkuranRealTimbangan']-$res['NilaiTambah'])/$res['NilaiBawah'];
				$Penambahan =($Nilai*$res['RetPenambahanDikantor'])+$res['RetribusiDikantor'];
							
				$query = mysqli_query($koneksi,"update trtimbanganitem set HasilAction1='$HasilAction1',NominalRetribusi='$Penambahan',HasilAction2='$HasilAction2', TanggalTransaksi='$TanggalNOW' where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'");
			}
		}
		
		if ($query){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Hasil Sidang Tera Timbangan', $login_id, $NoTransaksi, 'Transaksi Proses Sidang Tera');
			echo '<script language="javascript">document.location="TrSidangTera.php?'.$Aksi.'='.base64_encode($NoTransaksi).'"; </script>';
		}else{
			echo '<script language="javascript">alert("Data Gagal Disimpan!");document.location="TrSidangTera.php"; </script>';
		}
		
	}
	?>
  </body>
</html>