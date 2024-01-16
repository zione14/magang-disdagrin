<?php
 include '../../library/config.php';
 include '../../library/tgl-indo.php';
 
 $NoTransaksi	=@$_GET['NoTransaksi'];
 $login_id		=@$_GET['login_id'];
 $NoUrutTrans	=@$_GET['NoUrutTrans'];

 $Aksi			=@$_GET['Aksi'];
 

	$sql = mysqli_query($koneksi,("Select HasilAction1,HasilAction2,HasilAction3,FotoAction1,FotoAction2,FotoAction3,IDTimbangan,IDPerson,KodeLokasi from trtimbanganitem where NoTransaksi='$NoTransaksi' and NoUrutTrans='$NoUrutTrans'"));
	$row = mysqli_fetch_array($sql);
	

?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Hasil Tera Timbangan</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-6">
				<form action="TrTeraDilokasi.php"  method="post">
					<input name="NoTransaksi" type="hidden" value="<?php echo $NoTransaksi;?>" />
					<input name="UserName" type="hidden" value="<?php echo $login_id;?>" />
					<input name="NoUrutTrans" type="hidden" value="<?php echo $NoUrutTrans;?>" />
					<div class="form-group" align="left">
						<label>Pilih Timbangan</label>
						<select name="IDTimbangan" class="form-control" required>	
							<?php
								echo "<option value=''>--- Pilih Timbangan ---</option>";
								$menu = mysqli_query($koneksi,"SELECT IDTimbangan,NamaTimbangan from timbanganperson where IDPerson='".$row['IDPerson']."' and KodeLokasi='".$row['KodeLokasi']."' order by NamaTimbangan ASC");
								while($kode = mysqli_fetch_array($menu)){
									if($kode['IDTimbangan']==$row['IDTimbangan']){
										echo "<option value=\"".$kode['IDTimbangan']."\" selected>".$kode['NamaTimbangan']."</option>\n";
									}else{
										echo "<option value=\"".$kode['IDTimbangan']."\" >".$kode['NamaTimbangan']."</option>\n";
									}
								}
							?>
						</select>
					</div>
					<div class="form-group" align="left">
						<label>Hasil 1</label>
						<textarea type="text" name="HasilAction1" class="form-control" rows="2" placeholder="Hasil 1"><?php echo $row[0]; ?></textarea>
					</div>
					<div class="form-group" align="left">
						<label>Hasil 2</label>
						<textarea type="text" name="HasilAction2" class="form-control" rows="2" placeholder="Hasil 2"><?php echo $row[1]; ?></textarea>
					</div>
					<div class="form-group" align="left">
						<label>Hasil 3</label>
						<textarea type="text" name="HasilAction3" class="form-control" rows="2" placeholder="Hasil 3"><?php echo $row[2]; ?></textarea>
					</div>
					<div class="form-group" align="right">
						<button type="submit"  class="btn btn-primary" name="SimpanEdit">Simpan</button>
					</div>
				</form>
				</div>
				<div class="col-lg-6">
					<label>Upload Gambar type .jpg/.png 2MB</label>
						<form action="SimpanData/UploadTeraKantor.php?id=<?php echo base64_encode($NoTransaksi);?>&urt=<?php echo base64_encode($NoUrutTrans); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
							<div class="form-group-material">
								<div class="input-group">
								<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
								<input type="hidden" name="type" value="edit">
								<input type="hidden" name="nourut" value=" <?php echo '1'; ?>">
								<input type="hidden" name="UserName" value=" <?php echo $login_id; ?>">
								<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
								<span class="input-group-btn">
									<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
									<a href="#" class='open_modal_view' data-dokumen='<?php echo $row[3];?>' data-url='<?php echo 'TeraTimbangan';?>'><i  class="btn btn-success ">Preview</i></a>
								</span>
								</div>
							</div>
						</form>
						
						
						<form action="SimpanData/UploadTeraKantor.php?id=<?php echo base64_encode($NoTransaksi);?>&urt=<?php echo base64_encode($NoUrutTrans); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
						
							<div class="form-group-material">
								<div class="input-group">
								<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
								<input type="hidden" name="type" value="edit">
								<input type="hidden" name="nourut" value=" <?php echo '2'; ?>">
								<input type="hidden" name="UserName" value=" <?php echo $login_id; ?>">
								<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
								<span class="input-group-btn">
									<!-- tombol submit -->
									<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
									<?php if ($row[4] != '' OR $row[4]!= NULL) {?>
									<a href="#" class='open_modal_view' data-dokumen='<?php echo $row[4];?>' data-url='<?php echo 'TeraTimbangan';?>'><i  class="btn btn-success ">Preview</i></a>
									<?php } ?>
								</span>
								</div>
							</div>
						
						</form>
					
					
						<form action="SimpanData/UploadTeraKantor.php?id=<?php echo base64_encode($NoTransaksi);?>&urt=<?php echo base64_encode($NoUrutTrans); ?>" id="MyUploadForm" method="post" enctype="multipart/form-data">
						
							<div class="form-group-material">
								<div class="input-group">
								<input type="file" name="filefoto" id="filefoto" class="form-control" placeholder="Nama File"  style="width: 20%;" required>&nbsp;
								<input type="hidden" name="type" value="edit">
								<input type="hidden" name="nourut" value=" <?php echo '3'; ?>">
								<input type="hidden" name="UserName" value=" <?php echo $login_id; ?>">
								<input name="Aksi" type="hidden" value="<?php echo $Aksi;?>" />
									<span class="input-group-btn">
									<!-- tombol submit -->
									<button type="submit"  id="submit-btn"  class="btn btn-info" name="Simpan">Upload</button>
									<?php if ($row[5] != '' OR $row[5]!= NULL) {?>
									<a href="#" class='open_modal_view' data-dokumen='<?php echo $row[5];?>' data-url='<?php echo 'TeraTimbangan';?>'><i  class="btn btn-success ">Preview</i></a>
									<?php } ?>
								</span>
								</div>
							</div>
						</form>
						
						<img src="../web/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
							<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
						<div class="text-center">
							<div align="center" id="output"></div>
						</div>
					
				</div>
			</div>
        </div>
	</div>
