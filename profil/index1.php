<?php 
include_once '../library/config.php';
include '../library/tgl-indo.php';
include '../library/sistemsetting.php';
$tgl_now=date("Y-m-d H:i:s");
$Page='Index';

$slider = mysqli_query($koneksi,"SELECT * FROM kontenweb where JenisKonten='Slider' AND IsAktif=b'1' order by TanggalKonten DESC");
$berita = mysqli_query($koneksi,"SELECT * FROM kontenweb where JenisKonten='Berita' AND IsAktif=b'1' order by TanggalKonten DESC LIMIT 4");
$agenda = mysqli_query($koneksi,"SELECT * FROM kontenweb where JenisKonten='Agenda' AND IsAktif=b'1' order by TanggalKonten DESC");
$foto   = mysqli_query($koneksi,"SELECT a.KodeKonten,b.Dokumen,b.keterangan FROM kontenweb a left join detailkonten b on (a.KodeKonten,a.JenisKonten)=(b.KodeKonten,b.JenisKonten) where a.JenisKonten='Foto' AND a.IsAktif=b'1' order by a.TanggalKonten DESC LIMIT 6");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <?php include 'title.php';?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Dinas Perdagangan dan Perindustrian Kabupaten Jombang" />
  <meta name="author" content="" />

  <!-- css -->
  <link href="https://fonts.googleapis.com/css?family=Handlee|Open+Sans:300,400,600,700,800" rel="stylesheet">
  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/bootstrap-responsive.css" rel="stylesheet" />
  <link href="css/flexslider.css" rel="stylesheet" />
  <link href="css/prettyPhoto.css" rel="stylesheet" />
  <link href="css/camera.css" rel="stylesheet" />
  <link href="css/jquery.bxslider.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />

  <!-- Theme skin -->
  <link href="color/default.css" rel="stylesheet" />

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png" />
  <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png" />
  <!-- owl carousel-->
  <!-- Owl Stylesheets -->
  <link rel="stylesheet" href="owlcarousel/assets/owlcarousel/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="owlcarousel/assets/owlcarousel/assets/owl.theme.default.min.css">

  <style>
  .scrollup {
    position: fixed;
    width: 32px;
    height: 32px;
    bottom: 20px;
    right: 20px;
    z-index: 3;

  }
  </style>
  
  <!-- =======================================================
    Theme Name: Eterna
    Theme URL: https://bootstrapmade.com/eterna-free-multipurpose-bootstrap-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->
</head>

