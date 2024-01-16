
<script type="text/javascript" src="../library/ckeditor/ckeditor.js"></script>
<?php
 $id		=@$_GET['IdSet'];
 $nama		=@$_GET['NamaSet'];
 $value		=@$_GET['ValueSet'];
 $file		=@$_GET['FileSet'];

?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h5 id="exampleModalLabel" class="modal-title">Edit Data <?php echo $nama;?></h5>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div id="upload-wrapper">
							<div>
								<form method='post' action='SimpanData/SimpanSetting.php?id=<?php echo base64_encode($id);?>' enctype='multipart/form-data' >
									<input name="id" type="hidden" value="<?php echo $id;?>" />

									<div class="form-group">
									<?php if($id == '5' OR $id == '6' OR $id == '7' OR $id == '8' OR $id == '9') { ?>
										<label>Upload Foto min 2 mb </label>
										<br>
										<img id="image-preview-1" src="<?php echo isset($file) ? '../images/Assets/'.$file : '../images/Assets/thumb_noimage.png'; ?> " class="img-thumbnail" alt="image preview" style="max-width:100%;max-height:200px;"/>
			                            <div class="form-group custom-file mt-2">
			                                <input type="file"  style="display:block;" id="Gambar1" name="Gambar1" onchange="previewImage(1)"/>
			                                <input type="hidden" name="file" value="<?=$file?>">
			                            </div>
									<?php } ?>	
									</div>

									<div class="form-group">
									<?php if($id == '10') { ?>
										<textarea placeholder="keterangan" class="form-control" type="text" name="keterangan" style="border-radius:2px" rows="8" ><?php echo $value; ?></textarea>
									<?php }elseif($id == '10' OR $id == '1' OR $id == '2' OR $id == '3' OR $id == '4'){ ?>
										<textarea placeholder="keterangan" class="form-control" type="text" name="keterangan" style="border-radius:2px" rows="8" ><?php echo $value; ?></textarea>	
									<?php }else{ ?>	
										<textarea placeholder="keterangan" class="txtIsiEdit" type="text" name="keterangan" id="txtIsiEdit" rows="4" ><?php echo $value; ?></textarea>
										<script>
											CKEDITOR.replace('txtIsiEdit', {
											});
										</script>
									<?php } ?>	
									</div>
									<hr>
									<div align="right">
										<input type='submit' class='btn btn-success' style="border-radius:2px;" value='Simpan'>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>

<script type="text/javascript">
 function previewImage(num) {
    document.getElementById("image-preview-"+num).style.display = "block";
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("Gambar"+num).files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("image-preview-"+num).src = oFREvent.target.result;
    };
 };
</script>

	
	