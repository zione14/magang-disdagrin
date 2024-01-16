<?php
include '../library/config.php';
include '../library/tgl-indo.php';
include '../library/sistemsetting.php';
@$KodeKonten = htmlspecialchars(base64_decode($_GET['id']));
@$JenisData  = htmlspecialchars(base64_decode($_GET['jd']));
$Page='Informasi';
$Sekarang 		= date('Y-m-d');

$sql = mysqli_query($koneksi, "SELECT IsiKonten,JudulKonten,KodeKonten,JenisKonten,username,TanggalKonten FROM kontenweb  WHERE JenisKonten='$JenisData' AND KodeKonten='$KodeKonten'");
$data = mysqli_fetch_array($sql);
function get_user_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
$IP=get_user_ip();

//Transaksi dengan ip yang sama 
 $cekipemot = mysqli_query($koneksi, "SELECT * FROM tanggapankonten where KodeKonten='$KodeKonten' and JenisKonten='$JenisData' and  EmailPengirim='Emoticon' AND IsAktif=b'1' AND IP='$IP' AND DATE_FORMAT(TanggalKomentar,'%Y-%m-%d') = '$Sekarang'");
 $resultEmot	= mysqli_fetch_assoc($cekipemot);
 $HasilPen 	    = mysqli_num_rows($cekipemot);

 // echo $HasilPen;

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

	<style type="text/css">
		.emot {
		width: 16%; 
		position: relative;
		margin:0 auto;
		line-height: 1.4em;
		float: left;
		}

		@media only screen and (max-width: 479px){
		    #emot { width: 90%; }
		}

		.emoticon:hover {
		  -ms-transform: scale(1.2); /* IE 9 */
		  -webkit-transform: scale(1.2); /* Safari 3-8 */
		  transform: scale(1.2); 
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

    <section id="inner-headline">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="inner-heading">
              <ul class="breadcrumb">
                <li><a href="index.php">Beranda</a> <i class="icon-angle-right"></i></li>
                <li class="active">Detil</li>
              </ul>
              <h2>Detil <?php echo $JenisData; ?></h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="content">
      <div class="container">
        <div class="row">
          <div class="span8">
			<article>
			  <div class="row">
				<div class="span8">
					<div class="post-slider">
						<div class="post-heading">
						  <h3><a href="#"><?php echo $data['JudulKonten']; ?></a></h3>
						</div>
						<div class="clear"></div>
						<!-- start flexslider -->
						<div class="flexslider">
						  <ul class="slides">
							<?php 	
								$Foto = mysqli_query($koneksi,"SELECT Dokumen FROM detailkonten WHERE JenisKonten='".$data['JenisKonten']."'  AND KodeKonten='".$data['KodeKonten']."'");
								$JumlahFoto = mysqli_num_rows($Foto);
								if($JumlahFoto == 0){
									echo ' <li><img src="../images/web_profil/image/thumb_noimage.png" class="img img-thumbnail img-responsive"></li>';
								}else{
									while($row=mysqli_fetch_array($Foto)){
										if($JenisData == 'Berita'){
											echo '<li><img src="../images/web_profil/berita/'.$row['Dokumen'].'"  style="width:100%; height:500px;"alt=""></li>';
										}else{
											echo '<li><img src="../images/web_profil/artikel/'.$row['Dokumen'].'"  style="width:100%; height:500px;"alt=""></li>';
										}
									}
								}
							?>
						  </ul>
						</div>
					</div>
					<div class="meta-post">
						<ul>
						  <li><i class="icon-picture"></i></li>
						  <li>By <a href="#" class="author"><?php echo $data['username']; ?></a></li>
						  <li>Tanggal <a href="#" class="date"><?php echo TanggalIndo($data['TanggalKonten']); ?></a></li>
						  <!--<li>Tags: <a href="#">Design</a>, <a href="#">Blog</a></li>-->
						</ul>
					</div>
					<div class="post-entry" style=" text-align: justify;">
						<p>
							<?php echo @$data['IsiKonten']; ?>
						</p>
						<a href="<?php echo $JenisData; ?>.php" class="btn btn-danger btn-sm animated fadeInUp"><i class="icon-angle-left"></i> Kembali</a>
					</div>
					<hr>
					<div align="center"><h4>Apa Komentar Anda?</h4></div>
					<div align="center">
						<h5>
							<strong id="jmlKomentar"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', '')?></strong> Komentar
						</h5>
					</div>
					
					<div class="emot" align="center">
						<img src="img/upvote.png" id="1" class="emoticon" onclick="simpanVote(1)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Upvote' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Upvote</p>
						<strong id="txtupvote"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Upvote')?></strong>
					</div>
					<div  class="emot" align="center">
						<img src="img/funny.png" id="2" class="emoticon" onclick="simpanVote(2)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Funny' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Funny</p>
						<strong id="txtfunny"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Funny')?></strong>
					</div>
					<div  class="emot" align="center">
						<img src="img/love.png" id="3" class="emoticon" onclick="simpanVote(3)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Love' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Love</p>
						<strong id="txtlove"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Love')?></strong>
					</div>
					<div  class="emot" align="center">
						<img src="img/surprised.png" id="4" class="emoticon" onclick="simpanVote(4)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Surprised' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Surprised</p>
						<strong id="txtsurprised"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Surprised')?></strong>
					</div>
					<div class="emot" align="center">
						<img src="img/angry.png" id="5" class="emoticon" onclick="simpanVote(5)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Angry' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Angry</p>
						<strong id="txtangry"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Angry')?></strong>
					</div>
					<div class="emot" align="center">
						<img src="img/sad.png" id="6" class="emoticon" onclick="simpanVote(6)" style="max-width:50%; width: 50%; height: 50%; cursor: pointer; <?php echo isset($HasilPen) && $HasilPen == 0 ? 'filter: grayscale(0%);' :  isset($resultEmot['IsiKomentar']) && $resultEmot['IsiKomentar'] == 'Sad' ? 'filter: grayscale(0%);' : 'filter: grayscale(100%);'; ?> ">
						<p>Sad</p>
						<strong id="txtsad"><?=jumlahKomentar($koneksi, $KodeKonten, $JenisData, 'Emoticon', 'Sad')?></strong>
					</div>
					<br>
					<hr/>

					<div class="comment-area">

		            <h4>Baca Komentar</h4>
		            <?php
		            $Komentar = mysqli_query($koneksi,"SELECT * FROM tanggapankonten WHERE JenisKonten='".$data['JenisKonten']."'  AND KodeKonten='".$data['KodeKonten']."' AND IsAktif='1' AND EmailPengirim !='Emoticon'");
		            $JumlahKomentar = mysqli_num_rows($Komentar);
		            if($JumlahKomentar == 0){
						echo 'Tidak Ada Komentar';
					}else{

					while($rowkomen = mysqli_fetch_array($Komentar)){
					?>
		              
	              	<div class="media">
	                <a href="#" class="pull-left"><img src="img/umum.png" style="width: 50px; height: 50px;" alt="" class="img-circle" /></a>
	                <div class="media-body">
	                  <div class="media-content">
	                    <h6><span><?=TanggalIndo($rowkomen['TanggalKomentar'])?></span> <?=$rowkomen['EmailPengirim']?></h6>
	                    <p>
	                      <?=$rowkomen['IsiKomentar']?>
	                    </p>
	                  </div>
	                  <?php if($rowkomen['IsiTanggapan'] != null OR $rowkomen['IsiTanggapan'] != '') : ?>
	                  <div class="media">
	                    <a href="#" class="pull-left"><img src="img/admin.png" style="width: 50px; height: 50px;" alt="" class="img-circle" /></a>
	                    <div class="media-body">
	                      <div class="media-content">
	                        <h6><span><?=TanggalIndo($rowkomen['TanggalTanggapan'])?></span> <?=$rowkomen['UserName']?></h6>
	                        <p>
	                          <?=$rowkomen['IsiTanggapan']?>
	                        </p>
	                      </div>
	                    </div>
	                  </div>
	              	  <?php endif; ?>
	                </div>
	              </div>
	            <?php } 
	            	}
	            ?>

	              <div class="marginbot30"></div>
	              <h4>Tinggalkan Komentar Anda</h4>
	              <form id="commentform" action="Detil.php" method="post" name="comment-form">
	                <div class="row">
	                  <div class="span8 margintop10">
	                    <input type="text" name="email" placeholder="masukkan email anda" />
	                     <input type="hidden" name="jenis" value="<?php echo $JenisData; ?>">
	                    <input type="hidden" name="kode" value="<?php echo $KodeKonten; ?>">
	                  </div>
	                  <div class="span8 margintop10">
	                    
	                     <textarea name="message" rows="5" required placeholder="Pesan"></textarea>
	                    
	                    <p>
	                      <button class="btn btn-theme btn-medium margintop10" name="submit" type="submit">Kirim Komentar</button>
	                    </p>
	                  </div>
	                </div>
	              </form>
	            </div>
				</div>
			  </div>
			</article>
          </div>
		  <div class="span4">
            <aside class="right-sidebar">
			  <div class="widget">
                <div class="tabs">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#one" data-toggle="tab"><i class="icon-star" style="color: #d1a711"></i> <?php echo $JenisData; ?> Terbaru </a></li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="one">
                      <ul class="popular">
						<?php 
							$GaleriFoto = mysqli_query($koneksi,"SELECT KodeKonten,JudulKonten,TanggalKonten FROM kontenweb WHERE JenisKonten='$JenisData' AND IsAktif=b'1' ORDER BY TanggalKonten DESC Limit 0,5 ");
							$num =mysqli_num_rows($GaleriFoto);
							while($row=mysqli_fetch_array($GaleriFoto)){
								$gambar = mysqli_query($koneksi,"SELECT Dokumen FROM detailkonten where KodeKonten= '".$row['KodeKonten']."' AND JenisKonten='$JenisData' LIMIT 1");
								$gbr=mysqli_fetch_assoc($gambar);
								if($JenisData == 'Berita'){
									echo '<li>
										 <img src="../images/web_profil/berita/'.$gbr['Dokumen'].'" alt="" class="thumbnail pull-left" style="width:65px; height:65px;" />
										 <p><a href="Detil.php?id='.base64_encode($row['KodeKonten']).'&jd='.base64_encode($JenisData).'">'.strtoupper(substr($row['JudulKonten'], 0, 30)). '...'.'</a></p>
										 <span>'.TanggalIndo($row['TanggalKonten']).'</span></li>';
								}else{
									echo '<li>
										 <img src="../images/web_profil/artikel/'.$gbr['Dokumen'].'" alt="" class="thumbnail pull-left" style="width:65px; height:65px;" />
										 <p><a href="Detil.php?id='.base64_encode($row['KodeKonten']).'&jd='.base64_encode($JenisData).'">'.strtoupper(substr($row['JudulKonten'], 0, 30)). '...'.'</a></p>
										 <span>'.TanggalIndo($row['TanggalKonten']).'</span></li>';
								}
							}
						?>
                      </ul>
                    </div>
                  </div>
                </div>
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

  <!-- Template Custom JavaScript File -->
  <script src="js/custom2.js"></script>

  <script type="text/javascript">
  	function simpanVote(num) {
  		var KodeKonten = "<?php echo $KodeKonten; ?>";
  		var JenisData = "<?php echo $JenisData; ?>";
  		var IPUser = "<?php echo $IP; ?>";

  		if (num == "1") {
  			var IsiKomentar = "Upvote";
   		} else if(num == "2"){
   			var IsiKomentar = "Funny";
  		} else if(num == "3"){
  			var IsiKomentar = "Love";
  		} else if(num == "4"){
  			var IsiKomentar = "Surprised";
  		} else if(num == "5"){
  			var IsiKomentar = "Angry";
  		} else if(num == "6"){
  			var IsiKomentar = "Sad";

  		}

  		$.ajax({
			   url: "Komentar.php",
			   dataType: "json",
			   data : {KodeKonten: KodeKonten,JenisData: JenisData,IsiKomentar: IsiKomentar, IP: IPUser},
			   type: 'get',
			   success: function (response){

			   	  $('.emoticon').css('filter', 'grayscale(100%)');
			   	  $('#'+num).css('filter', 'grayscale(0%)');
			   	  document.getElementById('txtupvote').innerHTML 	= response[0];
			   	  document.getElementById('txtfunny').innerHTML 	= response[1];
			   	  document.getElementById('txtlove').innerHTML 		= response[2];
			   	  document.getElementById('txtsurprised').innerHTML = response[3];
			   	  document.getElementById('txtangry').innerHTML 	= response[4];
			   	  document.getElementById('txtsad').innerHTML 		= response[5];
			   	  document.getElementById('jmlKomentar').innerHTML  = response[6];

			   	  // alert(response[7]);
		   },
			error:function(error) {
		   }
		});
    };
  </script>
   <?php 
	@$email 		= htmlspecialchars($_POST['email']);
	@$isikomentar	= $_POST['message'];
	@$jnskonten	 	= htmlspecialchars($_POST['jenis']);
	@$konten	 	= htmlspecialchars($_POST['kode']);
	@$TglTransaksi 	= date('Y-m-d H:i:s');
	
	if(isset($_POST['submit'])){
		
		
		//membuat id user
		$year	 = date('Y');
		$sql 	 = mysqli_query($koneksi,'SELECT RIGHT(KodeTanggapan,7) AS kode FROM tanggapankonten WHERE KodeTanggapan LIKE "%'.$year.'%" ORDER BY KodeTanggapan DESC LIMIT 1');  
		$num	 = mysqli_num_rows($sql);
		 
		if($num <> 0) {
			$data = mysqli_fetch_array($sql);
			$kode = $data['kode'] + 1;
		}else{
			$kode = 1;
		}
		 
		//mulai bikin kode
		 $bikin_kode = str_pad($kode, 7, "0", STR_PAD_LEFT);
		 $kode_jadi	 = "KMTR-".$year."-".$bikin_kode;
		
		$Simpan = mysqli_query($koneksi,"INSERT INTO tanggapankonten (KodeTanggapan,KodeKonten,JenisKonten,EmailPengirim,TanggalKomentar,IsiKomentar,IsAktif,IP)VALUES('$kode_jadi','$konten','$jnskonten','$email','$TglTransaksi', '$isikomentar',b'1','$IP')");

		 // echo $isikomentar;
		
		if($Simpan){
			echo '<script language="javascript">document.location="Detil.php?id='.base64_encode($konten).'&jd='.base64_encode($jnskonten).'"; </script>';
		}else{
			echo '<script language="javascript">alert(Maaf, Komentar Gagal disimpan!"); document.location="Detil.php?id='.base64_encode($konten).'&jd='.base64_encode($jnskonten).'"; </script>';
		}
		
	}

	?>

</body>

</html>
