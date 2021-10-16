<?php
 
session_start();

#creates random 6 digit string
$random = md5(rand());
$captcha = substr($random, 0, 6);
$_SESSION['captcha'] = $captcha;
#creates image out of string data
header('Content-Type: captcha/png');

#sets image colours
$image = imagecreatetruecolor(200, 50);
$background = imagecolorallocate($image, 0, 162, 232);
$text = imagecolorallocate($image, 255, 255, 255);
imagefilledrectangle($image, 0, 0, 200, 50, $background);
#collects text type information
$font =  dirname(__FILE__).'/Enitity/arial.ttf';
#creates text
imagettftext($image, 20, 0, 60, 50, $text, $font, $captcha);
#creates image
imagepng($image);
imagedestroy($image);

?>