
	<footer>
      <div class="container">
        <div class="row">
          <div class="span2">
            <div class="widget">
              <h5 class="widgetheading">Profil Kami</h5>
              <ul class="link-list" style="font-size:12pt;">
                <li><a href="Profil.php?id=<?php echo base64_encode('5'); ?>">Visi dan Misi</a></li>
        				<li><a href="Profil.php?id=<?php echo base64_encode('6'); ?>">Selayang Pandang</a></li>
        				<li><a href="Profil.php?id=<?php echo base64_encode('7'); ?>">Sejarah</a></li>
        				<li><a href="Organisasi.php">Struktur Organisasi</a></li>
        				<li><a href="Profil.php?id=<?php echo base64_encode('8'); ?>">Rencana Strategis</a></li>
        				<li><a href="Profil.php?id=<?php echo base64_encode('9'); ?>">Program Kerja</a></li>
              </ul>
            </div>
          </div>
          <div class="span4">
            <div class="widget">
			
              <h5 class="widgetheading">Kontak Kami</h5>
                <address>
        					<strong><?php echo sistemSetting($koneksi, '1', 'value'); ?></strong><br>
        					<?php echo sistemSetting($koneksi, '4', 'value'); ?>
        				</address>
              <p>
                <i class="icon-phone"></i> <?php echo sistemSetting($koneksi, '2', 'value'); ?><br>
                <i class="icon-envelope-alt"></i> <?php echo sistemSetting($koneksi, '3', 'value'); ?>
              </p>
              <?php 
                $sosmed  = mysqli_query($koneksi,"SELECT * FROM setting where nama='SOSMED'");
                while ($rowsosmed = mysqli_fetch_assoc($sosmed)) {

                  echo '<a href="'.$rowsosmed['value'].'" Target="_BLANK"><img src="../images/Assets/'.$rowsosmed['file'].'" style="width: 30px; height: 30px;"></a>&nbsp;'; 
                 
                }
              ?>
              
               
            </div>
          </div>
          <div class="span3">
            <div class="widget">
              <h5 class="widgetheading">Statistik Website</h5>
              <table>
                <tr>
                  <td>Pengunjung Hari Ini </td>
                  <td> :</td>
                  <td><?=$todays?></td>
                </tr>

                <tr>
                  <td>Total Pengunjung </td>
                  <td> :</td>
                  <td><?=$visitor?></td>
                </tr>
                <tr>
                  <td>Hits Hari Ini </td>
                  <td> :</td>
                  <td><?=$hitsnow?></td>
                </tr>
                <tr>
                  <td>Total Hits </td>
                  <td> :</td>
                  <td><?=counter($koneksi)?></td>
                </tr>
                <tr>
                  <td>Pengunjung Online </td>
                  <td> :</td>
                  <td><?=UserOnline($koneksi)?></td>
                </tr>
                

              </table>
             
            </div>
          </div>

          <div class="span3">
            <div class="widget">
              <h5 class="widgetheading"><?php echo sistemSetting($koneksi, '10', 'nama'); ?></h5>
               <iframe src="<?php echo sistemSetting($koneksi, '10', 'value'); ?>" width="100%"  height="200" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
          </div>

        </div>
      </div>
      <div id="sub-footer">
        <div class="container">
          <div class="row">
            <div class="span6">
              <div class="copyright">
                <p><span>&copy; 2019 <?php echo sistemSetting($koneksi, '1', 'value'); ?>. All right reserved</span></p>
              </div>

            </div>

            <div class="span6">
              <div class="credits">
                Designed by <a href="https://afindo-inf.com/" Target="_BLANK">Afindo Informatika</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/5f1a897ea45e787d128c0891/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->
	
