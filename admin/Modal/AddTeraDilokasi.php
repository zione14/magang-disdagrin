<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $NoTransaksi	=@$_GET['NoTransaksi'];
 $IDPerson		=@$_GET['IDPerson'];
 $login_id		=@$_GET['login_id'];
 $KodeLokasi	=@$_GET['KodeLokasi'];
 $Aksi			=@$_GET['Aksi'];
 $TotalRetribusi=@$_GET['TotalRetribusi'];
 
 if ($Aksi == 'edit'){
	$sql = mysqli_query($koneksi,("Select HasilAction1,IDTimbangan,HasilAction2 from trtimbanganitem where NoTransaksi='$NoTransaksi' and NoUrutTrans='$login_id'"));
	$row = mysqli_fetch_array($sql);
 }
 
 
 ?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Hasil Tera Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="TrTeraDilokasi.php" method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="NoTransaksi" type="hidden" value="<?php echo $NoTransaksi;?>" />
						<input name="IDPerson" type="hidden" value="<?php echo $IDPerson;?>" />
						<input name="UserName" type="hidden" value="<?php echo $login_id;?>" />
						<input name="KodeLokasi" type="hidden" value="<?php echo $KodeLokasi;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<input name="Retribusi" type="hidden" value="<?php echo $TotalRetribusi;?>" />
						<div class="form-group" align="left">
							<div class="form-group" align="left">
								<label>Pilih Timbangan</label>
								<select name="IDTimbangan" class="form-control" required>	
									<?php
										echo "<option value=''>--- Pilih Timbangan ---</option>";
										$menu = mysqli_query($koneksi,"SELECT IDTimbangan,NamaTimbangan from timbanganperson where IDPerson='$IDPerson' and KodeLokasi='$KodeLokasi' and StatusUTTP='Aktif' order by NamaTimbangan ASC");
										while($kode = mysqli_fetch_array($menu)){
											if($kode['IDTimbangan'] === @$row['IDTimbangan']){
												echo "<option value=\"".$kode['IDTimbangan']."\" selected>".$kode['NamaTimbangan']."</option>\n";
											}else{
												echo "<option value=\"".$kode['IDTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
											}
										}
									?>
								</select>
							</div>
						</div>
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
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group" align="right">
							<?php  if ($Aksi != 'edit'){ ?>
								<button type="submit"  class="btn btn-primary" name="TeraUTTP">Simpan</button>
							<?php }else{ ?>
								<button type="submit"  class="btn btn-primary" name="EditUTTP">Simpan</button>
							<?php } ?>
						</div>
					</div>
				</div>
			</form>
        </div>
	</div>
</div>
