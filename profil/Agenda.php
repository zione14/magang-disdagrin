<?php
include_once '../library/config.php';
include '../library/tgl-indo.php';
include '../library/sistemsetting.php';

@$KodeKonten = htmlspecialchars(base64_decode($_GET['id']));
@$JenisData  = htmlspecialchars(base64_decode($_GET['jd']));
$Page='Informasi';
//pencarian data
if(isset($_POST['CariData'])){
	@$_SESSION['Judul']=htmlspecialchars($_POST['Judul']);
}

//reset pencarian
if(isset($_POST['ResetData'])){
	unset($_SESSION['Judul']);
	echo '<script language="javascript">document.location="Galeri.php";</script>';
} ?>
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
                <li class="active">Agenda</li>
              </ul>
              <h2>Agenda</h2>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="content">
      <div class="container">
        <div class="row">

         <div class="span9">
		   <div id="thumbs" >
		  
		  	<?php
				include '../library/pagination1.php';
				//jika tidak ada pencarian pakai ini
				$reload = "Agenda.php?pagination=true&id=".@$_GET['id']."&jd=".@$_GET['jd']."";
				$sql =  "SELECT IsiKonten,JudulKonten,KodeKonten,JenisKonten,username,TanggalKonten FROM kontenweb  WHERE JenisKonten='Agenda' AND JudulKonten LIKE '%".@$_SESSION['Judul']."%' ";
					
					if($KodeKonten!=null){ $sql .= " AND KodeKonten='$KodeKonten' "; }
					
					$sql .=" ORDER BY TanggalKonten DESC";
					
					$result = mysqli_query($koneksi,$sql);
					
					//pagination config start
					$rpp = 5; // jumlah record per halaman
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
						$data = mysqli_fetch_array($result);
					?>
					 <!-- Item Project and Filter Name -->
					   <article>
						  <div class="row">
						   <div class="span9">
								<div class="post-heading">
								  <h3><?php echo $data['JudulKonten']; ?></h3>
								</div>
						   </div>
						   <div class="span1">
						   	<br>
						   		<i class="icon-rounded icon-bgsuccess icon-calendar icon-3x" ></i>
						   </div>

							<div class="span8">
								
							  <div class="post-entry" style=" text-align: justify;">
								<p >
									<?php echo @$data['IsiKonten']; ?>
								</p>
								
							  </div>
							  <div class="meta-post">
								<ul>
								  <li><i class="icon-picture"></i></li>
								  <li>By <a href="#" class="author"><?php echo $data['username']; ?></a></li>
								  <li>Tanggal <a href="#" class="date"><?php echo TanggalIndo($data['TanggalKonten']); ?></a></li>
								  <!--<li>Tags: <a href="#">Design</a>, <a href="#">Blog</a></li>-->
								</ul>
							  </div>
							  
							</div>
						</div>
					</article>
					
					<?php
						$i++; 
						$count++;
					}
					if($tcount==0){
						echo '<h3>Tidak ada data</h3>';
					}
					?>
					
				
					
				<div id="pagination"> 
					<?php echo paginate_one($reload, $page, $tpages); ?> 
				</div>
			</div>
					
          </div>

          <div class="span3">

            <aside class="right-sidebar">

              <div class="widget">
                <form method="post" action="">
                  <div class="input-append">
                    <input class="span2" id="appendedInputButton" type="text" name="Judul" value="<?php echo @$_SESSION['Judul']; ?>" placeholder="Nama..">
                    <button class="btn btn-theme" type="submit" name="CariData">Search</button>
                  </div>
                </form>
              </div>

              <div class="widget" style=" overflow: auto;   height: 500px;">

                <a href="Agenda.php"><h5 class="widgetheading">Agenda Terbaru</h5></a>

                <ul class="cat">
					<?php 
					$GaleriFoto = mysqli_query($koneksi,"SELECT KodeKonten,JudulKonten,IsiKonten,TanggalKonten FROM kontenweb WHERE JenisKonten='Agenda' AND IsAktif=b'1' ORDER BY TanggalKonten DESC Limit 0,7 ");
					$num =mysqli_num_rows($GaleriFoto);
					while($row=mysqli_fetch_array($GaleriFoto)){
						echo ' <li><i class="icon-calendar"></i> <a href="Agenda.php?id='.base64_encode($row['KodeKonten']).'&jd='.base64_encode($row['JudulKonten']).'" style="color:#eda618; font-size: 20px;">'.$row['JudulKonten']. '</a><span></span></li>';

						echo '<strong>'.TanggalIndo($row['TanggalKonten']).'</strong>';
						
						 $num_char = 100;
						  $cut_text = substr($row['IsiKonten'], 0, $num_char);
						  $str_num = str_word_count($row['IsiKonten']);
						  if($str_num >= 10){
							if ($row['IsiKonten']{$num_char - 1} != ' ') { // jika huruf ke 50 (50 - 1 karena index dimulai dari 0) buka  spasi
							$new_pos = strrpos($cut_text, ' '); // cari posisi spasi, pencarian dari huruf terakhir
							$cut_text = substr($row['IsiKonten'], 0, $new_pos);
							}
							echo @$cut_text . '...';  
						  }else{
							echo @$row['IsiKonten'];                            
						  }
						
						
					}
					?>
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
  <!--<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bglight icon-2x active"></i></a>-->
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


</body>

</html>
