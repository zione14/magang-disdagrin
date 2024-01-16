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

$KodePasar = false;
$DataPasar = array();
if(isset($_GET['id'])){
    $KodePasar = base64_decode($_GET['id']);
    $sql = "SELECT p.*
    FROM mstpasar p 
    WHERE p.KodePasar = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $KodePasar);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //$num_of_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            if ($row != null) {
                $DataPasar = $row;
            }
        }
        $stmt->free_result();
        $stmt->close();
    }
}

if(isset($_POST['btn-simpan'])){
    $sql = "select * from userlogin u where u.UserName = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s',  $_POST['UserName']);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $num_of_rows = $result->num_rows;
        if($num_of_rows > 0){
                echo '<script>
                alert("username sudah dipakai");
                </script>';
        }else{
            $sql = "INSERT INTO userlogin(UserName, NamaPegawai, NIP, JenisLogin, UserPsw, ActualName, Phone, UserStatus, LevelID, IsAktif, KodePasar, Jabatan) VALUES (?, ?, ?, 'PEDAGANG', ?, ?, ?, 1, 1, 1, ?, ?);";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('ssssssss', $_POST['UserName'], $_POST['NamaPegawai'], $_POST['NIP'], base64_encode('123456'), $_POST['NamaPegawai'], $_POST['Phone'], $KodePasar, $_POST['Jabatan']);
            if ($stmt->execute()) {
                echo '<script>
                        alert("data petugas berhasil disimpan");
                        window.location.href = "PetugasPasar.php?id='.base64_encode($KodePasar).'";
                    </script>';
            }
        }
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
						<h2 class="no-margin-bottom">Petugas Pasar</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
								<div class="card-header">
									<h4>Tambah Data Petugas Pasar "<?php echo isset($DataPasar['NamaPasar']) ? $DataPasar['NamaPasar'] : ""; ?>"</h4>
								</div>
								<div class="card-body">
									<form action="" method="post">
                                        <div class="form-group">
                                            <label for="">NamaPegawai</label>
                                            <input type="text" name="NamaPegawai" id="NamaPegawai" placeholder="masukkan nama petugas" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">NIP</label>
                                            <input type="text" name="NIP" id="NIP" placeholder="masukkan NIP. petugas" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Telepon</label>
                                            <input type="text" name="Phone" id="Phone" placeholder="masukkan nomot telepon petugas" class="form-control" required>
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="">Username</label>
                                            <input type="text" name="UserName" id="UserName" placeholder="masukkan username petugas" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Jenis User</label>
                                            <select name="Jabatan" id="Jabatan" class="form-control">
                                                <option value="BAKPOTING" <?php echo isset($RowData['Jabatan']) && $RowData['Jabatan'] == "BAKPOTING" ?"selected" : ""; ?>>BAKPOTING</option>
                                                <option value="ERPAS" <?php echo isset($RowData['Jabatan']) && $RowData['Jabatan'] == "ERPAS" ?"selected" : ""; ?>>ERPAS</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <p><i>default password di set menjadi "123456"</i></p>
                                            <button type="submit" name="btn-simpan" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-save"></i> Simpan</button>
                                            <a href="MasterPasar.php" class="btn btn-secondary btn-sm"><i class="fa fa-fw fa-times"></i> Kembali</a>
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
</body>