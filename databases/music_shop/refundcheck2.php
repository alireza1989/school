<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>AMS Online Music Store</title><meta name="keywords" content="MUSIC STORE, cmpt354 project, Group 14" /><meta name="description" content="Database (cmpt354) project " /><link href="templatemo_style.css" rel="stylesheet" type="text/css" /></head><body><div id="templatemo_wrapper_outer">	<div id="templatemo_wrapper_inner"><div id="templatemo_header"><div id="site_title"> <a href="http://ams.avocadostudio.ca">AMS Music Store<span>Group 14 (CMPT 354)</span></a></div><!-- end of site_title --></div> <!-- end of templatemo_header --><div id="templatemo_menu"><ul><li><a href="http://ams.avocadostudio.ca" class="current">Home</a></li><li><a href="#"> Products</a></li><li><a href="managermenu.html">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="#">Contact</a></li></ul></div> <!-- end of templatemo_menu --><!-- SEARCH BAR--><!-- End of SEARCH BAR--><!-- end of tempaltemo_content --> ';
session_start();

$receiptnum = $_SESSION['receiptnum'];
//echo $receiptnum . "<br>";

$con = mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");
if(mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else{echo "successful connection<br>";}


//link upc with returned amount in this new array
$upcRetqty = array();
foreach($_SESSION['itemupcs'] as $x => $xval){
   if($_POST["retqty" . $x] == null){
      //do nothing
   }else {
      $upcRetqty[$x] = $_POST["retqty" . $x];
   }
}


//booleans for if something should prevent new return  entry from being created
$badretnum = false;
$retdatelimitexceed = false;

//check if any values are negative (print out to check)
foreach($upcRetqty as $y => $yval){
   if($yval <= 0){
      echo "You cannot return 0 or a negative number of items.";
      return;
   }
   //echo $y . ", " . $yval . "<br>";
}

//check for problems with returned amounts (returning more than bought)
//check if items have been returned on this receipt before
$alreadyreturned = "
SELECT PurchasedItem.upc, PurchasedItem.quantity, sold.totalqty
FROM
   PurchasedItem, 
   (SELECT ReturnItem.upc, SUM(ReturnItem.quantity) AS totalqty 
   FROM Returned, ReturnItem 
   WHERE Returned.retid = ReturnItem.retid AND Returned.receiptId = '$receiptnum' GROUP BY ReturnItem.upc) AS sold
WHERE PurchasedItem.upc = sold.upc AND PurchasedItem.receiptId = '$receiptnum'
";

if (mysqli_query($con, $alreadyreturned)) {
    echo "query successful<br>";
} else {
    echo "Error executing " . mysqli_error($con);
}

$alreadyreturnedresult = $con->query($alreadyreturned);

if ($alreadyreturnedresult->num_rows > 0) {
    //echo "Perform complicated stuff <br>";
    echo "<table><tr><th>UPC</th><th>Quantity Bought</th><th>Quantity Returned</th></tr>";
    // output data of each row
    while($row = $alreadyreturnedresult->fetch_assoc()) {
        echo "<tr><td>" . $row["upc"] . "</td><td> " . $row["quantity"] . "</td><td> " . $row["totalqty"] . "</td></tr>";
        $diff = $row["quantity"] - ($row["totalqty"] + $upcRetqty[$row['upc']]);
        //echo $diff . ", ";
        if($diff < 0){
            echo "You can't return more than you bought of " . $row["upc"] . "<br>";
            $badretnum = true;
        }

    }
    echo "</table>";
} else {
    //echo "0 results, perform easier stuff <br>";
    $neverreturned = "SELECT * FROM PurchasedItem WHERE receiptId = '$receiptnum'";

    if (mysqli_query($con, $neverreturned)) {
        //echo "query successful<br>";
    } else {
        echo "Error executing " . mysqli_error($con);
    }

    $neverreturnedresult = $con->query($neverreturned);
    if ($neverreturnedresult->num_rows > 0) {
        echo "<table><tr><th>receiptID</th><th>upc</th><th>Quantity Bought</th></tr>";
        while($row = $neverreturnedresult->fetch_assoc()){
            echo "<tr><td>" . $row["receiptId"] . "</td><td> " . $row["upc"] . "</td><td> " . $row["quantity"] . "</td></tr>";
            if($upcRetqty[$row['upc']] <= $row['quantity']){
                //echo "return number is good";
            }
            if($upcRetqty[$row['upc']] > $row['quantity']){
                echo "return number is bad<br>";
                $badretnum = true;
            }
        }
        echo "</table>";
    }
    else{ echo "no results"; }

}

//echo $badretnum ? 'true' : 'false';

//if there is a problem with the number of items returned, stop the script
if($badretnum){echo "Number of items returned is bad<br><h2>-------------------<br>Refund Aborted<br>-------------------<br></h2>"; return;}


echo "<br>";


//check for problems with date

$todayDate = date("Y-m-d");
$todayDatetime = strtotime($todayDate);


//echo $todayDate . ", " . $todayDatetime;


//Use Receipt id to get Purchase Date
//$purchaseDate;
//$tableName="orders"; // Table name 
$getpurdate = "SELECT date FROM Orders WHERE receiptID = '$receiptnum'";
if(mysqli_query($con, $getpurdate)){
   //echo "<br>found date<br>";
}


$getpurdateresult = $con->query($getpurdate);
if ($getpurdateresult->num_rows > 0) {
    //echo "<table><tr><th>Date</th></tr>";
    while($row = $getpurdateresult->fetch_assoc()){
        //echo "<tr><td>" . $row["date"] . "</td></tr>";
        $purdatetime = strtotime($row['date']); //echo $todayDatetime . " MINUS " . $purdatetime . " EQUALS " . ($todayDatetime - $purdatetime);
        if(($todayDatetime - $purdatetime) > 1296000){//if exceeds number of seconds in 15 days
            $retdatelimitexceed = true;
        }
     }
    //echo "</table>";
}
else{ echo "error, no results for date<br><h2>-------------------<br>Refund Aborted<br>-------------------<br></h2>"; return;}

//echo $retdatelimitexceed;
if($retdatelimitexceed){ echo "Purchase exceeds 15 days, cannot return<br><h2>-------------------<br>Refund Aborted<br>-------------------<br></h2>"; return; }

//echo "TIME TO INSERT VALUES INTO TABLES IF U GOT PAST HERE<br>";

//generate a unique retid that doesnt already exist
$unique = false;
$counter = 10; //limit to 10 random tries
$newretid;

while(($unique == false) && ($counter > 0)){
   if($counter < 10){
      $checknewretidresult->close(); //clear the previous result
   }
   if($counter == 0){
      echo "Could not generate new retid, error<br><h2>-------------------<br>Refund Aborted<br>-------------------<br></h2>";
      return;
   }
   $counter = $counter - 1;
   $newretid = rand(1000000, 9999999); //7 digit integer
   //echo $newretid . "<br>";
   $checknewretid = "SELECT Returned.retid FROM Returned WHERE Returned.retid = '$newretid'";
   if(mysqli_query($con, $checknewretid)){
      //echo "<br>ret query successful<br>";
   }
   if($checknewretidresult->num_rows == null){
      $unique = true;
   }
   //echo $unique ? 'Is unique' : 'Not unique';
}


echo "The generated retid is: " . $newretid . "<br>";
//insert row into Returned table for this date
$SQLInsertReturned = "INSERT INTO Returned (retid, date, receiptId) VALUES ('$newretid', '$todayDate', '$receiptnum')";

if(mysqli_query($con, $SQLInsertReturned)){
   echo "New row in Returned<br>";
}else{echo "Error executing " . mysqli_error($con);}

//insert rows into returnitem table for only the items they want to return (stored in the upcRetqty array)
foreach($upcRetqty as $z => $zval){
   $SQLInsertReturnItem = "INSERT INTO ReturnItem (retid, upc, quantity) VALUES ('$newretid', '$z', '$zval')";
   if(mysqli_query($con, $SQLInsertReturnItem)){
      echo "New row in ReturnItem<br>";
   }else {echo "Error executing " . mysqli_error($con);}
}

//add stock back to the Item table
foreach($upcRetqty as $z => $zval){
   $SQLupdateStock= "UPDATE Item SET stock = stock + $zval WHERE upc = '$z'";
   if(mysqli_query($con, $SQLupdateStock)){
      echo "Stock of " . $z . " updated<br>";
   }else {echo "Error executing " . mysqli_error($con);}
}

//print success
echo "<br><h2>-------------------<br>Refund Success<br>-------------------<br></h2>";


mysqli_close($con);

session_destory();

echo '<div class="cleaner"></div></div> <!-- end of content_wrapper --></div> <!-- end of templatemo_wrapper_inner --></div> <!-- templatemo_wrapper_outer --><div id="templatemo_footer_outer"><div id="templatemo_footer_inner"><div id="templatemo_footer"><div class="section_w180px"><h3>Main Menu</h3><ul class="footer_menu_list"><li><a href="http://ams.avocadostudio.ca" target="_blank">Main Page</a></li><li><a href="http://ams.avocadostudio.ca" target="_blank">Products</a></li><li><a href="managermenu.html" target="_blank">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="http://ams.avocadostudio.ca">Contact Us</a></li></ul></div><div class="section_w180px"><h3>Related Links</h3><ul class="footer_menu_list"><li><a href="http://www.sfu.ca">SFU Page</a></li><li><a href="https://www.w3schools.org">W3 Schools</a></li><li><a href="https://www.music.com">Music.com</a></li></ul></div><div class="section_w180px"><h3>W3C Validators</h3><a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a><div class="margin_bottom_10"></div><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a></div><div class="margin_bottom_20"></div><div class="margin_bottom_20"></div>Copyright © 2015 <a href="#">AMS MUSIC STORE</a> | <a href="http://ams.avocadostudio.ca" target="_parent">AMS</a> by <a href="http://ams.avocadostudio.ca" target="_parent">CMPT 354 Project</a></div> <!-- end of footer --></div> <!-- end of footer wrapper --></div></body></html>';
?>