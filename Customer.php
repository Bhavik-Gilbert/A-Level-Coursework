<?php 
	session_start();
	#connect to database
	include 'Enitity/connect.php';
	# initialize variables
	$record = mysqli_query($con, "SELECT * FROM consumer WHERE ConsumerID='$_SESSION[ID]'");
	$n = mysqli_fetch_array($record);
	$Firstname = $n['Firstname'];
	$Surname = $n['Surname'];
	$Email = $n['Email'];
	$PhoneNumber = $n['PhoneNumber'];
	$Address = $n['Address'];
	$update = false;
	$record2 = mysqli_query($con, "SELECT * FROM login WHERE LoginID='".$n['LoginID']."'");
	$n2 = mysqli_fetch_array($record2);
	$Password =$n2['Password'];

	$ID = $_SESSION['ID'];
	$id = 0;

	if (isset($_GET['edit'])) {
		#assigns id from the edit id
		$id = $_GET['edit'];
		$_SESSION['edit'] = $id;
		#assigns id to be used on update
		$update = true;
		#collects record from the Consumer Table at id
		$record = mysqli_query($con, "SELECT * FROM consumer WHERE ConsumerID='".$id."'");
		$n = mysqli_fetch_array($record);
		#assigns data from record
		$Firstname = $n['Firstname'];
		$Surname = $n['Surname'];
		$Email = $n['Email'];
		$PhoneNumber = $n['PhoneNumber'];
		$Address = $n['Address'];

		#collects record from the Login Table at collected LoginID
		$record2 = mysqli_query($con, "SELECT * FROM login WHERE LoginID='".$n['LoginID']."'");
		$n2 = mysqli_fetch_array($record2);
		#assigns password from record
		$Password =$n2['Password'];
	}
			
	#checks if the update is selected
	if (isset($_POST['update'])) {
		#assigns data collected from form 
		$Firstname = $_POST['Firstname'];
		$Surname = $_POST['Surname'];
		$Email = $_POST['Email'];
		$PhoneNumber = $_POST['PhoneNumber'];
		$Address = $_POST['Address'];
		$OPassword = $_POST['OPassword'];
		$Password =($_POST['Password']);
		
		#hashes password
		$Format = "$2y$10$";
		$HashLength = 55;
		$Unique = md5(uniqid(mt_rand(),true));
		$Base64  = base64_encode($Unique);
		$Modified = str_replace('+','.',$Base64);
		$Generate = substr($Modified,0,$HashLength);
		$Formatting = $Format.$Generate;
		$Hash1 = crypt($Password, $Formatting);

		#creates conditions for strong password
		$uppercase = preg_match('@[A-Z]@', $Password);
		$lowercase = preg_match('@[a-z]@', $Password);
		$number    = preg_match('@[0-9]@', $Password);
		$specialChars = preg_match('@[^\w]@', $Password);

		#gets ConsumerID of chosen consumer
		if ($_SESSION["Type"]=="Consumer"){
			$id = $_SESSION["ID"];
		}
		else if ($_SESSION["Type"]=="Photographer"){
			$id = $_SESSION["edit"];
		}
		#collects data from the consumer table at the id to get the LoginID
		$result = mysqli_query($con,"SELECT * FROM consumer WHERE ConsumerID='" . $id ."'");
		$row  = mysqli_fetch_array($result);
		#collects data from the login table at the collected LoginID
		$result2 = mysqli_query($con, "SELECT * FROM login WHERE LoginID='".$row['LoginID']."'");
		$row2 = mysqli_fetch_array($result2);
	
		#Hashes password using the password collected from the Login Table
		$Hash = crypt($OPassword, $row2["Password"]);
		#Checks if the hashes match
		if ($Hash == $row2["Password"]){
			$end = "true";
		}
		else {
			$end = "false";
		}
		
		#validates form inputs
		if ($_SESSION["Type"] == "Consumer"){
			if ((empty($Firstname)) || (empty($Surname)) || (empty($Email) && empty($PhoneNumber)) || (empty($Address)) || (empty($OPassword))){
				$message = "Please fill in all of the fields";
			}
			else if (is_numeric($Firstname)){
				$message = "Invalid Value for Firstname Field";
			}
			else if (is_numeric($Surname)){
				$message = "Invalid Value for Surname Field";
			}
			else if (!filter_var($Email, FILTER_VALIDATE_EMAIL)){
				$message = "Invalid Value for Email Field";
			}
			else if ((!is_numeric($PhoneNumber)) || (strlen($_POST['PhoneNumber'])<11)){
				$message = "Invalid Value for PhoneNumber Field. It should be a UK number in 07 or 02 form.";
			}

			else if  ($end == "false") {
				$message = "The old password is incorrect";
			}
		else{
			#validates new password if entered
			if($Password <>""){
				if (strlen($_POST['Password'])<8){
					$message = "Password is too short, it must be at least 8 characters long";
				}
				else if(!$uppercase || !$lowercase || !$number || !$specialChars){
					$message = "Password must include 1 upper case, 1 lower case, 1 number and 1 special character";
				}
				else {
					#updates password in login table at the corresponding LoginID
              		mysqli_query($con, "UPDATE login SET Password='$Hash1'  WHERE LoginID='" . $row['LoginID'] ."'") or die(mysqli_error($con));
			}
		}
				#updates the record in the Consumer Table at the corresponding ConsumerID
				mysqli_query($con, "UPDATE consumer SET Firstname='$Firstname', Surname='$Surname', Email='$Email', PhoneNumber='$PhoneNumber', Address='$Address' 
				WHERE ConsumerID='" . $id ."'") or die (mysqli_error($con));
				$message = "Details Successfully Updated";
			}
		}
		
			#validates form inputs
		if ($_SESSION["Type"] == "Photographer"){
			if ((empty($Firstname)) || (empty($Surname)) || (empty($Email) && empty($PhoneNumber)) || (empty($Address))){
				$message = "Please fill in all of the fields";
			}
			else if (is_numeric($Firstname)){
				$message = "Invalid Value for Firstname Field";
			}
			else if (is_numeric($Surname)){
				$message = "Invalid Value for Surname Field";
			}
			else if (!filter_var($Email, FILTER_VALIDATE_EMAIL)){
				$message = "Invalid Value for Email Field";
			}
			else if ((!is_numeric($PhoneNumber)) || (strlen($_POST['PhoneNumber']<11))){
				$message = "Invlaid Value for PhoneNumber Field. It should be a UK number in 07 or 02 form.";
			}
			else{
				#updates the record in the Consumer Table at the corresponding ConsumerID
				mysqli_query($con, "UPDATE consumer SET Firstname='$Firstname', Surname='$Surname', Email='$Email', PhoneNumber='$PhoneNumber', Address='$Address' 
				WHERE ConsumerID='" . $id ."'") or die (mysqli_error($con));
				$message = "Details Successfully Updated";
			}
		}
	}
	
		#checks if the del class button is selected
	if (isset($_GET['del'])) {
		#assigns id from the del id
        $id = $_GET['del'];
        
		#collects record in the Consumer Table at the corresponding ConsumerID
        $transfer = mysqli_query($con, "SELECT * FROM consumer WHERE ConsumerID='" . $id ."'") or (mysqli_error($con));
		$d = mysqli_fetch_array($transfer);
		
		#collects record in the Login Table at the corresponding LoginID collected
		$transfer2 = mysqli_query($con, "SELECT * FROM login WHERE LoginID='".$d['LoginID']."'");
		$d2 = mysqli_fetch_array($transfer2);

		#assigns data from collected record
        $Firstname = $d["Firstname"];
        $Surname = $d["Surname"];
        $Email = $d["Email"];
		$PhoneNumber = $d["PhoneNumber"];
		$Address = $d["Address"];
        $Username = $d2["Username"];
		$Password = $d2["Password"];

		#backups record by inserting data into DeletedConsumer Table
        mysqli_query($con, "INSERT INTO deletedconsumer(ConsumerID, Firstname, Surname , Email , PhoneNumber , Username , Password, LoginID, Address) 
		VALUES ('$id' , '$Firstname', '$Surname' , '$Email' , '$PhoneNumber' , '$Username' , '$Password',$d[LoginID], '$Address')") or die(mysqli_error($con));
        
		#deletes record in the Consumer Table at the corresponding ConsumerID
		mysqli_query($con, "DELETE FROM consumer WHERE ConsumerID='" . $id ."'") or (mysqli_error($con));
		#deletes record in the Login Table at the corresponding LoginID collected
		mysqli_query($con, "DELETE FROM login WHERE LoginID='" . $d['LoginID'] ."'") or (mysqli_error($con));
        $message = "Data deleted";
    }
?>

<head>
<meta charset="utf-8">
<title>Customer</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
#sets navbar at the top of the page
include 'Enitity/menu.php';
?>
<h1 style="text-align:center">Account Details</h1>
   
<?php
#checks if search is selected
if ($_SESSION["Type"] === "Photographer"){ 
	if (isset($_POST['_search'])) {
		#collects all consumers relating to search
        $query = mysqli_query($con, "SELECT * FROM consumer INNER JOIN Login ON consumer.LoginID = login.LoginID WHERE (ConsumerID='".$_POST['search']."' or 
		Firstname LIKE'%".$_POST['search']."%' or Surname LIKE'%".$_POST['search']."%' or PhoneNumber LIKE'%".$_POST['search']."%' 
		or Address LIKE'%".$_POST['search']."%' or Email LIKE'%".$_POST['search']."%' or Username LIKE '%".$_POST['search']."%')")
		or die(mysqli_error($con));
	}
else{
	#collects all consumers
    $query = mysqli_query($con, "SELECT * FROM consumer INNER JOIN Login ON consumer.LoginID = login.LoginID")
   or die(mysqli_error($con));
}
   #creates a search bar
   ?>

<form action="" method="post" align="center" style="background-color:transparent;
	border: solid transparent";>
<div class="input-group">
<input name="search" type="text" placeholder="Type here" style="height: 30px;
    width: 100%; font-size: 16px;">
</div>
<div class="input-group">
<button class="btn" type="submit" name="_search" style="display: block; margin-left: auto;
    margin-right: auto; width: 8em">Search</button>
</div>
</form>

<?php #display the table ?>
<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Firstname</th>
			<th>Surname</th>
			<th>Address</th>
			<th>PhoneNumber</th>
			<th>Email</th>
            <th>Username</th>
			<th colspan="5">Action</th>
		</tr>
	</thead>
	
	<?php 
	#insert the records selected from the consumer table into the displayed table
	while ($row = mysqli_fetch_array($query)) {
		#output data in row array
		echo
  		 "<tr>
			<td>{$row['ConsumerID']}</td>
   		    <td>{$row['Firstname']}</td>
			<td>{$row['Surname']}</td>
			<td>{$row['Address']}</td>
    		<td>{$row['PhoneNumber']}</td>
			<td>{$row['Email']}</td>
			<td>{$row['Username']}</td>";
			#adds an edit button to edit accounts
			?>
			<td>
				<a href="Customer.php?edit=<?php echo $row['ConsumerID']; ?>" class="edit_btn" >Edit</a>
			</td>
			<?php #adds a delete button to delete accounts ?>
			<td>
				<a href="Customer.php?del=<?php echo $row['ConsumerID']; ?>" class="del_btn">Delete</a>
			</td>
		</tr>
	<?php }?>
</table>
<?php }

#redirect users that aren't logged in to the referal page
if(!isset($_SESSION["Username"]))
{ header("Location:Refer.php");}


#shows form only if edit is selected or only one option exists
if (($_SESSION["Type"] === "Consumer") || $update){ ?>
    
	<?php #creates the form?>
	<form name="frmsign" method="post" action="" align="center">
	<?php #displays message?>
	<div class="message"><?php if($message!="") { echo $message; } ?></div>

	<div class="input-group">
		<label>Firstname</label>
		<?php #prefills field with collected Firstname ?>
		<input type="text" name="Firstname" value="<?php echo $Firstname;?>">
	</div>
 
	<div class="input-group">
		<label>Surname</label>
		<?php #prefills field with collected Surname ?>
		<input type="text" name="Surname" value="<?php echo $Surname;?>">
	</div>

	<div class="input-group">
		<label>Address</label>
		<?php #prefills field with collected Address ?>
		<input type="text" name="Address" value="<?php echo $Address;?>">
	</div>

	<div class="input-group">
		<label>Email</label>
		<?php #prefills field with collected Email ?>
		<input type="text" name="Email" value="<?php echo $Email;?>">
	</div>

	<div class="input-group">
		<label>Phone Number</label>
		<?php #prefills field with collected PhoneNumber ?>
		<input type="text" name="PhoneNumber" value="<?php echo $PhoneNumber;?>">
	</div>

	<?php 
	if ($_SESSION["Type"] === "Consumer"){ ?>
		<div>
			<label>Username</label>
			<?php #prefills field with collected Username ?>
			<?php echo $_SESSION["Username"]; ?>
		</div>

		<div class="input-group">
			<label>Old Password</label>
			<input type="Password" name="OPassword">
		</div>

		<div class="input-group">
			<label>New Password</label>
			<input type="Password" name="Password">
		</div>
	<?php }

	else {?>
		<div>
			<label>Username</label>
			<?php #prefills field with collected Username ?>
			<?php echo $n2['Username']; ?>
		</div>
		<?php } ?>

	<div class="input-group">
	<button class="btn" type="submit" name="update" style="background: #556B2F;display: block; margin-left: auto;
		margin-right: auto; width: 5em" >Update</button>
	</div>
	</form>

<?php } ?>

<br><br><br>
</body>
</html>
