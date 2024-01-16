<?php
include '../admin/akses.php';
$Page = 'HargaHarian';
$fitur_id = 22;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';

$sql = "SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
    r.HargaBarang, ifnull(r.Ketersediaan, 0) as Ketersediaan, ifnull(r.HargaProdusen, 0) as HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
    FROM reporthargaharian r
    INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
    INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
    LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
    LEFT JOIN (
            SELECT *
            FROM reporthargaharian k
            ORDER BY k.Tanggal DESC
    ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND 
    DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
    WHERE r.KodePasar = ? AND r.KodeBarang = ?
    GROUP BY hppkemarin.Tanggal
    ORDER BY r.Tanggal DESC
    LIMIT 1";
$reportharian = array();
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('ss', $KodePasar, $KodeBarang);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row != null) {
            $reportharian = $row;
        }
    }
    $stmt->free_result();
    $stmt->close();
}

// echo json_encode($reportharian);exit;

$sql_br = "SELECT *
    FROM mstbarangpokok b 
    WHERE b.KodeBarang = ?";
$stmt = $koneksi->prepare($sql_br);
$stmt->bind_param("s", $KodeBarang);
$detail_brg = array();
if($stmt->execute()){
    $result = $stmt->get_result();
    $num_of_rows = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        if($row != null){
            $detail_brg = $row;
        }
    }
    $stmt->free_result();
    $stmt->close();
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
	<script src="https://cdn.rawgit.com/googlemaps/v3-utility-library/master/infobox/src/infobox.js"></script>
	<!-- Sweet Alerts -->
    <link rel="stylesheet" href="../library/sweetalert/sweetalert.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
	<style>
		th {
			text-align: center;
		}

		.hidden {
			display: none;
			visibility: hidden;
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
	</style>
</head>
<body>
</body>
<div class="page">
	<!-- Main Navbar-->
	<?php 
	include 'header.php'; ?>
	<div class="page-content d-flex align-items-stretch"> 
		<!-- Side Navbar -->
		<?php include 'menu.php';?>
		<div class="content-inner">
			<!-- Page Header-->
			<header class="page-header">
				<div class="container-fluid">
					<h2 class="no-margin-bottom">Entry Laporan Harga Harian</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">		
							<form action="" method="post">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label  class="form-control-label" class="form-control-label" for="">Nama Bahan Pokok Pasar</label>
                                            <input type="hidden" name="KodeBarang" value="<?php echo $KodeBarang; ?>">
                                            <input type="text" name="NamaBarang" id="NamaBarang" class="form-control" value="<?php echo isset($reportharian['NamaBarang']) ? $reportharian['NamaBarang'] : isset($detail_brg['NamaBarang']) ? $detail_brg['NamaBarang'] : ""; ?>" readOnly>
                                        </div>
                                        <div class="form-group">
                                            <label  class="form-control-label"for="">Nama Pasar</label>
                                            <select class="form-control" name="psr" id="psr" required>
                                                <!-- <option class="form-control" value="" selected>Semua Pasar</option> -->
                                                <?php 
                                                $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                                                $res_p = $koneksi->query($sql_p);
                                                while ($row_p = $res_p->fetch_assoc()) {
                                                    if(isset($KodePasar) && $KodePasar === $row_p['KodePasar']){
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                                    }else{
                                                        if(!isset($KodePasar) || strlen($KodePasar) < 1 ){
                                                            $KodePasar = $row_p['KodePasar'];
                                                        }
                                                        echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label  class="form-control-label"for="">Tanggal Laporan</label>
										    <input  class="form-control" id="tgl" name="tgl" type="text" value="<?php echo $Tanggal; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label  class="form-control-label"for="">Harga Konsumen</label>
                                            <input type="text" name="HargaBarang" id="HargaBarang" class="form-control" value="<?php echo isset($reportharian['HargaBarang']) ? $reportharian['HargaBarang'] : "0"; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label  class="form-control-label"for="">Harga Produsen</label>
                                            <input type="text" name="HargaProdusen" id="HargaProdusen" class="form-control" value="<?php echo isset($reportharian['HargaProdusen']) ? $reportharian['HargaProdusen'] : "0"; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <!-- <label  class="form-control-label"for="">Ketersediaan</label> -->
                                            <input type="hidden" name="Ketersediaan" id="Ketersediaan" class="form-control" value="<?php echo isset($reportharian['Ketersediaan']) ? $reportharian['Ketersediaan'] : "0"; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="btn-simpan" class="btn btn-success btn-sm"><i class="fa fa-fw fa-save"></i> Simpan</button>
                                            <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-sm btn-secondary"><i class="fa fa-fw fa-times"></i> Kembali</a>
                                        </div>
                                    </div>
                                </div>
							</form>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
<!-- Sweet Alerts -->
<script src="../library/sweetalert/sweetalert.min.js" type="text/javascript"></script>
<script src="../komponen/js/input.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tgl').Zebra_DatePicker({format: 'Y-m-d'});

        cek_harga();
        
        $('#tgl').change(function () {
            cek_harga();
        });

        $('#psr').change(function () {
            cek_harga();
        });
      
        setInputFilter(document.getElementById("HargaBarang"), function(value) {
            return /^\d*$/.test(value); }); //nilai positive saja
      
        setInputFilter(document.getElementById("HargaProdusen"), function(value) {
            return /^\d*$/.test(value); }); //nilai positive saja
      
        setInputFilter(document.getElementById("Ketersediaan"), function(value) {
            return /^\d*$/.test(value); }); //nilai positive saja
	});

    function cek_harga() {
        var tgl = document.getElementById('tgl').value;
        var psr = document.getElementById('psr').value;
        var brg = '<?php echo base64_encode($KodeBarang); ?>';
        $.ajax({
			url: "aksi/lap_hpp.php",
			method: "GET",
			data: {tgl: tgl, psr: psr, brg: brg},
			dataType: 'json',
			success: function (data) {
				// console.log(data);
                if(data.HargaBarang != null){
                    document.getElementById('HargaBarang').value = data.HargaBarang;
                }else{
                    document.getElementById('HargaBarang').value = 0;
                }
                if(data.HargaProdusen != null){
                    document.getElementById('HargaProdusen').value = data.HargaProdusen;
                }else{
                    document.getElementById('HargaProdusen').value = 0;
                }
                if(data.Ketersediaan != null){
                    document.getElementById('Ketersediaan').value = data.Ketersediaan;
                }else{
                    document.getElementById('Ketersediaan').value = 0;
                }
			},
            error:function(err){
                // console.log(err);
            }
		});
    }
</script>
<?php 


if(isset($_POST['btn-simpan'])){
    // echo json_encode($_POST);exit;
    $KodePasar = mysqli_real_escape_string($koneksi,base64_decode($_POST['psr']));
    $KodeBarang = $_POST['KodeBarang'];
    $Tanggal = $_POST['tgl'];
    $HargaBarang = $_POST['HargaBarang'];
    $HargaProdusen = $_POST['HargaProdusen'];
    $Ketersediaan = $_POST['Ketersediaan'];

    $sql = "SELECT r.Tanggal
            FROM reporthargaharian r
            WHERE DATE(r.Tanggal) = DATE(?) AND r.KodeBarang = ? AND r.KodePasar = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('sss', $Tanggal, $KodeBarang, $KodePasar);
    if ($stmt->execute()) {
        $response = array();
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if ($num_of_rows < 1) {
            $Tanggal .= ' '.date('H:i:s');
            $sql1 = "INSERT INTO reporthargaharian(KodeBarang, Tanggal, HargaBarang, UserName, KodePasar, Ketersediaan, HargaProdusen) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql1);
            $stmt->bind_param('sssssss', $KodeBarang, $Tanggal, $HargaBarang, $login_id, $KodePasar, $Ketersediaan, $HargaProdusen);
            if ($stmt->execute()) {
                echo '<script type="text/javascript">
				  sweetAlert({
					title: "Prosess simpan laporan berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
                    window.location.href = "HargaBarangHarian.php?k='.base64_encode($KodeBarang).'&tgl='.$_POST['tgl'].'&psr='.base64_encode($KodePasar).'";
				  });
				  </script>';
            }else{
                echo '<script type="text/javascript">
                      sweetAlert({
                        title: "Prosess simpan laporan gagal!",
                        text: " ",
                        type: "error"
                      },
                      function () {
                        window.location.href = "HargaBarangHarian.php?k='.base64_encode($KodeBarang).'&tgl='.$_POST['tgl'].'&psr='.base64_encode($KodePasar).'";
                      });
                      </script>';
            }
        }else{
            while ($row = $result->fetch_assoc()) {
                if ($row != null) {
                    $response = $row;
                }
            }
            $Tanggal = $response['Tanggal'];

            $sql2 = "UPDATE reporthargaharian SET HargaBarang = ?, Ketersediaan = ?, HargaProdusen = ?
                    WHERE KodeBarang = ? AND KodePasar = ? AND Tanggal = ?";
            $stmt = $koneksi->prepare($sql2);
            $stmt->bind_param('ssssss', $HargaBarang, $Ketersediaan, $HargaProdusen, $KodeBarang, $KodePasar, $Tanggal);
            if ($stmt->execute()) {
                echo '<script type="text/javascript">
				  sweetAlert({
					title: "Prosess simpan laporan berhasil!",
					text: " ",
					type: "success"
				  },
				  function () {
                    window.location.href = "HargaBarangHarian.php?k='.base64_encode($KodeBarang).'&tgl='.$_POST['tgl'].'&psr='.base64_encode($KodePasar).'";
				  });
				  </script>';
            }else{
                echo '<script type="text/javascript">
                      sweetAlert({
                        title: "Prosess simpan laporan gagal!",
                        text: " ",
                        type: "error"
                      },
                      function () {
                        window.location.href = "HargaBarangHarian.php?k='.base64_encode($KodeBarang).'&tgl='.$_POST['tgl'].'&psr='.base64_encode($KodePasar).'";
                      });
                      </script>';
            }
        }
    }else{
        echo '<script type="text/javascript">
              sweetAlert({
                title: "Prosess simpan laporan gagal!",
                text: " ",
                type: "error"
              },
              function () {
                window.location.href = "HargaBarangHarian.php?k='.base64_encode($KodeBarang).'&tgl='.$_POST['tgl'].'&psr='.base64_encode($KodePasar).'";
              });
              </script>';
    }
}

?>