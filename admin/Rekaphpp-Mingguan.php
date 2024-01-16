<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='Mingguan';
$fitur_id = 22;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Period = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('m-Y');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$display = isset($_GET['d']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['d'])) : 'hkonsumen';
$Minggu = isset($_GET['m']) ? mysqli_real_escape_string($koneksi,$_GET['m']) : '1';
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
    <!-- Maps -->
    <script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
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
</body>
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
                    <h2 class="no-margin-bottom">Laporan Rekap Mingguan</h2>
                </div>
            </header>

            <section class="dashboard-counts no-padding-bottom">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">     
                            <form action="">            
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-control-label">Tampilkan Data</label>
                                        <select name="d" id="d" class="form-control">
                                            <option value="<?php echo base64_encode('hkonsumen'); ?>" <?php echo $display === 'hkonsumen' ? "selected":""; ?> >Harga Konsumen</option>
                                            <option value="<?php echo base64_encode('hprodusen'); ?>" <?php echo $display === 'hprodusen' ? "selected":""; ?> >Harga Produsen</option>
                                            <option value="<?php echo base64_encode('ketersediaan'); ?>" <?php echo $display === 'ketersediaan' ? "selected":""; ?> >Ketersediaan</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <label class="form-control-label">Pilih Minggu</label>
                                        <select name="m" id="m" class="form-control">
                                            <option value="1" <?php echo $Minggu === '1' ? "selected":""; ?> >Minggu 1</option>
                                            <option value="2" <?php echo $Minggu === '2' ? "selected":""; ?> >Minggu 2</option>
                                            <option value="3" <?php echo $Minggu === '3' ? "selected":""; ?> >Minggu 3</option>
                                            <option value="4" <?php echo $Minggu === '4' ? "selected":""; ?> >Minggu 4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-control-label">Tanggal</label>
                                        <input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Period; ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-control-label">Pasar</label>
                                        <select class="form-control" name="psr">
                                            <option class="form-control" value="" selected>Semua Pasar</option>
                                            <?php 
                                            $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                            $res_p = $koneksi->query($sql_p);
                                            while ($row_p = $res_p->fetch_assoc()) {
                                                if(isset($KodePasar) && $KodePasar === $row_p['KodePasar']){
                                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                                }else{
                                                    if(!isset($KodePasar) || strlen($KodePasar) < 1 ){
                                                        $KodePasar = $row_p['KodePasar'];
                                                    }
                                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select>                                        
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-control-label">Pencarian</label>
                                        <div class="input-group mb-3">
                                            <input id="v" name="v" type="text" class="form-control" placeholder="Pencarian" value="<?php echo isset($_GET['v']) ? $_GET['v'] : '' ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="col-lg-12">
                                <div class="table-responsive">  
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-left">No</th>
                                                <th class="text-left">Nama Bahan Pokok</th>
                                                <th class="text-center">Satuan</th>
                                                <th class="text-right">Hari Ke 1</th>
                                                <th class="text-right">Hari Ke 2</th>
                                                <th class="text-right">Hari Ke 3</th>
                                                <th class="text-right">Hari Ke 4</th>
                                                <th class="text-right">Hari Ke 5</th>
                                                <th class="text-right">Hari Ke 6</th>
                                                <th class="text-right">Hari Ke 7</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include '../library/pagination1.php';
                                            $value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
                                            $reload = "Rekaphpp-Mingguan.php?psr=".base64_encode($KodePasar)."&brg=".base64_encode($KodeBarang)."&pagination=true&view=1&v=".$value."&d=".base64_encode($display)."&tgl=".$Tanggal;
                                            
                                            if(isset($_GET['v']) && $_GET['v'] != ''){
                                                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
                                                FROM mstbarangpokok b
                                                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                                WHERE b.IsAktif = '1' AND b.NamaBarang LIKE '%$value%'
                                                GROUP BY b.KodeBarang
                                                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
                                            }else{
                                                $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang
                                                FROM mstbarangpokok b
                                                LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                                                WHERE b.IsAktif = '1'
                                                GROUP BY b.KodeBarang
                                                ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";
                                            }
                                            $result = mysqli_query($koneksi,$sql);
                                            $rpp = 20;
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
                                                    <td colspan="9"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
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
                                                    <td class="text-center">
                                                        <?php echo $data['Satuan'];?>
                                                    </td>
                                                    <?php 
                                                    $kodeBarang = $data['KodeBarang'];
                                                    for ($c=1; $c <= 7 ; $c++) {
                                                        $sql_brg = "SELECT DAYOFWEEK(r.Tanggal) AS Minggu, DATE(r.Tanggal) AS Tanggal, r.KodeBarang, IFNULL(r.HargaBarang, 0) AS HargaBarang, IFNULL(r.HargaProdusen, 0) AS HargaProdusen, IFNULL(r.Ketersediaan, 0) AS Ketersediaan 
                                                        FROM  reporthargaharian r
                                                        WHERE IF(LENGTH('$KodePasar') > 0, r.KodePasar = '$KodePasar', TRUE) AND WEEKOFMONTH(r.Tanggal) = '$Minggu' AND DATE_FORMAT(r.Tanggal,'%m-%Y') = '$Period' AND DAYOFWEEK(r.Tanggal) = '$c' AND r.KodeBarang = '$kodeBarang'
                                                        ORDER BY r.Tanggal ASC";
                                                        $res_brg = mysqli_query($koneksi,$sql_brg);
                                                        if($res_brg){
                                                            $row_brg = mysqli_fetch_assoc($res_brg);
                                                            ?>
                                                            <td class="text-right">
                                                                <?php echo 'Rp.'.(
                                                                    $display == 'hkonsumen' ?
                                                                    number_format($row_brg['HargaBarang'])
                                                                    : ($display == 'hprodusen' ? 
                                                                        number_format($row_brg['HargaProdusen'])
                                                                        : number_format($row_brg['Ketersediaan'])
                                                                    )
                                                                );?>
                                                            </td>
                                                            <?php
                                                        }else{
                                                            ?>
                                                            <td class="text-right">
                                                                <?php echo 'Rp.0';?>
                                                            </td>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
                                                $i++; 
                                                $count++;
                                            }

                                            if($tcount==0){
                                                echo '
                                                <tr>
                                                <td colspan="7" align="center">
                                                <strong>Tidak ada data</strong>
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
            </section>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tgl').Zebra_DatePicker({format: 'm-Y'});
    });
</script>