</div>
	<!-- Modal Popup untuk Edit--> 
	<div id="ModalDokumen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
	<script type="text/javascript">
		//open modal lihat foto
		$(document).ready(function () {
	    $(".open_modal_view").click(function(e) {
		  var foto_dokumen  = $(this).data("dokumen");
		  var url_foto  = $(this).data("url");
			   $.ajax({
					   url: "ViewFoto.php",
					   type: "GET",
					   data : {FotoDokumen: foto_dokumen, URLocation: url_foto},
					   success: function (ajaxData){
					   $("#ModalDokumen").html(ajaxData);
					   $("#ModalDokumen").modal('show',{backdrop: 'true'});
				   }
				});
			});
		});
	</script>
	<!-- =============================================Progres Bar=================================-->
	<script type="text/javascript" src="../komponen/js/jquery.form.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() { 

		var progressbox     = $('#progressbox');
		var progressbar     = $('#progressbar');
		var statustxt       = $('#statustxt');
		var completed       = '0%';
		
		var options = { 
				target:   '#output',   // target element(s) to be updated with server response 
				beforeSubmit:  beforeSubmit,  // pre-submit callback 
				uploadProgress: OnProgress,
				success:       afterSuccess,  // post-submit callback 
				resetForm: true        // reset the form after successful submit 
			}; 
			
		 $('#MyUploadForm').submit(function() { 
				$(this).ajaxSubmit(options);  			
				// return false to prevent standard browser submit and page navigation 
				return false; 
			});
		
	//when upload progresses	
	function OnProgress(event, position, total, percentComplete)
	{
		//Progress bar
		progressbar.width(percentComplete + '%') //update progressbar percent complete
		statustxt.html(percentComplete + '%'); //update status text
		if(percentComplete>50)
			{
				statustxt.css('color','#fff'); //change status text to white after 50%
			}
	}

	//after succesful upload
	function afterSuccess()
	{
		$('#submit-btn').show(); //hide submit button
		$('#loading-img').hide(); //hide submit button

	}

	//function to check file size before uploading.
	function beforeSubmit(){
		//check whether browser fully supports all File API
	   if (window.File && window.FileReader && window.FileList && window.Blob)
		{

			if( !$('#filefoto').val()) //check empty input filed
			{
				$("#output").html("Masukkan gambar terlebih dahulu!");
				return false
			}
			
			var fsize = $('#filefoto')[0].files[0].size; //get file size
			var ftype = $('#filefoto')[0].files[0].type; // get file type
			
			//allow only valid image file types 
			switch(ftype)
			{
				case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
					break;
				default:
					$("#output").html("<b>"+ftype+"</b> Unsupported file type!");
					return false
			}
			
			//Allowed file size is less than 1 MB (1048576)
			if(fsize>3048576) 
			{
				$("#output").html("<b>"+bytesToSize(fsize) +"</b> File Gambar Terlalu Besar <br />Masksimal upload 3 MB.");
				return false
			}
			
			//Progress bar
			progressbox.show(); //show progressbar
			progressbar.width(completed); //initial value 0% of progressbar
			statustxt.html(completed); //set status text
			statustxt.css('color','#000'); //initial color of status text

					
			$('#submit-btn').hide(); //hide submit button
			$('#loading-img').show(); //hide submit button
			$("#output").html("");  
		}
		else
		{
			//Output error to older unsupported browsers that doesn't support HTML5 File API
			$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
			return false;
		}
	}

	//function to format bites bit.ly/19yoIPO
	function bytesToSize(bytes) {
	   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	   if (bytes == 0) return '0 Bytes';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}

	}); 

	</script>