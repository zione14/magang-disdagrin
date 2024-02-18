<?php
include '../admin/akses.php';
$Page = 'KetersediaanStok';
$fitur_id = 24;
include '../library/lock-menu.php';
include '../library/tgl-indo.php';date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
$KodeBarang = isset($_GET['brg']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['brg'])) : '';
$Tgl = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : '';
$Bln = isset($_GET['bln']) ? mysqli_real_escape_string($koneksi,$_GET['bln']) : '';
$Thn = isset($_GET['thn']) ? mysqli_real_escape_string($koneksi,$_GET['thn']) : '';

$Periode = $Thn.'-'.$Bln.'-'.$Tgl;

// $sql = "
// SELECT r.KodeBarang, b.NamaBarang, b.KodeBarang, b.Satuan, b.Merk, b.KodeGroup, g.NamaGroup, r.Tanggal, IFNULL(hppkemarin.HargaBarang, 0) AS HargaKemarin,
//     r.HargaBarang, ifnull(r.Ketersediaan, 0) as Ketersediaan, ifnull(r.HargaProdusen, 0) as HargaProdusen, r.Keterangan, r.UserName, r.KodePasar, p.NamaPasar
//     FROM reporthargaharian r
//     INNER JOIN mstbarangpokok b ON b.KodeBarang = r.KodeBarang
//     INNER JOIN mstpasar p ON p.KodePasar = r.KodePasar
//     LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
//     LEFT JOIN (
//             SELECT *
//             FROM reporthargaharian k
//             ORDER BY k.Tanggal DESC
//     ) hppkemarin ON hppkemarin.KodeBarang = r.KodeBarang AND hppkemarin.KodePasar = r.KodePasar AND 
//     DATE_ADD(hppkemarin.Tanggal, INTERVAL 1 SECOND) < DATE_ADD(r.Tanggal, INTERVAL 1 SECOND) AND hppkemarin.UserName = r.UserName
//     WHERE r.KodePasar = ? AND r.KodeBarang = ?
//     GROUP BY hppkemarin.Tanggal
//     ORDER BY r.Tanggal DESC
//     LIMIT 1";
// $reportharian = array();
// $stmt = $koneksi->prepare($sql);
// $stmt->bind_param('ss', $KodePasar, $KodeBarang);
// if ($stmt->execute()) {
//     $result = $stmt->get_result();
//     while ($row = $result->fetch_assoc()) {
//         if ($row != null) {
//             $reportharian = $row;
//         }
//     }
//     $stmt->free_result();
//     $stmt->close();
// }

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


//Mencari Nama Pasar
$sql_p = "SELECT NamaPasar FROM mstpasar WHERE KodePasar='$KodePasar' ORDER BY NamaPasar ASC";
$res_p = $koneksi->query($sql_p);
$row_p = $res_p->fetch_assoc();

// Mencari Jumlah Pedagang dari Pasar dan jenis barang tertentu
$sql_mp = "SELECT IFNULL(p.JumlahPedagang, 0) as Pedagang
    FROM masterpedagang p
    WHERE p.KodePasar = '$KodePasar' AND p.KodeBarang = '$KodeBarang'";
$res_mp = $koneksi->query($sql_mp);
$row_mp = $res_mp->fetch_assoc();

//Data Stok
$data_stok = array();

