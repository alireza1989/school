<?php
session_start();
$cid = $_SESSION['username'];
$temp;
$upc;

//checks if option was selected from dropdown menu "Select Option"
if($_POST['search']=='')
{
	header('location: viewCart.php');
}

$con = mysqli_connect("localhost","ams_user14","Cmpt354@123","ams_g14");

//checks if submit was clicked 
if($_POST['submit'] != '')
{
	$basket = mysqli_query($con,"SELECT qty FROM Basket WHERE cid = '$cid'");
	$numrows = mysqli_num_rows($basket);

        //checks if edit was selected from the drop down menu
        if($_POST['search']=="edit")
	{
                for($i=0;$i<$numrows;$i++)
		{
                        $temp = $_POST['qty'.$i];
                        $upc = $_POST[$i];

                        //checks to see if quantity was entered
			if($temp != '')
			{      
				mysqli_query($con, "UPDATE Basket SET qty = '$temp' WHERE cid = '$cid' AND upc = $upc");
				if($temp == 0)
				{
					mysqli_query($con, "DELETE FROM Basket WHERE cid = '$cid' AND upc = $upc");
				}
			}	
		}
	} 
        //checks to see if delete was selected from the drop down menu
        else if($_POST['search'] == "delete")
        {
                for($i=0;$i<$numrows;$i++)
	        {
                        $temp = $_POST['qty'.$i];
                        $upc = $_POST[$i];
	                mysqli_query($con, "DELETE FROM Basket WHERE cid = '$cid' AND upc = $upc");
		 }
         }
}
//redirects back to cart
header('location: viewCart.php');
?>
