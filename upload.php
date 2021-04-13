<?php
require 'cred.php';

$bytes = bin2hex(random_bytes(16));

$bytes2 = bin2hex(random_bytes(16));


function convertImage($originalImage, $outputImage, $quality){
		// jpg, png or gif?
		$exploded = explode('.',$originalImage);
		$ext = $exploded[count($exploded) - 1]; 

		if (preg_match('/jpg|jpeg/i',$ext))
			$imageTmp=imagecreatefromjpeg($originalImage);
		else if (preg_match('/png/i',$ext))
			$imageTmp=imagecreatefrompng($originalImage);
		else if (preg_match('/gif/i',$ext))
			$imageTmp=imagecreatefromgif($originalImage);
		else
			return 0;
		// quality is a value from 0 (worst) to 100 (best)
		imagejpeg($imageTmp, $outputImage, $quality);
		imagedestroy($imageTmp);

		return 1;
}
	
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    die("Error: Not an image! We only support png, jpg, jpeg, and gif.");
    $uploadOk = 0;
}



// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    die("Error: Something somewhere went wrong! Try reuploading?");
// if everything is ok, try to upload file
} else {
	
	$tempfile = $_FILES["fileToUpload"]["tmp_name"];
	
	$destination = "uploads/$bytes.jpg";
	
	$ogmd5 = md5_file($tempfile);
	
	$ogname = basename($_FILES["fileToUpload"]["name"]);
	
	move_uploaded_file($tempfile, "tmp/{$ogname}");
	
	convertImage("tmp/{$ogname}", $destination, 75);
	
	unlink("tmp/{$ogname}");
	
	$md5 = md5_file($destination);

	$existing_md5 = $_DB->sql("SELECT hash, oghash, file FROM images WHERE hash = :hash OR hash = :oghash")->prepare([
		":hash" => $md5,
		":oghash" => $ogmd5
	])->single(true)->get();
	

	if ($_DB->row_num > 0) {
		$bytes = $existing_md5['file'];
		unlink($destination);
	}
	
	
	
		$_DB->sql("INSERT INTO images (image_id,file,hash,oghash,uploader_ip,uploader_browser) VALUES (:image_id,:file,:hash,:oghash,:uploader_ip,:uploader_browser)")->prepare([
		
			":image_id" => $bytes2,
			":file" => $bytes,
			":hash" => $md5,
			":oghash" => $ogmd5,
			":uploader_ip" => $_SERVER['REMOTE_ADDR'],
			":uploader_browser" => $_SERVER['HTTP_USER_AGENT']
		
		])->set();

}

header("Location: /");
?>