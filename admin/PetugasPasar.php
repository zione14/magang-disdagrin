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
$DataPetugas = array();
if(isset($_GET['id'])){
    $KodePasar = base64_decode($_GET['id']);
    $sql = "SELECT UserName, NamaPegawai, NIP, Jabatan, JenisLogin, UserPsw, ActualName, Address, Phone, HPNo, Email, UserStatus, LevelID, IsAktif, p.KodePasar, p.NamaPasar
    FROM userlogin u
    INNER JOIN mstpasar p on p.KodePasar = u.KodePasar
    WHERE u.KodePasar = ?
    GROUP BY u.UserName";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $KodePasar);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //$num_of_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            if ($row != null) {
                array_push($DataPetugas, $row);
            }
        }
        $stmt->free_result();
        $stmt->close();
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
									<h4>Data Petugas Pasar</h4>
								</div>
								<div class="card-body">
									<a href="PetugasPasar-tambah.php?id=<?php echo $_GET['id']; ?>" class="btn btn-sm btn-primary">Tambah Petugas</a>
									<div class="table-responsive" style="margin-top:10px">
										<table class="table table-stripped">
											<thead>
												<tr>
                                                    <th>No.</th>
													<th>Nama Petugas</th>
                                                    <th>Nama Pasar</th>
													<th>NIP</th>
													<th>No. Telp</th>
													<th>Username</th>
                                                    <th>Status</th>
													<th class="text-right">Aksi</th>
												</tr>
											</thead>
                                            <tbody>
                                                <?php 
                                                if($DataPetugas):
                                                $i = 1;
                                                foreach($DataPetugas as $petugas): ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $petugas['NamaPegawai']; ?></td>
                                                        <td><?php echo $petugas['NamaPasar']; ?></td>
                                                        <td><?php echo $petugas['NIP']; ?></td>
                                                        <td><?php echo $petugas['Phone']; ?></td>
                                                        <td><?php echo $petugas['UserName']; ?></td>
                                                        <td><?php echo $petugas['IsAktif'] == '1' ? '<span class="text-success">AKTIF</span>' : '<span class="text-danger">NONAKTIF</span>'; ?></td>
                                                        <td class="text-right">
                                                            <?php if($petugas['IsAktif'] == '1'): ?>
                                                            <a href="PetugasPasar-hapus.php?id=<?php echo base64_encode($KodePasar); ?>&uname=<?php echo base64_encode($petugas['UserName']); ?>&aktif=0" class="btn btn-sm btn-danger">nonaktifkan</a>
                                                            <?php else: ?>
                                                            <a href="PetugasPasar-hapus.php?id=<?php echo base64_encode($KodePasar); ?>&uname=<?php echo base64_encode($petugas['UserName']); ?>&aktif=1" class="btn btn-sm btn-success">aktifkan</a>
                                                            <?php endif;?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach;
                                                else: ?>
                                                <tr>
                                                    <td colspan="8"><span class="text-center">Tidak Ada Data</span></td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
										</table>
										<!-- <div><?php //echo paginate_one($reload, $page, $tpages); ?></div> -->
									</div>
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