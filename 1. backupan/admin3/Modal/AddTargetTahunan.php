<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $ThAnggaran	=@$_GET['ThAnggaran'];
 $NoTrans		=@$_GET['NoTrans'];
 $Aksi			=@$_GET['Aksi'];
 
 if ($Aksi == 'edit'){
	$cek = @mysqli_query($koneksi, "SELECT * from targettahunan where (date_format(ThAnggaran, '%Y-%m-%d')= '$ThAnggaran') and JenisTarget='PAD'");
	$pad = @mysqli_fetch_array($cek);
	
	$cek1 = @mysqli_query($koneksi, "SELECT * from targettahunan where (date_format(ThAnggaran, '%Y-%m-%d')= '$ThAnggaran') and JenisTarget='UTTP'");
	$uttp = @mysqli_fetch_array($cek1);
	
	$date=date_create($pad['ThAnggaran']);
	
	
	
 }
 @$Bulan = isset($date) && $date != null ? date_format($date,"Y-m") : date('Y-m'); 
	
?>
<!-- Datepcker -->
<link rel="stylesheet" href="../library/Datepicker/dist/css/default/zebra_datepicker.min.css" type="text/css">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Target Laporan Realisasi PAD dan UTTP</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="TargetTahunan.php" method="post" >
				<div class="row">
					<div class="col-lg-12">
					<div class="form-group">
					<label>Periode</label>
					<input type="text" name="ThAnggaran" class="form-control" id="time7" value="<?php echo @$Bulan; ?>" placeholder="Periode Bulan" <?php  if ($Aksi == 'edit'){ echo 'disabled'; } ?>>
				</div>
					</div>
					<div class="col-lg-6">
						<h5>Pendapatan Asli Daerah (PAD)</h5>
						<input name="ThAnggaran1" type="hidden" value="<?php echo $ThAnggaran;?>" />
						<input name="NoTrans" type="hidden" value="<?php echo $NoTrans;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<input name="JenisTarget" type="hidden" value="PAD" />
						<input name="JenisTarget1" type="hidden" value="UTTP" />
						<div class="form-group" align="left">
							<label>Kode Rekening</label>
							<input type="text" name="KodeRekening" class="form-control" placeholder="Kode Rekening" value="<?php echo @$pad['KodeRekening']; ?>" >
						</div>
						<div class="form-group" align="left">
							<label>Target</label>
							<input type="number" name="TargetAwal" class="form-control" placeholder="Target" value="<?php echo @$pad['TargetAwal']; ?>" required>
						</div>
						<div class="form-group" align="left">
							<label>Target PAK</label>
							<input type="number" name="TargetPAK" class="form-control" placeholder="Target PAK" value="<?php echo @$pad['TargetPAK']; ?>" >
						</div>
					</div>
					<div class="col-lg-6">
						<h5>UTTP</h5>
						<div class="form-group" align="left">
							<label>Kode Rekening</label>
							<input type="text" name="KodeRekeningUTTP" class="form-control" placeholder="Kode Rekening" value="<?php echo @$uttp['KodeRekening']; ?>" >
						</div>
						<div class="form-group" align="left">
							<label>Target</label>
							<input type="number" name="TargetAwalUTTP" class="form-control" placeholder="Target" value="<?php echo @$uttp['TargetAwal']; ?>" required>
						</div>
						<div class="form-group" align="left">
							<label>Target PAK</label>
							<input type="number" name="TargetPAKUTTP" class="form-control" placeholder="Target PAK" value="<?php echo @$uttp['TargetPAK']; ?>">
						</div>
						<div class="form-group" align="right">
							<button type="submit"  class="btn btn-primary" 	 <?php if ($Aksi != 'edit') { echo 'name="Simpan"'; } else{ echo 'name="EditSimpan"'; } ?>>Simpan</button>
						</div>
					</div>
				</div>
			</form>
        </div>
	</div>
</div>
<!-- DatePicker -->
	<script type="text/javascript" src="../library/Datepicker/dist/zebra_datepicker.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#time7').Zebra_DatePicker({format: 'Y-m'});
			$('#time1').Zebra_DatePicker({format: 'Y-m'});
		});
	</script>