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
<title>Pricing</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
#creates boxes to present information in
?>
<div align = "center">
<h1> Pricing</h1>
<br>
<h2>Shoot Types</h2>
<div align = "center">
<div class="container2" align="center">
<h3>Portraits</h3> 
<p>Corporate Headshot £80/hour</p> 
<p>Portrait Photography 75/hour</p> 
<p>Business Portraits 125/hour</p>
</div>

<div class="container2" align="center">
<h3>Events</h3>
<p>Corporate Lifestyle £140/hour</p> 
<p>Corporate Event Photography £175/hour</p> 
<p>Exhibition and Conference £200/hour</p>
<p>Lifestyle Photography £125/hour</p>
<p>Event Photography £175/hour</p>
<p>Scientific Photography £150/hour</p>
</div>
</div>

<br><br>
<h2>Packages</h2>
<div align = "center">
<div class="container3" align="center">
<h3>Standard</h3>
<h4>Portraits</h3>
<p>20 Photos</p>
<h4>Events</h3>
<p>100 Photos</p>
</div>

<div class="container3" align="center">
<h3>Photo+</h3>
<p>£50</p>
<h4>Portraits</h3>
<p>50 Photos</p>
<h4>Events</h3>
<p>200 photos/<p>
</div>

<div class="container3" align="center">
<h3>Photo Max</h3>
<p>£150</p>
<h4>Portraits</h3>
<p>150 Photso</p>
<h4>Events</h3>
<p>350 photos</p>
</div>

<div class="container3" align="center">
<h3>Studio</h3>
<p>£250</p>
<h4>Portraits</h3>
<p>Standard + Shooting Studio Provided</p>
</div>
</div>

<br><br>
<h4>We offer custom bookings as a mixture between these, after booking email us with you booking information to have it sorted.</h4>

</div>
<br><br><br>
</body>
<?php
#displays footer at the bottom of the page
include 'Enitity/Footer.php';
?>
</html>




