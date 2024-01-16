<?php
include '../admin/akses.php';
$Page = 'MasterDataPasar';
$SubPage='MasterLapakPasar';
$fitur_id = 29;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';


$KodeKab = '3517';
$KodeKec = '';
$KodeDesa = '';
$KodePasar = '';

$KodePasar = false;
$DataDokumen = array();
if(isset($_GET['k'])){
    $KodePasar = isset($_GET['k']) ? mysqli_escape_string($koneksi, base64_decode($_GET['k'])) : '';
    $IDLapak   = isset($_GET['l']) ? mysqli_escape_string($koneksi, base64_decode($_GET['l'])) : '';
    $sql = "SELECT d.NoUrut, d.TanggalUpload, d.JenisDokumen, d.NamaDokumen, d.LokasiFile, d.KodePasar, d.IDLapak, p.NamaPasar
    FROM dokumenlapak d
    INNER JOIN mstpasar p on p.KodePasar = d.KodePasar
    WHERE d.KodePasar = ? AND d.IDLapak = ?
    ORDER BY d.TanggalUpload DESC";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ss', $KodePasar, $IDLapak);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        //$num_of_rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            if ($row != null) {
                array_push($DataDokumen, $row);
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
						<h2 class="no-margin-bottom">Dokumen Lapak Pasar</h2>
					</div>
				</header>

				<section class="dashboard-counts no-padding-bottom">
					<div class="container-fluid">
						<div class="col-lg-12">
							<div class="card card-default">
								<div class="card-header">
									<h4>Data Dokumen Lapak Pasar</h4>
								</div>
								<div class="card-body">
									<?php if ($cek_fitur['DeleteData'] =='1'){ ?>
									<a data-toggle="modal" data-target="#tmbhlevel" class="btn btn-sm btn-primary"><span style="color:white;">Tambah Dokumen<span></a>
									<!-- <a href="PetugasPasar-tambah.php?id=<?php echo $_GET['k']; ?>" class="btn btn-sm btn-primary">Tambah Dokumen</a> -->
									<?php } ?>
									<div class="table-responsive" style="margin-top:10px">
										<table class="table table-stripped">
											<thead>
												<tr>
                          <th>No.</th>
													<th>Tanggal Upload</th>
													<th>Nama Dokumen</th>
                          <th>Jenis Dokumen</th>
													<th>Dokumen</th>
													<th>Aksi</th>
												</tr>
											</thead>
                      <tbody>
                          <?php 
                          if($DataDokumen):
                          $i = 1;
                          foreach($DataDokumen as $row): ?>
                              <tr>
                                  <td><?php echo $i++; ?></td>
                                  <td><?php echo TanggalIndo($row['TanggalUpload']); ?></td>
                                  <td><?php echo $row['NamaDokumen']; ?></td>
                                  <td><?php echo $row['JenisDokumen']; ?></td>
                                  <td><a class="btn btn-info btn-sm" title="Download Dokumen" href="../images/Dokumen/Pasar/<?php echo $row['LokasiFile']; ?>" target="_blank"><span><i class="fa fa-download"></i> Unduh</span></a></td></td>
                                  <td>
                                    <?php if (@$cek_fitur['DeleteData'] =='1') { ?> 
                                    <a class="btn btn-danger btn-sm" title="Hapus Data" href="SimpanData/MasterLapakPasar-dokumen_aksi.php?k=<?php echo base64_encode($row['KodePasar']); ?>&aksi=<?php echo base64_encode('Hapus'); ?>&dk=<?php echo base64_encode($row['LokasiFile']);?>&u=<?php echo base64_encode($row['NoUrut']);?>&l=<?php echo base64_encode($login_id);?>&p=<?php echo base64_encode($row['IDLapak']);?>"><span><i class="fa fa-trash"></i></span></a>
                                    <?php } ?>
                                  </td>
                              </tr>
                          <?php endforeach;
                          else: ?>
                          <tr>
                              <td colspan="8" align="center"><span class="text-center">Tidak Ada Data</span></td>
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

<div class="modal fade" id="tmbhlevel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
      <form action="SimpanData/MasterLapakPasar-dokumen_aksi.php" enctype="multipart/form-data" method="post" onSubmit="return validate();">
          <div class="modal-header">
          	<h4 class="modal-title" id="defaultModalLabel">Tambah Dokumen</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="KodePasar" value="<?=$KodePasar?>">
              <input type="hidden" name="IDLapak" value="<?=$IDLapak?>">
              <label>Nama Dokumen</label>
              <div class="form-group">
                  <input type="text" name="NamaDokumen"  class="form-control" placeholder="Nama Dokumen" required="">
              </div>
        			  <label>Jenis Dokumen</label>
        			  <select name="JenisDokumen" id="JenisDokumen" class="form-control" required="">
          				<option value="BUKTI KEPEMILIKAN" >BUKTI KEPEMILIKAN</option>
          				<option value="FOTO" >FOTO</option>
          				<option value="VIDEO" >VIDEO</option>
        			  </select>
              <div class="form-group">
                <label>File Dokumen 2 MB</label><br>
                <input type="file" id="exampleInputFile" name="Dokumen" required>
                <p class="help-block">Hanya Format <br>.jpeg, .jpg, .png, .mp4, .docx, .doc, .pdf, .xls, .xlsx, .ppt, .pptx </p>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" name="SimpanData" class="btn btn btn-danger waves-effect">Simpan</button>
              <button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
          </div>
      </form>
      </div>
  </div>  
</div>

<script type="text/javascript">
  function validate() {
    var file_size = $('#exampleInputFile')[0].files[0].size;
    if(file_size>2000000) {
      alert('Ukuran File Terlalu Besar')
      return false;
    } 

    var inputFile = document.getElementById('exampleInputFile');
    var pathFile = inputFile.value;
    var ekstensiOk = /(\.xlsx|\.xls|\.doc|\.docx|\.ppt|\.pptx|\.pdf|\.jpeg|\.jpg|\.png|\.mp4)$/i;
    if(!ekstensiOk.exec(pathFile)){
      alert('Silakan upload file yang memiliki ekstensi .jpeg, .jpg, .png, .mp4, .docx, .doc, .pdf, .xls, .xlsx, .ppt, .pptx ');
      inputFile.value = '';
      return false;
    }
 
    return true;
  }
</script>