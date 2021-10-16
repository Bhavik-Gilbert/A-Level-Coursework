<DOCTYPE html>
<?php
session_start();
?>
<html>
<?php
#displays header at the top of the page
include 'Enitity/Header.php';
?>

<head>
<meta charset="utf-8">
<title>Portfolio</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
?>

<?php
#displays photo slideshows
include 'Slideshow.php';
?>



<br><br><br>
</body>
<?php
#displays footer at the top of the page
include 'Enitity/Footer.php';
?>
</html>
