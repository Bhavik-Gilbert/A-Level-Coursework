<?php session_start();
    #connects page to database
	include 'Enitity/connect.php';
    #initialises variables
	$ID = $_SESSION['ID'];
    $id = 0;
    $Reference = "";
    $message ="";
    $update = "false";
        
    #checks if pay is selected
    if(isset($_GET['pay'])){ 
        #gets BookingID
        $Reference = $_GET['pay'];
        $_SESSION["Reference"] = $Reference;
        #sets payment button
        $update="true";}
    
    #checks if del is selected
    if(isset($_GET['del'])){ 
        #gets CardID
        $CardID = $_GET['del'];
        #Delete record from Card at CardID
        mysqli_query($con, "DELETE FROM card WHERE CardID='" . $CardID ."'") or (mysqli_error($con));
		$message = "Card deleted";}
    
    #checks if purchase is selected
    if(isset($_GET['purchase'])){
        #gets CardID and BookingID
        $CardID = $_GET['purchase'];
        $Reference = $_SESSION["Reference"];
        $hold3 = "yes";
        $Order = date("Y/m/d");
        #updates booking at BookingID
        mysqli_query($con, "INSERT INTO orders (BookingID, CardID , OrderDate) 
        VALUES ('$Reference','$CardID','$Order')") or die (mysqli_error($con));
        $sql = mysqli_query($con, "UPDATE booking SET Paid='".$hold3."' WHERE BookingID='".$Reference."'") or die(mysqli_error($con));
        #selects record from Booking at corresponding BookingID
        $record = mysqli_query($con, "SELECT * FROM booking WHERE BookingID='" . $Reference ."'");
        $n = mysqli_fetch_array($record);
        #assigns data collected
        $ShootType = $n['ShootTypeID'];
        $PackageType = $n['PackageID'];
        $Date = $n['Date'];
        $ShootLocation = $n['ShootLocation'];
        $StartTime = $n['StartTime'];
        $Length = $n['Length'];
        $Price = $n['Price'];
        #assigns data collected for Invoice
        $_SESSION["PackageID"] = $PackageType;
        $_SESSION["ShootID"] = $ShootType;
        $_SESSION["Date"] = $Date;
        $_SESSION["Address"] = $ShootLocation;
        $_SESSION["StartTime"] = $StartTime;
        $_SESSION["Length"] = $Length;
        $_SESSION["Price"] = $Price;
        $message = "Purchase Successful";
        #Clears reference
        $Reference = "";
        $_SESSION["Reference"] = "";
        #Directs to invoice page
        header("Location:Invoice.php");
    } 
    
    #checks if Add is selected
    if (isset($_POST['Add'])) {
        if ($Reference!="") {
            #assigns data collected from form
            $Owner = $_POST['Name'];
            $CardNumber = $_POST['Card'];
            #Remove any spaces from the card number
            $CardNumber = str_replace (' ', '', $CardNumber);
            $ExpiryMonth = $_POST['Month'];
            $ExpiryYear = $_POST['Year'];
            $CVV = $_POST['Code'];
            $Address = $_POST['Address'];
            $Type = $_POST['Type'];
            $Expiry = $ExpiryMonth."/".$ExpiryYear;
            #performs check on the 16 digit card number
            $verified = CardCheck($CardNumber,$Type); 
            #validates form inputs
            if (empty($CardNumber) || empty($ExpiryMonth) || empty($ExpiryYear) || empty($CVV) || empty($Owner) || empty($Address) || empty($Type)) {
                $message = "Please fill in all of the fields";
            } 
            elseif (is_numeric($Owner)) {
                $message = "Invalid card owner name";
            } 
            elseif (!$verified){
                $message = "Invalid 16 digit card number";
            } 
            elseif ((!is_numeric($CVV)) || (mb_strlen($CVV)!=3)) {
                $message = "Invalid CVV";
            }
             else {
                #sets ConsumerID
                if ($_SESSION["Type"] == "Consumer") {
                    $ID = $_SESSION["ID"];
                }
                if ($_SESSION["Type"] == "Photographer"){
                    $ID=38;
                }
                    #Encrypts card value
                    $cardencrypt = openssl_encrypt($CardNumber, "AES-128-CTR", "GeeksforGeeks" , 0, '1234567891011121'); 
                #Inserts new card into the Card Table                    
                $sql = mysqli_query($con, "INSERT INTO card (CardHolder, 16digit , CVV , Expiry , ConsumerID, CardAddress, CardType) 
                VALUES ('$Owner','$cardencrypt','$CVV','$Expiry','$ID','$Address','$Type')") or die (mysqli_error($con));
                $message="Card Added";}}
        else{$message = "Please choose a booking to pay for";}}


    function CardCheck ($cardnumber, $cardname) { 
        $cards = array (
            array ('name' => 'Mastercard', 
                  'length' => '16', 
                  'prefixes' => '51,52,53,54,55',
                  'checkdigit' => true
                   ),
            array ('name' => 'Visa', 
                  'length' => '16', 
                  'prefixes' => '4',
                  'checkdigit' => true
                   )
            );          
        #Find card type
        $cardType = -1;
        for ($i=0; $i<sizeof($cards); $i++) {
             // Check card type against array
             if (strtolower($cardname) == strtolower($cards[$i]['name'])) {
              $cardType = $i;
             break;
            }
        }

        #End check if card type not supported
        #End check if no card number is entered
        #Checks the card is numeric and a valid length
        if (($cardType == -1) || (strlen($cardnumber) == 0) || (!preg_match("/^[0-9]{13,19}$/",$cardnumber))) {
            return false;
            }

        #runs mod 10 check for valid card number
        if ($cards[$cardType]['checkdigit']) {
            $checksum = 0;                                    # next char to process
            $j = 1;                                         # takes value of 1 or 2
            #Processing each digit
            for ($i = strlen($cardnumber) - 1; $i >= 0; $i--) {
                #Get the next digit and multiply by 1 or 2 on alternative digits      
                $calc = $cardnumber[$i] * $j;
                #If the result is in two digits add 1 to the checksum total
                if ($calc > 9) {
                    $checksum = $checksum + 1;
                    $calc = $calc - 10;
                }
                # Add the units element to the checksum total
                $checksum = $checksum + $calc;
                #Switch the value of j
                if ($j ==1) {$j = 2;} 
                else {$j = 1;}
            } 
            #Report error is mod 10 is not 0
            if ($checksum % 10 != 0) {
                return false; 
            }
        }  

        #Load array for prefix
        $prefix = explode(',',$cards[$cardType]['prefixes']);
        #Check prefix against valid prefixes 
        $PrefixValid = false; 
        for ($i=0; $i<sizeof($prefix); $i++) {
            $exp = '/^' . $prefix[$i] . '/';
            if (preg_match($exp,$cardnumber)) {
            $PrefixValid = true;
            break;
            }
        }
            
        #End check in the case of an invalid prefix
        if (!$PrefixValid) {
            return false; 
        }
            
        #Check card number length validity
        $LengthValid = false;
        $lengths = explode(',',$cards[$cardType]['length']);
        for ($j=0; $j<sizeof($lengths); $j++) {
            if (strlen($cardnumber) == $lengths[$j]) {
            $LengthValid = true;
            break;
            }
        }
        
        #End check in the case of an invalid length
        if (!$LengthValid) {
            return false; 
        }   
        
        #Return true if all checks are cleared
        return true;
        }      

?>

<head>
<meta charset="utf-8">
<title>Payment</title>
<link rel="stylesheet" type = "text/css" href="CSS/Style.css">
<link rel="stylesheet" type = "text/css" href="CSS/table.css">
</head>

<body>
<?php
#sets navbar at the top of the page
include 'Enitity/menu.php';
?>
<h1 style="text-align:center">Payment Details</h1>

<?php
$hold1 = "no";
$hold2 = "Confirmed";
if ($_SESSION["Type"] === "Photographer") {
    #checks if search is selected
    if (isset($_POST['_search'])) {
        #collects from ShootType where record is like search
        $select1 = mysqli_query($con, "SELECT * FROM shoottype WHERE Type LIKE '%".$_POST['search']."%'")
        or die(mysqli_error($con));
        $Shoot = mysqli_fetch_array($select1);

        #collects from Package where record is like search
        $select2 = mysqli_query($con, "SELECT * FROM package WHERE Type LIKE '%".$_POST['search']."%'")
        or die(mysqli_error($con));
        $Package = mysqli_fetch_array($select2);

        #collects from Booking where record is like search
        $query = mysqli_query($con, "SELECT * FROM booking WHERE (Date LIKE'%".$_POST['search']."%' or 
		ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%' 
		or ShootTypeID='".$Shoot['ShootTypeID']."' or PackageID='".$Package['PackageID']."') AND (Paid='".$hold1."' AND Status='".$hold2."')")
        or die(mysqli_error($con));
    } else {
        #selects all from bookings
        $query = mysqli_query($con, "SELECT * FROM booking WHERE Paid='".$hold1."' AND Status='".$hold2."'") or die(mysqli_error($con));
    }
}

else{
    #checks if search is selected
    if (isset($_POST['_search'])) {
        #collects from ShootType where record is like search
        $select1 = mysqli_query($con, "SELECT * FROM shoottype WHERE Type LIKE '%".$_POST['search']."%'")
        or die(mysqli_error($con));
        $Shoot = mysqli_fetch_array($select1);

        #collects from Package where record is like search
        $select2 = mysqli_query($con, "SELECT * FROM package WHERE Type LIKE '%".$_POST['search']."%'")
        or die(mysqli_error($con));
        $Package = mysqli_fetch_array($select2);

        #collects from Booking where record is like search at ConsumerID
        $query = mysqli_query($con, "SELECT * FROM booking WHERE (Paid='".$hold1."' AND Status='".$hold2."' AND ConsumerID='".$_SESSION['ID']."') and (Date LIKE'%".$_POST['search']."%' or 
		ShootLocation LIKE'%".$_POST['search']."%' or StartTime LIKE'%".$_POST['search']."%' or Price LIKE'%".$_POST['search']."%'
		or ShootTypeID='".$Shoot['ShootTypeID']."' or PackageID='".$Package['PackageID']."')")
        or die(mysqli_error($con));
    } else {
        #selects all from bookings at ConsumerID
        $query = mysqli_query($con, "SELECT * FROM booking WHERE Paid='".$hold1."' AND Status='".$hold2."' AND ConsumerID='".$_SESSION['ID']."'") or die(mysqli_error($con));
    }
} 

#redirect users that aren't logged in to the referal page
if(!isset($_SESSION["Username"]))
{ header("Location:Refer.php");}
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
        <th>Reference</th>
			<th>Date</th>
			<th>Shoot Location</th>
			<th>StartTime</th>
			<th>Length</th>
            <th>Shoot Type</th>
            <th>Package</th>
            <th>Price Status</th>
            <th>Paid</th>
            <th>Price</th>
            <th colspan="5">Action</th>
		</tr>
	</thead>
	
	<?php 
    #insert the records selected from the Booking table into the displayed table
	while ($row = mysqli_fetch_array($query)) {
        #gets name of the shoottype at ShootTypeID in ShootType Table
		$collect1 = mysqli_query($con, "SELECT * FROM shoottype WHERE ShootTypeID='".$row["ShootTypeID"]."'") or die(mysqli_error($con));
		$Shootings = mysqli_fetch_array($collect1);
		#gets name of the package at PackageID in Package Table
        $collect2 = mysqli_query($con, "SELECT * FROM package WHERE PackageID='".$row['PackageID']."'") or die(mysqli_error($con));
		$Packagings = mysqli_fetch_array($collect2);
        #output data in row array
		echo
        "<tr>
        <td>{$row['BookingID']}</td>
        <td>{$row['Date']}</td>
        <td>{$row['ShootLocation']}</td>
        <td>{$row['StartTime']}</td>
        <td>{$row['Length']}</td>
        <td>{$Shootings['Type']}</td>
        <td>{$Packagings['Type']}</td>
        <td>{$row['Status']}</td>
        <td>{$row['Paid']}</td>
        <td>{$row['Price']}</td>";
        #adds a pay button to edit bookings
            ?>
        <td>
				<a href="payment.php?pay=<?php echo $row['BookingID']; ?>" class="edit_btn">Pay</a>
			</td>
		</tr>
	<?php }?>
</table>

<?php 
#selects cards at ConsumerID
    if ($_SESSION["Type"] == "Consumer") {
        $query2 = mysqli_query($con, "SELECT * FROM card WHERE ConsumerID='".$_SESSION['ID']."'") or die(mysqli_error($con));
    }
    else{$query2 = mysqli_query($con, "SELECT * FROM card WHERE ConsumerID='38'") or die(mysqli_error($con));}
    #display the table 
    ?>

<table>
<thead>
    <tr>
    <th>CardHolder</th>
    <th>Card Number</th>
    <th>CVV</th>
    <th>Expiry Date</th>
    <th>Card Type</th>
    <th colspan="5">Action</th>
    </tr>
</thead>

<?php
#insert the records selected from the Card table into the displayed table
while ($row2 = mysqli_fetch_array($query2)) {
    #decrypts card number from table
    $carddecrypt = openssl_decrypt ($row2['16digit'], "AES-128-CTR", "GeeksforGeeks", 0 , '1234567891011121');
    echo
    "<tr>
    <td>{$row2['CardHolder']}</td>
    <td>{$carddecrypt}</td>
    <td>{$row2['CVV']}</td>
    <td>{$row2['Expiry']}</td>
    <td>{$row2['CardType']}</td>";
    if ($update == "true") {
        #adds a payment button to edit bookings
        ?>
         <td>
            <a href="payment.php?purchase=<?php echo $row2['CardID']; ?>" class="edit_btn">Purchase</a>
        </td>
    <?php
    }
    #adds a delete button to delete bookings
    ?>
        <td>
            <a href="payment.php?del=<?php echo $row2['CardID']; ?>" class="del_btn">Delete</a>
        </td>
    </tr>
<?php }?>
</table>

<?php
#shows form only if edit is selected
if ($update == "true"){ 
    #creates the form ?>
    <form name="frmsign" method="post" action="" align="center">
    <?php #displays message?>
    <div class="message"><?php if($message!="") { echo $message; } ?></div>

    <div>
        <label>Reference Number</label>
        <br>
        <?php #prefills field with collected BookingID ?>
        <?php echo $Reference; ?>
    </div>

    <div class="input-group">
        <label>Card Owner</label>
        <input type="text" name="Name">
    </div>

    <div class="input-group">
        <label>Address</label>
        <input type="text" name="Address">
    </div>

    <div class="input-group">
        <label>Card Number</label>
        <input type="text" name="Card">
    </div>
    
    <div class="input-group">
        <label>Expiry Date</label>
        <select name="Month">
        <option value="">Select...</option>
        <option value="01">January</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
        </select>
        <select name="Year">
        <option value="">Select...</option>
        <option value="2020">2020</option>
        <option value="2021">2021</option>
        <option value="2022">2022</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
        <option value="2026">2026</option>
        <option value="27">2027</option>
        <option value="28">2028</option>
        <option value="29">2029</option>
        <option value="30">2030</option>
        <option value="31">2031</option>
        </select>
    </div>

    <div class="input-group">
        <label>CVV</label>
        <input type="text" name="Code">
    </div>

    <div class="input-group">
        <label>Card Type</label>
        <select name="Type">
        <option value="">Select...</option>
        <option value="Visa">Visa</option>
        <option value="Mastercard">Matsercard</option>
        </select>
    </div>

    <div class="input-group">
        <button class="btn" type="submit" name="Add" style="background: #556B2F;display: block; margin-left: auto;
            margin-right: auto; width: 5em" >Add Card</button>
    </div>
    </form>

<?php } ?>

<br><br><br>
</body>
</html>

