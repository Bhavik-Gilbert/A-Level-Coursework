<?php

session_start();
$message="";
#checks if form is submitted
if (isset($_POST['Submit'])) {
    if ($_POST['captcha'] == $_SESSION['captcha']) {
        #connects to database
        include 'Enitity/connect.php';
         
        #checks if username is in the login table
        $result = mysqli_query($con, "SELECT * FROM login WHERE Username='" . $_POST["Username"] ."'");
        $row  = mysqli_fetch_array($result);
        
        #checks if the password at the record with username entered matches the hashed password in the table
        if (is_array($row)) {
            $Hash = crypt($_POST["Password"], $row["Password"]);
            if ($Hash == $row["Password"]) {
                $end = "true";
            } else {
                $end = "false";
            }
        }

        #logs user in and defines their session type and username for user access
        if ($end) {
            $_SESSION["Type"] = $row['Type'];
            $_SESSION["Username"] = $row['Username'];
            if ($_SESSION['Type']=="Consumer") {
                $result2 = mysqli_query($con, "SELECT * FROM consumer WHERE LoginID='" . $row["LoginID"] ."'");
                $row2  = mysqli_fetch_array($result2);
                $_SESSION["ID"] = $row2['ConsumerID'];
            } elseif ($_SESSION['Type']=="Photographer") {
                $result2 = mysqli_query($con, "SELECT * FROM photographer WHERE LoginID='" . $row["LoginID"] ."'");
                $row2  = mysqli_fetch_array($result2);
                $_SESSION["ID"] = $row2['PhotographerID'];
            };
            #redirects user to the account page
            header("Location:Account.php");
        } else {
            $message = "Invalid Username or Password";
        }
    } else {
        $message = "Invalid captcha";
    }
}
?>

<html>
<head>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
<title>User Login</title>
</head>
<body>
<?php
include 'Enitity/menu.php';
?>

<h1 style="text-align:center">Login</h1>

<?php
if($_SESSION["Username"]) {
    #redirects users to the account page if they are already logged in
    header("Location:Account.php");
}else{
    #provides a form for users to fill in their information
?>
<form method="post" action="" align="center">
<?php #displays an error message ?>
<div class="message"><?php if($message!="") { echo $message; } ?></div>
<h3 align="center">Enter Login Details</h3>

<div class="input-group">
<label>Username</label>
<input type="text" name="Username">
</div>

<div class="input-group">
<label>Password</label>
<input type="password" name="Password">
</div>

<div class="input-group">
    <label>Please Enter the Captcha Text</label>
    <input type="text" name="captcha">
    <br><br>
    <div class = "captcha" align="center">
    <?php #displays the captcha code
	$random = md5(rand());
	$captcha = substr($random, 0, 6);
	$_SESSION['captcha'] = $captcha;
	echo $captcha;?>
    </div>
</div>

<div class="input-group">
<button class="btn" type="submit" name="Submit" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Login</button>
</div>

</form>

<?php
#creates a hyperlink to the signup page
?>
<div align="center">
<a href="signup.php" title="Signup">Signup
</div>

<?php
}
?>

<br><br><br>
</body>
</html>
