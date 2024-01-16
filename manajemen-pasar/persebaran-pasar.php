<?php 
include 'head.php';
include '../library/pagination1.php';
date_default_timezone_set('Asia/Jakarta');

$date = date('Y-m-d');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : $date;
$date_minus_sebulan = date('Y-m-d', strtotime($Tanggal.' -1 month')); 

$sql_p = "SELECT p.*, b.NamaKabupaten, c.NamaKecamatan, d.NamaDesa
FROM mstpasar p
INNER JOIN mstkab b ON b.KodeKab = p.KodeKab
INNER JOIN mstkec c ON c.KodeKec = p.KodeKec AND c.KodeKab= p.KodeKab
INNER JOIN mstdesa d ON d.KodeDesa = p.KodeDesa AND d.KodeKec = p.KodeKec AND d.KodeKab = p.KodeKab
ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$datapasar = array();
while ($row_p = $res_p->fetch_assoc()) {
    array_push($datapasar, $row_p);
}
?>

<style>
/* Always set the map height explicitly to define the size of the div
* element that contains the map. */
#map {
    height: 100%;
}
</style>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="span12">
                <h4>Halaman Peta Persebaran Pasar</h4>
            </div>
            <div class="span12">
                <label>Koordinat Lokasi</label>
                <div id="mappengambilan" style="height:450px;width:100%;margin-bottom:30px;"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'foot.php';?>
<script>

    function initAutocomplete() {
        var map_ambil = new google.maps.Map(document.getElementById('mappengambilan'), {
            center: {lat: -7.556032627191996, lng: 112.221},
            zoom: 12,
            //mapTypeId: 'satellite'
            mapTypeControl: false,
        });
        
        var markers = [];
        var datapasar = <?php echo json_encode($datapasar); ?>;
        for (let i = 0; i < datapasar.length; i++) {
            const pasar = datapasar[i];
            var contentString = '<h4>'+pasar.NamaPasar+'</h4><table class="table stripped"><tbody><tr><td>Nama Kepala</td><td>'+pasar.NamaKepalaPasar+'</td></tr><tr><td>No Telepon</td><td>'+pasar.NoTelpPasar+'</td></tr><tr><td>Alamat Lengkap</td><td>'+pasar.NamaDesa+', '+pasar.NamaKecamatan+', '+pasar.NamaKabupaten+'</td></tr><tr><td colspan="2"><a href="hargapokok-detailpasar.php?psr='+encode(pasar.KodePasar)+'" class="btn btn-sm btn-primary">Lihat Harga Pokok</a></td></tr></tbody></table>';
            var marker = new google.maps.Marker({
                position: {lat: parseFloat(pasar.KoorLat), lng: parseFloat(pasar.KoorLong)},
                map: map_ambil,
                title: pasar.NamaPasar
            });
            markers.push({
                marker:marker, contentinfo:contentString
            });
        }

        for (let j = 0; j < markers.length; j++) {
            const mm = markers[j];
            addInfoWindow(map_ambil, mm.marker, mm.contentinfo);
        }
    }

    function addInfoWindow(map, marker, message) {
        var infoWindow = new google.maps.InfoWindow({
            content: message
        });

        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.open(map, marker);
        });
    }

    // Function to encode a string to base64 format
    function encode(str) {
        encodedString = btoa(str);
        return encodedString;
    }

    // Function to decode a string from base64 format
    function decode(str) {
        decodedString = atob(str);
        return decodedString;
    }
</script>

<!-- Maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaX_RkNdrKRsaMYOp9PCOj11JgrZNgR6w&language=id&region=ID&libraries=places&callback=initAutocomplete"></script>
<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>