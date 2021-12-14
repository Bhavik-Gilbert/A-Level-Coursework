<?php 
#checks if something has been submitted
if(count($_POST)>0) {
    //initialising variable
    $message = "";
    #validates form
    if (($_POST['Email'] == "") or ($_POST['Comment'] == "")){
		$message = "Please fill in all of the fields";}
    else if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)){
        $message = "Invalid Value for Email Field";}
    else{
        #assigns collected data from the form
        $Email = $_POST['Email'];
        $Comment = $_POST['Comment'];
        #uses mail function to send email
        $send = (mail("bhaviklob@gmail.com",$Email,$Comment));
        #checks if email was sent
        if ($send == true){
            $message = "Message sent successfully, we'll get back to you at our earliest convenience";}
         else if ($send == false){$message = "Message could not be sent, try again later";}
 }}
?> <DOCTYPE html>
<html>
<?php
session_start();
?>
<?php
#displays header at the top of the page
include 'Enitity/Header.php';
?>

<head>
<meta charset="utf-8">
<title>Contact Page</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
?>
<h1> Contact</h1>
<p>If you'd like any more info about my photography, you can get in touch using the form below, via info@londonphotographer.co.uk or on 07986 821020.</p>

<?php #creates form for users to input their email and message ?>
<form method="post">
<div class="message"><?php if ($message!="") {
        echo $message;
    } ?>
<br><br>
Email:<br> <textarea name="Email" rows="1" cols="32"></textarea>
<br><br>
Message:<br> <textarea name="Comment" rows="6" cols="80"></textarea>
<br>
<button type="submit" name="Submit" style="display: block; margin-left: 265px;">Submit</button>
</form>
<p>My Studio Address is: 14 Bacon Street London E1 6LF Telephone Numbers: 07986 821020 020 7193 7633</p>

<br><br><br>
</body>
</html>




