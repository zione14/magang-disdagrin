<?php

class ImageFunction {

    private $error;
    private $newfilename;

    public function upload($photo, $DestinationDirectory, $NewImageName) {
        $ThumbSquareSize = 200; //Thumbnail will be 200x200
        $BigImageMaxSize = 600; //Image Maximum height or width
        $ThumbPrefix = "thumb_"; //Normal thumb Prefix
        $Quality = 100; //jpeg quality
        move_uploaded_file($photo["tmp_name"], "$DestinationDirectory" . $photo["name"]);
        $file = $DestinationDirectory . $photo["name"];
        // Random number will be added after image name
        $RandomNumber = rand(0, 9999999999);
        $ImageName = str_replace(' ', '-', strtolower($file)); //get image name
        $ImageSize = filesize($file); // get original image size
        $TempSrc = $photo['tmp_name']; // Temp name of image file stored in PHP tmp folder
        $ImageType = pathinfo($file, PATHINFO_EXTENSION); //get file type, returns "image/png", image/jpeg, text/plain etc.
        switch (strtolower($ImageType)) {
        case 'image/png':
        case 'png':
            //Create a new image from file
            $CreatedImage = imagecreatefrompng($file);
            break;
        case 'image/gif':
        case 'gif':
            $CreatedImage = imagecreatefromgif($file);
            break;
        case 'jpg':
        case 'jpeg':
        case 'pjpeg':
        case 'image/jpg':
        case 'image/jpeg':
        case 'image/pjpeg':
            $CreatedImage = @ImageCreateFromJpeg($file);
            if (!$CreatedImage) {
                $CreatedImage = imagecreatefromstring(file_get_contents($file));
            }
            //$CreatedImage = imagecreatefromjpeg($file);
            break;
        default:
            $this->error = 'jenis foto tidak support!';
            return FALSE;
            break;
        }
        //PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
        //Get first two values from image, width and height.
        //list assign svalues to $CurWidth,$CurHeight
        list($CurWidth, $CurHeight) = getimagesize($file);
        //Get file extension from Image name, this will be added after random name
        $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
        $ImageExt = str_replace('.', '', $ImageExt);
        //remove extension from filename
        $ImageName = preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName);
        //Construct a new name with random number and extension.
        $NewImageName = $NewImageName . '.' . $ImageExt;
        $this->newfilename = $NewImageName;
        //set the Destination Image
        $thumb_DestRandImageName = $DestinationDirectory . $ThumbPrefix . $NewImageName; //Thumbnail name with destination directory
        $DestRandImageName = $DestinationDirectory . $NewImageName; // Image with destination directory
        // echo $thumb_DestRandImageName.'</br>'.$DestRandImageName;exit;
        if ($this->resizeImage($CurWidth, $CurHeight, $BigImageMaxSize, $DestRandImageName, $CreatedImage, $Quality, $ImageType)) {
            //Create a square Thumbnail right after, this time we are using cropImage() function
            if (!$this->cropImage($CurWidth, $CurHeight, $ThumbSquareSize, $thumb_DestRandImageName, $CreatedImage, $Quality, $ImageType)) {
                if (file_get_contents($file) != null) {
                    unlink($file);
                }
                $this->error = 'foto gagal di crop';
                return FALSE;
            }
            if (file_get_contents($file) != null) {
                unlink($file);
            }
            return TRUE;
        } else {
            if (file_get_contents($file) != null) {
                unlink($file);
            }
            $this->error = 'foto gagal di resize';
            return FALSE;
        }
    }

    public function geterror() {
        return $this->error;
    }

    public function getimagename() {
        return $this->newfilename;
    }

    // This function will proportionally resize image
    function resizeImage($CurWidth, $CurHeight, $MaxSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
        //Check Image size is not 0
        if ($CurWidth <= 0 || $CurHeight <= 0) {
            return FALSE;
        }
        //Construct a proportional size of new image
        $ImageScale = min($MaxSize / $CurWidth, $MaxSize / $CurHeight);
        $NewWidth = ceil($ImageScale * $CurWidth);
        $NewHeight = ceil($ImageScale * $CurHeight);
        $NewCanves = imagecreatetruecolor($NewWidth, $NewHeight);

        // Resize Image
        if (@imagecopyresampled($NewCanves, $SrcImage, 0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight)) {
            switch (strtolower($ImageType)) {
            case 'image/png':
            case 'png':
                imagepng($NewCanves, $DestFolder);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($NewCanves, $DestFolder);
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
                imagejpeg($NewCanves, $DestFolder, $Quality);
                break;
            default:
                return false;
            }
            //Destroy image, frees memory
            if (@is_resource($NewCanves)) {
                @imagedestroy($NewCanves);
            }
            return TRUE;
        }
    }

    //This function corps image to create exact square images, no matter what its original size!
    function cropImage($CurWidth, $CurHeight, $iSize, $DestFolder, $SrcImage, $Quality, $ImageType) {
        //Check Image size is not 0
        if ($CurWidth <= 0 || $CurHeight <= 0) {
            return false;
        }
        //abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
        if ($CurWidth > $CurHeight) {
            $y_offset = 0;
            $x_offset = ($CurWidth - $CurHeight) / 2;
            $square_size = $CurWidth - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($CurHeight - $CurWidth) / 2;
            $square_size = $CurHeight - ($y_offset * 2);
        }

        $NewCanves = imagecreatetruecolor($iSize, $iSize);
        if (imagecopyresampled($NewCanves, $SrcImage, 0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size)) {
            switch (strtolower($ImageType)) {
            case 'image/png':
            case 'png':
                imagepng($NewCanves, $DestFolder);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($NewCanves, $DestFolder);
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
                imagejpeg($NewCanves, $DestFolder, $Quality);
                break;
            default:
                return false;
            }
            //Destroy image, frees memory
            if (is_resource($NewCanves)) {
                imagedestroy($NewCanves);
            }
            return true;
        }
    }
}
