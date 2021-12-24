<?php 
#checks if something has been submitted
if(!empty($_POST)) {
    //initialising variable
    $message = "";
    #validates form
    if ((empty($_POST['Email'])) || (empty($_POST['Comment']))){
		$message .= "Please fill in all of the fields <br>";
    }
    if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)){
        $message .= "Invalid Value for Email Field <br>";
    }
    if(empty($message)){
        $email = $_POST['Email'];
        $message = $_POST['Comment'];
        include'mail.php';  
    }
}
?> <DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Contact Page</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
#displays navbar at the top of the page
include 'Enitity/menu.php';
?>
<div align="center">
    <h1> Contact</h1>
    <p2>If you'd like any more info about my photography, you can get in touch using the form below.</p2>
</div>


<?php #creates form for users to input their email and message ?>
<form method="post" align="center">
 <?php if(!empty($message)) { ?> <div class="message"> <?php echo $message; ?> </div> <?php } ?>

<div class="input-group">
<label>Email</label> 
<input name="Email" rows="1" cols="32"></input>
</div>
<div class="input-group">
<label>Message</label>
<textarea name="Comment" rows="6" cols="80"></textarea>
</div>

<button type="submit" class="btn name="Submit">Send</button>
</form>

<br><br>
<div align="center">
    <p2>p.s. the mailer may not work due to the server not being able to handle the request<br>
        you can feel free to upload the code from my github to another server if you really want to test this<br>
        sorry for the inconvenience</p2>
</div>

<br><br><br>
</body>
</html>




