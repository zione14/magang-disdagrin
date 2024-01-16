<?php
 include '../library/config.php';
 include '../library/tgl-indo.php';
 
 @$KodeLapak	=$_GET['KodeLapak'];
 @$KodePasar 	=$_GET['KodePasar'];
 @$NamaPasar	=$_GET['NamaPasar'];

 $sql_lapakpr = "SELECT l.IDLapak, l.BlokLapak, l.NomorLapak, l.Keterangan, l.Retribusi, l.KodePasar,  c.NoTransRet, e.NamaPerson, c.IDPerson, c.TanggalTerakhirBayar, d.LokasiFile
	FROM lapakpasar l 
	LEFT JOIN (
		SELECT p.NamaPerson, lp.IDPerson, lp.KodePasar, lp.IDLapak
		FROM lapakperson lp 
		JOIN mstperson p ON lp.IDPerson=p.IDPerson
		WHERE lp.IsAktif=b'1'
	) e ON e.KodePasar = l.KodePasar AND e.IDLapak = l.IDLapak
	LEFT JOIN (
		SELECT t.NoTransRet, MAX(t.TanggalTrans) as TanggalTerakhirBayar, t.KodePasar, t.IDLapak, t.IDPerson
		FROM trretribusipasar t
		JOIN lapakperson lp ON t.IDLapak=lp.IDLapak AND t.KodePasar=lp.KodePasar
		WHERE lp.IsAktif=b'1'
		GROUP by t.KodePasar, t.IDLapak
	) c ON c.KodePasar = l.KodePasar AND c.IDLapak = l.IDLapak
	LEFT JOIN (
		SELECT d.KodePasar, d.IDLapak, d.JenisDokumen, d.LokasiFile
		FROM dokumenlapak d
		WHERE d.JenisDokumen='FOTO'
	) d ON d.KodePasar = l.KodePasar AND d.IDLapak = l.IDLapak
	WHERE l.KodePasar='$KodePasar' AND l.IDLapak='$KodeLapak' ORDER BY IDLapak ASC";
	$res_lapakpr = $koneksi->query($sql_lapakpr);
	$row_lapakpr = $res_lapakpr->fetch_assoc();
	$blok = str_replace(" ", "", $row_lapakpr['BlokLapak']);
	if($NamaPasar == 'CitraNiaga'){
		$nmpasar = 'CITRANIAGA';
	}else{
		$nmpasar = $NamaPasar;
	}
	$filename = '../images/Dokumen/Pasar/'.strtoupper($nmpasar).'_'.$blok.'_'.$row_lapakpr['NomorLapak'].'.*';
	$result = glob ($filename);
	// echo $result[0];
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Informasi Lapak Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-4">
						<img id="image-preview-1" width="100%" height="auto" <?php echo isset($row_lapakpr['LokasiFile']) && $row_lapakpr['LokasiFile'] != '' ? 'src="../images/Dokumen/Pasar/'.$row_lapakpr['LokasiFile'].'"' : @file_exists($result[0]) ?  'src="'.$result[0].'"' : 'src="../images/Assets/thumb_noimage.png"'; ?> >
					</div>
					<div class="col-lg-8">
						<div class="table-responsive">  
							<table class="table table-striped">
							  <thead>
							  	<tr>
								  <td>Kode Lapak</td>
								  <td>:</td>
								  <td><?=$KodeLapak?></td>
								</tr>
								<tr>
								  <td>Nama Lapak</td>
								  <td>:</td>
								  <td>Blok <?=$row_lapakpr['BlokLapak']?> Nomor <?=$row_lapakpr['NomorLapak']?></td>
								</tr>
								<tr>
								  <td>Nama Pemilik Lapak</td>
								  <td>:</td>
								  <td><?=$row_lapakpr['NamaPerson']?></td>
								</tr>
								<tr>
								  <td>Tanggal Terakhir Bayar</td>
								  <td>:</td>
								  <td><?=isset($row_lapakpr['TanggalTerakhirBayar']) && $row_lapakpr['TanggalTerakhirBayar'] != NULL ? TanggalIndo($row_lapakpr['TanggalTerakhirBayar']) : ''?></td>
								</tr>
								<tr>
								  <td>Nominal Retribusi</td>
								  <td>:</td>
								  <td><?=number_format($row_lapakpr['Retribusi'])?></td>
								</tr>
							  </thead>
							  <tbody>
								
							  </tbody>
							</table>
							<!-- <?php 
								print($result[0]);
								echo '<pre>'; print_r($result); echo '</pre>';
							?> -->
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
          <a href="MapInfo.php?l=<?php echo base64_encode($KodeLapak); ?>&p=<?php echo base64_encode($KodePasar)?>&n=<?php echo base64_encode($NamaPasar); ?>" title="Detail Informasi Lapak" ><input class="btn btn-primary" value="Lihat Detil" readonly=""></a>
		</div>
	</div>
</div>