<?php 
include '../library/config.php';
include '../library/sistemsetting.php';
$Page='Layanan';
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
                <li class="active">Layanan Masyarakat</li>
              </ul>
              <h2>Sistem Layanan Pengaduan Masyarakat</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="content">
      <div class="container">
        <div class="row">
          <div class="span8">
            <h4>Pengaduan Masyarakat</h4>
            <form action="" method="post"  >
              <div class="row contactForm">
                <div class="span4 form-group field">
                  <input type="text" name="telepon"  placeholder="Nomor yang bisa dihubungi" required>
                </div>

                <div class="span4 form-group">
                  <input type="email" name="email"  placeholder="Email*" required>
                </div>
                <div class="span8 form-group">
                  <textarea name="message" rows="5" required placeholder="Masukkan pesan, saran, kritik, keluahan anda"></textarea>
                  <div class="validation"></div>
                  <div class="text-center">
                    <button class="btn btn-theme btn-medium margintop10" name="submit" type="submit">Kirim Pesan</button>
                  </div>
                </div>
              </div>
            </form>
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
<?php 
//======== Kirim Pesan ======//
	@$telepon 		= htmlspecialchars($_POST['telepon']);
	@$email 		= htmlspecialchars($_POST['email']);
	@$message	 	= htmlspecialchars($_POST['message']);
		
	if(isset($_POST['submit'])){		
		//membuat id user
		$year	 = date('Y');
		$sql 	 = mysqli_query($koneksi,'SELECT RIGHT(KodePengaduan,7) AS kode FROM trpengaduan WHERE KodePengaduan LIKE "%'.$year.'%" ORDER BY KodePengaduan DESC LIMIT 1');  
		$num	 = mysqli_num_rows($sql);
		 
		if($num <> 0) {
			$data = mysqli_fetch_array($sql);
			$kode = $data['kode'] + 1;
		}else{
			$kode = 1;
		}
		 
		//mulai bikin kode
		 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		 $kode_jadi_kontak	 = "ADUAN-".$year."-".$bikin_kode;
		
		$Simpan = mysqli_query($koneksi,"INSERT INTO trpengaduan (KodePengaduan,TglPengaduan,Telepon,Email,Pesan,Status)VALUES('$kode_jadi_kontak',NOW(),'$telepon','$email','$message','Belum dibaca')");
		
		if($Simpan){
			echo '<script language="javascript">alert("Pesan Berhasil Dikirim, Pesan akan dibalas melalui Email Anda!"); document.location="Pengaduan.php"; </script>';
		}else{
			echo '<script language="javascript">alert(Maaf, Pesan Gagal Dikirim!"); document.location="Pengaduan.php"; </script>';
		}
		
	}
	

?>
</html>
