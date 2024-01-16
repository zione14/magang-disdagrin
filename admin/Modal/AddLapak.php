<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $IDPerson		=@$_GET['IDPerson'];
 $Aksi			=@$_GET['Aksi'];

?>

<div class="modal-dialog modal-md">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Tambah Lapak User</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="LokasiUserAdd.php" method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="IDPerson" type="hidden" value="<?php echo $IDPerson;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<div class="form-group" lign="left">
							<label>Pilih Pasar</label>
							<select class="form-control" name="KodePasar" required>	
								<?php
									echo "<option value=''>--- Pilih Pasar ---</option>";
									$menu = mysqli_query($koneksi,"SELECT NamaPasar,KodePasar from mstpasar order by NamaPasar ASC");
									while($kode = mysqli_fetch_array($menu)){
									
										if($kode['KodePasar'] === $KodePasar){
											echo "<option value=\"".$kode['KodePasar']."\" selected='selected'>".$kode['NamaPasar']."</option>\n";
										}else{
											echo "<option value=\"".$kode['KodePasar']."\" se>".$kode['NamaPasar']."</option>\n";
										}
										
									}
								?>
							</select>
						</div>
						<div class="form-group" align="left">
							<label>Blok Lapak</label>
							<input type="text" name="BlokLapak" class="form-control" placeholder="Blok Lapak" value="<?php echo @$row['NamaKelas']; ?>" required>
						</div>
						<div class="form-group" align="left">
							<label>Nomor Lapak</label>
							<input type="text" name="NomorLapak" class="form-control" placeholder="Nomor Lapak" value="<?php echo @$row['NamaKelas']; ?>" required>
						</div>
						<!--<div class="form-group" align="left">
							<label>Retribusi</label>
							<input type="number" name="Retribusi" class="form-control" placeholder="Retribusi" value="<?php echo @$row['NamaKelas']; ?>" required>
						</div>-->
						<div class="form-group" align="right">
							<?php if (@$Aksi != 'Edit'){ ?>
								<input type="submit"  class="btn btn-primary" name="SimpanLapak" value="Simpan"><br>
							<?php }else{ ?>
							<input type="submit"  class="btn btn-success" name="EditItemLapak" value="Simpan"><br>
								
							<?php } ?>
						</div>
					</div>	
				</div>
			</form>
        </div>
	</div>
</div>