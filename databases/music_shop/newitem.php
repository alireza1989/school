<?php

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>AMS Online Music Store</title><meta name="keywords" content="MUSIC STORE, cmpt354 project, Group 14" /><meta name="description" content="Database (cmpt354) project " /><link href="templatemo_style.css" rel="stylesheet" type="text/css" /></head><body><div id="templatemo_wrapper_outer"><div id="templatemo_wrapper_inner"><div id="templatemo_header"><div id="site_title"><a href="http://ams.avocadostudio.ca">AMS Music Store<span>Group 14 (CMPT 354)</span></a></div><!-- end of site_title --></div> <!-- end of templatemo_header --><div id="templatemo_menu"><ul><li><a href="http://ams.avocadostudio.ca" class="current">Home</a></li><li><a href="product.php"> Products</a></li><li><a href="managermenu.html">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact</a></li></ul></div> <!-- end of templatemo_menu --><!-- SEARCH BAR--><form name="formSearch" action="search.php" method="POST"><select name="search" type="select-one"><option value="">Select Search Criteria</option><option value="default">Default Search</option><option value="category">Search by Category</option><option value="title">Search by Title</option><option value="singer">Search by Lead Singer</option></select> <input name="searchCriteria" id="searchCriteria" type="text" /><input name="Search" type="submit" value="Search" /></form><!-- End of SEARCH BAR-->';

if(strlen($_POST['upcname'])==0 or strlen($_POST['stockname'])==0 or $_POST['songnumbername']<1 or $_POST['songnumbername']==NULL  )
{header('location: newitem.html');}

$con=mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");


if (mysqli_connect_errno())
{
     echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$upc = $_POST['upcname'];
$title = $_POST['titlename'];
$singer = $_POST['singername'];
$type = $_POST['typename'];
$category = $_POST['categoryname'];
$songnumber = $_POST['songnumbername'];
$company = $_POST['companyname'];
$year = $_POST['yearname'];
$price = $_POST['pricename'];
$stock = $_POST['stockname'];

$temp1 = "SELECT upc FROM Item WHERE upc = $upc";
$result = mysqli_query( $con, $temp1);
$temp = mysqli_num_rows($result);

//checks if item already exists
if($temp >0)
{
//redirects
header('location: newitem.html');
}
//if item does not exist, add it into the database
$sql1 = "INSERT INTO Item(upc,title,type,category,company,year,price,stock) VALUES ($upc,'$title','$type','$category','$company','$year','$price',$stock)";
     mysqli_query($con, $sql1);
$sql2 = "INSERT INTO LeadSinger(upc,name) VALUES($upc,'$singer')";
     mysqli_query($con, $sql2);


mysqli_close($con);

$i=1;
echo "<table>";
echo"<form name = 'random' method = 'POST' action = addsongs.php>";
for($i;$i<($songnumber+1);$i++)
{
echo "<tr><td><label>
Song #$i: </td><td><input name='".$i."' type='text' id='".$i."'></td></tr>";
}
echo "</table><button type='submit' name='selectall' value='".$upc." ".$songnumber."'>Submit Songs</button></form>";

echo '<!-- end of tempaltemo_content --><div class="cleaner"></div></div> <!-- end of content_wrapper --></div> <!-- end of templatemo_wrapper_inner --></div> <!-- templatemo_wrapper_outer --><div id="templatemo_footer_outer"><div id="templatemo_footer_inner"><div id="templatemo_footer"><div class="section_w180px"><h3>Main Menu</h3><ul class="footer_menu_list"><li><a href="http://ams.avocadostudio.ca" target="_blank">Main Page</a></li><li><a href="product.html" target="_blank">Products</a></li><li><a href="managermenu.html" target="_blank">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact Us</a></li></ul></div><div class="section_w180px"><h3>Related Links</h3><ul class="footer_menu_list"><li><a href="http://www.sfu.ca">SFU Page</a></li><li><a href="https://www.w3schools.org">W3 Schools</a></li><li><a href="https://www.music.com">Music.com</a></li></ul></div><div class="section_w180px"><h3>W3C Validators</h3><a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a><div class="margin_bottom_10"></div><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a></div><div class="margin_bottom_20"></div><div class="margin_bottom_20"></div>Copyright © 2015 <a href="#">AMS MUSIC STORE</a> | <a href="http://ams.avocadostudio.ca" target="_parent">AMS</a> by <a href="http://ams.avocadostudio.ca" target="_parent">CMPT 354 Project</a></div> <!-- end of footer --></div> <!-- end of footer wrapper --></div></body></html>';
?>