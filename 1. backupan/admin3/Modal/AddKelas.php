<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $KodeTimbangan	=@$_GET['KodeTimbangan'];
 $NamaTimbangan	=@$_GET['NamaTimbangan'];
 $login_id		=@$_GET['login_id'];
 $KodeKelas		=@$_GET['NoUrutTrans'];
 $Aksi			=@$_GET['Aksi'];
 
	if ($Aksi == 'Edit'){
		$sql = mysqli_query($koneksi, ("Select KodeTimbangan,NamaKelas from kelas where KodeKelas='$KodeKelas' and KodeTimbangan='$KodeTimbangan'"));
		$row = mysqli_fetch_array($sql);
	}

?>

<div class="modal-dialog modal-md">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title"><?php echo @$Aksi; ?> Nama Kelas Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="MasterUTTP.php" method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="KodeTimbangan" type="hidden" value="<?php echo $KodeTimbangan;?>" />
						<input name="NamaTimbangan" type="hidden" value="<?php echo $NamaTimbangan;?>" />
						<input name="UserName" type="hidden" value="<?php echo $login_id;?>" />
						<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
						<input name="KodeKelas" type="hidden" value="<?php echo $KodeKelas;?>" />
						<div class="form-group" lign="left">
							<label>Pilih Timbangan</label>
							<select class="form-control" <?php if ($KodeTimbangan != null) { echo 'disabled'; }else{ echo 'required'; } ?>>	
								<?php
									echo "<option value=''>--- Pilih Timbangan ---</option>";
									$menu = mysqli_query($koneksi,"SELECT NamaTimbangan,KodeTimbangan from msttimbangan order by NamaTimbangan ASC");
									while($kode = mysqli_fetch_array($menu)){
									
										if($kode['KodeTimbangan'] === $KodeTimbangan){
											echo "<option value=\"".$kode['KodeTimbangan']."\" selected='selected'>".$kode['NamaTimbangan']."</option>\n";
										}else{
											echo "<option value=\"".$kode['KodeTimbangan']."\" se>".$kode['NamaTimbangan']."</option>\n";
										}
										
									}
								?>
							</select>
						</div>
						<div class="form-group" align="left">
							<label>Nama Kelas</label>
							<input type="text" name="NamaKelas" class="form-control" placeholder="Nama Kelas" value="<?php echo @$row['NamaKelas']; ?>" required>
						</div>
						<div class="form-group" align="right">
							<?php if ($Aksi != 'Edit'){ ?>
								<input type="submit"  class="btn btn-primary" name="SimpanItem" value="Simpan"><br>
							<?php }else{ ?>
							<input type="submit"  class="btn btn-success" name="EditItem" value="Simpan"><br>
								
							<?php } ?>
						</div>
					</div>	
				</div>
			</form>
        </div>
	</div>
</div>