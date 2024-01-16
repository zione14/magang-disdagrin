<!--
Author : Aguzrybudy
Created : Selasa, 19-April-2016
Title : Crud Menggunakan Modal Bootsrap
-->
<?php
 $JudulKonten	=@$_GET['JudulKonten'];
 $IsiKonten		=@$_GET['IsiKonten'];
 $Dokumen		=@$_GET['Dokumen'];
 $KodeKonten	=@$_GET['KodeKonten'];
 $JenisKonten	=@$_GET['JenisKonten'];
?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Unggah Dokumen</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div id="upload-wrapper">
							<div>
								<form action="SimpanData/UploadDokumen.php" method="post" enctype="multipart/form-data" >
									<div class="form-group-material">
									  <label>Nama Dokumen</label>
									  <input type="text" name="JudulKonten" style="border-radius: 10px;" class="form-control" placeholder="Nama Dokumen">
									</div>
									<br>
									<?php if($JenisKonten == 'Sakip') : ?>
									<div class="form-group-material">
									  <label>Tahun</label>
									  <input type="text" name="IsiKonten" style="border-radius: 10px;" class="form-control" placeholder="Tahun">
									</div>
									<br>
									<?php endif; ?>
									<div class="form-group-material">
										<label>File type .doc .pdf .xls .ppt dengan ukuran max 4 mb</label>
										<input name="JenisKonten" type="hidden" value="<?php echo $JenisKonten;?>" />
										<input name="Gambar1" id="Gambar1" type="file" required><br/>
									</div>
									<br>
									<div align="right">
									<input type="submit"  class="btn btn-primary" id="submit-btn" value="Unggah" /><br><br></div>
									<img src="../images/Assets/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
								</form>
								
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div><br>
						<!-- <div align="center" id="output"></div> -->
					</div>
				</div>
			</div>
        </div>
	</div>
</div>

