<?php
function unique_filename($filename){
	$temp = $filename;
	$tpi =  pathinfo($temp); //target file path info
	$i = 1;
	while (file_exists($temp)) {
		$temp = $tpi['dirname'] .'/'. $tpi['filename'] . ' ('.$i.').'.$tpi['extension'];	
		$i++;
	}
	return $temp;
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists 
/*
$temp_target_file = $target_file;
$tpi =  pathinfo($target_file); //target file path info
$i = 1;
while (file_exists($temp_target_file)) {
	$temp_target_file = $tpi['dirname'] .'/'. $tpi['filename'] . ' ('.$i.').'.$tpi['extension'];	
	$i++;
}
$target_file = $temp_target_file ;
echo $target_file;
*/
$target_file = unique_filename($target_file);
echo $target_file;

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0; 
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?> 


<!DOCTYPE html>
<html>
<body>
<img src="<?=$target_file?>">

</body>
</html> 
