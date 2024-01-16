<?php
include '../admin/akses.php';
$Page = 'MasterData';
$SubPage='MasterPasar';
$fitur_id = 19;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';
$LAT = 0;
$LONG = 0;

if(isset($_GET['k']) && $_GET['k'] != ''){
	$KodePasar = mysqli_escape_string($koneksi, base64_decode($_GET['k']));
	$sql = "SELECT * FROM mstpasar WHERE KodePasar = '$KodePasar'";
	$res_select = $koneksi->query($sql);
	$RowData = array();
	if($res_select){
		if(mysqli_num_rows($res_select) < 1){
			?>
			<script type="text/javascript">
				swal({
					title: "Error", 
					text: "Data tidak ditemukan", 
					icon: "error", 
					allowOutsideClick: false
				}).then(function() {
					location.href="MasterPasar.php";
				});
			</script>
			<?php
		}else{
			$RowData = mysqli_fetch_assoc($res_select);
			$KodeKab = $RowData['KodeKab'];
			$KodeKec = $RowData['KodeKec'];
            $KodeDesa = $RowData['KodeDesa'];
            $LAT = $RowData['KoorLat'];
            $LONG = $RowData['KoorLong'];
		}
	}else{
		?>
		<script type="text/javascript">
			swal({
				title: "Error", 
				text: "Terjadi kesalahan", 
				icon: "error", 
				allowOutsideClick: false
			}).then(function() {
				location.href="MasterPasar.php";
			});
		</script>
		<?php
	}
}

$sql_kec = "SELECT * FROM mstkec WHERE IF(length('$KodeKab') > 0, KodeKab = '$KodeKab', TRUE) ORDER BY NamaKecamatan ASC";
$res_kec = $koneksi->query($sql_kec);
$data_kec = array();
while ($row_kec = $res_kec->fetch_assoc()) {
    array_push($data_kec, $row_kec);
}

$data_des = array();
if(isset($KodeKec) && strlen($KodeKec) > 0){
    $sql_des = "SELECT * FROM mstdesa WHERE IF(length('$KodeKec') > 0, KodeKec = '$KodeKec', TRUE) ORDER BY NamaDesa ASC";
    $res_des = $koneksi->query($sql_des);
    while ($row_des = $res_des->fetch_assoc()) {
        array_push($data_des, $row_des);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php include '../admin/title.php';?>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="all,follow">
	<!-- Bootstrap CSS-->
	<link rel="stylesheet" href="../komponen/vendor/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome CSS-->
	<link rel="stylesheet" href="../komponen/vendor/font-awesome/css/font-awesome.min.css">
	<!-- Fontastic Custom icon font-->
	<link rel="stylesheet" href="../komponen/css/fontastic.css">
	<!-- Google fonts - Poppins -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
	<!-- theme stylesheet-->
	<?php include '../admin/style.php';?>
	<!-- Custom stylesheet - for your changes-->
	<link rel="stylesheet" href="../komponen/css/custom.css">
	<!-- Maps -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBqH_ctOCgwu5RLMrH6VdhCBLorpJXaDk&libraries=places"></script>
	<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
	<style>
	th {
		text-align: center;
	}
	
	#map #infowindow-content {
		display: inline;
	}

	.pac-card {
	  margin: 10px 10px 0 0;
	  border-radius: 2px 0 0 2px;
	  box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  outline: none;
	  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
	  background-color: #fff;
	  font-family: Roboto;
	}

	#pac-container {
	  padding-bottom: 12px;
	  margin-right: 12px;
	}

	.pac-controls {
	  display: inline-block;
	  padding: 5px 11px;
	}

	.pac-controls label {
	  font-family: Roboto;
	  font-size: 13px;
	  font-weight: 300;
	}

	#pacinput,#pacinputpengambilan {
	  background-color: #fff;
	  font-family: Roboto;
	  font-size: 15px;
	  font-weight: 300;
	  margin: 10px 12px;
	  padding: 5px;
	  text-overflow: ellipsis;
	  width: 250px;
	}

	#pacinput:focus {
	  border-color: #4d90fe;
	}
	</style>
