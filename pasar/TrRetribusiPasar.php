<?php
include 'akses.php';
$Page = 'TrRetribusiPasar';
$Tanggal = date('Ymd');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include 'title.php';?>
    <!-- <meta name="description" content=""> -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->
    <!-- <meta name="robots" content="all,follow"> -->
    <!-- Bootstrap CSS-->
    <!-- <link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css"> -->
    <!-- Font Awesome CSS-->
    <!-- <link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css"> -->
    <!-- Fontastic Custom icon font-->
    <!-- <link rel="stylesheet" href="../komponen/css/fontastic.css"> -->
    <!-- Google fonts - Poppins -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700"> -->
    <!-- theme stylesheet-->
	<!-- <?php include 'style.php';?> -->
    <!-- Custom stylesheet - for your changes-->
    <!-- <link rel="stylesheet" href="../komponen/css/custom.css"> -->
	<!-- Sweet Alerts -->
    <!-- <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet"> -->
	<!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Select2Master -->
	<!-- <link rel="stylesheet" href="../library/select2master/css/select2.css"/> -->
	<!-- <link rel="stylesheet" href="../library/datatables/dataTables.bootstrap.css"/> -->
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
              <h2 class="no-margin-bottom">Entry Retribusi Pasar</h2>
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
							<a href="TrRetribusiPasar_tambah.php"><span class="btn btn-primary">Entry Transaksi</span></a>&nbsp;
							<!-- <?php if ($cek_fitur['AddData'] =='1'){ ?> -->
							<!-- <a href="#tambah-user" data-toggle="tab"><span class="btn btn-primary">Tambah Data</span></a>&nbsp; -->
								
							<!-- <?php } ?> -->
						</li>
					</ul><br/>
				  <div class="card">
					<div class="tab-content">
						<div class="tab-pane fade <?php if(@$_GET['id']==null){ echo 'in active show'; }?>" id="home-pills">
							<div class="card-header d-flex align-items-center">
							  <h3 class="h4">Catatan Transaksi Retribusi Pasar</h3>
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
									  <th>Lapak</th>
									  <!-- <th>Jumlah Hari Dibayar</th> -->
									  <th>Nilai Retribusi</th>
									  <!--<th>Jumlah Retribusi</th>-->
									  <th>Aksi</th>
									</tr>
								  </thead>
									<?php
										include '../library/pagination1.php';
										// mengatur variabel reload dan sql
										@$keyword = $_REQUEST['keyword'];
										$reload = "TrRetribusiPasar.php?pagination=true&keyword=$keyword";
										$sql =  "SELECT a.TanggalTrans,a.JmlHariDibayar,a.NominalDiterima,b.NamaPasar,c.BlokLapak,c.NomorLapak,a.NoTransRet,d.NamaPerson
										
										FROM trretribusipasar a
										JOIN mstpasar b on a.KodePasar=b.KodePasar
										JOIN lapakpasar c on (a.KodePasar,a.IDLapak)=(c.KodePasar,c.IDLapak)
										JOIN mstperson d on a.IDPerson=d.IDPerson
										WHERE IsTransfer = '0' AND a.KodePasar='$KodePasar' ";
										
										if(@$_REQUEST['keyword']!=null){
											$sql .= " AND d.NamaPerson LIKE '%".$_REQUEST['keyword']."%'   ";
										}
										
										$sql .=" ORDER BY a.TanggalTrans DESC";
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
												<?php echo 'Pada : '.@TanggalIndo($data['TanggalTrans']); ?>
											</td>
											<td>
												<?php echo $data ['NamaPasar']."<br>".$data['BlokLapak']." ".$data['NomorLapak']; ?>
											</td>
											<!-- <td>
												<?php echo $data ['JmlHariDibayar']." Hari"; ?>
											</td> -->
											
											<td align="center">
												<?php echo "Rp ".number_format($data['NominalDiterima']); ?>
											</td>
											<td width="100px" align="center">
												<a href="TrRetribusiPasar.php?tr=<?php echo base64_encode($data['NoTransRet']);?>&aksi=<?php echo base64_encode('HapusTransaksi');?>" title="Hapus" onclick="return confirmation()"><i class="btn btn-danger btn-sm"><span class="fa fa-trash"></span></i></a>
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
								<form method="post" action="">
								<div class="row">
								  <div class="col-lg-6">
									<div class="form-group">
										<label>User Pemohon</label>
										<input type="hidden" name="NoTransRet" value="<?php echo @$row['NoTransRet']; ?>">
										<input type="hidden" id="IDPerson" name="IDPerson" value="<?php echo  @$row['IDPerson']; ?>">
										<input type="text" class="form-control" data-toggle="modal" data-target="#myModal" name="NamaPerson" id="NamaPerson" placeholder="Klik Pilih User"  value="<?php echo  @$row['NamaPerson']; ?>" readonly="" />
									</div>
									<div class="form-group">
										<label>Lapak Pemohon</label>
										<select id="IDLapak" class="form-control">
										</select>
										<input type="hidden" id="val_pasar" name="KodePasar" value="<?php echo @$No_Transaksi; ?>">
										<input type="hidden" id="val_tgl" name="TglAktif" value="<?php echo @$No_Transaksi; ?>">
										<input type="hidden" id="val_lapak" name="IDLapak" value="<?php echo @$No_Transaksi; ?>">
										<input type="hidden" id="val_trans" name="TanggalTrans" value="<?php echo @$No_Transaksi; ?>">
										
								    </div>
									<div class="form-group">
									  <label>Retribusi Lapak </label>
										<input type="text" class="form-control" id="val_retribusi" value="<?php echo @$RowData['MasaTera'];?>" name="Retribusi" readonly>
									</div>
									 <div class="form-row">
										<div class="form-group col-md-6">
										  <label>Jumlah Hari Dibayar</label>
										  <input type="number" id="Jumlah" onkeyup="hitungNilai()" name="JmlHariDibayar" placeholder="Jumlah Hari"   class="form-control">
										</div>
										<div class="form-group col-md-6">
										  <label>Nominal Retribusi Diterima</label>
										  <input type="text" id="coba" name="NominalRetribusi" placeholder="0" class="form-control" readonly>
										</div>
									 </div>
								   </div>
								   <div class="col-lg-6">
									<div class="form-group">
									  <label>Keterangan</label>	
										<textarea type="text" name="Keterangan" class="form-control" rows="3" placeholder="Keterangan"></textarea>
									</div>
									<div class="form-group">
										<input type="checkbox"  value="1" name="IsTransfer" <?php if(@$row['IsTransfer'] == '1') { echo 'checked'; }; ?>  class="checkbox-template">
										<label>Transfer</label>
									</div>
								   </div>
									<div class="col-lg-12">
										<div class="text-right">
										<?php
											if(@$_GET['id']==null){
												echo '<button type="submit" class="btn btn-primary" name="Simpan">Simpan</button> &nbsp;';
											}else{
												echo '<button type="submit" class="btn btn-primary" name="SimpanEdit">Simpan</button> &nbsp;';
											}
											echo '<a href="TrRetribusiPasar.php"><span class="btn btn-warning">Kembali</span></a>'
										?>
										</div>
									  </form>
									</div>
								</div>
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

	</div>
	<!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="width:800px">
                <div class="modal-content">
                  <div class="modal-header">
						<h4 class="modal-title" id="myModalLabel">Pencarian User Pemilik Lapak</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table id="lookup" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
									<th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody><!-- -->
                                <?php
                                $query = mysqli_query($koneksi,"SELECT * FROM mstperson WHERE  IsPerusahaan = '0'  and UserName != 'dinas' and JenisPerson LIKE '%Pedagang%' AND IsVerified=b'1'  ORDER BY NamaPerson ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                ?>
                                    <tr id="pilih" data-nim="<?php echo $data['IDPerson']; ?>" data-nama="<?php echo $data['NamaPerson']; ?>">
                                        <td><?php echo $data['NamaPerson']; ?></td>
                                        <td><?php echo $data['NIK']; ?></td>
										<td><?php echo $data['AlamatLengkapPerson']; ?></td>
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
	<script src="../library/select2master/js/select2.min.js"></script>
	<script src="../library/datatables/jquery.dataTables.js"></script>
    <script src="../library/datatables/dataTables.bootstrap.js"></script>
	<script type="text/javascript">
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
		});
		
	
	  //tabel lookup user
		$(function () {
			$("#lookup").dataTable();
		});
	 // jika dipilih, id akan masuk ke input dan modal di tutup
		$(document).on('click', '#pilih', function (e) {
			document.getElementById("IDPerson").value = $(this).attr('data-nim');
			document.getElementById("NamaPerson").value = $(this).attr('data-nama');
			$('#myModal').modal('hide');
			 document.getElementById("val_pasar").value = "";
			 document.getElementById("val_retribusi").value = "";
			 document.getElementById("val_tgl").value = "";
			 document.getElementById("val_lapak").value = "";
			 document.getElementById("val_trans").value = "";
			 document.getElementById("Jumlah").value = "";
			 document.getElementById("coba").value = "";
			var IDPerson = document.getElementById("IDPerson").value;
				$.ajax({
				url: "../library/Dropdown/ambil-lapakuser.php",
				data: "IDPerson="+IDPerson,
				cache: false,
				success: function(msg){
					$("#IDLapak").html(msg);
				}
			});
		});
	
		var htmlobjek;
		$(document).ready(function(){
		  //apabila terjadi event onchange terhadap object <select id=nama_produk>
		 $("#IDLapak").change(function(){
			var IDLapak = $("#IDLapak").val();
			var arrstring = IDLapak.split("#");
			var idlapak   = arrstring[0];
			var kodepasar = arrstring[1];
			
			 $.ajax({
				url: "../library/Dropdown/ambil-harga.php",
				method: "POST",
				data: {Lapak: idlapak,Pasar: kodepasar},
				// dataType: 'json',
				success: function (data) {
					if (data) {
						var Data = data;
						var string = Data.split("#");
						$('#val_pasar').val(string[1]);
						$('#val_retribusi').val(string[2]);
						$('#val_tgl').val(string[0]);
						$('#val_lapak').val(string[3]);
						$('#val_trans').val(string[4]);
					
					} else {
						alert('request failed');
					}
				}
			});
		  });
		});
	
	function hitungNilai() {
		var jumlahhari =  document.getElementById("Jumlah").value;
		var retribusi =  document.getElementById("val_retribusi").value;
		var nilairetribusi = parseInt(jumlahhari) * parseInt(retribusi);
		
		if (!isNaN(nilairetribusi)){
				var akhir = nilairetribusi == '' ? 0 : nilairetribusi;
				//console.log(tempNumber);
				console.log(nilairetribusi);
				document.getElementById("coba").value = akhir;
		}		
	}
	
	</script>
	<?php 
	
		@$NoTransRet		= htmlspecialchars($_POST['NoTransRet']);
		@$IDPerson			= htmlspecialchars($_POST['IDPerson']);
		@$KodePasar			= htmlspecialchars($_POST['KodePasar']);
		@$TglAktif			= htmlspecialchars($_POST['TglAktif']);
		@$IDLapak			= htmlspecialchars($_POST['IDLapak']);
		@$TanggalTrans		= htmlspecialchars($_POST['TanggalTrans']);
		@$Retribusi			= htmlspecialchars($_POST['Retribusi']);
		@$JmlHariDibayar	= htmlspecialchars($_POST['JmlHariDibayar']);
		@$NominalRetribusi	= htmlspecialchars($_POST['NominalRetribusi']);
		@$Keterangan		= htmlspecialchars($_POST['Keterangan']);
		@$IsTransfer		= htmlspecialchars($_POST['IsTransfer']);
		@$TglPenambahan     = date('Y-m-d', strtotime('+1 days', strtotime($TanggalTrans))); 
		@$TglMulaiDibayar	= $TanggalTrans != null ? $TglPenambahan : $TglAktif;
		@$TglPenambahan1     = date('Y-m-d', strtotime('+'.$JmlHariDibayar.' days', strtotime($TglAktif))); 
		@$TglPenambahan2     = date('Y-m-d', strtotime('+'.$JmlHariDibayar.' days', strtotime($TanggalTrans)));		
		@$TglSampaiDibayar	= $TanggalTrans != null || $TanggalTrans != '' ? $TglPenambahan2 : $TglPenambahan1;
		
	if(isset($_POST['Simpan'])){
		
		// echo $TanggalTrans."<br>".$TglMulaiDibayar."<br>".$TglSampaiDibayar."<br>".$JmlHariDibayar;
		// echo $TanggalTrans."<br>";
		echo $TglSampaiDibayar."<br>";
		
		$tahun = date('Y');
		$sql = "SELECT RIGHT(NoTransRet,7) AS kode FROM trretribusipasar WHERE NoTransRet LIKE '%$tahun%' ORDER BY NoTransRet DESC LIMIT 1";
		$res = mysqli_query($koneksi, $sql);
		if(mysqli_num_rows($res) > 0){
			$result = mysqli_fetch_array($res);
			if ($result['kode'] == null) {
				$kode = 1;
			} else {
				$kode = ++$result['kode'];
			}	
		}else{
			$kode = 1;
		}
		
		$bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		$kode_jadi  = 'TRP-' . $tahun . '-' . $bikin_kode ;
		echo $IsTransfer;
		
		// $SimpanData = @mysqli_query($koneksi, 
		// "INSERT INTO trretribusipasar (NoTransRet,TanggalTrans,JmlHariDibayar,TglMulaiDibayar,TglSampaiDibayar,NominalRetribusi,NominalDiterima,IsTransfer,Keterangan,IDPerson,KodePasar,UserName,IDLapak) VALUES ('$kode_jadi', '2019-11-22', '$JmlHariDibayar', '$TglMulaiDibayar', '$TglSampaiDibayar', '$Retribusi', '$NominalRetribusi', '$IsTransfer', '$Keterangan', '$IDPerson', '$KodePasar', '$login_id', '$IDLapak')"); 
		
		
		$SimpanData = @mysqli_query($koneksi, 
		"INSERT INTO trretribusipasar (NoTransRet,TanggalTrans,JmlHariDibayar,TglMulaiDibayar,TglSampaiDibayar,NominalRetribusi,NominalDiterima,IsTransfer,Keterangan,IDPerson,KodePasar,UserName,IDLapak) VALUES ('$kode_jadi', NOW(), '$JmlHariDibayar','$TglMulaiDibayar','$TglSampaiDibayar','$Retribusi','$NominalRetribusi','$IsTransfer','$Keterangan','$IDPerson','$KodePasar','$login_id','$IDLapak')"); 
				
		if ($SimpanData){
			InsertLog($koneksi, 'Tambah Data', 'Menambah Transaksi Retribusi Pasar', $login_id, $kode_jadi, 'Transaksi Retribusi Pasar');
			echo '<script language="javascript">document.location="TrRetribusiPasar.php";</script>';
		}else{
			echo '<script type="text/javascript">
			  sweetAlert({
				title: "Simpan Data Gagal!",
				text: " ",
				type: "error"
			  },
			  function () {
				window.location.href = "TrRetribusiPasar.php";
			  });
			  </script>';
		}
	}
	
	
	//Simpan Transaksi Penerimaan
	// if(isset($_POST['SimpanTransaksi'])){
		// cek apakah sudah ada permohonan
		// $cek = @mysqli_query($koneksi, "SELECT * from trtimbanganitem where NoTransaksi='$NoTransaksi'");
		// $num = @mysqli_num_rows($cek);
		// echo $num;
		
		// if($num <= 0){
			// echo '<script type="text/javascript">swal( "Item Timbangan Belum Ada!", " Silahkan inputkan item timbangan user ", "error" ); </script>';
		// }else{ 
			// update 
			// $query = mysqli_query($koneksi,"UPDATE tractiontimbangan SET  StatusTransaksi='PENERIMAAN', NoSKRD='$NoSKRD', TotalRetribusi='$TotalRetribusi' WHERE NoTransaksi='$NoTransaksi' and IDPerson='$IDPerson'");
			// if ($query){
				// InsertLog($koneksi, 'Edit Data', 'Transaksi Penerimaan Timbangan User', $login_id, $NoTransaksi, 'Transaksi Penerimaan Timbangan User');
				// echo '<script language="javascript">document.location="TrTerimaTimbangan.php";</script>';
			// }else{
				// echo '<script type="text/javascript">
				  // sweetAlert({
					// title: "Simpan Data Gagal!",
					// text: " ",
					// type: "error"
				  // },
				  // function () {
					// window.location.href = "TrTerimaTimbangan.php";
				  // });
				  // </script>';
			// }
		// }
	// }
	
	//Hapus Transaksi Penerimaan
	if(base64_decode(@$_GET['aksi'])=='HapusTransaksi'){
		
		$query = mysqli_query($koneksi,"delete from trretribusipasar WHERE NoTransRet='".htmlspecialchars(base64_decode($_GET['tr']))."' ");
		if($query){
			InsertLog($koneksi, 'Hapus Data', 'Menghapus Data Transaksi Retribusi Pasar', $login_id, base64_decode(@$_GET['tr']), 'Transaksi Retribusi Pasar');
			echo '<script language="javascript">document.location="TrRetribusiPasar.php"; </script>';
		}else{
			echo '<script type="text/javascript">
					  sweetAlert({
						title: "Hapus Data Gagal!",
						text: " Data Telah Digunakan Dalam Berbagai Transaksi ",
						type: "error"
					  },
					  function () {
						window.location.href = "TrRetribusiPasar.php";
					  });
					  </script>';
		}
	}
	
	?>
  </body>
</html>