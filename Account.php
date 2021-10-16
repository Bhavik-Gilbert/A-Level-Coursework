<?php
session_start();
?>
<html>
<?php
include 'Enitity/Header.php';
#directs users who are not logged in to the referal page
if (!isset($_SESSION["Username"])) {
    header("Location:Refer.php");
}
?>

<head>
<meta charset="utf-8">
<title>Account</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
include 'Enitity/menu.php';
	
	echo "<br>";
#creates buttons to link to other pages
if ($_SESSION["Type"] == "Photographer"){ ?>
	<a href="Customer.php" class="btn" type="submit" name="account1" style="background: #905e26; border: 1px solid #6e471d;">Customer Details</a>
<?php if ($_SESSION["ID"] == 1){ ?>
	<a href="photographer.php" class="btn" type="submit" name="account3" style="background: #97694f; border: 1px solid #78533e;" >Photographer Details</a>
<?php } ?>
	<a href="Restore.php" class="btn" type="submit" name="Restore" style="background: #b01030; border: 1px solid #7d0920;">Deleted Information</a>
	<a href="photographersignup.php" class="btn" type="submit" name="signupP" style="background: #343d52; border: 1px solid #1c222e;">New Photographer</a>
<?php } else if ($_SESSION["Type"] == "Consumer"){ ?>
	<a href="Customer.php" class="btn" type="submit" name="account2" style="background: #905e26; border: 1px solid #6e471d;" >Account Details</a>
<?php } ?>
<a href="booking.php" class="btn" type="submit" name="booking" style="background: #007070; border: 1px solid #004f4f;">Bookings</a>

<br><br>
<br><br>
<?php #creates a message and hyperlink to logout?>
Welcome <?php echo $_SESSION["Username"]; ?>. 
You have already logged in.
Click here to <a href="logout.php" tite="Logout">Logout
<br><br>
</body>
<?php
include 'Enitity/Footer.php';
?>
</html>