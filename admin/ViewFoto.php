<?php
 include '../library/config.php';
 $FotoDokumen =@$_GET['FotoDokumen'];
 $URLocation  =@$_GET['URLocation'];
?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Lihat Foto</h4>
		  <!--<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>-->
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div id="upload-wrapper">
							<div align="center">
							<?php 
							 if ($URLocation == 'TeraTimbangan'){
								 if($FotoDokumen){
									echo '<img src="../images/TeraTimbangan/'.$FotoDokumen.'" class="img img-responsive img-thumbnail" alt="Image User">';
								}else{
									echo '<img src="../images/Assets/timbangan.png" class="img img-responsive img-thumbnail" alt="Image User">';
								}
							 }elseif ($URLocation == 'FotoPerson'){
								 if($FotoDokumen){
									echo '<img src="../images/FotoPerson/'.$FotoDokumen.'" class="img img-responsive img-thumbnail" alt="Image User">';
								}else{
									echo '<img src="../images/Timbangan/no-image.jpg" class="img img-responsive img-thumbnail" alt="Image User">';
								}
							 }else{
								if($FotoDokumen){
									echo '<img src="../images/Timbangan/'.$FotoDokumen.'" class="img img-responsive img-thumbnail" alt="Image User">';
								}else{
									echo '<img src="../images/Assets/timbangan.png" class="img img-responsive img-thumbnail" alt="Image User">';
								}
							 }							
							?>	
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>