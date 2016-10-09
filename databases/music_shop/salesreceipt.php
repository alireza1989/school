<?php

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>AMS Online Music Store</title><meta name="keywords" content="MUSIC STORE, cmpt354 project, Group 14" /><meta name="description" content="Database (cmpt354) project " /><link href="templatemo_style.css" rel="stylesheet" type="text/css" /></head><body><div id="templatemo_wrapper_outer"><div id="templatemo_wrapper_inner"><div id="templatemo_header"><div id="site_title"><a href="http://ams.avocadostudio.ca">AMS Music Store<span>Group 14 (CMPT 354)</span></a></div><!-- end of site_title --></div> <!-- end of templatemo_header --><div id="templatemo_menu"><ul><li><a href="http://ams.avocadostudio.ca" class="current">Home</a></li><li><a href="product.php"> Products</a></li><li><a href="managermenu.html">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact</a></li></ul></div> <!-- end of templatemo_menu --><!-- SEARCH BAR--><form name="formSearch" action="search.php" method="POST"><select name="search" type="select-one"><option value="">Select Search Criteria</option><option value="default">Default Search</option><option value="category">Search by Category</option><option value="title">Search by Title</option><option value="singer">Search by Lead Singer</option></select> <input name="searchCriteria" id="searchCriteria" type="text" /><input name="Search" type="submit" value="Search" /></form><!-- End of SEARCH BAR-->';



session_start();

$servername = "167.88.125.113";
$username = "ams_user14";
$password = "Cmpt354@123";
$dbname = "ams_g14";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

/*
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully<br>";
*/

$generatedrid = $_SESSION['rid'];


echo "<h2>RECEIPT<br><h2>";
echo "<br>";

/*
if (mysqli_query($conn, $getallitems)) {
    echo "query successful<br>";
} else {
    echo "Error executing " . mysqli_error($conn);
}
*/


$getreceipt = "
SELECT ord.receiptId, ord.date, ord.cid, ord.cardnumber, ord.expiryDate, ord.expectedDate , temp.bill 
FROM 

   Orders ord, 

   (SELECT SUM(Item.price*PurchasedItem.quantity) AS bill
   FROM PurchasedItem, Item
   WHERE PurchasedItem.receiptId = '$generatedrid' AND Item.upc = PurchasedItem.upc) AS temp

WHERE receiptId = '$generatedrid'


";
//echo $getreceipt;

/*
if (mysqli_query($conn, $getreceipt)) {
    echo "query successful<br>";
} else {
    echo "Error executing " . mysqli_error($conn);
}
*/

$getreceiptresult = $conn->query($getreceipt);


if ($getreceiptresult->num_rows > 0) {
    echo "<table class = 'report' ><tr><th>Receipt ID</th><th>Date</th><th>User</th><th>Card</th><th>Expiry Date</th><th>Expected Date of Delivery</th><th>Total Cost</th></tr>";
    // output data of each row
    while($row = $getreceiptresult->fetch_assoc()) {
        echo "<tr><td>" . $row["receiptId"] . "</td><td> " . $row["date"] . "</td><td> " . $row["cid"] .  "</td><td> " . $row["cardnumber"] . "</td><td> " . $row["expiryDate"] . "</td><td> " . $row["expectedDate"] . "</td><td> " . $row["bill"] ."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}


$getallitems = "
SELECT Item.title, temp.quantity, (Item.price*temp.quantity) as subtotal
FROM 

   Item,
   (SELECT PurchasedItem.upc, PurchasedItem.quantity
   FROM Item, PurchasedItem
   WHERE Item.upc = PurchasedItem.upc AND receiptId = '$generatedrid') AS temp

WHERE temp.upc = Item.upc

";

$getallitemsresult = $conn->query($getallitems);

echo "<br>";

if ($getallitemsresult->num_rows > 0) {
    echo "<table class = 'report'><tr><th>Title</th><th>Quantity</th><th>Cost</th></tr>";
    // output data of each row
    while($row = $getallitemsresult->fetch_assoc()) {
        echo "<tr><td>" . $row["title"] . "</td><td> " . $row["quantity"] . "</td><td> " . $row["subtotal"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}




mysqli_close($conn);






echo '<!-- end of tempaltemo_content --><div class="cleaner"></div></div> <!-- end of content_wrapper --></div> <!-- end of templatemo_wrapper_inner --></div> <!-- templatemo_wrapper_outer --><div id="templatemo_footer_outer"><div id="templatemo_footer_inner"><div id="templatemo_footer"><div class="section_w180px"><h3>Main Menu</h3><ul class="footer_menu_list"><li><a href="http://ams.avocadostudio.ca" target="_blank">Main Page</a></li><li><a href="product.html" target="_blank">Products</a></li><li><a href="managermenu.html" target="_blank">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact Us</a></li></ul></div><div class="section_w180px"><h3>Related Links</h3><ul class="footer_menu_list"><li><a href="http://www.sfu.ca">SFU Page</a></li><li><a href="https://www.w3schools.org">W3 Schools</a></li><li><a href="https://www.music.com">Music.com</a></li></ul></div><div class="section_w180px"><h3>W3C Validators</h3><a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a><div class="margin_bottom_10"></div><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a></div><div class="margin_bottom_20"></div><div class="margin_bottom_20"></div>Copyright © 2015 <a href="#">AMS MUSIC STORE</a> | <a href="http://ams.avocadostudio.ca" target="_parent">AMS</a> by <a href="http://ams.avocadostudio.ca" target="_parent">CMPT 354 Project</a></div> <!-- end of footer --></div> <!-- end of footer wrapper --></div></body></html>';

?>