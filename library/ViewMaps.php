<script src="http://maps.google.com/maps/api/js?key=AIzaSyCaX_RkNdrKRsaMYOp9PCOj11JgrZNgR6w&sensor=false"></script>
<script>
		
    var marker;
      function initialize() {
		  
		// Variabel untuk menyimpan informasi (desc)
		var infoWindow = new google.maps.InfoWindow;
		
		//  Variabel untuk menyimpan peta Roadmap
		var mapOptions = {
          mapTypeId: google.maps.MapTypeId.ROADMAP
        } 
		
		// Pembuatan petanya
		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
              
        // Variabel untuk menyimpan batas kordinat
		var bounds = new google.maps.LatLngBounds();

		// Pengambilan data dari database
		<?php
            $query = mysqli_query($koneksi,"select * from timbanganperson where IDPerson='".base64_decode($_GET['id'])."'");
			while ($data = mysqli_fetch_array($query))
			{
				$lat = $data['KoorLat'];
				$lon = $data['KoorLong'];
				echo ("addMarker($lat, $lon, '<b></b>');\n");                        
			}
          ?>
		
		  
		  
		// Proses membuat marker 
		function addMarker(lat, lng, info) {
			var lokasi = new google.maps.LatLng(lat, lng);
			bounds.extend(lokasi);
			 var gambar = {
				url: "../web/images/map-marker.png", // url
				scaledSize: new google.maps.Size(50, 50), // scaled size
				origin: new google.maps.Point(0,0), // origin
				anchor: new google.maps.Point(0, 0) // anchor
			};
			var marker = new google.maps.Marker({
				map: map,
				position: lokasi,
				icon: gambar
			});       
			map.fitBounds(bounds);
			bindInfoWindow(marker, map, infoWindow, info);
		 }
		
		// Menampilkan informasi pada masing-masing marker yang diklik
        function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
          });
        }
 
        }
      google.maps.event.addDomListener(window, 'load', initialize);
    
	</script>