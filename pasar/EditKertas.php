<?php
 include '../library/config.php';
 include '../library/tgl-indo.php';
 
 $NoTrRequest    =@$_GET['NoTrRequest'];
 $NoUrut		 =@$_GET['NoUrut'];
 $KodeKB		 =@$_GET['KodeKB'];
 $JumlahKB	  	 =@$_GET['JumlahKB'];

?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Hasil Tera Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
        	<form action="Request_aksi.php"  method="post">
				<div class="row">
					<div class="col-lg-12">
						<input name="NoTrRequest" type="hidden" value="<?php echo $NoTrRequest;?>" />
						<input name="NoUrut" type="hidden" value="<?php echo $NoUrut;?>" />
						<input name="NilaiTotal" type="hidden" id="NilaiTotal" />
						<input name="aksi" type="hidden" value="EditItem"/>
						<div class="form-group" align="left">
							<label class="form-control-label">Jenis Karcis Retribusi</label>
							<select name="KodeKB" id="comboKB" class="form-control" required>	
								<?php
									$menu = mysqli_query($koneksi,"SELECT KodeKB, NamaKB, NilaiKB  FROM mstkertasberharga WHERE IsAktif='1'");
									while($kode = mysqli_fetch_array($menu)){
										if($kode['KodeKB']== $KodeKB){
											echo '<option value="'.$kode['KodeKB'].'" data-nilai="'.$kode['NilaiKB'].'" data-nama="'.$kode['NamaKB'].'" selected>'.$kode['NamaKB'].'</option>';
										}else{
											echo '<option value="'.$kode['KodeKB'].'" data-nilai="'.$kode['NilaiKB'].'" data-nama="'.$kode['NamaKB'].'">'.$kode['NamaKB'].'</option>';
										}
									}
								?>
							</select>
						</div>
						<div class="form-group" align="left">
							<label class="form-control-label">Jumlah Karcis Retribusi</label>
							<input type="number" placeholder="Jumlah Karcis" class="form-control" name="JumlahKB" value="<?=$JumlahKB?>" id="JumlahDebetKB" required>
						</div>
					</div>
					<div class="col-lg-12" align="right">
						<button type="submit" id="btn-hapus-modal" class="btn btn-sm btn-danger">Simpan</button>	
						<a href="#" data-dismiss="modal" class="btn btn-sm btn-secondary">Batal</a>		
					</div>
				</div>
			</form>
        </div>
	</div>
</div>

<script type="text/javascript">
	var NilaiKB =  $('#comboKB').find('option:selected').attr('data-nilai');
	$('#NilaiTotal').val(parseFloat(NilaiKB));
	$('#comboKB').change(function () {
	    NamaKB = $(this).find('option:selected').attr('data-nama');
	    NilaiKB = $(this).find('option:selected').attr('data-nilai');
	    $('#NilaiTotal').val(parseFloat(NilaiKB));
	});
</script>
	