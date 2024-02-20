<?php
include '../admin/akses.php';
$Page = 'index';
include '../library/tgl-indo.php';
$Page = 'Pembayaran';
$KodePasar = htmlspecialchars(base64_decode($_GET['p']));
$IDLapak   = htmlspecialchars(base64_decode($_GET['l']));
$NamaPasar = htmlspecialchars(base64_decode($_GET['n']));
$sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar
  FROM lapakpasar l 
  WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$IDLapak' ORDER BY IDLapak ASC";
  $res_lapakpr = $koneksi->query($sql_lapakpr);
  $row_lapakpr = $res_lapakpr->fetch_assoc();

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
    <!-- Datepcker -->
    <link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
    <style>
    .table thead th {
        vertical-align: middle;
        text-align: center;
        border-bottom: 2px solid #dee2e6;
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
        <?php include 'menu_lapak.php';?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Lapak Pasar <?=$NamaPasar?></h2>
            </div>
          </header>
		  
      

		 <section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
                    <div class="card-header d-flex align-items-center">
                     <h3 class="h4">Histori Pembayaran Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h3>
                    </div>
					<div class="card">
                        <div class="card-body">
                                <form method="post">
                                   
                                    <div class="col-lg-8 offset-lg-4">
                                        <div class="col-lg-12 form-group input-group">
                                            <input type="text" name="Tanggal1" class="form-control" id="time1" value="<?php echo @$_REQUEST['Tanggal1']; ?>" placeholder="Tanggal Awal" required>&nbsp;&nbsp;
                                            <input type="text" name="Tanggal2" class="form-control" id="time2" value="<?php echo @$_REQUEST['Tanggal2']; ?>" placeholder="Tanggal Akhir" required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">Cari</button>&nbsp;
                                                
                                            </span>
                                        </div>
                                    </div>
                                </form>
                              <div class="table-responsive">  
                                <table class="table table-hover">
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
                                        $key  = @$_REQUEST['keyword'];
                                        $reload = "MapPembayaran.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&Tanggal2=$tgl2&Tanggal1=$tgl1&keyword=$key";
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

                                        if((isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"")){
                                            $sql .= " AND b.NamaPerson LIKE '%".htmlspecialchars($_REQUEST['keyword'])."%' ";
                                        }
                                        
                                        $sql .="  ORDER BY a.TanggalTrans DESC";
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
                                        while(($count<$rpp) && ($i<$tcount)) {
                                            mysqli_data_seek($result,$i);
                                            $data = mysqli_fetch_array($result);
                                        ?>
                                        <tr class="odd gradeX">
                                            <td width="50px">
                                                <?php echo ++$no_urut;?> 
                                            </td>
                                            <td>
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
  </body>
</html>