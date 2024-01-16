<?php 
include '../library/config.php';
$tagline1 = mysqli_query($koneksi,"SELECT * FROM setting where id='15'");
$tagline2 = mysqli_query($koneksi,"SELECT * FROM setting where id='16'");
$tagline3 = mysqli_query($koneksi,"SELECT * FROM setting where id='17'");
$tagline4 = mysqli_query($koneksi,"SELECT * FROM setting where id='18'");
$tagline5 = mysqli_query($koneksi,"SELECT * FROM setting where id='19'");
$tagline6 = mysqli_query($koneksi,"SELECT * FROM setting where id='20'");
$tagline7 = mysqli_query($koneksi,"SELECT * FROM setting where id='21'");
$tagline8 = mysqli_query($koneksi,"SELECT * FROM setting where id='22'");
$tagline9 = mysqli_query($koneksi,"SELECT * FROM setting where id='23'");
$caritagline1	= mysqli_fetch_assoc($tagline1);
$caritagline2	= mysqli_fetch_assoc($tagline2);
$caritagline3	= mysqli_fetch_assoc($tagline3);
$caritagline4	= mysqli_fetch_assoc($tagline4);
$caritagline5	= mysqli_fetch_assoc($tagline5);
$caritagline6	= mysqli_fetch_assoc($tagline6);
$caritagline7	= mysqli_fetch_assoc($tagline7);
$caritagline8	= mysqli_fetch_assoc($tagline8);
$caritagline9	= mysqli_fetch_assoc($tagline9);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <?php include 'title.php';?>
        <link rel="stylesheet" type="text/css" href="katalog/css/default.css" />
        <link rel="stylesheet" type="text/css" href="katalog/css/component.css" />
        <script src="katalog/js/modernizr.custom.js"></script>
        <link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="katalog/mycss.css">
        <link rel="stylesheet" href="katalog/katalog.css">
		<link rel="stylesheet" href="css/font-awesome.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    </head>
    <body>
        <!--<div class="container"> -->
            <header class="clearfix">
                <div class="row-header">
                    <div class="col-lg-6  ">
                        <img src="katalog/logo.png" style="width:80%;height:auto;">
                    </div>
                   
                </div>
            </header>
            <div class="dd">
                <ul id="og-grid" class="og-grid">
                    <li class="m-4">  
                        <a href="index.php" data-largesrc="katalog/icon/web.png" data-title="WEBSITE UTAMA" data-description="<?php echo $caritagline1 ['value']; ?> " class="og-item" data-toggle="tooltip" data-placement="top" title="Website"> 
                            <img src="katalog/icon/web.png" alt="img01" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>WEBSITE UTAMA</b><p>
                        </a> 
                        <div class="og-title bg-white p-4 txt-detail">
                            Website
                        </div>
                    </li>
                    <li class="m-4">  
                        <a href="../web/index.php?nm=<?php echo base64_encode('TIMBANGAN'); ?>" data-largesrc="katalog/icon/metrologi.png" data-title="Login Admin Simoleg" data-description="<?php echo $caritagline2['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Login Admin Simoleg">
                            <img src="katalog/icon/metrologi.png" alt="img02" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>SI MOLEG</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Login Admin Simoleg
                        </div>
                    </li>
                    <li class="m-4">  
                        <a href="../web/index.php?nm=<?php echo base64_encode('HARGA PASAR'); ?>" data-largesrc="katalog/icon/saudagar.png" data-title="Login Admin Saudagar" data-description="<?php echo $caritagline3['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Login Admin Saudagar">
                            <img src="katalog/icon/saudagar.png" alt="img02" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>SAUDAGAR</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Login Admin Saudagar
                        </div>
                    </li>
					<li class="m-4">  
                        <a href="../web/index.php?nm=<?php echo base64_encode('PUPUK SUBSIDI'); ?>" data-largesrc="katalog/icon/pupuk.png" data-title="Login Admin Pupuk Subsidi" data-description="<?php echo $caritagline9['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Login Admin Pupuk Subsidi">
                            <img src="katalog/icon/pupuk.png" alt="img03" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>PUPUK SUBSIDI</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Login Admin Pupuk Subsidi
                        </div>
                    </li>
                    <li class="m-4">  
                        <a href="../web/index.php?nm=<?php echo base64_encode('RETRIBUSI PASAR'); ?>" data-largesrc="katalog/icon/epras.png" data-title="Login Admin eRPAS" data-description="<?php echo $caritagline4['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Login Admin eRPAS">
                            <img src="katalog/icon/epras.png" alt="img03" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>eRPAS</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Login Admin eRPAS
                        </div>
                    </li>
                    <li class="m-4">  
                        <a href="../manajemen-pasar/tabel-hargakonsumen.php" data-largesrc="katalog/icon/e-katalog.png" data-title=" E-Katalog Bahan Pokok Penting" data-description="<?php echo $caritagline5['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title=" E-Katalog Bahan Pokok Penting (Harga Pasar)">
                            <img src="katalog/icon/e-katalog.png" alt="img03" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>e-KATALOG</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            E-Katalog Bahan Pokok Penting (Harga Pasar)
                        </div>
                    </li>
                   <!--  <li class="m-4">  
                        <a href="login_distri.php" data-largesrc="katalog/icon/ditributor.png" data-title="Sistem Manajemen Pupuk Subsidi" data-description="<?php echo $caritagline6['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Sistem Manajemen Pupuk Subsidi">
                            <img src="katalog/icon/ditributor.png" alt="img03" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>DISTRIBUTOR PUPUK</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Sistem Manajemen Pupuk Subsidi
                        </div>
                    </li> -->
                    <li class="m-4">  
                        <a href="Pengaduan.php" data-largesrc="katalog/icon/masyarakat.png" data-title="Layanan Pengaduan Masyarakat" data-description="<?php echo $caritagline7['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Layanan Pengaduan Masyarakat">
                            <img src="katalog/icon/masyarakat.png" alt="img03" style="max-height: 200px;"/>
                            <p style="color:#000000; padding: 5px;"><b>ADUAN MASYARAKAT</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Layanan Pengaduan Masyarakat
                        </div>
                    </li>
                    <li class="m-4">  
                        <a href="PermohonanTera.php" data-largesrc="katalog/icon/simoleg.png" data-title="Sistem Layanan Tera dan Tera UTTP" data-description="<?php echo $caritagline8['value']; ?>" class="og-item" data-toggle="tooltip" data-placement="top" title="Sistem Layanan Tera dan Tera UTTP"> 
                            <img src="katalog/icon/simoleg.png" alt="img01" style="max-height: 200px;"/> 
                            <p style="color:#000000; padding: 5px;"><b>LAYANAN TERA UTTP</b><p>
                        </a>
                        <div class="og-title bg-white p-4 txt-detail">
                            Sistem Layanan Tera dan Tera UTTP
                        </div>
                    </li>
                </ul>
                <!-- <p>Filler text by <a href="http://veggieipsum.com/">Veggie Ipsum</a></p> -->
            </div>
            <header class="clearfix">
                <div class="row row-header">
                    <div class="col-lg-12">
						<?php include '../library/sistemsetting.php'; ?>
                        <h4><strong><?php echo sistemSetting($koneksi, '1', 'value'); ?></strong></h4>
                        <strong><i class="icon-home"></i> <?php echo sistemSetting($koneksi, '4', 'value'); ?><br>
						<i class="icon-phone"></i> <?php echo sistemSetting($koneksi, '2', 'value'); ?><br>
                        <i class="icon-envelope-alt"></i> <?php echo sistemSetting($koneksi, '3', 'value'); ?></strong>
                    </div>
                    <!--<div class="col-lg-12">
                        <div>Icons made by <a href="https://www.flaticon.com/authors/popcorns-arts" title="Icon Pond">Icon Pond</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
                    </div>-->
                </div>
            </header>
        <!--</div>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 
        <script src="katalog/js/grid.js"></script> 

        <script>
            $(function() {
                Grid.init();
            });
        </script>
    </body>
</html>