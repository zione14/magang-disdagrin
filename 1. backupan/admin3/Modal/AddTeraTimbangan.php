<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $NoTransaksi	=@$_GET['NoTransaksi'];
 $IDTimbangan	=@$_GET['login_id'];
 $NoUrutTrans	=@$_GET['NoUrutTrans'];
 $Aksi			=@$_GET['Aksi'];
 $Transaksi		=@$_GET['Transaksi'];
 
 if ($Transaksi == 'edit'){
	$sql = mysqli_query($koneksi,("Select HasilAction1,HasilAction2 from trtimbanganitem where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'"));
	$row = mysqli_fetch_array($sql);
 }
?>

<div class="modal-dialog ">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Hasil Tera Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="TrSidangTera.php" method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="NoTransaksi" type="hidden" value="<?php echo $NoTransaksi;?>" />
						<input name="IDTimbangan" type="hidden" value="<?php echo $IDTimbangan;?>" />
						<input name="NoUrutTrans" type="hidden" value="<?php echo $NoUrutTrans;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<div class="form-group" align="left">
							<label>Hasil Pelayanan Tera</label>
							<select name="HasilAction1" id="HasilAction1" class="form-control">
								<option value="TERA SAH" <?php echo isset($row['HasilAction1']) && $row['HasilAction1'] === "TERA SAH" ?"selected" : ""; ?>>TERA SAH</option>
								<option value="TERA BATAL" <?php echo isset($row['HasilAction1']) && $row['HasilAction1'] === "TERA BATAL" ?"selected" : ""; ?>>TERA BATAL</option>
							</select>
						</div>
						<div class="form-group" align="left">
							<label>Metode Tera UTTP</label>
							<select name="HasilAction2" id="Metode" class="form-control">
								<option value="">Pilih Opsi</option>
								<option value="Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji" <?php echo isset($row['HasilAction2']) && $row['HasilAction2'] === "Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji" ?"selected" : ""; ?>>Syarat Teknis Meter Bahan bakar Minyak dan Pompa Ukur Elpiji</option>
								<option value="Syarat Teknis Timbangan Bukan Otomatis" <?php echo isset($row['HasilAction2']) && $row['HasilAction2'] === "Syarat Teknis Timbangan Bukan Otomatis" ?"selected" : ""; ?>>Syarat Teknis Timbangan Bukan Otomatis</option>
							</select>
							<div class="form-group" align="right">
								<button type="submit"  id="submit-btn"  class="btn btn-primary" name="TeraUTTP">Simpan</button>
							</div>
						</div>
					</div>
				</div>
			</form>
        </div>
	</div>
</div>
	