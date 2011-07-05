<?php
header ("Content-type: image/png");

$width = round((int)$_GET['km']/4);

if ($width < 0)
	$width = 0;
if ($width > 330)
	$width = 330;

$image = ImageCreateFromPng('../../img/schuhbalken.png');
$alpha = ImageColorAllocate($image, 255, 255, 255);

ImageFilledRectangle($image, $width, 0, 330, 2, $alpha);
ImageColorTransparent($image, $alpha);

ImagePng($image);
ImageDestroy($image);
?>