<DOCTYPE HTML>
<?php
session_start();
?>
<html>
<?php
#links the stylesheet to determine the design of the page
?>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<title>Home Page</title>
</head>
<body>
<?php
#add the navbar under the header
include 'Enitity/menu.php';
?>


<br><br>

<?php
#creates hypertext linking to other pages
?>

<a href="Pricing.php" tite="Portfolio">Find information on my prices here

<br><br>

<a href="Portfolio.php" tite="Portfolio">Find some example shots here

<br><br><br>
</body>
</html>
</DOCTYPE>