</head>
<body>
	<div class="page">
		<!-- Main Navbar-->
		<?php include 'header.php'; ?>
		<div class="page-content d-flex align-items-stretch"> 
			<!-- Side Navbar -->
			<?php include 'menu.php';?>
			<div class="content-inner">
				<!-- Page Header-->
				<header class="page-header">
					<div class="container-fluid">
						<h2 class="no-margin-bottom">Master Pasar</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
                                <div class="card-header">
                                    <h4>Tambah Data Pasar</h4>
                                </div>
								<div class="card-body">
                                    <form id="form_pasar" method="post" action="">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">Nama Pasar</label>
                                                    <input type="text" placeholder="Nama Pasar" class="form-control" name="txtNamaPasar" value="<?php echo isset($RowData['NamaPasar']) ? $RowData['NamaPasar'] : ''; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Nama Kepala Pasar</label>
                                                    <input type="text" placeholder="Nama Kepala Pasar" class="form-control" name="txtNamaKepalaPasar" value="<?php echo isset($RowData['NamaKepalaPasar']) ? $RowData['NamaKepalaPasar'] : ''; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">No. Telp. Pasar</label>
                                                    <input type="number" placeholder="No. Telp. Pasar" class="form-control" name="txtNoTelp" value="<?php echo isset($RowData['NoTelpPasar']) ? $RowData['NoTelpPasar'] : ''; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Kecamatan</label>
                                                    <select id="cbKodeKec" name="cbKodeKec" class="form-control" required>
                                                    <?php 
                                                        if($data_kec):
                                                        foreach($data_kec as $kec): ?>
                                                        <option value="<?php echo $kec['KodeKec']; ?>" <?php if(isset($KodeKec) && $KodeKec === $kec['KodeKec']){ echo 'selected'; } ?>><?php echo $kec['NamaKecamatan']; ?></option>
                                                    <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Desa</label>
                                                    <select id="cbKodeDesa" name="cbKodeDesa" class="form-control" required>
                                                        <option value="" selected disabled>Pilih Desa</option>
                                                        <?php 
                                                        if($data_des):
                                                        foreach($data_des as $des): ?>
                                                            <option value="<?php echo $des['KodeDesa']; ?>" <?php if(isset($KodeDesa) && $KodeDesa === $des['KodeDesa']){ echo 'selected'; } ?>><?php echo $des['NamaDesa']; ?></option>
                                                        <?php endforeach;
                                                        endif;?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group-material">
                                                    <script>
                                                        var lat = <?php echo isset($LAT) ? $LAT : 0; ?>;
                                                        var lng = <?php echo isset($LONG) ? $LONG : 0; ?>;
                                                    </script>
                                                    <?php include '../library/latlong.php'; ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <button type="submit" name="btn-simpan-pasar" class="btn btn-primary">Simpan</button>
                                                <a href="MasterPasar.php" class="btn btn-secondary">Kembali</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<?php include 'footer.php'; ?>

    <script>

    var KodePasar, KodeKab, KodeKec, KodeDesa;

    $(document).ready(function () {
        KodePasar = "<?php echo $KodePasar; ?>";
        KodeKab = "<?php echo $KodeKab; ?>";
        KodeKec = "<?php echo $KodeKec; ?>";
        KodeDesa = "<?php echo $KodeDesa; ?>";
        // GetDataKec();
        // GetDataDesa();

        $('#cbKodeKec').change(function () {
            KodeKec = $(this).find('option:selected').attr('value');
            GetDataDesa();
        });

        $("#form_pasar").submit(function(e) {
            e.preventDefault();
            var NamaPasar = $("[name='txtNamaPasar']").val();
            var NamaKepalaPasar = $("[name='txtNamaKepalaPasar']").val();
            var NoTelpPasar = $("[name='txtNoTelp']").val();
            var KodeKec = $("[name='cbKodeKec']").val();
            var KodeDesa = $("[name='cbKodeDesa']").val();
            var KoorLong = $("[name='Lng']").val();
            var KoorLat = $("[name='Lat']").val();

            var action = "InsertEditData";

            var formData = new FormData();
            formData.append("KodePasar", KodePasar);
            formData.append("NamaPasar", NamaPasar);
            formData.append("NamaKepalaPasar", NamaKepalaPasar);
            formData.append("NoTelpPasar", NoTelpPasar);
            formData.append("KodeKab", KodeKab);
            formData.append("KodeKec", KodeKec);
            formData.append("KodeDesa", KodeDesa);
            formData.append("KoorLong", KoorLong);
            formData.append("KoorLat", KoorLat);
            formData.append("action", 'SimpanPasar');
            $.ajax({
                url: "aksi/AksiPasar.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData:false,
                dataType: 'json',
                success: function (data) {
                    if (data.response == 200) {
                        // alert(data.msg);
                        swal('Sukses' ,  data.msg ,  'success');
                        window.location = "MasterPasar.php";
                    } else {
                        swal('Error', data.msg,'warning');
                        // console.log(data.sql);
                    }
                }
            });
        });

        $(document).on('click', '#btnHapus', function () {
            var kodePasar = $(this).val();
            swal({
                title: "Peringatan",
                text: "Apakah Anda yakin menghapus data tersebut.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    HapusData(kodePasar);              
                }
            });
        });
    });

	function GetDataKec(){
		$.ajax({
			url: "GetDataWilayah.php",
			method: "POST",
			data: {KodeKab: KodeKab, KodeKec: KodeKec, action: 'Kecamatan'},
			success: function (data) {
				$('#cbKodeKec').html(data);
			}
		});
	}

	function GetDataDesa(){
		$.ajax({
			url: "GetDataWilayah.php",
			method: "POST",
			data: {KodeDesa: KodeDesa, KodeKec: KodeKec, action: 'Desa'},
			success: function (data) {
				$('#cbKodeDesa').html(data);
			}
		});
	}

    function HapusData(kodePasar){
        console.log(kodePasar);
        var action = "HapusPasar";
        $.ajax({
            url: "aksi/AksiPasar.php",
            method: "POST",
            data: {action: action, KodePasar: kodePasar},
            dataType: 'json',
            success: function (data) {
                if(data.response = 200){
                    swal({
                        title: "Berhasil", 
                        text: "Berhasil menghapus data.", 
                        icon: "success", 
                        allowOutsideClick: false
                    }).then(function() {
                        location.reload();
                    });
                }else{
                    swal('Gagal menghapus data\nCoba lagi.','error');
                }               
            }
        });
    }

    </script>
</body>