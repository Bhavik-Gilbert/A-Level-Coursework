<?php #styling for the graphsheet ?>
<style>
    *{margin: 0px; padding: 0px;}
    canvas{border: 3px solid #303745;background:#c0fafa;margin-top:20px;}
    h2 {color: #000;}
</style>
<?php
#connects page to database
include 'connect.php';
//initialising variable
$counting = 1;
#a loop creating an array for the revenue for each month in the current year
while ($counting < 13){
    #selects all bookings from the current month
    $query = mysqli_query($con, "SELECT Price FROM Booking WHERE MONTH(DATE)='".$counting."' and YEAR(DATE)='".date("Y")."'") or die(mysqli_error($con));
    while ($row = mysqli_fetch_array($query)) {
        #calculates revenue price for the month
        $rev[$counting]=$row["Price"]+$rev[$counting];}
    if($rev[$counting]=="")
        #ensures a 0 value if no revenue is generated in a month
        {$rev[$counting]=0;}
    #goes to next month
    $counting = $counting+1;}

    #creates canvas for graph
?>

<body>
    <h3>Revenue Table</h3>
    <canvas></canvas>

<script>
//sets graph dimentions
canvas=document.querySelector('canvas');
canvas.width=720;
canvas.height=720;

//creates a grid for reference during development
xGrid=10;
yGrid=10;
cellSize=10;
canvas=canvas.getContext('2d');

<?php
#reverts variables in array rev into javascript
echo "var rev1 = '$rev[1]';";
echo "var rev2 = '$rev[2]';";
echo "var rev3 = '$rev[3]';";
echo "var rev4 = '$rev[4]';";
echo "var rev5 = '$rev[5]';";
echo "var rev6 = '$rev[6]';";
echo "var rev7 = '$rev[7]';";
echo "var rev8 = '$rev[8]';";
echo "var rev9 = '$rev[9]';";
echo "var rev10 = '$rev[10]';";
echo "var rev11 = '$rev[11]';";
echo "var rev12 = '$rev[12]';";
?>
//creates a data array to display on the graph (axis)
data={Jan:rev1,
      Feb:rev2,
      Mar:rev3,
      Apr:rev4,
      May:rev5,
      Jun:rev6,
      Jul:rev7,
      Aug:rev8,
      Sep:rev9,
      Oct:rev10,
      Nov:rev11,
      Dec:rev12}

entries=Object.entries(data);


//creates axis and chats
Axis()
graph()

//function to aid with calulations(scale factor)
function Cells(count){
    return count*10
}
//creates axis through vector graphics
function Axis(){
    //starting points
    yTitle=65.2;
    yLine=0
    //draws graph lines
    canvas.beginPath();
    canvas.strokeStyle="black";
    canvas.moveTo(Cells(5),Cells(5));
    canvas.lineTo(Cells(5),Cells(65));
    canvas.lineTo(Cells(70),Cells(65));

    canvas.moveTo(Cells(5),Cells(65));
    //writes y axis values
    for(i=1;i<=13;i++){
        canvas.strokeText(yLine,20,Cells(yTitle));
        //positioning and repositioning
        yTitle=yTitle-4.99;
        yLine=250+yLine;
    }
    canvas.stroke();}

function graph(){
    canvas.beginPath();
    canvas.strokeStyle="black";
    canvas.moveTo(Cells(5),Cells(65));
    //starting points
    xPlot=10
    xTitle=9.75
    //draws graph and writes x axis
    for([xvalue,yvalue] of entries){
        //scaling
        ycells=yvalue/50;
        //writes text to x axis
        canvas.strokeText(xvalue,Cells(xTitle),675)
        //creates plot
        canvas.lineTo(Cells(xPlot),Cells(65-ycells))
        //creates point for easier plot visibility
        canvas.arc(Cells(xPlot),Cells(65-ycells),2,0,Math.PI*2,true);
        //repositioning
        xPlot=5+xPlot
        xTitle=4.97+xTitle
    }
    canvas.stroke();
}
</script>

</body>