<body>

  <div id="wrapper">

    <!-- start header -->
    <?php include 'header.php';?>
    <!-- end header -->

    <!-- section featured -->
    <section id="featured">

      <!-- slideshow start here -->

      <div class="camera_wrap" id="camera-slide">
    
    <!-- slide 1 here -->
        <div data-src="img/slides/camera/slide2/img1.jpg">
          <div class="camera_caption fadeFromLeft">
            <div class="container">
              <div class="row">
                <div class="span12 aligncenter">
                  <h2 class="animated fadeInDown"><strong><span class="colored">Dinas Perdagangan</span> dan <span class="colored">Perindustrian </span> Jombang</strong></h2>
                  <p class="animated fadeInUp">Selamat Datang di Website Resmi Dinas Perdagangan dan Perindustrian Kabupaten Jombang </p>
                  <img src="img/slides/camera/slide2/jombang1.jpeg" alt="" class="animated bounceInDown delay1"  style="width:45%"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php
      while($cari=mysqli_fetch_assoc($slider)){
      $arr = explode(' ',trim($cari['JudulKonten']));
    ?>
        <!-- slide 2 here -->
        <div data-src="../images/web_profil/slider/<?php echo ucwords($cari['Gambar1']); ?>">
          <div class="camera_caption fadeFromLeft">
            <div class="container">
              <div class="row">
                <div class="span7" >
          
            <h2 class="animated fadeInDown"><strong><?php echo ucwords($arr[0]); ?> <span class="colored"><?php echo ucwords(str_replace($arr[0],"",$cari['JudulKonten'])); ?></span></strong></h2>
            <h4 class="animated fadeInUp"  style="font-size: 90; background: rgba(0,0,0,0.5); color: white;  text-align: center; border-radius:10px; padding:10px;"> <?php echo $cari['IsiKonten']; ?></h4>
        
                </div>
                <div class="span5">
                  <!--<img src="../assets/slider/<?php echo ucwords($cari['Gambar1']); ?>" alt="" class="animated bounceInDown delay1" style="width:65%" />-->
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php } ?>   
      </div>

      <!-- slideshow end here -->

    </section>
    <!-- /section featured -->

    <section id="content">
      <div class="container">


        <div class="row">
          <div class="span12">
            <div class="row">
              <div class="span6">
                <div class="box flyLeft">
                  <div class="icon">
                    <i class="ico icon-circled icon-bgdark  icon-cogs active icon-3x"></i>
                  </div>
                  <div class="text">
                    <h5>BIDANG <strong><?php echo $res1['nama']; ?></strong></h5>
                    <p>
                      <?php echo $res1['value']; ?>
                    </p>
                  </div>
                </div>
              </div>

              <div class="span6">
                <div class="box flyRight">
                  <div class="icon">
                    <i class="ico icon-circled icon-bgdark icon-shopping-cart active icon-3x"></i>
                  </div>
                  <div class="text">
                    <h5>BIDANG <strong><?php echo $res3['nama']; ?></strong></h5>
                    <p>
            <?php echo $res3['value']; ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="span6">
                <div class="box flyLeft">
                  <div class="icon">
                    <i class="ico icon-circled icon-bgdark icon-bullhorn active icon-3x"></i>
                  </div>
                  <div class="text">
                    <h5>BIDANG <strong><?php echo $res2['nama']; ?></strong></h5>
                    <p>
            <?php echo $res2['value']; ?>
                    </p>
                    
                  </div>
                </div>
              </div>
        
        <br><div class="span6">
                <div class="box flyRight">
                  <div class="icon">
                    <i class="ico icon-circled icon-bgdark  icon-group active icon-3x"></i>
                  </div>
                  <div class="text">
                    <h5>BIDANG <strong><?php echo $res4['nama']; ?></strong></h5>
                    <p>
                     <?php echo $res4['value']; ?>
                    </p>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="row">
          <div class="span12">
            <div class="solidline"></div>
          </div>
        </div>

        <div class="row">
          <div class="span12">
            <div class="row">
              <div class="span12">
                <div class="aligncenter">
                  <a href="Berita.php" ><h3>Berita <strong>Terbaru</strong></h3></a>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- Item Project and Filter Name -->
              <?php
                while($brt=mysqli_fetch_assoc($berita)){
                $kode = $brt['KodeKonten'];
                $gambar = mysqli_query($koneksi,"SELECT Dokumen FROM detailkonten where KodeKonten= '$kode' AND JenisKonten='Berita' LIMIT 1");
                $gbr=mysqli_fetch_assoc($gambar);
              ?>
      
                <div class="item-thumbs span3 ">
                 <div class="team-box thumbnail">
                  <img src="../images/web_profil/berita/thumb_<?php echo ($gbr['Dokumen']); ?>" alt=""/>
                  <div class="caption">
                  <h6><strong>
                    <?php 
                     $num_char = 15;
                      $cut_text = substr($brt['JudulKonten'], 0, $num_char);
                      $str_num = str_word_count($brt['JudulKonten']);
                      if($str_num >= 3){
                      if (@$brt['JudulKonten']{$num_char - 1} != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
                      $new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
                      $cut_text = substr($brt['JudulKonten'], 0, $new_pos);
                      }
                      echo @strtoupper($cut_text) . '...';  
                      }else{
                      echo strtoupper($brt['JudulKonten']);                            
                      } ?></strong></h6>
                  <p>
                   <?php 
           
           
          $num_char = 100;
                    $cut_text = substr(strip_tags($brt['IsiKonten']), 0, $num_char);
                    // $str_num = str_word_count($brt['IsiKonten']);
                    $str_num = strlen(strip_tags($brt['IsiKonten']));
                    if($str_num >= 6){
                    if (strip_tags($brt['IsiKonten']){$num_char - 1} != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
                    $new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
                    $cut_text = substr(strip_tags($brt['IsiKonten']), 0, $new_pos);
                    }
                    echo @$cut_text . '...';  
                    }else{
                    echo @$brt['IsiKonten'];   
    
                    }
          ?>
                  </p>
                   <a href="Detil.php?id=<?php echo base64_encode($brt['KodeKonten']);?>&jd=<?php echo base64_encode('Berita');?>" class="btn btn-success btn-sm animated fadeInUp"><i class="icon-link"></i> Read more </a>
                  </div>
                  </div>
                </div>
              <?php } ?>
             <!-- End Item Project -->
            </div>
          </div>
        </div>
    
        <div class="row">
          <div class="span12">
            <a href="Agenda.php" ><h4 class="title"><strong>Agenda</strong></h4></a>
      
       <div class="row team">
        <div class="owl-carousel">
          <?php while($agd=mysqli_fetch_assoc($agenda)){ ?>
            <div class="span4" style="background:#7d827e; height:220px">
            <div class="team-box" style="background:#e6ede7;  text-align: justify; height:160px">
             <a href="Agenda.php?id=<?=base64_encode($agd['KodeKonten'])?>&jd=<?=base64_encode($agd['JudulKonten'])?>"><blockquote style="font-size:24px;"> <?=$agd['JudulKonten']?></blockquote></a>
              <?php
                 $num_char = 100;
                $cut_text = substr(strip_tags($agd['IsiKonten']), 0, $num_char);
                $str_num = strlen(strip_tags($agd['IsiKonten']));
                if($str_num >= 6){
                  if (strip_tags($agd['IsiKonten']){$num_char - 1} != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
                    $new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
                    $cut_text = substr(strip_tags($agd['IsiKonten']), 0, $new_pos);
                  }
                  echo @$cut_text . '...';  
                }else{
                echo @$agd['IsiKonten']; 
                }         

              ?>
                <div class="meta-post">
                  <ul>
                    <li><i class="icon-picture"></i></li>
                    <li>By <a class="author"><?php echo $agd['UserName']; ?></a></li>
                    <li>Tanggal <a class="date"><?php echo TanggalIndo($agd['TanggalKonten']); ?></a></li>
                    <!--<li>Tags: <a href="#">Design</a>, <a href="#">Blog</a></li>-->
                  </ul>
                </div>
            </div>
            </div>
          <?php } ?>

        </div>
      </div>
          </div>
        </div>
    
      </div>
    </section>
  
    
    <section id="works">
      <div class="container">
        <div class="row">
          <div class="span12">
            <a href="GaleriFoto.php" ><h4 class="title">Galeri <strong>Foto</strong></h4></a>
            <div class="row">
              <div class="grid cs-style-4">
                <?php while($gal=mysqli_fetch_assoc($foto)){ ?>
                <div class="span4" style="margin-bottom:25px;">
                  <div class="item">
                    <figure>
                      <div><img src="../images/web_profil/galeri/<?php echo ($gal['Dokumen']); ?>" alt="" style="height: 250px; width:100%;"/></div>
                      <figcaption>
                        <div>
                          <span>
                            <a href="../images/web_profil/galeri/<?php echo ($gal['Dokumen']); ?>" data-pretty="prettyPhoto[gallery1]" title="<?php echo $gal['keterangan']; ?>"><i class="icon-plus icon-circled icon-bglight icon-2x"></i></a>
                          </span>
                        </div>
                      </figcaption>
                    </figure>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  
  <!-- start footer -->
    <?php include 'footer.php';?>
    <!-- end footer -->
  </div>
  <a href="Pengaduan.php" class="scrollup"><i class="icon-comments-alt icon-circled icon-bgsuccess icon-2x "></i></a>

  <!-- javascript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="js/jquery.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/bootstrap.js"></script>

  <script src="js/modernizr.custom.js"></script>
  <script src="js/toucheffects.js"></script>
  <script src="js/google-code-prettify/prettify.js"></script>
  <script src="js/jquery.bxslider.min.js"></script>
  <script src="js/camera/camera.js"></script>
  <script src="js/camera/setting.js"></script>

  <script src="js/jquery.prettyPhoto.js"></script>
  <script src="js/portfolio/jquery.quicksand.js"></script>
  <script src="js/portfolio/setting.js"></script>

  <script src="js/jquery.flexslider.js"></script>
  <script src="js/animate.js"></script>
  <script src="js/inview.js"></script>

  <!-- Template Custom JavaScript File -->
  <script src="js/custom2.js"></script>
  
  <!-- Javascript files carousel-->
   <script src="owlcarousel/assets/owlcarousel/owl.carousel.js"></script>

  
 <script>
      var owl = $('.owl-carousel');
      owl.owlCarousel({
        margin: 10,
        loop: true,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 2
          },
          1000: {
            items: 3
          }
        }
      })
    </script>

</body>
</html>
