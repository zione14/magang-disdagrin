
    	
	<!-- start footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="span4">
            <div class="widget">
              <h5 class="widgetheading">Profil Kami</h5>
              <ul class="link-list" style="font-size:12pt;">
                <li><a href="../profil/Profil.php?id=<?php echo base64_encode('5'); ?>">Visi dan Misi</a></li>
				<li><a href="../profil/Profil.php?id=<?php echo base64_encode('6'); ?>">Selayang Pandang</a></li>
				<li><a href="../profil/Profil.php?id=<?php echo base64_encode('7'); ?>">Sejarah</a></li>
				<li><a href="../profil/Organisasi.php">Struktur Organisasi</a></li>
				<li><a href="../profil/Profil.php?id=<?php echo base64_encode('8'); ?>">Rencana Strategis</a></li>
				<li><a href="../profil/Profil.php?id=<?php echo base64_encode('9'); ?>">Program Kerja</a></li>
              </ul>
            </div>
          </div>
          <div class="span4">
            <div class="widget">
              <h5 class="widgetheading">Kontak Kami</h5>
              <p><strong>Dinas Perdagangan dan Perindustrian Jombang</strong><br>
                    Jl. Wahid Hasyim No.143 Kepanjen, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61411</p>
            </div>
          </div>
        </div>
      </div>
      <div id="sub-footer">
        <div class="container">
          <div class="row">
            <div class="span6">
              <div class="copyright">
                <p><span>&copy; 2019. All right reserved</span></p>
              </div>

            </div>

            <div class="span6">
              <div class="credits">
                Designed by <a href="#" Target="_BLANK">Afindo Inf</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- end footer -->
  </div>
  <a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bglight icon-2x active"></i></a>

  <!-- javascript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="../assets/js/jquery.js"></script>
  <script src="../assets/js/jquery.easing.1.3.js"></script>
  <script src="../assets/js/bootstrap.js"></script>

  <script src="../assets/js/modernizr.custom.js"></script>
  <script src="../assets/js/toucheffects.js"></script>
  <script src="../assets/js/google-code-prettify/prettify.js"></script>
  <script src="../assets/js/jquery.bxslider.min.js"></script>
  <script src="../assets/js/camera/camera.js"></script>
  <script src="../assets/js/camera/setting.js"></script>

  <script src="../assets/js/jquery.prettyPhoto.js"></script>
  <script src="../assets/js/portfolio/jquery.quicksand.js"></script>
  <script src="../assets/js/portfolio/setting.js"></script>

  <script src="../assets/js/jquery.flexslider.js"></script>
  <script src="../assets/js/animate.js"></script>
  <script src="../assets/js/inview.js"></script>

  <!-- Template Custom JavaScript File -->
  <script src="../assets/js/custom.js"></script>

  <script>

  function showsnakbar() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
  </script>
</body>
</html>
