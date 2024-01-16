<?php
 $FotoDokumen	=@$_GET['FotoDokumen'];
 $URLocation	=@$_GET['URLocation'];

?>

<div class="modal-dialog modal-md">
    <div class="modal-content">
    	<div class="modal-header">
		  <h4 id="exampleModalLabel" class="modal-title">Upload Foto User</h4>
		  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		</div>
        <div class="modal-body">
			<form action="ProsesUpload.php" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
				<div class="row">
					<div class="col-lg-12">
						<input name="UserName" type="hidden" value="<?php echo $FotoDokumen;?>" />
						<input name="URLocation" type="hidden" value="<?php echo $URLocation;?>" />
						<div class="form-group" align="left">
							<label>Masukkan Foto Profil</label><br>
							<input class="form-control" type="file" name="ImageFile" id="imageInput" required />
							<small>* Max 2 MB dan Tipe File Harus .jpg, .jpeg,.png</small>
						</div>
						<div class="form-group" align="right">
							<input type="submit"  class="btn btn-primary" id="submit-btn" value="Unggah Foto" />
							<img src="../web/images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
						</div>
					</div>	
				</div>
			</form>
			<div class="row">
				<div class="col-lg-12">
					<div id="progressbox" style="display:none;"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
					<div align="center" id="output"></div>
				</div>
			</div>
        </div>
	</div>
</div>
<!-- =============================================Progres Bar=================================-->
	<link href="../komponen/css/upload-img.css" rel="stylesheet">
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

			if( !$('#imageInput').val()) //check empty input filed
			{
				$("#output").html("Masukkan gambar terlebih dahulu!");
				return false
			}
			
			var fsize = $('#imageInput')[0].files[0].size; //get file size
			var ftype = $('#imageInput')[0].files[0].type; // get file type
			
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
			if(fsize>4048576) 
			{
				$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
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