
											<label>Koordinat Lokasi</label>
									
										<input id="pacinputpengambilan" type="text" placeholder="Tentukan Lokasi" style="z-index: 0; position: absolute; left: 113px; top: 300px;" required>
											<div id="mappengambilan" style="height:350px;width:100%;margin-bottom:30px;"></div>
											<input id="latpengambilan" name="Lat" type="hidden" value="<?php echo @$RowData['Lat'];?> ">
											<input id="lngpengambilan" name="Lng" type="hidden" value="<?php echo @$RowData['Lng']; ?>">
											<script>
											function initAutocomplete() {
											  var map_ambil = new google.maps.Map(document.getElementById('mappengambilan'), {
												center: {lat: -7.556032627191996, lng: 112.221},
												zoom: 12,
												//mapTypeId: 'satellite'
												mapTypeControl: false,
											  });

											  // Create the search box and link it to the UI element.
											  var input_ambil = document.getElementById('pacinputpengambilan');
											  var searchBox_ambil = new google.maps.places.SearchBox(input_ambil);
												  map_ambil.controls[google.maps.ControlPosition.TOP_LEFT].push(input_ambil);
											  // Bias the SearchBox results towards current map's viewport.
											  map_ambil.addListener('bounds_changed', function() {
												searchBox_ambil.setBounds(map_ambil.getBounds());
											  });

											  var markers_ambil = [];
											  											  
											  var places_ambil = [];
											  if(lat != 0 && lng != 0){
												places_ambil.push({
													geometry : {
														location : {lat:lat,lng:lng}
													}
												});

												// Clear out the old markers.
												markers_ambil.forEach(function(marker_ambil) {
												  marker_ambil.setMap(null);
												});
												markers_ambil = [];
												  
												// For each place, get the icon, name and location.
												var bounds_ambil = new google.maps.LatLngBounds();
												for (var j = 0, place_ambil; place_ambil = places_ambil[j]; j++) {
												  var image_ambil = {
													url: place_ambil.icon,
													url: "../library/map-marker.png",
													size: new google.maps.Size(100, 100),
													origin: new google.maps.Point(0, 0),
													anchor: new google.maps.Point(17, 45),
													scaledSize: new google.maps.Size(34, 45)
												  };
												  var marker_ambil = new google.maps.Marker({
													draggable: true,
													map: map_ambil,
													icon: image_ambil,
													title: place_ambil.name,
													position: place_ambil.geometry.location
												  });

												  console.log(JSON.stringify(place_ambil.geometry.location));

												  document.getElementById('latpengambilan').value = marker_ambil.getPosition().lat().toFixed(6);
												  document.getElementById('lngpengambilan').value = marker_ambil.getPosition().lng().toFixed(6);
												  // drag response
												  google.maps.event.addListener(marker_ambil, 'dragend', function(e) {
													displayPosition_ambil(this.getPosition());
												  });
												  // click response
												  google.maps.event.addListener(marker_ambil, 'click', function(e) {
													displayPosition_ambil(this.getPosition());
												  });
												  markers_ambil.push(marker_ambil);
												  bounds_ambil.extend(place_ambil.geometry.location);
												}
											  }
												  
											  // Listen for the event fired when the user selects a prediction and retrieve
											  // more details for that place.
											  searchBox_ambil.addListener('places_changed', function() {
												var places_ambil = searchBox_ambil.getPlaces();

												if (places_ambil.length == 0) {
												  return;
												}

												// Clear out the old markers.
												markers_ambil.forEach(function(marker_ambil) {
												  marker_ambil.setMap(null);
												});
												markers_ambil = [];
												  
												// For each place, get the icon, name and location.
												var bounds_ambil = new google.maps.LatLngBounds();
												for (var j = 0, place_ambil; place_ambil = places_ambil[j]; j++) {
												  var image_ambil = {
													url: place_ambil.icon,
													url: "../library/map-marker.png",
													// url: "http://afindo-inf.com/metrologi/web/images/map-marker.png",
													size: new google.maps.Size(100, 100),
													origin: new google.maps.Point(0, 0),
													anchor: new google.maps.Point(17, 45),
													scaledSize: new google.maps.Size(34, 45)
												  };
												  var marker_ambil = new google.maps.Marker({
													draggable: true,
													map: map_ambil,
													icon: image_ambil,
													title: place_ambil.name,
													position: place_ambil.geometry.location
												  });


												  document.getElementById('latpengambilan').value = marker_ambil.getPosition().lat().toFixed(6);
												  document.getElementById('lngpengambilan').value = marker_ambil.getPosition().lng().toFixed(6);
												  // drag response
												  google.maps.event.addListener(marker_ambil, 'dragend', function(e) {
													displayPosition_ambil(this.getPosition());
												  });
												  // click response
												  google.maps.event.addListener(marker_ambil, 'click', function(e) {
													displayPosition_ambil(this.getPosition());
												  });
												  markers_ambil.push(marker_ambil);
												  bounds_ambil.extend(place_ambil.geometry.location);
												}

												map_ambil.fitBounds(bounds_ambil); 				
											  });

											  google.maps.event.addListener(map_ambil, 'bounds_changed', function() {
												var bounds_ambil = map_ambil.getBounds();
												searchBox_ambil.setBounds(bounds_ambil);
											  });

											  // displays a position on two <input> elements
											  function displayPosition_ambil(post) {
												document.getElementById('latpengambilan').value = post.lat();
												document.getElementById('lngpengambilan').value = post.lng();
											  }
											}
											
											</script>
											
									  
								