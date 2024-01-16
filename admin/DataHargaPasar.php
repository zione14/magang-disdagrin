<?php
include '../admin/akses.php';
$Page = 'Rekap';
$SubPage='LaporanPasar';
$fitur_id = 26;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
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
                        <h2 class="no-margin-bottom">Laporan Pasar Entry Harga</h2>
                    </div>
                </header>

                <section class="dashboard-counts no-padding-bottom">
                    <div class="container-fluid">
                        <div class="col-lg-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4>Data Laporan Pasar Entry Harga</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row" style="padding:0px !important;">
                                        <div class="col-lg-12">
                                            <form action="" method="get">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <label class="form-control-label">Tanggal</label>
                                                        <div class="input-group mb-3">
                                                            <input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Tanggal; ?>">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="submit">Cari</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-stripped">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Nama Pasar</th>
                                                            <th>Nama Kep. Pasar</th>
                                                            <th>No. Telp</th>
                                                            <th>Alamat Lengkap</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                    include '../library/pagination1.php';
                                                    $value = isset($_GET['v']) ? mysqli_escape_string($koneksi, $_GET['v']) : '';
                                                    $reload = "DataHargaPasar.php?pagination=true&view=1&v=".$value;
                                                    if(isset($_GET['v']) && $_GET['v'] != ''){
                                                        $sql =  "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa, COUNT(DISTINCT u.UserName) AS JumlahPetugas, IF((SELECT COUNT(r.KodeBarang) FROM reporthargaharian r WHERE r.KodePasar = p.KodePasar AND DATE(r.Tanggal) = '$Tanggal') =  (SELECT COUNT(mb.KodeBarang) FROM mstbarangpokok mb WHERE mb.IsAktif = 1), 1, 0) AS Entry
                                                        FROM mstpasar p
                                                        INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
                                                        INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
                                                        INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
                                                        LEFT JOIN userlogin u ON u.KodePasar = p.KodePasar
                                                        WHERE (p.NamaPasar LIKE '%$value%' OR p.NamaKepalaPasar LIKE '%$value%') 
                                                        GROUP BY p.KodePasar
                                                        ORDER BY p.NamaPasar ASC";
                                                    }else{
                                                        $sql =  "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa, COUNT(DISTINCT u.UserName) AS JumlahPetugas, IF((SELECT COUNT(r.KodeBarang) FROM reporthargaharian r WHERE r.KodePasar = p.KodePasar AND DATE(r.Tanggal) = '$Tanggal') =  (SELECT COUNT(mb.KodeBarang) FROM mstbarangpokok mb WHERE mb.IsAktif = 1), 1, 0) AS Entry
                                                        FROM mstpasar p
                                                        INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
                                                        INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
                                                        INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
                                                        LEFT JOIN userlogin u ON u.KodePasar = p.KodePasar
                                                        GROUP BY p.KodePasar
                                                        ORDER BY p.NamaPasar ASC";  
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
                                                    ?>
                                                    <tbody>
                                                        <?php
                                                        while(($count<$rpp) && ($i<$tcount)) {
                                                            mysqli_data_seek($result,$i);
                                                            $data = mysqli_fetch_array($result);
                                                            ?>
                                                            <tr class="odd gradeX" <?php echo $data['Entry'] == 1 ? 'style="background-color: #21d40d;"' : ''; ?>>
                                                                <td width="50px">
                                                                    <?php echo ++$no_urut;?> 
                                                                </td>
                                                                <td>
                                                                    <?php echo $data ['NamaPasar'];?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $data ['NamaKepalaPasar'];?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $data ['NoTelpPasar'];?>
                                                                </td>
                                                                <td>
                                                                    <?php echo ucwords(strtolower($data['NamaDesa'].', '.$data['NamaKecamatan'].', '.$data['NamaKabupaten'])); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo $data['Entry'] == 1 ? '<strong>Sudah Entry Harga</strong>' : '<strong>Belum Entry Harga</strong>'; ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                            $i++; 
                                                            $count++;
                                                        }

                                                        if($tcount==0){
                                                            echo '
                                                            <tr>
                                                            <td colspan="4" align="center">
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
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
<?php include 'footer.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#tgl').Zebra_DatePicker({format: 'Y-m-d'});
    });
</script>