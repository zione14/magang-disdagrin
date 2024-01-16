<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='Bulanan';
$fitur_id = 27;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';
include 'aksi_hitung.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

$month = isset($_GET['month']) ? mysqli_real_escape_string($koneksi,$_GET['month']) : date('Y-m');
$Tanggal =  $month.'-01';
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';

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
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
    
</head>
<body>
    <div class="page">
        <!-- Main Navbar-->
        <?php include 'header.php'; ?>
        <div class="page-content d-flex align-items-stretch"> 
            <!-- Side Navbar -->
            <?php include 'menu.php';?>
            <div class="content-inner">
                <!-- Page Header-->
                <header class="page-header">
                    <div class="container-fluid">
                        <h2 class="no-margin-bottom">Laporan Rekap Bulanan</h2>
                    </div>
                </header>

                <section class="dashboard-counts no-padding-bottom">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4>Data Laporan Rekap Bulanan Bahan Pokok </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="padding:0px !important;">
                                        <div class="col-lg-12">
                                            <form action="" method="get">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Tampilkan Data</label>
                                                        <select name="d" id="d" class="form-control">
                                                            <option value="<?php echo base64_encode('hkonsumen'); ?>" <?php echo $display === 'hkonsumen' ? "selected":""; ?> >Harga Konsumen</option>
                                                            <option value="<?php echo base64_encode('hprodusen'); ?>" <?php echo $display === 'hprodusen' ? "selected":""; ?> >Harga Produsen</option>
                                                            <option value="<?php echo base64_encode('ketersediaan'); ?>" <?php echo $display === 'ketersediaan' ? "selected":""; ?> >Stok Barang</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Pencarian</label>
                                                        <select class="form-control" name="psr">
                                                            <option class="form-control" value="" selected>Semua Pasar</option>
                                                            <?php 
                                                            $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                                            $res_p = $koneksi->query($sql_p);
                                                            while ($row_p = $res_p->fetch_assoc()) {
                                                                if(isset($KodePasar) && $KodePasar === $row_p['KodePasar']){
                                                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                                                }else{
                                                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                                                }
                                                            }
                                                            ?>
                                                        </select>                                            
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <label class="form-control-label">Bulan</label>
                                                        <div class="input-group mb-3">
                                                            <input class="form-control" type="month" name="month" value="<?php echo date('Y-m', strtotime($Tanggal)); ?>" required>
                                                            <!-- <input class="form-control" id="tgl" name="tgl" type="text" value="<?php //echo $Tanggal; ?>"> -->
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="submit">Cari</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg-12 col-md-12" style="margin-bottom:10px;">
                                            <a target="_blank" href="cetak/Rekaphpp-Bulanan.php?month=<?php echo $month ?>&psr=<?php echo base64_encode($KodePasar); ?>&d=<?php echo base64_encode($display); ?>" class="btn btn-secondary"><i class="fa fa-fw fa-print"></i> Cetak</a>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left" width="50px">No.</th>
                                                            <th class="text-left">Nama Bahan Pokok</th>
                                                            <th class="text-left">Satuan</th>
                                                            <th class="text-right">Minggu I</th>
                                                            <th class="text-right">Minggu II</th>
                                                            <th class="text-right">Minggu III</th>
                                                            <th class="text-right">Minggu IV</th>
                                                        </tr>
                                                    </thead>
                                                    <?php 
                                                    include '../library/pagination1.php';
                                                    if ($display === "ketersediaan" ) : ?>
                                                     <tbody>
                                                        <?php 
                                                        //Pecahan Tanggl Pencarian
                                                        $Thn = substr($month, 0, 4);
                                                        $Bln = substr($month, 5);
                                                        $periode1 = $Thn.'-'.$Bln.'-01';
                                                        $periode2 = $Thn.'-'.$Bln.'-08';
                                                        $periode3 = $Thn.'-'.$Bln.'-15';
                                                        $periode4 = $Thn.'-'.$Bln.'-22';

                                                        $reload = $KodePasar !== "" ? "Rekaphpp-Bulanan.php?pagination=true&view=1&psr=".base64_encode($KodePasar)."&month=".date('Y-m', strtotime($Tanggal)) : "Rekaphpp-Bulanan.php?pagination=true&view=1&month=".date('Y-m', strtotime($Tanggal));

                                                        $sql_pasar = "";
                                                        if($KodePasar !== ""){
                                                            $sql_pasar = "AND r.KodePasar = '".$KodePasar."'";
                                                        }
                                                        $sql = "SELECT b.KodeBarang, b.NamaBarang, g.KodeGroup, g.NamaGroup, b.Satuan, IFNULL(week1.RtKetersediaan,0) AS RtKetersediaan1, IFNULL(week2.RtKetersediaan,0) AS RtKetersediaan2, IFNULL(week3.RtKetersediaan,0) AS RtKetersediaan3, IFNULL(week4.RtKetersediaan,0) AS RtKetersediaan4
                                                        FROM mstbarangpokok b
                                                        INNER JOIN mstgroupbarang g on g.KodeGroup = b.KodeGroup
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                                            FROM stokpedagang r
                                                            WHERE r.Periode ='$periode1' $sql_pasar 
                                                            GROUP by r.KodeBarang
                                                        ) AS week1 ON week1.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                                            FROM stokpedagang r
                                                            WHERE r.Periode ='$periode2' $sql_pasar 
                                                            GROUP by r.KodeBarang
                                                        ) AS week2 ON week2.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                                            FROM stokpedagang r
                                                            WHERE r.Periode ='$periode3' $sql_pasar 
                                                            GROUP by r.KodeBarang
                                                        ) AS week3 ON week3.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, SUM(r.Stok) AS RtKetersediaan
                                                            FROM stokpedagang r
                                                            WHERE r.Periode ='$periode4' $sql_pasar 
                                                            GROUP by r.KodeBarang
                                                        ) AS week4 ON week4.KodeBarang = b.KodeBarang
                                                        WHERE (b.KodeBarang='BRG-2020-0000003' OR b.KodeBarang='BRG-2020-0000002' OR b.KodeBarang='BRG-2020-0000001' OR b.KodeBarang='BRG-2019-0000026' OR b.KodeBarang='BRG-2019-0000027' OR b.KodeBarang='BRG-2019-0000028' OR b.KodeBarang='BRG-2019-0000009') 
                                                        GROUP BY b.KodeBarang
                                                        ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
                                                        // echo $sql;exit;
                                                        $result = mysqli_query($koneksi, $sql);
                                                        $rpp = 10;
                                                        $page = intval(@$_GET["page"]);
                                                        if($page<=0) $page = 1;  
                                                        $tcount = mysqli_num_rows($result);
                                                        $tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
                                                        $count = 0;
                                                        $i = ($page-1)*$rpp;
                                                        $no_urut = ($page-1)*$rpp;
                                                        $kodegroup = "";
                                                        while(($count<$rpp) && ($i<$tcount)) {
                                                            mysqli_data_seek($result,$i);
                                                            $data = mysqli_fetch_array($result);
                                                            if($kodegroup !== $data['KodeGroup']){
                                                                echo '<tr style="background:#f7f7f7;"><td width="50px"></td>
                                                                    <td colspan="7"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
                                                                </tr>';
                                                                $kodegroup = $data['KodeGroup'];
                                                            }
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td width="50px">
                                                                    <?php echo ++$no_urut;?> 
                                                                </td>
                                                                <td>
                                                                    <?php echo $data['NamaBarang'];?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $data['Satuan'];?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo $data ['RtKetersediaan1']; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo $data ['RtKetersediaan2']; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo $data ['RtKetersediaan3']; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo $data ['RtKetersediaan4']; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++; 
                                                            $count++;
                                                        }

                                                        if($tcount==0){
                                                            echo '<tr><td colspan="7" align="center">
                                                            <strong>Tidak ada data</strong>
                                                            </td>
                                                            </tr>
                                                            ';
                                                        }
                                                        ?>
                                                    </tbody>

                                                    <?php else : ?>
                                                    <tbody>
                                                        <?php 
                                                        $TanggalMulai = $Tanggal;
                                                        $TanggalSelesai = date('Y-m-d', strtotime($TanggalMulai.' 1 month'));
                                                        $minggu = array();
                                                        for ($i=0; $i < 4; $i++) { 
                                                            # code...
                                                            if($i == 3){
                                                                $period = new DatePeriod(
                                                                    new DateTime($TanggalMulai),
                                                                    new DateInterval('P1D'),
                                                                    new DateTime(date('Y-m-d', strtotime($TanggalSelesai)))
                                                                );
                                                            }else{
                                                                $period = new DatePeriod(
                                                                    new DateTime($TanggalMulai),
                                                                    new DateInterval('P1D'),
                                                                    new DateTime(date('Y-m-d', strtotime($TanggalMulai.' 7 day')))
                                                                );
                                                            }
                                                            $tgl_arr = array();
                                                            foreach ($period as $key => $value) {
                                                                $valDate = date_format($value, 'Y-m-d');
                                                                array_push($tgl_arr, $valDate);
                                                            }
                                                            array_push($minggu, $tgl_arr);
                                                            $TanggalMulai = date('Y-m-d', strtotime($tgl_arr[sizeof($tgl_arr)-1].' 1 day'));
                                                        }
                                                        $range = array();
                                                        foreach ($minggu as $week ) {
                                                            # code...
                                                            $minggu_mulai = $week[0];
                                                            $minggu_selesai = $week[sizeof($week)-1];
                                                            array_push($range, array(
                                                                'mulai'=>$minggu_mulai, 'selesai'=>$minggu_selesai
                                                            ));
                                                        }
                                                        // selesai untuk menghitung periode minggu
                                                        // print_r($range);
                                                        
                                                        
                                                        $value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
                                                        $reload = $KodePasar !== "" ? "Rekaphpp-Bulanan.php?pagination=true&view=1&v=".$value."&psr=".base64_encode($KodePasar)."&month=".date('Y-m', strtotime($Tanggal)) : "Rekaphpp-Bulanan.php?pagination=true&view=1&v=".$value."&month=".date('Y-m', strtotime($Tanggal));
                                                        // echo json_encode($range);
                                                        $sql_pasar = "";
                                                        if($KodePasar !== ""){
                                                            $sql_pasar = "AND r.KodePasar = '".$KodePasar."'";
                                                        }
                                                        $sql = "SELECT b.KodeBarang, b.NamaBarang, g.KodeGroup, g.NamaGroup, b.Satuan, IFNULL(week1.RtHargabarang,0) AS RtHargabarang1, IFNULL(week2.RtHargabarang,0) AS RtHargabarang2, IFNULL(week3.RtHargabarang,0) AS RtHargabarang3, IFNULL(week4.RtHargabarang,0) AS RtHargabarang4, IFNULL(week1.RtHargaProdusen,0) AS RtHargaProdusen1, IFNULL(week2.RtHargaProdusen,0) AS RtHargaProdusen2, IFNULL(week3.RtHargaProdusen,0) AS RtHargaProdusen3, IFNULL(week4.RtHargaProdusen,0) AS RtHargaProdusen4, IFNULL(week1.RtKetersediaan,0) AS RtKetersediaan1, IFNULL(week2.RtKetersediaan,0) AS RtKetersediaan2, IFNULL(week3.RtKetersediaan,0) AS RtKetersediaan3, IFNULL(week4.RtKetersediaan,0) AS RtKetersediaan4, IFNULL(week1.JmlData,0) AS JmlData1, IFNULL(week2.JmlData,0) AS JmlData2, IFNULL(week3.JmlData,0) AS JmlData3, IFNULL(week4.JmlData,0) AS JmlData4
                                                        FROM mstbarangpokok b
                                                        INNER JOIN mstgroupbarang g on g.KodeGroup = b.KodeGroup
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
                                                            FROM reporthargaharian r
                                                            WHERE DATE(r.Tanggal) >= DATE('".$range[0]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[0]['selesai']."') ".$sql_pasar." 
                                                            GROUP by r.KodeBarang
                                                        ) AS week1 ON week1.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
                                                            FROM reporthargaharian r
                                                            WHERE DATE(r.Tanggal) >= DATE('".$range[1]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[1]['selesai']."') ".$sql_pasar." 
                                                            GROUP by r.KodeBarang
                                                        ) AS week2 ON week2.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
                                                            FROM reporthargaharian r
                                                            WHERE DATE(r.Tanggal) >= DATE('".$range[2]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[2]['selesai']."') ".$sql_pasar." 
                                                            GROUP by r.KodeBarang
                                                        ) AS week3 ON week3.KodeBarang = b.KodeBarang
                                                        LEFT JOIN(
                                                            SELECT r.KodeBarang, AVG(r.HargaBarang) AS RtHargabarang,AVG(r.HargaProdusen) AS RtHargaProdusen, AVG(r.Ketersediaan) AS RtKetersediaan, COUNT(r.KodeBarang) AS JmlData
                                                            FROM reporthargaharian r
                                                            WHERE DATE(r.Tanggal) >= DATE('".$range[3]['mulai']."') AND DATE(r.Tanggal) <= DATE('".$range[3]['selesai']."') ".$sql_pasar." 
                                                            GROUP by r.KodeBarang
                                                        ) AS week4 ON week4.KodeBarang = b.KodeBarang
                                                        WHERE b.IsAktif='1'";
                                                        // echo $sql;exit;
                                                        $result = mysqli_query($koneksi, $sql);
                                                        $rpp = 10;
                                                        $page = intval(@$_GET["page"]);
                                                        if($page<=0) $page = 1;  
                                                        $tcount = mysqli_num_rows($result);
                                                        $tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
                                                        $count = 0;
                                                        $i = ($page-1)*$rpp;
                                                        $no_urut = ($page-1)*$rpp;
                                                        $kodegroup = "";
                                                        while(($count<$rpp) && ($i<$tcount)) {
                                                            mysqli_data_seek($result,$i);
                                                            $data = mysqli_fetch_array($result);
                                                            if($kodegroup !== $data['KodeGroup']){
                                                                echo '<tr style="background:#f7f7f7;"><td width="50px"></td>
                                                                    <td colspan="7"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
                                                                </tr>';
                                                                $kodegroup = $data['KodeGroup'];
                                                            }
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td width="50px">
                                                                    <?php echo ++$no_urut;?> 
                                                                </td>
                                                                <td>
                                                                    <?php echo $data['NamaBarang'];?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $data['Satuan'];?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                    if($display === "ketersediaan"){
                                                                        echo pembulatan($data ['RtKetersediaan1']);
                                                                    }elseif($display === "hprodusen"){
                                                                        echo pembulatan($data ['RtHargaProdusen1']);
                                                                    }else{
                                                                        echo pembulatan($data ['RtHargabarang1']);
                                                                    }?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                    if($display === "ketersediaan"){
                                                                        echo pembulatan($data ['RtKetersediaan2']);
                                                                    }elseif($display === "hprodusen"){
                                                                        echo pembulatan($data ['RtHargaProdusen2']);
                                                                    }else{
                                                                        echo pembulatan($data ['RtHargabarang2']);
                                                                    }?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                    if($display === "ketersediaan"){
                                                                        echo pembulatan($data ['RtKetersediaan3']);
                                                                    }elseif($display === "hprodusen"){
                                                                        echo pembulatan($data ['RtHargaProdusen3']);
                                                                    }else{
                                                                        echo pembulatan($data ['RtHargabarang3']);
                                                                    }?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php 
                                                                    if($display === "ketersediaan"){
                                                                        echo pembulatan($data ['RtKetersediaan4']);
                                                                    }elseif($display === "hprodusen"){
                                                                        echo pembulatan($data ['RtHargaProdusen4']);
                                                                    }else{
                                                                        echo pembulatan($data ['RtHargabarang4']);
                                                                    }?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++; 
                                                            $count++;
                                                        }

                                                        if($tcount==0){
                                                            echo '<tr><td colspan="7" align="center">
                                                            <strong>Tidak ada data</strong>
                                                            </td>
                                                            </tr>
                                                            ';
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <?php endif; ?>
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
</body>
<?php include 'footer.php'; ?>