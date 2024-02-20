<!--
Author : Aguzrybudy
Created : Selasa, 19-April-2016
Title : Crud Menggunakan Modal Bootsrap
-->
<?php
 include '../library/config.php';
 $IDTimbangan	=@$_GET['IDTimbangan'];
?>

<div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Lihat Foto Dokumen</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-lg-12">
						<div id="upload-wrapper">
							<div align="center">
								<div class="table-responsive">  
									<table class="table table-striped">
									  <thead>
										<tr>
										  <th>No</th>
										  <th>Foto</th>
										  <th>File</th>
										</tr>
									  </thead>
									  <tbody>
										<?php 
											$no =1;
											$sqla = "SELECT FotoTimbangan1,FotoTimbangan2,FotoTimbangan3,FotoTimbangan4 FROM timbanganperson WHERE IDTimbangan= ? ";
											$ab = $IDTimbangan;
											$oke = $koneksi->prepare($sqla);
											$oke->bind_param('s', $ab);
											$oke->execute();
											$sql= $oke->get_result(); 
											$datafoto = @mysqli_fetch_array($sql);
										?>
										<tr>
										  <th scope="row"><?php echo $no++; ?></th>
										  <td>Foto Timbangan</td>
										  <td width="50px">
										  
											<a href="#" class='open_modal_gambar' data-dokumen='<?php echo $datafoto['FotoTimbangan1'];?>'><i  class="btn btn-success ">Preview</i></a>
												
										  </td>
										</tr>
										<?php if ($datafoto['FotoTimbangan2'] != NULL OR $datafoto['FotoTimbangan2'] !='' ) { ?>
											<tr>
										  <th scope="row"><?php echo $no++; ?></th>
										  <td>Foto Timbangan</td>
										  <td width="50px">
										  
											<a href="#" class='open_modal_view' data-dokumen='<?php echo $datafoto['FotoTimbangan2'];?>'><i  class="btn btn-success ">Preview</i></a>
												
										  </td>
										</tr>
										<?php } ?>
										<?php if ($datafoto['FotoTimbangan3'] != NULL OR $datafoto['FotoTimbangan3'] !='' ) { ?>
											<tr>
										  <th scope="row"><?php echo $no++; ?></th>
										  <td>Foto Timbangan</td>
										  <td width="50px">
										  
											<a href="#" class='open_modal_view' data-dokumen='<?php echo $datafoto['FotoTimbangan3'];?>'><i  class="btn btn-success ">Preview</i></a>
												
										  </td>
										</tr>
										<?php } ?>
										<?php if ($datafoto['FotoTimbangan4'] != NULL OR $datafoto['FotoTimbangan4'] !='' ) { ?>
											<tr>
										  <th scope="row"><?php echo $no++; ?></th>
										  <td>Foto Timbangan</td>
										  <td width="50px">
										  
											<a href="#" class='open_modal_view' data-dokumen='<?php echo $datafoto['FotoTimbangan4'];?>'><i  class="btn btn-success ">Preview</i></a>
												
										  </td>
										</tr>
										<?php } ?>
									  </tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
	</div>
</div>
<div id="ModalGambar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	</div>
	<script type="text/javascript">
	 //open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_gambar").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
			   $.ajax({
					   url: "ViewFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen},
					   success: function (ajaxData){
					   $("#ModalGambar").html(ajaxData);
					   $("#ModalGambar").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>