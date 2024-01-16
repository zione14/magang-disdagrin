<?php
include ("../../library/config.php");

	//mendefinisikan folder upload
	define("UPLOAD_DIR", "../../images/web_profil/struktur/");

	if (!empty($_FILES["media"])) {
		$media	= $_FILES["media"];
		$ext	= pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION);
		$size	= $_FILES["media"]["size"];
		$tgl	= date("Y-m-d");

		if($size > (1024000*3)){ // maksimal 3 MB
			echo 'Upload Gagal ! Ukuran file maksimal 3 MB.';
			exit;
		}else{
			if ($media["error"] !== UPLOAD_ERR_OK) {
				echo 'Gagal upload file.';
				exit;
			}

			// filename yang aman
			$name = preg_replace("/[^A-Z0-9._-]/i", "_", $media["name"]);

			// mencegah overwrite filename
			$i = 0;
			$parts = pathinfo($name);
			while (file_exists(UPLOAD_DIR . $name)) {
				$i++;
				$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
			}

			$success = move_uploaded_file($media["tmp_name"], UPLOAD_DIR . $name);
			if ($success) {					
				if($ext=='jpg' OR $ext=='jpeg' OR $ext=='png'){
					//cari file untuk di hapus
					$AmbilData = mysqli_query($koneksi,"SELECT Gambar1 FROM kontenweb WHERE JenisKonten='Struktur' and KodeKonten='KONTEN-2019-00000001'");
					$row=mysqli_fetch_assoc($AmbilData);
					//insert ke db.
					$query = mysqli_query($koneksi,"UPDATE kontenweb SET Gambar1='$name' WHERE JenisKonten='Struktur' and KodeKonten='KONTEN-2019-00000001'");
					if($query){
						if($row['Gambar1']!=null){
							unlink("../../images/web_profil/struktur/".$row['Gambar1']."");
							echo '<script language="javascript">alert("Data Berhasil Disimpan!");document.location="StrukturOrg.php";</script>';
							// echo "Upload File Berhasil!";
							exit;
						}else{
							echo "Upload File Berhasil!";
							exit;
						}
					}else{
						echo "Upload File Gagal !";
						exit;
					}
				}else{
					echo "Extension file harus .jpg/.jpeg/.png";
					exit;
				}
			}
			chmod(UPLOAD_DIR . $name, 0644);
		}
	}
?>