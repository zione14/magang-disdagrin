<?php
include '../admin/akses.php';
$Page = 'index';
include '../library/tgl-indo.php';
$Page = 'UTTP';
$KodePasar = htmlspecialchars(base64_decode($_GET['p']));
$IDLapak   = htmlspecialchars(base64_decode($_GET['l']));
$NamaPasar = htmlspecialchars(base64_decode($_GET['n']));

// $sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet, c.NamaPerson, c.IDPerson, c.TanggalTerakhirBayar
//   FROM lapakpasar l 
//   LEFT JOIN (
//     SELECT t.NoTransRet, MAX(t.TanggalTrans) as TanggalTerakhirBayar, t.KodePasar, t.IDLapak, p.NamaPerson, t.IDPerson
//     FROM trretribusipasar t
//     JOIN mstperson p ON t.IDPerson=p.IDPerson
//     JOIN lapakperson lp ON t.IDLapak=lp.IDLapak AND t.KodePasar=lp.KodePasar
//     WHERE DATE_FORMAT(t.TanggalTrans, '%Y-%m-%d') = CURDATE() AND  lp.IsAktif=b'1'
//   ) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
//   WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$IDLapak' ORDER BY IDLapak ASC";
$sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet, e.NamaPerson, c.IDPerson, c.TanggalTerakhirBayar, d.LokasiFile
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
                     <h3 class="h4">Keberadaan UTTP Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h3>
                    </div>
					<div class="card">
                        <div class="card-body">
                            <div class="col-lg-6 offset-lg-6">
                                <form method="post" action="">
                                    <div class="form-group input-group">                        
                                        <input type="text" name="keyword" class="form-control" placeholder="Nama Timbangan..." value="<?php echo @$_REQUEST['keyword']; ?>">&nbsp;&nbsp;
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
                                      <th>No</th>
                                      <th>Nama UTTP</th>
                                      <th>Jenis UTTP</th>
                                      <th>Tarif Retribusi</th>                                    
                                      <th>Alamat</th>
                                      <th>Status</th>
                                    </tr>
                                  </thead>
                                  </thead>
                                    <?php
                                        include '../library/pagination1.php';
                                        $Day = date('Y-m');
                                        // echo $row_lapakpr['IDPerson'];
                                        @$keyword=$_REQUEST['keyword'];
                                        $reload = "MapUTTP.php?l=".base64_encode($IDLapak)."&p=".base64_encode($KodePasar)."&n=".base64_encode($NamaPasar)."&pagination=true&keyword=$keyword";
                                        
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
                                        $result = mysqli_query($koneksi,$sql);  
                                        
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