$sql_stok = "SELECT * FROM stokpedagang WHERE Periode= ? AND KodePasar= ? AND KodeBarang= ? ORDER BY NoUrut ASC";
$res_stok = $koneksi->prepare($sql_stok);
$res_stok->bind_param("sss", $Periode, $KodePasar, $KodeBarang);
$res_stok->execute();
$result = $res_stok->get_result();
while ($row_stok = $result->fetch_assoc()) {
    array_push($data_stok, $row_stok);
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
    .table thead th {
        
        border: 2px solid #dee2e6;
    }
    td {
        border: 2px solid #dee2e6;
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
					<h2 class="no-margin-bottom">Tambah Stok</h2>
				</div>
			</header>

			<section class="dashboard-counts no-padding-bottom">
				<div class="container-fluid">
					<div class="card">
						<div class="card-body">
                          <h4>PASAR : <?=strtoupper($row_p['NamaPasar'])?> / KABUPATEN JOMBANG</h4>	
                           <form action="" method="post">         
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-control-label">Tanggal</label>
                                        <select class="form-control" name="tgl">
                                            <option value="01" <?php echo isset($Tgl) && $Tgl === "01" ?"selected" : ""; ?>>01</option>
                                            <option value="08" <?php echo isset($Tgl) && $Tgl === "08" ?"selected" : ""; ?>>08</option>
                                            <option value="15" <?php echo isset($Tgl) && $Tgl === "15" ?"selected" : ""; ?>>15</option>
                                            <option value="22" <?php echo isset($Tgl) && $Tgl === "22" ?"selected" : ""; ?>>22</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">&nbsp;</label>
                                        <select class="form-control" name="bln">
                                            <?php
                                                $bln=array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                                for($bulan=1; $bulan<=12; $bulan++){
                                                    if($bulan<=9){
                                                        if (isset($Bln) && $Bln !=''){
                                                            $select = isset($Bln) && $Bln == '0'.$bulan ? 'selected' : '';
                                                            echo"<option value=0".$bulan." ".$select."> $bln[$bulan] </option>";
                                                        }else{
                                                            $select = isset($blnow)  && $blnow == '0'.$bulan ? 'selected' : '';
                                                            echo"<option value=0".$bulan." ".$select."> $bln[$bulan] </option>";
                                                        }
                                                    }else{
                                                        if (isset($Bln) && $Bln !=''){
                                                            $select = isset($Bln) && $Bln == $bulan ? 'selected' : '';
                                                            echo"<option value=".$bulan." ".$select."> $bln[$bulan] </option>";
                                                        }else{
                                                            $select = isset($blnow)  && $blnow == $bulan ? 'selected' : '';
                                                            echo"<option value=".$bulan." ".$select."> $bln[$bulan] </option>";
                                                            
                                                        }
                                                    }
                                                }
                                           ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-control-label">&nbsp;</label>
                                        <select class="form-control" name="thn">
                                           <?php
                                            for($i=date("Y"); $i>=date("Y")-5; $i-=1){
                                                $selectthn = isset($Thn) && $Thn == $i ? 'selected' : '';
                                                echo "<option value=$i ".$selectthn."> $i </option>";
                                                if(!isset($Thn) || strlen($Thn) < 1 ){
                                                    $Thn = $i;
                                                }
                                            }
                                           ?>
                                        </select>
                                    </div>

                                    <div class="col-lg-9">
                                        <hr>
                                        <div class="form-group">
                                            <label  class="form-control-label">*) Wajib mengisi semua item</label>
                                            <h3>
                                                <?php echo isset($reportharian['NamaBarang']) ? $reportharian['NamaBarang'] : isset($detail_brg['NamaBarang']) ? $detail_brg['NamaBarang'] : ""; ?> (<?=$detail_brg['Satuan']?>)
                                            </h3>
                                        </div>
                                        <?php
                                            //Rumus Slovin Untuk Menentukan jumlah pedagang
                                            $Pedagang = isset($row_mp['Pedagang']) ? $row_mp['Pedagang'] : '0';
                                            $e = pow(0.3,2);
                                            $JumlahPedagang = Round($Pedagang / (1+($Pedagang*$e)));
                                        ?>
                                        <div class="table-responsive">
                                            <table class="table table-stripped">
                                                <thead>
                                                    <tr>
                                                        <th>PEDAGANG</th>
                                                        <th>STOK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        if($Pedagang != 0):
                                                            $no = 0;

                                                            if(sizeof($data_stok) > 0):
                                                                foreach($data_stok as $stokpedagang):
                                                                     echo "<tr>";
                                                                    echo "<td>Pedagang ".++$no."</td>";
                                                                    echo "<td>";
                                                                    echo '
                                                                          <div class="input-group">
                                                                            <input type="number" name="Stok[]" value="'.@$stokpedagang['Stok'].'" class="form-control">
                                                                            <div class="input-group-append"><span class="input-group-text">'.$detail_brg['Satuan'].'</span></div>
                                                                          
                                                                        </div>';
                                                                    echo '<input type="hidden" name="NoUrut[]" value="'.@$stokpedagang['NoUrut'].'">';
                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                endforeach;

                                                            else:

                                                                for($d=0; $d<$JumlahPedagang; $d+=1){
                                                                
                                                                    echo "<tr>";
                                                                    echo "<td>Pedagang ".++$no."</td>";
                                                                    echo "<td>";
                                                                    echo '
                                                                          <div class="input-group">
                                                                            <input type="number" name="Stok[]" value="'.@$stokpedagang['Stok'].'" class="form-control">
                                                                            <div class="input-group-append"><span class="input-group-text">'.$detail_brg['Satuan'].'</span></div>
                                                                          
                                                                        </div>';
                                                                    echo "</td>";
                                                                    echo "</tr>";
                                                                }

                                                            endif; 
                                                        else :
                                                            echo "<tr>";
                                                            echo "<td colspan=2 align=center>Tidak Ada Pedagang</td>";
                                                            echo "</tr>";
                                                       
                                                        endif;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="KodeBarang" value="<?php echo $KodeBarang; ?>">
                                            <input type="hidden" name="KodePasar" value="<?php echo $KodePasar; ?>">
                                            <input type="hidden" name="JumlahPedagang" value="<?php echo $JumlahPedagang; ?>">
                                            <button type="submit" name="btn-simpan" class="btn btn-success btn-sm"><i class="fa fa-fw fa-save"></i> Simpan</button>
                                            <a href="KetersediaanStok.php" class="btn btn-sm btn-secondary"><i class="fa fa-fw fa-times"></i> Kembali</a>
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
    $KodePasar = mysqli_real_escape_string($koneksi,$_POST['KodePasar']);
    $KodeBarang = $_POST['KodeBarang'];

    $Tanggal = $_POST['tgl'];
    $Bulan = $_POST['bln'];
    $Tahun = $_POST['thn'];
    $JumlahPedagang = $_POST['JumlahPedagang'];

    // $TglInput = $Tahun.'-'.$Bulan.'-'.$Tanggal;
   

    $Periode = $Tahun.'-'.$Bulan.'-'.$Tanggal;

    $sql_stok = "SELECT * FROM stokpedagang WHERE Periode= ? AND KodePasar= ? AND KodeBarang= ? ";
    $rest = $koneksi->prepare($sql_stok);
    $rest->bind_parm('sss', $Periode, $KodePasar, $KodeBarang);
    $rest->execute();
    $resultku = $rest->get_result();
    $row_stok = mysqli_num_rows($resultku);
    if($row_stok > 0) :
      
        for ($i=0; $i < sizeof($_POST['Stok']); $i++) {
        
            mysqli_query($koneksi,"UPDATE stokpedagang SET Stok='".@$_POST['Stok'][$i]."' WHERE NoUrut='".@$_POST['NoUrut'][$i]."' AND KodePasar='$KodePasar' AND KodeBarang='$KodeBarang' AND Periode='$Periode' ");
        }

    else:
        
         for ($i=0; $i < sizeof($_POST['Stok']); $i++) {
            @$NoUrut = $i+1;

            mysqli_query($koneksi,"INSERT into stokpedagang (KodePasar,KodeBarang,Periode,JumlahPedagang,Stok,NoUrut,TglInput) 
                VALUES ('$KodePasar','$KodeBarang','$Periode','$JumlahPedagang','".$_POST['Stok'][$i]."','$NoUrut', CURDATE())");
        }
    endif;

   

    echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="KetersediaanStok.php";</script>';

}

?>