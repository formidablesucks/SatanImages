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
<?php
require 'cred.php';

$image = $_DB
->sql("SELECT * FROM images WHERE image_id = :image_id")
->prepare([
	":image_id" => $_GET["i"]
])
->single(true)
->get();

if ($image == null){
    die("Image ID Invalid!");
} else
echo "<img class='view_image' src='uploads/{$image['file']}.jpg'>";
