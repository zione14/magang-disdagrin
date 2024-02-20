<?php
include '../admin/akses.php';
include '../library/tgl-indo.php';
$Page = 'Pemilik';
$KodePasar = htmlspecialchars(base64_decode($_GET['p']));
$IDLapak   = htmlspecialchars(base64_decode($_GET['l']));
$NamaPasar = htmlspecialchars(base64_decode($_GET['n']));
$sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar
  FROM lapakpasar l 
  WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$IDLapak' ORDER BY IDLapak ASC";
  $oke = $koneksi->prepare($sql_lapakpr);
  $oke->execute();
  $res_lapakpr = $oke->get_result();
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
                     <h3 class="h4">Histori Pemilik Lapak Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h3>
                    </div>
					<div class="card">
                        <div class="card-body">
                            <div class="col-lg-6 offset-lg-6">
                                <form method="post" action="">
                                    <div class="form-group input-group">                        
                                        <input type="text" name="keyword" class="form-control" placeholder="Nama Pemilik..." value="<?php echo @$_REQUEST['keyword']; ?>">&nbsp;&nbsp;
                                        <span class="input-group-btn">
                                            <button class="btn btn-success" type="submit">Cari</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                              <div class="table-responsive">  
                                <table class="table table-hover">
                                  <thead>
                                    <tr>
                                      <th>No </th>
                                      <th>Nama Pemilik</th>
                                      <th>Alamat</th>
                                      <th>Tanggal Mulai Sewa</th>
                                    </tr>
                                    
                                  </thead>
                                    <?php
                                        include '../library/pagination1.php';
                                        $Day = date('Y-m');
                                        @$keyword=$_REQUEST['keyword'];
                                        $reload = "MapPemilik.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&keyword=$keyword";
                                        $sql =  "SELECT a.BlokLapak,a.NomorLapak,a.Retribusi,b.NamaPasar,b.NamaKepalaPasar,a.IDLapak,d.NamaPerson,d.AlamatLengkapPerson,d.IDPerson, c.TglAktif  
                                        FROM lapakpasar a join mstpasar b on a.KodePasar=b.KodePasar 
                                        join lapakperson c on (c.IDLapak,c.KodePasar)=(a.IDLapak,a.KodePasar) 
                                        join mstperson d on c.IDPerson=d.IDPerson 
                                        where a.KodePasar='$KodePasar' AND a.IDLapak='$IDLapak'";
                                        
                                    
                                        if((isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>"")){
                                            $sql .= " AND (a.BlokLapak LIKE '%$keyword%' OR  a.NomorLapak LIKE '%$keyword%' OR  b.NamaPasar LIKE '%$keyword%' OR  d.NamaPerson LIKE '%$keyword%' )";
                                        }
                                        
                                        
                                        $sql .=" GROUP BY d.IDPerson order by d.NamaPerson";
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