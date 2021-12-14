<DOCTYPE html>
<?php
session_start();
?>
<html>
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
</html>
