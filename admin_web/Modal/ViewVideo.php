
<?php
 $Gambar1		=@$_GET['Gambar1'];
 $JudulKonten	=@$_GET['JudulKonten'];


?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<div class="modal-header">
		  <h5 id="exampleModalLabel" class="modal-title"><?php echo $JudulKonten;?></h5>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div id="upload-wrapper">
							<div>
								<iframe width="760" height="450" src="<?php echo $Gambar1; ?>" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>

	
	