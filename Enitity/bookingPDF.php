<?php 
require_once __DIR__ . '/../../PDF/vendor/autoload.php';

$selection1 = mysqli_query($con, "SELECT * FROM booking
INNER JOIN shoottype ON booking.ShootTypeID = shoottype.ShootTypeID
INNER JOIN consumer ON booking.ConsumerID = consumer.ConsumerID
WHERE BookingID='".$booking."'") or die(mysqli_error($con));

$selection2 = mysqli_query($con, "SELECT * FROM booking
INNER JOIN package ON booking.PackageID = package.PackageID
INNER JOIN photographer ON booking.PhotographerID = photographer.PhotographerID
WHERE BookingID='".$booking."'") or die(mysqli_error($con));

$values1  = mysqli_fetch_array($selection1);
$values2  = mysqli_fetch_array($selection2);

//assign variables
$date = date("Y/m/d");
$time = date("h:i:sa");
//booking
$shootDate = $values1['Date'];
$location = $values1['shootLocation'];
$start = $values1['StartTime'];
$length = $values1['Length'];
$price = $values1['Price'];
$status = $values1['Status'];

$paid = $values1['Paid'];

//package
$packageType = $values2['Type'];
$packageCost = $values2['AddedCost'];

//shoottype
$shootType = $values1['Type'];
$shootTypeCost = $values1['BasePrice'];
$eventType = $values1['PackageType'];

//consumer
$cName = $values1['Firstname'] . " " . $values1['Surname'];
$cEmail = $values1['Email'];
$cPhoneNumber = $values1['PhoneNumber'];

//photographer
$pName = $values2['Firstname'] . " " . $values2['Surname'];
$pEmail = $values2['Email'];
$pPhoneNumber = $values2['PhoneNumber'];


if($paid == "yes"){
    $cardselection = mysqli_query($con, "SELECT * FROM orders
        INNER JOIN card ON orders.CardID = card.CardID
        WHERE BookingID='".$booking."'" ) or die(mysqli_error($con));
    $card  = mysqli_fetch_array($cardselection);
    $cardType = $card['CardType'];
    $cardNumber = substr(openssl_decrypt ($card['16digit'], "AES-128-CTR", "GeeksforGeeks", 0 , '1234567891011121'), -4);
}

$mpdf = new \Mpdf\Mpdf();

$body = "";

$body .= 
"<style>
p {color: #222;font-size: 12x;}
</style>";

//header
$body .= "<h1>Booking Receipt</h1>";
$body .= "<h2>Booking Reference : " . $booking . " </h2> <br />";

//body
//purcahse details
$body .= "<h3>Purchase</h3>";
if($paid == "yes")
{
    $body .= "<p> Transaction Type : " . $cardType ."</p>";
    $body .= "<p> Ending in " . $cardNumber ."</p>";
}
else
{
    $body .= "<h4>Purchase Not Yet Made</h4>";
}

$body .= "<br>";
$body .= "<p> Purchase by : " . $cName ."</p>";
$body .= "<p> Email : " . $cEmail ."</p>";
$body .= "<p> Phone Number : " . $cPhoneNumber . " </p>"; 
$body .= "<h6>Please Ensure Your Contact Details Are Kept Up To Date</h6>";

$body .= "<br>";
$body .= "<p> Photographer : " . $pName ."</p>";
$body .= "<p> Email : " . $pEmail ."</p>";
$body .= "<p> Phone Number : " . $pPhoneNumber . " </p>"; 
$body .= "<h6>If You Have Any Issues On the Day, Contact Your Photographer Directly</h6>";

$body .= "<br>";

//booking
$body .= "<h3>Booking Details </h3>";
$body .= "<p> Date : " . $shootDate ."</p>";
$body .= "<p> Address : " . $location ."</p>";
$body .= "<p> Start Time : " . $start ."</p>";
$body .= "<p> Time Period : " . $length ."hrs</p>";
$body .= "<p> Booking Status : " . $status ." </p>";

$body .= "<br>";
$body .= "<h3>Package Details </h3>";
$body .= "<p> Package : " . $packageType ." </p>";
$body .= "<p> Price : £" . $packageCost ." </p>";

$body .= "<br>";
$body .= "<h3>Shoot Type Details </h3>";
$body .= "<p> Shoot Type : " . $shootType ." </p>";
$body .= "<p> Price : £" . $shootTypeCost ." </p>";
$body .= "<p> Event Type : " . $eventType ." </p>";


$body .= "<br>";
$body .= "<h4> Total : £" . $price ." </h4>";

//footer
$body .= "<br>";
$body .= "<h6>Print Date : " . $date . "</h6>";
$body .= "<h6>Print Time : " . $time . "</h6>";

//write pdf
$mpdf->WriteHTML($body);

//output
$filename = "Receipt " . $booking . ".pdf";
$mpdf->Output($filename, 'D')

?>