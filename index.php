<?php

$_URL = explode("?", substr($_SERVER['REQUEST_URI'], 1));
$_URL = explode("/", $_URL[0]);

require("database.php");

$_DB = new database([
	"username" => "administrator",
	"password" => "akH5u8D9AaVqspPv",
	"database" => "satanimages",
	"host" => "localhost",
	
]);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<title>SatanImages</title>
<meta charset="UTF-8">

<meta name="description" content="Satanic IMAGES have never been THIS good!">

<meta name="keywords" content="experience,satan,s8n,me,imaages,">
<meta name="author" content="S8N.ME">
</head>
<body>
<img src="/assets/img/logo.png" alt="logo">
<form action="upload.php" method="post" enctype="multipart/form-data">
<p>Your one stop shop for Satanist Images!</p>
    Select Image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<?php
$total = $_DB->sql("SELECT COUNT(*) AS amount FROM images")->single(true)->get();

echo ("We proudly host {$total['amount']} satanist images! <br> <br>");

if (isset($_GET["p"]) && is_numeric($_GET["p"]) && $_GET["p"] > 0){
	$page = $_GET["p"];
} else {
	$page = 0;
}
$limit = 36;
$offset = $limit * $page;

$images = $_DB->sql("SELECT * FROM images ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}")
->single(false)
->get();

if ($images)
	foreach($images as $image) {
		echo "<a href='uploads/{$image['file']}.jpg'><img class='uploaded' src='uploads/{$image['file']}.jpg'></a>";
	}
else
	echo "There are no images on this page! <br>";

?>
<br>
<a href="/?p=<?php if ($page > 0): echo $page - 1; else: echo 0; endif; ?>">Previous</a> <a href="/?p=<?= $page + 1 ?>">Next</a>
</body>
<br>
<br>
<b>NEW</b> in SatanImages 1.1.1 
<li>added a new goodbye to the old update log (goodbye not having a goodbye)</li>
<br>
<br>
<b>NEW</b> in SatanImages 1.1 
<li>jpg compression (goodbye gigabytes of bandwidth waste)</li>
<li>automatic file renaming(goodbye not being able to upload two images of the same name)</li>
<li>sequential image sorting (goodbye random arrangement, newest image is always on top)</li>
<li>duplicate images now use the same file to save space (goodbye gigbytes of wasted storage)</li>
</html>
<br>
<br>