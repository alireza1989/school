<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>AMS Online Music Store</title><meta name="keywords" content="MUSIC STORE, cmpt354 project, Group 14" /><meta name="description" content="Database (cmpt354) project " /><link href="templatemo_style.css" rel="stylesheet" type="text/css" /></head><body><div id="templatemo_wrapper_outer"><div id="templatemo_wrapper_inner"><div id="templatemo_header"><div id="site_title"><a href="http://ams.avocadostudio.ca">AMS Music Store<span>Group 14 (CMPT 354)</span></a></div><!-- end of site_title --></div> <!-- end of templatemo_header --><div id="templatemo_menu"><ul><li><a href="http://ams.avocadostudio.ca" class="current">Home</a></li><li><a href="product.php"> Products</a></li><li><a href="managermenu.html">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact</a></li></ul></div> <!-- end of templatemo_menu --><!-- SEARCH BAR--><form name="formSearch" action="search.php" method="POST"><select name="search" type="select-one"><option value="">Select Search Criteria</option><option value="default">Default Search</option><option value="category">Search by Category</option><option value="title">Search by Title</option><option value="singer">Search by Lead Singer</option></select> <input name="searchCriteria" id="searchCriteria" type="text" /><input name="Search" type="submit" value="Search" /></form><!-- End of SEARCH BAR-->';
	session_start();
	$cid = $_SESSION['username'];
	$ccnumber = $_POST['cardnum'];
	$ccexp = $_POST['cardexp'];
	$date = date("Y-m-d");
	$ordersperday = 3;

	$con = mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");

	$check = mysqli_query($con,"SELECT Item.stock FROM Item INNER JOIN Basket ON Basket.cid = '$cid' AND Item.upc = Basket.upc WHERE Basket.qty > Item.stock");
	$numrows = mysqli_num_rows($check);
	if($numrows>0)
	{
		header('location: viewCart.php');
	} else {

		$temp1 = mysqli_query($con, "SELECT count(receiptId) as number FROM Orders WHERE deliveredDate is NULL or deliveredDate = '0000-00-00'");
		$orders = mysqli_fetch_assoc($temp1);
		$temp2 = ceil($orders['number']/$ordersperday);
		$edate = date('Y-m-d', strtotime($date. ' + '.$temp2.' days'));
		echo $edate;

		$sql = "SELECT * FROM Basket WHERE cid = '$cid'";
		$result = mysqli_query($con, $sql);

			$result2 = mysqli_query($con, "SELECT receiptId FROM PurchasedItem GROUP BY receiptId");
			$id = 0;
			while($temp = mysqli_fetch_assoc($result2)){
				if($id+1 != $temp['receiptId']){
					$id++;
					break;
				} else {
					$id = $temp['receiptId'];
				}
			}
			$sql3 = "INSERT INTO Orders(receiptId, date, cid, cardnumber, expiryDate, expectedDate) VALUES(".$id.",'".$date."','".$cid."',".$ccnumber.",'".$ccexp."','".$edate."')";
		echo "<br>".$sql3;
			mysqli_query($con, $sql3);

		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
		echo $id;
				$sql1 =  "INSERT INTO PurchasedItem(receiptId,upc,quantity) VALUES(".$id.",".$row['upc'].",".$row['qty'].")";
		echo "<br>".$sql1;
				mysqli_query($con, $sql1);
				$sql2 = "UPDATE Item SET stock = stock - ".$row['qty']." WHERE upc = ".$row['upc'];
		echo "<br>".$sql2;
				mysqli_query($con, $sql2);
			}
		}
		$drop = "DELETE FROM Basket WHERE cid = '$cid'";
		mysqli_query($con, $drop);
		$_SESSION['rid'] = $id;
		header('location: salesreceipt.php');
	}
echo '<!-- end of tempaltemo_content --><div class="cleaner"></div></div> <!-- end of content_wrapper --></div> <!-- end of templatemo_wrapper_inner --></div> <!-- templatemo_wrapper_outer --><div id="templatemo_footer_outer"><div id="templatemo_footer_inner"><div id="templatemo_footer"><div class="section_w180px"><h3>Main Menu</h3><ul class="footer_menu_list"><li><a href="http://ams.avocadostudio.ca" target="_blank">Main Page</a></li><li><a href="product.html" target="_blank">Products</a></li><li><a href="managermenu.html" target="_blank">Manager</a></li><li><a href="clerk.html">Clerk</a></li><li><a href="contact.html">Contact Us</a></li></ul></div><div class="section_w180px"><h3>Related Links</h3><ul class="footer_menu_list"><li><a href="http://www.sfu.ca">SFU Page</a></li><li><a href="https://www.w3schools.org">W3 Schools</a></li><li><a href="https://www.music.com">Music.com</a></li></ul></div><div class="section_w180px"><h3>W3C Validators</h3><a href="http://validator.w3.org/check?uri=referer"><img style="border:0;width:88px;height:31px" src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Transitional" width="88" height="31" vspace="8" border="0" /></a><div class="margin_bottom_10"></div><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px"  src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" vspace="8" border="0" /></a></div><div class="margin_bottom_20"></div><div class="margin_bottom_20"></div>Copyright © 2015 <a href="#">AMS MUSIC STORE</a> | <a href="http://ams.avocadostudio.ca" target="_parent">AMS</a> by <a href="http://ams.avocadostudio.ca" target="_parent">CMPT 354 Project</a></div> <!-- end of footer --></div> <!-- end of footer wrapper --></div></body></html>';
?>