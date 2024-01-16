<?php 
include '../library/config.php';
include '../library/sistemsetting.php';
$Page='Informasi';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <?php include 'title.php';?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Your page description here" />
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

    <section id="inner-headline">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="inner-heading">
              <ul class="breadcrumb">
                <li><a href="index.php">Beranda</a> <i class="icon-angle-right"></i></li>
                <li class="active">Informasi</li>
              </ul>
              <h2>Unduhan File</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="content">
      <div class="container">
        <div class="row">
          <div class="span8">
            <!-- <h4 class="title"><strong>Unduhan</strong> File<span></span></h4> -->
              <div class="widget">
                <form method="post" action="">
                  <div class="input-append">
                    <input class="span3" id="appendedInputButton" type="text" name="Judul" value="<?php echo @$_REQUEST['Judul']; ?>" placeholder="Nama..">
                    <button class="btn btn-theme" type="submit" name="CariData">CARI</button>
                  </div>
                </form>
              </div> 

              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama File Unduhan</th>
                    <!-- <th>Tahun</th> -->
                    <th width="20%">Unduhan</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                      include '../library/pagination1.php';
                    //jika tidak ada pencarian pakai ini
                      // $reload = "Unduhan.php?pagination=true&id=$caridata";
                      if(isset($_REQUEST['Judul']) && $_REQUEST['Judul']<>""){
                        // jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)pakai ini
                        $keyword=$_REQUEST['Judul'];
                        $reload = "Unduhan.php?pagination=true&keyword=$keyword";
                        $sql =  "SELECT IsiKonten,JudulKonten,KodeKonten,JenisKonten,username,TanggalKonten,Gambar1  FROM kontenweb WHERE  JudulKonten LIKE '%$keyword%' and JenisKonten='Dokumen' ORDER BY TanggalKonten ASC";
                        $result = mysqli_query($koneksi,$sql);
                      }else{
                      //jika tidak ada pencarian pakai ini
                        $reload = "Unduhan.php?pagination=true";
                        $sql =  "SELECT IsiKonten,JudulKonten,KodeKonten,JenisKonten,username,TanggalKonten,Gambar1  FROM kontenweb where JenisKonten='Dokumen' ORDER BY TanggalKonten ASC";
                        @$result = mysqli_query($koneksi,$sql);
                      }
                      // $sql =  "SELECT IsiKonten,JudulKonten,KodeKonten,JenisKonten,username,TanggalKonten,Gambar1 
                      // FROM kontenweb  
                      // WHERE JenisKonten='Dokumen'";
                      // // if($caridata != '') {
                      // //   $sql .=" AND JudulKonten LIKE '%$caridata%' OR IsiKonten LIKE '%$caridata%' ";
                      // // }
                      // $sql .=" ORDER BY TanggalKonten DESC";
                      
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
                      while(($count<$rpp) && ($i<$tcount)) {
                        mysqli_data_seek($result,$i);
                        $data = mysqli_fetch_array($result); ?>
          
                  <tr>
                      <td><?=++$no_urut?></td>
                      <td><?=$data['JudulKonten']?></td>
                      <!-- <td><?=$data['IsiKonten']?></td> -->
                      <td><a href="../images/Dokumen/Unduhan/<?=$data['Gambar1']?>" class="btn btn-info btn-sm animated fadeInUp"><i class="icon-download-alt"></i> Download </a></td>
                  </tr>
                  <?php
                      $i++; 
                      $count++;
                    }
                    if($tcount==0){
                      echo '<tr><td colspan="4"><strong><center>Tidak ada data</center></strong></td></tr>';
                    }
                  ?>
                </tbody>
              </table>
              <div class="pagination"> 
              <?php echo paginate_one($reload, $page, $tpages); ?> 
            </div>
          </div>
          <div class="span4">
            <div class="clearfix"></div>
            <aside class="right-sidebar">

              <div class="widget">
                <h5 class="widgetheading">INFORMASI KONTAK<span></span></h5>
                <ul class="contact-info">
				          <li><label>Alamat :</label> <?php echo sistemSetting($koneksi, '4', 'value'); ?></li>
                  <li><label>Telephone & Fax :</label> <?php echo sistemSetting($koneksi, '2', 'value'); ?></li>
                  <li><label>Email : </label> <?php echo sistemSetting($koneksi, '3', 'value'); ?></li>
                </ul>

              </div>
            </aside>
          </div>
        </div>
      </div>
    </section>

    <!-- start footer -->
		<?php include 'footer.php';?>
    <!-- end footer -->
  </div>
  <!-- <a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bglight icon-2x active"></i></a> -->
  <!-- <a href="Pengaduan.php" class="scrollup"><i class="icon-comments-alt icon-circled icon-bgsuccess icon-2x "></i></a> -->

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

  <script src="js/jquery.prettyPhoto.js"></script>
  <script src="js/portfolio/jquery.quicksand.js"></script>
  <script src="js/portfolio/setting.js"></script>

  <script src="js/jquery.flexslider.js"></script>
  <script src="js/animate.js"></script>
  <script src="js/inview.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Custom JavaScript File -->
  <script src="js/custom2.js"></script>
  <!-- <script src="js/custom.js"></script> -->


</body>

</html>
