<?php
include '../admin/akses.php';
$Page = 'index';
include '../library/tgl-indo.php';
//data surat
function ResultData($Jenis,$Tabel,$koneksi){
	if ($Tabel=='VerifikasiUser'){
		$Query = mysqli_query($koneksi,"SELECT IDPerson FROM mstperson where IsVerified=b'0'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;
	}elseif($Tabel == 'Jumlah Pasar'){
		$Query = mysqli_query($koneksi,"SELECT KodePasar FROM mstpasar");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;
	}elseif($Tabel == 'Jumlah Lapak'){
		$Query = mysqli_query($koneksi,"SELECT IDLapak FROM lapakpasar");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
	}elseif($Tabel == 'Lapak Terpakai'){
		$Query = mysqli_query($koneksi,"SELECT IDLapak FROM lapakperson");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
		
	}elseif($Tabel == 'Lapak Kosong'){
		$lapak = mysqli_query($koneksi,"SELECT IDLapak FROM lapakpasar");
		$ada = mysqli_num_rows($lapak);
		$tersedia = $ada;	
		
		$Query = mysqli_query($koneksi,"SELECT IDLapak FROM lapakperson");
		$RowData = mysqli_num_rows($Query);
		$terpakai = $RowData;	
		
		$Data = $tersedia-$terpakai;
		
	}elseif($Tabel == 'Penerimaan'){
		$Query = mysqli_query($koneksi,"SELECT NoTransaksi FROM tractiontimbangan where StatusTransaksi='$Jenis'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
	}elseif($Tabel == 'Proses'){
		$Query = mysqli_query($koneksi,"SELECT NoTransaksi FROM tractiontimbangan where StatusTransaksi='$Jenis'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
		
	}elseif($Tabel == 'Proses'){
		$Query = mysqli_query($koneksi,"SELECT NoTransaksi FROM tractiontimbangan where StatusTransaksi='$Jenis'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
	}elseif($Tabel == 'Pengambilan'){
		$Query = mysqli_query($koneksi,"SELECT NoTransaksi FROM tractiontimbangan where StatusTransaksi='$Jenis'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
	}elseif($Tabel == 'Pembayaran'){
		$Query = mysqli_query($koneksi,"SELECT NoTransaksi FROM tractiontimbangan where StatusTransaksi='$Jenis' and IsDibayar=b'1'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
	}elseif($Tabel == 'Distributor'){
		$Query = mysqli_query($koneksi,"SELECT IDPerson  FROM mstperson WHERE JenisPerson LIKE '%PupukSub%'  AND IsVerified=b'1' and IsPerusahaan=b'1' AND ID_Distributor IS NULL");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;
	}elseif($Tabel == 'Pupuk'){
		$Query = mysqli_query($koneksi,"SELECT KodeBarang FROM mstpupuksubsidi WHERE IsAktif=b'1'");
		$RowData = mysqli_num_rows($Query);
		$Data = $RowData;	
		
	} 
	// $sql = @mysqli_query($koneksi, "SELECT a.NoTransIzin FROM iz_reklame a JOIN progressizin b ON b.NoTransIzin = a.NoTransIzin WHERE  a.Status='$Jenis' GROUP BY a.NoTransIzin"); 
	// $nums = @mysqli_num_rows($sql); 
	// return($nums);
	
	return($Data);
} 

$date = date('Y-m-d');
$date_minus_sebulan = date('Y-m-d', strtotime($date.' -1 month'));

$TanggalMulai = isset($_GET['tglmulai']) ? mysqli_real_escape_string($koneksi,$_GET['tglmulai']) : $date_minus_sebulan;
$TanggalSelesai = isset($_GET['tglselesai']) ? mysqli_real_escape_string($koneksi,$_GET['tglselesai']) : $date;

$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';





?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php include '../admin/title.php';?>
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
    <?php include '../admin/style.php';?>
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../komponen/css/custom.css">
	
	
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
	</style>
  </head>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php 
	  include 'header.php'; ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php include 'menu.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Dashboard</h2>
            </div>
          </header>
		  
          <!-- Dashboard Counts Section-->
		  <?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'TIMBANGAN') { ?> 
		  <!-- statistik untuk metrologi -->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="card-header d-flex align-items-center">
				  <h3 class="h4">Statistik Tera Timbangan</h3>
				</div>
				  <div class="row bg-white has-shadow">
				
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-blue"><i class="fa fa-check"></i></div>
                    <div class="title"><span>Penerimaan Timbangan</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-blue"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo ResultData('PRAWAITING','Penerimaan',$koneksi); ?></strong></div>
                  </div>
                </div>
				<!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="fa fa-cog"></i></div>
                    <div class="title"><span>Proses Sidang Tera UTTP</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo ResultData('PROSES SIDANG','Proses',$koneksi);?></strong></div>
                  </div>
                </div>
				<!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-orange"><i class="fa fa-balance-scale"></i></div>
                    <div class="title"><span>Pengambilan Timbangan</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo ResultData('SELESAI','Pengambilan',$koneksi);?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><i class="fa fa-dollar"></i></div>
                    <div class="title"><span>Penerimaan Pembayaran</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php echo ResultData('SELESAI','Pembayaran',$koneksi);?></strong></div>
                  </div>
                </div>
              </div>
				
            </div>
          </section>
		  <?php } ?>

		  <?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'RETRIBUSI PASAR') { ?> 
		    <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="card-header d-flex align-items-center">
				  <h3 class="h4">Statistik Data Pasar</h3>
				</div>
				  <div class="row bg-white has-shadow">
				
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-check"></i></div>
                    <div class="title"><span>Jumlah Pasar</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData('','Jumlah Pasar',$koneksi); ?></strong></div>
                  </div>
                </div>
				<!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-green"><i class="fa fa-sitemap"></i></div>
                    <div class="title"><span>Lapak Pasar</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-green"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData('','Jumlah Lapak',$koneksi); ?></strong></div>
                  </div>
                </div>
				<!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-blue"><i class="fa fa-handshake-o"></i></div>
                    <div class="title"><span>Lapak Terpakai</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-blue"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData('','Lapak Terpakai',$koneksi); ?></strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="fa fa-window-close"></i></div>
                    <div class="title"><span>Lapak Kosong</span>
                      <div class="progress">
                        <div role="progressbar" style="width: 100%; height: 4px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong><?php 
					echo ResultData('','Lapak Kosong',$koneksi); ?></strong></div>
                  </div>
                </div>
              </div>
				
            </div>
          </section><br>
			  <?php } ?>
			<?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'HARGA PASAR') { ?> 
		 <section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">		
							<form action="">			
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Pencarian</label>
                                            <select class="form-control" name="psr">
                                                <!-- <option class="form-control" value="" selected>Semua Pasar</option> -->
                                                <?php 
                                                $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                                $res_p = $koneksi->query($sql_p);
                                                while ($row_p = $res_p->fetch_assoc()) {
                                                    if($KodePasar == $row_p['KodePasar']){
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                                    }else{
                                                        if(!isset($KodePasar) || strlen($KodePasar) < 1){
                                                            $KodePasar = $row_p['KodePasar'];
                                                        }
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Nama Bahan Pokok</label>
                                            <select class="form-control" name="brg">
                                            <?php
                                                $sql_b = "SELECT KodeBarang, NamaBarang
                                                    FROM mstbarangpokok 
                                                    WHERE IsAktif = '1'";
                                                $res_b = $koneksi->query($sql_b);
                                                while ($row_b = $res_b->fetch_assoc()) {
                                                    if($KodeBarang == $row_b['KodeBarang']){
                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'" selected>'.$row_b['NamaBarang'].'</option>';
                                                    }else{
                                                        if(!isset($KodeBarang) || strlen($KodeBarang) < 1){
                                                            $KodeBarang = $row_b['KodeBarang'];
                                                        }
                                                        echo '<option class="form-control" value="'.base64_encode($row_b['KodeBarang']).'">'.$row_b['NamaBarang'].'</option>';
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Tanggal Mulai</label>
                                            <input  class="form-control" id="tglmulai" name="tglmulai" type="text" value="<?php echo $TanggalMulai; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group ">
                                            <label class="form-control-label">Tanggal Selesai</label>
                                            <div class="input-group">
                                                <input  class="form-control" id="tglselesai" name="tglselesai" type="text" value="<?php echo $TanggalSelesai; ?>">
                                                <span class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">Cari</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if(isset($KodeBarang) && strlen($KodeBarang) > 0 && isset($KodePasar) && strlen($KodePasar) > 0 ): ?>
                                    <?php 
                                    $sql_lap = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin, r.HargaBarang, IFNULL(r.Ketersediaan, 0) AS Ketersediaan, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar, DATE(r.Tanggal) AS FormatTanggal
                                    FROM reporthargaharian r
                                    INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
                                    INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
                                    LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                    LEFT JOIN (
                                        SELECT *
                                        FROM reporthargaharian k
                                        ORDER BY k.Tanggal DESC
                                    ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
                                    WHERE r.KodePasar = '".$KodePasar."' AND r.KodeBarang = '".$KodeBarang."' AND 
                                    (
                                        (
                                            DATE(r.Tanggal) <= DATE('".$TanggalMulai."') AND 
                                            DATE(r.Tanggal) >= DATE('".$TanggalSelesai."')
                                        ) OR 
                                        (
                                            DATE(r.Tanggal) >= DATE('".$TanggalMulai."') AND 
                                            DATE(r.Tanggal) <= DATE('".$TanggalSelesai."')
                                        )
                                    )
                                    GROUP BY r.Tanggal
                                    ORDER BY r.Tanggal DESC";
                                    $res_lap = $koneksi->query($sql_lap);
                                    $lap_ = array();
                                    while ($row_lap = $res_lap->fetch_assoc()) {
                                        array_push($lap_, $row_lap);
                                    }
                                    $label_array = array();
                                    $period = new DatePeriod(
                                        new DateTime($TanggalMulai),
                                        new DateInterval('P1D'),
                                        new DateTime(date('Y-m-d', strtotime($TanggalSelesai.' 1 day')))
                                    );
                                    ?>
                                    <div class="col-lg-12 col-md-12" style="margin:10px 0px;">
                                        <ul class="nav nav-tabs">
											<li class="nav-item">
                                                <a href="#home-pills" data-toggle="tab" <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>></a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <div class="col-lg-12 col-md-12 mb-20">
                                        <div class="tab-content">
											 <div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==1){ echo 'in active show'; }?>" id="home-pills">
                                                <div class="table-responsive">
                                                    <table class="table table-stripped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left">No.</th>
                                                                <th class="text-left">Tanggal</th>
                                                                <th class="text-left">Satuan</th>
                                                                <th class="text-right">Harga Konsumen</th>
                                                                <th class="text-right">Harga Produsen</th>
                                                                <th class="text-center">Ketersediaan</th>
                                                                <th class="text-left">Petugas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                            $nourut = 1;
                                                            $tgl_lap = "";
                                                            foreach($lap_ as $lap):
                                                                if($tgl_lap !== $lap['FormatTanggal']){
                                                                    echo '<tr style="background:#f7f7f7;">
                                                                        <td></td>
                                                                        <td colspan="6">'.date('d F Y', strtotime($lap['FormatTanggal'])).'</td>
                                                                    </tr>';
                                                                    $tgl_lap = $lap['FormatTanggal'];
                                                                }
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $nourut++; ?></td>
                                                                    <td><?php echo date('H:i:s', strtotime($lap['Tanggal']));;?></td>
                                                                    <td><?php echo $lap['Satuan']; ?></td>
                                                                    <td class="text-right"><?php echo 'Rp.'.number_format($lap['HargaBarang']); ?></td>
                                                                    <td class="text-right"><?php echo 'Rp.'.number_format($lap['HargaProdusen']); ?></td>
                                                                    <td class="text-center"><?php echo number_format($lap['Ketersediaan']); ?></td>
                                                                    <td class="text-left"><?php echo $lap['UserName']; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    <?php //echo $sql_lap; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-lg-12">
                                        <label for="">Tidak ada data</label>
                                    </div>
                                    <?php endif; ?>
                                </div>
							</form>                            						
						</div>
					</div>
				</div>
			</section>
		   <?php } ?>
		   
		   <?php if ($JenisLogin == 'SUPER ADMIN'  OR $JenisLogin == 'PUPUK SUBSIDI') { ?> 
		   <section class="dashboard-header  no-padding-bottom">
            <div class="container-fluid">
              <div class="row">
                <!-- Statistics -->
                <div class="statistics col-lg-6 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow ">
                    <div class="icon bg-violet"><i class="fa fa-truck"></i></div>
                    <div class="text"><strong><?php echo ResultData('','Distributor',$koneksi); ?></strong><br><small>Jumlah Data Distributor</small></div>
                  </div>
                </div>
				<div class="statistics col-lg-6 col-12">
                  <div class="statistic d-flex align-items-center bg-white has-shadow " >
                    <div class="icon bg-orange"><i class="fa fa-gavel"></i></div>
                    <div class="text"><strong><?php echo ResultData('','Pupuk',$koneksi); ?></strong><br><small>Data Jenis Pupuk</small></div>
                  </div>
                </div>
              </div>
            </div>
          </section>
		  
		  <section class="dashboard-counts no-padding-bottom ">
            <div class="container-fluid">
              <div class="card-header d-flex align-items-center">
				  <h3 class="h4">Statistik Stok Pupuk</h3>
				</div>
			  <div class="row bg-white has-shadow">
                <!-- Item -->
                <div class="col-xl-12 col-sm-6 table-responsive">
                  <table class="table">
				  <thead>
					<tr>
					  <th>Nama Pupuk</th>
					  <th>Satuan</th>
					  <th>Jumlah Stok</th>
					</tr>
				  </thead>
				  <tbody>
					<?php
					//AND b.NoTrMohon='$No_Transaksi'
					$no =1;
					$sql_pp = @mysqli_query($koneksi, "SELECT b.NamaBarang, b.Keterangan, (IFNULL(s.JumlahMasuk, 0)-IFNULL(s.JumlahKeluar, 0)) AS StokSekarang
					FROM mstpupuksubsidi b
					LEFT JOIN (
						SELECT s.KodeBarang, SUM(s.JumlahMasuk) as JumlahMasuk, SUM(s.JumlahKeluar) as JumlahKeluar
						FROM sirkulasipupuk s
						GROUP by s.KodeBarang
					) s ON s.KodeBarang = b.KodeBarang
					WHERE b.IsAktif=b'1'
					GROUP by b.KodeBarang"); 
					while($data_pp = @mysqli_fetch_array($sql_pp)){
						
					?>
					<tr>
					  <td><?php echo $data_pp['NamaBarang'];?></td>
					  <td align="center"><?php echo $data_pp['Keterangan'];?></td>
					  <td align="center"><?php echo number_format($data_pp['StokSekarang'], 2);?></td>
					</tr>
					<?php } ?>
				  </tbody>
				</table>
                </div>
				
              </div>
            </div>
          </section>
		  
		  
		  
		   <?php } ?>
		   
		   <section class="client no-padding-top">
            <div class="container-fluid">
              <div class="row">
				<!--<div class="col-lg-3">
					<div class="statistic d-flex align-items-center bg-white has-shadow">
						<div class="icon bg-violet"><i class="fa fa-users"></i></div>
						<div class="text"><strong><?php echo ResultData(null,'VerifikasiUser',$koneksi);?></strong><br><small><a href="VefUser.php" style="text-decoration:none"> Verifikasi  User</a></small></div>
					</div>
                </div>-->
				
				
				<!--<div class="col-lg-3">
					<div class="statistic d-flex align-items-center bg-white has-shadow">
						<div class="icon bg-green"><i class="fa fa-users"></i></div>
						<div class="text"><strong><?php echo ResultData(null,'TotalPegawaiAnjab',$koneksi);?></strong><br><small>Total Pegawai (Anjab)</small></div>
					</div>
                </div>-->
              </div>
            </div>
		   </section>
        </div>
      </div>
    </div>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
	
    <!-- JavaScript files-->
    <script src="../komponen/vendor/jquery/jquery.min.js"></script>
    <script src="../komponen/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="../komponen/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../komponen/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../komponen/vendor/chart.js/Chart.min.js"></script>
    <script src="../komponen/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../komponen/js/charts-home.js"></script>
    <!-- Main File-->
    <script src="../komponen/js/front.js"></script>
	<script>

var labels = [];
var datea = <?php echo json_encode( $label_array); ?>;
for (let i = 0; i < datea.length; i++) {
    const label = formatDate(datea[i].date);
    labels.push(label);
}

var HargaKonsumenChart = document.getElementById("HargaKonsumenChart");
var HargaProdusenChart = document.getElementById("HargaProdusenChart");
var KetersediaanChart = document.getElementById("KetersediaanChart");

Chart.defaults.global.defaultFontFamily = "Lato";
Chart.defaults.global.defaultFontSize = 12;

var hargaKonsumenData = {
  labels: labels,
  datasets: [{
    label: "Harga Barang Konsumen",
    data: <?php echo json_encode( $hrg_konsumen); ?>,
    lineTension: 0,
    fill: true,
    borderColor: 'rgb(255, 193, 7)',
    backgroundColor: 'transparent',
    pointBorderColor: 'rgb(255, 193, 7)',
    pointBackgroundColor: 'rgb(255, 193, 7)',
    pointRadius: 5,
    pointHoverRadius: 10,
    pointHitRadius: 30,
    pointBorderWidth: 2,
    pointStyle: 'rectRounded'
  }]
};

var hargaProdusenData = {
  labels: labels,
  datasets: [{
    label: "Harga Produsen",
    data: <?php echo json_encode( $hrg_produsen); ?>,
    lineTension: 0,
    fill: true,
    borderColor: 'rgb(40, 167, 69)',
    backgroundColor: 'transparent',
    pointBorderColor: 'rgb(40, 167, 69)',
    pointBackgroundColor: 'rgb(40, 167, 69)',
    pointRadius: 5,
    pointHoverRadius: 10,
    pointHitRadius: 30,
    pointBorderWidth: 2,
    pointStyle: 'rectRounded'
  }]
};

var ketersediaanData = {
  labels: labels,
  datasets: [{
    label: "Jumlah Ketersediaan",
    data: <?php echo json_encode( $ketersediaan); ?>,
    lineTension: 0,
    fill: true,
    borderColor: 'rgb(0, 123, 255)',
    backgroundColor: 'transparent',
    pointBorderColor: 'rgb(0, 123, 255)',
    pointBackgroundColor: 'rgb(0, 123, 255)',
    pointRadius: 5,
    pointHoverRadius: 10,
    pointHitRadius: 30,
    pointBorderWidth: 2,
    pointStyle: 'rectRounded'
  }]
};

var chartOptions = {
    legend: {
        display: true,
        position: 'bottom'
    },
    scales: {
        yAxes: [{
            ticks: {
                suggestedMin: 0
            }
        }]
    }
};

var lineChart1 = new Chart(HargaKonsumenChart, {
  type: 'line',
  data: hargaKonsumenData,
  options: chartOptions
});

var lineChart2 = new Chart(HargaProdusenChart, {
  type: 'line',
  data: hargaProdusenData,
  options: chartOptions
});

var lineChart3 = new Chart(KetersediaanChart, {
  type: 'line',
  data: ketersediaanData,
  options: chartOptions
});

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();
    
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun", "Jul",
        "Aug", "Sept", "Okt",
        "Nov", "Des"
    ];

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [day, monthNames[month-1], year].join('/');
}

</script>
  
  </body>
</html>