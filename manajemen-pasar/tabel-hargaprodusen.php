<?php 
include 'head.php';
include '../library/pagination1.php';
date_default_timezone_set('Asia/Jakarta');
$Tanggal = isset($_GET['tgl']) ? mysqli_real_escape_string($koneksi,$_GET['tgl']) : date('Y-m-d');
$TanggalKemarin = date('Y-m-d', strtotime('-1 days', strtotime($Tanggal)));
$KodePasar = isset($_GET['psr']) ? mysqli_real_escape_string($koneksi,base64_decode($_GET['psr'])) : '';
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/zebra_datepicker@latest/dist/css/bootstrap/zebra_datepicker.min.css">
<style>
    .pagination {
        display: inline-block !important;
    }

    .pagination li {
        color: black;
        float: left;
        padding: 8px 2px;
        text-decoration: none;
        list-style-type: none;
    }
    
    select{
        display: block;
        width: 100%;
    }
</style>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="span12">
                <h4>Tabel Harga Pokok Pasar pada tingkat Produsen</h4>
            </div>
            <form action="" method="get">
            <div class="span3">
                <div class="form-group">
                    <label for="">Pilih Pasar</label>
                    <select name="psr" id="psr" class="form-control">
                        <?php 
                            $sql_p = "SELECT * FROM mstpasar ORDER BY NamaPasar ASC";
                            $res_p = $koneksi->query($sql_p);
                            while ($row_p = $res_p->fetch_assoc()) {
                                if($KodePasar == $row_p['KodePasar']){
                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'" selected>'.$row_p['NamaPasar'].'</option>';
                                }else{
                                    if(isset($KodePasar) && strlen($KodePasar) < 1){
                                        $KodePasar = $row_p['KodePasar'];
                                    }
                                    echo '<option class="form-control" value="'.base64_encode($row_p['KodePasar']).'">'.$row_p['NamaPasar'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="span4">
                <div class="form-search input-group">
                    <label for="" style="display:block;">Tanggal</label>
                    <div class="input-group">
                        <input type="text" name="tgl" id="tgl" value="<?php echo $Tanggal; ?>" class="form-control">
                        <button type="submit" class="btn btn-sm btn-primary input-group-btn input-group-append">Tampilkan</button>
                    </div>
                </div>
            </div>
            </form>
            <div class="span12" style="margin-top:12px;">
                <h6>Harga Bahan Pokok Pasar di tingkat Produsen Per <?php $strTanggal = date_create($Tanggal); echo date_format($strTanggal, "d/F/Y"); ?></h6>
                <div class="table-responsive">  
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Bahan Pokok</th>
                                <th>Satuan</th>
                                <th class="text-right">Harga Kemarin</th>
                                <th class="text-right">Harga Sekarang</th>
                                <th class="text-right">Perubahan (Rp)</th>
                                <th class="text-right">Perubahan (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php
                    $reload = "tabel-hargaprodusen.php?psr=".base64_encode($KodePasar)."&tgl=".$Tanggal;
                    $sql = "SELECT b.KodeBarang, b.NamaBarang, b.Merk, b.Satuan, IFNULL(b.Keterangan, '') AS KeteranganBarang, b.KodeGroup, g.NamaGroup, IFNULL(g.Keterangan, '') AS KeteranganGroup, g.IsAktif AS IsAktifGroup, b.IsAktif AS IsAktifBarang, IFNULL(hppkemarin.HargaBarang, 0) AS HargaBarangKemarin, IFNULL(hppkemarin.Ketersediaan, 0) AS KetersediaanKemarin, IFNULL(hppkemarin.HargaProdusen, 0) AS HargaProdusenKemarin, IFNULL(hppsekarang.Ketersediaan, 0) AS KetersediaanSekarang, IFNULL(hppsekarang.HargaProdusen, 0) AS HargaProdusenSekarang, IFNULL(hppsekarang.HargaBarang, 0) AS HargaBarangSekarang, IFNULL(hppkemarin.Keterangan, '') AS KeteranganLap, hppsekarang.Tanggal
                        FROM mstbarangpokok b
                        LEFT JOIN mstgroupbarang g ON g.KodeGroup = b.KodeGroup
                        LEFT JOIN (
                            SELECT *
                            FROM reporthargaharian r
                            WHERE r.KodePasar = '" . $KodePasar . "' AND DATE(r.Tanggal) = DATE('" . $TanggalKemarin . "')
                            GROUP BY r.Tanggal
                            ORDER BY r.Tanggal ASC
                        ) hppkemarin ON hppkemarin.KodeBarang = b.KodeBarang
                        LEFT JOIN (
                            SELECT *
                            FROM reporthargaharian r
                            WHERE r.KodePasar = '" . $KodePasar . "' AND DATE(r.Tanggal) = DATE('" . $Tanggal . "')
                            GROUP BY r.Tanggal
                            ORDER BY r.Tanggal ASC
                        ) hppsekarang ON hppsekarang.KodeBarang = b.KodeBarang
                        WHERE b.IsAktif = '1'
                        GROUP BY b.KodeBarang
                        ORDER BY b.KodeGroup ASC, b.KodeBarang ASC";                    
                    $result = mysqli_query($koneksi,$sql);
                    $rpp = 20;
                    $page = intval(@$_GET["page"]);
                    if($page<=0) $page = 1;  
                    $tcount = mysqli_num_rows($result);
                    $tpages = ($tcount) ? ceil($tcount/$rpp) : 1;
                    $count = 0;
                    $i = ($page-1)*$rpp;
                    $no_urut = ($page-1)*$rpp;
                    $kodegroup = "";
                    while(($count<$rpp) && ($i<$tcount)) {
                        mysqli_data_seek($result,$i);
                        $data = mysqli_fetch_array($result);
                        if($kodegroup !== $data['KodeGroup']){
                            echo '<tr style="background:#f7f7f7;"><td width="50px"></td>
                                <td colspan="7"><strong>'.ucwords($data['NamaGroup']).'</strong></td>
                            </tr>';
                            $kodegroup = $data['KodeGroup'];
                        }
                        ?>
                        <tr class="odd gradeX">
                            <td width="50px">
                                <?php echo ++$no_urut;?> 
                            </td>
                            <td>
                                <?php echo $data ['NamaBarang'];?>
                            </td>
                            <th>
                                <?php echo $data ['Satuan'];?>
                            </th>
                            <td class="text-right">
                                <?php echo 'Rp.'.number_format($data ['HargaProdusenKemarin']);?>
                            </td>
                            <td class="text-right">
                                <?php echo 'Rp.'.number_format($data ['HargaProdusenSekarang']);?>
                            </td>
                            <?php
                            $class_naik = '';
                            $icon_ = '';
                            ?>
                            <td class="text-right">
                                <?php
                                $dataselisi = ""; 
                                $selisi =  $data ['HargaProdusenSekarang'] < 1 ? 0 : $data ['HargaProdusenSekarang'] - $data ['HargaProdusenKemarin'];
                                if($selisi < 0){
                                    $icon_ = '<i class="fa fa-fw fa-chevron-down"></i>';
                                    $tmpselisi = $selisi * -1;
                                    $class_naik = 'text-success';
                                    $dataselisi = "- Rp.".number_format($tmpselisi);
                                }elseif($selisi == 0){
                                    $icon_ = '<i class="fa fa-fw fa-minus"></i>';
                                    $class_naik = '';
                                    $dataselisi = '--';
                                }else{
                                    $icon_ = '<i class="fa fa-fw fa-chevron-up"></i>';
                                    $class_naik = 'text-danger';
                                    $dataselisi = "Rp.".number_format($selisi);
                                }
                                echo '<p class="'.$class_naik.'">'.$dataselisi.'</p>'; ?>
                            </td>
                            <td class="text-right">
                                <?php 
                                if($data['HargaProdusenSekarang'] == 0 && $data['HargaProdusenKemarin'] == 0 ){
                                    echo '<p class="'.$class_naik.'">-- '.$icon_.'</p>';
                                }else{
                                    $persenSelisi = 0;
                                    if($selisi == $data['HargaProdusenSekarang']){
                                        $persenSelisi = 100;
                                    }else if($selisi == 0){
                                        $persenSelisi = 0;
                                    }else{
                                        $persenSelisi = ($selisi / $data['HargaProdusenKemarin']) * 100;
                                    }
                                    echo '<p class="'.$class_naik.'">'.number_format($persenSelisi, 2).' % '.$icon_.'</p>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $i++; 
                        $count++;
                    }

                    if($tcount==0){
                        echo '<tr>
                            <td colspan="7" align="center">
                                <strong>Tidak ada data</strong>
                            </td>
                        </tr>
                        ';
                    }
                ?>
                    </tbody>
                </table>
                <div><?php echo paginate_one($reload, $page, $tpages); ?></div>
            </div>
        </div>
    </div>
</section>
<?php include 'foot.php';?>

<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tgl').Zebra_DatePicker({format: 'Y-m-d'});
	});
</script>