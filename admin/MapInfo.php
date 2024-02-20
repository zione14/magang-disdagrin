<?php
include 'akses.php';
// $Page = 'Security';
// $SubPage = 'AksesLevel';
// $fitur_id = 55;
// include '../library/lock-menu.php'; 
@include '../library/tgl-indo.php';
$KodePasar = htmlspecialchars(base64_decode($_GET['p']));
$IDLapak   = htmlspecialchars(base64_decode($_GET['l']));
$NamaPasar = htmlspecialchars(base64_decode($_GET['n']));
$Page = '';
$SubPage = 'Map'.$NamaPasar;
$sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet, e.NamaPerson, e.IDPerson, c.TanggalTerakhirBayar, d.LokasiFile
  FROM lapakpasar l 
  LEFT JOIN (
    SELECT p.NamaPerson, lp.IDPerson, lp.KodePasar, lp.IDLapak
    FROM lapakperson lp 
    JOIN mstperson p ON lp.IDPerson=p.IDPerson
    WHERE lp.IsAktif=b'1'
  ) e ON e.KodePasar = l.KodePasar AND e.IDLapak = l.IDLapak
  LEFT JOIN (
    SELECT t.NoTransRet, MAX(t.TanggalTrans) as TanggalTerakhirBayar, t.KodePasar, t.IDLapak, t.IDPerson
    FROM trretribusipasar t
    JOIN lapakperson lp ON t.IDLapak=lp.IDLapak AND t.KodePasar=lp.KodePasar
    WHERE lp.IsAktif=b'1'
    GROUP by t.KodePasar, t.IDLapak
  ) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
  LEFT JOIN (
    SELECT d.KodePasar, d.IDLapak, d.JenisDokumen, d.LokasiFile
    FROM dokumenlapak d
    WHERE d.JenisDokumen='FOTO'
  ) d ON d.KodePasar = l.KodePasar AND d.IDLapak = l.IDLapak
  WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$IDLapak' ORDER BY IDLapak ASC";
  $oke = $koneksi->prepare($sql_lapakpr);
  $oke->execute();
  $res_lapakpr = $oke->get_result();
  $row_lapakpr = $res_lapakpr->fetch_assoc();
  $blok = str_replace(" ", "", $row_lapakpr['BlokLapak']);
	if($NamaPasar == 'CitraNiaga'){
		$nmpasar = 'CITRANIAGA';
	}else{
		$nmpasar = $NamaPasar;
	}
	$filename = '../images/Dokumen/Pasar/'.strtoupper($nmpasar).'_'.$blok.'_'.$row_lapakpr['NomorLapak'].'.*';
	$result = glob ($filename);
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
    <!-- Datepcker -->
	<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
   <style>
		 th {
			text-align: center;
		}
		.Zebra_DatePicker_Icon_Wrapper{
			width:100% !important;
		}
		
		.Zebra_DatePicker_Icon {
			top: 11px !important;
			right: 12px;
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
              <h2 class="no-margin-bottom">Lapak Pasar <?=$NamaPasar?></h2>
            </div>
          </header>
          <!-- Dashboard Counts Section-->
         <section class="tables"> 
            <div class="container-fluid">
                <div class="col-lg-12">
				  <div class="card">
					<div class="card-header d-flex align-items-center">
					  <h3 class="h4">Lapak Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h3>
					</div>
					<div class="card-body">
				<!-- 	<div class="col-lg-4 offset-lg-8">
						<form method="post" action="">
							<div class="form-group input-group">						
								<input type="text" name="keyword" class="form-control" placeholder="Tanggal..." id="time1" value="<?php echo @$_REQUEST['keyword']; ?>">
								<span class="input-group-btn">
									<button class="btn btn-success" type="submit">Cari</button>
								</span>
							</div>
						</form>
					</div> -->
					<div class="col-lg-12 col-md-12">
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a href="#informasi" data-toggle="tab" class="nav-link <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>"><span>Informasi Lapak</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#histori" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==1){ echo 'in active show'; }?>"><span>Histori Pembayaran</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#uttp" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==2){ echo 'in active show'; }?>"><span>Keberadan UTTP</span></a>&nbsp;
							</li>
							<li class="nav-item">
								<a href="#pemilik" data-toggle="tab" class="nav-link <?php if(@$_GET['view']==3){ echo 'in active show'; }?>"><span>Histori Pemilik</span></a>&nbsp;
							</li>
						</ul>
                    </div>
					<div class="col-lg-12 col-md-12 mb-20">

						<div class="tab-content">
							<div class="tab-pane fade <?php if(!isset($_GET['view']) || @$_GET['view']==0){ echo 'in active show'; }?>" id="informasi">
								
								<br><h4>Informasi Lapak</h4><br>
								<div class="row">
				                  <div class="col-lg-4">
				                    <!-- <img id="image-preview-1" width="100%" height="auto" <?php echo isset($row_lapakpr['LokasiFile']) && $row_lapakpr['LokasiFile'] != '' ? 'src="../images/Dokumen/Pasar/'.$row_lapakpr['LokasiFile'].'"' : 'src="../images/Assets/thumb_noimage.png"'; ?> /> -->
				                    <img id="image-preview-1" width="100%" height="auto" <?php echo isset($row_lapakpr['LokasiFile']) && $row_lapakpr['LokasiFile'] != '' ? 'src="../images/Dokumen/Pasar/'.$row_lapakpr['LokasiFile'].'"' : @file_exists($result[0]) ?  'src="'.$result[0].'"' : 'src="../images/Assets/thumb_noimage.png"'; ?> >
				                  </div>
				                  <div class="col-lg-8">
				                    <div class="table-responsive">  
				                      <table class="table table-striped">
				                        <thead>
				                          <tr>
				                          <td  style="text-align: left;">Kode Lapak</td>
				                          <td>:</td>
				                          <td style="text-align: left;"><?=$IDLapak?></td>
				                        </tr>
				                        <tr>
				                          <td style="text-align: left;">Nama Lapak</td>
				                          <td>:</td>
				                          <td style="text-align: left;">Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></td>
				                        </tr>
				                        <tr>
				                          <td style="text-align: left;">Nama Pemilik Lapak</td>
				                          <td>:</td>
				                          <td style="text-align: left;"><?=$row_lapakpr['NamaPerson']?></td>
				                        </tr>
				                        <tr>
				                          <td style="text-align: left;">Tanggal Terakhir Bayar</td>
				                          <td>:</td>
				                          <td style="text-align: left;"><?=isset($row_lapakpr['TanggalTerakhirBayar']) && $row_lapakpr['TanggalTerakhirBayar'] != NULL ? TanggalIndo($row_lapakpr['TanggalTerakhirBayar']) : ''?></td>
				                        </tr>
				                        <tr>
				                          <td style="text-align: left;">Nominal Retribusi</td>
				                          <td>:</td>
				                          <td style="text-align: left;"><?=number_format($row_lapakpr['Retribusi'])?></td>
				                        </tr>
				                        </thead>
				                        <tbody>
				                        
				                        </tbody>
				                      </table>
				                    </div>
				                  </div>
				                </div>
                            </div>
							<div class="tab-pane fade <?php if(@$_GET['view']==1){ echo 'in active show'; }?>" id="histori">
								<form action="MapInfo.php?l=<?=base64_encode($IDLapak)?>&p=<?=base64_encode($KodePasar)?>&n=<?=base64_encode($NamaPasar)?>&view=1" method="post">
								  <div class="form-row align-items-center">
								    <div class="col-auto">
								      <input type="text" name="Tanggal1" class="form-control" id="time1" value="<?php echo @$_REQUEST['Tanggal1']; ?>" placeholder="Tanggal Awal" required>
								    </div>
								    <div class="col-auto">
								      <input type="text" name="Tanggal2" class="form-control" id="time2" value="<?php echo @$_REQUEST['Tanggal2']; ?>" placeholder="Tanggal Akhir" required>
								    </div>
								    
								    <div class="col-auto" style="padding-top: 10px;">
								      <button type="submit" class="btn btn-success mb-2">Refresh</button>
								    </div>
								  </div>
								</form>
								<div class="table-responsive">  
									<table class="table">
									  <thead>
										<tr>
	                                      <th>No </th>
	                                      <th>Nama Person</th>
	                                      <!--<th>Uraian</th>-->
	                                      <th>Alamat </th>
	                                      <th>Lapak Person </th>
	                                      <th>Nilai Dibayar</th>
	                                    </tr>
									  </thead>
										<?php
                                        include '../library/pagination1.php';
                                        $Day = date('Y-m');
                                        $psr  = @$_REQUEST['KodePasar'];
                                        $tgl1 = @$_REQUEST['Tanggal1'];
                                        $tgl2 = @$_REQUEST['Tanggal2'];
                                        
                                        $reload = "MapInfo.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&Tanggal2=$tgl2&Tanggal1=$tgl1&view=1";
                                        $sql =  "SELECT a.*, b.NamaPerson, 
                                            b.AlamatLengkapPerson,d.BlokLapak,d.NomorLapak,c.NamaPasar,f.Keterangan as KetLapak 
                                            FROM trretribusipasar a 
                                            JOIN mstperson b ON a.IDPerson=b.IDPerson 
                                            join mstpasar c on a.KodePasar=c.KodePasar join 
                                            lapakpasar d on (d.KodePasar,d.IDLapak)=(a.KodePasar,a.IDLapak) 
                                            join lapakperson f on (f.KodePasar,f.IDLapak,f.IDPerson)=(a.KodePasar,a.IDLapak,a.IDPerson) 
                                            WHERE   a.KodePasar='$KodePasar' AND a.IDLapak='$IDLapak' ";
                                        
                                    
                                        if((isset($_REQUEST['Tanggal1']) && $_REQUEST['Tanggal2']<>"")){
                                            $sql .= " AND (date_format(a.TanggalTrans, '%Y-%m-%d') BETWEEN '".@$_REQUEST['Tanggal1']."' AND '".@$_REQUEST['Tanggal2']."') ";
                                        }
                                        
                                        $sql .="  ORDER BY a.TanggalTrans DESC";
                                        $oke1 = $koneksi->prepare($sql);
                                        $oke1->execute();
                                        $result = $oke1->get_result();  
                                        
                                        //pagination config start
                                        $rpp = 20; // jumlah record per halaman
                                        $page = intval(@$_GET["page"]);
                                        if($page<=0) $page = 1;  
                                        $tcount = mysqli_num_rows($result);
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
                                            $data = mysqli_fetch_array($result);
                                        ?>
                                        <tr class="odd gradeX">
                                            <td width="50px">
                                                <?php echo ++$no_urut;?> 
                                            </td>
                                            <td align="left">
                                                <strong><?php echo @$data ['NamaPerson']; ?></strong><br>
                                                <?php echo TanggalIndo(@$data ['TanggalTrans']); ?>
                                                <p>No Transaksi : <?php echo @$data ['NoTransRet']; ?></p>
                                            </td>
                                            <td>
                                                <?php echo $data['KetLapak']."<br>".$data['NamaPasar']; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo @$data ['NamaPasar']."</strong> ".$data['BlokLapak']." ".$data['NomorLapak']."<br>".$data['IDLapak']; ?>
                                            </td>
                                            <td align="right">
                                                <?php echo "<strong> Rp ".number_format($data ['NominalDiterima'])."</strong>"; ?>
                                                <?php  $Jumlah[] = $data['NominalDiterima']; ?>
                                            </td>
                                        </tr>
                                        <?php
                                            $i++; 
                                            $count++;
                                        }
                                        
                                        if($tcount==0){
                                            echo '
                                            <tr>
                                                <td colspan="8" align="center">
                                                    <strong>Data Tidak Ada</strong>
                                                </td>
                                            </tr>
                                            ';
                                        }
                                        ?>
                                    </tbody>
									</table>
									<div><?php echo paginate_one($reload, $page, $tpages); ?></div>
								</div>
                            </div>
                            <div class="tab-pane fade <?php if(@$_GET['view']==2){ echo 'in active show'; }?>" id="uttp">
                            	<form action="MapInfo.php?l=<?=base64_encode($IDLapak)?>&p=<?=base64_encode($KodePasar)?>&n=<?=base64_encode($NamaPasar)?>&view=2" method="post">
								  <div class="form-row align-items-center">
								    <div class="col-auto">
								      <input type="text" name="keyword" class="form-control" value="<?php echo @$_REQUEST['keyword']; ?>" placeholder="Nama UTTP">
								    </div>
								    <div class="col-auto" style="padding-top: 10px;">
								      <button type="submit" class="btn btn-primary mb-2">Refresh</button>
								    </div>
								  </div>
								</form>
								<div class="table-responsive">  
									<table class="table">
									  <thead>
	                                    <tr>
	                                      <th>No</th>
	                                      <th>Nama UTTP</th>
	                                      <th>Jenis UTTP</th>
	                                      <th>Tarif Retribusi</th>                                    
	                                      <th>Alamat</th>
	                                      <th>Status</th>
	                                    </tr>
	                                  </thead>
										<?php

										$Day = date('Y-m');
                                        @$keyword=$_REQUEST['keyword'];
                                        $reload = "MapInfo.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&keyword=$keyword&view=2";
                                        
                                        $sql =  "SELECT b.IDTimbangan,c.JenisTimbangan,c.NamaTimbangan,b.NamaTimbangan as RealName,a.NamaPerson,a.IDPerson,d.NamaKelas,e.NamaUkuran,e.RetribusiDikantor,e.RetribusiDiLokasi,f.NamaLokasi,f.AlamatLokasi,b.StatusUTTP 
                                            FROM mstperson a 
                                            join timbanganperson b on a.IDPerson=b.IDPerson 
                                            join msttimbangan c on b.KodeTimbangan=c.KodeTimbangan 
                                            join kelas d on (b.KodeTimbangan,b.KodeKelas)=(d.KodeTimbangan,d.KodeKelas) 
                                            join detilukuran e on (b.KodeTimbangan,b.KodeKelas,b.KodeUkuran)=(e.KodeTimbangan,e.KodeKelas,e.KodeUkuran) 
                                            join lokasimilikperson f on (f.KodeLokasi,f.IDPerson)=(b.KodeLokasi,b.IDPerson) 
                                            WHERE f.KodePasar='".$row_lapakpr['KodePasar']."' and  a.IsVerified=b'1' and a.IDPerson='".$row_lapakpr['IDPerson']."'   ";
                                        
                                    
                                      
	                                        if((isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"")){
	                                            $sql .= " AND c.NamaTimbangan LIKE '%".htmlspecialchars($keyword)."%' ";
	                                        }
	                                        
	                                        $sql .="  GROUP BY b.IDTimbangan ASC";
                                          $oke = $koneksi->prepare($sql);
                                          $oke->execute();
	                                        $result = $oke->get_result();  
	                                        
	                                        //pagination config start
	                                        $rpp = 20; // jumlah record per halaman
	                                        $page = intval(@$_GET["page"]);
	                                        if($page<=0) $page = 1;  
	                                        $tcount = mysqli_num_rows($result);
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
	                                            <td><?php echo "Dikantor : Rp ".number_format($data ['RetribusiDikantor'])."<br>"; ?>
	                                                <?php echo "Dilokasi : Rp ".number_format($data ['RetribusiDiLokasi']).""; ?></td>
	                                            <td><?php echo $data['NamaLokasi']."<br> ".$data['AlamatLokasi']; ?></td>
	                                            <td><?php echo $data['StatusUTTP'] == 'Aktif' ? '<span class="text-success">AKTIF</span>' : '<span class="text-danger">NONAKTIF</span>'; ?></td>
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
                            <div class="tab-pane fade <?php if(@$_GET['view']==3){ echo 'in active show'; }?>" id="pemilik">
                            	<form action="MapInfo.php?l=<?=base64_encode($IDLapak)?>&p=<?=base64_encode($KodePasar)?>&n=<?=base64_encode($NamaPasar)?>&view=3" method="post">
								  <div class="form-row align-items-center">
								    <div class="col-auto">
								      <input type="text" name="key" class="form-control" value="<?php echo @$_REQUEST['key']; ?>" placeholder="Nama Person">
								    </div>
								    <div class="col-auto" style="padding-top: 10px;">
								      <button type="submit" class="btn btn-info mb-2">Refresh</button>
								    </div>
								  </div>
								</form>
								<div class="table-responsive">  
									<table class="table">
									  <thead>
										<tr>
	                                      <th>No </th>
	                                      <th>Nama Pemilik</th>
	                                      <th>Alamat</th>
	                                      <th>Tanggal Mulai Sewa</th>
	                                    </tr>
									  </thead>
										<?php
										// mengatur variabel reload dan sql
										@$key=$_REQUEST['key'];
                                        $reload = "MapInfo.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&key=$key&view=3";
										$Day = date('Y-m');
                                        $sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,d.NamaPerson,d.AlamatLengkapPerson,d.IDPerson, c.TglAktif  
                                        FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar 
                                        join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) 
                                        join mstperson d on c.IDPerson=d.IDPerson 
                                        where a.KodePasar='$KodePasar' AND a.IDLapak='$IDLapak'";
                                        
                                    
                                        if((isset($_REQUEST['key']) && $_REQUEST['key']<>"")){
                                            $sql .= " AND (a.BlokLapak LIKE '%$key%' OR  a.NomorLapak LIKE '%$key%' OR  b.NamaPasar LIKE '%$key%' OR  d.NamaPerson LIKE '%$key%' )";
                                        }
                                        
                                        
                                        $sql .=" GROUP BY d.IDPerson order by d.NamaPerson";
                                        $oke2 = $koneksi->prepare($sql);
                                        $oke2->execute();
                                        $result = $oke2->get_result();  
                                        
                                        //pagination config start
                                        $rpp = 20; // jumlah record per halaman
                                        $page = intval(@$_GET["page"]);
                                        if($page<=0) $page = 1;  
                                        $tcount = mysqli_num_rows($result);
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
                                            $data = mysqli_fetch_array($result);
                                        ?>
                                        <tr class="odd gradeX">
                                            <td width="50px">
                                                <?php echo ++$no_urut;?> 
                                            </td>
                                            <td>
                                                <?php echo $data ['NamaPerson']; ?>
                                                
                                            </td>
                                            <td>
                                                <?php echo $data ['AlamatLengkapPerson']; ?>
                                            </td>
                                            <td>
                                                <?php echo TanggalIndo($data ['TglAktif'])?>
                                            </td>
                                        </tr>
                                        <?php
                                            $i++; 
                                            $count++;
                                        }
                                        
                                        if($tcount==0){
                                            echo '
                                            <tr>
                                                <td colspan="8" align="center">
                                                    <strong>Data Tidak Ada</strong>
                                                </td>
                                            </tr>
                                            ';
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
    <!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		//datepicker
		$(document).ready(function() {
			$('#time1').Zebra_DatePicker({format: 'Y-m-d'});
            $('#time2').Zebra_DatePicker({format: 'Y-m-d'});
            $('#time7').Zebra_DatePicker({format: 'Y-m-d'});
		});
	</script>
  </body>
</html>